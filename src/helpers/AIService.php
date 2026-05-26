<?php
// ============================================================
//  src/helpers/AIService.php
// ============================================================

class AIService
{
    /**
     * Chatbot Manual: Menggunakan Logika Kata Kunci
     */
    public static function chat(int $userId, string $message): string
    {
        // 1. Ambil respons dari sistem manual kita
        $responseText = self::getManualResponse($message);

        // 2. Simpan ke riwayat database (agar chat tetap muncul di layar)
        Database::query(
            'INSERT INTO chat_history (user_id, role, message) VALUES (?, ?, ?)',
            [$userId, 'user', $message]
        );
        Database::query(
            'INSERT INTO chat_history (user_id, role, message) VALUES (?, ?, ?)',
            [$userId, 'assistant', $responseText]
        );

        return $responseText;
    }

    /**
     * Analisis Sentimen Manual: Berdasarkan Skor Kata
     */
    public static function analyzeSentiment(string $text): string
    {
        return self::keywordSentiment($text);
    }

    // ============================================================
    //  ENGINE UTAMA — urutan PENTING: spesifik dulu, umum belakang
    // ============================================================
    private static function getManualResponse(string $message): string
    {
        $msg = mb_strtolower(trim($message));

        // --------------------------------------------------------
        // BLOK A — TOPIK KURSUS SPESIFIK
        // (harus di atas blok "kursus" yang umum)
        // --------------------------------------------------------

        // A1. Web Development / PHP / Laravel
        if (
            str_contains($msg, 'php') ||
            str_contains($msg, 'laravel') ||
            str_contains($msg, 'web development') ||
            str_contains($msg, 'web dev') ||
            str_contains($msg, 'html') ||
            str_contains($msg, 'css') ||
            str_contains($msg, 'javascript') ||
            str_contains($msg, 'js') ||
            str_contains($msg, 'backend') ||
            str_contains($msg, 'frontend') ||
            str_contains($msg, 'fullstack') ||
            str_contains($msg, 'full stack') ||
            str_contains($msg, 'coding') ||
            str_contains($msg, 'pemrograman')
       ) {
            $responses = [
                "Untuk kelas Web Development, materi difokuskan pada pemrograman full-stack. Kamu bakal belajar dari dasar:\n\n• Frontend: HTML, CSS (Layouting), dan JavaScript dasar.\n• Backend: PHP Dasar hingga konsep OOP.\n• Framework: Penggunaan Laravel (Routing, Controller, Views, Migration).\n• Database: Operasi CRUD dengan MySQL.\n\nPembelajaran berbasis proyek (bikin sistem utuh).",
                "Kelas Web Dev di sini cocok untuk yang mau belajar bikin aplikasi web dari nol. Kurikulumnya mencakup materi HTML/CSS untuk tampilan, logika backend pakai PHP Native, dan manajemen database MySQL. Di bagian akhir juga ada materi opsional untuk perkenalan framework Laravel."
            ];
            return $responses[array_rand($responses)];
        }

        // A2. UI/UX Design / Figma
        if (
            str_contains($msg, 'ui') ||
            str_contains($msg, 'ux') ||
            str_contains($msg, 'figma') ||
            str_contains($msg, 'desain') ||
            str_contains($msg, 'design') ||
            str_contains($msg, 'prototype') ||
            str_contains($msg, 'wireframe') ||
            str_contains($msg, 'mockup') ||
            str_contains($msg, 'user interface') ||
            str_contains($msg, 'user experience')
        ) {
            $responses = [
                "Kelas UI/UX Design SkillUp cocok banget buat kamu yang suka dunia visual! 🎨 Yang bakal kamu pelajari:\n\n• Prinsip dasar UI & UX Design\n• Figma dari nol — komponen, auto layout, varian\n• Membuat wireframe & prototype interaktif\n• Design system & style guide\n• User research & usability testing\n\nSetelah lulus, portfolio kamu siap dibawa melamar kerja! Harga mulai Rp 129.000.",

                "Figma sekarang jadi tool wajib buat UI/UX designer. Di kelas kami:\n\n• Belajar Figma dari antarmuka dasar sampai fitur advanced\n• Latihan redesign aplikasi nyata (e-commerce, fintech, dll)\n• Cara presentasi desain ke klien atau tim developer\n• Tips freelance sebagai UI/UX designer\n\nMentor kami aktif sebagai designer di startup Indonesia. Tertarik?",

                "UI/UX Design adalah salah satu skill paling dicari perusahaan tech saat ini! Kelas SkillUp mengajarkan:\n\n• Design thinking & empathy map\n• Figma: frame, komponen, hingga prototyping\n• Cara buat portfolio yang menarik rekruter\n• Studi kasus redesign aplikasi populer\n\nHarga kelas Rp 129.000 — sudah termasuk akses seumur hidup dan sertifikat!"
            ];
            return $responses[array_rand($responses)];
        }

        // A3. Social Media Marketing / Digital Marketing
        if (
            str_contains($msg, 'marketing') ||
            str_contains($msg, 'social media') ||
            str_contains($msg, 'sosmed') ||
            str_contains($msg, 'instagram') ||
            str_contains($msg, 'tiktok') ||
            str_contains($msg, 'konten') ||
            str_contains($msg, 'content') ||
            str_contains($msg, 'iklan') ||
            str_contains($msg, 'ads') ||
            str_contains($msg, 'seo') ||
            str_contains($msg, 'copywriting') ||
            str_contains($msg, 'branding') ||
            str_contains($msg, 'digital')
        ) {
            $responses = [
                "Kelas Social Media Marketing SkillUp bakal bikin kamu jago kelola brand di dunia digital! 📱 Materi lengkapnya:\n\n• Strategi konten Instagram & TikTok\n• Cara riset target audiens & kompetitor\n• Membuat copywriting yang menjual\n• Dasar-dasar iklan berbayar (Meta Ads & TikTok Ads)\n• Analisis insight & laporan performa\n\nCocok buat kamu yang mau kerja di agency, jadi freelancer, atau promosiin bisnis sendiri. Mulai Rp 99.000!",

                "Pengen kontenmu viral dan bisnismu laris? 🔥 Di kelas ini kamu belajar:\n\n• Content planning & kalender konten\n• Teknik storytelling untuk caption & video\n• SEO dasar untuk meningkatkan jangkauan organik\n• Meta Ads: setting campaign dari nol sampai menghasilkan\n• Tools gratis untuk desain konten (Canva, CapCut)\n\nSudah 1.500+ alumni yang berhasil naikin engagement mereka setelah ikut kelas ini!",

                "Digital marketing adalah skill yang bisa langsung menghasilkan uang! Di kelas SkillUp:\n\n• Strategi organic growth di Instagram & TikTok\n• Cara bikin iklan yang efisien dan tidak boncos\n• Teknik copywriting: AIDA, storytelling, CTA\n• Google Analytics & Meta Business Suite\n• Membangun personal branding yang kuat\n\nHarga Rp 99.000 — investasi kecil, hasil besar!"
            ];
            return $responses[array_rand($responses)];
        }

        // A4. Data / Excel / Analisis
        if (
            str_contains($msg, 'data') ||
            str_contains($msg, 'excel') ||
            str_contains($msg, 'analisis') ||
            str_contains($msg, 'spreadsheet') ||
            str_contains($msg, 'python') ||
            str_contains($msg, 'sql') ||
            str_contains($msg, 'database') ||
            str_contains($msg, 'statistik')
        ) {
            $responses = [
                "Wah, kamu tertarik dunia data! 📊 Saat ini SkillUp punya kelas:\n\n• Excel Profesional — rumus, pivot table, dashboard\n• SQL untuk analisis data\n• Python dasar untuk pengolahan data\n\nSkill data sangat dibutuhkan di hampir semua industri. Cocok banget buat kamu yang kerja di finance, HR, atau bisnis. Mau info lebih lanjut kelas yang mana?",

                "Kelas Data di SkillUp dirancang untuk pemula yang belum pernah coding sama sekali. Kamu mulai dari Excel dulu, lalu naik ke SQL dan Python secara bertahap. Sudah banyak alumni kami yang berhasil pindah karir jadi Data Analyst setelah ikut kelas ini! Cek katalog lengkapnya di halaman Kursus ya."
            ];
            return $responses[array_rand($responses)];
        }

        // A5. Canva / Desain Grafis
        if (
            str_contains($msg, 'canva') ||
            str_contains($msg, 'grafis') ||
            str_contains($msg, 'poster') ||
            str_contains($msg, 'flyer') ||
            str_contains($msg, 'logo') ||
            str_contains($msg, 'illustrator') ||
            str_contains($msg, 'photoshop')
        ) {
            return "Untuk desain grafis, SkillUp punya kelas Canva Pro & Desain Visual yang cocok buat pemula! 🎨 Kamu akan belajar:\n\n• Prinsip desain: warna, tipografi, layout\n• Canva Pro — fitur-fitur tersembunyi yang jarang diketahui\n• Membuat konten sosmed, presentasi, dan poster profesional\n• Tips desain yang terlihat mahal tanpa software berbayar\n\nMulai hanya Rp 79.000. Langsung bisa dipraktikkan hari ini!";
        }

        // --------------------------------------------------------
        // BLOK B — TENTANG SKILLUP & UMUM
        // --------------------------------------------------------

        // B1. Tentang SkillUp
        if (
            str_contains($msg, 'apa itu skillup') ||
            str_contains($msg, 'tentang skillup') ||
            str_contains($msg, 'apa itu skill up') ||
            str_contains($msg, 'skill up itu apa') ||
            str_contains($msg, 'skill up adalah') ||
            str_contains($msg, 'apa itu skillup') ||
            $msg === 'skillup' ||
            $msg === 'skill up'
        ) {
            return "SkillUp adalah platform e-learning masa kini yang fokus pada skill digital. Kami menyediakan kursus mulai dari pemrograman, desain grafis, hingga marketing dengan kurikulum yang mudah dipahami pemula!";
        }

        // B2. Harga / Pembayaran / Promo
        if (
            str_contains($msg, 'harga') ||
            str_contains($msg, 'bayar') ||
            str_contains($msg, 'biaya') ||
            str_contains($msg, 'murah') ||
            str_contains($msg, 'transfer') ||
            str_contains($msg, 'payment') ||
            str_contains($msg, 'midtrans') ||
            str_contains($msg, 'gopay') ||
            str_contains($msg, 'ovo') ||
            str_contains($msg, 'dana')
        ) {
            return "Biaya untuk setiap kelas di SkillUp bervariasi, berkisar antara Rp 50.000 hingga Rp 159.000 per kursus tergantung pada kelengkapan topik dan jumlah modul video di dalamnya.\n\nSistem pembayaran di platform ini menggunakan sistem sekali bayar (one-time payment), yang berarti tidak ada biaya langganan bulanan atau biaya tersembunyi tambahan setelahnya. Begitu pembayaran selesai, akses ke seluruh materi video, kuis, dan aset kelas akan terbuka secara permanen (selamanya).\n\nUntuk metode pembayaran, sistem kami terintegrasi secara otomatis sehingga kamu tidak perlu melakukan konfirmasi manual lewat bukti transfer. Metode yang tersedia meliputi:\n1. Transfer Bank / Virtual Account (VA): Mendukung Bank Mandiri, BCA, BNI, BRI, dan bank lainnya.\n2. E-Wallet: Pembayaran instan via scan QRIS, GoPay, OVO, DANA, atau LinkAja.\n\nSetelah status pada gerbang pembayaran (payment gateway) dinyatakan berhasil, kelas yang dibeli akan langsung aktif di dashboard akun kamu dalam waktu beberapa menit.";
        }

        // B3. Kursus / Belajar (Umum)
        if (
            str_contains($msg, 'kursus') ||
            str_contains($msg, 'belajar') ||
            str_contains($msg, 'materi') ||
            str_contains($msg, 'kelas') ||
            str_contains($msg, 'pelajaran') ||
            str_contains($msg, 'modul') ||
            str_contains($msg, 'video')
       ) {
            return "Sistem pembelajaran di SkillUp dirancang dengan metode belajar mandiri (self-paced learning) yang sepenuhnya berbasis pada materi video rekaman. Setelah menyelesaikan proses pembelian, seluruh rangkaian video tutorial di dalam kelas tersebut akan langsung terbuka dan dapat diakses kapan saja tanpa batas waktu.\n\nBerikut adalah bidang pemfokusan video materi yang tersedia saat ini:\n• Web Development (Membahas HTML, CSS, PHP Native, hingga Laravel)\n• UI/UX Design (Membahas Alur Kerja Desain dan Praktik Tool Figma)\n• Social Media Marketing (Membahas Strategi Konten, Copywriting, dan Iklan)\n• Data Analyst (Membahas Rumus Excel dan Dasar Query SQL)\n• Desain Grafis (Membahas Praktik Pembuatan Visual Menggunakan Canva)\n\nDetail susunan judul video untuk setiap kelas dapat kamu lihat langsung melalui halaman Katalog Kursus.";
        }
        
        // B5. Mentor / Pengajar
        if (
            str_contains($msg, 'mentor') ||
            str_contains($msg, 'pengajar') ||
            str_contains($msg, 'instruktur') ||
            str_contains($msg, 'guru') ||
            str_contains($msg, 'tutor') ||
            str_contains($msg, 'siapa yang ngajar')
        ) {
            return "Seluruh materi video tutorial di SkillUp disusun dan disampaikan oleh instruktur serta praktisi yang bergerak di bidangnya masing-masing. Penjelasan dalam video dirancang secara terstruktur mulai dari konsep dasar hingga contoh implementasi praktis, sehingga isi materi dapat dipelajari secara mandiri dengan mudah oleh pengguna.";
        }

        // B6. Login / Daftar / Akun / Password
        if (
            str_contains($msg, 'login') ||
            str_contains($msg, 'daftar') ||
            str_contains($msg, 'registrasi') ||
            str_contains($msg, 'akun') ||
            str_contains($msg, 'password') ||
            str_contains($msg, 'lupa password') ||
            str_contains($msg, 'reset') ||
            str_contains($msg, 'email') ||
            str_contains($msg, 'sign up') ||
            str_contains($msg, 'sign in')
        ) {
            return "Untuk pengelolaan akun di platform SkillUp, berikut adalah panduan operasionalnya:\n\n1. Pembuatan Akun (Daftar/Sign Up): Silakan akses menu 'Daftar' pada halaman web dengan mengisi data nama lengkap, alamat email aktif, dan kata sandi.\n2. Masuk Sistem (Login/Sign In): Gunakan kombinasi alamat email dan kata sandi yang telah terregistrasi melalui halaman login resmi platform.\n3. Perubahan Kata Sandi (Ubah Password): Jika kamu ingin memperbarui atau mengubah kata sandi lama, silakan masuk ke akun terlebih dahulu, lalu akses menu pengaturan yang terdapat di halaman Profil User.\n\nJika kamu mengalami kendala teknis lebih lanjut mengenai akses masuk ke sistem, silakan hubungi layanan bantuan tim support.";
        }

        // B7. Cara Beli / Akses Kursus
        if (
            str_contains($msg, 'cara beli kursus') ||
            str_contains($msg, 'gimana beli kursus') ||
            str_contains($msg, 'cara dapet kursus') ||
            str_contains($msg, 'cara akses kursus') ||
            str_contains($msg, 'cara ikut kursus') ||
            str_contains($msg, 'cara daftar kursus') ||
            str_contains($msg, 'order kursus') ||
            str_contains($msg, 'checkout kursus') ||
            str_contains($msg, 'beli kursus') ||
            $msg === 'gimana beli' ||  
            $msg === 'bagaimana cara membeli kursus' || 
            $msg === 'Bagaimana beli' 
        ) {
            return "Cara beli kursus di SkillUp super mudah! 🛒\n\n1. Daftar / login ke akun SkillUp kamu\n2. Pilih kursus yang kamu inginkan\n3. Klik tombol 'Beli Sekarang'\n4. Pilih metode pembayaran (e-wallet, transfer bank, kartu kredit)\n5. Selesaikan pembayaran\n6. Kursus langsung aktif di dashboard kamu!\n\nSeluruh proses biasanya selesai dalam 5 menit. Ada yang mau ditanyain lagi?";
        }


        // B9. Durasi / Lama Belajar / Jadwal
        if (
            str_contains($msg, 'berapa lama') ||
            str_contains($msg, 'durasi') ||
            str_contains($msg, 'jadwal') ||
            str_contains($msg, 'kapan') ||
            str_contains($msg, 'deadline') ||
            str_contains($msg, 'expired') ||
            str_contains($msg, 'kedaluwarsa') ||
            str_contains($msg, 'selesai kapan') ||
            str_contains($msg, 'lama kursus')
        ) {
            return "Di SkillUp tidak ada jadwal kelas yang kaku! ⏰ Kamu bisa belajar kapan saja dan di mana saja. Setelah beli kursus, akses materinya tidak akan pernah kedaluwarsa — seumur hidup!\n\nRata-rata siswa menyelesaikan satu kursus dalam 2–4 minggu jika belajar 1 jam per hari. Tapi tidak ada tekanan — kamu bebas atur sendiri kecepatannya.";
        }

        // B10. Refund / Garansi
        if (
            str_contains($msg, 'refund') ||
            str_contains($msg, 'uang kembali') ||
            str_contains($msg, 'garansi') ||
            str_contains($msg, 'tidak puas') ||
            str_contains($msg, 'kecewa') ||
            str_contains($msg, 'cancel') ||
            str_contains($msg, 'batal')
        ) {
            return "Mohon maaf, saat ini kami belum menyediakan opsi refund karena seluruh materi kursus langsung dapat diakses sepenuhnya setelah pembelian. Jika ada kendala teknis, tim kami akan dengan senang hati membantu proses belajarmu!";
        }

        // B11. Kontak / Support / Bantuan
        if (
            str_contains($msg, 'kontak') ||
            str_contains($msg, 'support') ||
            str_contains($msg, 'hubungi') ||
            str_contains($msg, 'bantuan') ||
            str_contains($msg, 'cs') ||
            str_contains($msg, 'customer service') ||
            str_contains($msg, 'whatsapp') ||
            str_contains($msg, 'wa') ||
            str_contains($msg, 'email') ||
            str_contains($msg, 'komplain') ||
            str_contains($msg, 'lapor')||
            $msg === 'tolong bantuan' || 
            $msg === 'minta bantuan' ||
            $msg === 'butuh bantuan' 
        ) {
            return "Untuk bantuan lebih lanjut, kamu bisa hubungi tim support SkillUp melalui:\n\n📧 Email: support@skillup.id\n💬 WhatsApp: 0821-7052-3128 (Senin–Jumat, 09.00–17.00)\n📷 Instagram: @skillup.id\n\nRespon email biasanya dalam 1x24 jam di hari kerja. Untuk pertanyaan cepat, WhatsApp lebih disarankan!";
        }

        // --------------------------------------------------------
        // BLOK C — PERCAKAPAN UMUM
        // --------------------------------------------------------

        // C1. Sapaan
        if (preg_match('/(halo|hallo|hi|hai|hey|pagi|siang|sore|malam|assalamualaikum|salam)/i', $msg)) {
            $greetings = [
                "Halo! Saya asisten SkillUp. Senang melihatmu hari ini! Ada yang bisa saya bantu terkait belajar kamu?",
                "Halo kawan belajar! Ada yang ingin kamu tanyakan seputar kursus di SkillUp?",
                "Hai! Siap untuk upgrade skill hari ini? Tanya saya apa saja ya!"
            ];
            return $greetings[array_rand($greetings)];
        }

        // C2. Kabar
        if (
            str_contains($msg, 'apa kabar') ||
            str_contains($msg, 'gimana kabar') ||
            str_contains($msg, 'how are you')
        ) {
            return "Saya baik-baik saja, siap membantu kamu! 😊 Kamu sendiri gimana? Ada yang bisa saya bantu hari ini seputar kursus di SkillUp?";
        }

        // C3. Terima Kasih
        if (
            str_contains($msg, 'makasih') ||
            str_contains($msg, 'terima kasih') ||
            str_contains($msg, 'thanks') ||
            str_contains($msg, 'thank you') ||
            str_contains($msg, 'thx')
        ) {
            $responses = [
                "Sama-sama! Semangat terus belajarnya ya. Kalau butuh bantuan lagi, panggil saya saja! 😊",
                "Dengan senang hati! Jangan ragu bertanya lagi kalau ada yang ingin kamu ketahui. Selamat belajar! 🚀",
                "Sama-sama! Sukses terus perjalanan belajar kamu di SkillUp ya! 💪"
            ];
            return $responses[array_rand($responses)];
        }

        // C4. OK / Oke / Mengerti
        if (
            $msg === 'ok' || $msg === 'oke' || $msg === 'okay' ||
            str_contains($msg, 'oke makasih') ||
            str_contains($msg, 'ok thanks') ||
            str_contains($msg, 'mengerti') ||
            str_contains($msg, 'paham') ||
            str_contains($msg, 'ngerti') ||
            str_contains($msg, 'siap')
        ) {
            $responses = [
                "Sip! Kalau ada pertanyaan lain, saya siap bantu ya. 😊",
                "Oke! Semangat belajarnya. Jangan lupa eksplorasi katalog kursus kami ya!",
                "Mantap! Ada lagi yang bisa saya bantu?"
            ];
            return $responses[array_rand($responses)];
        }

        // --------------------------------------------------------
        // BLOK D — DEFAULT
        // --------------------------------------------------------
        return "Maaf, saya belum mengerti maksudnya atau belum memiliki informasi lengkap mengenai hal tersebut. Silakan gunakan kata kunci yang lebih spesifik seputar kelas, atau kamu bisa langsung menghubungi tim support kami melalui WhatsApp di 0821-7052-3128 (Senin–Jumat, 09.00–17.00 WIB) maupun email ke support@skillup.id.";
    }

    // ============================================================
    //  ANALISIS SENTIMEN
    // ============================================================
    private static function keywordSentiment(string $text): string
    {
        $text = mb_strtolower($text);

        $positif = [
            'bagus', 'puas', 'mantap', 'keren', 'suka', 'bermanfaat',
            'mudah', 'senang', 'recommended', 'recommend', 'oke', 'top',
            'membantu', 'luar biasa', 'memuaskan', 'cepat', 'responsif'
        ];

        $negatif = [
            'buruk', 'jelek', 'kecewa', 'sulit', 'susah', 'error', 'lambat',
            'mahal', 'ribet', 'membingungkan', 'tidak jelas', 'zonk',
            'tipu', 'bohong', 'payah', 'lemot', 'mengecewakan'
        ];

        $posScore = 0;
        $negScore = 0;

        foreach ($positif as $w) {
            if (str_contains($text, $w)) $posScore++;
        }
        foreach ($negatif as $w) {
            if (str_contains($text, $w)) $negScore++;
        }

        if ($posScore > $negScore) return 'positif';
        if ($negScore > $posScore) return 'negatif';
        return 'netral';
    }
}