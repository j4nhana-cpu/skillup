<?php $pageTitle = 'Dashboard Mentor — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
      <h1 style="font-size:1.4rem;font-weight:700">Dashboard Mentor 🧑‍🏫</h1>
      <p style="color:var(--gray-500);font-size:.875rem">Selamat datang, <?= e($_SESSION['user_name']) ?></p>
    </div>
  </div>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <!-- Stats -->
  <div class="grid-4" style="margin-bottom:1.5rem">
    <div class="stat-card">
      <div class="stat-icon">📚</div>
      <div class="stat-label">Total Kursus</div>
      <div class="stat-value"><?= $stats['total_courses'] ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">👥</div>
      <div class="stat-label">Total Siswa</div>
      <div class="stat-value"><?= number_format($stats['total_students']) ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">💰</div>
      <div class="stat-label">Total Pendapatanku</div>
      <div class="stat-value" style="font-size:1.25rem"><?= rupiah($stats['total_earned']) ?></div>
      <div class="stat-sub">70% dari <?= rupiah($stats['total_gross']) ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon">📊</div>
      <div class="stat-label">Bagi Hasil Platform</div>
      <div class="stat-value" style="font-size:1.1rem;color:var(--gray-500)"><?= MENTOR_SHARE_PERCENT ?>% / <?= PLATFORM_FEE_PERCENT ?>%</div>
      <div class="stat-sub">Mentor / Platform</div>
    </div>
  </div>

  <div class="grid-2" style="gap:1.5rem">
    <!-- Kursus Terpopuler -->
    <div class="card">
      <div class="card-header">🏆 Kursus Terpopuler</div>
      <div class="card-body" style="padding:0">
        <?php if (empty($topCourses)): ?>
        <p style="padding:1rem;color:var(--gray-500);font-size:.875rem">Belum ada kursus.</p>
        <?php else: ?>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Kursus</th><th>Siswa</th><th>Rating</th><th>Pendapatan</th></tr></thead>
            <tbody>
            <?php foreach ($topCourses as $c): ?>
            <tr>
              <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($c['title']) ?></td>
              <td><?= $c['total_students'] ?></td>
              <td><?= number_format($c['rating_avg'],1) ?> ⭐</td>
              <td><?= rupiah($c['earned']) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Ulasan Terbaru -->
    <div class="card">
      <div class="card-header">💬 Ulasan Terbaru</div>
      <div class="card-body">
        <?php if (empty($recentReviews)): ?>
        <p style="color:var(--gray-500);font-size:.875rem">Belum ada ulasan.</p>
        <?php else: ?>
        <?php foreach ($recentReviews as $r): ?>
        <div style="padding:.65rem 0;border-bottom:1px solid var(--gray-100)">
          <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem">
            <strong style="font-size:.85rem"><?= e($r['student_name']) ?></strong>
            <?= stars($r['rating'], true) ?>
            <span class="badge sentiment-<?= $r['sentiment'] ?>" style="font-size:.7rem"><?= $r['sentiment'] ?></span>
          </div>
          <p style="font-size:.8rem;color:var(--gray-500);margin-bottom:.2rem">📚 <?= e($r['course_title']) ?></p>
          <p style="font-size:.85rem;color:var(--gray-700)"><?= e(mb_substr($r['comment'], 0, 80)) ?><?= mb_strlen($r['comment']) > 80 ? '...' : '' ?></p>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Grafik Pendapatan Bulanan -->
  <?php if (!empty($monthlyRevenue)): ?>
  <div class="card" style="margin-top:1.5rem">
    <div class="card-header">📈 Pendapatan 6 Bulan Terakhir</div>
    <div class="card-body" style="min-height:260px">
      <canvas id="revenueChart" style="width:100%;height:100%" aria-label="Grafik Pendapatan"></canvas>
    </div>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
  <script>
  const ctx = document.getElementById('revenueChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_column($monthlyRevenue, 'month')) ?>,
      datasets: [{
        label: 'Pendapatanku (Rp)',
        data: <?= json_encode(array_map(fn($r) => (float)$r['amount'], $monthlyRevenue)) ?>,
        backgroundColor: 'rgba(99,102,241,0.7)',
        borderRadius: 6,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } }
      }
    }
  });
  </script>
  <?php endif; ?>

</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
