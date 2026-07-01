-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2026 at 04:35 PM
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
(1, 'Bali Surga Tropis', 'Bali, Indonesia', 2500000.00, 'Nikmati keindahan Bali bersama keluarga: Ubud, Kuta, Tanah Lot.', NULL, 4, 20, 'aktif', '2026-06-19 06:18:31', '2026-06-19 06:18:31'),
(2, 'Raja Ampat Diving', 'Raja Ampat, Papua', 6800000.00, 'Paket menyelam eksklusif di surga bawah laut Raja Ampat.', NULL, 5, 10, 'aktif', '2026-06-19 06:18:31', '2026-06-19 06:18:31'),
(3, 'Yogyakarta Heritage', 'Yogyakarta, Indonesia', 1200000.00, 'Jelajahi Keraton, Borobudur, dan Prambanan dalam satu paket hemat.', NULL, 3, 30, 'aktif', '2026-06-19 06:18:31', '2026-06-19 06:18:31'),
(4, 'Labuan Bajo Adventure', 'Labuan Bajo, NTT', 4500000.00, 'Komodo, Pink Beach, dan snorkeling di taman nasional Labuan Bajo.', NULL, 4, 15, 'aktif', '2026-06-19 06:18:31', '2026-06-19 06:18:31');

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
(1, 'Super Admin', 'admin@vyantravel.com', '$2y$10$eiHjISuqOEnnudnn/gtHKeLabrUO2Xmx2BbQGOCcqd8JGZ/Mz5vbm', NULL, 'admin', '2026-06-19 06:18:31', '2026-06-20 07:07:58'),
(4, 'budiman', 'budiman@gmail.com', '$2y$12$xAQknzRBwnKHkcOPTh.saezFHZ65Ka3.EjBYjVjOF.sQe/H/kIk0G', '08129382733', 'pelanggan', '2026-06-20 07:34:22', '2026-06-20 07:34:22'),
(5, 'User Satu', 'user1@gmail.com', '$2y$12$6ov7.OJOBx8.7wfRPBoD2OO7GF4qnLue3dteTBsFWr6HHxHfnxskO', '08123456789', 'pelanggan', '2026-07-01 10:00:00', '2026-07-01 10:00:00');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paket_wisata`
--
ALTER TABLE `paket_wisata`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
