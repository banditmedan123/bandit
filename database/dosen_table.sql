-- Database SIOM - Tabel Dosen
-- Created by: SIOM Development Team

-- Use database
USE siom_db;

-- Create dosen table sesuai form input dosen
CREATE TABLE IF NOT EXISTS dosen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_dosen VARCHAR(100) NOT NULL,
    nip VARCHAR(30) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    fakultas VARCHAR(100) NOT NULL,
    program_studi VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data for dosen
INSERT INTO dosen (nama_dosen, nip, email, no_hp, fakultas, program_studi, jabatan) VALUES
('Dr. Ir. John Doe, M.T.', '198001011990011001', 'john.doe@siom.edu', '081234567890', 'Fakultas Teknik', 'Teknik Informatika', 'Kaprodi'),
('Dr. Sarah Putri, S.E., M.M.', '198202021991022002', 'sarah.putri@siom.edu', '081234567891', 'Fakultas Ekonomi', 'Manajemen', 'Dosen'),
('Dr. Ahmad Fadillah, S.H., M.H.', '198303031992033003', 'ahmad.fadillah@siom.edu', '081234567892', 'Fakultas Hukum', 'Ilmu Hukum', 'Dosen'),
('Dr. dr. Rizki Pratama, Sp.PD.', '198404041993044004', 'rizki.pratama@siom.edu', '081234567893', 'Fakultas Kedokteran', 'Pendidikan Dokter', 'Dosen'),
('Dr. Dewi Sartika, M.Si.', '198505051994055005', 'dewi.sartika@siom.edu', '081234567894', 'Fakultas MIPA', 'Matematika', 'Dosen'),
('Dr. Siti Nurhaliza, M.Hum.', '198606061995066006', 'siti.nurhaliza@siom.edu', '081234567895', 'Fakultas Sastra', 'Sastra Indonesia', 'Dosen');

-- Create indexes for better performance
CREATE INDEX idx_dosen_nip ON dosen(nip);
CREATE INDEX idx_dosen_email ON dosen(email);
CREATE INDEX idx_dosen_fakultas ON dosen(fakultas);
CREATE INDEX idx_dosen_program_studi ON dosen(program_studi);

-- Create view for dosen statistics
CREATE VIEW dosen_stats AS
SELECT 
    COUNT(*) as total_dosen,
    COUNT(DISTINCT fakultas) as total_fakultas,
    COUNT(DISTINCT program_studi) as total_program_studi,
    COUNT(CASE WHEN jabatan = 'Kaprodi' THEN 1 END) as total_kaprodi,
    COUNT(CASE WHEN jabatan = 'Dosen' THEN 1 END) as total_dosen_regular
FROM dosen; 