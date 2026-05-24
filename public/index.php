<?php
// ============================================================
//  public/index.php — Front Controller (Router Utama)
// ============================================================

// Bootstrap
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/helpers/Database.php';
require_once __DIR__ . '/../src/helpers/helpers.php';
require_once __DIR__ . '/../src/helpers/AIService.php';
require_once __DIR__ . '/../src/helpers/Payment.php';
require_once __DIR__ . '/../src/middleware/Auth.php';

// Load controllers
require_once __DIR__ . '/../src/controllers/AuthController.php';
require_once __DIR__ . '/../src/controllers/HomeController.php';
require_once __DIR__ . '/../src/controllers/StudentController.php';
require_once __DIR__ . '/../src/controllers/MentorController.php';
require_once __DIR__ . '/../src/controllers/AdminController.php';
require_once __DIR__ . '/../src/controllers/APIController.php';
require_once __DIR__ . '/../src/controllers/ProfileController.php';

// Cegah browser cache halaman yang memerlukan autentikasi
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

session_set_cookie_params([
    'lifetime' => 7200,
    'path'     => '/',
    'secure'   => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// Routing sederhana: uraikan URL path
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Strip base path supaya kompatibel dengan XAMPP (localhost/skillup/public)
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
if ($basePath !== '' && strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}
$uri = rtrim($uri, '/') ?: '/';
// ── Routes ────────────────────────────────────────────────────

// Halaman Utama
if ($uri === '/' && $method === 'GET') {
    HomeController::index();
}

// ─── Auth ─────────────────────────────────────────────────────
elseif ($uri === '/auth/login') {
    $method === 'POST' ? AuthController::login() : AuthController::showLogin();
}
elseif ($uri === '/auth/register') {
    $method === 'POST' ? AuthController::register() : AuthController::showRegister();
}
elseif ($uri === '/auth/logout') {
    AuthController::logout();
}

// ─── Student ──────────────────────────────────────────────────
elseif ($uri === '/student/dashboard') {
    Auth::requireRole('student');
    StudentController::dashboard();
}
elseif ($uri === '/student/courses') {
    StudentController::browseCourses();
}
elseif (preg_match('#^/student/course/(\d+)$#', $uri, $m)) {
    StudentController::courseDetail((int)$m[1]);
}
elseif ($uri === '/student/enroll' && $method === 'POST') {
    Auth::requireRole('student');
    StudentController::enroll();
}
elseif (preg_match('#^/student/watch/(\d+)/(\d+)$#', $uri, $m)) {
    Auth::requireRole('student');
    StudentController::watchVideo((int)$m[1], (int)$m[2]);
}
elseif ($uri === '/student/review' && $method === 'POST') {
    Auth::requireRole('student');
    StudentController::submitReview();
}
elseif ($uri === '/student/payment/finish') {
    Auth::requireRole('student');
    StudentController::paymentFinish();
}
elseif ($uri === '/student/chat') {
    Auth::requireRole('student');
    StudentController::chat();
}

// ─── Mentor ───────────────────────────────────────────────────
elseif ($uri === '/mentor/dashboard') {
    Auth::requireRole('mentor');
    MentorController::dashboard();
}
elseif ($uri === '/mentor/courses') {
    Auth::requireRole('mentor');
    MentorController::myCourses();
}
elseif ($uri === '/mentor/course/create') {
    Auth::requireRole('mentor');
    $method === 'POST' ? MentorController::storeCourse() : MentorController::createCourse();
}
elseif (preg_match('#^/mentor/course/(\d+)/edit$#', $uri, $m)) {
    Auth::requireRole('mentor');
    $method === 'POST' ? MentorController::updateCourse((int)$m[1]) : MentorController::editCourse((int)$m[1]);
}
elseif (preg_match('#^/mentor/course/(\d+)/video/add$#', $uri, $m)) {
    Auth::requireRole('mentor');
    $method === 'POST' ? MentorController::addVideo((int)$m[1]) : MentorController::showAddVideo((int)$m[1]);
}
elseif (preg_match('#^/mentor/course/(\d+)/video/(\d+)/delete$#', $uri, $m) && $method === 'POST') {
    Auth::requireRole('mentor');
    MentorController::deleteVideo((int)$m[1], (int)$m[2]);
}
elseif (preg_match('#^/mentor/course/(\d+)/delete$#', $uri, $m) && $method === 'POST') {
    Auth::requireRole('mentor');
    MentorController::deleteCourse((int)$m[1]);
}
elseif ($uri === '/mentor/students') {
    Auth::requireRole('mentor');
    MentorController::myStudents();
}
elseif ($uri === '/mentor/revenue') {
    Auth::requireRole('mentor');
    MentorController::revenue();
}
elseif ($uri === '/mentor/payout/request' && $method === 'POST') {
    Auth::requireRole('mentor');
    MentorController::requestPayout();
}

// ─── Admin ────────────────────────────────────────────────────
elseif ($uri === '/admin/dashboard') {
    Auth::requireRole('admin');
    AdminController::dashboard();
}
elseif ($uri === '/admin/users') {
    Auth::requireRole('admin');
    AdminController::users();
}
elseif ($uri === '/admin/courses') {
    Auth::requireRole('admin');
    AdminController::courses();
}
elseif (preg_match('#^/admin/course/(\d+)/review$#', $uri, $m)) {
    Auth::requireRole('admin');
    AdminController::reviewCourse((int)$m[1]);
}
elseif (preg_match('#^/admin/course/(\d+)/(approve|reject)$#', $uri, $m) && $method === 'POST') {
    Auth::requireRole('admin');
    if ($m[2] === 'approve') {
        AdminController::approveCourse((int)$m[1]);
    } else {
        AdminController::rejectCourse((int)$m[1]);
    }
}
elseif (preg_match('#^/admin/course/(\d+)/toggle$#', $uri, $m)) {
    Auth::requireRole('admin');
    AdminController::toggleCourse((int)$m[1]);
}
elseif ($uri === '/admin/reviews') {
    Auth::requireRole('admin');
    AdminController::reviews();
}
elseif ($uri === '/admin/revenue') {
    Auth::requireRole('admin');
    AdminController::revenue();
}
elseif ($uri === '/admin/payout/process' && $method === 'POST') {
    Auth::requireRole('admin');
    AdminController::processPayout();
}

elseif ($uri === '/api/check-email' && $method === 'GET') {
    $email  = trim($_GET['email'] ?? '');
    $exists = Database::row('SELECT id FROM users WHERE email = ?', [$email]);
    jsonResponse(['exists' => (bool)$exists]);
}

elseif ($uri === '/profile/edit') {
    Auth::requireLogin();
    $method === 'POST' ? ProfileController::update() : ProfileController::show();
}

// ─── API Endpoints ────────────────────────────────────────────
elseif ($uri === '/api/chat' && $method === 'POST') {
    Auth::requireLogin();
    APIController::chat();
}
elseif ($uri === '/api/payment/notification' && $method === 'POST') {
    APIController::paymentNotification();
}
elseif ($uri === '/api/courses/search' && $method === 'GET') {
    APIController::searchCourses();
}

// ─── 404 ──────────────────────────────────────────────────────
else {
    http_response_code(404);
    include APP_ROOT . '/views/layouts/404.php';
}
