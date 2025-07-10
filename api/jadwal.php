<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

// GET: tampilkan data jadwal
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
    
    // Ambil parameter nim jika ada
    $nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';
    
    if ($nim !== '') {
        // Filter berdasarkan NIM mahasiswa
        $query = "SELECT j.id, j.nim, j.hari, j.jam_mulai, j.jam_selesai, j.semester, j.tahun_akademik,
                         m.kode_mk, m.nama_mk as nama_matakuliah, m.sks,
                         d.nama_dosen,
                         r.nama_ruangan
                  FROM jadwal j 
                  JOIN mata_kuliah m ON j.mata_kuliah_id = m.id 
                  JOIN dosen d ON j.dosen_id = d.id
                  JOIN ruangan r ON j.ruangan_id = r.id
                  WHERE j.nim = ? 
                  ORDER BY FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.jam_mulai";
        $stmt = $db->prepare($query);
        $stmt->execute([$nim]);
    } else {
        // Ambil semua data jadwal (untuk admin)
        $query = "SELECT j.id, j.nim, j.hari, j.jam_mulai, j.jam_selesai, j.semester, j.tahun_akademik,
                         m.kode_mk, m.nama_mk as nama_matakuliah, m.sks,
                         d.nama_dosen,
                         r.nama_ruangan
                  FROM jadwal j 
                  JOIN mata_kuliah m ON j.mata_kuliah_id = m.id 
                  JOIN dosen d ON j.dosen_id = d.id
                  JOIN ruangan r ON j.ruangan_id = r.id
                  ORDER BY j.nim, FIELD(j.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), j.jam_mulai";
        $stmt = $db->prepare($query);
        $stmt->execute();
    }
    
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $data]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage(), "data" => []]);
}
?> 