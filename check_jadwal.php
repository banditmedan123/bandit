<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Cek struktur tabel jadwal
    echo "=== STRUKTUR TABEL JADWAL ===\n";
    $stmt = $db->query("DESCRIBE jadwal");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . " - " . $column['Null'] . " - " . $column['Key'] . "\n";
    }
    
    echo "\n=== ISI TABEL JADWAL ===\n";
    $stmt = $db->query("SELECT * FROM jadwal");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($data) > 0) {
        foreach ($data as $row) {
            echo "ID: " . $row['id'] . "\n";
            echo "NIM: " . $row['nim'] . "\n";
            echo "Mata Kuliah ID: " . $row['mata_kuliah_id'] . "\n";
            echo "Dosen ID: " . $row['dosen_id'] . "\n";
            echo "Ruangan ID: " . $row['ruangan_id'] . "\n";
            echo "Hari: " . $row['hari'] . "\n";
            echo "Jam Mulai: " . $row['jam_mulai'] . "\n";
            echo "Jam Selesai: " . $row['jam_selesai'] . "\n";
            echo "Semester: " . $row['semester'] . "\n";
            echo "Tahun Akademik: " . $row['tahun_akademik'] . "\n";
            echo "Created At: " . $row['created_at'] . "\n";
            echo "---\n";
        }
    } else {
        echo "Tabel jadwal kosong\n";
    }
    
    echo "\n=== TOTAL DATA ===\n";
    $stmt = $db->query("SELECT COUNT(*) as total FROM jadwal");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total data: " . $count['total'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 