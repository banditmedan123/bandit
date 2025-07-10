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
    $semester = isset($input['semester']) ? trim($input['semester']) : '';
    $tahun_akademik = isset($input['tahun_akademik']) ? trim($input['tahun_akademik']) : '';

    if ($nim === '' || $mata_kuliah_id <= 0 || $semester === '' || $tahun_akademik === '') {
        echo json_encode(["status" => "error", "message" => "Semua field wajib diisi!"]);
        exit;
    }

    try {
        // Buat tabel krs jika belum ada
        $db->exec("CREATE TABLE IF NOT EXISTS krs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nim VARCHAR(20) NOT NULL,
            mata_kuliah_id INT NOT NULL,
            semester ENUM('Ganjil', 'Genap') NOT NULL,
            tahun_akademik VARCHAR(10) NOT NULL,
            status ENUM('terdaftar', 'selesai', 'batal') DEFAULT 'terdaftar',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE
        )");
        
        $query = "INSERT INTO krs (nim, mata_kuliah_id, semester, tahun_akademik) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$nim, $mata_kuliah_id, $semester, $tahun_akademik]);
        echo json_encode(["status" => "success", "message" => "Mata kuliah berhasil ditambahkan ke KRS!"]);
    } catch (PDOException $e) {
        $msg = $e->getCode() == 23000 ? 'Mahasiswa sudah terdaftar untuk mata kuliah ini.' : $e->getMessage();
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
        $query = "DELETE FROM krs WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Mata kuliah berhasil dihapus dari KRS!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Data KRS tidak ditemukan!"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// GET: tampilkan data KRS
try {
    // Buat tabel krs jika belum ada
    $db->exec("CREATE TABLE IF NOT EXISTS krs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nim VARCHAR(20) NOT NULL,
        mata_kuliah_id INT NOT NULL,
        semester ENUM('Ganjil', 'Genap') NOT NULL,
        tahun_akademik VARCHAR(10) NOT NULL,
        status ENUM('terdaftar', 'selesai', 'batal') DEFAULT 'terdaftar',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (mata_kuliah_id) REFERENCES mata_kuliah(id) ON DELETE CASCADE
    )");
    
    // Ambil parameter nim jika ada
    $nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';
    
    if ($nim !== '') {
        // Filter berdasarkan NIM mahasiswa
        $query = "SELECT k.id, k.nim, k.semester, k.tahun_akademik, k.status, 
                         m.kode_mk as kode_matakuliah, m.nama_mk as nama_matakuliah, m.sks 
                  FROM krs k 
                  JOIN mata_kuliah m ON k.mata_kuliah_id = m.id 
                  WHERE k.nim = ? 
                  ORDER BY k.id";
        $stmt = $db->prepare($query);
        $stmt->execute([$nim]);
    } else {
        // Ambil semua data KRS (untuk admin)
        $query = "SELECT k.id, k.nim, k.semester, k.tahun_akademik, k.status, 
                         m.kode_mk as kode_matakuliah, m.nama_mk as nama_matakuliah, m.sks 
                  FROM krs k 
                  JOIN mata_kuliah m ON k.mata_kuliah_id = m.id 
                  ORDER BY k.id";
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