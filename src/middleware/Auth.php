<?php
// ============================================================
//  src/middleware/Auth.php — Proteksi Middleware Autentikasi
// ============================================================

class Auth
{
    /**
     * Pastikan user sudah login. Jika belum, redirect ke login.
     */
    public static function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_PATH . '/auth/login');
            exit;
        }
    }

    /**
     * Pastikan user memiliki role tertentu.
     * Jika tidak, tampilkan 403.
     */
    public static function requireRole(string ...$roles): void
    {
        self::requireLogin();

        if (!in_array($_SESSION['user_role'], $roles, true)) {
            http_response_code(403);
            include APP_ROOT . '/views/layouts/403.php';
            exit;
        }
    }

    /**
     * Simpan data user ke session setelah login berhasil
     */
    public static function login(array $user): void
    {
        session_regenerate_id(true); // cegah session fixation

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];
        $_SESSION['user_avatar']= $user['avatar'];
        $_SESSION['logged_at']  = time();
    }

    /**
     * Hapus session (logout)
     */
    public static function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }

        session_destroy();
    }

    /**
     * Cek apakah user sudah login (boolean)
     */
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Ambil data user yang sedang login
     */
    public static function user(): ?array
    {
        if (!self::check()) return null;

        return [
            'id'     => $_SESSION['user_id'],
            'name'   => $_SESSION['user_name'],
            'email'  => $_SESSION['user_email'],
            'role'   => $_SESSION['user_role'],
            'avatar' => $_SESSION['user_avatar'],
        ];
    }

    /**
     * CSRF Token — buat token unik per session
     */
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validasi CSRF token dari form POST
     */
    public static function verifyCsrf(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(419);
            die('<h1>419 - CSRF Token Mismatch</h1>');
        }
    }
}
