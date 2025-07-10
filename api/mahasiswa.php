<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

try {
    include_once '../config/database.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception("Database connection failed");
    }
    
    $query = "SELECT id, nim, nama_lengkap, fakultas, program_studi, angkatan, status, email FROM mahasiswa ORDER BY nama_lengkap";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    
    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage(),
        "data" => []
    ]);
}
?> 