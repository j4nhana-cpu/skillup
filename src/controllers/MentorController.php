<?php
// ============================================================
//  src/controllers/MentorController.php
// ============================================================

class MentorController
{
    public static function dashboard(): void
    {
        $mentorId = $_SESSION['user_id'];

        $stats = Database::row(
            'SELECT
                COUNT(DISTINCT c.id)                          AS total_courses,
                COALESCE(SUM(e.amount_paid), 0)               AS total_gross,
                COALESCE(SUM(rs.mentor_share), 0)             AS total_earned,
                COUNT(DISTINCT e.student_id)                  AS total_students
             FROM courses c
             LEFT JOIN enrollments e ON e.course_id = c.id AND e.status = "active"
             LEFT JOIN revenue_shares rs ON rs.enrollment_id = e.id
             WHERE c.mentor_id = ?',
            [$mentorId]
        );

        // Pendapatan per bulan (6 bulan terakhir)
        $monthlyRevenue = Database::rows(
            'SELECT DATE_FORMAT(e.enrolled_at, "%Y-%m") AS month,
                    SUM(rs.mentor_share) AS amount
             FROM enrollments e
             JOIN revenue_shares rs ON rs.enrollment_id = e.id
             JOIN courses c ON c.id = e.course_id
             WHERE c.mentor_id = ? AND e.status = "active"
               AND e.enrolled_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
             GROUP BY month ORDER BY month',
            [$mentorId]
        );

        // Kursus terpopuler
        $topCourses = Database::rows(
            'SELECT c.title, c.total_students, c.rating_avg,
                    COALESCE(SUM(rs.mentor_share), 0) AS earned
             FROM courses c
             LEFT JOIN enrollments e ON e.course_id = c.id AND e.status="active"
             LEFT JOIN revenue_shares rs ON rs.enrollment_id = e.id
             WHERE c.mentor_id = ?
             GROUP BY c.id ORDER BY c.total_students DESC LIMIT 5',
            [$mentorId]
        );

        // Ulasan terbaru
        $recentReviews = Database::rows(
            'SELECT r.rating, r.comment, r.sentiment, r.created_at,
                    u.name AS student_name, c.title AS course_title
             FROM reviews r
             JOIN users u ON u.id = r.student_id
             JOIN courses c ON c.id = r.course_id
             WHERE c.mentor_id = ? ORDER BY r.created_at DESC LIMIT 5',
            [$mentorId]
        );

        $flash = getFlash();
        include APP_ROOT . '/views/mentor/dashboard.php';
    }

    public static function myCourses(): void
    {
        $courses = Database::rows(
            'SELECT c.*,
                    (SELECT COUNT(*) FROM videos WHERE course_id=c.id) AS video_count,
                    (SELECT COUNT(*) FROM enrollments WHERE course_id=c.id AND status="active") AS student_count
             FROM courses c WHERE c.mentor_id = ? ORDER BY c.created_at DESC',
            [$_SESSION['user_id']]
        );
        $flash = getFlash();
        include APP_ROOT . '/views/mentor/courses.php';
    }

    public static function createCourse(): void
    {
        $flash = getFlash();
        include APP_ROOT . '/views/mentor/course_form.php';
    }

    public static function storeCourse(): void
    {
        Auth::verifyCsrf();

        $title    = input('title');
        $desc     = strip_tags(input('description'), '<p><br><ul><li><strong><em>');
        $price    = (float) str_replace('.', '', input('price'));
        $category = input('category');
        $level    = in_array(input('level'), ['Pemula','Menengah','Mahir']) ? input('level') : 'Pemula';

        if (!$title || $price < 0) {
            flash('error', 'Judul kursus wajib diisi dan harga tidak boleh negatif.');
            redirect('/mentor/course/create');
        }

        // Upload thumbnail
        $thumbnail = null;
        if (!empty($_FILES['thumbnail']['name'])) {
            $thumbnail = self::uploadImage($_FILES['thumbnail']);
            if (!$thumbnail) {
                flash('error', 'Gagal upload thumbnail. Format yang didukung: JPG, PNG, WEBP (maks 2MB).');
                redirect('/mentor/course/create');
            }
        }

        $id = Database::insert(
            'INSERT INTO courses (mentor_id, title, description, price, category, level, thumbnail, status)
             VALUES (?,?,?,?,?,?,?,"draft")',
            [$_SESSION['user_id'], $title, $desc, $price, $category, $level, $thumbnail]
        );

        $videoCreated = false;
        if (!empty($_FILES['video_file']['name'])) {
            $file     = $_FILES['video_file'];
            $allowed  = ['video/mp4', 'video/webm', 'video/ogg'];
            $maxBytes = 500 * 1024 * 1024;

            if (!in_array($file['type'], $allowed)) {
                flash('error', 'Format video tidak didukung. Gunakan MP4, WEBM, atau OGG.');
                redirect('/mentor/course/create');
            }
            if ($file['size'] > $maxBytes) {
                flash('error', 'Ukuran video maksimal 500MB.');
                redirect('/mentor/course/create');
            }
            if ($file['error'] !== UPLOAD_ERR_OK) {
                flash('error', 'Gagal mengupload video. Error: ' . $file['error']);
                redirect('/mentor/course/create');
            }
            try {
                require_once APP_ROOT . '/src/helpers/CloudinaryService.php';
                $videoUrl = CloudinaryService::uploadVideo($file['tmp_name'], $file['name']);
            } catch (Exception $e) {
                flash('error', 'Gagal upload video ke cloud: ' . $e->getMessage());
                redirect('/mentor/course/' . $courseId . '/video/add');
            }

            Database::insert(
    'INSERT INTO videos (course_id, title, video_url, order_num) VALUES (?,?,?,?)',
    [$id, $title . ' - Bagian 1', $videoUrl, 1]
);
            $videoCreated = true;
        }

        if ($videoCreated) {
            flash('success', 'Kursus dan video pertama berhasil dibuat!');
        } else {
            flash('success', 'Kursus berhasil dibuat! Tambahkan video sekarang.');
        }
        redirect('/mentor/course/' . $id . '/video/add');
    }

    public static function editCourse(int $id): void
    {
        $course = self::ownCourse($id);
        $videos = Database::rows('SELECT * FROM videos WHERE course_id=? ORDER BY order_num', [$id]);
        $flash  = getFlash();
        include APP_ROOT . '/views/mentor/course_form.php';
    }

    public static function updateCourse(int $id): void
    {
        Auth::verifyCsrf();
        $course = self::ownCourse($id);
        error_log('FILES: ' . print_r($_FILES, true));

        $title    = input('title');
        $desc     = input('description');
        $price = (float) preg_replace('/[^0-9]/', '', input('price'));
        $category = input('category');
        $level    = in_array(input('level'), ['Pemula','Menengah','Mahir']) ? input('level') : $course['level'];
        $status   = $course['status'];

        $thumbnail = $course['thumbnail'];
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
            error_log('Ada file thumbnail: ' . $_FILES['thumbnail']['name'] . ' error: ' . $_FILES['thumbnail']['error']);
            $new = self::uploadImage($_FILES['thumbnail']);
            error_log('Hasil upload: ' . ($new ?? 'NULL'));
            if ($new) $thumbnail = $new;
        }

        Database::query(
            'UPDATE courses SET title=?, description=?, price=?, category=?, level=?, status=?, thumbnail=?
             WHERE id=?',
            [$title, $desc, $price, $category, $level, $status, $thumbnail, $id]
        );

        flash('success', 'Kursus berhasil diperbarui.');
        redirect('/mentor/course/' . $id . '/edit');
    }

    public static function deleteCourse(int $id): void
    {
        Auth::verifyCsrf();
        $course = self::ownCourse($id);

        if ($course['status'] === 'published') {
            flash('error', 'Kursus yang sudah dipublikasikan tidak bisa dihapus.');
            redirect('/mentor/courses');
        }

        if ($course['thumbnail'] && str_starts_with($course['thumbnail'], 'uploads/thumbnails/')) {
            $thumbPath = APP_ROOT . '/public/' . $course['thumbnail'];
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
        }

        $videos = Database::rows('SELECT video_url FROM videos WHERE course_id=?', [$id]);
        foreach ($videos as $video) {
            if (str_starts_with($video['video_url'], '/uploads/videos/')) {
                $videoPath = APP_ROOT . '/public' . $video['video_url'];
                if (file_exists($videoPath)) {
                    unlink($videoPath);
                }
            }
        }

        Database::query('DELETE FROM courses WHERE id=?', [$id]);
        flash('success', 'Kursus berhasil dihapus.');
        redirect('/mentor/courses');
    }

    public static function showAddVideo(int $courseId): void
    {
        $course = self::ownCourse($courseId);
        $videos = Database::rows('SELECT * FROM videos WHERE course_id=? ORDER BY order_num', [$courseId]);
        $flash  = getFlash();
        include APP_ROOT . '/views/mentor/add_video.php';
    }

public static function addVideo(int $courseId): void
{
    Auth::verifyCsrf();
    set_time_limit(0);
    ini_set('memory_limit', '512M');

    $course    = self::ownCourse($courseId);
    $title     = input('title');


    if (!$title) {
        flash('error', 'Judul video wajib diisi.');
        redirect('/mentor/course/' . $courseId . '/video/add');
    }

    if (empty($_FILES['video_file']['name'])) {
        flash('error', 'File video wajib diupload.');
        redirect('/mentor/course/' . $courseId . '/video/add');
    }

    $file     = $_FILES['video_file'];
    $allowed  = ['video/mp4', 'video/webm', 'video/ogg'];
    $maxBytes = 500 * 1024 * 1024;

    if (!in_array($file['type'], $allowed)) {
        flash('error', 'Format video tidak didukung. Gunakan MP4 atau WEBM.');
        redirect('/mentor/course/' . $courseId . '/video/add');
    }
    if ($file['size'] > $maxBytes) {
        flash('error', 'Ukuran video maksimal 500MB.');
        redirect('/mentor/course/' . $courseId . '/video/add');
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        flash('error', 'Gagal mengupload video. Error: ' . $file['error']);
        redirect('/mentor/course/' . $courseId . '/video/add');
    }

try {
    require_once APP_ROOT . '/src/helpers/CloudinaryService.php';
    $videoUrl = CloudinaryService::uploadVideo($file['tmp_name'], $file['name']);
} catch (Exception $e) {
    die('<h1>ERROR: ' . $e->getMessage() . '</h1>');
}
    $lastOrder = Database::row('SELECT MAX(order_num) AS max_ord FROM videos WHERE course_id=?', [$courseId]);
    $nextOrder = ($lastOrder['max_ord'] ?? 0) + 1;

   Database::insert(
    'INSERT INTO videos (course_id, title, video_url, order_num) VALUES (?,?,?,?)',
    [$courseId, $title, $videoUrl, $nextOrder]
);
    flash('success', 'Video berhasil diupload!');
    redirect('/mentor/course/' . $courseId . '/video/add');
}

    public static function deleteVideo(int $courseId, int $videoId): void
    {
        Auth::verifyCsrf();

        $course = self::ownCourse($courseId);

        // Pastikan video milik kursus ini
        $video = Database::row('SELECT * FROM videos WHERE id=? AND course_id=?', [$videoId, $courseId]);
        if (!$video) {
            flash('error', 'Video tidak ditemukan.');
            redirect('/mentor/course/' . $courseId . '/video/add');
        }

        // Hapus file video dari server jika lokal
        if (str_starts_with($video['video_url'], '/uploads/videos/')) {
            $filePath = APP_ROOT . '/public' . $video['video_url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Hapus dari database
        Database::query('DELETE FROM videos WHERE id=?', [$videoId]);

        // Update order_num untuk video lainnya
        $remainingVideos = Database::rows('SELECT id FROM videos WHERE course_id=? ORDER BY order_num', [$courseId]);
        foreach ($remainingVideos as $idx => $v) {
            Database::query('UPDATE videos SET order_num=? WHERE id=?', [$idx + 1, $v['id']]);
        }

        flash('success', 'Video berhasil dihapus.');
        redirect('/mentor/courses');
    }

    public static function myStudents(): void
    {
        $students = Database::rows(
            'SELECT u.name, u.email, c.title AS course_title,
                    e.enrolled_at, e.amount_paid, rs.mentor_share
             FROM enrollments e
             JOIN users u ON u.id = e.student_id
             JOIN courses c ON c.id = e.course_id
             LEFT JOIN revenue_shares rs ON rs.enrollment_id = e.id
             WHERE c.mentor_id = ? AND e.status = "active"
             ORDER BY e.enrolled_at DESC',
            [$_SESSION['user_id']]
        );
        $flash = getFlash();
        include APP_ROOT . '/views/mentor/students.php';
    }

    public static function revenue(): void
    {
        $shares = Database::rows(
            'SELECT rs.*, c.title AS course_title,
                    u.name AS student_name, e.enrolled_at
             FROM revenue_shares rs
             JOIN enrollments e ON e.id = rs.enrollment_id
             JOIN courses c ON c.id = e.course_id
             JOIN users u ON u.id = e.student_id
             WHERE rs.mentor_id = ? ORDER BY rs.created_at DESC',
            [$_SESSION['user_id']]
        );

        $summary = Database::row(
            'SELECT SUM(mentor_share) AS total_earned,
                    SUM(CASE WHEN status="settled" THEN mentor_share ELSE 0 END) AS settled,
                    SUM(CASE WHEN status="pending" THEN mentor_share ELSE 0 END) AS pending
             FROM revenue_shares WHERE mentor_id=?',
            [$_SESSION['user_id']]
        );

        $payoutRequests = Database::rows(
            'SELECT * FROM mentor_payouts WHERE mentor_id = ? ORDER BY requested_at DESC',
            [$_SESSION['user_id']]
        );

        $hasPendingRequest = Database::row(
            'SELECT id FROM mentor_payouts WHERE mentor_id = ? AND status = "pending"',
            [$_SESSION['user_id']]
        );

        $flash = getFlash();
        include APP_ROOT . '/views/mentor/revenue.php';
    }

    public static function requestPayout(): void
    {
        Auth::verifyCsrf();

        $mentorId = $_SESSION['user_id'];
        
        // Cek apakah mentor sudah isi rekening
        $mentor = Database::row('SELECT bank_account FROM users WHERE id = ?', [$mentorId]);
        if (empty($mentor['bank_account'])) {
            flash('error', 'Lengkapi data rekening di profil terlebih dahulu.');
            redirect('/mentor/revenue');
        }

        // Hitung total pending revenue
        $pending = Database::row(
            'SELECT SUM(mentor_share) AS total FROM revenue_shares WHERE mentor_id = ? AND status = "pending"',
            [$mentorId]
        );

        $amount = (float)($pending['total'] ?? 0);
        if ($amount <= 0) {
            flash('error', 'Tidak ada pendapatan yang bisa dicairkan.');
            redirect('/mentor/revenue');
        }

        // Cek apakah sudah ada permintaan penarikan yang pending
        $existing = Database::row(
            'SELECT id FROM mentor_payouts WHERE mentor_id = ? AND status = "pending"',
            [$mentorId]
        );

        if ($existing) {
            flash('error', 'Kamu sudah memiliki permintaan penarikan yang menunggu persetujuan.');
            redirect('/mentor/revenue');
        }

        // Buat permintaan payout baru
        $bankInfo = self::parseBankAccount($mentor['bank_account']);

        Database::insert(
            'INSERT INTO mentor_payouts (mentor_id, amount, bank_name, account_number, status)
             VALUES (?, ?, ?, ?, \'pending\')',
            [$mentorId, $amount, $bankInfo['bank_name'], $bankInfo['account_number']]
        );

        // Update revenue_shares status menjadi pending untuk payout
        Database::query(
            'UPDATE revenue_shares SET status = "pending" WHERE mentor_id = ? AND status = "pending"',
            [$mentorId]
        );

        flash('success', 'Permintaan penarikan berhasil dibuat! Admin akan memverifikasi dalam 1-3 hari kerja.');
        redirect('/mentor/revenue');
    }

    // ── Helpers ──────────────────────────────────────────────

    private static function ownCourse(int $id): array
    {
        $course = Database::row(
            'SELECT * FROM courses WHERE id=? AND mentor_id=?',
            [$id, $_SESSION['user_id']]
        );
        if (!$course) {
            flash('error', 'Kursus tidak ditemukan atau bukan milikmu.');
            redirect('/mentor/courses');
        }
        return $course;
    }

    private static function parseBankAccount(string $account): array
    {
        $result = ['bank_name' => '', 'account_number' => trim($account)];
        if (preg_match('/^\s*([^\-|]+)\s*[-|]\s*([0-9]+)\s*$/', $account, $m)) {
            $result['bank_name'] = trim($m[1]);
            $result['account_number'] = trim($m[2]);
        }
        return $result;
    }

    private static function uploadImage(array $file): ?string
    {
        $allowed  = ['image/jpeg', 'image/png', 'image/webp'];
       $maxBytes = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowed)) return null;
        if ($file['size'] > $maxBytes) return null;
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'uploads/thumbnails/' . uniqid('thumb_', true) . '.' . strtolower($ext);
        $dest     = APP_ROOT . '/public/' . $filename;

        return move_uploaded_file($file['tmp_name'], $dest) ? $filename : null;
    }
}
