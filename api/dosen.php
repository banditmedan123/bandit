<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $nama_dosen = isset($input['nama_dosen']) ? trim($input['nama_dosen']) : '';
    $nip = isset($input['nip']) ? trim($input['nip']) : '';
    $email = isset($input['email']) ? trim($input['email']) : '';
    $no_hp = isset($input['no_hp']) ? trim($input['no_hp']) : '';
    $fakultas = isset($input['fakultas']) ? trim($input['fakultas']) : '';
    $program_studi = isset($input['prodi']) ? trim($input['prodi']) : '';
    $jabatan = isset($input['jabatan']) ? trim($input['jabatan']) : '';

    if ($nama_dosen === '' || $nip === '' || $email === '' || $no_hp === '' || $fakultas === '' || $program_studi === '' || $jabatan === '') {
        echo json_encode(["status" => "error", "message" => "Semua field wajib diisi!"]);
        exit;
    }

    try {
        // Buat tabel dosen jika belum ada
        $db->exec("CREATE TABLE IF NOT EXISTS dosen (
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
        )");
        
        $query = "INSERT INTO dosen (nama_dosen, nip, email, no_hp, fakultas, program_studi, jabatan) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$nama_dosen, $nip, $email, $no_hp, $fakultas, $program_studi, $jabatan]);
        echo json_encode(["status" => "success", "message" => "Dosen berhasil ditambahkan!"]);
    } catch (PDOException $e) {
        $msg = $e->getCode() == 23000 ? 'NIP/email sudah terdaftar.' : $e->getMessage();
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
        $query = "DELETE FROM dosen WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Dosen berhasil dihapus!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Dosen tidak ditemukan!"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

// GET: tampilkan data dosen dari tabel dosen
try {
    // Buat tabel dosen jika belum ada
    $db->exec("CREATE TABLE IF NOT EXISTS dosen (
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
    )");
    
    $query = "SELECT * FROM dosen ORDER BY id";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $data]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage(), "data" => []]);
} 