# 🎓 SkillUp — Marketplace Kursus Keterampilan Digital

Platform e-learning PHP Native yang mempertemukan mentor ahli dengan pelajar.

---

## ✅ Fitur Utama

| Fitur | Detail |
|-------|--------|
| 🔐 Autentikasi | Login/Register dengan bcrypt password hashing + CSRF protection |
| 🎬 Video Akses Terlindungi | Hanya pelajar yang sudah membeli yang bisa menonton |
| 💳 Payment Gateway | Integrasi Midtrans Snap (transfer bank, GoPay, QRIS, dll) |
| 🤖 AI Chatbot | Asisten belajar berbasis keyword matching dengan fallback AI |
| 🧠 Analisis Sentimen | Ulasan otomatis dianalisis (positif/netral/negatif) via keyword AI |
| 📊 Dashboard Mentor | Statistik pendapatan, siswa, dan grafik bulanan |
| 💰 Sistem Bagi Hasil | Otomatis 70% mentor / 30% platform setiap transaksi |
| ⚙️ Admin Panel | Kelola user, kursus, ulasan, dan laporan revenue |

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.1+ Native (tanpa framework)
- **Database**: MySQL 8.0+ (7 tabel dengan relasi)
- **Frontend**: HTML + CSS murni (tanpa framework CSS)
- **API Pihak Ketiga**:
  - 💳 Midtrans Snap API (Payment Gateway)

---

## 🚀 Cara Menjalankan (Lokal dengan XAMPP)

### Prasyarat
- XAMPP (PHP 8.1+, MySQL 8.0+, Apache dengan `mod_rewrite` aktif)

### Langkah 1 — Salin Project
```
Salin folder skillup/ ke: C:/xampp/htdocs/skillup/
```

### Langkah 2 — Setup Database
```
1. Buka phpMyAdmin: http://localhost/phpmyadmin
2. Buat database baru: skillup_db
3. Import file: database/skillup_db.sql
```

### Langkah 3 — Konfigurasi
```
Buka file: config/config.php
Sesuaikan:
  DB_HOST     = localhost
  DB_NAME     = skillup_db
  DB_USER     = root
  DB_PASS     = (kosong jika default XAMPP)

  MIDTRANS_SERVER_KEY = (isi dari dashboard.midtrans.com)
  MIDTRANS_CLIENT_KEY = (isi dari dashboard.midtrans.com)
  MIDTRANS_IS_PRODUCTION = false
```

### Langkah 4 — Jalankan
```
Buka browser: http://localhost/skillup/public/
```

---

## 👤 Akun Demo

| Role | Email | Password |
|------|-------|----------|
👤 Student | hana@gmail.com  | hana123
🧑‍🏫 Mentor  | ana@gmail.com   | ana123
⚙️ Admin   | admin@skillup.id | password

---

## 📁 Struktur Folder

```
skillup/
├── config/
│   └── config.php          # Konfigurasi utama (DB, API keys, dll)
├── database/
│   └── skillup_db (2).sql      # Schema + data demo
├── public/                 # ← Document root web server
│   ├── index.php           # Front controller / router
│   ├── .htaccess           # URL rewriting Apache
│   └── uploads/            # Thumbnail & video kursus
├── src/
│   ├── controllers/        # Logic bisnis
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── StudentController.php
│   │   ├── MentorController.php
│   │   ├── AdminController.php
│   │   └── APIController.php
│   ├── helpers/
│   │   ├── Database.php    # PDO wrapper (singleton)
│   │   ├── helpers.php     # Fungsi utilitas
│   │   ├── AIService.php   # Chatbot & analisis sentimen
│   │   └── Payment.php     # Midtrans API
│   └── middleware/
│       └── Auth.php        # Autentikasi & CSRF
└── views/
    ├── layouts/            # Header, footer, flash
    ├── auth/               # Login, register
    ├── student/            # Dashboard, kursus, video, chat
    ├── mentor/             # Dashboard, kursus, revenue
    └── admin/              # Panel admin
```

---

## 🔐 Implementasi Keamanan

| Aspek | Implementasi |
|-------|-------------|
| Password | `password_hash()` dengan bcrypt (cost=12) |
| CSRF | Token per-session di setiap form POST |
| SQL Injection | PDO Prepared Statements di seluruh query |
| XSS | `htmlspecialchars()` di semua output (`e()`) |
| Session | `httponly`, `samesite=Lax`, regenerate on login |
| File Upload | Validasi MIME type + ekstensi + ukuran maks |
| Akses Konten | Middleware role-check sebelum akses video |

---

## 👥 Role Pengguna

| Role | Akses |
|------|-------|
| **Student** | Beli kursus, tonton video, beri ulasan, chat AI |
| **Mentor** | Buat & kelola kursus, upload video, lihat pendapatan, ajukan payout |
| **Admin** | Kelola semua user & kursus, verifikasi payout, monitor revenue |