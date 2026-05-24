<?php
// ============================================================
//  src/middleware/Auth.php — Proteksi Middleware Autentikasi
// ============================================================

class Auth
{
    public static function requireLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_PATH . '/auth/login');
            exit;
        }
    }

    public static function requireRole(string ...$roles): void
    {
        self::requireLogin();

        if (!in_array($_SESSION['user_role'], $roles, true)) {
            http_response_code(403);
            include APP_ROOT . '/views/layouts/403.php';
            exit;
        }
    }

    public static function login(array $user): void
    {
        session_regenerate_id(true);

        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];
        $_SESSION['user_avatar']= $user['avatar'];
        $_SESSION['logged_at']  = time();
    }

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

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

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

    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(): void
    {
        // Temporarily disabled for Railway debugging
        return;
    }
}