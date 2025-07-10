<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Buat tabel ruangan jika belum ada
    $db->exec("CREATE TABLE IF NOT EXISTS ruangan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_ruangan VARCHAR(50) NOT NULL,
        kapasitas INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Buat tabel khs jika belum ada
    $db->exec("CREATE TABLE IF NOT EXISTS khs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nim VARCHAR(20) NOT NULL,
        mata_kuliah_id INT NOT NULL,
        semester ENUM('Ganjil', 'Genap') NOT NULL,
        tahun_akademik VARCHAR(10) NOT NULL,
        nilai DECIMAL(4,2) DEFAULT NULL,
        grade CHAR(1) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE
    )");
    
    // Buat tabel jadwal jika belum ada
    $db->exec("CREATE TABLE IF NOT EXISTS jadwal (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nim VARCHAR(20) NOT NULL,
        mata_kuliah_id INT NOT NULL,
        dosen_id INT NOT NULL,
        ruangan_id INT NOT NULL,
        hari ENUM('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu') NOT NULL,
        jam_mulai TIME NOT NULL,
        jam_selesai TIME NOT NULL,
        semester ENUM('Ganjil', 'Genap') NOT NULL,
        tahun_akademik VARCHAR(10) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE,
        FOREIGN KEY (dosen_id) REFERENCES dosen(id) ON DELETE CASCADE,
        FOREIGN KEY (ruangan_id) REFERENCES ruangan(id) ON DELETE CASCADE
    )");
    
    // Tambah data ruangan contoh
    $ruanganQuery = "INSERT IGNORE INTO ruangan (nama_ruangan, kapasitas) VALUES 
        ('Lab Komputer 1', 30),
        ('Lab Komputer 2', 30),
        ('Ruang Kelas A1', 40),
        ('Ruang Kelas A2', 40),
        ('Ruang Kelas B1', 35),
        ('Ruang Kelas B2', 35)";
    $db->exec($ruanganQuery);
    
    // Ambil data mata kuliah, dosen, dan ruangan
    $mkQuery = "SELECT id FROM mata_kuliah LIMIT 6";
    $mkStmt = $db->prepare($mkQuery);
    $mkStmt->execute();
    $mataKuliahIds = $mkStmt->fetchAll(PDO::FETCH_COLUMN);
    
    $dosenQuery = "SELECT id FROM dosen LIMIT 3";
    $dosenStmt = $db->prepare($dosenQuery);
    $dosenStmt->execute();
    $dosenIds = $dosenStmt->fetchAll(PDO::FETCH_COLUMN);
    
    $ruanganQuery = "SELECT id FROM ruangan LIMIT 6";
    $ruanganStmt = $db->prepare($ruanganQuery);
    $ruanganStmt->execute();
    $ruanganIds = $ruanganStmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($mataKuliahIds) > 0 && count($dosenIds) > 0 && count($ruanganIds) > 0) {
        $nim = "2024001";
        $semester = "Ganjil";
        $tahun_akademik = "2024/2025";
        
        // Tambah data KHS contoh
        $khsQuery = "INSERT IGNORE INTO khs (nim, mata_kuliah_id, semester, tahun_akademik, nilai, grade) VALUES (?, ?, ?, ?, ?, ?)";
        $khsStmt = $db->prepare($khsQuery);
        
        $nilaiContoh = [85, 90, 88, 92, 87, 89];
        $gradeContoh = ['A', 'A', 'A', 'A', 'B', 'A'];
        
        $khsAdded = 0;
        foreach ($mataKuliahIds as $index => $mkId) {
            try {
                $khsStmt->execute([
                    $nim, 
                    $mkId, 
                    $semester, 
                    $tahun_akademik, 
                    $nilaiContoh[$index % count($nilaiContoh)],
                    $gradeContoh[$index % count($gradeContoh)]
                ]);
                $khsAdded++;
            } catch (PDOException $e) {
                // Skip jika sudah ada
                continue;
            }
        }
        
        // Tambah data jadwal contoh
        $jadwalQuery = "INSERT IGNORE INTO jadwal (nim, mata_kuliah_id, dosen_id, ruangan_id, hari, jam_mulai, jam_selesai, semester, tahun_akademik) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $jadwalStmt = $db->prepare($jadwalQuery);
        
        $hariContoh = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jamMulaiContoh = ['08:00:00', '10:00:00', '13:00:00', '15:00:00', '08:00:00', '10:00:00'];
        $jamSelesaiContoh = ['09:30:00', '11:30:00', '14:30:00', '16:30:00', '09:30:00', '11:30:00'];
        
        $jadwalAdded = 0;
        foreach ($mataKuliahIds as $index => $mkId) {
            try {
                $jadwalStmt->execute([
                    $nim,
                    $mkId,
                    $dosenIds[$index % count($dosenIds)],
                    $ruanganIds[$index % count($ruanganIds)],
                    $hariContoh[$index % count($hariContoh)],
                    $jamMulaiContoh[$index % count($jamMulaiContoh)],
                    $jamSelesaiContoh[$index % count($jamSelesaiContoh)],
                    $semester,
                    $tahun_akademik
                ]);
                $jadwalAdded++;
            } catch (PDOException $e) {
                // Skip jika sudah ada
                continue;
            }
        }
        
        echo json_encode([
            "status" => "success", 
            "message" => "Berhasil menambahkan data contoh",
            "data" => [
                "nim" => $nim,
                "semester" => $semester,
                "tahun_akademik" => $tahun_akademik,
                "khs_added" => $khsAdded,
                "jadwal_added" => $jadwalAdded
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error", 
            "message" => "Data mata kuliah, dosen, atau ruangan tidak cukup. Silakan tambah data terlebih dahulu."
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Error: " . $e->getMessage()
    ]);
}
?> 