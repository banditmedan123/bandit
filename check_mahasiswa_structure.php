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

echo "\n=== SAMPLE DATA MAHASISWA ===\n";
try {
    $stmt = $db->query("SELECT * FROM mahasiswa LIMIT 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($data) {
        echo "Sample data:\n";
        foreach ($data as $key => $value) {
            echo "$key: $value\n";
        }
    } else {
        echo "Tidak ada data mahasiswa\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 