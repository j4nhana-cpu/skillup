<?php $pageTitle = 'Daftar — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem">
  <div style="width:100%;max-width:460px">
    <div class="card">
      <div class="card-body" style="padding:2rem">
        <div style="text-align:center;margin-bottom:1.75rem">
          <div style="font-size:2.5rem">✍️</div>
          <h1 style="font-size:1.5rem;font-weight:700;margin-top:.5rem">Buat Akun SkillUp</h1>
          <p style="color:var(--gray-500);font-size:.875rem;margin-top:.25rem">Mulai belajar atau ajarkan keahlianmu</p>
        </div>

        <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

        <form method="POST" action="<?= BASE_PATH ?>/auth/register" id="registerForm">
          <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">

          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" placeholder="Nama kamu" required minlength="3">
          </div>

          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" id="emailInput" placeholder="nama@email.com" required>
            <div id="emailMsg" style="font-size:.8rem;margin-top:.35rem;display:none"></div>
          </div>

          <div class="form-group">
            <label>Password <span style="color:var(--gray-500);font-size:.8rem">(min. 6 karakter)</span></label>
            <input type="password" name="password" id="passInput" placeholder="••••••••" required minlength="6">
          </div>

          <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirm" id="confirmInput" placeholder="••••••••" required>
            <div id="confirmMsg" style="font-size:.8rem;margin-top:.35rem;display:none"></div>
          </div>

          <div class="form-group">
            <label>Daftar sebagai</label>
            <select name="role">
              <option value="student">🎓 Pelajar — Saya ingin belajar</option>
              <option value="mentor">🧑‍🏫 Mentor — Saya ingin mengajar</option>
            </select>
          </div>

          <div style="display:flex;justify-content:center;margin-top:.5rem">
            <button type="submit" class="btn btn-primary" style="width:100%;padding:.65rem;justify-content:center;text-align:center">
          Buat Akun
            </button>
          </div>
        </form>

        <p style="text-align:center;margin-top:1.25rem;font-size:.875rem;color:var(--gray-500)">
          Sudah punya akun? <a href="<?= BASE_PATH ?>/auth/login">Masuk</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
// Cek email sudah terdaftar (realtime via API)
const emailInput   = document.getElementById('emailInput');
const emailMsg     = document.getElementById('emailMsg');
const passInput    = document.getElementById('passInput');
const confirmInput = document.getElementById('confirmInput');
const confirmMsg   = document.getElementById('confirmMsg');

let emailTimer;
emailInput.addEventListener('input', function() {
  clearTimeout(emailTimer);
  emailMsg.style.display = 'none';
  emailTimer = setTimeout(async () => {
    const val = this.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!val || !emailRegex.test(val)) return;
    try {
      const res  = await fetch('<?= BASE_PATH ?>/api/check-email?email=' + encodeURIComponent(val));
      const data = await res.json();
      if (data.exists) {
        emailMsg.style.display = 'block';
        emailMsg.style.color   = 'var(--danger)';
        emailMsg.textContent   = '❌ Email ini sudah pernah digunakan. Coba email lain atau masuk.';
      } else {
        emailMsg.style.display = 'block';
        emailMsg.style.color   = 'var(--success)';
        emailMsg.textContent   = '✅ Email tersedia!';
      }
    } catch(e) {}
  }, 600);
});

// Cek konfirmasi password realtime
confirmInput.addEventListener('input', checkPass);
passInput.addEventListener('input', checkPass);

function checkPass() {
  const p = passInput.value;
  const c = confirmInput.value;
  if (!c) { confirmMsg.style.display = 'none'; return; }
  confirmMsg.style.display = 'block';
  if (p !== c) {
    confirmMsg.style.color   = 'var(--danger)';
    confirmMsg.textContent   = '❌ Password tidak cocok!';
  } else {
    confirmMsg.style.color   = 'var(--success)';
    confirmMsg.textContent   = '✅ Password cocok!';
  }
}

// Blokir submit jika password tidak cocok
document.getElementById('registerForm').addEventListener('submit', function(e) {
  if (passInput.value !== confirmInput.value) {
    e.preventDefault();
    confirmMsg.style.display = 'block';
    confirmMsg.style.color   = 'var(--danger)';
    confirmMsg.textContent   = '❌ Password tidak cocok! Periksa kembali.';
    confirmInput.focus();
  }
});
</script>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
