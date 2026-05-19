<?php $pageTitle = 'Manajemen Pengguna — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>
<div class="page">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <h1 style="font-size:1.4rem;font-weight:700">👥 Manajemen Pengguna</h1>
    <a href="<?= BASE_PATH ?>/admin/dashboard" style="font-size:.875rem;color:var(--gray-500)">← Dashboard</a>
  </div>
  <?php include APP_ROOT . '/views/layouts/flash.php'; ?>
  <div class="card" style="margin-bottom:1rem">
    <div class="card-body" style="padding:.75rem 1rem">
      <form method="GET" action="<?= BASE_PATH ?>/admin/users" style="display:flex;gap:.75rem;flex-wrap:wrap">
        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Cari nama/email..." style="max-width:260px;margin:0">
        <select name="role" style="max-width:160px;margin:0">
          <option value="">Semua Role</option>
          <option value="student" <?= ($role??'')==='student'?'selected':'' ?>>Student</option>
          <option value="mentor"  <?= ($role??'')==='mentor' ?'selected':'' ?>>Mentor</option>
          <option value="admin"   <?= ($role??'')==='admin'  ?'selected':'' ?>>Admin</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-outline btn-sm">Reset</a>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="table-wrap">
      <table>
        <thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Bergabung</th></tr></thead>
        <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td style="color:var(--gray-500);font-size:.8rem">#<?= $u['id'] ?></td>
          <td><strong><?= e($u['name']) ?></strong></td>
          <td style="font-size:.875rem"><?= e($u['email']) ?></td>
          <td>
            <?php $rc = ['student'=>'badge-primary','mentor'=>'badge-success','admin'=>'badge-danger']; ?>
            <span class="badge <?= $rc[$u['role']] ?? 'badge-gray' ?>"><?= $u['role'] ?></span>
          </td>
          <td><span class="badge <?= $u['is_active'] ? 'badge-success' : 'badge-danger' ?>"><?= $u['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
          <td style="font-size:.8rem;color:var(--gray-500)"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
