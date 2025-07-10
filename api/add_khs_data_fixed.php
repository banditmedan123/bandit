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
        nilai VARCHAR(2) NOT NULL,
        bobot DECIMAL(3,1) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE
    )");
    
    // Tambah data KHS contoh langsung
    $khsData = [
        ['nim' => '2024001', 'mata_kuliah_id' => 1, 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025', 'nilai' => 'A', 'bobot' => 4.0],
        ['nim' => '2024001', 'mata_kuliah_id' => 2, 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025', 'nilai' => 'A', 'bobot' => 4.0],
        ['nim' => '2024001', 'mata_kuliah_id' => 3, 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025', 'nilai' => 'A', 'bobot' => 4.0],
        ['nim' => '2024001', 'mata_kuliah_id' => 4, 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025', 'nilai' => 'A', 'bobot' => 4.0],
        ['nim' => '2024001', 'mata_kuliah_id' => 5, 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025', 'nilai' => 'B', 'bobot' => 3.0],
        ['nim' => '2024001', 'mata_kuliah_id' => 6, 'semester' => 'Ganjil', 'tahun_akademik' => '2024/2025', 'nilai' => 'A', 'bobot' => 4.0]
    ];
    
    $insertQuery = "INSERT IGNORE INTO khs (nim, mata_kuliah_id, semester, tahun_akademik, nilai, bobot) VALUES (?, ?, ?, ?, ?, ?)";
    $insertStmt = $db->prepare($insertQuery);
    
    $added = 0;
    foreach ($khsData as $data) {
        try {
            $insertStmt->execute([
                $data['nim'],
                $data['mata_kuliah_id'],
                $data['semester'],
                $data['tahun_akademik'],
                $data['nilai'],
                $data['bobot']
            ]);
            $added++;
        } catch (PDOException $e) {
            // Skip jika sudah ada
            continue;
        }
    }
    
    echo json_encode([
        "status" => "success", 
        "message" => "Berhasil menambahkan $added data KHS contoh",
        "data" => [
            "nim" => "2024001",
            "semester" => "Ganjil",
            "tahun_akademik" => "2024/2025",
            "jumlah_mata_kuliah" => $added
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error", 
        "message" => "Error: " . $e->getMessage()
    ]);
}
?> 