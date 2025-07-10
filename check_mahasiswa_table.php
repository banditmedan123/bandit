<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "=== STRUKTUR TABEL MAHASISWA ===\n";
try {
    $stmt = $db->query("DESCRIBE mahasiswa");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo $column['Field'] . " - " . $column['Type'] . " - " . $column['Null'] . " - " . $column['Key'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== ISI TABEL MAHASISWA ===\n";
try {
    $stmt = $db->query("SELECT * FROM mahasiswa LIMIT 5");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($data) > 0) {
        foreach ($data as $row) {
            echo "ID: " . $row['id'] . "\n";
            echo "NIM: " . $row['nim'] . "\n";
            echo "Nama: " . $row['nama_lengkap'] . "\n";
            echo "Email: " . $row['email'] . "\n";
            echo "---\n";
        }
    } else {
        echo "Tabel mahasiswa kosong\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== TOTAL DATA MAHASISWA ===\n";
try {
    $stmt = $db->query("SELECT COUNT(*) as total FROM mahasiswa");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total data: " . $count['total'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 