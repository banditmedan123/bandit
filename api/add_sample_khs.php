<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
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
    
    // Cek apakah sudah ada data KHS
    $checkQuery = "SELECT COUNT(*) as count FROM khs";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->execute();
    $count = $checkStmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    if ($count == 0) {
        // Ambil beberapa mata kuliah dari database
        $mkQuery = "SELECT id FROM mata_kuliah LIMIT 6";
        $mkStmt = $db->prepare($mkQuery);
        $mkStmt->execute();
        $mataKuliahIds = $mkStmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($mataKuliahIds) > 0) {
            // Tambah data KHS contoh untuk NIM 2024001
            $nim = "2024001";
            $semester = "Ganjil";
            $tahun_akademik = "2024/2025";
            
            $insertQuery = "INSERT INTO khs (nim, mata_kuliah_id, semester, tahun_akademik, nilai, grade) VALUES (?, ?, ?, ?, ?, ?)";
            $insertStmt = $db->prepare($insertQuery);
            
            $nilaiContoh = [85, 90, 88, 92, 87, 89];
            $gradeContoh = ['A', 'A', 'A', 'A', 'B', 'A'];
            
            $added = 0;
            foreach ($mataKuliahIds as $index => $mkId) {
                try {
                    $insertStmt->execute([
                        $nim, 
                        $mkId, 
                        $semester, 
                        $tahun_akademik, 
                        $nilaiContoh[$index % count($nilaiContoh)],
                        $gradeContoh[$index % count($gradeContoh)]
                    ]);
                    $added++;
                } catch (PDOException $e) {
                    // Skip jika sudah ada
                    continue;
                }
            }
            
            echo json_encode([
                "status" => "success", 
                "message" => "Berhasil menambahkan $added data KHS contoh untuk NIM $nim",
                "data" => [
                    "nim" => $nim,
                    "semester" => $semester,
                    "tahun_akademik" => $tahun_akademik,
                    "jumlah_mata_kuliah" => $added
                ]
            ]);
        } else {
            echo json_encode([
                "status" => "error", 
                "message" => "Tidak ada mata kuliah di database. Silakan tambah mata kuliah terlebih dahulu."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "info", 
            "message" => "Data KHS sudah ada di database. Total: $count data"
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Error: " . $e->getMessage()
    ]);
}
?> 