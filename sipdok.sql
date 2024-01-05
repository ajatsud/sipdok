-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2024 at 01:16 AM
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
-- Database: `sipdok`
--

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id` char(12) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenkel` char(1) NOT NULL,
  `lahir` date NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `ins_id` varchar(50) NOT NULL,
  `ins_dtm` datetime NOT NULL,
  `upd_id` varchar(50) NOT NULL,
  `upd_dtm` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id`, `nama`, `jenkel`, `lahir`, `alamat`, `ins_id`, `ins_dtm`, `upd_id`, `upd_dtm`) VALUES
('PS2023120001', 'Ajat Sudrajat', 'l', '1986-03-15', 'Bandung barat', 'admin', '2023-12-25 14:59:27', 'admin', '2024-01-05 07:00:47'),
('PS2023120002', 'Budi Sudarsono', 'l', '2023-12-25', 'Jakarta', 'admin', '2023-12-25 14:59:48', 'admin', '2023-12-30 06:22:47'),
('PS2023120003', 'Candra Mukti', 'l', '2023-12-25', 'Medan', 'admin', '2023-12-25 15:00:40', 'admin', '2023-12-30 06:22:53'),
('PS2023120004', 'Deni Candra', 'l', '2023-12-25', 'Cimahi', 'admin', '2023-12-25 15:09:15', 'admin', '2023-12-30 06:23:00'),
('PS2023120005', 'Entis Sutisna', 'l', '2023-12-25', 'Cimahi', 'admin', '2023-12-25 15:09:35', 'admin', '2023-12-30 08:44:50'),
('PS2023120006', 'Farhan Abbas', 'l', '2023-12-25', 'Jakarta', 'admin', '2023-12-25 15:10:09', 'admin', '2023-12-30 06:23:06'),
('PS2023120007', 'Gugun Gunawan', 'l', '2023-12-25', 'Jakarta', 'admin', '2023-12-25 15:10:23', 'admin', '2023-12-30 06:23:12'),
('PS2023120008', 'Hermawan Dwiyulianto', 'p', '2023-12-25', 'Cibitung', 'admin', '2023-12-25 15:10:41', 'admin', '2023-12-30 06:23:21'),
('PS2023120009', 'Inara Usro', 'p', '2023-12-25', 'Jambi', 'admin', '2023-12-25 15:10:59', 'admin', '2023-12-30 06:23:28'),
('PS2023120010', 'Jajang Herman', 'p', '2023-12-01', 'Cicangkang hilir', 'admin', '2023-12-25 15:11:14', 'admin', '2024-01-05 07:01:41'),
('PS2023120011', 'Kurnia Megi', 'l', '2023-12-25', 'Jakarta', 'admin', '2023-12-25 15:11:32', 'admin', '2023-12-30 06:23:40'),
('PS2023120012', 'Lala Mutila', 'p', '2023-12-25', 'Jakarta Selatan', 'admin', '2023-12-25 16:01:56', 'admin', '2023-12-25 16:01:56'),
('PS2023120013', 'Danang Prayoga', 'l', '1989-12-26', 'Sukabumi', 'admin', '2023-12-26 02:13:31', 'admin', '2024-01-04 14:44:32'),
('PS2023120014', 'test update lagi', 'p', '2023-01-02', 'alamat test', 'admin', '2023-12-26 02:33:37', 'admin', '2024-01-01 08:02:05'),
('PS2023120015', 'Najwa Sihab', 'p', '2000-01-01', 'Jakarta', 'admin', '2023-12-29 14:18:04', 'admin', '2024-01-05 06:57:44'),
('PS2023120016', 'M Tejan Haedari', 'l', '2000-01-01', 'Pamoyanan', 'admin', '2023-12-29 14:18:24', 'admin', '2024-01-01 08:02:38'),
('PS2023120017', 'Desti Rani Sucia', 'p', '1998-01-01', 'Bojong Ranca', 'admin', '2023-12-29 14:18:48', 'admin', '2024-01-01 08:01:37'),
('PS2023120018', 'melki gunawan', 'p', '2023-12-30', 'Bandung Barat', 'admin', '2023-12-30 08:29:58', 'admin', '2023-12-31 00:07:46'),
('PS2023120019', 'Kurniasih', 'p', '2023-12-31', 'Pasar jumat', 'admin', '2023-12-31 00:08:20', 'admin', '2023-12-31 00:08:20'),
('PS2023120020', 'Agus Miftah', 'p', '2023-12-31', 'Jawa Timur', 'admin', '2023-12-31 09:25:32', 'admin', '2024-01-05 06:53:27'),
('PS2023120021', 'Agus Candra', 'l', '2023-12-31', 'Jakarta Selatan', 'admin', '2023-12-31 09:26:02', 'admin', '2024-01-05 07:13:20'),
('PS2023120022', 'Agus Mulyadi', 'l', '2023-12-31', 'Jawa Tengah', 'admin', '2023-12-31 09:26:31', 'admin', '2024-01-05 06:25:24'),
('PS2023120023', 'Jajang Miharja', 'l', '2023-12-31', 'Bekasi Utara', 'admin', '2023-12-31 09:52:42', 'admin', '2023-12-31 09:52:52'),
('PS2023120024', 'Rifat Sauki', 'l', '2024-01-03', 'Batujajar', 'admin', '2023-12-31 10:07:57', 'admin', '2024-01-05 07:13:31'),
('PS2024010001', 'test', 'l', '2024-01-01', 'test', 'admin', '2024-01-01 06:46:18', 'admin', '2024-01-03 08:15:28'),
('PS2024010002', 'Dudung', 'l', '2024-01-05', 'Ciamisx', 'admin', '2024-01-05 05:57:38', 'admin', '2024-01-05 05:58:41');

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id` char(12) NOT NULL,
  `pasien_id` char(12) NOT NULL,
  `keluhan` varchar(200) NOT NULL,
  `ins_id` varchar(50) NOT NULL,
  `ins_dtm` datetime NOT NULL,
  `upd_id` varchar(50) NOT NULL,
  `upd_dtm` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftaran`
--

INSERT INTO `pendaftaran` (`id`, `pasien_id`, `keluhan`, `ins_id`, `ins_dtm`, `upd_id`, `upd_dtm`) VALUES
('RG2023120001', 'PS2023120001', 'Badan lemes', 'admin', '2023-12-30 08:03:04', 'admin', '2023-12-30 08:03:04'),
('RG2023120002', 'PS2023120010', 'Kelapa senat senut', 'admin', '2023-12-30 08:03:45', 'admin', '2024-01-01 08:03:47'),
('RG2023120003', 'PS2023120017', 'Cek kandungan aja', 'admin', '2023-12-30 08:04:35', 'admin', '2023-12-30 08:35:25'),
('RG2023120004', 'PS2023120018', 'Sakit perut', 'admin', '2023-12-30 08:29:58', 'admin', '2023-12-30 09:03:48'),
('RG2023120005', 'PS2023120001', 'Batuk pilek', 'admin', '2023-12-30 08:34:39', 'admin', '2023-12-30 08:43:00'),
('RG2023120006', 'PS2023120001', 'kantong kosong', 'admin', '2023-12-30 08:42:00', 'admin', '2024-01-01 08:03:31'),
('RG2023120007', 'PS2023120001', 'rada ngilu', 'admin', '2023-12-30 12:12:43', 'admin', '2024-01-05 07:00:47'),
('RG2023120008', 'PS2023120010', 'sakit mastaka', 'admin', '2023-12-30 12:58:48', 'admin', '2023-12-30 22:22:28'),
('RG2023120009', 'PS2023120010', 'panas dalam', 'admin', '2023-12-30 22:39:40', 'admin', '2024-01-05 07:01:41'),
('RG2023120010', 'PS2023120015', 'panas dalam', 'admin', '2023-12-30 22:40:01', 'admin', '2024-01-05 06:57:44'),
('RG2023120011', 'PS2023120022', 'Radang tenggorokan', 'admin', '2023-12-31 09:26:58', 'admin', '2024-01-05 06:25:24'),
('RG2023120012', 'PS2023120020', 'Hilang akal karena wowo', 'admin', '2023-12-31 10:07:07', 'admin', '2024-01-05 06:53:27'),
('RG2023120013', 'PS2023120024', 'Perut kram', 'admin', '2023-12-31 10:07:57', 'admin', '2024-01-05 07:13:31');

-- --------------------------------------------------------

--
-- Table structure for table `rekmed`
--

CREATE TABLE `rekmed` (
  `id` char(12) NOT NULL,
  `pendaftaran_id` char(12) NOT NULL,
  `pasien_id` char(12) NOT NULL,
  `anamnesa` varchar(200) NOT NULL,
  `pemeriksaan` varchar(200) NOT NULL,
  `diagnosa` varchar(200) NOT NULL,
  `terapi` varchar(200) NOT NULL,
  `biaya` float NOT NULL,
  `ins_id` varchar(50) NOT NULL,
  `ins_dtm` datetime NOT NULL,
  `upd_id` varchar(50) NOT NULL,
  `upd_dtm` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekmed`
--

INSERT INTO `rekmed` (`id`, `pendaftaran_id`, `pasien_id`, `anamnesa`, `pemeriksaan`, `diagnosa`, `terapi`, `biaya`, `ins_id`, `ins_dtm`, `upd_id`, `upd_dtm`) VALUES
('RM2024010001', 'RG2023120013', 'PS2023120024', 'susah tidur', 'normal', 'halusinasi', 'obat tidur', 100000, 'admin', '2024-01-05 06:19:33', 'admin', '2024-01-05 07:13:31'),
('RM2024010002', 'RG2023120012', 'PS2023120020', 'pegel', 'bagi-bagi duit', 'wowonista', 'panadol x', 200000, 'admin', '2024-01-05 06:22:27', 'admin', '2024-01-05 06:53:27'),
('RM2024010003', 'RG2023120011', 'PS2023120022', 'habis begadang', 'radang tenggorokan', 'radteng', 'larutan', 30000, 'admin', '2024-01-05 06:25:19', 'admin', '2024-01-05 06:25:24'),
('RM2024010004', 'RG2023120010', 'PS2023120015', 'kurang tidur dah 3 hari', 'radang tenggorokan', 'radteng', 'vitamin', 150000, 'admin', '2024-01-05 06:56:58', 'admin', '2024-01-05 06:57:44'),
('RM2024010005', 'RG2023120007', 'PS2023120001', 'lagi sedih', 'galau', 'stress', 'obat penenang', 20000, 'admin', '2024-01-05 07:00:47', 'admin', '2024-01-05 07:00:47'),
('RM2024010006', 'RG2023120009', 'PS2023120010', 'sakit badan', 'otot kram', 'overcully', 'obat pereda dan vitamin', 20000, 'admin', '2024-01-05 07:01:41', 'admin', '2024-01-05 07:01:41');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`) VALUES
('admin', '$2y$10$xAeaao09DYLxygrFUKnl/uwP607SEoGh6bl2/koXFuTleQzwSR8HO');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id`,`pasien_id`);

--
-- Indexes for table `rekmed`
--
ALTER TABLE `rekmed`
  ADD PRIMARY KEY (`id`,`pendaftaran_id`,`pasien_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
