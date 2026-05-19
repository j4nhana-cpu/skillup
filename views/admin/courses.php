<?php $pageTitle = 'Moderasi Kursus — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>
<div class="page">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h1 style="font-size:1.4rem;font-weight:700">📚 Moderasi Kursus</h1>
    <a href="<?= BASE_PATH ?>/admin/dashboard" style="font-size:.875rem;color:var(--gray-500)">← Dashboard</a>
  </div>
  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr><th>Judul</th><th>Mentor</th><th>Kategori</th><th>Harga</th><th>Siswa</th><th>Rating</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php foreach ($courses as $c): ?>
        <tr>
          <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-weight:600"><?= e($c['title']) ?></td>
          <td style="font-size:.875rem"><?= e($c['mentor_name']) ?></td>
          <td><span class="badge badge-primary"><?= e($c['category']) ?></span></td>
          <td><?= rupiah($c['price']) ?></td>
          <td><?= $c['student_count'] ?></td>
          <td><?= number_format($c['rating_avg'],1) ?> ⭐</td>
          <td>
            <?php $sc = ['published'=>'badge-success','draft'=>'badge-warning','archived'=>'badge-danger','rejected'=>'badge-danger']; ?>
            <span class="badge <?= $sc[$c['status']] ?? 'badge-gray' ?>"><?= $c['status'] ?></span>
          </td>
          <td style="display:flex;flex-wrap:wrap;gap:.35rem;align-items:center">
            <a href="<?= BASE_PATH ?>/admin/course/<?= $c['id'] ?>/review" class="btn btn-outline btn-sm">Review</a>
            <?php if ($c['status'] !== 'published'): ?>
            <form method="POST" action="<?= BASE_PATH ?>/admin/course/<?= $c['id'] ?>/approve" style="margin:0">
              <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
              <button type="submit" class="btn btn-success btn-sm">Setujui</button>
            </form>
            <form method="POST" action="<?= BASE_PATH ?>/admin/course/<?= $c['id'] ?>/reject" style="margin:0">
              <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak upload kursus ini?')">Tolak</button>
            </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
