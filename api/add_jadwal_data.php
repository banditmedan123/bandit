<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
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
    
    // Buat tabel ruangan jika belum ada
    $db->exec("CREATE TABLE IF NOT EXISTS ruangan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama_ruangan VARCHAR(50) NOT NULL,
        kapasitas INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
    
    // Tambah data jadwal contoh langsung
    $jadwalData = [
        ['nim' => '2024001', 'mata_kuliah_id' => 1, 'dosen_id' => 1, 'ruangan_id' => 1, 'hari' => 'Senin', 'jam_mulai' => '08:00:00', 'jam_selesai' => '09:30:00', 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025'],
        ['nim' => '2024001', 'mata_kuliah_id' => 2, 'dosen_id' => 2, 'ruangan_id' => 2, 'hari' => 'Selasa', 'jam_mulai' => '10:00:00', 'jam_selesai' => '11:30:00', 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025'],
        ['nim' => '2024001', 'mata_kuliah_id' => 3, 'dosen_id' => 3, 'ruangan_id' => 3, 'hari' => 'Rabu', 'jam_mulai' => '13:00:00', 'jam_selesai' => '14:30:00', 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025'],
        ['nim' => '2024001', 'mata_kuliah_id' => 4, 'dosen_id' => 1, 'ruangan_id' => 4, 'hari' => 'Kamis', 'jam_mulai' => '15:00:00', 'jam_selesai' => '16:30:00', 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025'],
        ['nim' => '2024001', 'mata_kuliah_id' => 5, 'dosen_id' => 2, 'ruangan_id' => 5, 'hari' => 'Jumat', 'jam_mulai' => '08:00:00', 'jam_selesai' => '09:30:00', 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025'],
        ['nim' => '2024001', 'mata_kuliah_id' => 6, 'dosen_id' => 3, 'ruangan_id' => 6, 'hari' => 'Sabtu', 'jam_mulai' => '10:00:00', 'jam_selesai' => '11:30:00', 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025']
    ];
    
    $insertQuery = "INSERT IGNORE INTO jadwal (nim, mata_kuliah_id, dosen_id, ruangan_id, hari, jam_mulai, jam_selesai, semester, tahun_akademik) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $db->prepare($insertQuery);
    
    $added = 0;
    foreach ($jadwalData as $data) {
        try {
            $insertStmt->execute([
                $data['nim'],
                $data['mata_kuliah_id'],
                $data['dosen_id'],
                $data['ruangan_id'],
                $data['hari'],
                $data['jam_mulai'],
                $data['jam_selesai'],
                $data['semester'],
                $data['tahun_akademik']
            ]);
            $added++;
        } catch (PDOException $e) {
            // Skip jika sudah ada
            continue;
        }
    }
    
    echo json_encode([
        "status" => "success", 
        "message" => "Berhasil menambahkan $added data jadwal contoh",
        "data" => [
            "nim" => "2024001",
            "semester" => "Ganjil",
            "tahun_akademik" => "2024/2025",
            "jumlah_jadwal" => $added
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Error: " . $e->getMessage()
    ]);
}
?> 