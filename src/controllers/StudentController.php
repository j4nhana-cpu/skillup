<?php
// ============================================================
//  src/controllers/StudentController.php
// ============================================================

class StudentController
{
    public static function dashboard(): void
    {
        $userId = $_SESSION['user_id'];


        $enrollments = Database::rows(
            'SELECT e.*, c.title, c.thumbnail, c.category, u.name AS mentor_name,
                    (SELECT COUNT(*) FROM videos WHERE course_id = c.id) AS total_videos
             FROM enrollments e
             JOIN courses c ON c.id = e.course_id
             JOIN users u   ON u.id = c.mentor_id
             WHERE e.student_id = ? AND e.status = "active"
             ORDER BY e.enrolled_at DESC',
            [$userId]
        );

        $flash = getFlash();
        include APP_ROOT . '/views/student/dashboard.php';
    }

    public static function browseCourses(): void
    {
        $search   = trim($_GET['q'] ?? '');
        $category = trim($_GET['category'] ?? '');
        $level    = trim($_GET['level'] ?? '');

        $sql    = 'SELECT c.*, u.name AS mentor_name
                   FROM courses c JOIN users u ON u.id = c.mentor_id
                   WHERE c.status = "published"';
        $params = [];

        if ($search) {
            $sql .= ' AND (c.title LIKE ? OR c.description LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if ($category) { $sql .= ' AND c.category = ?'; $params[] = $category; }
        if ($level)    { $sql .= ' AND c.level = ?';    $params[] = $level;    }

        // Hide courses that the current student already purchased/enrolled in
        if (Auth::check()) {
        $sql .= ' AND c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ? AND status = "active")';
        $params[] = $_SESSION['user_id'];
}
        $sql .= ' ORDER BY c.total_students DESC';

        $courses    = Database::rows($sql, $params);
        $categories = Database::rows('SELECT DISTINCT category FROM courses WHERE status="published" ORDER BY category');
        $flash      = getFlash();

        include APP_ROOT . '/views/student/courses.php';
    }

    public static function courseDetail(int $courseId): void
    {
        $course = Database::row(
            'SELECT c.*, u.name AS mentor_name, u.id AS mentor_user_id
             FROM courses c JOIN users u ON u.id = c.mentor_id
             WHERE c.id = ? AND c.status = "published"',
            [$courseId]
        );

        if (!$course) { http_response_code(404); include APP_ROOT . '/views/layouts/404.php'; return; }

        $videos = Database::rows(
            'SELECT * FROM videos WHERE course_id = ? ORDER BY order_num',
            [$courseId]
        );

        $reviews = Database::rows(
            'SELECT r.*, u.name AS student_name FROM reviews r
             JOIN users u ON u.id = r.student_id
             WHERE r.course_id = ? ORDER BY r.created_at DESC LIMIT 10',
            [$courseId]
        );

        $isEnrolled = false;
        $enrollmentExpiresAt = null;
        $userReview = null;
        if (Auth::check()) {
            $enrollment = Database::row(
                'SELECT * FROM enrollments WHERE student_id=? AND course_id=? AND status="active"',
                [$_SESSION['user_id'], $courseId]
            );
            if ($enrollment) {
                $isEnrolled = true;
                $enrollmentExpiresAt = $enrollment['expires_at'] ?? null;
            }

            $userReview = Database::row(
                'SELECT * FROM reviews WHERE student_id=? AND course_id=?',
                [$_SESSION['user_id'], $courseId]
            );
        }
        include APP_ROOT . '/views/student/course_detail.php';
    }

    public static function enroll(): void
    {
        Auth::verifyCsrf();

        $courseId = (int) ($_POST['course_id'] ?? 0);
        $userId   = $_SESSION['user_id'];

        $course = Database::row('SELECT * FROM courses WHERE id = ? AND status = "published"', [$courseId]);
        if (!$course) { flash('error', 'Kursus tidak ditemukan.'); redirect('/student/courses'); }

       // Cek sudah enrolled dengan status active?
$existingEnroll = Database::row(
    'SELECT id, status FROM enrollments WHERE student_id=? AND course_id=?',
    [$userId, $courseId]
);

if ($existingEnroll) {
    if ($existingEnroll['status'] === 'active') {
        flash('info', 'Kamu sudah terdaftar di kursus ini.');
        redirect('/student/course/' . $courseId);
    } else {
        // Hapus enrollment pending/expired supaya bisa beli ulang
        Database::query('DELETE FROM enrollments WHERE id=?', [$existingEnroll['id']]);
    }
}

        $orderCode = generateOrderCode();
        $amountToPay = $course['price'];

        // Simpan enrollment pending dulu
        $enrollId = Database::insert(
            'INSERT INTO enrollments (student_id, course_id, order_code, amount_paid, status) VALUES (?,?,?,?,"pending")',
            [$userId, $courseId, $orderCode, $amountToPay]
        );

        // Buat snap token Midtrans
        $user = Database::row('SELECT name, email FROM users WHERE id=?', [$userId]);
        $snapResult = Payment::createSnapToken([
            'code'         => $orderCode,
            'amount'       => $amountToPay,
            'course_id'    => $courseId,
            'course_title' => $course['title']
        ], [
            'name'  => $user['name'],
            'email' => $user['email']
        ]);

        if (isset($snapResult['error'])) {
            flash('error', 'Gagal membuat pembayaran: ' . $snapResult['error']);
            redirect('/student/course/' . $courseId);
        }

        // Simpan snap token di session untuk checkout
        $_SESSION['pending_order']  = $orderCode;
        $_SESSION['pending_course'] = $courseId;
        $_SESSION['pending_amount'] = $amountToPay;
        $_SESSION['snap_token']     = $snapResult['snap_token'] ?? $snapResult['token'] ?? '';

        include APP_ROOT . '/views/student/checkout.php';
        exit;

    }

    public static function watchVideo(int $courseId, int $videoId): void
    {
        $userId = $_SESSION['user_id'];

        $enrolled = Database::row(
            'SELECT id FROM enrollments WHERE student_id=? AND course_id=? AND status="active"',
            [$userId, $courseId]
        );

        if (!$enrolled) {
            flash('error', 'Kamu belum memiliki akses ke kursus ini.');
            redirect('/student/course/' . $courseId);
        }

        $video  = Database::row('SELECT * FROM videos WHERE id=? AND course_id=?', [$videoId, $courseId]);
        if (!$video) {
            flash('error', 'Video tidak ditemukan. Mungkin sudah dihapus oleh mentor.');
            redirect('/student/course/' . $courseId);
        }

        $course  = Database::row('SELECT * FROM courses WHERE id=?', [$courseId]);
        $allVids = Database::rows('SELECT * FROM videos WHERE course_id=? ORDER BY order_num', [$courseId]);

        include APP_ROOT . '/views/student/watch.php';
    }

    public static function submitReview(): void
    {
        Auth::verifyCsrf();

        $courseId = (int) input('course_id');
        $rating   = (int) input('rating');
        $comment  = input('comment');
        $userId   = $_SESSION['user_id'];

        if ($rating < 1 || $rating > 5) {
            flash('error', 'Rating harus antara 1-5.');
            redirect('/student/course/' . $courseId);
        }

        // Cek sudah enrolled?
        $enrolled = Database::row(
            'SELECT id FROM enrollments WHERE student_id=? AND course_id=? AND status="active"',
            [$userId, $courseId]
        );
        if (!$enrolled) {
            flash('error', 'Kamu harus membeli kursus untuk memberikan ulasan.');
            redirect('/student/course/' . $courseId);
        }

        // Analisis sentimen via AI
        $sentiment = AIService::analyzeSentiment($comment);

        // Insert atau update ulasan
        $existing = Database::row('SELECT id FROM reviews WHERE student_id=? AND course_id=?', [$userId, $courseId]);
        if ($existing) {
            Database::query(
                'UPDATE reviews SET rating=?, comment=?, sentiment=? WHERE id=?',
                [$rating, $comment, $sentiment, $existing['id']]
            );
        } else {
            Database::insert(
                'INSERT INTO reviews (student_id, course_id, rating, comment, sentiment) VALUES (?,?,?,?,?)',
                [$userId, $courseId, $rating, $comment, $sentiment]
            );
        }

        // Update rating avg di tabel courses
        Database::query(
            'UPDATE courses SET rating_avg = (SELECT AVG(rating) FROM reviews WHERE course_id = ?) WHERE id = ?',
            [$courseId, $courseId]
        );

        flash('success', 'Ulasan berhasil disimpan!');
        redirect('/student/course/' . $courseId);
    }

    public static function paymentFinish(): void
    {
        $orderId           = $_GET['order_id'] ?? '';
        $transactionStatus = $_GET['transaction_status'] ?? null;
        $fraudStatus       = $_GET['fraud_status'] ?? null;

        if (!$orderId) {
            flash('error', 'Order ID tidak ditemukan.');
            redirect('/student/dashboard');
        }

        $enrollment = Database::row(
            'SELECT e.*, c.title AS course_title FROM enrollments e
             JOIN courses c ON c.id = e.course_id
             WHERE e.order_code = ? AND e.student_id = ?',
            [$orderId, $_SESSION['user_id']]
        );

        if (!$enrollment) {
            flash('error', 'Order tidak ditemukan.');
            redirect('/student/dashboard');
        }

        if (!$transactionStatus || !$fraudStatus) {
            $statusData = Payment::getTransactionStatus($orderId);
            if ($statusData) {
                $transactionStatus = $statusData['transaction_status'] ?? $transactionStatus;
                $fraudStatus       = $statusData['fraud_status'] ?? $fraudStatus;
            }
        }

               if (Payment::isPaid($transactionStatus, $fraudStatus)) {
            if ($enrollment['status'] !== 'active') {
                Database::query('UPDATE enrollments SET status="active" WHERE id=?', [$enrollment['id']]);
                Database::query('UPDATE courses SET total_students = total_students + 1 WHERE id=?', [$enrollment['course_id']]);

                $exists = Database::row('SELECT id FROM revenue_shares WHERE enrollment_id=?', [$enrollment['id']]);
                if (!$exists) {
                    $gross    = $enrollment['amount_paid'];
                    $platform = round($gross * (PLATFORM_FEE_PERCENT / 100), 2);
                    $mentor   = round($gross - $platform, 2);
                    $course   = Database::row('SELECT mentor_id FROM courses WHERE id=?', [$enrollment['course_id']]);

                    Database::insert(
                        'INSERT INTO revenue_shares (enrollment_id, mentor_id, gross_amount, platform_cut, mentor_share, status)
                         VALUES (?,?,?,?,?,"pending")',
                        [$enrollment['id'], $course['mentor_id'], $gross, $platform, $mentor]
                    );
                }
            }

            unset($_SESSION['snap_token'], $_SESSION['pending_order'], $_SESSION['pending_course']);
            $firstVideo = Database::row(
                'SELECT id FROM videos WHERE course_id=? ORDER BY order_num LIMIT 1',
                [$enrollment['course_id']]
            );
            $redirectUrl = '/student/course/' . $enrollment['course_id'];
            if ($firstVideo) {
                $redirectUrl = '/student/watch/' . $enrollment['course_id'] . '/' . $firstVideo['id'];
            }
            flash('success', 'Pembayaran berhasil! Kamu sekarang dapat mengakses kursus "' . $enrollment['course_title'] . '".');
            redirect($redirectUrl);
        }

        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            Database::query('UPDATE enrollments SET status="expired" WHERE id=?', [$enrollment['id']]);
            unset($_SESSION['snap_token'], $_SESSION['pending_order'], $_SESSION['pending_course']);
            flash('error', 'Pembayaran dibatalkan atau gagal. Coba lagi atau hubungi admin.');
            redirect('/student/dashboard');
        }

        flash('info', 'Pembayaran sedang diproses. Akses kursus akan aktif setelah pembayaran selesai.');
        redirect('/student/dashboard');
    }

    public static function chat(): void
    {
        $history = Database::rows(
            'SELECT role, message, created_at FROM chat_history
             WHERE user_id = ? ORDER BY created_at ASC LIMIT 50',
            [$_SESSION['user_id']]
        );
        include APP_ROOT . '/views/student/chat.php';
    }
}
