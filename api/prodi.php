<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $nama_prodi = isset($input['nama_prodi']) ? trim($input['nama_prodi']) : '';
    $kode_prodi = isset($input['kode_prodi']) ? trim($input['kode_prodi']) : '';
    $fakultas = isset($input['fakultas']) ? trim($input['fakultas']) : '';
    $kaprodi = isset($input['kaprodi']) ? trim($input['kaprodi']) : '';
    $akreditasi = isset($input['akreditasi']) ? trim($input['akreditasi']) : 'C';

    // Cari fakultas_id dari nama fakultas
    $fakultas_id = null;
    if ($fakultas !== '') {
        $stmt = $db->prepare('SELECT id FROM fakultas WHERE nama_fakultas = ?');
        $stmt->execute([$fakultas]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $fakultas_id = $row['id'];
        }
    }

    if ($nama_prodi === '' || $kode_prodi === '' || !$fakultas_id || $kaprodi === '') {
        echo json_encode(["status" => "error", "message" => "Semua field wajib diisi dan fakultas harus valid!"]);
        exit;
    }

    try {
        $query = "INSERT INTO program_studi (nama_prodi, kode_prodi, fakultas_id, kaprodi, akreditasi) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$nama_prodi, $kode_prodi, $fakultas_id, $kaprodi, $akreditasi]);
        echo json_encode(["status" => "success", "message" => "Program Studi berhasil ditambahkan!"]);
    } catch (PDOException $e) {
        $msg = $e->getCode() == 23000 ? 'Kode prodi sudah terdaftar.' : $e->getMessage();
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
        $query = "DELETE FROM program_studi WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Program studi berhasil dihapus!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Program studi tidak ditemukan!"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

try {
    $query = "SELECT p.id, p.nama_prodi, p.kode_prodi, p.fakultas_id, f.nama_fakultas, p.kaprodi, p.akreditasi FROM program_studi p JOIN fakultas f ON p.fakultas_id = f.id ORDER BY p.id";
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