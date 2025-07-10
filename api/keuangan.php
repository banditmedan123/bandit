<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$query = "SELECT k.*, m.nama_lengkap as nama_mahasiswa 
          FROM keuangan k 
          LEFT JOIN mahasiswa m ON k.mahasiswa_id = m.id 
          ORDER BY k.created_at DESC";
$stmt = $db->prepare($query);
$stmt->execute();

$data = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $row;
}
echo json_encode($data); 