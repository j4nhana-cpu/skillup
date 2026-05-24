<?php
$host = getenv('DB_HOST');
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

$pdo = new PDO("mysql:host=$host;port=$port;dbname=$name", $user, $pass);
$hash = password_hash('password123', PASSWORD_BCRYPT, ['cost' => 10]);
$stmt = $pdo->prepare("UPDATE users SET password=? WHERE id IN (1,14,15)");
$stmt->execute([$hash]);
echo "Berhasil! Password semua user sudah direset ke: password123";