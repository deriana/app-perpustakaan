-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 26, 2024 at 03:16 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id_activity_logs` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `activity_type` enum('borrow','return','add','remove','update') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_books` int DEFAULT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id_activity_logs`, `id_user`, `activity_type`, `id_books`, `timestamp`) VALUES
(31, 3, 'borrow', 10, '2024-10-20 11:19:28'),
(32, 3, 'add', 11, '2024-10-20 11:19:34'),
(33, 3, 'add', 13, '2024-10-20 11:19:39'),
(34, 3, 'add', 14, '2024-10-20 11:19:45'),
(35, 3, 'add', 15, '2024-10-20 11:19:49'),
(36, 3, 'add', 17, '2024-10-20 11:19:53'),
(37, 3, 'borrow', 11, '2024-10-20 11:20:03'),
(38, 3, 'borrow', 13, '2024-10-20 11:20:03'),
(39, 3, 'borrow', 14, '2024-10-20 11:20:03'),
(40, 3, 'borrow', 15, '2024-10-20 11:20:03'),
(41, 3, 'borrow', 17, '2024-10-20 11:20:03'),
(42, 3, 'remove', 11, '2024-10-20 11:20:03'),
(43, 3, 'remove', 13, '2024-10-20 11:20:03'),
(44, 3, 'remove', 14, '2024-10-20 11:20:03'),
(45, 3, 'remove', 15, '2024-10-20 11:20:03'),
(46, 3, 'remove', 17, '2024-10-20 11:20:03'),
(48, 3, 'return', 8, '2024-10-21 07:41:51'),
(49, 3, 'add', 8, '2024-10-21 07:42:07'),
(50, 3, 'add', 28, '2024-10-21 07:42:15'),
(51, 3, 'borrow', 8, '2024-10-21 07:42:19'),
(52, 3, 'borrow', 28, '2024-10-21 07:42:19'),
(53, 3, 'remove', 8, '2024-10-21 07:42:19'),
(54, 3, 'remove', 28, '2024-10-21 07:42:19'),
(55, 3, 'return', 9, '2024-10-21 11:32:16'),
(56, 3, 'return', 12, '2024-10-21 11:32:32'),
(57, 3, 'return', 16, '2024-10-21 11:32:37'),
(58, 3, 'add', 9, '2024-10-21 11:32:45'),
(59, 3, 'add', 12, '2024-10-21 11:32:51'),
(60, 3, 'borrow', 9, '2024-10-21 11:32:58'),
(61, 3, 'borrow', 12, '2024-10-21 11:32:58'),
(62, 3, 'remove', 9, '2024-10-21 11:32:58'),
(63, 3, 'remove', 12, '2024-10-21 11:32:58'),
(64, 3, 'add', 29, '2024-10-21 12:13:59'),
(65, 3, 'borrow', 29, '2024-10-21 12:14:05'),
(66, 3, 'remove', 29, '2024-10-21 12:14:05'),
(67, 3, 'return', 29, '2024-10-21 12:14:20'),
(68, 3, 'return', 8, '2024-10-26 18:50:50'),
(69, 3, 'add', 8, '2024-10-26 18:51:29'),
(70, 3, 'return', 13, '2024-10-26 19:16:19'),
(71, 3, 'return', 8, '2024-10-26 19:31:31'),
(72, 3, 'borrow', 8, '2024-10-26 19:31:49'),
(73, 3, 'remove', 8, '2024-10-26 19:31:49'),
(74, 3, 'add', 29, '2024-10-26 19:31:58'),
(75, 3, 'add', 29, '2024-10-26 19:35:13'),
(76, 13, 'add', 8, '2024-10-26 20:38:14'),
(77, 13, 'add', 9, '2024-10-26 20:38:17'),
(78, 13, 'add', 10, '2024-10-26 20:38:21'),
(79, 13, 'add', 11, '2024-10-26 20:38:24'),
(80, 13, 'borrow', 8, '2024-10-26 20:38:26'),
(81, 13, 'borrow', 9, '2024-10-26 20:38:26'),
(82, 13, 'borrow', 10, '2024-10-26 20:38:26'),
(83, 13, 'borrow', 11, '2024-10-26 20:38:26'),
(84, 13, 'remove', 8, '2024-10-26 20:38:26'),
(85, 13, 'remove', 9, '2024-10-26 20:38:26'),
(86, 13, 'remove', 10, '2024-10-26 20:38:27'),
(87, 13, 'remove', 11, '2024-10-26 20:38:27'),
(88, 13, 'return', 8, '2024-10-26 22:01:14'),
(89, 13, 'return', 9, '2024-10-26 22:01:19');

-- --------------------------------------------------------

--
-- Table structure for table `activity_reports`
--

CREATE TABLE `activity_reports` (
  `id_activity_reports` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_books` int DEFAULT NULL,
  `activity_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id_books` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `author` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `synopsis` text COLLATE utf8mb4_general_ci NOT NULL,
  `cover_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `book_date` date NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `is_favorite` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id_books`, `title`, `author`, `synopsis`, `cover_path`, `book_date`, `is_read`, `is_favorite`) VALUES
(8, 'Kaiju no 8', 'Naoya Matsumoto', 'Kafka Hibino menjalani kehidupan membosankan sebagai anggota tim pembersih setelah serangan monster raksasa (kaiju). Meski bercita-cita masuk Pasukan Pertahanan, umurnya yang tak lagi muda membuatnya tak berani mengejar mimpi itu. Namun, hidupnya berubah ketika dia secara misterius berubah menjadi kaiju. Dengan kekuatan monster di tubuhnya, Kafka memutuskan untuk bergabung dengan Pasukan Pertahanan sambil menyembunyikan identitasnya yang berbahaya. Cerita ini mengikuti perjuangan Kafka untuk mengendalikan kekuatannya dan melindungi orang-orang yang dicintainya dari ancaman kaiju lain.', '6713606513377_kaiju no 8.jpg', '2024-10-19', 0, 0),
(9, 'Dandadan', 'Yukinobu Tatsu', 'Momo Ayase adalah seorang gadis yang percaya pada hantu, sedangkan teman sekelasnya, Okarun, yakin bahwa alien itu nyata. Saat mereka bertengkar mengenai keberadaan entitas supernatural, mereka setuju untuk membuktikan kebenaran satu sama lain. Namun, situasi berubah menjadi kacau ketika mereka berdua benar-benar menghadapi hantu dan alien di dunia nyata. Momo dan Okarun akhirnya terlibat dalam serangkaian kejadian aneh, penuh aksi, humor, dan romansa. Manga ini menyajikan kombinasi menarik antara elemen horor, fiksi ilmiah, dan kejenakaan sehari-hari.', '671361430d691_dandadan.jpg', '2024-10-19', 0, 0),
(10, 'Panorama of Hell', 'Hideshi Hino', 'Berpusat pada seorang seniman yang terobsesi dengan darahnya sendiri, manga ini menggambarkan perjalanan pribadi seniman tersebut ke dalam dunia kelam yang dipenuhi penderitaan dan trauma masa lalunya. Karya seninya, yang terbuat dari darah, menciptakan gambaran mengerikan tentang neraka, yang mencerminkan rasa sakit batin yang dia alami. Sepanjang manga ini, pembaca dibawa menyelami pikiran seniman tersebut, menelusuri masa lalunya yang penuh dengan tragedi dan kekerasan, serta bagaimana trauma itu terus menghantuinya hingga hari ini.', '67136164235c5_paranormal.jpg', '2024-10-19', 0, 0),
(11, 'Stitches', 'Junji Ito dan Hirokatsu Kihara', 'Dibuat oleh maestro horor Junji Ito dan penulis misteri Hirokatsu Kihara, Stitches adalah antologi cerita pendek yang mengambil inspirasi dari misteri-misteri nyata yang belum terpecahkan. Setiap cerita membawa pembaca ke dunia yang dipenuhi dengan ketakutan, di mana setiap petunjuk mengarah pada kengerian yang tak terbayangkan. Cerita-cerita ini tidak hanya membuat pembaca takut, tetapi juga menimbulkan rasa penasaran yang mendalam terhadap misteri yang melatarbelakanginya. Stitches adalah salah satu manga horor yang menghadirkan atmosfer menakutkan dengan cara yang unik dan tak terduga.', '67136179a19e5_junji ito.jpg', '2024-10-19', 0, 0),
(12, 'One Piece', 'Eiichiro Oda', 'Luffy dan kru Topi Jerami melanjutkan perjalanan mereka untuk menemukan harta karun legendaris, One Piece, yang diyakini akan membuat pemiliknya menjadi Raja Bajak Laut. Di arc terbaru, Luffy menghadapi kekuatan terkuat di Grand Line dan mengungkap misteri dunia, termasuk sejarah D. dan Rahasia Poneglyph. Sementara itu, kru Topi Jerami terus berhadapan dengan ancaman besar dari pemerintah dunia dan Yonko, penguasa lautan. Petualangan ini penuh dengan aksi, persahabatan, dan humor, serta pesan-pesan tentang kebebasan dan impian.', '6713619b6c24a_one piece.jpg', '2024-10-19', 0, 0),
(13, 'Spy X Family', 'Tatsuya Endo', 'Loid Forger, alias Twilight, adalah mata-mata elit yang diberi misi untuk menyusup ke sekolah elit di negara musuh. Untuk itu, dia membangun keluarga palsu dengan mengadopsi seorang gadis kecil bernama Anya, yang memiliki kemampuan membaca pikiran, dan menikahi Yor, seorang pembunuh bayaran yang merahasiakan identitasnya. Meski misi ini penuh dengan intrik, hal-hal yang tidak terduga terjadi saat keluarga palsu ini perlahan-lahan mulai merasa seperti keluarga sungguhan. Setiap anggota keluarga memiliki rahasia yang tak diketahui oleh yang lain, menciptakan situasi yang penuh dengan humor dan ketegangan.', '671361b5112e0_spy x family.jpg', '2024-10-19', 0, 0),
(14, 'Jujutsu Kaisen', 'Gege Akutami', 'Yuji Itadori, seorang siswa SMA biasa, tiba-tiba terjerat dalam dunia ilmu kutukan ketika dia menelan jari terkutuk milik Ryoumen Sukuna, Raja Kutukan. Untuk mencegah kehancuran dunia, Yuji bergabung dengan Sekolah Jujutsu untuk mengendalikan kutukan yang ada di dalam dirinya. Di sekolah, dia belajar cara menghadapi makhluk-makhluk kutukan yang menyerang manusia. Sepanjang perjalanan, Yuji dan teman-temannya terlibat dalam pertempuran sengit melawan para pengguna kutukan yang ingin menguasai dunia dengan kekuatan jahat.', '671361e9ba507_jjk.jpg', '2024-10-19', 0, 0),
(15, 'Boku No Hero Academia', 'Horikoshi Kohei', 'Di dunia di mana hampir semua orang memiliki kekuatan super yang disebut Quirk, Izuku Midoriya adalah salah satu dari sedikit orang yang terlahir tanpa kekuatan. Namun, setelah bertemu dengan pahlawan nomor satu, All Might, Midoriya diberikan kekuatan Quirk terkuat, One For All. Dengan kekuatan ini, Midoriya bergabung dengan Akademi U.A., sekolah khusus bagi para calon pahlawan. Cerita ini mengikuti perjalanannya menjadi pahlawan pro dan perjuangan melawan Liga Penjahat yang dipimpin oleh Tomura Shigaraki, yang memiliki ambisi menghancurkan masyarakat pahlawan.', '6713620ad36fc_bnha.jpg', '2024-10-19', 0, 0),
(16, 'Tokyo Revengers', 'Wakui Ken', 'Takemichi Hanagaki, seorang pria berusia 26 tahun, mendapati hidupnya berantakan setelah kematian mantan pacarnya, Hinata. Secara misterius, dia kembali ke masa lalu saat dia masih remaja, tepat sebelum dirinya bergabung dengan geng Tokyo Manji. Dengan pengetahuannya tentang masa depan, Takemichi bertekad untuk mengubah sejarah dan menyelamatkan Hinata dari nasib tragisnya. Sepanjang manga ini, Takemichi terlibat dalam pertempuran antara geng-geng jalanan yang penuh dengan pengkhianatan, kekerasan, dan persahabatan yang diuji waktu.', '6713622228c0c_tokyo revengers.jpg', '2024-10-19', 0, 0),
(17, 'Oshi No Ko', 'Aka Akasaka', 'Aqua dan Ruby adalah anak kembar dari seorang idol terkenal, Ai Hoshino, yang secara misterius dibunuh. Keduanya terlahir kembali dengan memori kehidupan mereka sebelumnya, dan mereka memutuskan untuk mengejar impian masing-masing di dunia hiburan sambil mencoba mengungkap siapa yang bertanggung jawab atas kematian ibu mereka. Manga ini mengeksplorasi sisi gelap industri hiburan Jepang, dari tekanan terhadap para idol hingga manipulasi media, serta bagaimana Aqua dan Ruby menghadapi trauma dan misteri di balik kehidupan mereka.', '671362351c212_oshi no ko.jpg', '2024-10-19', 0, 0),
(28, 'Furry', 'Ridho', 'RIdho Suka Furry', '6714fcaa8727e_c4cde5e0-0646-475b-b5ff-2cce6f9cda86.jpg', '2024-10-20', 0, 0),
(29, '10 Dosa besar Soeharto', 'JOKOWI', 'Masih Nanya', '6715c8004e0b0_7725825.jpg', '2024-10-21', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `borrows`
--

CREATE TABLE `borrows` (
  `id_borrows` int NOT NULL,
  `id_user` int NOT NULL,
  `id_books` int NOT NULL,
  `borrow_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('borrowed','returned') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'borrowed',
  `is_read` tinyint(1) DEFAULT '0',
  `is_favorite` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrows`
--

INSERT INTO `borrows` (`id_borrows`, `id_user`, `id_books`, `borrow_date`, `return_date`, `status`, `is_read`, `is_favorite`) VALUES
(12, 3, 17, '2024-10-19', '2024-10-19', 'returned', 0, 0),
(22, 3, 8, '2024-10-19', '2024-10-21', 'returned', 1, 0),
(23, 3, 9, '2024-10-19', '2024-10-21', 'returned', 1, 0),
(24, 3, 12, '2024-10-19', '2024-10-21', 'returned', 1, 0),
(25, 3, 16, '2024-10-19', '2024-10-21', 'returned', 0, 0),
(29, 3, 10, '2024-10-20', NULL, 'borrowed', 1, 1),
(30, 3, 11, '2024-10-20', NULL, 'borrowed', 0, 0),
(31, 3, 13, '2024-10-20', '2024-10-26', 'returned', 0, 0),
(32, 3, 14, '2024-10-20', NULL, 'borrowed', 0, 0),
(33, 3, 15, '2024-10-20', NULL, 'borrowed', 0, 0),
(34, 3, 17, '2024-10-20', NULL, 'borrowed', 0, 0),
(36, 3, 8, '2024-10-21', '2024-10-26', 'returned', 0, 0),
(37, 3, 28, '2024-10-21', NULL, 'borrowed', 0, 0),
(38, 3, 9, '2024-10-21', NULL, 'borrowed', 0, 0),
(39, 3, 12, '2024-10-21', NULL, 'borrowed', 0, 0),
(40, 3, 29, '2024-10-21', '2024-10-21', 'returned', 1, 0),
(41, 3, 8, '2024-10-26', NULL, 'borrowed', 0, 0),
(42, 13, 8, '2024-10-26', '2024-10-26', 'returned', 0, 0),
(43, 13, 9, '2024-10-26', '2024-10-26', 'returned', 1, 0),
(44, 13, 10, '2024-10-26', NULL, 'borrowed', 0, 0),
(45, 13, 11, '2024-10-26', NULL, 'borrowed', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_books` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `user_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','users') COLLATE utf8mb4_general_ci DEFAULT 'users',
  `pf_img` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `user_name`, `user_password`, `role`, `pf_img`) VALUES
(3, 'jeanne', '$2y$10$XeTiaeaTO/.vtDBU8KDjEuFgnY/.8Yi1YEykBm3eQIVE/jtaAMtcS', 'users', '671cecd796d03_GOJ_11vXkAEqz7V.jpeg'),
(4, 'kafka', '$2y$10$UocbnPoyOCN9.rTVpS0OmODVTzIfZz8NUuOYnwLHjHKpjzJ7SUAhq', 'users', NULL),
(5, 'test', '$2y$10$Iy5XDW4Zrca8ItMbGh6V/OvykKrx.UVMkRf6V4T5JMX9EqH6Bj7sS', 'admin', ''),
(6, 'test123', '$2y$10$7UOL8fhxmMGT6Mzncq9zOOK5Vdf76C8SVzkMoweu7TVQaDxsZ8SWO', 'users', '6713f4586012f_843948.png'),
(7, 'admin123', '$2y$10$gjHUuapscLWmTcqH3PYF/OEO9oEkJUNhM/uJKRYxmYIBk7QQH/l6.', 'admin', ''),
(8, 'admin', '$2y$10$g0rz5ngshJDn.nHZGeU9DuNALmhELmPmhXHlG134eP1XRVTNhNb0W', 'admin', '6714822d727da_1309920.png'),
(9, '123', '$2y$10$PsPp0vOvWz9JC/zrw9OO.e04SraiWNKLxLF62wihThYxpezTPGnm.', 'admin', '6714f9bfce4ae_frieren-beyond-journeys-end-vol-1-9781974725762_hr.jpg'),
(10, 'kafka', '$2y$10$ZcR3w6OtyhrDIJYxkg3q1O4MUJi1L5G5suOBsODFZZJeAgHpwDXWC', 'admin', ''),
(11, 'abdul', '$2y$10$ySVEFZKcDuvggJW5qRptCuqX7SR2PAC1MJbOBhoEXzI5HkqGZfe26', 'users', NULL),
(12, 'laravel', '$2y$10$H.DjnJZfe93La/pZb1mSoOD1mWhUuBiJqnIKeHT1YQFfeUz64nzq2', 'users', NULL),
(13, 'risu', '$2y$10$Jr/62P6Jw1stlTYNFx/2aOa9dKnfXFbR76eOd56FgTc3wG8Y1i942', 'users', '671cf09a671b4_GPKOO5wbMAABsh7.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id_activity_logs`),
  ADD KEY `id_books` (`id_books`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `activity_reports`
--
ALTER TABLE `activity_reports`
  ADD PRIMARY KEY (`id_activity_reports`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_books` (`id_books`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id_books`);

--
-- Indexes for table `borrows`
--
ALTER TABLE `borrows`
  ADD PRIMARY KEY (`id_borrows`),
  ADD KEY `id_books` (`id_books`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_books` (`id_books`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id_activity_logs` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `activity_reports`
--
ALTER TABLE `activity_reports`
  MODIFY `id_activity_reports` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id_books` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `borrows`
--
ALTER TABLE `borrows`
  MODIFY `id_borrows` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`id_books`) REFERENCES `books` (`id_books`),
  ADD CONSTRAINT `activity_logs_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `activity_reports`
--
ALTER TABLE `activity_reports`
  ADD CONSTRAINT `activity_reports_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `activity_reports_ibfk_2` FOREIGN KEY (`id_books`) REFERENCES `books` (`id_books`);

--
-- Constraints for table `borrows`
--
ALTER TABLE `borrows`
  ADD CONSTRAINT `borrows_ibfk_1` FOREIGN KEY (`id_books`) REFERENCES `books` (`id_books`),
  ADD CONSTRAINT `borrows_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_books`) REFERENCES `books` (`id_books`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
