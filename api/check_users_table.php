<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Cek apakah tabel users ada
    $stmt = $db->prepare("SHOW TABLES LIKE 'users'");
    $stmt->execute();
    
    if ($stmt->rowCount() === 0) {
        // Tabel tidak ada, buat tabel baru
        $createTable = "
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin', 'dosen', 'mahasiswa') NOT NULL DEFAULT 'mahasiswa',
            status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
            full_name VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $db->exec($createTable);
        
        // Insert sample data
        $insertData = "
        INSERT INTO users (username, email, password, role, status, full_name, phone) VALUES
        ('admin', 'admin@siom.com', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'admin', 'aktif', 'Administrator', '081234567890'),
        ('dosen001', 'dosen001@siom.com', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'dosen', 'aktif', 'Dr. Ahmad Suryadi', '081234567891'),
        ('mahasiswa001', 'mahasiswa001@siom.com', '" . password_hash('password', PASSWORD_DEFAULT) . "', 'mahasiswa', 'aktif', 'Budi Santoso', '081234567892')
        ";
        
        $db->exec($insertData);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Tabel users berhasil dibuat dengan data sample'
        ]);
    } else {
        // Tabel ada, cek struktur kolom
        $stmt = $db->prepare("DESCRIBE users");
        $stmt->execute();
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $columnNames = array_column($columns, 'Field');
        
        // Cek apakah kolom full_name ada
        if (!in_array('full_name', $columnNames)) {
            // Tambah kolom full_name
            $db->exec("ALTER TABLE users ADD COLUMN full_name VARCHAR(100) NOT NULL DEFAULT '' AFTER status");
            echo json_encode([
                'status' => 'success',
                'message' => 'Kolom full_name berhasil ditambahkan'
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'message' => 'Tabel users sudah ada dan struktur sudah benar',
                'columns' => $columnNames
            ]);
        }
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 