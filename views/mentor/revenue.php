<?php $pageTitle = 'Pendapatan — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <div style="margin-bottom:1.5rem">
    <a href="<?= BASE_PATH ?>/mentor/dashboard" style="font-size:.875rem;color:var(--gray-500)">← Dashboard</a>
    <h1 style="font-size:1.4rem;font-weight:700;margin-top:.5rem">💰 Detail Pendapatanku</h1>
  </div>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
    <div></div>
    <form method="POST" action="<?= BASE_PATH ?>/mentor/payout/request" style="margin:0">
      <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
      <button type="submit" class="btn btn-primary" style="padding:.75rem 1rem" <?= empty($summary['pending']) || $summary['pending'] <= 0 || $hasPendingRequest ? 'disabled' : '' ?>>
        🧾 Ajukan Penarikan
      </button>
    </form>
  </div>

  <!-- Summary Cards -->
  <div class="grid-3" style="margin-bottom:1.5rem">
    <div class="stat-card" style="border-left:4px solid var(--success)">
      <div class="stat-label">Total Pendapatan</div>
      <div class="stat-value"><?= rupiah($summary['total_earned'] ?? 0) ?></div>
      <div class="stat-sub">Bagian mentor (70%)</div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--primary)">
      <div class="stat-label">Sudah Dicairkan</div>
      <div class="stat-value"><?= rupiah($summary['settled'] ?? 0) ?></div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--warning)">
      <div class="stat-label">Menunggu Pencairan</div>
      <div class="stat-value"><?= rupiah($summary['pending'] ?? 0) ?></div>
    </div>
  </div>

  <!-- Cara Kerja Bagi Hasil -->
  <div class="card" style="margin-bottom:1.5rem;background:var(--primary-l);border:none">
    <div class="card-body">
      <h3 style="font-size:.95rem;font-weight:600;margin-bottom:.5rem;color:var(--primary)">ℹ️ Cara Kerja Bagi Hasil SkillUp</h3>
      <p style="font-size:.875rem;color:var(--gray-700)">
        Setiap penjualan kursus dibagi: <strong><?= MENTOR_SHARE_PERCENT ?>% untuk Mentor</strong> dan <?= PLATFORM_FEE_PERCENT ?>% untuk Platform.
        Contoh: kursus Rp 300.000 → Mentor mendapat Rp <?= number_format(300000 * MENTOR_SHARE_PERCENT / 100, 0, ',', '.') ?>.
        Pencairan dilakukan setiap bulan ke rekening yang terdaftar.
      </p>
    </div>
  </div>

  <!-- Tabel Riwayat -->
  <div class="card">
    <div class="card-header">📋 Riwayat Bagi Hasil</div>
    <?php if (empty($shares)): ?>
    <div class="card-body" style="text-align:center;color:var(--gray-500);padding:2rem">
      Belum ada riwayat pendapatan.
    </div>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Kursus</th>
            <th>Siswa</th>
            <th>Harga Jual</th>
            <th>Potongan Platform (<?= PLATFORM_FEE_PERCENT ?>%)</th>
            <th>Bagianku (<?= MENTOR_SHARE_PERCENT ?>%)</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($shares as $s): ?>
        <tr>
          <td style="font-size:.85rem"><?= date('d M Y', strtotime($s['created_at'])) ?></td>
          <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.85rem"><?= e($s['course_title']) ?></td>
          <td style="font-size:.85rem"><?= e($s['student_name']) ?></td>
          <td><?= rupiah($s['gross_amount']) ?></td>
          <td style="color:var(--danger)">-<?= rupiah($s['platform_cut']) ?></td>
          <td style="font-weight:600;color:var(--success)"><?= rupiah($s['mentor_share']) ?></td>
          <td>
            <span class="badge <?= $s['status'] === 'settled' ? 'badge-success' : 'badge-warning' ?>">
              <?= $s['status'] === 'settled' ? '✓ Cair' : '⏳ Pending' ?>
            </span>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <div class="card" style="margin-top:1.5rem">
    <div class="card-header">📌 Riwayat Permintaan Penarikan</div>
    <?php if (empty($payoutRequests)): ?>
    <div class="card-body" style="text-align:center;color:var(--gray-500);padding:2rem">
      Belum ada permintaan penarikan.
    </div>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Tanggal</th><th>Jumlah</th><th>Status</th><th>Catatan</th></tr>
        </thead>
        <tbody>
        <?php foreach ($payoutRequests as $pr): ?>
        <tr>
          <td style="font-size:.85rem"><?= date('d M Y H:i', strtotime($pr['requested_at'])) ?></td>
          <td><?= rupiah($pr['amount']) ?></td>
          <td>
            <?php if ($pr['status'] === 'pending'): ?>
              <span class="badge badge-warning">Menunggu</span>
            <?php elseif ($pr['status'] === 'processed'): ?>
              <span class="badge badge-success">Diverifikasi</span>
            <?php else: ?>
              <span class="badge badge-danger">Ditolak</span>
            <?php endif; ?>
          </td>
          <td style="font-size:.85rem;color:var(--gray-600)"><?= e($pr['notes'] ?? '-') ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
