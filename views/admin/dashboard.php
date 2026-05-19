<?php $pageTitle = 'Admin Dashboard — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
      <h1 style="font-size:1.4rem;font-weight:700">⚙️ Admin Panel</h1>
      <p style="color:var(--gray-500);font-size:.875rem">Selamat datang, <?= e($_SESSION['user_name']) ?></p>
    </div>
    <div style="display:flex;gap:.75rem">
      <a href="<?= BASE_PATH ?>/admin/users"   class="btn btn-outline btn-sm">👥 Users</a>
      <a href="<?= BASE_PATH ?>/admin/courses" class="btn btn-outline btn-sm">📚 Kursus</a>
      <a href="<?= BASE_PATH ?>/admin/reviews" class="btn btn-outline btn-sm">⭐ Ulasan</a>
      <a href="<?= BASE_PATH ?>/admin/revenue" class="btn btn-outline btn-sm">💰 Revenue</a>
    </div>
  </div>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <!-- Stats -->
  <div class="grid-3" style="margin-bottom:1.5rem">
    <div class="stat-card" style="border-left:4px solid var(--primary)">
      <div class="stat-icon">👥</div>
      <div class="stat-label">Total Pelajar</div>
      <div class="stat-value"><?= number_format($stats['students']) ?></div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--success)">
      <div class="stat-icon">🧑‍🏫</div>
      <div class="stat-label">Total Mentor</div>
      <div class="stat-value"><?= number_format($stats['mentors']) ?></div>
    </div>
    <div class="stat-card" style="border-left:4px solid #8b5cf6">
      <div class="stat-icon">📚</div>
      <div class="stat-label">Kursus Aktif</div>
      <div class="stat-value"><?= number_format($stats['courses']) ?></div>
    </div>
  </div>
  <div class="grid-3" style="margin-bottom:1.5rem">
    <div class="stat-card" style="border-left:4px solid var(--warning)">
      <div class="stat-icon">🛒</div>
      <div class="stat-label">Total Enrollment</div>
      <div class="stat-value"><?= number_format($stats['enrollments']) ?></div>
    </div>
    <div class="stat-card" style="border-left:4px solid #06b6d4">
      <div class="stat-icon">💵</div>
      <div class="stat-label">Gross Merchandise Value</div>
      <div class="stat-value" style="font-size:1.2rem"><?= rupiah($stats['total_gmv']) ?></div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--success)">
      <div class="stat-icon">🏦</div>
      <div class="stat-label">Pendapatan Platform (30%)</div>
      <div class="stat-value" style="font-size:1.2rem"><?= rupiah($stats['platform_revenue']) ?></div>
    </div>
  </div>

  <!-- Transaksi Terbaru -->
  <div class="card">
    <div class="card-header">🕐 Enrollment Terbaru</div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Waktu</th><th>Siswa</th><th>Kursus</th><th>Nilai</th></tr></thead>
        <tbody>
        <?php foreach ($recentEnrollments as $e): ?>
        <tr>
          <td style="font-size:.8rem;color:var(--gray-500)"><?= date('d M Y H:i', strtotime($e['enrolled_at'])) ?></td>
          <td><strong><?= e($e['student']) ?></strong></td>
          <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($e['course']) ?></td>
          <td style="font-weight:600;color:var(--success)"><?= rupiah($e['amount_paid']) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
