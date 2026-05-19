<?php
// ============================================================
//  config/config.php — Konfigurasi Utama Aplikasi
// ============================================================

// ── Deteksi environment ─────────────────────────────────────
define('IS_PRODUCTION', getenv('APP_ENV') === 'production');

// ── Database ────────────────────────────────────────────────
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'skillup_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// ── Aplikasi ────────────────────────────────────────────────
define('APP_NAME', 'SkillUp');
define('APP_URL',  getenv('APP_URL') ?: 'http://localhost/skillup/public');
define('APP_ROOT', dirname(__DIR__));

// ── Keamanan ────────────────────────────────────────────────
define('SESSION_SECRET', getenv('SESSION_SECRET') ?: 'skillup-secret-2024-change-this');
define('PASSWORD_ALGO',  PASSWORD_BCRYPT);
define('PASSWORD_COST',  12);

// ── Bagi Hasil ──────────────────────────────────────────────
define('PLATFORM_FEE_PERCENT', 30);
define('MENTOR_SHARE_PERCENT', 70);

// ── Midtrans Payment Gateway ─────────────────────────────────
define('MIDTRANS_MERCHANT_ID',    getenv('MIDTRANS_MERCHANT_ID') ?: '');
define('MIDTRANS_SERVER_KEY',     getenv('MIDTRANS_SERVER_KEY')  ?: '');
define('MIDTRANS_CLIENT_KEY',     getenv('MIDTRANS_CLIENT_KEY')  ?: '');
define('MIDTRANS_IS_PRODUCTION',  false);

// ── Base Path ────────────────────────────────────────────────
define('BASE_PATH', '/skillup/public');

// ── Timezone & Locale ────────────────────────────────────────
date_default_timezone_set('Asia/Jakarta');

// ── Pengaturan Error ─────────────────────────────────────────
if (IS_PRODUCTION) {
    error_reporting(0);
    ini_set('display_errors', '0');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}