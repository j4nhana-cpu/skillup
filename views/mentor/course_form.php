<?php
$isEdit     = isset($course);
$pageTitle  = ($isEdit ? 'Edit Kursus' : 'Buat Kursus Baru') . ' — SkillUp';
$action = $isEdit ? BASE_PATH . '/mentor/course/' . $course['id'] . '/edit' : BASE_PATH . '/mentor/course/create';
?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page" style="max-width:680px">
  <div style="margin-bottom:1.5rem">
    <a href="<?= BASE_PATH ?>/mentor/courses" style="font-size:.875rem;color:var(--gray-500)">← Kembali ke Kursus Saya</a>
    <h1 style="font-size:1.4rem;font-weight:700;margin-top:.5rem"><?= $isEdit ? '✏️ Edit Kursus' : '➕ Buat Kursus Baru' ?></h1>
  </div>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <?php if ($isEdit): ?>
  <div style="display:flex;gap:.75rem;margin-bottom:1.5rem">
    <a href="<?= BASE_PATH ?>/mentor/course/<?= $course['id'] ?>/video/add" class="btn btn-outline btn-secondary">Kelola Video</a>
  </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body" style="padding:2rem">
      <form method="POST" action="<?= $action ?>" enctype="multipart/form-data">
        <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">

        <div class="form-group">
          <label>Judul Kursus *</label>
          <input type="text" name="title" value="<?= e($course['title'] ?? '') ?>" placeholder="Contoh: Laravel untuk Pemula" required maxlength="200">
        </div>

        <div class="form-group">
          <label>Deskripsi Kursus</label>
          <textarea name="description" rows="5" placeholder="Jelaskan apa yang akan dipelajari siswa..."><?= e($course['description'] ?? '') ?></textarea>
        </div>

        <div class="grid-2">
          <div class="form-group">
            <label>Harga (Rp)</label>
            <input type="number" name="price" value="<?= isset($course['price']) ? (int)$course['price'] : '' ?>" placeholder="0" min="0" step="1000">
            <small style="color:var(--gray-500);font-size:.8rem">Isi 0 untuk kursus gratis</small>
          </div>
          <div class="form-group">
            <label>Level</label>
            <select name="level">
              <?php foreach (['Pemula','Menengah','Mahir'] as $lv): ?>
              <option value="<?= $lv ?>" <?= ($course['level'] ?? 'Pemula') === $lv ? 'selected' : '' ?>><?= $lv ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Kategori</label>
          <select name="category">
            <?php $cats = ['Web Development','Mobile Development','Data Science','UI/UX Design','Digital Marketing','Bisnis & Keuangan','Bahasa','Lainnya']; ?>
            <?php foreach ($cats as $cat): ?>
            <option value="<?= $cat ?>" <?= ($course['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <?php if ($isEdit): ?>
        <div class="form-group">
          <label>Status Kursus</label>
          <div style="padding:.75rem 1rem;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:10px;">
            <?= ucfirst($course['status']) ?>
          </div>
          <small style="color:var(--gray-500);font-size:.8rem">Status kursus hanya dapat diubah oleh admin.</small>
        </div>
        <?php endif; ?>

        <?php if ($isEdit): ?>
        <div class="form-group" style="margin-bottom:1rem;padding:1rem;background:var(--gray-50);border:1px solid var(--gray-200);border-radius:12px">
          <label style="font-weight:600;margin-bottom:.5rem;display:block">Video Terdaftar</label>
          <?php if (empty($videos)): ?>
            <div style="color:var(--gray-500);font-size:.9rem">Belum ada video untuk kursus ini.</div>
          <?php else: ?>
            <?php foreach ($videos as $vid): ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:.65rem 0;border-bottom:1px solid var(--gray-200)">
              <div style="min-width:0">
                <div style="font-size:.9rem;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= e($vid['title']) ?></div>
              </div>
              <span style="font-size:.8rem;color:var(--gray-600">#<?= $vid['order_num'] ?></span>
            </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="form-group">
          <label>Thumbnail Kursus</label>
          <?php if ($isEdit && $course['thumbnail']): ?>
          <img id="thumbnailPreview" src="<?= thumbnail($course['thumbnail']) ?>" style="width:100%;max-height:260px;object-fit:contain;border-radius:10px;margin-bottom:.75rem">
          <?php else: ?>
          <img id="thumbnailPreview" style="display:none;width:100%;max-height:260px;object-fit:contain;border-radius:10px;margin-bottom:.75rem">
          <?php endif; ?>
          <input type="file" name="thumbnail" id="thumbnailInput" accept="image/jpeg,image/png,image/webp" style="padding:.4rem" onchange="cekGambar(this)">
          <small style="color:var(--gray-500);font-size:.8rem">JPG, PNG, WEBP · Maks 5MB</small>
          <div id="thumbnailMsg" style="display:none;margin-top:.35rem;font-size:.8rem;color:var(--success)">✅ Gambar siap diupload!</div>
        </div>

        <?php if (!$isEdit): ?>
        <div class="form-group" style="margin-top:1rem">
          <label>Video Pertama (opsional)</label>
          <input type="file" name="video_file" id="videoInput" accept="video/mp4,video/webm,video/ogg" style="padding:.4rem" onchange="cekVideo(this)">
          <small style="color:var(--gray-500);font-size:.8rem">MP4, WEBM, OGG · Maks 100MB</small>
          <div id="videoMsg" style="display:none;margin-top:.35rem;font-size:.8rem;color:var(--success)">✅ Video siap diupload!</div>
        </div>

        <?php endif; ?>

        <div style="display:flex;gap:.75rem;margin-top:1rem">
          <button type="submit" class="btn btn-primary"><?= $isEdit ? '💾 Simpan Perubahan' : '🚀 Buat Kursus' ?></button>
          <a href="<?= BASE_PATH ?>/mentor/courses" class="btn btn-outline">Batal</a>
        </div>
        <script>
        function cekGambar(input) {
          const msg = document.getElementById('thumbnailMsg');
          const preview = document.getElementById('thumbnailPreview');
          
          if (input.files[0]) {
            msg.style.display = 'block';
            const reader = new FileReader();
            reader.onload = function(e) {
              preview.src = e.target.result;
              preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
          } else {
            msg.style.display = 'none';
            preview.style.display = 'none';
          }
        }
        function cekVideo(input) {
          const msg = document.getElementById('videoMsg');
          if (!msg) return;
          msg.style.display = input.files[0] ? 'block' : 'none';
        }
        </script>
      </form>
    </div>
  </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
