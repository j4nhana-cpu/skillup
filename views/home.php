<?php $pageTitle = 'SkillUp — Platform Kursus Digital Terbaik'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<style>
/* Pattern background */
.hero-section {
  /* Gunakan url() dan akhiri dengan titik koma ; */
 background: url('<?= BASE_PATH ?>/uploads/background2.jpg');
  background-size: cover;
  background-position: center;
  position: relative;
  overflow: hidden;
}

.floating-card {
  background: rgba(160, 65, 192, 0.12);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
  border-radius: 16px;
  padding: 1.25rem;
  color: #fff;
  text-align: center;
}
.section-pattern {
  background-color: #fcf8ff;
  background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.04' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
}
.course-card:hover .cc-title { color: var(--primary); }
.feature-card {
  background: #fff;
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 4px 24px rgba(99,102,241,0.08);
  border: 1px solid rgba(99,102,241,0.1);
  transition: transform .2s, box-shadow .2s;
  text-align: center;
}
.feature-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 32px rgba(99,102,241,0.15);
}
.feature-icon {
  width: 64px; height: 64px;
  border-radius: 16px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.75rem;
  margin: 0 auto 1rem;
}
.about-card {
  background: #fff;
  border-radius: 16px;
  padding: 1.5rem;
  border: 1px solid var(--gray-200);
  transition: transform .2s, box-shadow .2s;
}
.about-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
</style>

<!-- Hero -->
<section class="hero-section" style="color:#ffffff;padding:5rem 2rem 4rem">
  <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(30,20,60,0.72) 0%,rgba(60,30,80,0.55) 100%);z-index:0"></div>
  <div style="position:relative;z-index:1;max-width:1100px;margin:0 auto">
    <div style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(240, 222, 199, 0.83);border:1px solid rgba(255, 255, 255, 0.62);border-radius:99px;padding:.35rem 1rem;font-size:.85rem;margin-bottom:1.5rem">
      🚀 Platform Kursus Digital
    </div>
    <h1 style="font-size:2.75rem;font-weight:800;line-height:1.2;margin-bottom:1.25rem;max-width:700px">
      Tingkatkan Keterampilanmu<br>
      <span style="background:linear-gradient(90deg,#fbbf24,#f59e0b);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
        Bersama Mentor Terbaik
      </span>
    </h1>
    <p style="font-size:1.05rem;opacity:100;margin-bottom:2rem;line-height:1.7;max-width:660px">
      Akses ratusan kursus digital dari praktisi industri berpengalaman. Belajar kapan saja, di mana saja, dengan harga terjangkau.
    </p>
    <?php if (!Auth::check()): ?>
    <div style="display:flex;gap:1rem;flex-wrap:wrap">
      <a href="<?= BASE_PATH ?>/auth/register" class="btn btn-lg" style="background:#fff;color:var(--primary);font-weight:700;border-radius:12px">
        🚀 Daftar Gratis
      </a>
      <a href="<?= BASE_PATH ?>#kursus-populer" class="btn btn-lg" style="background:rgba(255,255,255,.15);color:#fff;border:1.5px solid rgba(255,255,255,.4);border-radius:12px">
        🔍 Jelajahi Kursus
      </a>
    </div>
    <?php endif; ?>
    <div style="display:flex;gap:2rem;margin-top:2.5rem;flex-wrap:wrap">
      <div>
        <div style="font-size:1.5rem;font-weight:800"><?= number_format($stats['total_students'] ?? 0) ?>+</div>
        <div style="font-size:.8rem;opacity:.75">Pelajar Aktif</div>
      </div>
      <div style="width:1px;background:rgba(255,255,255,.2)"></div>
      <div>
        <div style="font-size:1.5rem;font-weight:800"><?= number_format($stats['total_courses'] ?? 0) ?>+</div>
        <div style="font-size:.8rem;opacity:.75">Kursus Tersedia</div>
      </div>
      <div style="width:1px;background:rgba(255,255,255,.2)"></div>
      <div>
        <div style="font-size:1.5rem;font-weight:800"><?= number_format($stats['total_mentors'] ?? 0) ?>+</div>
        <div style="font-size:.8rem;opacity:.75">Mentor Ahli</div>
      </div>
    </div>
  </div>
</section>

<!-- Tentang SkillUp -->
<section class="section-pattern" style="padding:4rem 2rem">
  <div style="max-width:1100px;margin:0 auto">
    <div style="text-align:center;margin-bottom:3rem">
      <span class="badge badge-primary" style="font-size:.85rem;padding:.4rem 1rem;border-radius:99px">Tentang Kami</span>
      <h2 style="font-size:2rem;font-weight:800;margin:1rem 0 .75rem">
        Apa itu <span style="color:var(--primary)">SkillUp</span>?
      </h2>
      <p style="color:#494f5c;max-width:1000px;margin:0 auto;line-height:1.8">
  <strong>SkillUp</strong> adalah platform marketplace kursus keterampilan digital yang mempertemukan
  <strong>mentor ahli berpengalaman</strong> dengan <strong>pelajar yang ingin berkembang</strong>.
  Kami percaya setiap orang berhak mendapat pendidikan berkualitas — kapan saja, di mana saja.
  Dengan ratusan kursus dari berbagai bidang seperti teknologi, desain, bisnis, dan kreatif,
  SkillUp hadir sebagai solusi belajar modern yang fleksibel, terjangkau, dan berdampak nyata
  bagi karier dan kehidupanmu.
</p>
    </div>
    <div class="grid-3" style="gap:1.5rem">
      <div class="about-card">
        <div style="width:48px;height:48px;background:#ede9fe;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:1rem">🎯</div>
        <h3 style="font-weight:700;margin-bottom:.5rem">Belajar Terarah</h3>
        <p style="font-size:.875rem;color:var(--gray-500);line-height:1.6">Kurikulum terstruktur dari mentor yang sudah terbukti ahli di bidangnya.</p>
      </div>
      <div class="about-card">
        <div style="width:48px;height:48px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:1rem">💼</div>
        <h3 style="font-weight:700;margin-bottom:.5rem">Skill Siap Kerja</h3>
        <p style="font-size:.875rem;color:var(--gray-500);line-height:1.6">Materi praktis yang langsung bisa diterapkan di dunia kerja dan industri.</p>
      </div>
      <div class="about-card">
        <div style="width:48px;height:48px;background:#d1fae5;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:1rem">🤝</div>
        <h3 style="font-weight:700;margin-bottom:.5rem">Komunitas Aktif</h3>
        <p style="font-size:.875rem;color:var(--gray-500);line-height:1.6">Bergabung dengan ribuan pelajar dan mentor yang saling mendukung.</p>
      </div>
    </div>
  </div>
</section>

<!-- Kursus Populer -->
<section class="section-pattern" style="padding:4rem 2rem">
  <div style="max-width:1100px;margin:0 auto">
    <?php include APP_ROOT . '/views/layouts/flash.php'; ?>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem">
      <div>
        <h2 id="kursus-populer" style="font-size:1.6rem;font-weight:800">🔥 Kursus Populer</h2>
        <p style="color:var(--gray-500);font-size:.875rem;margin-top:.25rem">Dipilih oleh ribuan pelajar aktif</p>
      </div>
      <a href="<?= BASE_PATH ?>/student/courses" class="btn btn-outline">Lihat Semua →</a>
    </div>
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
              <span style="font-size:.8rem;color:var(--gray-500)"><?= stars($c['rating_avg'], true) ?> <?= number_format($c['rating_avg'],1) ?></span>
            </div>
            <div style="font-size:.8rem;color:var(--gray-500);margin-top:.35rem">👥 <?= number_format($c['total_students']) ?> siswa</div>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Fitur Unggulan -->
<section class="section-pattern" style="padding:4rem 2rem">
  <div style="max-width:1100px;margin:0 auto">
    <div style="text-align:center;margin-bottom:3rem">
      <h2 style="font-size:1.75rem;font-weight:800;margin-bottom:.5rem">Kenapa Pilih SkillUp?</h2>
      <p style="color:var(--gray-500)">Semua yang kamu butuhkan untuk berkembang ada di sini</p>
    </div>
    <div class="grid-3" style="gap:1.5rem">
      <div class="feature-card">
        <div class="feature-icon" style="background:#ede9fe">🎬</div>
        <h3 style="font-weight:700;margin-bottom:.5rem">Video Berkualitas HD</h3>
        <p style="font-size:.875rem;color:var(--gray-500);line-height:1.6">Konten video dilindungi hak akses. Hanya pelajar terdaftar yang bisa menonton.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon" style="background:#dbeafe">🤖</div>
        <h3 style="font-weight:700;margin-bottom:.5rem">AI Asisten Belajar</h3>
        <p style="font-size:.875rem;color:var(--gray-500);line-height:1.6">Chatbot AI siap menjawab pertanyaan dan merekomendasikan kursus terbaik untukmu.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon" style="background:#d1fae5">💰</div>
        <h3 style="font-weight:700;margin-bottom:.5rem">Bagi Hasil Transparan</h3>
        <p style="font-size:.875rem;color:var(--gray-500);line-height:1.6">Mentor mendapat 70% dari setiap penjualan. Dashboard real-time untuk pantau pendapatan.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon" style="background:#fef3c7">⭐</div>
        <h3 style="font-weight:700;margin-bottom:.5rem">Rating Berbasis AI</h3>
        <p style="font-size:.875rem;color:var(--gray-500);line-height:1.6">Setiap ulasan dianalisis sentimen otomatis oleh AI untuk memastikan kualitas kursus.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon" style="background:#fce7f3">📱</div>
        <h3 style="font-weight:700;margin-bottom:.5rem">Akses Multi Device</h3>
        <p style="font-size:.875rem;color:var(--gray-500);line-height:1.6">Belajar dari laptop, tablet, atau smartphone kapan saja dan di mana saja.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon" style="background:#ecfdf5">🏆</div>
        <h3 style="font-weight:700;margin-bottom:.5rem">Mentor Terverifikasi</h3>
        <p style="font-size:.875rem;color:var(--gray-500);line-height:1.6">Semua mentor telah melalui proses kurasi ketat untuk memastikan kualitas pengajaran.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA Banner -->
<?php if (!Auth::check()): ?>

<?php endif; ?>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>