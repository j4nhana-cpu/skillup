<?php $pageTitle = 'Dashboard Pelajar — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
 <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <div>
      <h1 style="font-size:1.4rem;font-weight:700">Hai, <?= e($_SESSION['user_name']) ?> 👋</h1>
      <p style="color:var(--gray-500);font-size:.875rem">Selamat datang di dashboard belajarmu</p>
    </div>
    <div style="display:flex;gap:.75rem">
      <a href="<?= BASE_PATH ?>/student/courses" class="btn btn-primary btn-sm">+ Kursus Baru</a>
    </div>
  </div>
  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <?php if (empty($enrollments)): ?>
  <div class="card card-body" style="text-align:center;padding:3rem">
    <div style="font-size:3rem;margin-bottom:1rem">📚</div>
    <h3 style="font-weight:600;margin-bottom:.5rem">Belum ada kursus</h3>
    <p style="color:var(--gray-500);margin-bottom:1.5rem">Yuk mulai belajar dengan memilih kursus yang kamu minati!</p>
    <a href="<?= BASE_PATH ?>/student/courses" class="btn btn-primary">Jelajahi Kursus</a>
  </div>
  <?php else: ?>
  <h2 style="font-size:1.1rem;font-weight:600;margin-bottom:1rem">📖 Kursus Aktifku (<?= count($enrollments) ?>)</h2>
  <div class="grid-3">
    <?php foreach ($enrollments as $e): ?>
    <div class="course-card">
      <img src="<?= thumbnail($e['thumbnail']) ?>" alt="<?= e($e['title']) ?>">
      <div class="cc-body">
        <div style="margin-bottom:.4rem"><span class="badge badge-primary"><?= e($e['category']) ?></span></div>
        <div class="cc-title"><?= e($e['title']) ?></div>
        <div class="cc-mentor">👤 <?= e($e['mentor_name']) ?></div>
        <div style="font-size:.8rem;color:var(--gray-500);margin-bottom:.75rem">
          🎬 <?= $e['total_videos'] ?> video · Dibeli <?= date('d M Y', strtotime($e['enrolled_at'])) ?>
        </div>
        <div style="display:flex;gap:.5rem">
          <a href="<?= BASE_PATH ?>/student/watch/<?= $e['course_id'] ?>/<?php
            // Ambil video pertama
            $firstVid = Database::row('SELECT id FROM videos WHERE course_id=? ORDER BY order_num LIMIT 1', [$e['course_id']]);
            echo $firstVid['id'] ?? 0;
          ?>" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">▶ Lanjutkan</a>
          <a href="<?= BASE_PATH ?>/student/course/<?= $e['course_id'] ?>" class="btn btn-outline btn-sm">Detail</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
