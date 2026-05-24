<?php
require_once '../config/config.php';
require_once '../src/helpers/Database.php';

$hash = password_hash('password123', PASSWORD_BCRYPT, ['cost' => 10]);
Database::query("UPDATE users SET password=? WHERE id IN (1,14,15)", [$hash]);
echo "Password berhasil direset! Hash: " . $hash;