<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Cek struktur tabel khs
    echo "=== STRUKTUR TABEL KHS ===\n";
    $stmt = $db->query("DESCRIBE khs");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . " - " . $column['Null'] . " - " . $column['Key'] . "\n";
    }
    
    echo "\n=== ISI TABEL KHS ===\n";
    $stmt = $db->query("SELECT * FROM khs");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($data) > 0) {
        foreach ($data as $row) {
            echo "ID: " . $row['id'] . "\n";
            echo "NIM: " . $row['nim'] . "\n";
            echo "Mata Kuliah ID: " . $row['mata_kuliah_id'] . "\n";
            echo "Semester: " . $row['semester'] . "\n";
            echo "Tahun Akademik: " . $row['tahun_akademik'] . "\n";
            echo "Nilai: " . $row['nilai'] . "\n";
            echo "Grade: " . $row['grade'] . "\n";
            echo "Created At: " . $row['created_at'] . "\n";
            echo "---\n";
        }
    } else {
        echo "Tabel khs kosong\n";
    }
    
    echo "\n=== TOTAL DATA ===\n";
    $stmt = $db->query("SELECT COUNT(*) as total FROM khs");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total data: " . $count['total'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 