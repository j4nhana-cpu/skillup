<?php $pageTitle = e($video['title']) . ' — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div style="max-width:1200px;margin:0 auto;padding:1.5rem;display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start">
  <!-- Video Player -->
  <div>
    <div style="background:#000;border-radius:var(--radius);overflow:hidden;aspect-ratio:16/9">
      <?php
      $url = $video['video_url'];
      // Deteksi YouTube embed vs video lokal
      $isYoutube = str_contains($url, 'youtube.com/embed') 
          || str_contains($url, 'youtu.be')
          || str_contains($url, 'drive.google.com');
$isLocal   = str_starts_with($url, '/uploads/videos/');
$url = $isLocal ? BASE_PATH . $url : $url;
      ?>
      <?php if ($isYoutube): ?>
<iframe src="<?= e($url) ?>?rel=0&autoplay=1" style="width:100%;height:100%;border:none" allowfullscreen allow="autoplay"></iframe>
<?php elseif ($isLocal): ?>
<video src="<?= e($url) ?>" controls style="width:100%;height:100%" controlslist="nodownload noremoteplayback">
  Browser kamu tidak mendukung video.
</video>
<?php else: ?>
<video src="<?= e($url) ?>" controls style="width:100%;height:100%" controlslist="nodownload">
  Browser kamu tidak mendukung video.
</video>
<?php endif; ?>
    </div>

    <div style="margin-top:1rem">
      <h1 style="font-size:1.25rem;font-weight:700"><?= e($video['title']) ?></h1>
      <p style="font-size:.875rem;color:var(--gray-500);margin-top:.25rem">
        Kursus: <a href="<?= BASE_PATH ?>/student/course/<?= $course['id'] ?>"><?= e($course['title']) ?></a>
      </p>
    </div>

  </div>

  <!-- Sidebar: Daftar Video -->
  <div>
    <div class="card" style="position:sticky;top:76px">
      <div class="card-header">📋 Konten Kursus</div>
      <div style="max-height:70vh;overflow-y:auto">
        <?php foreach ($allVids as $idx => $v): ?>
        <a href="<?= BASE_PATH ?>/student/watch/<?= $course['id'] ?>/<?= $v['id'] ?>"
           style="display:flex;align-items:center;gap:.75rem;padding:.65rem 1rem;border-bottom:1px solid var(--gray-100);text-decoration:none;color:inherit;<?= $v['id'] == $video['id'] ? 'background:var(--primary-l);' : '' ?>">
          <span style="font-size:.8rem;color:var(--gray-500);min-width:22px"><?= $idx + 1 ?>.</span>
          <div style="flex:1;min-width:0">
            <div style="font-size:.85rem;font-weight:<?= $v['id'] == $video['id'] ? '600' : '400' ?>;color:<?= $v['id'] == $video['id'] ? 'var(--primary)' : 'var(--gray-900)' ?>;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
              <?= $v['id'] == $video['id'] ? '▶ ' : '' ?><?= e($v['title']) ?>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
      <div style="padding:1rem;border-top:1px solid var(--gray-200)">
        <a href="<?= BASE_PATH ?>/student/course/<?= $course['id'] ?>" class="btn btn-outline btn-sm" style="width:100%;justify-content:center">← Kembali ke Detail</a>
      </div>
    </div>
  </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
