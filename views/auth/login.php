<?php $pageTitle = 'Login — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem">
  <div style="width:100%;max-width:420px">
    <div class="card">
      <div class="card-body" style="padding:2rem">
        <div style="text-align:center;margin-bottom:1.75rem">
          <div style="font-size:2.5rem">🎓</div>
          <h1 style="font-size:1.5rem;font-weight:700;margin-top:.5rem">Masuk ke SkillUp</h1>
          <p style="color:var(--gray-500);font-size:.875rem;margin-top:.25rem">Lanjutkan perjalanan belajarmu</p>
        </div>

        <?php if (!empty($flash['error'])): ?>
          <div style="background:#fee2e2;border-left:4px solid #ef4444;border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:#991b1b">
              ❌ <?= e($flash['error'][0]) ?>
          </div>
        <?php endif; ?>
        <?php if (!empty($flash['success'])): ?>
          <div style="background:#d1fae5;border-left:4px solid #10b981;border-radius:8px;padding:.75rem 1rem;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:#065f46">
            ✅ <?= e($flash['success'][0]) ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_PATH ?>/auth/login">
          <input type="hidden" name="_csrf" value="<?= Auth::csrfToken() ?>">

          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="nama@email.com" required autofocus>
          </div>

          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
          </div>

          <div style="display:flex;justify-content:center;margin-top:.5rem">
            <button type="submit" class="btn btn-primary" style="width:100%;padding:.65rem;justify-content:center;text-align:center">
          Masuk
            </button>
          </div>
        </form>

        <p style="text-align:center;margin-top:1.25rem;font-size:.875rem;color:var(--gray-500)">
          Belum punya akun? <a href="<?= BASE_PATH ?>/auth/register">Daftar sekarang</a>
        </p>

      </div>
    </div>
  </div>
</div>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
