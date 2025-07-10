-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2025 at 12:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siom_db`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `dashboard_stats`
-- (See below for the actual view)
--
CREATE TABLE `dashboard_stats` (
`total_mahasiswa` bigint(21)
,`mahasiswa_aktif` decimal(22,0)
,`mahasiswa_cuti` decimal(22,0)
,`mahasiswa_lulus` decimal(22,0)
,`rata_rata_ipk` decimal(7,6)
);

-- --------------------------------------------------------

--
-- Table structure for table `dosen`
--

CREATE TABLE `dosen` (
  `id` int(11) NOT NULL,
  `nama_dosen` varchar(100) NOT NULL,
  `nip` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `fakultas` varchar(100) NOT NULL,
  `program_studi` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dosen`
--

INSERT INTO `dosen` (`id`, `nama_dosen`, `nip`, `email`, `no_hp`, `fakultas`, `program_studi`, `jabatan`, `created_at`, `updated_at`) VALUES
(2, 'Zakial Viki', '2321312312', 'Zakial@gmail.com', '08231232', 'Fakultas Teknik', 'Teknik Mesin', 'yyy', '2025-07-07 17:26:55', '2025-07-08 03:26:55');

-- --------------------------------------------------------

--
-- Table structure for table `fakultas`
--

CREATE TABLE `fakultas` (
  `id` int(11) NOT NULL,
  `nama_fakultas` varchar(100) NOT NULL,
  `kode_fakultas` varchar(10) NOT NULL,
  `dekan` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fakultas`
--

INSERT INTO `fakultas` (`id`, `nama_fakultas`, `kode_fakultas`, `dekan`, `created_at`) VALUES
(1, 'Fakultas Teknik', 'FT', 'Dr. Ir. Budi Santoso, M.T.', '2025-06-30 12:37:35'),
(2, 'Fakultas Ekonomi', 'FE', 'Dr. Sarah Putri, S.E., M.M.', '2025-06-30 12:37:35'),
(3, 'Fakultas Hukum', 'FH', 'Dr. Ahmad Fadillah, S.H., M.H.', '2025-06-30 12:37:35'),
(4, 'Fakultas Kedokteran', 'FK', 'Dr. dr. Rizki Pratama, Sp.PD.', '2025-06-30 12:37:35'),
(5, 'Fakultas MIPA', 'FMIPA', 'Dr. Dewi Sartika, M.Si.', '2025-06-30 12:37:35'),
(6, 'Fakultas Sastra', 'FS', 'Dr. Siti Nurhaliza, M.Hum.', '2025-06-30 12:37:35'),
(8, 'FIOKSM', '23', 'SDS', '2025-07-07 16:47:51'),
(9, 'Eka', 'asasas', 'Zakial Viki', '2025-07-07 21:55:26'),
(10, 'adasdd', 'asdasd', 'Zakial Viki', '2025-07-09 21:45:33');

-- --------------------------------------------------------

--
-- Stand-in structure for view `fakultas_distribution`
-- (See below for the actual view)
--
CREATE TABLE `fakultas_distribution` (
`fakultas` varchar(50)
,`jumlah_mahasiswa` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `mata_kuliah_id` int(11) NOT NULL,
  `dosen_id` int(11) NOT NULL,
  `ruangan_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `tahun_akademik` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kuliah`
--

CREATE TABLE `jadwal_kuliah` (
  `id` int(11) NOT NULL,
  `mata_kuliah_id` int(11) NOT NULL,
  `hari` enum('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `ruangan` varchar(20) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `tahun_akademik` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_kuliah`
--

INSERT INTO `jadwal_kuliah` (`id`, `mata_kuliah_id`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`, `semester`, `tahun_akademik`, `created_at`) VALUES
(1, 1, 'Senin', '08:00:00', '10:30:00', 'Lab 1', 'Ganjil', '2024/2025', '2025-06-30 12:37:35'),
(2, 2, 'Senin', '10:30:00', '13:00:00', 'Ruang 201', 'Ganjil', '2024/2025', '2025-06-30 12:37:35'),
(3, 3, 'Senin', '13:00:00', '15:30:00', 'Ruang 301', 'Ganjil', '2024/2025', '2025-06-30 12:37:35');

-- --------------------------------------------------------

--
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `jenis_pembayaran` enum('SPP','Uang Lab','Uang Praktikum','Lainnya') NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `tahun_akademik` varchar(10) NOT NULL,
  `status` enum('belum_bayar','sudah_bayar','terlambat') DEFAULT 'belum_bayar',
  `tanggal_bayar` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `khs`
--

CREATE TABLE `khs` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `mata_kuliah_id` int(11) NOT NULL,
  `nilai` varchar(2) NOT NULL,
  `bobot` decimal(3,1) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `tahun_akademik` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khs`
--

INSERT INTO `khs` (`id`, `nim`, `mata_kuliah_id`, `nilai`, `bobot`, `semester`, `tahun_akademik`, `created_at`) VALUES
(1, '2024001', 1, 'A', 4.0, 'Ganjil', '2024/2025', '2025-07-07 23:28:45'),
(2, '2024001', 2, 'A', 4.0, 'Ganjil', '2024/2025', '2025-07-07 23:28:45'),
(3, '2024001', 3, 'A', 4.0, 'Ganjil', '2024/2025', '2025-07-07 23:28:45'),
(7, '121212', 1, 'A', 0.0, 'Ganjil', '2024/2025', '2025-07-09 14:33:06'),
(8, '1122', 3, 'B', 0.0, 'Ganjil', '2024', '2025-07-09 17:09:05'),
(9, '1122', 3, 'C+', 0.0, 'Ganjil', '2024', '2025-07-09 17:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `krs`
--

CREATE TABLE `krs` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `mata_kuliah_id` int(11) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `tahun_akademik` varchar(10) NOT NULL,
  `status` enum('terdaftar','selesai','batal') DEFAULT 'terdaftar',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `krs`
--

INSERT INTO `krs` (`id`, `nim`, `mata_kuliah_id`, `semester`, `tahun_akademik`, `status`, `created_at`) VALUES
(1, '234234', 1, 'Ganjil', '2024/2025', 'terdaftar', '2025-07-07 21:55:55'),
(2, '234234', 2, 'Ganjil', '2024/2025', 'terdaftar', '2025-07-07 21:56:25'),
(3, '234234', 1, 'Ganjil', '2024/2025', 'terdaftar', '2025-07-07 22:08:35'),
(4, '1111', 3, 'Ganjil', '2024/2025', 'terdaftar', '2025-07-07 22:09:01'),
(5, '234234', 2, 'Ganjil', '2024/2025', 'terdaftar', '2025-07-07 22:36:19'),
(6, '121212', 1, 'Ganjil', '2024/2025', 'terdaftar', '2025-07-09 14:28:17'),
(7, '121212', 2, 'Genap', '2024', 'terdaftar', '2025-07-09 14:29:11'),
(8, '121212', 3, 'Genap', '2024/2025', 'terdaftar', '2025-07-09 14:43:18'),
(10, '121212', 2, 'Ganjil', '2024/2025', 'terdaftar', '2025-07-09 16:24:04'),
(11, '121212', 1, 'Ganjil', '2025', 'terdaftar', '2025-07-09 16:24:13'),
(12, '1122', 1, 'Ganjil', '2024', 'terdaftar', '2025-07-09 16:44:24'),
(13, '1122', 2, 'Ganjil', '2024', 'terdaftar', '2025-07-09 16:45:09'),
(14, '1122', 3, 'Ganjil', '2023', 'terdaftar', '2025-07-09 16:45:15'),
(15, '1122', 1, 'Ganjil', '2024/2025', 'terdaftar', '2025-07-09 17:03:06'),
(16, '1122', 2, 'Ganjil', '2024', 'terdaftar', '2025-07-09 17:03:23'),
(17, '121212', 3, 'Ganjil', '2024/2025', 'terdaftar', '2025-07-09 21:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `agama` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fakultas` varchar(50) NOT NULL,
  `program_studi` varchar(50) NOT NULL,
  `angkatan` int(11) NOT NULL,
  `status` enum('aktif','cuti','lulus','nonaktif') DEFAULT 'aktif',
  `ipk` decimal(3,2) DEFAULT 0.00,
  `total_sks` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`id`, `nim`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `alamat`, `no_hp`, `email`, `fakultas`, `program_studi`, `angkatan`, `status`, `ipk`, `total_sks`, `created_at`, `updated_at`) VALUES
(9, '1111', 'Maya', 'matang mesjid', '2025-07-09', 'L', 'Islam', 'matang', '082311', 'maya@gmail.com', 'Fakultas Ekonomi', 'asdadad', 2024, 'aktif', 0.00, 0, '2025-07-07 22:04:43', '2025-07-08 08:04:43'),
(10, '2024001', 'Ahmad Fauzi', 'Jakarta', '2000-05-15', 'L', 'Islam', 'Jl. Sudirman No. 123, Jakarta Pusat', '081234567890', 'ahmad.fauzi@email.com', 'Teknik', 'Teknik Informatika', 2024, 'aktif', 3.75, 24, '2025-07-07 23:53:10', '2025-07-08 09:53:10'),
(11, '2024002', 'Siti Nurhaliza', 'Bandung', '2001-03-20', 'P', 'Islam', 'Jl. Asia Afrika No. 45, Bandung', '081234567891', 'siti.nurhaliza@email.com', 'Ekonomi', 'Manajemen', 2024, 'aktif', 3.80, 20, '2025-07-07 23:53:10', '2025-07-08 09:53:10'),
(12, '2024003', 'Budi Santoso', 'Surabaya', '2000-08-10', 'L', 'Islam', 'Jl. Pemuda No. 67, Surabaya', '081234567892', 'budi.santoso@email.com', 'Hukum', 'Ilmu Hukum', 2024, 'aktif', 3.65, 18, '2025-07-07 23:53:10', '2025-07-08 09:53:10'),
(13, '121212', 'riyan', 'matang mesjid', '2025-07-23', 'L', 'Islam', 'Matang mesjid', '0822', 'riyan@gmail.com', 'Fakultas Teknik', 'teknik industri', 2024, 'aktif', 0.00, 0, '2025-07-09 14:15:09', '2025-07-10 00:15:09'),
(14, '1122', 'beyza', 'matang', '2025-07-07', 'P', 'Islam', 'd', '0933', 'beyza@gmail.com', 'Fakultas Ekonomi', 'Ekonomi', 2023, 'aktif', 0.00, 0, '2025-07-09 16:43:30', '2025-07-10 02:43:30');

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id` int(11) NOT NULL,
  `kode_mk` varchar(20) NOT NULL,
  `nama_mk` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `prodi_id` int(11) NOT NULL,
  `dosen_pengampu` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id`, `kode_mk`, `nama_mk`, `sks`, `semester`, `prodi_id`, `dosen_pengampu`, `created_at`) VALUES
(1, 'TI101', 'Pemrograman Web', 3, 1, 1, 'Dr. Ir. John Doe, M.T.', '2025-06-30 12:37:35'),
(2, 'TI102', 'Basis Data', 3, 1, 1, 'Dr. Sarah Putri, S.E., M.M.', '2025-06-30 12:37:35'),
(3, 'TI103', 'Algoritma & Struktur Data', 3, 1, 1, 'Dr. Ahmad Fadillah, S.H., M.H.', '2025-06-30 12:37:35'),
(7, 'MTK', 'TTR', 1, 1, 4, 'Zakial Viki', '2025-07-07 17:43:36');

-- --------------------------------------------------------

--
-- Table structure for table `program_studi`
--

CREATE TABLE `program_studi` (
  `id` int(11) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL,
  `kode_prodi` varchar(10) NOT NULL,
  `fakultas_id` int(11) NOT NULL,
  `kaprodi` varchar(100) NOT NULL,
  `akreditasi` varchar(10) DEFAULT 'C',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_studi`
--

INSERT INTO `program_studi` (`id`, `nama_prodi`, `kode_prodi`, `fakultas_id`, `kaprodi`, `akreditasi`, `created_at`) VALUES
(1, 'Teknik Informatika', 'TI', 1, 'Dr. Ir. John Doe, M.T.', 'A', '2025-06-30 12:37:35'),
(3, 'Manajemen', 'MNJ', 2, 'Dr. Sarah Putri, S.E., M.M.', 'A', '2025-06-30 12:37:35'),
(4, 'Akuntansi', 'AKT', 2, 'Dr. Ahmad Fadillah, S.E., M.Ak.', 'A', '2025-06-30 12:37:35'),
(5, 'Ilmu Hukum', 'HKM', 3, 'Dr. Budi Santoso, S.H., M.H.', 'A', '2025-06-30 12:37:35'),
(6, 'Pendidikan Dokter', 'PD', 4, 'Dr. dr. Rizki Pratama, Sp.PD.', 'A', '2025-06-30 12:37:35'),
(7, 'Matematika', 'MTK', 5, 'Dr. Dewi Sartika, M.Si.', 'A', '2025-06-30 12:37:35'),
(8, 'Sastra Indonesia', 'SIN', 6, 'Dr. Siti Nurhaliza, M.Hum.', 'A', '2025-06-30 12:37:35');

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id` int(11) NOT NULL,
  `nama_ruangan` varchar(50) NOT NULL,
  `kapasitas` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dosen','mahasiswa') NOT NULL DEFAULT 'mahasiswa',
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `full_name`, `email`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administrator', 'admin@siom.com', '2025-07-07 20:27:03', '2025-07-07 20:27:03'),
(2, 'dosen001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dosen', 'Dr. John Doe', 'dosen001@siom.com', '2025-07-07 20:27:03', '2025-07-07 20:27:03'),
(4, 'dosen002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dosen', 'Dr. Sarah Johnson', 'dosen002@siom.com', '2025-07-07 20:27:03', '2025-07-07 20:27:03'),
(5, 'mahasiswa002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mahasiswa', 'Mike Wilson', 'mahasiswa002@siom.com', '2025-07-07 20:27:03', '2025-07-07 20:27:03'),
(8, '2024003', '$2y$10$1i4ML/VxocrO8LmswVoqZ./.Vg30NpY4Bx06jxihJ6.23A6syXAjW', 'mahasiswa', 'Budi Santoso', 'budi.santoso@email.com', '2025-07-07 23:58:08', '2025-07-07 23:58:08'),
(9, '121212', '$2y$10$eh6LDLNeh.F//5yRqJlO9O3mTckQIpB8O1JxCBEsZ9DhgEPPUDki.', 'mahasiswa', 'riyan', 'riyan@gmail.com', '2025-07-09 14:26:01', '2025-07-09 14:26:01'),
(10, '1122', '$2y$10$2fAQ83omRJBIi5vp3vcC8.c74TZusSA610JRzuU5IAE3xuKx8K/lC', 'mahasiswa', 'beyza', 'beyza@gmail.com', '2025-07-09 16:43:50', '2025-07-09 16:43:50');

-- --------------------------------------------------------

--
-- Structure for view `dashboard_stats`
--
DROP TABLE IF EXISTS `dashboard_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `dashboard_stats`  AS SELECT count(0) AS `total_mahasiswa`, sum(case when `mahasiswa`.`status` = 'aktif' then 1 else 0 end) AS `mahasiswa_aktif`, sum(case when `mahasiswa`.`status` = 'cuti' then 1 else 0 end) AS `mahasiswa_cuti`, sum(case when `mahasiswa`.`status` = 'lulus' then 1 else 0 end) AS `mahasiswa_lulus`, avg(`mahasiswa`.`ipk`) AS `rata_rata_ipk` FROM `mahasiswa` ;

-- --------------------------------------------------------

--
-- Structure for view `fakultas_distribution`
--
DROP TABLE IF EXISTS `fakultas_distribution`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `fakultas_distribution`  AS SELECT `mahasiswa`.`fakultas` AS `fakultas`, count(0) AS `jumlah_mahasiswa` FROM `mahasiswa` WHERE `mahasiswa`.`status` = 'aktif' GROUP BY `mahasiswa`.`fakultas` ORDER BY count(0) DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dosen`
--
ALTER TABLE `dosen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `fakultas`
--
ALTER TABLE `fakultas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_fakultas` (`kode_fakultas`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mata_kuliah_id` (`mata_kuliah_id`),
  ADD KEY `dosen_id` (`dosen_id`),
  ADD KEY `ruangan_id` (`ruangan_id`);

--
-- Indexes for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mata_kuliah_id` (`mata_kuliah_id`);

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_keuangan_mahasiswa` (`mahasiswa_id`),
  ADD KEY `idx_keuangan_status` (`status`);

--
-- Indexes for table `khs`
--
ALTER TABLE `khs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mata_kuliah_id` (`mata_kuliah_id`);

--
-- Indexes for table `krs`
--
ALTER TABLE `krs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mata_kuliah_id` (`mata_kuliah_id`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_mahasiswa_nim` (`nim`),
  ADD KEY `idx_mahasiswa_fakultas` (`fakultas`),
  ADD KEY `idx_mahasiswa_status` (`status`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_mk` (`kode_mk`),
  ADD KEY `prodi_id` (`prodi_id`);

--
-- Indexes for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_prodi` (`kode_prodi`),
  ADD KEY `fakultas_id` (`fakultas_id`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dosen`
--
ALTER TABLE `dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fakultas`
--
ALTER TABLE `fakultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `khs`
--
ALTER TABLE `khs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `krs`
--
ALTER TABLE `krs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `program_studi`
--
ALTER TABLE `program_studi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_2` FOREIGN KEY (`dosen_id`) REFERENCES `dosen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_ibfk_3` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_kuliah`
--
ALTER TABLE `jadwal_kuliah`
  ADD CONSTRAINT `jadwal_kuliah_ibfk_1` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD CONSTRAINT `keuangan_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `khs`
--
ALTER TABLE `khs`
  ADD CONSTRAINT `khs_ibfk_1` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `krs`
--
ALTER TABLE `krs`
  ADD CONSTRAINT `krs_ibfk_1` FOREIGN KEY (`mata_kuliah_id`) REFERENCES `mata_kuliah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`prodi_id`) REFERENCES `program_studi` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `program_studi`
--
ALTER TABLE `program_studi`
  ADD CONSTRAINT `program_studi_ibfk_1` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
