<?php $pageTitle = 'AI Asisten — SkillUp'; ?>
<?php include APP_ROOT . '/views/layouts/header.php'; ?>

<div style="max-width:720px;margin:0 auto;padding:1.5rem;display:flex;flex-direction:column;height:calc(100vh - 80px)">
  <div style="margin-bottom:1rem">
    <h1 style="font-size:1.25rem;font-weight:700">🤖 AI Asisten Belajar</h1>
    <p style="font-size:.875rem;color:var(--gray-500)">Tanya apa saja seputar belajar & kursus</p>
  </div>

  <!-- Chat Window -->
  <div id="chat-box" style="flex:1;overflow-y:auto;background:var(--white);border:1.5px solid var(--gray-200);border-radius:var(--radius);padding:1rem;display:flex;flex-direction:column;gap:.75rem;min-height:400px;margin-bottom:1rem">
    <!-- Pesan sambutan -->
    <div style="display:flex;gap:.75rem">
      <div style="width:32px;height:32px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">🤖</div>
      <div style="background:var(--gray-50);border-radius:0 12px 12px 12px;padding:.65rem 1rem;max-width:85%;font-size:.9rem">
        Halo, <?= e($_SESSION['user_name']) ?>! 👋 Saya asisten belajar SkillUp. Tanya saya tentang:<br><br>
        • Rekomendasi kursus yang cocok<br>
        • Konsep teknis yang membingungkan<br>
        • Tips belajar efektif<br><br>
        Ada yang ingin ditanyakan?
      </div>
    </div>

    <!-- Riwayat chat -->
    <?php foreach ($history as $msg): ?>
    <?php if ($msg['role'] === 'user'): ?>
    <div style="display:flex;gap:.75rem;justify-content:flex-end">
      <div style="background:var(--primary);color:#fff;border-radius:12px 0 12px 12px;padding:.65rem 1rem;max-width:85%;font-size:.9rem;white-space:pre-wrap">
        <?= e($msg['message']) ?>
      </div>
    </div>
    <?php else: ?>
    <div style="display:flex;gap:.75rem">
      <div style="width:32px;height:32px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">🤖</div>
      <div style="background:var(--gray-50);border-radius:0 12px 12px 12px;padding:.65rem 1rem;max-width:85%;font-size:.9rem;white-space:pre-wrap">
        <?= e($msg['message']) ?>
      </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <!-- Input Area -->
  <div style="display:flex;gap:.75rem">
    <input type="text" id="chat-input" placeholder="Ketik pertanyaanmu..." style="flex:1;margin-bottom:0" maxlength="1000">
    <button id="send-btn" class="btn btn-primary" onclick="sendMessage()">Kirim</button>
  </div>
  <p style="font-size:.75rem;color:var(--gray-500);margin-top:.5rem;text-align:center">
    Tekan Enter untuk mengirim · Maks 1000 karakter
  </p>
</div>

<script>
const chatBox  = document.getElementById('chat-box');
const input    = document.getElementById('chat-input');
const sendBtn  = document.getElementById('send-btn');

// Scroll ke bawah
chatBox.scrollTop = chatBox.scrollHeight;

// Kirim dengan Enter
input.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); } });

async function sendMessage() {
  const msg = input.value.trim();
  if (!msg) return;

  input.value = '';
  sendBtn.disabled = true;
  sendBtn.textContent = '...';

  // Tambah bubble user
  appendBubble('user', msg);

  // Loading bubble
  const loadId = 'load-' + Date.now();
  chatBox.insertAdjacentHTML('beforeend', `
    <div id="${loadId}" style="display:flex;gap:.75rem">
      <div style="width:32px;height:32px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">🤖</div>
      <div style="background:var(--gray-50);border-radius:0 12px 12px 12px;padding:.65rem 1rem;font-size:.9rem;color:var(--gray-500)">Sedang mengetik...</div>
    </div>`);
  chatBox.scrollTop = chatBox.scrollHeight;

  try {
    const res  = await fetch('<?= BASE_PATH ?>/api/chat', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ message: msg })
    });
    const data = await res.json();

    document.getElementById(loadId)?.remove();

    if (data.reply) {
      appendBubble('assistant', data.reply);
    } else {
      appendBubble('assistant', data.error || 'Terjadi kesalahan, coba lagi.');
    }
  } catch (err) {
    document.getElementById(loadId)?.remove();
    appendBubble('assistant', '❌ Gagal menghubungi AI. Periksa koneksi internetmu.');
  }

  sendBtn.disabled = false;
  sendBtn.textContent = 'Kirim';
}

function appendBubble(role, text) {
  const isUser = role === 'user';
  const html = isUser
    ? `<div style="display:flex;gap:.75rem;justify-content:flex-end">
         <div style="background:var(--primary);color:#fff;border-radius:12px 0 12px 12px;padding:.65rem 1rem;max-width:85%;font-size:.9rem;white-space:pre-wrap">${escHtml(text)}</div>
       </div>`
    : `<div style="display:flex;gap:.75rem">
         <div style="width:32px;height:32px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0">🤖</div>
         <div style="background:var(--gray-50);border-radius:0 12px 12px 12px;padding:.65rem 1rem;max-width:85%;font-size:.9rem;white-space:pre-wrap">${escHtml(text)}</div>
       </div>`;
  chatBox.insertAdjacentHTML('beforeend', html);
  chatBox.scrollTop = chatBox.scrollHeight;
}

function escHtml(str) {
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>

<?php include APP_ROOT . '/views/layouts/footer.php'; ?>
