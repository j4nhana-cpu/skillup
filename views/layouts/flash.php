<?php
// ============================================================
//  views/layouts/flash.php — Tampilkan flash messages
// ============================================================
$flashes = getFlash();
foreach ($flashes as $type => $messages):
    $cls = $type === 'success' ? 'alert-success' : ($type === 'error' ? 'alert-error' : 'alert-info');
    foreach ($messages as $msg):
?>
<div class="alert <?= $cls ?>"><?= e($msg) ?></div>
<?php
    endforeach;
endforeach;
