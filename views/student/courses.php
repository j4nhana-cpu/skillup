<?php $pageTitle = 'Jelajahi Kursus — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <h1 style="font-size:1.4rem;font-weight:700;margin-bottom:1.25rem">🔍 Jelajahi Kursus</h1>

  <!-- Filter Bar -->
  <form method="GET" action="<?= BASE_PATH ?>/student/courses">
    <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1.5rem">
      <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari kursus..." style="max-width:280px;margin-bottom:0">
      <select name="category" style="max-width:180px;margin-bottom:0">
        <option value="">Semua Kategori</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= e($cat['category']) ?>" <?= $category === $cat['category'] ? 'selected' : '' ?>>
          <?= e($cat['category']) ?>
        </option>
        <?php endforeach; ?>
      </select>
      <select name="level" style="max-width:150px;margin-bottom:0">
        <option value="">Semua Level</option>
        <option value="Pemula"   <?= $level === 'Pemula'   ? 'selected' : '' ?>>Pemula</option>
        <option value="Menengah" <?= $level === 'Menengah' ? 'selected' : '' ?>>Menengah</option>
        <option value="Mahir"    <?= $level === 'Mahir'    ? 'selected' : '' ?>>Mahir</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm">Filter</button>
      <?php if ($search || $category || $level): ?>
      <a href="<?= BASE_PATH ?>/student/courses" class="btn btn-outline btn-sm">Reset</a>
      <?php endif; ?>
    </div>
  </form>

  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <?php if (empty($courses)): ?>
  <div style="width:100%;text-align:center;padding:3rem 1rem;color:var(--gray-500);min-height:calc(100vh - 260px);display:flex;flex-direction:column;justify-content:center;align-items:center;gap:.75rem">
    <div style="font-size:2.5rem">🔎</div>
    <p style="width:100%;max-width:680px;margin:0 auto;">Tidak ada kursus yang sesuai. Coba kata kunci lain atau periksa kembali filter Anda.</p>
  </div>
  <?php else: ?>
  <p style="font-size:.875rem;color:var(--gray-500);margin-bottom:1rem"><?= count($courses) ?> kursus ditemukan</p>
  <div class="grid-4">
    <?php foreach ($courses as $c): ?>
    <a href="<?= BASE_PATH ?>/student/course/<?= $c['id'] ?>" style="text-decoration:none;color:inherit">
      <div class="course-card">
        <img src="<?= thumbnail($c['thumbnail']) ?>" alt="<?= e($c['title']) ?>">
        <div class="cc-body">
          <div style="display:flex;gap:.35rem;flex-wrap:wrap;margin-bottom:.4rem">
            <span class="badge badge-primary"><?= e($c['category']) ?></span>
            <span class="badge badge-gray"><?= e($c['level']) ?></span>
          </div>
          <div class="cc-title"><?= e($c['title']) ?></div>
          <div class="cc-mentor">👤 <?= e($c['mentor_name']) ?></div>
          <div style="display:flex;align-items:center;justify-content:space-between;margin-top:.5rem">
            <span class="cc-price"><?= rupiah($c['price']) ?></span>
            <span style="font-size:.8rem;color:var(--gray-500)">
              <?= stars($c['rating_avg'], true) ?> <?= number_format($c['rating_avg'], 1) ?>
            </span>
          </div>
          <div style="font-size:.8rem;color:var(--gray-500);margin-top:.3rem">👥 <?= number_format($c['total_students']) ?> siswa</div>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
