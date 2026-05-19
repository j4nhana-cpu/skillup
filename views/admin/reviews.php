<?php $pageTitle = 'Moderasi Ulasan — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>
<div class="page">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h1 style="font-size:1.4rem;font-weight:700">⭐ Moderasi Ulasan</h1>
    <a href="<?= BASE_PATH ?>/admin/dashboard" style="font-size:.875rem;color:var(--gray-500)">← Dashboard</a>
  </div>
  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <!-- Statistik sentimen -->
  <?php
  $sentCountPos = count(array_filter($reviews, fn($r) => $r['sentiment']==='positif'));
  $sentCountNet = count(array_filter($reviews, fn($r) => $r['sentiment']==='netral'));
  $sentCountNeg = count(array_filter($reviews, fn($r) => $r['sentiment']==='negatif'));
  $total = count($reviews) ?: 1;
  ?>
  <div class="grid-3" style="margin-bottom:1.5rem">
    <div class="stat-card" style="border-left:4px solid var(--success)">
      <div class="stat-label">😊 Positif</div>
      <div class="stat-value"><?= $sentCountPos ?></div>
      <div class="stat-sub"><?= round($sentCountPos/$total*100) ?>% dari semua ulasan</div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--gray-500)">
      <div class="stat-label">😐 Netral</div>
      <div class="stat-value"><?= $sentCountNet ?></div>
      <div class="stat-sub"><?= round($sentCountNet/$total*100) ?>% dari semua ulasan</div>
    </div>
    <div class="stat-card" style="border-left:4px solid var(--danger)">
      <div class="stat-label">😞 Negatif</div>
      <div class="stat-value"><?= $sentCountNeg ?></div>
      <div class="stat-sub"><?= round($sentCountNeg/$total*100) ?>% dari semua ulasan</div>
    </div>
  </div>

  <div class="card">
    <div class="table-wrap">
      <table>
        <thead><tr><th>Pelajar</th><th>Kursus</th><th>Rating</th><th>Komentar</th><th>Sentimen</th><th>Tanggal</th></tr></thead>
        <tbody>
        <?php foreach ($reviews as $r): ?>
        <tr>
          <td><strong><?= e($r['student_name']) ?></strong></td>
          <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.85rem"><?= e($r['course_title']) ?></td>
          <td><?= str_repeat('⭐', $r['rating']) ?></td>
          <td style="max-width:220px;font-size:.85rem"><?= e(mb_substr($r['comment'] ?? '', 0, 80)) ?><?= mb_strlen($r['comment']??'')>80?'...':'' ?></td>
          <td><span class="badge sentiment-<?= $r['sentiment'] ?>"><?= $r['sentiment'] ?></span></td>
          <td style="font-size:.8rem;color:var(--gray-500)"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
