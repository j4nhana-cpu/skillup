<?php $pageTitle = 'Review Kursus — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
      <a href="<?= BASE_PATH ?>/admin/courses" style="font-size:.875rem;color:var(--gray-500)">← Kembali ke Moderasi</a>
      <h1 style="font-size:1.4rem;font-weight:700;margin-top:.5rem">🎬 Review Kursus</h1>
      <p style="color:var(--gray-500);font-size:.95rem"><?= e($course['title']) ?> — <?= e($course['mentor_name']) ?></p>
    </div>
    <div style="text-align:right">
      <div style="font-size:.85rem;color:var(--gray-500);margin-bottom:.4rem">Status saat ini</div>
      <?php $sc = ['published'=>'badge-success','draft'=>'badge-warning','archived'=>'badge-danger','rejected'=>'badge-danger']; ?>
      <span class="badge <?= $sc[$course['status']] ?? 'badge-gray' ?>" style="font-size:.9rem;"><?= $course['status'] ?></span>
    </div>
  </div>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <div class="grid-2" style="gap:1.5rem;align-items:flex-start">
    <div class="card">
      <div class="card-header">📘 Detail Kursus</div>
      <div class="card-body">
        <p><strong>Judul:</strong> <?= e($course['title']) ?></p>
        <p><strong>Mentor:</strong> <?= e($course['mentor_name']) ?> &middot; <?= e($course['mentor_email']) ?></p>
        <p><strong>Kategori:</strong> <?= e($course['category']) ?> | <strong>Level:</strong> <?= e($course['level']) ?></p>
        <p><strong>Harga:</strong> <?= rupiah($course['price']) ?></p>
        <div style="margin-top:1rem;line-height:1.6;color:var(--gray-700)"><?= nl2br(e($course['description'])) ?></div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">🎥 Video Preview</div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:1rem">
        <?php if (empty($videos)): ?>
        <div style="text-align:center;color:var(--gray-500);padding:2rem">Belum ada video di kursus ini.</div>
        <?php else: ?>
          <?php foreach ($videos as $video): ?>
          <div style="border:1px solid var(--gray-100);border-radius:12px;overflow:hidden;">
            <video controls style="width:100%;max-height:320px;background:#000">
              <source src="<?= BASE_PATH . e($video['video_url']) ?>" type="video/mp4">
              Browser kamu tidak mendukung pemutaran video.
            </video>
            <div style="padding:1rem">
              <div style="font-weight:600;margin-bottom:.35rem;"><?= e($video['title']) ?></div>
              <div style="font-size:.85rem;color:var(--gray-600)">Video ini dapat diputar langsung untuk peninjauan konten.</div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

<div class="card" style="margin-top:1.5rem">
    
    <div class="card-body" style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:center">
      <?php if ($course['status'] !== 'published'): ?>
      <form method="POST" action="<?= BASE_PATH ?>/admin/course/<?= $course['id'] ?>/approve" style="margin:0">
        <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
        <button type="submit" class="btn btn-success">Setujui dan Publish</button>
      </form>
      <form method="POST" action="<?= BASE_PATH ?>/admin/course/<?= $course['id'] ?>/reject" style="margin:0">
        <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak kursus ini dan arsipkan?')">Tolak dan Arsipkan</button>
      </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
