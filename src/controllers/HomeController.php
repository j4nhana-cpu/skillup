<?php
// ============================================================
//  src/controllers/HomeController.php
// ============================================================

class HomeController
{
    
 public static function index(): void
    {
        // Redirect ke dashboard sesuai role jika sudah login
        if (Auth::check()) {
            $dest = match($_SESSION['user_role']) {
                'mentor' => '/mentor/dashboard',
                'admin'  => '/admin/dashboard',
                default  => '/student/dashboard',
            };
            redirect($dest);
        }

        $courses = Database::rows(
            'SELECT c.*, u.name AS mentor_name
             FROM courses c
             JOIN users u ON u.id = c.mentor_id
             WHERE c.status = "published"
             ORDER BY c.total_students DESC
             LIMIT 8'
        );
        

        $stats = Database::row(
            'SELECT
                (SELECT COUNT(*) FROM users WHERE role="student") AS total_students,
                (SELECT COUNT(*) FROM users WHERE role="mentor")  AS total_mentors,
                (SELECT COUNT(*) FROM courses WHERE status="published") AS total_courses,
                (SELECT COUNT(*) FROM enrollments WHERE status="active") AS total_enrollments'
        );

        $flash = getFlash();
        include APP_ROOT . '/views/home.php';
    }
}
