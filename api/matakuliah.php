<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $kode_mk = isset($input['kode_mk']) ? trim($input['kode_mk']) : '';
    $nama_mk = isset($input['nama_mk']) ? trim($input['nama_mk']) : '';
    $sks = isset($input['sks']) ? intval($input['sks']) : 0;
    $semester = isset($input['semester']) ? intval($input['semester']) : 0;
    $prodi = isset($input['prodi']) ? trim($input['prodi']) : '';
    $dosen_pengampu = isset($input['dosen_pengampu']) ? trim($input['dosen_pengampu']) : '';

    // Cari prodi_id dari nama prodi
    $prodi_id = null;
    if ($prodi !== '') {
        $stmt = $db->prepare('SELECT id FROM program_studi WHERE nama_prodi = ?');
        $stmt->execute([$prodi]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $prodi_id = $row['id'];
        }
    }

    if ($kode_mk === '' || $nama_mk === '' || !$prodi_id || $dosen_pengampu === '' || $sks <= 0 || $semester <= 0) {
        echo json_encode(["status" => "error", "message" => "Semua field wajib diisi dan program studi harus valid!"]);
        exit;
    }

    try {
        $query = "INSERT INTO mata_kuliah (kode_mk, nama_mk, sks, semester, prodi_id, dosen_pengampu) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$kode_mk, $nama_mk, $sks, $semester, $prodi_id, $dosen_pengampu]);
        echo json_encode(["status" => "success", "message" => "Mata Kuliah berhasil ditambahkan!"]);
    } catch (PDOException $e) {
        $msg = $e->getCode() == 23000 ? 'Kode mata kuliah sudah terdaftar.' : $e->getMessage();
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
        $query = "DELETE FROM mata_kuliah WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Mata kuliah berhasil dihapus!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Mata kuliah tidak ditemukan!"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    exit;
}

try {
    $query = "SELECT m.id, m.kode_mk, m.nama_mk, m.sks, m.semester, m.prodi_id, p.nama_prodi, m.dosen_pengampu FROM mata_kuliah m JOIN program_studi p ON m.prodi_id = p.id ORDER BY m.id";
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