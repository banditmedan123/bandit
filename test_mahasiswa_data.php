<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "=== CEK DATA MAHASISWA ===\n";
try {
    $stmt = $db->query("SELECT * FROM mahasiswa WHERE nim = '2024001'");
    $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($mahasiswa) {
        echo "Data mahasiswa ditemukan:\n";
        foreach ($mahasiswa as $key => $value) {
            echo "$key: $value\n";
        }
    } else {
        echo "Data mahasiswa dengan NIM 2024001 tidak ditemukan\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST API GET_MAHASISWA_BY_USERNAME.PHP ===\n";
try {
    // Simulate API call with NIM parameter
    $_GET['nim'] = '2024001';
    
    ob_start();
    include 'api/get_mahasiswa_by_username.php';
    $output = ob_get_clean();
    
    echo "API Response:\n";
    echo $output . "\n";
    
    // Parse JSON response
    $response = json_decode($output, true);
    if ($response) {
        echo "Parsed Response:\n";
        print_r($response);
    } else {
        echo "Failed to parse JSON response\n";
    }
} catch (Exception $e) {
    echo "Error testing API: " . $e->getMessage() . "\n";
}
?> 