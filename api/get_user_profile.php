<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

// Fungsi untuk logging
function writeLog($message) {
    $logFile = '../logs/profile_debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    
    // Buat direktori logs jika belum ada
    if (!is_dir('../logs')) {
        mkdir('../logs', 0777, true);
    }
    
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
}

try {
    // Cek session login
    session_start();
    writeLog("Profile API - Session started. Session data: " . json_encode($_SESSION));
    
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        writeLog("Profile API - User not logged in");
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'User tidak login'
        ]);
        exit;
    }
    
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
    $user_id = $_SESSION['user_id'];
    writeLog("Profile API - Username: $username, Role: $role, User ID: $user_id");
    
    $database = new Database();
    $db = $database->getConnection();
    
    // Ambil data user dari tabel users
    $stmt = $db->prepare("
        SELECT id, username, full_name, email, role, created_at 
        FROM users 
        WHERE id = ?
    ");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    writeLog("Profile API - User data: " . json_encode($user));
    
    if (!$user) {
        writeLog("Profile API - User not found for ID: $user_id");
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Data user tidak ditemukan'
        ]);
        exit;
    }
    
    // Jika role adalah mahasiswa, ambil data tambahan dari tabel mahasiswa
    $mahasiswa_data = null;
    if ($role === 'mahasiswa') {
        $stmt = $db->prepare("
            SELECT m.*, f.nama_fakultas, p.nama_prodi 
            FROM mahasiswa m 
            LEFT JOIN fakultas f ON m.id_fakultas = f.id 
            LEFT JOIN prodi p ON m.id_prodi = p.id 
            WHERE m.nim = ?
        ");
        $stmt->execute([$username]);
        $mahasiswa_data = $stmt->fetch(PDO::FETCH_ASSOC);
        writeLog("Profile API - Mahasiswa data: " . json_encode($mahasiswa_data));
    }
    
    // Jika role adalah dosen, ambil data tambahan dari tabel dosen
    $dosen_data = null;
    if ($role === 'dosen') {
        $stmt = $db->prepare("
            SELECT d.*, f.nama_fakultas 
            FROM dosen d 
            LEFT JOIN fakultas f ON d.id_fakultas = f.id 
            WHERE d.nidn = ?
        ");
        $stmt->execute([$username]);
        $dosen_data = $stmt->fetch(PDO::FETCH_ASSOC);
        writeLog("Profile API - Dosen data: " . json_encode($dosen_data));
    }
    
    // Gabungkan data
    $profile_data = [
        'user' => $user,
        'role_specific_data' => $mahasiswa_data ?: $dosen_data
    ];
    
    writeLog("Profile API - Success returning profile data for user: $username");
    echo json_encode([
        'status' => 'success',
        'data' => $profile_data
    ]);
    
} catch (Exception $e) {
    writeLog("Profile API - Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 