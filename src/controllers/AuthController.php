<?php
// ============================================================
//  src/controllers/AuthController.php
// ============================================================

class AuthController
{
    public static function showLogin(): void
    {
        if (Auth::check()) redirect('/');
        $flash = getFlash();
        include APP_ROOT . '/views/auth/login.php';
    }

    public static function login(): void
    {
        Auth::verifyCsrf();

        $email    = input('email');
        $password = input('password');

        if (!$email || !$password) {
            $_SESSION['flash']['error'][] = 'Email dan password wajib diisi.';
            session_write_close();
            redirect('/auth/login');
        }

        $user = Database::row(
            'SELECT * FROM users WHERE BINARY email = ? AND is_active = 1 LIMIT 1',
            [$email]
        );

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['flash']['error'][] = 'Email atau password salah.';
            session_write_close();
            redirect('/auth/login');
        }

        Auth::login($user);
        flash('success', 'Selamat datang kembali, ' . $user['name'] . '!');

        $dest = match($user['role']) {
            'mentor' => '/mentor/dashboard',
            'admin'  => '/admin/dashboard',
            default  => '/student/dashboard',
        };

        $dest = $_SESSION['redirect_after_login'] ?? $dest;
        unset($_SESSION['redirect_after_login']);

        redirect($dest);
    }

    public static function showRegister(): void
    {
        if (Auth::check()) redirect('/');
        $flash = getFlash();
        include APP_ROOT . '/views/auth/register.php';
    }

    public static function register(): void
    {
        Auth::verifyCsrf();

        $name     = input('name');
        $email    = input('email');
        $password = input('password');
        $confirm  = input('password_confirm');
        $role     = in_array(input('role'), ['student', 'mentor']) ? input('role') : 'student';

        $errors = [];
        if (strlen($name) < 3)         $errors[] = 'Nama minimal 3 karakter.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Format email tidak valid.';
        if (strlen($password) < 6)     $errors[] = 'Password minimal 6 karakter.';
        if ($password !== $confirm)    $errors[] = 'Konfirmasi password tidak cocok.';

        if ($errors) {
            foreach ($errors as $e) flash('error', $e);
            redirect('/auth/register');
        }

        if (Database::row('SELECT id FROM users WHERE email = ?', [$email])) {
            flash('error', 'Email sudah terdaftar.');
            redirect('/auth/register');
        }

        $hashed = password_hash($password, PASSWORD_ALGO, ['cost' => PASSWORD_COST]);
        Database::insert(
            'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)',
            [$name, $email, $hashed, $role]
        );

        $newUser = Database::row('SELECT * FROM users WHERE email = ?', [$email]);
        Auth::login($newUser);

        flash('success', 'Registrasi berhasil! Selamat datang, ' . $name . '!');

        $dest = match($role) {
            'mentor' => '/mentor/dashboard',
            default  => '/student/dashboard',
        };
        redirect($dest);
    }

    public static function logout(): void
    {
        Auth::logout();
        redirect('/auth/login');
    }
}