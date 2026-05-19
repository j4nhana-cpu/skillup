-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2026 at 09:33 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skillup_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat_history`
--

CREATE TABLE `chat_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role` enum('user','assistant') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_history`
--

INSERT INTO `chat_history` (`id`, `user_id`, `role`, `message`, `created_at`) VALUES
(17, 14, 'user', 'hai', '2026-05-15 20:33:56'),
(18, 14, 'assistant', 'Pertanyaan yang bagus! Saya masih asisten versi dasar, jadi belum paham semua hal. Kamu bisa coba tanya soal \'Web Dev\', \'Harga\', atau \'Apa itu SkillUp\' ya! 👍', '2026-05-15 20:33:56'),
(19, 14, 'user', 'hi', '2026-05-15 20:34:06'),
(20, 14, 'assistant', 'Halo! Saya asisten SkillUp. Ada yang bisa saya bantu untuk proses belajar kamu hari ini? 😊', '2026-05-15 20:34:06'),
(21, 14, 'user', 'hai', '2026-05-15 20:35:17'),
(22, 14, 'assistant', 'Halo! Saya asisten SkillUp. Ada yang bisa saya bantu untuk proses belajar kamu hari ini? 😊', '2026-05-15 20:35:17'),
(23, 14, 'user', 'apa itu', '2026-05-15 20:35:40'),
(24, 14, 'assistant', 'Halo! Saya asisten SkillUp. Ada yang bisa saya bantu untuk proses belajar kamu hari ini? 😊', '2026-05-15 20:35:40'),
(25, 14, 'user', 'skillup', '2026-05-15 20:35:55'),
(26, 14, 'assistant', 'Halo! Saya asisten SkillUp. Ada yang bisa saya bantu untuk proses belajar kamu hari ini? 😊', '2026-05-15 20:35:55'),
(27, 14, 'user', 'apa itu skill up', '2026-05-15 20:36:14'),
(28, 14, 'assistant', 'Halo! Saya asisten SkillUp. Ada yang bisa saya bantu untuk proses belajar kamu hari ini? 😊', '2026-05-15 20:36:14'),
(29, 14, 'user', 'hai', '2026-05-15 20:38:17'),
(30, 14, 'assistant', 'Halo! Saya asisten SkillUp. Ada yang bisa saya bantu untuk proses belajar kamu hari ini? 😊', '2026-05-15 20:38:17'),
(31, 14, 'user', 'apa itu skill up', '2026-05-15 20:38:23'),
(32, 14, 'assistant', 'SkillUp adalah platform belajar digital terbaik! Kamu bisa belajar Web Dev, Desain, dan Marketing di sini dengan mentor profesional.', '2026-05-15 20:38:23'),
(33, 14, 'user', 'rekomendasi kursus yang cocok', '2026-05-15 20:38:38'),
(34, 14, 'assistant', 'Kami punya berbagai kursus menarik seperti PHP, UI/UX, dan Digital Marketing. Kamu mau mulai belajar yang mana?', '2026-05-15 20:38:38'),
(35, 14, 'user', 'php', '2026-05-15 20:38:42'),
(36, 14, 'assistant', 'Halo! Saya asisten SkillUp. Ada yang bisa saya bantu untuk proses belajar kamu hari ini? 😊', '2026-05-15 20:38:42'),
(37, 14, 'user', 'hai', '2026-05-15 20:40:12'),
(38, 14, 'assistant', 'Halo kawan belajar! Ada yang ingin kamu tanyakan seputar kursus di SkillUp?', '2026-05-15 20:40:12'),
(39, 14, 'user', 'apa itu skill up', '2026-05-15 20:40:18'),
(40, 14, 'assistant', 'Halo! Saya asisten SkillUp. Senang melihatmu hari ini! Ada yang bisa saya bantu terkait belajar kamu?', '2026-05-15 20:40:18'),
(41, 14, 'user', 'apa itu skill up', '2026-05-15 20:41:46'),
(42, 14, 'assistant', 'SkillUp adalah platform e-learning masa kini yang fokus pada skill digital. Kami menyediakan kursus mulai dari pemrograman, desain grafis, hingga marketing dengan kurikulum yang mudah dipahami pemula!', '2026-05-15 20:41:46'),
(43, 14, 'user', 'skill up', '2026-05-15 20:42:13'),
(44, 14, 'assistant', 'SkillUp adalah platform e-learning masa kini yang fokus pada skill digital. Kami menyediakan kursus mulai dari pemrograman, desain grafis, hingga marketing dengan kurikulum yang mudah dipahami pemula!', '2026-05-15 20:42:13'),
(45, 14, 'user', 'skillup', '2026-05-15 20:42:16'),
(46, 14, 'assistant', 'SkillUp adalah platform e-learning masa kini yang fokus pada skill digital. Kami menyediakan kursus mulai dari pemrograman, desain grafis, hingga marketing dengan kurikulum yang mudah dipahami pemula!', '2026-05-15 20:42:16'),
(47, 14, 'user', 'hai', '2026-05-15 20:42:19'),
(48, 14, 'assistant', 'Hai! Siap untuk upgrade skill hari ini? Tanya saya apa saja ya!', '2026-05-15 20:42:19'),
(49, 14, 'user', 'selamat malam', '2026-05-15 20:42:22'),
(50, 14, 'assistant', 'Halo kawan belajar! Ada yang ingin kamu tanyakan seputar kursus di SkillUp?', '2026-05-15 20:42:22'),
(51, 14, 'user', 'p', '2026-05-15 20:42:26'),
(52, 14, 'assistant', 'Hai! Siap untuk upgrade skill hari ini? Tanya saya apa saja ya!', '2026-05-15 20:42:26'),
(53, 14, 'user', 'Konsep teknis yang membingungkan', '2026-05-15 20:42:44'),
(54, 14, 'assistant', 'Halo! Saya asisten SkillUp. Senang melihatmu hari ini! Ada yang bisa saya bantu terkait belajar kamu?', '2026-05-15 20:42:44'),
(55, 14, 'user', 'kursusnya ada apa aja', '2026-05-15 20:43:13'),
(56, 14, 'assistant', 'Kamu bisa mulai belajar dengan memilih kursus di halaman katalog. Setiap kursus punya video materi dan tugas praktik agar kamu cepat mahir!', '2026-05-15 20:43:13'),
(57, 14, 'user', 'hai', '2026-05-15 20:54:20'),
(58, 14, 'assistant', 'Halo! Saya asisten SkillUp. Senang melihatmu hari ini! Ada yang bisa saya bantu terkait belajar kamu?', '2026-05-15 20:54:20'),
(59, 14, 'user', 'skill up itu apa', '2026-05-15 20:55:18'),
(60, 14, 'assistant', 'Saya masih asisten dalam tahap belajar. Untuk bantuan lebih lanjut, kamu juga bisa hubungi support@skillup.id ya!', '2026-05-15 20:55:18'),
(61, 14, 'user', 'skillup itu apa', '2026-05-15 20:55:27'),
(62, 14, 'assistant', 'Wah, pertanyaan menarik! Tapi saya belum punya info lengkap soal itu. Coba tanya soal \'kursus\', \'harga\', \'sertifikat\', atau \'cara beli\' ya!', '2026-05-15 20:55:27'),
(63, 14, 'user', 'skill up', '2026-05-15 20:57:59'),
(64, 14, 'assistant', 'SkillUp adalah platform e-learning masa kini yang fokus pada skill digital. Kami menyediakan kursus mulai dari pemrograman, desain grafis, hingga marketing dengan kurikulum yang mudah dipahami pemula!', '2026-05-15 20:57:59'),
(65, 14, 'user', 'apa itu skill up', '2026-05-15 20:58:05'),
(66, 14, 'assistant', 'SkillUp adalah platform e-learning masa kini yang fokus pada skill digital. Kami menyediakan kursus mulai dari pemrograman, desain grafis, hingga marketing dengan kurikulum yang mudah dipahami pemula!', '2026-05-15 20:58:05'),
(67, 14, 'user', 'duh', '2026-05-15 20:58:19'),
(68, 14, 'assistant', 'Wah, pertanyaan menarik! Tapi saya belum punya info lengkap soal itu. Coba tanya soal \'kursus\', \'harga\', \'sertifikat\', atau \'cara beli\' ya!', '2026-05-15 20:58:19'),
(69, 14, 'user', 'harga', '2026-05-15 20:58:23'),
(70, 14, 'assistant', 'Kami sering adain promo lho! Pantau terus halaman utama SkillUp atau follow sosmed kami untuk dapat info diskon lebih awal. Metode pembayaran kami sangat lengkap: e-wallet, transfer bank, bahkan kartu kredit bisa dicicil 0%.', '2026-05-15 20:58:23'),
(71, 14, 'user', 'gimana cara beli kursus', '2026-05-15 20:59:16'),
(72, 14, 'assistant', 'Di SkillUp, kamu bisa belajar sesuai kecepatanmu sendiri. Tidak ada batas waktu, materi bisa diakses selamanya setelah dibeli.', '2026-05-15 20:59:16'),
(73, 14, 'user', 'gimana', '2026-05-15 21:00:44'),
(74, 14, 'assistant', 'Wah, pertanyaan menarik! Tapi saya belum punya info lengkap soal itu. Coba tanya soal \'kursus\', \'harga\', \'sertifikat\', atau \'cara beli\' ya!', '2026-05-15 21:00:44'),
(75, 14, 'user', 'gimana beli', '2026-05-15 21:00:52'),
(76, 14, 'assistant', 'Cara beli kursus di SkillUp super mudah! 🛒\n\n1. Daftar / login ke akun SkillUp kamu\n2. Pilih kursus yang kamu inginkan\n3. Klik tombol \'Beli Sekarang\'\n4. Pilih metode pembayaran (e-wallet, transfer bank, kartu kredit)\n5. Selesaikan pembayaran\n6. Kursus langsung aktif di dashboard kamu!\n\nSeluruh proses biasanya selesai dalam 5 menit. Ada yang mau ditanyain lagi?', '2026-05-15 21:00:52'),
(77, 14, 'user', 'beli', '2026-05-15 21:00:59'),
(78, 14, 'assistant', 'Cara beli kursus di SkillUp super mudah! 🛒\n\n1. Daftar / login ke akun SkillUp kamu\n2. Pilih kursus yang kamu inginkan\n3. Klik tombol \'Beli Sekarang\'\n4. Pilih metode pembayaran (e-wallet, transfer bank, kartu kredit)\n5. Selesaikan pembayaran\n6. Kursus langsung aktif di dashboard kamu!\n\nSeluruh proses biasanya selesai dalam 5 menit. Ada yang mau ditanyain lagi?', '2026-05-15 21:00:59'),
(79, 14, 'user', 'bisa akses kapan aja?', '2026-05-15 21:01:29'),
(80, 14, 'assistant', 'Di SkillUp tidak ada jadwal kelas yang kaku! ⏰ Kamu bisa belajar kapan saja dan di mana saja. Setelah beli kursus, akses materinya tidak akan pernah kedaluwarsa — seumur hidup!\n\nRata-rata siswa menyelesaikan satu kursus dalam 2–4 minggu jika belajar 1 jam per hari. Tapi tidak ada tekanan — kamu bebas atur sendiri kecepatannya.', '2026-05-15 21:01:29'),
(81, 14, 'user', 'refund', '2026-05-15 21:06:59'),
(82, 14, 'assistant', 'Mohon maaf, saat ini kami belum menyediakan opsi refund karena seluruh materi kursus langsung dapat diakses sepenuhnya setelah pembelian. Jika ada kendala teknis, tim kami akan dengan senang hati membantu proses belajarmu!', '2026-05-15 21:06:59'),
(83, 14, 'user', 'bagus', '2026-05-15 21:07:34'),
(84, 14, 'assistant', 'Wah, pertanyaan menarik! Tapi saya belum punya info lengkap soal itu. Coba tanya soal \'kursus\', \'harga\', \'sertifikat\', atau \'cara beli\' ya!', '2026-05-15 21:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `mentor_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `thumbnail` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT 'Umum',
  `level` enum('Pemula','Menengah','Mahir') DEFAULT 'Pemula',
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `rating_avg` decimal(3,2) DEFAULT 0.00,
  `total_students` int(10) UNSIGNED DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `mentor_id`, `title`, `description`, `price`, `thumbnail`, `category`, `level`, `status`, `rating_avg`, `total_students`, `created_at`) VALUES
(18, 15, 'laravel untuk pengguna', 'coba coba', 400000.00, 'uploads/thumbnails/thumb_6a078b86c2a976.09527808.png', 'Web Development', 'Pemula', 'published', 5.00, 1, '2026-05-15 21:09:26'),
(19, 15, 'Penjelasan tentang Data Analyst', 'penjelasan apa itu Data Analyst, pekerjaan nya dan lain lain', 360000.00, 'uploads/thumbnails/thumb_6a08acc6015c67.42320904.png', 'Data Science', 'Menengah', 'published', 0.00, 1, '2026-05-16 17:43:34');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `order_code` varchar(50) DEFAULT NULL,
  `amount_paid` decimal(12,2) DEFAULT 0.00,
  `status` enum('pending','active','expired') DEFAULT 'pending',
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `duration_months` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `order_code`, `amount_paid`, `status`, `enrolled_at`, `expires_at`, `duration_months`) VALUES
(54, 14, 18, 'ORD-20260516-4BB0E1', 400000.00, 'active', '2026-05-15 21:12:38', NULL, 1),
(56, 14, 19, 'ORD-20260517-1FB33E', 360000.00, 'active', '2026-05-16 19:22:58', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mentor_payouts`
--

CREATE TABLE `mentor_payouts` (
  `id` int(11) NOT NULL,
  `mentor_id` int(10) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `bank_name` varchar(50) NOT NULL,
  `account_number` varchar(30) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `status` enum('pending','processed','rejected') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mentor_payouts`
--

INSERT INTO `mentor_payouts` (`id`, `mentor_id`, `amount`, `bank_name`, `account_number`, `account_name`, `status`, `notes`, `requested_at`, `processed_at`) VALUES
(3, 15, 532000.00, 'bca', '94883823', 'bca - 94883823', 'rejected', 'Ditolak oleh admin.', '2026-05-16 19:30:00', '2026-05-16 19:30:36'),
(4, 15, 532000.00, 'bca', '94883823', 'bca - 94883823', 'processed', 'Disetujui oleh admin.', '2026-05-16 19:30:52', '2026-05-16 19:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `revenue_shares`
--

CREATE TABLE `revenue_shares` (
  `id` int(10) UNSIGNED NOT NULL,
  `enrollment_id` int(10) UNSIGNED NOT NULL,
  `mentor_id` int(10) UNSIGNED NOT NULL,
  `gross_amount` decimal(12,2) NOT NULL,
  `platform_cut` decimal(12,2) NOT NULL,
  `mentor_share` decimal(12,2) NOT NULL,
  `status` enum('pending','settled') DEFAULT 'pending',
  `settled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenue_shares`
--

INSERT INTO `revenue_shares` (`id`, `enrollment_id`, `mentor_id`, `gross_amount`, `platform_cut`, `mentor_share`, `status`, `settled_at`, `created_at`) VALUES
(15, 54, 15, 400000.00, 120000.00, 280000.00, 'settled', '2026-05-16 19:31:25', '2026-05-15 21:12:58'),
(16, 56, 15, 360000.00, 108000.00, 252000.00, 'settled', '2026-05-16 19:31:25', '2026-05-16 19:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `student_id` int(10) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `sentiment` enum('positif','netral','negatif') DEFAULT 'netral',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `student_id`, `course_id`, `rating`, `comment`, `sentiment`, `created_at`) VALUES
(6, 14, 18, 5, 'bagus', 'positif', '2026-05-15 21:13:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','mentor','admin') DEFAULT 'student',
  `avatar` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `avatar`, `bank_account`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Admin SkillUp', 'admin@skillup.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'uploads/avatars/avatar_1_1778875028.jpg', NULL, 1, '2026-05-02 10:56:15', '2026-05-15 19:57:08'),
(14, 'hana', 'hana@gmail.com', '$2y$12$u4cqvNtWckVWuOEFjYVHU.0qZFvKozIiTvGECpy0bYB95aql7gbqq', 'student', NULL, NULL, 1, '2026-05-15 20:27:30', '2026-05-15 20:27:30'),
(15, 'ana', 'ana@gmail.com', '$2y$12$ArYoo6wrXlCZwB4F9nBMCex3LyoVUXWAXQPEc7YOvwX4gf3cHTZY6', 'mentor', 'uploads/avatars/avatar_15_1778879332.jpg', 'bca - 94883823', 1, '2026-05-15 21:08:35', '2026-05-15 21:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` int(10) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `video_url` varchar(500) NOT NULL,
  `order_num` tinyint(3) UNSIGNED DEFAULT 1,
  `is_preview` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `course_id`, `title`, `video_url`, `order_num`, `is_preview`, `created_at`) VALUES
(22, 18, 'laravel untuk pengguna - Bagian 1', '/uploads/videos/video_6a078b86c7f5d1.16740017.mp4', 1, 0, '2026-05-15 21:09:26'),
(23, 19, 'Penjelasan tentang Data Analyst - Bagian 1', '/uploads/videos/video_6a08acc602ff50.08630809.mp4', 1, 0, '2026-05-16 17:43:34'),
(24, 19, 'Penjelasan tentang Data Analyst - Bagian 2', '/uploads/videos/video_6a08acec0d6489.80825708.mp4', 2, 0, '2026-05-16 17:44:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_chat` (`user_id`,`created_at`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_mentor` (`mentor_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_enrollment` (`student_id`,`course_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_order` (`order_code`);

--
-- Indexes for table `mentor_payouts`
--
ALTER TABLE `mentor_payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mentor_id` (`mentor_id`);

--
-- Indexes for table `revenue_shares`
--
ALTER TABLE `revenue_shares`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollment_id` (`enrollment_id`),
  ADD KEY `mentor_id` (`mentor_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_review` (`student_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_course` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `mentor_payouts`
--
ALTER TABLE `mentor_payouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `revenue_shares`
--
ALTER TABLE `revenue_shares`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD CONSTRAINT `chat_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mentor_payouts`
--
ALTER TABLE `mentor_payouts`
  ADD CONSTRAINT `mentor_payouts_ibfk_1` FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `revenue_shares`
--
ALTER TABLE `revenue_shares`
  ADD CONSTRAINT `revenue_shares_ibfk_1` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revenue_shares_ibfk_2` FOREIGN KEY (`mentor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
