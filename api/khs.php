<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $nim = isset($input['nim']) ? trim($input['nim']) : '';
    $mata_kuliah_id = isset($input['mata_kuliah_id']) ? intval($input['mata_kuliah_id']) : 0;
    $nilai = isset($input['nilai']) ? trim($input['nilai']) : '';
    $bobot = isset($input['bobot']) ? floatval($input['bobot']) : 0;
    $semester = isset($input['semester']) ? trim($input['semester']) : '';
    $tahun_akademik = isset($input['tahun_akademik']) ? trim($input['tahun_akademik']) : '';

    if ($nim === '' || $mata_kuliah_id <= 0 || $nilai === '' || $semester === '' || $tahun_akademik === '') {
        echo json_encode(["status" => "error", "message" => "Semua field wajib diisi!"]);
        exit;
    }

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
        
        $query = "INSERT INTO khs (nim, mata_kuliah_id, semester, tahun_akademik, nilai, bobot) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$nim, $mata_kuliah_id, $semester, $tahun_akademik, $nilai, $bobot]);
        echo json_encode(["status" => "success", "message" => "Nilai berhasil ditambahkan ke KHS!"]);
    } catch (PDOException $e) {
        $msg = $e->getCode() == 23000 ? 'Data KHS sudah ada untuk mata kuliah ini.' : $e->getMessage();
        echo json_encode(["status" => "error", "message" => $msg]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = isset($input['id']) ? intval($input['id']) : 0;

    if ($id <= 0) {
        echo json_encode(["status" => "error", "message" => "ID tidak valid!"]);
        exit;
    }

    try {
        $query = "DELETE FROM khs WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Data KHS berhasil dihapus!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Data KHS tidak ditemukan!"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// GET: tampilkan data KHS
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
    
    // Ambil parameter nim jika ada
    $nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';
    
    if ($nim !== '') {
        // Filter berdasarkan NIM mahasiswa
        $query = "SELECT k.id, k.nim, k.semester, k.tahun_akademik, k.nilai, k.bobot,
                         m.kode_mk as kode_matakuliah, m.nama_mk as nama_matakuliah, m.sks 
                  FROM khs k 
                  JOIN mata_kuliah m ON k.mata_kuliah_id = m.id 
                  WHERE k.nim = ? 
                  ORDER BY k.semester DESC, k.tahun_akademik DESC, m.kode_mk";
        $stmt = $db->prepare($query);
        $stmt->execute([$nim]);
    } else {
        // Ambil semua data KHS (untuk admin)
        $query = "SELECT k.id, k.nim, k.semester, k.tahun_akademik, k.nilai, k.bobot,
                         m.kode_mk as kode_matakuliah, m.nama_mk as nama_matakuliah, m.sks 
                  FROM khs k 
                  JOIN mata_kuliah m ON k.mata_kuliah_id = m.id 
                  ORDER BY k.nim, k.semester DESC, k.tahun_akademik DESC";
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