<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Update password untuk user yang sudah ada
    $users = [
        ['username' => 'admin', 'password' => 'password'],
        ['username' => 'dosen001', 'password' => 'password'],
        ['username' => 'mahasiswa001', 'password' => 'password'],
        ['username' => 'dosen002', 'password' => 'password'],
        ['username' => 'mahasiswa002', 'password' => 'password']
    ];
    
    $updated = 0;
    foreach ($users as $user) {
        $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
        $result = $stmt->execute([$hashed_password, $user['username']]);
        
        if ($result) {
            $updated++;
        }
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => "Berhasil update password untuk $updated user",
        'updated_count' => $updated
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 