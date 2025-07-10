<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Test koneksi database
try {
    include_once '../config/database.php';
    
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        // Test query sederhana
        $query = "SELECT COUNT(*) as total FROM mahasiswa";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            "status" => "success",
            "message" => "Database connected successfully",
            "total_mahasiswa" => $result['total'],
            "connection" => "OK"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database connection failed",
            "connection" => "FAILED"
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error: " . $e->getMessage(),
        "connection" => "ERROR"
    ]);
}
?> 