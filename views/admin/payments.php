<?php $pageTitle = 'Konfirmasi Pembayaran — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h1 style="font-size:1.4rem;font-weight:700">💳 Konfirmasi Pembayaran</h1>
    <a href="<?= BASE_PATH ?>/admin/dashboard" style="font-size:.875rem;color:var(--gray-500)">← Dashboard</a>
  </div>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <div class="card">
    <?php if (empty($payments)): ?>
    <div class="card-body" style="text-align:center;padding:3rem;color:var(--gray-500)">
      <div style="font-size:2.5rem;margin-bottom:1rem">📭</div>
      <p>Belum ada bukti pembayaran yang masuk.</p>
    </div>
    <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Pelajar</th>
            <th>Kursus</th>
            <th>Jumlah</th>
            <th>Bukti Transfer</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($payments as $p): ?>
        <tr>
          <td>
            <strong><?= e($p['student_name']) ?></strong><br>
            <span style="font-size:.75rem;color:var(--gray-500)"><?= e($p['student_email']) ?></span>
          </td>
          <td style="max-width:160px;font-size:.875rem"><?= e($p['course_title']) ?></td>
          <td style="font-weight:600"><?= rupiah($p['amount_paid']) ?></td>
          <td>
            <?php if ($p['bukti_transfer']): ?>
            <a href="<?= BASE_PATH ?>/<?= e($p['bukti_transfer']) ?>" target="_blank" class="btn btn-outline btn-sm">
              🖼 Lihat Bukti
            </a>
            <?php endif; ?>
          </td>
          <td style="font-size:.8rem;color:var(--gray-500)"><?= date('d M Y H:i', strtotime($p['enrolled_at'])) ?></td>
          <td>
            <?php $sc = ['pending'=>'badge-warning','active'=>'badge-success','expired'=>'badge-danger']; ?>
            <span class="badge <?= $sc[$p['status']] ?? 'badge-gray' ?>"><?= $p['status'] ?></span>
          </td>
          <td>
            <?php if ($p['status'] === 'pending'): ?>
            <form method="POST" action="<?= BASE_PATH ?>/admin/payment/confirm" style="display:flex;gap:.35rem">
              <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
              <input type="hidden" name="enrollment_id" value="<?= $p['id'] ?>">
              <button name="action" value="approve" class="btn btn-success btn-sm"
                onclick="return confirm('Konfirmasi pembayaran ini?')">✅ Setujui</button>
              <button name="action" value="reject" class="btn btn-danger btn-sm"
                onclick="return confirm('Tolak pembayaran ini?')">❌ Tolak</button>
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
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>