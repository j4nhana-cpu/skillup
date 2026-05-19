<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? APP_NAME) ?></title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
<style>
/* ── Reset & Base ── */
  html { height: 100%; }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --primary: #6366f1; --primary-d: #4f46e5; --primary-l: #e0e7ff;
    --success: #10b981; --danger: #ef4444; --warning: #f59e0b;
    --gray-50:#f9fafb;  --gray-100:#f3f4f6; --gray-200:#e5e7eb;
    --gray-500:#6b7280; --gray-700:#374151; --gray-900:#111827;
    --white: #fff;
    --radius: 12px;
    --shadow: 0 1px 4px rgba(0,0,0,.08);
    --shadow-md: 0 6px 24px rgba(99,102,241,.13);
    --font: 'Segoe UI', system-ui, sans-serif;
  }
body {
  font-family: var(--font);
  background-color: #fcf8ff;
  background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.04' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C%2Fg%3E%3C/svg%3E");
  color: var(--gray-900);
  line-height: 1.6;
  height: 100%;
  display: flex;
  flex-direction: column;
}

  /* ── Navbar ── */
  .navbar {
    background: #ada9f878; /* Semi-transparent with backdrop blur */
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(99,102,241,.1);
    height: 64px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 16px rgba(99,102,241,.07);
    width: 100%;
    display: flex;
    align-items: center;
  }
  .navbar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
    width: 100%;
    max-width: none;
    margin: 0;
    padding: 0.5rem 2.5rem;
  }
  .navbar-brand {
    font-weight: 800;
    font-size: 1.4rem;
    color: var(--primary);
    letter-spacing: -.5px;
    display: flex;
    align-items: center;
    gap: .3rem;
  }
  .navbar-brand span { color: var(--gray-900); }
  .navbar-nav { display: flex; align-items: center; gap: .25rem; list-style: none; }
  .navbar-nav a {
    color: var(--gray-700);
    font-size: .875rem;
    font-weight: 500;
    padding: .4rem .85rem;
    border-radius: 8px;
    transition: background .15s, color .15s;
  }
  .navbar-nav a:hover { background: var(--primary-l); text-decoration: none; color: var(--primary); }
  .nav-right { margin-left: auto; display: flex; align-items: center; gap: .75rem; }
  .nav-list-item { list-style: none; }

  /* Profile button + dropdown */
  .profile-btn {
    display: flex; align-items: center; gap: .5rem;
    background: var(--gray-100);
    border: 1.5px solid var(--gray-200);
    border-radius: 99px;
    padding: .25rem .75rem .25rem .3rem;
    cursor: pointer; font-size: .875rem; color: var(--gray-700);
    transition: all .15s;
  }
  .profile-btn:hover { background: var(--primary-l); border-color: var(--primary); color: var(--primary); }
  .profile-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--primary-d));
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 700; flex-shrink: 0; overflow: hidden;
    box-shadow: 0 2px 8px rgba(99,102,241,.3);
  }
  .profile-name { max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: inline-block; vertical-align: middle; padding-right: .15rem; font-weight: 500; }
  .profile-dropdown {
    display: none; position: absolute; right: 0; top: calc(100% + 10px);
    background: #fff; border: 1px solid var(--gray-200);
    border-radius: 14px; box-shadow: 0 8px 32px rgba(0,0,0,.13);
    min-width: 210px; z-index: 999; overflow: hidden;
  }
  .profile-dropdown .dropdown-head { padding: .85rem 1rem; border-bottom: 1px solid var(--gray-100); background: linear-gradient(135deg, #f8f7ff, #fff); }
  .dropdown-item {
    display: flex; align-items: center; gap: .65rem;
    padding: .7rem 1rem; color: var(--gray-700); font-size: .875rem;
    text-decoration: none; transition: background .12s;
  }
  .dropdown-item:hover { background: var(--gray-50); text-decoration: none; }
  .dropdown-item.logout { color: var(--danger); border-top: 1px solid var(--gray-100); }
  .dropdown-item.logout:hover { background: #fff5f5; }

  /* ── Buttons ── */
  .btn {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem 1.25rem; border-radius: 9px; border: none;
    cursor: pointer; font-size: .875rem; font-weight: 600;
    transition: all .18s; text-decoration: none; letter-spacing: .01em;
  }
  .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-d)); color: #fff; box-shadow: 0 2px 8px rgba(99,102,241,.25); }
  .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(99,102,241,.35); text-decoration: none; color: #fff; }
  .btn-outline { background: transparent; color: var(--primary); border: 1.5px solid var(--primary); }
  .btn-outline:hover { background: var(--primary-l); text-decoration: none; }
  .btn-danger  { background: var(--danger); color: #fff; }
  .btn-danger:hover { background: #dc2626; text-decoration: none; color: #fff; }
  .btn-success { background: var(--success); color: #fff; }
  .btn-success:hover { background: #059669; text-decoration: none; color: #fff; }
  .btn-sm { padding: .3rem .8rem; font-size: .8rem; border-radius: 7px; }
  .btn-lg { padding: .75rem 2rem; font-size: 1rem; border-radius: 11px; }

  /* ── Cards ── */
  .card {
    background: var(--white); border-radius: var(--radius);
    box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden;
    border: 1px solid rgba(0,0,0,.04);
  }
  .card-body { padding: 1.5rem; }
  .card-header {
    padding: 1rem 1.5rem; border-bottom: 1px solid var(--gray-100);
    font-weight: 600; font-size: .95rem; color: var(--gray-900);
    background: linear-gradient(135deg, #fafafa, #fff);
  }

  /* ── Forms ── */
  .form-group { margin-bottom: 1.1rem; }
  label { display: block; font-size: .875rem; font-weight: 600; margin-bottom: .4rem; color: var(--gray-700); }
  input, select, textarea {
    width: 100%; padding: .6rem .9rem;
    border: 1.5px solid var(--gray-200);
    border-radius: 9px; font-size: .9rem; font-family: inherit;
    transition: border-color .15s, box-shadow .15s;
    background: var(--white); color: var(--gray-900);
  }
  input:focus, select:focus, textarea:focus {
    outline: none; border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99,102,241,.12);
  }
  textarea { resize: vertical; min-height: 90px; }

  /* ── Alerts ── */
  .alert { padding: .85rem 1.1rem; border-radius: 10px; margin-bottom: 1rem; font-size: .875rem; display: flex; align-items: flex-start; gap: .5rem; }
  .alert-success { background: #ecfdf5; color: #065f46; border-left: 4px solid var(--success); }
  .alert-error   { background: #fef2f2; color: #991b1b; border-left: 4px solid var(--danger); }
  .alert-info    { background: #eff6ff; color: #1e40af; border-left: 4px solid #3b82f6; }

  /* ── Badges ── */
  .badge { display: inline-block; padding: .2rem .65rem; border-radius: 99px; font-size: .73rem; font-weight: 600; letter-spacing: .02em; }
  .badge-primary { background: var(--primary-l); color: var(--primary-d); }
  .badge-success { background: #d1fae5; color: #065f46; }
  .badge-warning { background: #fef3c7; color: #92400e; }
  .badge-danger  { background: #fee2e2; color: #991b1b; }
  .badge-gray    { background: var(--gray-100); color: var(--gray-700); }

  /* ── Layout ── */
  .container { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }
  .page { padding: 2rem 2rem; max-width: 1100px; margin: 0 auto; min-height: calc(100vh - 200px); }
  .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
  .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
  .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }

  /* ── Stat Cards ── */
  .stat-card {
    background: var(--white); border-radius: var(--radius);
    padding: 1.4rem; box-shadow: 0 2px 12px rgba(0,0,0,.06);
    border: 1px solid rgba(0,0,0,.04);
    transition: transform .2s, box-shadow .2s;
  }
  .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
  .stat-card .stat-label { font-size: .78rem; color: var(--gray-500); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; }
  .stat-card .stat-value { font-size: 1.9rem; font-weight: 800; color: var(--gray-900); margin-top: .3rem; }
  .stat-card .stat-sub { font-size: .8rem; color: var(--gray-500); margin-top: .3rem; }
  .stat-card .stat-icon { font-size: 2rem; margin-bottom: .5rem; }

  /* ── Tables ── */
  .table-wrap { overflow-x: auto; border-radius: var(--radius); }
  table { width: 100%; border-collapse: collapse; font-size: .875rem; }
  th, td { padding: .75rem 1rem; text-align: left; border-bottom: 1px solid var(--gray-100); }
  th { background: #f8f7ff; font-weight: 700; color: var(--primary-d); font-size: .76rem; text-transform: uppercase; letter-spacing: .05em; }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #fafaff; }

  /* ── Course Cards ── */
  .course-card {
    background: var(--white); border-radius: var(--radius);
    box-shadow: 0 2px 12px rgba(0,0,0,.07); overflow: hidden;
    transition: transform .2s, box-shadow .2s;
    border: 1px solid rgba(0,0,0,.04);
  }
  .course-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(99,102,241,.15); }
  .course-card img { width: 100%; height: 165px; object-fit: cover; border-radius: 0; }
  .course-card .cc-body { padding: 1.1rem; }
  .course-card .cc-title { font-weight: 700; margin-bottom: .35rem; font-size: .95rem; color: var(--gray-900); }
  .course-card .cc-mentor { font-size: .8rem; color: var(--gray-500); margin-bottom: .5rem; }
  .course-card .cc-price { font-size: 1.1rem; font-weight: 800; color: var(--primary); }

  /* ── Sidebar layout ── */
  .layout-sidebar { display: grid; grid-template-columns: 230px 1fr; gap: 1.75rem; padding: 1.75rem 2rem; max-width: 1200px; margin: 0 auto; }
  .sidebar {
    background: var(--white); border-radius: var(--radius);
    padding: 1.1rem; box-shadow: 0 2px 12px rgba(0,0,0,.06);
    height: fit-content; position: sticky; top: 76px;
    border: 1px solid rgba(0,0,0,.04);
  }
  .sidebar-link {
    display: flex; align-items: center; gap: .65rem;
    padding: .65rem .9rem; border-radius: 9px; color: var(--gray-700);
    font-size: .875rem; font-weight: 500; margin-bottom: .2rem;
    transition: all .15s;
  }
  .sidebar-link:hover, .sidebar-link.active { background: var(--primary-l); color: var(--primary); text-decoration: none; }
  .sidebar-link.active { font-weight: 700; }
  .sidebar-section { font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; color: var(--gray-500); font-weight: 700; padding: .6rem .9rem .25rem; }

  /* ── Sentiment badges ── */
  .sentiment-positif { background: #d1fae5; color: #065f46; }
  .sentiment-netral  { background: var(--gray-100); color: var(--gray-700); }
  .sentiment-negatif { background: #fee2e2; color: #991b1b; }

  /* ── Responsive ── */
  @media (max-width: 768px) {
    .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
    .layout-sidebar { grid-template-columns: 1fr; padding: 1rem; }
    .sidebar { position: static; }
    .navbar-container { padding: 0 1rem; }
    .page { padding: 1rem; }
  }

</style>
</head>
<body>
<nav class="navbar">
  <div class="navbar-container">
  <?php if (Auth::check()): ?>
    <span class="navbar-brand">🎓 SkillUp</span>
  <?php else: ?>
    <a href="<?= BASE_PATH ?>/" class="navbar-brand">🎓 SkillUp</a>
  <?php endif; ?>
    <ul class="navbar-nav nav-right">
   
    <?php if (Auth::check()): ?>
      <?php if ($_SESSION['user_role'] === 'student'): ?>
 <?php 
  $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $bp = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
  if ($bp !== '' && strpos($currentUri, $bp) === 0) $currentUri = substr($currentUri, strlen($bp));
?>
<?php if ($currentUri !== '/profile/edit'): ?>
  <li><a href="<?= BASE_PATH ?>/student/dashboard">Dashboard</a></li>
<li><a href="<?= BASE_PATH ?>/student/chat">💬 AI Chat</a></li>
      <?php endif; ?>
      <?php elseif ($_SESSION['user_role'] === 'mentor'): ?>
<?php 
  $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $bp = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
  if ($bp !== '' && strpos($currentUri, $bp) === 0) $currentUri = substr($currentUri, strlen($bp));
?>
<?php if ($currentUri !== '/profile/edit'): ?>
    <li><a href="<?= BASE_PATH ?>/mentor/dashboard">Dashboard</a></li>
    <li><a href="<?= BASE_PATH ?>/mentor/courses">Kelola Kursus</a></li>
    <li><a href="<?= BASE_PATH ?>/mentor/students">Daftar Siswa</a></li>
    <li><a href="<?= BASE_PATH ?>/mentor/revenue">Detail Pendapatan</a></li>
      <?php endif; ?>
  <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
      <?php endif; ?>
      <!-- Profile Dropdown -->
      <li class="nav-list-item" style="position:relative">
        <button id="profileBtn" onclick="toggleDropdown()" class="profile-btn">
          <div id="profileAvatar" class="profile-avatar">
            <?php if (!empty($_SESSION['user_avatar'])): ?>
              <img src="<?= BASE_PATH ?>/<?= ltrim($_SESSION['user_avatar'], '/') ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block">
            <?php else: ?>
              <?= strtoupper(mb_substr($_SESSION['user_name'], 0, 1)) ?>
            <?php endif; ?>
          </div>
          <span class="profile-name"><?= e(explode(' ', $_SESSION['user_name'])[0]) ?></span>
          <span style="font-size:.7rem;opacity:.9">▾</span>
        </button>
        <div id="profileDropdown" class="profile-dropdown">
          <div class="dropdown-head">
            <div style="font-weight:600;font-size:.9rem"><?= e($_SESSION['user_name']) ?></div>
            <div style="font-size:.75rem;color:var(--gray-500)"><?= e($_SESSION['user_email']) ?></div>
            <span class="badge badge-primary" style="margin-top:.35rem;font-size:.7rem"><?= $_SESSION['user_role'] ?></span>
          </div>
          <a href="<?= BASE_PATH ?>/profile/edit" class="dropdown-item">
            ✏️ Edit Profil
          </a>
          <a href="<?= BASE_PATH ?>/auth/logout" class="dropdown-item logout">
            🚪 Keluar
          </a>
        </div>
      </li>
    <?php else: ?>
     <?php
  $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
  if ($basePath !== '' && strpos($currentUri, $basePath) === 0) {
      $currentUri = substr($currentUri, strlen($basePath));
  }
  $hideNav = in_array($currentUri, ['/auth/login', '/auth/register']);
?>
      <?php if (!$hideNav): ?>
    <li class="nav-list-item"><a href="<?= BASE_PATH ?>/auth/login" class="btn btn-outline btn-sm">Masuk</a></li>
      <?php endif; ?>
    <?php endif; ?>
    </ul>
  </div>
</nav>
<script>
function toggleDropdown() {
  const d = document.getElementById('profileDropdown');
  d.style.display = d.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
  if (!e.target.closest('#profileDropdown') && !e.target.closest('button[onclick]')) {
    const d = document.getElementById('profileDropdown');
    if (d) d.style.display = 'none';
  }
});
</script>
<div class="main-content">
