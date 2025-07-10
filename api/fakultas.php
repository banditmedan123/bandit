<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data JSON
    $input = json_decode(file_get_contents('php://input'), true);
    $nama_fakultas = isset($input['nama_fakultas']) ? trim($input['nama_fakultas']) : '';
    $kode_fakultas = isset($input['kode_fakultas']) ? trim($input['kode_fakultas']) : '';
    $dekan = isset($input['dekan']) ? trim($input['dekan']) : '';

    // Validasi
    if ($nama_fakultas === '' || $kode_fakultas === '' || $dekan === '') {
        echo json_encode(["status" => "error", "message" => "Semua field wajib diisi!"]);
        exit;
    }

    // Simpan ke database
    try {
        $query = "INSERT INTO fakultas (nama_fakultas, kode_fakultas, dekan) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$nama_fakultas, $kode_fakultas, $dekan]);
        echo json_encode(["status" => "success", "message" => "Fakultas berhasil ditambahkan!"]);
    } catch (PDOException $e) {
        $msg = $e->getCode() == 23000 ? 'Kode fakultas sudah terdaftar.' : $e->getMessage();
        echo json_encode(["status" => "error", "message" => $msg]);
    }
    exit;
}

try {
    $query = "SELECT id, nama_fakultas, kode_fakultas, dekan FROM fakultas ORDER BY id";
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