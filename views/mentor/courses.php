<?php $pageTitle = 'Kursus Saya — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h1 style="font-size:1.4rem;font-weight:700">📚 Kursus Saya</h1>
    <a href="<?= BASE_PATH ?>/mentor/course/create" class="btn btn-primary">+ Kursus Baru</a>
  </div>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <?php if (empty($courses)): ?>
  <div class="card card-body" style="text-align:center;padding:3rem">
    <div style="font-size:3rem;margin-bottom:1rem">📝</div>
    <h3 style="font-weight:600;margin-bottom:.5rem">Belum ada kursus</h3>
    <p style="color:var(--gray-500);margin-bottom:1.5rem">Buat kursus pertamamu dan mulai mengajar!</p>
    <a href="<?= BASE_PATH ?>/mentor/course/create" class="btn btn-primary">Buat Kursus Sekarang</a>
  </div>
  <?php else: ?>
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Judul Kursus</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Video</th>
            <th>Siswa</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($courses as $c): ?>
        <tr>
          <td>
            <div style="font-weight:600;font-size:.9rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($c['title']) ?></div>
            <div style="font-size:.75rem;color:var(--gray-500)"><?= e($c['level']) ?></div>
          </td>
          <td><span class="badge badge-primary"><?= e($c['category']) ?></span></td>
          <td><?= rupiah($c['price']) ?></td>
          <td><?= $c['video_count'] ?> video</td>
          <td><?= $c['student_count'] ?> siswa</td>
          <td><?= number_format($c['rating_avg'],1) ?> ⭐</td>
          <td>
            <?php $sc = ['published'=>'badge-success','draft'=>'badge-warning','archived'=>'badge-danger']; ?>
            <span class="badge <?= $sc[$c['status']] ?? 'badge-gray' ?>"><?= $c['status'] ?></span>
          </td>
          <td>
            <div style="display:flex;gap:.35rem;flex-wrap:wrap">
              <a href="<?= BASE_PATH ?>/mentor/course/<?= $c['id'] ?>/edit" class="btn btn-primary btn-sm">Edit</a>
              <?php if ($c['status'] !== 'published'): ?>
              <form method="POST" action="<?= BASE_PATH ?>/mentor/course/<?= $c['id'] ?>/delete" style="margin:0" onsubmit="return confirm('Yakin ingin menghapus kursus ini?')">
                <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
              </form>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
