<?php $pageTitle = 'Checkout — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem">
  <div style="width:100%;max-width:520px">
    <div class="card">
      <div class="card-body" style="padding:2rem">
        <div style="text-align:center;margin-bottom:1.5rem">
          <div style="font-size:2.5rem">💳</div>
          <h2 style="font-weight:700;margin-bottom:.25rem">Pembayaran Kursus</h2>
          <p style="color:var(--gray-500);font-size:.875rem">Pilih metode pembayaran yang tersedia</p>
        </div>

        <!-- Info Order -->
        <div style="background:#f0f4ff;border-radius:10px;padding:1.25rem;margin-bottom:1.5rem">
          <h3 style="font-size:.9rem;font-weight:600;margin-bottom:.75rem;color:var(--primary)">📋 Detail Order</h3>
          <table style="width:100%;font-size:.875rem;border-collapse:collapse">
            <tr><td style="padding:.35rem 0;color:var(--gray-500);width:120px">Kode Order</td><td><code style="font-size:.8rem"><?= e($_SESSION['pending_order'] ?? '-') ?></code></td></tr>
            <tr><td style="padding:.35rem 0;color:var(--gray-500)">Total Bayar</td><td><strong style="color:var(--primary);font-size:1.1rem"><?= rupiah($_SESSION['pending_amount'] ?? 0) ?></strong></td></tr>
          </table>
        </div>

        <!-- Midtrans Snap Container -->
        <div id="snap-container" style="margin-bottom:1.5rem">
          <button id="pay-button" class="btn btn-primary" style="width:100%;justify-content:center;padding:.75rem">
            💳 Bayar Sekarang
          </button>
        </div>

        <div style="margin-top:1rem;padding:.75rem;background:#dbeafe;border-radius:8px;font-size:.8rem;color:#1e40af">
          🔒 Pembayaran aman dengan Midtrans. Setelah bayar, kamu akan langsung mendapat akses ke kursus.
        </div>

        <a href="<?= BASE_PATH ?>/student/courses" style="display:block;text-align:center;margin-top:1rem;font-size:.875rem;color:var(--gray-500)">Batal</a>
      </div>
    </div>
  </div>
</div>

<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= MIDTRANS_CLIENT_KEY ?>"></script>
<script>
document.getElementById('pay-button').onclick = function() {
  snap.pay('<?= e($_SESSION['snap_token'] ?? '') ?>', {
    onSuccess: function(result) {
      console.log('success', result);
      window.location.href = '<?= BASE_PATH ?>/student/payment/finish?order_id=<?= e($_SESSION['pending_order'] ?? '') ?>&transaction_status=' + encodeURIComponent(result.transaction_status) + '&fraud_status=' + encodeURIComponent(result.fraud_status || '');
    },
    onPending: function(result) {
      console.log('pending', result);
      window.location.href = '<?= BASE_PATH ?>/student/payment/finish?order_id=<?= e($_SESSION['pending_order'] ?? '') ?>&transaction_status=' + encodeURIComponent(result.transaction_status) + '&fraud_status=' + encodeURIComponent(result.fraud_status || '');
    },
    onError: function(result) {
      console.log('error', result);
      alert('Pembayaran gagal: ' + (result.status_message || 'Terjadi kesalahan.'));
    },
    onClose: function() {
      console.log('customer closed the popup without finishing the payment');
    }
  });
};
</script>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>