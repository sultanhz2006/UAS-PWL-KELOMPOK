-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2026 at 11:35 AM
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
-- Database: `vyantravel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_booking` varchar(20) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `paket_id` int(10) UNSIGNED NOT NULL,
  `tanggal_pesan` date NOT NULL,
  `tanggal_berangkat` date NOT NULL,
  `jumlah_peserta` tinyint(3) UNSIGNED DEFAULT 1,
  `total_harga` decimal(14,2) NOT NULL,
  `status` enum('pending','dikonfirmasi','dibatalkan') DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `kode_booking`, `user_id`, `paket_id`, `tanggal_pesan`, `tanggal_berangkat`, `jumlah_peserta`, `total_harga`, `status`, `catatan`, `pdf_path`, `created_at`) VALUES
(1, 'VT-20260701-B101C', 4, 1, '2026-07-01', '2026-07-22', 1, 2500000.00, 'dibatalkan', '', 'tiket-VT-20260701-B101C-1782890378.pdf', '2026-07-01 05:03:05'),
(2, 'VT-20260701-EF2D6', 4, 7, '2026-07-01', '2026-07-29', 1, 1350000.00, 'pending', '', 'tiket-VT-20260701-EF2D6-1782889554.pdf', '2026-07-01 07:05:54'),
(3, 'VT-20260701-D14EF', 4, 5, '2026-07-01', '2026-07-31', 1, 2300000.00, 'pending', '', 'tiket-VT-20260701-D14EF-1782890376.pdf', '2026-07-01 07:06:10'),
(4, 'VT-20260701-1B548', 4, 2, '2026-07-01', '2026-07-22', 1, 6800000.00, 'pending', '', 'tiket-VT-20260701-1B548-1782891425.pdf', '2026-07-01 07:06:33'),
(5, 'VT-20260701-3B8FB', 4, 5, '2026-07-01', '2026-07-23', 1, 2300000.00, 'pending', '', 'tiket-VT-20260701-3B8FB-1782891427.pdf', '2026-07-01 07:21:25'),
(6, 'VT-20260701-A757A', 4, 7, '2026-07-01', '2026-07-22', 1, 1350000.00, 'pending', '', 'tiket-VT-20260701-A757A-1782891438.pdf', '2026-07-01 07:37:18'),
(7, 'VT-20260701-1D963', 4, 5, '2026-07-01', '2026-07-16', 1, 2300000.00, 'pending', '', 'tiket-VT-20260701-1D963-1782894127.pdf', '2026-07-01 08:22:07'),
(8, 'VT-20260701-0D1BD', 4, 5, '2026-07-01', '2026-07-30', 1, 2300000.00, 'pending', '', 'tiket-VT-20260701-0D1BD-1782894233.pdf', '2026-07-01 08:23:53'),
(9, 'VT-20260701-57C5C', 4, 3, '2026-07-01', '2026-07-15', 3, 3600000.00, 'pending', '', 'tiket-VT-20260701-57C5C-1782894260.pdf', '2026-07-01 08:24:20');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_hotel` varchar(150) NOT NULL,
  `destinasi` varchar(150) NOT NULL,
  `harga_per_malam` decimal(12,2) NOT NULL,
  `bintang` tinyint(3) UNSIGNED DEFAULT 3,
  `alamat` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `nama_hotel`, `destinasi`, `harga_per_malam`, `bintang`, `alamat`, `deskripsi`, `foto`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bali Garden Resort', 'Bali, Indonesia', 850000.00, 4, 'Kuta, Bali', 'Resort keluarga dekat pantai dan pusat kuliner Kuta.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48'),
(2, 'Raja Ampat Dive Lodge', 'Raja Ampat, Papua', 1400000.00, 4, 'Waisai, Raja Ampat', 'Penginapan tepi laut untuk wisata snorkeling dan diving.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48'),
(3, 'Jogja Heritage Inn', 'Yogyakarta, Indonesia', 520000.00, 3, 'Malioboro, Yogyakarta', 'Hotel nyaman dekat Malioboro, Keraton, dan stasiun.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48'),
(4, 'Komodo Harbor Hotel', 'Labuan Bajo, NTT', 950000.00, 4, 'Labuan Bajo, NTT', 'Hotel strategis dekat pelabuhan dan titik keberangkatan island hopping.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48'),
(5, 'Mandalika Bay Hotel', 'Lombok, Indonesia', 780000.00, 4, 'Mandalika, Lombok', 'Hotel modern dekat Pantai Kuta Mandalika dan Bukit Merese.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48'),
(6, 'Bromo View Guesthouse', 'Malang, Jawa Timur', 430000.00, 3, 'Tumpang, Malang', 'Guesthouse sederhana untuk persiapan trip sunrise Bromo.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48'),
(7, 'Lembang Family Stay', 'Bandung, Jawa Barat', 620000.00, 3, 'Lembang, Bandung', 'Penginapan keluarga dengan akses mudah ke wisata Lembang.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48'),
(8, 'Toba Lake Hotel', 'Medan, Sumatera Utara', 720000.00, 4, 'Parapat, Sumatera Utara', 'Hotel dengan akses perjalanan menuju Danau Toba dan Samosir.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48'),
(9, 'Losari Sea View Hotel', 'Makassar, Sulawesi Selatan', 690000.00, 4, 'Pantai Losari, Makassar', 'Hotel kota dekat Pantai Losari dan pusat kuliner Makassar.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48'),
(10, 'Belitung Island Hotel', 'Belitung, Indonesia', 650000.00, 3, 'Tanjung Pandan, Belitung', 'Hotel nyaman untuk island hopping dan wisata pantai granit.', NULL, 'aktif', '2026-07-01 06:47:48', '2026-07-01 06:47:48');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_bookings`
--

CREATE TABLE `hotel_bookings` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_hotel_booking` varchar(24) NOT NULL,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `hotel_id` int(10) UNSIGNED NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `jumlah_kamar` tinyint(3) UNSIGNED DEFAULT 1,
  `jumlah_tamu` tinyint(3) UNSIGNED DEFAULT 1,
  `total_harga` decimal(14,2) NOT NULL,
  `status` enum('pending','dikonfirmasi','dibatalkan') DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel_bookings`
--

INSERT INTO `hotel_bookings` (`id`, `kode_hotel_booking`, `booking_id`, `user_id`, `hotel_id`, `check_in`, `check_out`, `jumlah_kamar`, `jumlah_tamu`, `total_harga`, `status`, `catatan`, `created_at`) VALUES
(1, 'HTL-20260701-42F21', 2, 4, 7, '2026-07-29', '2026-08-01', 1, 1, 1860000.00, 'pending', '', '2026-07-01 07:05:57');

-- --------------------------------------------------------

--
-- Table structure for table `paket_wisata`
--

CREATE TABLE `paket_wisata` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_paket` varchar(150) NOT NULL,
  `destinasi` varchar(150) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `durasi_hari` tinyint(3) UNSIGNED DEFAULT 1,
  `kuota` smallint(5) UNSIGNED DEFAULT 10,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket_wisata`
--

INSERT INTO `paket_wisata` (`id`, `nama_paket`, `destinasi`, `harga`, `deskripsi`, `foto`, `durasi_hari`, `kuota`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Bali Surga Tropis', 'Bali, Indonesia', 2500000.00, 'Nikmati keindahan Bali bersama keluarga: Ubud, Kuta, Tanah Lot.', NULL, 4, 20, 'aktif', '2026-06-28 05:29:33', '2026-06-28 05:29:33'),
(2, 'Raja Ampat Diving', 'Raja Ampat, Papua', 6800000.00, 'Paket menyelam eksklusif di surga bawah laut Raja Ampat.', NULL, 5, 10, 'aktif', '2026-06-28 05:29:33', '2026-06-28 05:29:33'),
(3, 'Yogyakarta Heritage', 'Yogyakarta, Indonesia', 1200000.00, 'Jelajahi Keraton, Borobudur, dan Prambanan dalam satu paket hemat.', NULL, 3, 30, 'aktif', '2026-06-28 05:29:33', '2026-06-28 05:29:33'),
(4, 'Labuan Bajo Adventure', 'Labuan Bajo, NTT', 4500000.00, 'Komodo, Pink Beach, dan snorkeling di taman nasional Labuan Bajo.', NULL, 4, 15, 'aktif', '2026-06-28 05:29:33', '2026-06-28 05:29:33'),
(5, 'Lombok Mandalika Escape', 'Lombok, Indonesia', 2300000.00, 'Liburan santai ke Pantai Kuta Mandalika, Bukit Merese, dan desa adat Sade.', NULL, 3, 25, 'aktif', '2026-07-01 05:53:02', '2026-07-01 05:53:02'),
(6, 'Bromo Sunrise Trip', 'Malang, Jawa Timur', 1500000.00, 'Berburu matahari terbit di Gunung Bromo, pasir berbisik, dan savana Teletubbies.', NULL, 2, 18, 'aktif', '2026-07-01 05:53:02', '2026-07-01 05:53:02'),
(7, 'Bandung Family Tour', 'Bandung, Jawa Barat', 1350000.00, 'Nikmati wisata keluarga ke Lembang, Farmhouse, dan kuliner khas Bandung.', NULL, 3, 28, 'aktif', '2026-07-01 05:53:02', '2026-07-01 05:53:02'),
(8, 'Medan Lake Toba Journey', 'Medan, Sumatera Utara', 3200000.00, 'Perjalanan ke Danau Toba, Pulau Samosir, dan wisata budaya Batak.', NULL, 4, 20, 'aktif', '2026-07-01 05:53:02', '2026-07-01 05:53:02'),
(9, 'Makassar Marine Holiday', 'Makassar, Sulawesi Selatan', 2800000.00, 'Jelajahi Pantai Losari, Pulau Samalona, dan kuliner laut khas Makassar.', NULL, 3, 22, 'aktif', '2026-07-01 05:53:02', '2026-07-01 05:53:02'),
(10, 'Belitung Island Hopping', 'Belitung, Indonesia', 2600000.00, 'Island hopping ke Pulau Lengkuas, Tanjung Tinggi, dan pantai granit Belitung.', NULL, 3, 24, 'aktif', '2026-07-01 05:53:02', '2026-07-01 05:53:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `role` enum('admin','pelanggan') NOT NULL DEFAULT 'pelanggan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `email`, `password`, `no_telp`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@vyantravel.com', '$2y$10$ce/ZuKK6I7pTMMTpvEOBn.hvRaXjjOZVRYn4ly.EbmNsDPyjmhX5q', NULL, 'admin', '2026-06-28 05:29:33', '2026-06-29 04:32:46'),
(4, 'user1', 'user1@gmail.com', '$2y$12$5Hr50055J7lDDZ4LIzC/uuk106MLFZLXqf8ykZkVSP.Fn0GinOBcC', '087765456876', 'pelanggan', '2026-07-01 04:58:01', '2026-07-01 04:58:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `paket_id` (`paket_id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_bookings`
--
ALTER TABLE `hotel_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_hotel_booking` (`kode_hotel_booking`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `paket_wisata`
--
ALTER TABLE `paket_wisata`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hotel_bookings`
--
ALTER TABLE `hotel_bookings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `paket_wisata`
--
ALTER TABLE `paket_wisata`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`paket_id`) REFERENCES `paket_wisata` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_bookings`
--
ALTER TABLE `hotel_bookings`
  ADD CONSTRAINT `hotel_bookings_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hotel_bookings_ibfk_3` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
