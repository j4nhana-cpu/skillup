<?php $pageTitle = 'Tambah Video — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <div style="margin-bottom:1.5rem">
    <a href="<?= BASE_PATH ?>/mentor/courses" style="font-size:.875rem;color:var(--gray-500)">← Kembali ke Kursus Saya</a>
    <h1 style="font-size:1.4rem;font-weight:700;margin-top:.5rem">🎬 Tambah Video</h1>
    <p style="color:var(--gray-500);font-size:.875rem">Kursus: <strong><?= e($course['title']) ?></strong></p>
  </div>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <div class="grid-2" style="gap:1.5rem;align-items:start">
    <!-- Form Tambah Video -->
    <div class="card">
      <div class="card-header">➕ Video Baru</div>
      <div class="card-body" style="padding:1.5rem">
        <form method="POST" action="<?= BASE_PATH ?>/mentor/course/<?= $course['id'] ?>/video/add" enctype="multipart/form-data">
          <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">

          <div class="form-group">
            <label>Judul Video *</label>
            <input type="text" name="title" placeholder="Contoh: Instalasi & Setup Project" required maxlength="200">
          </div>

         <div class="form-group">
  <label>File Video *</label>
  <input type="file" name="video_file" accept="video/mp4,video/webm,video/ogg" style="padding:.4rem" onchange="cekVideo(this)" required>
  <small style="color:var(--gray-500);font-size:.8rem">Format: MP4, WEBM · Maks 100MB</small>
  <div id="videoMsg" style="display:none;margin-top:.35rem;font-size:.8rem;color:var(--success)">✅ Video siap diupload!</div>
</div>

          <script>
          function cekVideo(input) {
            document.getElementById('videoMsg').style.display = input.files[0] ? 'block' : 'none';
          }
          </script>

          <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
            🎬 Tambahkan Video
          </button>
        </form>
      </div>
    </div>

    <!-- Daftar Video Saat Ini -->
    <div class="card">
      <div class="card-header">📋 Video Terdaftar (<?= count($videos) ?>)</div>
      <?php if (empty($videos)): ?>
      <div class="card-body" style="text-align:center;color:var(--gray-500);padding:2rem">
        Belum ada video. Tambahkan video pertamamu!
      </div>
      <?php else: ?>
      <div style="padding:0">
        <?php foreach ($videos as $i => $v): ?>
        <div style="display:flex;align-items:center;gap:.75rem;padding:.75rem 1rem;border-bottom:1px solid var(--gray-100)">
          <span style="background:var(--primary-l);color:var(--primary);border-radius:6px;width:28px;height:28px;display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;flex-shrink:0"><?= $i+1 ?></span>
          <div style="flex:1;min-width:0">
            <div style="font-size:.9rem;font-weight:500;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($v['title']) ?></div>
            <a href="<?= BASE_PATH . $v['video_url'] ?>" target="_blank" style="font-size:.8rem;color:var(--gray-500)">Tonton Video</a>
          </div>
          <form method="POST" action="<?= BASE_PATH ?>/mentor/course/<?= $course['id'] ?>/video/<?= $v['id'] ?>/delete" style="margin:0" onsubmit="return confirm('Yakin ingin menghapus video ini?')">
            <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
            <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger);border-color:var(--danger);padding:.25rem .5rem;font-size:.75rem">🗑️ Hapus</button>
          </form>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="padding:1rem;border-top:1px solid var(--gray-200)">
        <?php if ($course['status'] !== 'published'): ?>
        <p style="font-size:.8rem;color:var(--gray-500);margin-bottom:.75rem">
          ⚠️ Kursus masih berstatus <strong><?= $course['status'] ?></strong>. Publikasi kursus dikendalikan oleh admin.
        </p>
        <a href="<?= BASE_PATH ?>/mentor/course/<?= $course['id'] ?>/edit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">
          ✏️ Edit Kursus
        </a>
        <?php else: ?>
        <div class="alert alert-success" style="margin:0">✅ Kursus sudah dipublikasi!</div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
