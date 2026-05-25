<?php
// ============================================================
//  src/controllers/AdminController.php
// ============================================================

class AdminController
{
    public static function dashboard(): void
    {
        $stats = Database::row(
            'SELECT
                (SELECT COUNT(*) FROM users WHERE role="student") AS students,
                (SELECT COUNT(*) FROM users WHERE role="mentor")  AS mentors,
                (SELECT COUNT(*) FROM courses WHERE status="published") AS courses,
                (SELECT COUNT(*) FROM enrollments WHERE status="active") AS enrollments,
                (SELECT COALESCE(SUM(gross_amount),0) FROM revenue_shares) AS total_gmv,
                (SELECT COALESCE(SUM(platform_cut),0) FROM revenue_shares) AS platform_revenue'
        );

        $recentEnrollments = Database::rows(
            'SELECT e.enrolled_at, e.amount_paid, u.name AS student, c.title AS course
             FROM enrollments e
             JOIN users u ON u.id = e.student_id
             JOIN courses c ON c.id = e.course_id
             WHERE e.status="active" ORDER BY e.enrolled_at DESC LIMIT 10'
        );

        $flash = getFlash();
        include APP_ROOT . '/views/admin/dashboard.php';
    }

    public static function users(): void
    {
        $search = trim($_GET['q'] ?? '');
        $role   = $_GET['role'] ?? '';

        $sql    = 'SELECT id, name, email, role, is_active, created_at FROM users WHERE 1=1';
        $params = [];

        if ($search) { $sql .= ' AND (name LIKE ? OR email LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
        if ($role)   { $sql .= ' AND role = ?'; $params[] = $role; }
        $sql .= ' ORDER BY created_at DESC';

        $users = Database::rows($sql, $params);
        $flash = getFlash();
        include APP_ROOT . '/views/admin/users.php';
    }

    public static function courses(): void
    {
        $courses = Database::rows(
            'SELECT c.*, u.name AS mentor_name,
                    (SELECT COUNT(*) FROM enrollments WHERE course_id=c.id AND status="active") AS student_count
             FROM courses c JOIN users u ON u.id=c.mentor_id
             ORDER BY c.created_at DESC'
        );
        $flash = getFlash();
        include APP_ROOT . '/views/admin/courses.php';
    }

    public static function reviewCourse(int $id): void
    {
        $course = Database::row(
            'SELECT c.*, u.name AS mentor_name, u.email AS mentor_email
             FROM courses c
             JOIN users u ON u.id = c.mentor_id
             WHERE c.id = ?'
        , [$id]);

        if (!$course) {
            flash('error', 'Kursus tidak ditemukan.');
            redirect('/admin/courses');
        }

        $videos = Database::rows('SELECT * FROM videos WHERE course_id = ? ORDER BY order_num', [$id]);

        $flash = getFlash();
        include APP_ROOT . '/views/admin/course_review.php';
    }

    public static function approveCourse(int $id): void
    {
        Auth::verifyCsrf();

        $course = Database::row('SELECT * FROM courses WHERE id = ?', [$id]);
        if (!$course) {
            flash('error', 'Kursus tidak ditemukan.');
            redirect('/admin/courses');
        }

        Database::query('UPDATE courses SET status = ? WHERE id = ?', ['published', $id]);
        flash('success', 'Kursus berhasil disetujui dan dipublikasikan.');
        redirect('/admin/course/' . $id . '/review');
    }

    public static function rejectCourse(int $id): void
    {
        Auth::verifyCsrf();

        $course = Database::row('SELECT * FROM courses WHERE id = ?', [$id]);
        if (!$course) {
            flash('error', 'Kursus tidak ditemukan.');
            redirect('/admin/courses');
        }

        Database::query('UPDATE courses SET status = ? WHERE id = ?', ['archived', $id]);
        flash('info', 'Kursus ditolak dan diarsipkan.');
        redirect('/admin/courses');
    }

    public static function toggleCourse(int $id): void
    {
        $course = Database::row('SELECT * FROM courses WHERE id=?', [$id]);
        if (!$course) { flash('error', 'Kursus tidak ditemukan.'); redirect('/admin/courses'); }

        $newStatus = $course['status'] === 'published' ? 'archived' : 'published';
        Database::query('UPDATE courses SET status=? WHERE id=?', [$newStatus, $id]);

        flash('success', 'Status kursus diubah ke: ' . $newStatus);
        redirect('/admin/courses');
    }

    public static function reviews(): void
    {
        $reviews = Database::rows(
            'SELECT r.*, u.name AS student_name, c.title AS course_title
             FROM reviews r
             JOIN users u ON u.id=r.student_id
             JOIN courses c ON c.id=r.course_id
             ORDER BY r.created_at DESC'
        );
        $flash = getFlash();
        include APP_ROOT . '/views/admin/reviews.php';
    }

    public static function revenue(): void
    {
        $shares = Database::rows(
            'SELECT rs.*, c.title AS course_title, u.name AS mentor_name, u.bank_account AS mentor_bank_account
             FROM revenue_shares rs
             JOIN users u ON u.id=rs.mentor_id
             JOIN enrollments e ON e.id=rs.enrollment_id
             JOIN courses c ON c.id=e.course_id
             ORDER BY rs.created_at DESC LIMIT 50'
        );

        $summary = Database::row(
            'SELECT SUM(gross_amount) AS gmv,
                    SUM(platform_cut) AS platform_rev,
                    SUM(mentor_share) AS mentor_rev
             FROM revenue_shares'
        );

        $payoutRequests = Database::rows(
            'SELECT p.*, u.name AS mentor_name, u.bank_account AS mentor_bank_account
             FROM mentor_payouts p
             JOIN users u ON u.id = p.mentor_id
             ORDER BY p.requested_at DESC'
        );

        $flash = getFlash();
        include APP_ROOT . '/views/admin/revenue.php';
    }

    public static function processPayout(): void
    {
        Auth::verifyCsrf();

        $payoutId = (int) input('payout_id');
        $action   = input('action');

        $payout = Database::row('SELECT * FROM mentor_payouts WHERE id = ? AND status = "pending"', [$payoutId]);
        if (!$payout) {
            flash('error', 'Permintaan penarikan tidak ditemukan atau sudah diproses.');
            redirect('/admin/revenue');
        }

        if ($action === 'approve') {
            Database::query('UPDATE mentor_payouts SET status=\'processed\', processed_at=NOW(), notes=\'Disetujui oleh admin.\' WHERE id=?', [$payoutId]);
            Database::query(
                'UPDATE revenue_shares SET status=\'settled\', settled_at=NOW() WHERE mentor_id=? AND status=\'pending\'',
                [$payout['mentor_id']]
            );
            flash('success', 'Permintaan penarikan telah diverifikasi.');
        } else {
            Database::query('UPDATE mentor_payouts SET status=\'rejected\', processed_at=NOW(), notes=\'Ditolak oleh admin.\' WHERE id=?', [$payoutId]);
            flash('info', 'Permintaan penarikan telah ditolak.');
        }

        redirect('/admin/revenue');
    }
}
