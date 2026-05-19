<?php $pageTitle = 'Edit Profil — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem">
  <div style="width:100%;max-width:480px">
    <div style="margin-bottom:1.25rem">
      <?php
        $dashboardPath = '/student/dashboard';
        if ($_SESSION['user_role'] === 'mentor') {
            $dashboardPath = '/mentor/dashboard';
        } elseif ($_SESSION['user_role'] === 'admin') {
            $dashboardPath = '/admin/dashboard';
        }
      ?>
      <a href="<?= BASE_PATH . $dashboardPath ?>" style="font-size:.875rem;color:var(--gray-500)">← Kembali</a>
      <h1 style="font-size:1.4rem;font-weight:700;margin-top:.5rem">✏️ Edit Profil</h1>
    </div>

    <?php include APP_ROOT . '/views/layouts/flash.php'; ?>

    <div class="card">
      <div class="card-body" style="padding:2rem">

        <!-- Avatar dengan opsi upload -->
        <div style="text-align:center;margin-bottom:1.5rem">
          <div id="avatarPreview" style="width:100px;height:100px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:700;margin:0 auto .75rem;position:relative;overflow:hidden">
            <?php if (!empty($user['avatar']) && file_exists(APP_ROOT . '/public/' . $user['avatar'])): ?>
              <img src="<?= BASE_PATH . '/' . $user['avatar'] ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
            <?php else: ?>
              <?= strtoupper(mb_substr($user['name'], 0, 1)) ?>
            <?php endif; ?>
          </div>
          <div style="font-weight:600;margin-bottom:.35rem"><?= e($user['name']) ?></div>
          <span class="badge badge-primary"><?= $user['role'] ?></span>
        </div>

        <form method="POST" action="<?= BASE_PATH ?>/profile/edit" enctype="multipart/form-data">
          <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">

          <!-- Upload Foto Profil -->
          <div class="form-group">
            <label>📸 Foto Profil (Opsional)</label>
            <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" style="border:2px dashed var(--gray-200);padding:1.5rem;border-radius:8px;cursor:pointer;text-align:center;background:var(--gray-50);transition:all .2s">
            <small style="color:var(--gray-500);display:block;margin-top:.35rem">Ukuran maksimal 5MB. Format: JPG, PNG, GIF, WebP</small>
          </div>

          <hr style="border:none;border-top:1px solid var(--gray-200);margin:1.25rem 0">

          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="<?= e($user['name']) ?>" required minlength="3">
          </div>

          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= e($user['email']) ?>" required>
          </div>

          <?php if ($_SESSION['user_role'] === 'mentor'): ?>
          <div class="form-group">
            <label>Nomor Rekening (opsional)</label>
            <input type="text" name="bank_account" value="<?= e($user['bank_account'] ?? '') ?>" placeholder="Contoh: BCA - 1234567890">
            <small style="color:var(--gray-500);font-size:.8rem">Opsional — hanya untuk keperluan pencairan pendapatan. Bisa diubah kapan saja.</small>
          </div>
          <?php endif; ?>

          <hr style="border:none;border-top:1px solid var(--gray-200);margin:1.25rem 0">
          <p style="font-size:.85rem;color:var(--gray-500);margin-bottom:1rem">Ganti password (kosongkan jika tidak ingin mengubah)</p>

          <div class="form-group">
            <label>Password Lama</label>
            <input type="password" name="old_password" placeholder="••••••••">
          </div>

          <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="new_password" id="newPass" placeholder="••••••••" minlength="6">
          </div>

          <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="confirm_password" id="confirmPass" placeholder="••••••••">
            <div id="passMsg" style="font-size:.8rem;margin-top:.35rem;display:none"></div>
          </div>

          <div style="display:flex;gap:.75rem;margin-top:1rem">
            <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">💾 Simpan</button>
            <a href="<?= BASE_PATH ?>/auth/logout" class="btn btn-danger">🚪 Logout</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Preview foto profil saat dipilih
const avatarInput = document.getElementById('avatarInput');
const avatarPreview = document.getElementById('avatarPreview');

avatarInput.addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;

  // Validasi ukuran (5MB max)
  if (file.size > 5 * 1024 * 1024) {
    alert('Ukuran file terlalu besar! Maksimal 5MB');
    avatarInput.value = '';
    return;
  }

  // Validasi tipe file
  const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
  if (!validTypes.includes(file.type)) {
    alert('Format file tidak didukung! Gunakan JPG, PNG, GIF, atau WebP');
    avatarInput.value = '';
    return;
  }

  // Preview
  const reader = new FileReader();
  reader.onload = function(event) {
    const img = document.createElement('img');
    img.src = event.target.result;
    img.style.cssText = 'width:100%;height:100%;object-fit:cover;border-radius:50%';
    avatarPreview.innerHTML = '';
    avatarPreview.appendChild(img);
  };
  reader.readAsDataURL(file);
});

// Styling pada hover
avatarInput.addEventListener('dragover', function(e) {
  e.preventDefault();
  avatarInput.style.borderColor = 'var(--primary)';
  avatarInput.style.background = 'var(--primary-l)';
});

avatarInput.addEventListener('dragleave', function() {
  avatarInput.style.borderColor = 'var(--gray-200)';
  avatarInput.style.background = 'var(--gray-50)';
});

avatarInput.addEventListener('drop', function(e) {
  e.preventDefault();
  avatarInput.style.borderColor = 'var(--gray-200)';
  avatarInput.style.background = 'var(--gray-50)';
  const files = e.dataTransfer.files;
  if (files.length > 0) {
    avatarInput.files = files;
    const event = new Event('change', { bubbles: true });
    avatarInput.dispatchEvent(event);
  }
});

const np = document.getElementById('newPass');
const cp = document.getElementById('confirmPass');
const pm = document.getElementById('passMsg');
function checkPass() {
  if (!cp.value) { pm.style.display='none'; return; }
  pm.style.display = 'block';
  if (np.value !== cp.value) {
    pm.style.color = 'var(--danger)'; pm.textContent = '❌ Password tidak cocok!';
  } else {
    pm.style.color = 'var(--success)'; pm.textContent = '✅ Password cocok!';
  }
}
np.addEventListener('input', checkPass);
cp.addEventListener('input', checkPass);
</script>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
