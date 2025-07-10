-- Database SIOM (Sistem Informasi Optimalisasi Pengelolaan Data Mahasiswa)
-- Created by: SIOM Development Team
-- Simplified version for easy import

-- Create database
CREATE DATABASE IF NOT EXISTS siom_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use database
USE siom_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'mahasiswa', 'dosen') NOT NULL DEFAULT 'mahasiswa',
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    nim VARCHAR(20) NULL,
    fakultas VARCHAR(50) NULL,
    program_studi VARCHAR(50) NULL,
    angkatan INT NULL,
    status ENUM('aktif', 'cuti', 'lulus', 'nonaktif') DEFAULT 'aktif',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login DATETIME NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- Create mahasiswa table
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) UNIQUE NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    agama VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    no_hp VARCHAR(15) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    fakultas VARCHAR(50) NOT NULL,
    program_studi VARCHAR(50) NOT NULL,
    angkatan INT NOT NULL,
    status ENUM('aktif', 'cuti', 'lulus', 'nonaktif') DEFAULT 'aktif',
    ipk DECIMAL(3,2) DEFAULT 0.00,
    total_sks INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create fakultas table
CREATE TABLE IF NOT EXISTS fakultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_fakultas VARCHAR(100) NOT NULL,
    kode_fakultas VARCHAR(10) UNIQUE NOT NULL,
    dekan VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create program_studi table
CREATE TABLE IF NOT EXISTS program_studi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_prodi VARCHAR(100) NOT NULL,
    kode_prodi VARCHAR(10) UNIQUE NOT NULL,
    fakultas_id INT NOT NULL,
    kaprodi VARCHAR(100) NOT NULL,
    akreditasi VARCHAR(10) DEFAULT 'C',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fakultas_id) REFERENCES fakultas(id) ON DELETE CASCADE
);

-- Create mata_kuliah table
CREATE TABLE IF NOT EXISTS mata_kuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_mk VARCHAR(20) UNIQUE NOT NULL,
    nama_mk VARCHAR(100) NOT NULL,
    sks INT NOT NULL,
    semester INT NOT NULL,
    prodi_id INT NOT NULL,
    dosen_pengampu VARCHAR(100) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prodi_id) REFERENCES program_studi(id) ON DELETE CASCADE
);

-- Create jadwal_kuliah table
CREATE TABLE IF NOT EXISTS jadwal_kuliah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mata_kuliah_id INT NOT NULL,
    hari ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    ruangan VARCHAR(20) NOT NULL,
    semester ENUM('Ganjil', 'Genap') NOT NULL,
    tahun_akademik VARCHAR(10) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE
);

-- Create keuangan table
CREATE TABLE IF NOT EXISTS keuangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mahasiswa_id INT NOT NULL,
    jenis_pembayaran ENUM('SPP', 'Uang Lab', 'Uang Praktikum', 'Lainnya') NOT NULL,
    jumlah DECIMAL(10,2) NOT NULL,
    semester ENUM('Ganjil', 'Genap') NOT NULL,
    tahun_akademik VARCHAR(10) NOT NULL,
    status ENUM('belum_bayar', 'sudah_bayar', 'terlambat') DEFAULT 'belum_bayar',
    tanggal_bayar DATE NULL,
    keterangan TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (mahasiswa_id) REFERENCES mahasiswa(id) ON DELETE CASCADE
);

-- Insert sample data for fakultas
INSERT INTO fakultas (nama_fakultas, kode_fakultas, dekan) VALUES
('Fakultas Teknik', 'FT', 'Dr. Ir. Budi Santoso, M.T.'),
('Fakultas Ekonomi', 'FE', 'Dr. Sarah Putri, S.E., M.M.'),
('Fakultas Hukum', 'FH', 'Dr. Ahmad Fadillah, S.H., M.H.'),
('Fakultas Kedokteran', 'FK', 'Dr. dr. Rizki Pratama, Sp.PD.'),
('Fakultas MIPA', 'FMIPA', 'Dr. Dewi Sartika, M.Si.'),
('Fakultas Sastra', 'FS', 'Dr. Siti Nurhaliza, M.Hum.');

-- Insert sample data for program studi
INSERT INTO program_studi (nama_prodi, kode_prodi, fakultas_id, kaprodi, akreditasi) VALUES
('Teknik Informatika', 'TI', 1, 'Dr. Ir. John Doe, M.T.', 'A'),
('Teknik Mesin', 'TM', 1, 'Dr. Ir. Jane Smith, M.T.', 'A'),
('Manajemen', 'MNJ', 2, 'Dr. Sarah Putri, S.E., M.M.', 'A'),
('Akuntansi', 'AKT', 2, 'Dr. Ahmad Fadillah, S.E., M.Ak.', 'A'),
('Ilmu Hukum', 'HKM', 3, 'Dr. Budi Santoso, S.H., M.H.', 'A'),
('Pendidikan Dokter', 'PD', 4, 'Dr. dr. Rizki Pratama, Sp.PD.', 'A'),
('Matematika', 'MTK', 5, 'Dr. Dewi Sartika, M.Si.', 'A'),
('Sastra Indonesia', 'SIN', 6, 'Dr. Siti Nurhaliza, M.Hum.', 'A');

-- Insert sample data for users (password: admin/admin, mahasiswa/mahasiswa123)
INSERT INTO users (username, password, role, nama_lengkap, email, nim, fakultas, program_studi, angkatan, status) VALUES
('admin', 'admin', 'admin', 'Administrator SIOM', 'admin@siom.edu', NULL, NULL, NULL, NULL, 'aktif'),
('2024001', 'mahasiswa123', 'mahasiswa', 'Ahmad Fadillah', 'ahmad.fadillah@email.com', '2024001', 'Fakultas Teknik', 'Teknik Informatika', 2024, 'aktif'),
('2024002', 'mahasiswa123', 'mahasiswa', 'Sarah Putri', 'sarah.putri@email.com', '2024002', 'Fakultas Ekonomi', 'Manajemen', 2024, 'aktif'),
('2023001', 'mahasiswa123', 'mahasiswa', 'Budi Santoso', 'budi.santoso@email.com', '2023001', 'Fakultas Hukum', 'Ilmu Hukum', 2023, 'cuti'),
('2022001', 'mahasiswa123', 'mahasiswa', 'Dewi Sartika', 'dewi.sartika@email.com', '2022001', 'Fakultas Kedokteran', 'Pendidikan Dokter', 2022, 'lulus'),
('2024003', 'mahasiswa123', 'mahasiswa', 'Rizki Pratama', 'rizki.pratama@email.com', '2024003', 'Fakultas MIPA', 'Matematika', 2024, 'aktif');

-- Insert sample data for mahasiswa
INSERT INTO mahasiswa (nim, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, no_hp, email, fakultas, program_studi, angkatan, status, ipk, total_sks) VALUES
('2024001', 'Ahmad Fadillah', 'Jakarta', '2000-05-15', 'L', 'Islam', 'Jl. Sudirman No. 123, Jakarta', '081234567890', 'ahmad.fadillah@email.com', 'Fakultas Teknik', 'Teknik Informatika', 2024, 'aktif', 3.85, 24),
('2024002', 'Sarah Putri', 'Bandung', '2000-08-20', 'P', 'Islam', 'Jl. Asia Afrika No. 456, Bandung', '081234567891', 'sarah.putri@email.com', 'Fakultas Ekonomi', 'Manajemen', 2024, 'aktif', 3.90, 24),
('2023001', 'Budi Santoso', 'Surabaya', '1999-12-10', 'L', 'Islam', 'Jl. Tunjungan No. 789, Surabaya', '081234567892', 'budi.santoso@email.com', 'Fakultas Hukum', 'Ilmu Hukum', 2023, 'cuti', 3.75, 48),
('2022001', 'Dewi Sartika', 'Yogyakarta', '1998-03-25', 'P', 'Islam', 'Jl. Malioboro No. 321, Yogyakarta', '081234567893', 'dewi.sartika@email.com', 'Fakultas Kedokteran', 'Pendidikan Dokter', 2022, 'lulus', 3.95, 144),
('2024003', 'Rizki Pratama', 'Semarang', '2000-11-05', 'L', 'Islam', 'Jl. Pandanaran No. 654, Semarang', '081234567894', 'rizki.pratama@email.com', 'Fakultas MIPA', 'Matematika', 2024, 'aktif', 3.80, 24),
('2024004', 'Nina Safitri', 'Medan', '2000-07-12', 'P', 'Islam', 'Jl. Gatot Subroto No. 111, Medan', '081234567895', 'nina.safitri@email.com', 'Fakultas Teknik', 'Teknik Mesin', 2024, 'aktif', 3.70, 24),
('2024005', 'Doni Kusuma', 'Palembang', '2000-09-30', 'L', 'Islam', 'Jl. Jendral Sudirman No. 222, Palembang', '081234567896', 'doni.kusuma@email.com', 'Fakultas Ekonomi', 'Akuntansi', 2024, 'aktif', 3.88, 24),
('2023002', 'Maya Indah', 'Makassar', '1999-04-18', 'P', 'Islam', 'Jl. Pengayoman No. 333, Makassar', '081234567897', 'maya.indah@email.com', 'Fakultas Sastra', 'Sastra Indonesia', 2023, 'aktif', 3.82, 48),
('2023003', 'Hendra Wijaya', 'Denpasar', '1999-06-22', 'L', 'Islam', 'Jl. Diponegoro No. 444, Denpasar', '081234567898', 'hendra.wijaya@email.com', 'Fakultas Teknik', 'Teknik Informatika', 2023, 'aktif', 3.76, 48),
('2022002', 'Siti Aisyah', 'Malang', '1998-11-08', 'P', 'Islam', 'Jl. Soekarno Hatta No. 555, Malang', '081234567899', 'siti.aisyah@email.com', 'Fakultas Kedokteran', 'Pendidikan Dokter', 2022, 'aktif', 3.92, 144),
('2024006', 'Aditya Nugraha', 'Solo', '2000-02-14', 'L', 'Islam', 'Jl. Slamet Riyadi No. 666, Solo', '081234567800', 'aditya.nugraha@email.com', 'Fakultas MIPA', 'Matematika', 2024, 'aktif', 3.78, 24),
('2024007', 'Putri Wulandari', 'Manado', '2000-10-25', 'P', 'Islam', 'Jl. Sam Ratulangi No. 777, Manado', '081234567801', 'putri.wulandari@email.com', 'Fakultas Ekonomi', 'Manajemen', 2024, 'aktif', 3.85, 24),
('2023004', 'Bambang Setiawan', 'Pontianak', '1999-01-03', 'L', 'Islam', 'Jl. Gajah Mada No. 888, Pontianak', '081234567802', 'bambang.setiawan@email.com', 'Fakultas Hukum', 'Ilmu Hukum', 2023, 'aktif', 3.79, 48),
('2024008', 'Ratna Sari', 'Balikpapan', '2000-12-07', 'P', 'Islam', 'Jl. Jendral Sudirman No. 999, Balikpapan', '081234567803', 'ratna.sari@email.com', 'Fakultas Teknik', 'Teknik Mesin', 2024, 'aktif', 3.81, 24),
('2024009', 'Eko Prasetyo', 'Tangerang', '2000-03-19', 'L', 'Islam', 'Jl. Raya Serpong No. 100, Tangerang', '081234567804', 'eko.prasetyo@email.com', 'Fakultas MIPA', 'Matematika', 2024, 'aktif', 3.83, 24),
('2024010', 'Diana Permata', 'Bekasi', '2000-08-11', 'P', 'Islam', 'Jl. Ahmad Yani No. 200, Bekasi', '081234567805', 'diana.permata@email.com', 'Fakultas Sastra', 'Sastra Indonesia', 2024, 'aktif', 3.87, 24);

-- Insert sample data for mata_kuliah
INSERT INTO mata_kuliah (kode_mk, nama_mk, sks, semester, prodi_id, dosen_pengampu) VALUES
('TI101', 'Pemrograman Web', 3, 1, 1, 'Dr. Ir. John Doe, M.T.'),
('TI102', 'Basis Data', 3, 1, 1, 'Dr. Sarah Putri, S.E., M.M.'),
('TI103', 'Algoritma & Struktur Data', 3, 1, 1, 'Dr. Ahmad Fadillah, S.H., M.H.');

-- Insert sample data for jadwal kuliah
INSERT INTO jadwal_kuliah (mata_kuliah_id, hari, jam_mulai, jam_selesai, ruangan, semester, tahun_akademik) VALUES
(1, 'Senin', '08:00:00', '10:30:00', 'Lab 1', 'Ganjil', '2024/2025'),
(2, 'Senin', '10:30:00', '13:00:00', 'Ruang 201', 'Ganjil', '2024/2025'),
(3, 'Senin', '13:00:00', '15:30:00', 'Ruang 301', 'Ganjil', '2024/2025'),
(4, 'Selasa', '08:00:00', '10:30:00', 'Ruang 101', 'Ganjil', '2024/2025'),
(5, 'Selasa', '10:30:00', '13:00:00', 'Ruang 102', 'Ganjil', '2024/2025');

-- Insert sample data for keuangan
INSERT INTO keuangan (mahasiswa_id, jenis_pembayaran, jumlah, semester, tahun_akademik, status, tanggal_bayar, keterangan) VALUES
(1, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(1, 'SPP', 5000000.00, 'Genap', '2024/2025', 'belum_bayar', NULL, 'Pembayaran SPP semester 2'),
(1, 'Uang Lab', 500000.00, 'Ganjil', '2024/2025', 'terlambat', NULL, 'Uang praktikum lab'),
(2, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(2, 'Uang Praktikum', 750000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-15', 'Uang praktikum manajemen'),
(3, 'SPP', 5000000.00, 'Ganjil', '2023/2024', 'sudah_bayar', '2023-08-01', 'Pembayaran SPP semester 1'),
(3, 'SPP', 5000000.00, 'Genap', '2023/2024', 'sudah_bayar', '2024-01-15', 'Pembayaran SPP semester 2'),
(4, 'SPP', 5000000.00, 'Ganjil', '2022/2023', 'sudah_bayar', '2022-08-01', 'Pembayaran SPP semester 1'),
(4, 'SPP', 5000000.00, 'Genap', '2022/2023', 'sudah_bayar', '2023-01-15', 'Pembayaran SPP semester 2'),
(4, 'Uang Lab', 1000000.00, 'Ganjil', '2022/2023', 'sudah_bayar', '2022-09-01', 'Uang lab kedokteran'),
(5, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(5, 'Uang Praktikum', 300000.00, 'Ganjil', '2024/2025', 'belum_bayar', NULL, 'Uang praktikum matematika'),
(6, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(6, 'Uang Lab', 600000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-20', 'Uang lab teknik mesin'),
(7, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(7, 'Uang Praktikum', 400000.00, 'Ganjil', '2024/2025', 'belum_bayar', NULL, 'Uang praktikum akuntansi'),
(8, 'SPP', 5000000.00, 'Ganjil', '2023/2024', 'sudah_bayar', '2023-08-01', 'Pembayaran SPP semester 1'),
(8, 'SPP', 5000000.00, 'Genap', '2023/2024', 'sudah_bayar', '2024-01-15', 'Pembayaran SPP semester 2'),
(8, 'Uang Praktikum', 250000.00, 'Ganjil', '2023/2024', 'sudah_bayar', '2023-09-01', 'Uang praktikum sastra'),
(9, 'SPP', 5000000.00, 'Ganjil', '2023/2024', 'sudah_bayar', '2023-08-01', 'Pembayaran SPP semester 1'),
(9, 'SPP', 5000000.00, 'Genap', '2023/2024', 'belum_bayar', NULL, 'Pembayaran SPP semester 2'),
(9, 'Uang Lab', 500000.00, 'Ganjil', '2023/2024', 'terlambat', NULL, 'Uang lab informatika'),
(10, 'SPP', 5000000.00, 'Ganjil', '2022/2023', 'sudah_bayar', '2022-08-01', 'Pembayaran SPP semester 1'),
(10, 'SPP', 5000000.00, 'Genap', '2022/2023', 'sudah_bayar', '2023-01-15', 'Pembayaran SPP semester 2'),
(10, 'Uang Lab', 1200000.00, 'Ganjil', '2022/2023', 'sudah_bayar', '2022-09-01', 'Uang lab kedokteran'),
(11, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(11, 'Uang Praktikum', 300000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-25', 'Uang praktikum matematika'),
(12, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(12, 'Uang Praktikum', 750000.00, 'Ganjil', '2024/2025', 'belum_bayar', NULL, 'Uang praktikum manajemen'),
(13, 'SPP', 5000000.00, 'Ganjil', '2023/2024', 'sudah_bayar', '2023-08-01', 'Pembayaran SPP semester 1'),
(13, 'SPP', 5000000.00, 'Genap', '2023/2024', 'sudah_bayar', '2024-01-15', 'Pembayaran SPP semester 2'),
(13, 'Uang Praktikum', 350000.00, 'Ganjil', '2023/2024', 'sudah_bayar', '2023-09-01', 'Uang praktikum hukum'),
(14, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(14, 'Uang Lab', 600000.00, 'Ganjil', '2024/2025', 'terlambat', NULL, 'Uang lab teknik mesin'),
(15, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(15, 'Uang Praktikum', 300000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-30', 'Uang praktikum matematika'),
(16, 'SPP', 5000000.00, 'Ganjil', '2024/2025', 'sudah_bayar', '2024-08-01', 'Pembayaran SPP semester 1'),
(16, 'Uang Praktikum', 250000.00, 'Ganjil', '2024/2025', 'belum_bayar', NULL, 'Uang praktikum sastra');

-- Create indexes for better performance
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_mahasiswa_nim ON mahasiswa(nim);
CREATE INDEX idx_mahasiswa_fakultas ON mahasiswa(fakultas);
CREATE INDEX idx_mahasiswa_status ON mahasiswa(status);
CREATE INDEX idx_keuangan_mahasiswa ON keuangan(mahasiswa_id);
CREATE INDEX idx_keuangan_status ON keuangan(status);

-- Create view for dashboard statistics
CREATE VIEW dashboard_stats AS
SELECT 
    COUNT(*) as total_mahasiswa,
    SUM(CASE WHEN status = 'aktif' THEN 1 ELSE 0 END) as mahasiswa_aktif,
    SUM(CASE WHEN status = 'cuti' THEN 1 ELSE 0 END) as mahasiswa_cuti,
    SUM(CASE WHEN status = 'lulus' THEN 1 ELSE 0 END) as mahasiswa_lulus,
    AVG(ipk) as rata_rata_ipk
FROM mahasiswa;

-- Create view for fakultas distribution
CREATE VIEW fakultas_distribution AS
SELECT 
    fakultas,
    COUNT(*) as jumlah_mahasiswa
FROM mahasiswa 
WHERE status = 'aktif'
GROUP BY fakultas
ORDER BY jumlah_mahasiswa DESC;

-- Show success message
SELECT 'Database SIOM berhasil dibuat!' as message;
SELECT 'Sample data berhasil dimasukkan!' as message;
SELECT 'Silakan login dengan kredensial berikut:' as message;
SELECT 'Admin - Username: admin, Password: admin123' as admin_credentials;
SELECT 'Mahasiswa - Username: 2024001, Password: mahasiswa123' as student_credentials; 