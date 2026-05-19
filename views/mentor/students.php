<?php $pageTitle = 'Daftar Siswa — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <div style="margin-bottom:1.5rem">
    <a href="<?= BASE_PATH ?>/mentor/dashboard" style="font-size:.875rem;color:var(--gray-500)">← Dashboard</a>
    <h1 style="font-size:1.4rem;font-weight:700;margin-top:.5rem">👥 Daftar Siswaku</h1>
    <p style="color:var(--gray-500);font-size:.875rem"><?= count($students) ?> siswa aktif dari semua kursusmu</p>
  </div>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <div class="card">
    <?php if (empty($students)): ?>
    <div class="card-body" style="text-align:center;padding:3rem;color:var(--gray-500)">
      <div style="font-size:2.5rem;margin-bottom:1rem">👥</div>
      <p>Belum ada siswa. Publikasikan kursusmu untuk mulai menerima pelajar!</p>
    </div>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Nama Siswa</th>
            <th>Email</th>
            <th>Kursus</th>
            <th>Tanggal Daftar</th>
            <th>Harga Bayar</th>
            <th>Bagianku (70%)</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $s): ?>
        <tr>
          <td><strong><?= e($s['name']) ?></strong></td>
          <td style="font-size:.85rem;color:var(--gray-500)"><?= e($s['email']) ?></td>
          <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($s['course_title']) ?></td>
          <td style="font-size:.85rem"><?= date('d M Y', strtotime($s['enrolled_at'])) ?></td>
          <td><?= rupiah($s['amount_paid']) ?></td>
          <td style="font-weight:600;color:var(--success)"><?= rupiah($s['mentor_share'] ?? $s['amount_paid'] * 0.7) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
