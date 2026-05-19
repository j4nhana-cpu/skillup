<?php
// ============================================================
//  src/controllers/ProfileController.php
// ============================================================

class ProfileController
{
    public static function show(): void
    {
        $user  = Database::row('SELECT * FROM users WHERE id = ?', [$_SESSION['user_id']]);
        $flash = getFlash();
        include APP_ROOT . '/views/profile/edit.php';
    }

    public static function update(): void
    {
        Auth::verifyCsrf();

        $userId = $_SESSION['user_id'];
        $name   = input('name');
        $email  = input('email');
        $oldPass= input('old_password');
        $newPass= input('new_password');
        $confirm= input('confirm_password');
        $bank    = trim(input('bank_account')) ?: null;

        if (strlen($name) < 3) {
            flash('error', 'Nama minimal 3 karakter.');
            redirect('/profile/edit');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Format email tidak valid.');
            redirect('/profile/edit');
        }

        // Cek email sudah dipakai user lain
        $existing = Database::row('SELECT id FROM users WHERE email = ? AND id != ?', [$email, $userId]);
        if ($existing) {
            flash('error', 'Email sudah digunakan akun lain.');
            redirect('/profile/edit');
        }

        // Handle foto profil
        $user = Database::row('SELECT * FROM users WHERE id = ?', [$userId]);
        $avatarPath = $user['avatar'];

        if (!empty($_FILES['avatar']['name'])) {
            $file = $_FILES['avatar'];
            
            // Validasi file
            $maxSize = 5 * 1024 * 1024; // 5MB
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $uploadDir = APP_ROOT . '/public/uploads/avatars/';
            
            if ($file['size'] > $maxSize) {
                flash('error', 'Ukuran file terlalu besar! Maksimal 5MB.');
                redirect('/profile/edit');
            }
            
            if (!in_array($file['type'], $allowedTypes)) {
                flash('error', 'Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WebP.');
                redirect('/profile/edit');
            }
            
            // Buat folder jika belum ada
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate nama file unik
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            $filepath = $uploadDir . $filename;
            
            // Hapus avatar lama jika ada
            if (!empty($user['avatar'])) {
                $oldPath = APP_ROOT . '/public/' . $user['avatar'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            // Upload file baru
            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                flash('error', 'Gagal mengupload file. Silahkan coba lagi.');
                redirect('/profile/edit');
            }
            
            $avatarPath = 'uploads/avatars/' . $filename;
        }

        // Update password jika diisi
        if ($newPass) {
            if (!password_verify($oldPass, $user['password'])) {
                flash('error', 'Password lama tidak sesuai.');
                redirect('/profile/edit');
            }
            if ($newPass !== $confirm) {
                flash('error', 'Konfirmasi password baru tidak cocok.');
                redirect('/profile/edit');
            }
            if (strlen($newPass) < 6) {
                flash('error', 'Password baru minimal 6 karakter.');
                redirect('/profile/edit');
            }
            $hashed = password_hash($newPass, PASSWORD_ALGO, ['cost' => PASSWORD_COST]);
            Database::query('UPDATE users SET name=?, email=?, avatar=?, password=?, bank_account=? WHERE id=?', [$name, $email, $avatarPath, $hashed, $bank, $userId]);
        } else {
            Database::query('UPDATE users SET name=?, email=?, avatar=?, bank_account=? WHERE id=?', [$name, $email, $avatarPath, $bank, $userId]);
        }

        // Update session
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_avatar'] = $avatarPath;

        flash('success', 'Profil berhasil diperbarui!');
        redirect('/profile/edit');
    }
}
