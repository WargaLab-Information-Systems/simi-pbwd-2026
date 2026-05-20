-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 20, 2026 at 11:35 AM
-- Server version: 9.6.0
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_simi`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int NOT NULL,
  `client_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('belum_tayang','aktif','selesai') DEFAULT 'belum_tayang',
  `price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `client_id`, `title`, `description`, `start_date`, `end_date`, `status`, `price`, `created_at`) VALUES
(1, 1, 'Promo Akhir Tahun PT Maju Bersama', 'Iklan billboard promo diskon akhir tahun', '2025-12-01', '2025-12-31', 'selesai', '5000000.00', '2026-05-20 11:33:29'),
(2, 2, 'Peluncuran Produk Cahaya Digital', 'Iklan spanduk peluncuran aplikasi baru', '2026-01-10', '2026-01-31', 'selesai', '3500000.00', '2026-05-20 11:33:29'),
(3, 3, 'Promo Lebaran Toko Pak Harto', 'Iklan radio dan banner promo lebaran', '2026-03-15', '2026-04-10', 'selesai', '2000000.00', '2026-05-20 11:33:29'),
(4, 4, 'Diskon Produk UD Karya Mandiri', 'Iklan media sosial diskon produk unggulan', '2026-02-01', '2026-02-28', 'selesai', '1800000.00', '2026-05-20 11:33:29'),
(5, 5, 'Grand Opening Nusa Kreatif', 'Iklan opening kantor cabang baru', '2026-01-05', '2026-01-20', 'selesai', '4200000.00', '2026-05-20 11:33:29'),
(6, 6, 'Promo Obat Generik Apotek Sehat', 'Iklan brosur dan spanduk promo obat', '2026-03-01', '2026-03-31', 'selesai', '1500000.00', '2026-05-20 11:33:29'),
(7, 7, 'Kampanye Brand CV Bintang Promosi', 'Iklan kampanye pengenalan brand baru', '2026-04-01', '2026-04-30', 'selesai', '6000000.00', '2026-05-20 11:33:29'),
(8, 1, 'Iklan Semester PT Maju Bersama', 'Iklan billboard semester pertama 2026', '2026-05-01', '2026-06-30', 'aktif', '7500000.00', '2026-05-20 11:33:29'),
(9, 2, 'Iklan Digital Cahaya Digital Q2', 'Iklan banner digital kuartal kedua', '2026-04-15', '2026-06-15', 'aktif', '4000000.00', '2026-05-20 11:33:29'),
(10, 8, 'Promo Reguler PT Sejahtera Abadi', 'Iklan rutin bulanan produk unggulan', '2026-05-10', '2026-06-10', 'aktif', '3000000.00', '2026-05-20 11:33:29'),
(11, 9, 'Promo Menu Baru Rumah Makan Padang', 'Iklan spanduk menu baru musim ini', '2026-05-05', '2026-05-25', 'aktif', '1200000.00', '2026-05-20 11:33:29'),
(12, 10, 'Sale Koleksi Terbaru Distro Urban', 'Iklan koleksi summer sale di media sosial', '2026-05-01', '2026-05-31', 'aktif', '2500000.00', '2026-05-20 11:33:29'),
(13, 3, 'Iklan Harian Toko Pak Harto', 'Iklan mingguan promosi produk sembako', '2026-05-12', '2026-05-26', 'aktif', '800000.00', '2026-05-20 11:33:29'),
(14, 5, 'Iklan Rekrutmen Nusa Kreatif', 'Iklan lowongan kerja media cetak dan digital', '2026-05-08', '2026-05-28', 'aktif', '1600000.00', '2026-05-20 11:33:29'),
(15, 4, 'Promo Stok Akhir UD Karya Mandiri', 'Iklan clearance stok akhir semester', '2026-04-20', '2026-05-20', 'aktif', '2200000.00', '2026-05-20 11:33:29'),
(16, 6, 'Kampanye Kesehatan Apotek Sehat', 'Iklan kampanye kesehatan nasional', '2026-06-01', '2026-06-30', 'belum_tayang', '3800000.00', '2026-05-20 11:33:29'),
(17, 7, 'Iklan Ulang Tahun CV Bintang Promosi', 'Iklan anniversary perusahaan ke-5', '2026-07-01', '2026-07-15', 'belum_tayang', '4500000.00', '2026-05-20 11:33:29'),
(18, 8, 'Peluncuran Produk Baru PT Sejahtera', 'Iklan peluncuran lini produk terbaru', '2026-06-15', '2026-07-15', 'belum_tayang', '5500000.00', '2026-05-20 11:33:29'),
(19, 10, 'Koleksi Back to School Distro Urban', 'Iklan koleksi back to school 2026', '2026-07-01', '2026-07-31', 'belum_tayang', '3200000.00', '2026-05-20 11:33:29'),
(20, 9, 'Promo HUT RI Rumah Makan Padang', 'Iklan spesial promo kemerdekaan RI', '2026-08-01', '2026-08-17', 'belum_tayang', '1800000.00', '2026-05-20 11:33:29');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone`, `address`, `created_at`) VALUES
(1, 'PT Maju Bersama Tbk', '081234567001', 'Jl. Sudirman No. 12, Jakarta Pusat', '2026-05-20 11:31:03'),
(2, 'CV Cahaya Digital', '081234567002', 'Jl. Gatot Subroto No. 45, Bandung', '2026-05-20 11:31:03'),
(3, 'Toko Sembako Pak Harto', '081234567003', 'Jl. Pahlawan No. 8, Surabaya', '2026-05-20 11:31:03'),
(4, 'UD Karya Mandiri', '081234567004', 'Jl. Diponegoro No. 33, Semarang', '2026-05-20 11:31:03'),
(5, 'PT Nusa Kreatif Indonesia', '081234567005', 'Jl. Imam Bonjol No. 21, Medan', '2026-05-20 11:31:03'),
(6, 'Apotek Sehat Selalu', '081234567006', 'Jl. Ahmad Yani No. 17, Yogyakarta', '2026-05-20 11:31:03'),
(7, 'CV Bintang Promosi', '081234567007', 'Jl. Veteran No. 5, Malang', '2026-05-20 11:31:03'),
(8, 'PT Sejahtera Abadi', '081234567008', 'Jl. MT Haryono No. 88, Makassar', '2026-05-20 11:31:03'),
(9, 'Rumah Makan Padang Jaya', '081234567009', 'Jl. Merdeka No. 3, Padang', '2026-05-20 11:31:03'),
(10, 'Distro Urban Style', '081234567010', 'Jl. Cihampelas No. 99, Bandung', '2026-05-20 11:31:03');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `advertisement_id` int NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_status` enum('belum_lunas','lunas') DEFAULT 'belum_lunas',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `advertisement_id`, `amount`, `payment_date`, `payment_status`, `notes`, `created_at`) VALUES
(1, 1, '5000000.00', '2025-11-28', 'lunas', 'Pembayaran lunas sebelum tayang', '2026-05-20 11:35:39'),
(2, 2, '3500000.00', '2026-01-08', 'lunas', 'Transfer BCA, lunas di muka', '2026-05-20 11:35:39'),
(3, 3, '1000000.00', '2026-03-14', 'lunas', 'DP pertama 50%', '2026-05-20 11:35:39'),
(4, 3, '1000000.00', '2026-03-28', 'lunas', 'Pelunasan 50% sisanya', '2026-05-20 11:35:39'),
(5, 4, '1800000.00', '2026-01-30', 'lunas', 'Pembayaran penuh via transfer', '2026-05-20 11:35:39'),
(6, 5, '2000000.00', '2026-01-04', 'lunas', 'DP awal 50%', '2026-05-20 11:35:39'),
(7, 5, '2200000.00', '2026-01-15', 'lunas', 'Pelunasan sisa tagihan', '2026-05-20 11:35:39'),
(8, 6, '1500000.00', '2026-02-28', 'lunas', 'Lunas sebelum periode tayang', '2026-05-20 11:35:39'),
(9, 7, '3000000.00', '2026-03-28', 'lunas', 'DP 50% kampanye brand', '2026-05-20 11:35:39'),
(10, 7, '3000000.00', '2026-04-20', 'lunas', 'Pelunasan kampanye brand', '2026-05-20 11:35:39'),
(11, 8, '7500000.00', '2026-04-28', 'lunas', 'Bayar penuh di muka sebelum tayang', '2026-05-20 11:35:39'),
(12, 9, '4000000.00', '2026-04-12', 'lunas', 'Transfer lunas sebelum periode mulai', '2026-05-20 11:35:39'),
(13, 10, '1500000.00', '2026-05-08', 'lunas', 'DP 50% iklan bulanan', '2026-05-20 11:35:39'),
(14, 10, '1500000.00', '2026-05-18', 'lunas', 'Pelunasan iklan bulanan', '2026-05-20 11:35:39'),
(15, 11, '600000.00', '2026-05-03', 'lunas', 'Bayar DP 50%', '2026-05-20 11:35:39'),
(16, 11, '600000.00', '2026-05-17', 'belum_lunas', 'Sisa pelunasan belum dibayar', '2026-05-20 11:35:39'),
(17, 12, '2500000.00', '2026-04-29', 'lunas', 'Pembayaran penuh di muka', '2026-05-20 11:35:39'),
(18, 13, '400000.00', '2026-05-10', 'lunas', 'DP awal iklan mingguan', '2026-05-20 11:35:39'),
(19, 13, '400000.00', '2026-05-20', 'belum_lunas', 'Pelunasan belum dikonfirmasi', '2026-05-20 11:35:39'),
(20, 14, '1600000.00', '2026-05-06', 'lunas', 'Lunas transfer BRI', '2026-05-20 11:35:39'),
(21, 15, '1100000.00', '2026-04-18', 'lunas', 'DP 50% clearance stok', '2026-05-20 11:35:39'),
(22, 15, '1100000.00', '2026-05-15', 'belum_lunas', 'Sisa tagihan menunggu pembayaran', '2026-05-20 11:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Admin SIMI', 'admin@simi.id', 'c93ccd78b2076528346216b3b2f701e6', '2026-05-20 11:29:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advertisement_id` (`advertisement_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD CONSTRAINT `advertisements_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`advertisement_id`) REFERENCES `advertisements` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
