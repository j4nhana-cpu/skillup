<?php $pageTitle = 'Laporan Revenue — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>
<div class="page">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h1 style="font-size:1.4rem;font-weight:700">💰 Laporan Revenue & Bagi Hasil</h1>
    <a href="<?= BASE_PATH ?>/admin/dashboard" style="font-size:.875rem;color:var(--gray-500)">← Dashboard</a>
  </div>
  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <div class="grid-3" style="margin-bottom:1.5rem">
    <div class="stat-card" style="border-left:4px solid #06b6d4">
      <div class="stat-label">Total GMV</div>
      <div class="stat-value" style="font-size:1.3rem"><?= rupiah($summary['gmv'] ?? 0) ?></div>
      <div class="stat-sub">Total penjualan bruto</div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--success)">
      <div class="stat-label">Pendapatan Platform (30%)</div>
      <div class="stat-value" style="font-size:1.3rem"><?= rupiah($summary['platform_rev'] ?? 0) ?></div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--primary)">
      <div class="stat-label">Dibayar ke Mentor (70%)</div>
      <div class="stat-value" style="font-size:1.3rem"><?= rupiah($summary['mentor_rev'] ?? 0) ?></div>
    </div>
  </div>

  <div class="card" style="margin-bottom:1.5rem">
    <div class="card-header">📌 Permintaan Penarikan Mentor</div>
    <?php if (empty($payoutRequests)): ?>
    <div class="card-body" style="text-align:center;color:var(--gray-500);padding:2rem">
      Tidak ada permintaan penarikan mentor.
    </div>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Tanggal</th><th>Mentor</th><th>Jumlah</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php foreach ($payoutRequests as $pr): ?>
        <tr>
          <td style="font-size:.8rem;color:var(--gray-500)"><?= date('d M Y H:i', strtotime($pr['requested_at'])) ?></td>
          <td><strong><?= e($pr['mentor_name']) ?></strong></td>
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
          <td>
            <?php if ($pr['status'] === 'pending'): ?>
            <form method="POST" action="<?= BASE_PATH ?>/admin/payout/process" style="display:flex;gap:.35rem">
              <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
              <input type="hidden" name="payout_id" value="<?= $pr['id'] ?>">
              <button name="action" value="approve" class="btn btn-success btn-sm" onclick="return confirm('Verifikasi penarikan ini?')">✅ Verifikasi</button>
              <button name="action" value="reject" class="btn btn-danger btn-sm" onclick="return confirm('Tolak permintaan penarikan ini?')">❌ Tolak</button>
            </form>
            <?php else: ?>
            <span style="font-size:.8rem;color:var(--gray-500)">Sudah diproses</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <div class="card">
    <div class="card-header">📋 Riwayat Bagi Hasil (50 Terbaru)</div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Tanggal</th><th>Mentor</th><th>Rekening</th><th>Keterangan Kursus</th><th>Jumlah</th><th>Pendapatan Platform</th><th>Pendapatan Mentor</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php foreach ($shares as $s): ?>
        <tr>
          <td style="font-size:.8rem;color:var(--gray-500)"><?= date('d M Y', strtotime($s['created_at'])) ?></td>
          <td><strong><?= e($s['mentor_name']) ?></strong></td>
          <td style="font-size:.8rem">
  <span style="font-weight:600"><?= e($pr['bank_name'] ?? '-') ?></span><br>
  <?= e($pr['account_number'] ?? '-') ?>
</td>
          <td style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.85rem"><?= e($s['course_title']) ?></td>
          <td><?= rupiah($s['gross_amount']) ?></td>
          <td style="color:var(--primary);font-weight:500"><?= rupiah($s['platform_cut']) ?></td>
          <td style="color:var(--success);font-weight:500"><?= rupiah($s['mentor_share']) ?></td>
          <td><span class="badge <?= $s['status']==='settled'?'badge-success':'badge-warning' ?>"><?= $s['status'] ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
