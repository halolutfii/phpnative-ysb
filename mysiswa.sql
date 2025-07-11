-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jul 11, 2025 at 03:30 PM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mysiswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id_matpel` int(11) NOT NULL,
  `nama_matpel` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id_matpel`, `nama_matpel`, `created_at`, `updated_at`) VALUES
(8, 'IPA', '2025-07-11 13:18:53', '2025-07-11 14:16:33'),
(9, 'MATEMATIKA', '2025-07-11 15:15:17', '2025-07-11 15:15:17'),
(10, 'JARINGAN KOMPUTER', '2025-07-11 15:15:29', '2025-07-11 15:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_matpel` int(11) NOT NULL,
  `nilai` varchar(3) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id_nilai`, `id_siswa`, `id_matpel`, `nilai`, `created_at`, `updated_at`) VALUES
(8, 3, 8, '100', '2025-07-11 15:04:41', '2025-07-11 15:04:41'),
(9, 3, 9, '100', '2025-07-11 15:15:39', '2025-07-11 15:15:39'),
(10, 4, 10, '70', '2025-07-11 15:20:11', '2025-07-11 15:20:11'),
(11, 3, 10, '99', '2025-07-11 15:20:32', '2025-07-11 15:20:32'),
(12, 4, 8, '78', '2025-07-11 15:20:48', '2025-07-11 15:20:48'),
(13, 5, 9, '80', '2025-07-11 15:21:05', '2025-07-11 15:21:05');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama`, `kelas`, `tanggal_lahir`, `created_at`, `updated_at`) VALUES
(3, 'LUTFI CAHYA NUGRAHA', 'XI - IPA', '2025-07-11', '2025-07-11 13:18:41', '2025-07-11 13:19:09'),
(4, 'NUGRAHA', 'X - IPS', '2001-09-11', '2025-07-11 15:17:55', '2025-07-11 15:17:55'),
(5, 'CAHYA', 'X - IPA', '2015-07-11', '2025-07-11 15:18:14', '2025-07-11 15:18:14');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@mail.com', '$2y$10$qWgDwcs27DzJfSpUFT6izuQ/Mh1ipZts3W.EJe6ZYqUdguHjMSZqS', '2025-07-10 03:47:22', '2025-07-10 03:47:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id_matpel`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_matpel` (`id_matpel`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id_matpel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_ibfk_2` FOREIGN KEY (`id_matpel`) REFERENCES `mata_pelajaran` (`id_matpel`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
