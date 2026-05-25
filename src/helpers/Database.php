<?php
// ============================================================
//  src/helpers/Database.php — Koneksi Database (PDO Singleton)
// ============================================================

class Database
{
    private static ?PDO $instance = null;

    /**
     * Ambil koneksi PDO (dibuat sekali, dipakai ulang)
     */
    public static function connect(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                DB_HOST, DB_PORT, DB_NAME
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,  // prepared statement nyata
            ];

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Jangan tampilkan detail error di production
                http_response_code(500);
                die(IS_PRODUCTION
                    ? '<h1>503 Service Unavailable</h1>'
                    : '<h1>Database Error: ' . htmlspecialchars($e->getMessage()) . '</h1>'
                );
            }
        }

        return self::$instance;
    }

    /**
     * Shortcut: jalankan query dengan parameter, kembalikan PDOStatement
     */
   public static function query(string $sql, array $params = []): PDOStatement
{
    $stmt = self::connect()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}
    /**
     * Ambil satu baris
     */
    public static function row(string $sql, array $params = []): ?array
    {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    /**
     * Ambil semua baris
     */
    public static function rows(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    /**
     * Insert dan kembalikan ID baru
     */
    public static function insert(string $sql, array $params = []): string
    {
        self::query($sql, $params);
        return self::connect()->lastInsertId();
    }

    /**
     * Pastikan tabel mentor_payouts ada.
     */
    public static function ensureMentorPayoutsTable(): void
    {
        self::query(
            'CREATE TABLE IF NOT EXISTS mentor_payouts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                mentor_id INT UNSIGNED NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                bank_name VARCHAR(50) NOT NULL,
                account_name VARCHAR(100) NOT NULL,
                account_number VARCHAR(30) NOT NULL,
                status ENUM("pending","processed","rejected") DEFAULT "pending",
                notes TEXT DEFAULT NULL,
                requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                processed_at TIMESTAMP NULL DEFAULT NULL,
                FOREIGN KEY (mentor_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

   
}
