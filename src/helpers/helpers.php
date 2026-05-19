<?php
// ============================================================
//  src/helpers/helpers.php — Fungsi Pembantu Global
// ============================================================

/**
 * Escape output HTML — SELALU pakai ini saat menampilkan data user
 */
function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Redirect ke URL lain
 */
function redirect(string $url): never
{
    if (str_starts_with($url, '/') && !str_starts_with($url, BASE_PATH)) {
        $url = BASE_PATH . $url;
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Format rupiah: 299000 → "Rp 299.000"
 */
function rupiah(float $amount): string
{
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format durasi video: detik → "12m 30d"
 */
function duration(int $seconds): string
{
    $m = intdiv($seconds, 60);
    $s = $seconds % 60;
    return $m > 0 ? "{$m}m {$s}d" : "{$s}d";
}

/**
 * Render bintang rating HTML
 */
function stars(float $rating, bool $small = false): string
{
    $size  = $small ? '14' : '16';
    $html  = '<span class="stars" style="color:#F59E0B;font-size:' . $size . 'px">';
    $full  = (int) $rating;
    $half  = ($rating - $full) >= 0.5;
    for ($i = 0; $i < $full; $i++)  $html .= '★';
    if ($half)                       $html .= '½';
    for ($i = $full + ($half?1:0); $i < 5; $i++) $html .= '☆';
    $html .= '</span>';
    return $html;
}

/**
 * Flash message — simpan ke session untuk ditampilkan sekali
 */
function flash(string $type, string $message): void
{
    $_SESSION['flash'][$type][] = $message;
}

/**
 * Ambil dan hapus flash messages dari session
 */
function getFlash(): array
{
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Generate kode order unik
 */
function generateOrderCode(): string
{
    return 'ORD-' . strtoupper(date('Ymd')) . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
}

/**
 * Cek apakah request adalah AJAX
 */
function isAjax(): bool
{
    return $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '' === 'XMLHttpRequest';
}

/**
 * Keluarkan JSON dan hentikan eksekusi
 */
function jsonResponse(array $data, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Ambil input POST yang sudah di-trim dan di-strip
 */
function input(string $key, string $default = ''): string
{
    return trim(strip_tags($_POST[$key] ?? $default));
}

/**
 * Buat thumbnail placeholder jika belum ada gambar
 */
function thumbnail(?string $path): string
{
    if ($path && file_exists(APP_ROOT . '/public/' . $path)) {
        return BASE_PATH . '/' . $path;
    }
    return 'https://placehold.co/640x360/6366f1/ffffff?text=SkillUp+Course';
}