<?php
// ============================================================
//  src/controllers/APIController.php — API Endpoints (JSON)
// ============================================================

class APIController
{
    /**
     * POST /api/chat — AI Chatbot
     */
    public static function chat(): void
    {
        $body    = json_decode(file_get_contents('php://input'), true);
        $message = trim($body['message'] ?? '');

        if (!$message) {
            jsonResponse(['error' => 'Pesan tidak boleh kosong.'], 400);
        }

        if (strlen($message) > 1000) {
            jsonResponse(['error' => 'Pesan terlalu panjang (maks 1000 karakter).'], 400);
        }

        $reply = AIService::chat($_SESSION['user_id'], $message);
        jsonResponse(['reply' => $reply]);
    }

    /**
     * POST /api/payment/notification — Webhook Midtrans
     * Midtrans mengirim notifikasi setiap ada perubahan status transaksi.
     */
    public static function paymentNotification(): void
    {
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!$payload || !Payment::verifySignature($payload)) {
            http_response_code(403);
            echo json_encode(['status' => 'invalid_signature']);
            exit;
        }

        $orderCode         = $payload['order_id'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus       = $payload['fraud_status'] ?? '';

        $enrollment = Database::row(
            'SELECT * FROM enrollments WHERE order_code = ? LIMIT 1',
            [$orderCode]
        );

        if (!$enrollment) {
            http_response_code(404);
            echo json_encode(['status' => 'order_not_found']);
            exit;
        }

        if (Payment::isPaid($transactionStatus, $fraudStatus)) {
            // Aktifkan enrollment
            Database::query(
                'UPDATE enrollments SET status="active" WHERE order_code=?',
                [$orderCode]
            );

            // Tambah jumlah siswa di kursus
            Database::query(
                'UPDATE courses SET total_students = total_students + 1 WHERE id=?',
                [$enrollment['course_id']]
            );

            // Hitung & simpan bagi hasil jika belum ada
            $exists = Database::row(
                'SELECT id FROM revenue_shares WHERE enrollment_id=?',
                [$enrollment['id']]
            );

            if (!$exists) {
                $gross    = $enrollment['amount_paid'];
                $platform = round($gross * (PLATFORM_FEE_PERCENT / 100), 2);
                $mentor   = round($gross - $platform, 2);

                $course = Database::row('SELECT mentor_id FROM courses WHERE id=?', [$enrollment['course_id']]);

                Database::insert(
                    'INSERT INTO revenue_shares (enrollment_id, mentor_id, gross_amount, platform_cut, mentor_share, status)
                     VALUES (?,?,?,?,?,"pending")',
                    [$enrollment['id'], $course['mentor_id'], $gross, $platform, $mentor]
                );
            }

        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            Database::query(
                'UPDATE enrollments SET status="expired" WHERE order_code=?',
                [$orderCode]
            );
        }

        http_response_code(200);
        echo json_encode(['status' => 'ok']);
    }

    /**
     * GET /api/courses/search — Pencarian kursus (AJAX)
     */
    public static function searchCourses(): void
    {
        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 2) {
            jsonResponse(['courses' => []]);
        }

        $sql = 'SELECT c.id, c.title, c.price, c.rating_avg, c.category, u.name AS mentor_name
             FROM courses c JOIN users u ON u.id=c.mentor_id
             WHERE c.status="published" AND (c.title LIKE ? OR c.category LIKE ?)';
        $params = ["%$q%", "%$q%"];

        if (Auth::check()) {
            $sql .= ' AND c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ? AND status = "active")';
            $params[] = $_SESSION['user_id'];
        }

        $sql .= ' LIMIT 5';

        $courses = Database::rows($sql, $params);

        jsonResponse(['courses' => $courses]);
    }
}
