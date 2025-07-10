<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Cek apakah tabel users ada
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Tabel users tidak ditemukan!'
        ]);
        exit;
    }
    
    // Cek struktur tabel users
    $stmt = $db->query("DESCRIBE users");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $required_columns = ['id', 'username', 'password', 'role', 'full_name', 'email'];
    $missing_columns = array_diff($required_columns, $columns);
    
    if (!empty($missing_columns)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Kolom yang hilang: ' . implode(', ', $missing_columns)
        ]);
        exit;
    }
    
    // Ambil semua user dari database
    $stmt = $db->query("SELECT id, username, role, full_name, email FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo json_encode([
            'status' => 'warning',
            'message' => 'Tidak ada user di database!',
            'users' => []
        ]);
        exit;
    }
    
    // Cek apakah password sudah di-hash
    $stmt = $db->query("SELECT username, password FROM users LIMIT 1");
    $sample_user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $password_info = '';
    if ($sample_user) {
        if (password_verify('password', $sample_user['password'])) {
            $password_info = 'Password sudah di-hash dengan benar';
        } else {
            $password_info = 'Password belum di-hash dengan benar';
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Database users siap untuk login',
        'password_info' => $password_info,
        'total_users' => count($users),
        'users' => $users
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 