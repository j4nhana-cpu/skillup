<?php $pageTitle = e($course['title']) . ' — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div class="page">
  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

  <div class="grid-2" style="gap:2rem;align-items:start">
    <!-- Kolom Kiri: Info Kursus -->
    <div>
      <img src="<?= thumbnail($course['thumbnail']) ?>" alt="<?= e($course['title']) ?>" style="width:100%;border-radius:var(--radius);margin-bottom:1rem">

<?php if ($course['description']): ?>
<div class="card" style="margin-bottom:1.5rem">
  <div class="card-header">📝 Deskripsi Kursus</div>
  <div class="card-body" style="font-size:.9rem;color:var(--gray-700);line-height:1.8">
    <?= nl2br(e($course['description'])) ?>
  </div>
</div>
<?php endif; ?>

      <!-- Ulasan Section -->
      <div class="card" style="margin-top:1.5rem">
        <div class="card-header">⭐ Ulasan Pelajar (<?= count($reviews) ?>)</div>
        <div class="card-body">
          <?php if (empty($reviews)): ?>
          <p style="color:var(--gray-500);font-size:.875rem">Belum ada ulasan.</p>
          <?php else: ?>
          <?php foreach ($reviews as $r): ?>
          <div style="padding:.75rem 0;border-bottom:1px solid var(--gray-200)">
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.35rem">
              <strong style="font-size:.875rem"><?= e($r['student_name']) ?></strong>
              <?= stars($r['rating']) ?>
            </div>
            <p style="font-size:.875rem;color:var(--gray-700)"><?= e($r['comment']) ?></p>
            <span style="font-size:.75rem;color:var(--gray-500)"><?= date('d M Y', strtotime($r['created_at'])) ?></span>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>

          <!-- Form Ulasan (jika enrolled) -->
          <?php if ($isEnrolled && Auth::check()): ?>
          <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--gray-200)">

            <?php if ($userReview): ?>
            <!-- Sudah ada ulasan: tampil tombol Edit -->
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem">
              <h4 style="font-weight:600">✍️ Ulasanmu</h4>
              <button onclick="toggleForm()" class="btn btn-outline btn-sm" id="editBtn">✏️ Edit Ulasan</button>
            </div>
            <?php else: ?>
            <h4 style="font-weight:600;margin-bottom:.75rem">✍️ Tulis Ulasan</h4>
            <?php endif; ?>

            <!-- Form: tersembunyi jika sudah ada ulasan, langsung tampil jika belum -->
            <div id="reviewForm" style="display:<?= $userReview ? 'none' : 'block' ?>">
              <form method="POST" action="<?= BASE_PATH ?>/student/review">
                <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">

                <div class="form-group">
                  <label>Rating</label>
                  <div id="star-container" style="display:flex;gap:.5rem">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star-btn" data-val="<?= $i ?>" onclick="setRating(<?= $i ?>)"
                      style="cursor:pointer;font-size:1.75rem;opacity:<?= ($userReview['rating'] ?? 0) >= $i ? '1' : '0.3' ?>">⭐</span>
                    <?php endfor; ?>
                  </div>
                  <input type="hidden" name="rating" id="ratingInput" value="<?= $userReview['rating'] ?? 0 ?>">
                </div>

                <div class="form-group">
                  <label>Komentar</label>
                  <textarea name="comment" rows="3" placeholder="Bagikan pengalamanmu..."><?= e($userReview['comment'] ?? '') ?></textarea>
                </div>

                <div style="display:flex;gap:.5rem">
                  <button type="submit" class="btn btn-primary btn-sm">Kirim Ulasan</button>
                  <?php if ($userReview): ?>
                  <button type="button" onclick="toggleForm()" class="btn btn-outline btn-sm">Batal</button>
                  <?php endif; ?>
                </div>
              </form>
            </div>

          </div>

          <script>
          function toggleForm() {
            const form = document.getElementById('reviewForm');
            const btn  = document.getElementById('editBtn');
            if (form.style.display === 'none') {
              form.style.display = 'block';
              btn.textContent = '✕ Batal';
            } else {
              form.style.display = 'none';
              btn.textContent = '✏️ Edit Ulasan';
            }
          }
          function setRating(val) {
            document.getElementById('ratingInput').value = val;
            document.querySelectorAll('.star-btn').forEach((s, i) => {
              s.style.opacity = i < val ? '1' : '0.3';
            });
          }
          </script>

          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Sidebar Pembelian -->
    <div>
      <div class="card" style="position:sticky;top:76px">
        <div class="card-body">
          <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:.75rem">
            <span class="badge badge-primary"><?= e($course['category']) ?></span>
            <span class="badge badge-gray"><?= e($course['level']) ?></span>
          </div>
          <h1 style="font-size:1.3rem;font-weight:700;margin-bottom:.5rem;line-height:1.3"><?= e($course['title']) ?></h1>
          <p style="font-size:.875rem;color:var(--gray-500);margin-bottom:.75rem">
            Oleh <strong><?= e($course['mentor_name']) ?></strong>
          </p>
          <div style="display:flex;align-items:center;gap:.75rem;margin-bottom.75rem">
            <?= stars($course['rating_avg']) ?>
            <span style="font-size:.9rem;color:var(--gray-500)"><?= number_format($course['rating_avg'],1) ?> · <?= $course['total_students'] ?> siswa</span>
          </div>

          <div style="font-size:2rem;font-weight:800;color:var(--primary);margin:1rem 0"><?= rupiah($course['price']) ?></div>

          <?php if ($isEnrolled): ?>
          <div class="alert alert-success">✅ Kamu sudah terdaftar di kursus ini!</div>
          <?php if (!empty($enrollmentExpiresAt)): ?>
            <?php $expires = new DateTime($enrollmentExpiresAt); $now = new DateTime(); ?>
            <div style="font-size:.9rem;margin-bottom:.75rem;color:var(--gray-700)">Akses aktif sampai <strong><?= $expires->format('d M Y') ?></strong> (<?= $expires > $now ? $now->diff($expires)->days . ' hari lagi' : 'Kedaluwarsa' ?>)</div>
          <?php endif; ?>
          <a href="<?= BASE_PATH ?>/student/watch/<?= $course['id'] ?>/<?= $videos[0]['id'] ?? 0 ?>" class="btn btn-primary" style="width:100%;justify-content:center">▶ Mulai Belajar</a>
          <?php elseif (Auth::check()): ?>
          <form method="POST" action="<?= BASE_PATH ?>/student/enroll">
            <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">
            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
  
            <button type="submit" id="buyButton" class="btn btn-primary" style="width:100%;justify-content:center;padding:.75rem">
              🛒 Beli Sekarang — <?= rupiah($course['price']) ?>
            </button>
          </form>
          <script>
            const coursePrice = <?= (int)$course['price'] ?>;
            const durationSelect = document.getElementById('durationMonths');
            const buyButton = document.getElementById('buyButton');
            function formatRupiah(value) {
              return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
            }
            function updateBuyButton() {
              const months = Number(durationSelect.value) || 1;
              buyButton.innerText = `🛒 Beli Sekarang — ${formatRupiah(coursePrice * months)} (${months} bulan)`;
            }
            durationSelect.addEventListener('change', updateBuyButton);
            updateBuyButton();
          </script>
          <?php else: ?>
          <a href="<?= BASE_PATH ?>/auth/login" class="btn btn-primary" style="width:100%;justify-content:center;padding:.75rem">
            Masuk untuk Membeli
          </a>
          <?php endif; ?>

          <!-- Daftar Video -->
          <div style="margin-top:1.25rem">
            <h3 style="font-size:.9rem;font-weight:600;margin-bottom:.75rem">📋 Konten Kursus (<?= count($videos) ?> video)</h3>
            <?php foreach ($videos as $idx => $v): ?>
            <div style="display:flex;align-items:center;gap:.75rem;padding:.5rem 0;border-bottom:1px solid var(--gray-100)">
              <span style="font-size:.8rem;color:var(--gray-500);min-width:20px"><?= $idx + 1 ?>.</span>
              <?php if ($isEnrolled): ?>
                <a href="<?= BASE_PATH ?>/student/watch/<?= $course['id'] ?>/<?= $v['id'] ?>" style="font-size:.85rem;flex:1"><?= e($v['title']) ?></a>
              <?php else: ?>
                <span style="font-size:.85rem;flex:1;color:var(--gray-500)">🔒 <?= e($v['title']) ?></span>
              <?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
