<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

function writeLog($message) {
    $logFile = '../logs/api_debug.log';
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message" . PHP_EOL;
    if (!is_dir('../logs')) {
        mkdir('../logs', 0777, true);
    }
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
}

try {
    // Cek apakah ada parameter nim di query string
    $nim_param = isset($_GET['nim']) ? trim($_GET['nim']) : '';
    if ($nim_param !== '') {
        // Query langsung berdasarkan nim dari parameter
        $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
        $stmt->execute([$nim_param]);
        $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$mahasiswa) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan'
            ]);
            exit;
        }
        
        echo json_encode([
            'status' => 'success',
            'data' => [
                'mahasiswa' => $mahasiswa
            ]
        ]);
        exit;
    }
    
    // Cek session login
    session_start();
    writeLog("Session started. Session data: " . json_encode($_SESSION));
    
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        writeLog("User not logged in");
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'User tidak login'
        ]);
        exit;
    }
    
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
    writeLog("Username: $username, Role: $role");
    
    // Pastikan user adalah mahasiswa
    if ($role !== 'mahasiswa') {
        writeLog("Access denied. Role: $role");
        http_response_code(403);
        echo json_encode([
            'status' => 'error',
            'message' => 'Akses ditolak. Hanya untuk mahasiswa.'
        ]);
        exit;
    }
    
    // Ambil NIM dari session (username mahasiswa = NIM)
    $nim = $_SESSION['username']; // NIM = username untuk mahasiswa
    writeLog("Using NIM from session: $nim");
    
    // Ambil data mahasiswa berdasarkan NIM
    $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
    $stmt->execute([$nim]);
    $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    writeLog("Mahasiswa query result: " . json_encode($mahasiswa));
    
    if (!$mahasiswa) {
        writeLog("Mahasiswa not found for NIM: $nim");
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Data mahasiswa tidak ditemukan untuk NIM: ' . $nim
        ]);
        exit;
    }
    
    // Gabungkan semua data
    $data = [
        'mahasiswa' => $mahasiswa
    ];
    
    writeLog("Success returning data for NIM: $nim");
    echo json_encode([
        'status' => 'success',
        'data' => $data
    ]);
    
} catch (Exception $e) {
    writeLog("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 