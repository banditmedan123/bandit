<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Cek apakah mahasiswa dengan NIM 2024001 sudah ada
    $stmt = $db->prepare("SELECT id FROM mahasiswa WHERE nim = ?");
    $stmt->execute(['2024001']);
    $existing = $stmt->fetch();
    
    if ($existing) {
        echo "Mahasiswa dengan NIM 2024001 sudah ada di database.\n";
    } else {
        // Tambah data mahasiswa baru
        $insertQuery = "INSERT INTO mahasiswa (nim, nama_lengkap, tempat_lahir, alamat, no_hp, email, jenis_kelamin, tanggal_lahir, id_fakultas, id_prodi, angkatan, semester, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $db->prepare($insertQuery);
        
        $result = $insertStmt->execute([
            '2024001',
            'Ahmad Fauzi',
            'Jakarta',
            'Jl. Sudirman No. 123, Jakarta Pusat',
            '081234567890',
            'ahmad.fauzi@email.com',
            'Laki-laki',
            '2000-05-15',
            1, // id_fakultas
            1, // id_prodi
            '2024',
            '1',
            'Aktif'
        ]);
        
        if ($result) {
            echo "Berhasil menambahkan mahasiswa dengan NIM 2024001\n";
            echo "Nama: Ahmad Fauzi\n";
            echo "Email: ahmad.fauzi@email.com\n";
            echo "Fakultas ID: 1\n";
            echo "Prodi ID: 1\n";
        } else {
            echo "Gagal menambahkan mahasiswa\n";
        }
    }
    
    // Tampilkan data mahasiswa yang baru ditambahkan
    echo "\n=== DATA MAHASISWA 2024001 ===\n";
    $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE nim = ?");
    $stmt->execute(['2024001']);
    $mahasiswa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($mahasiswa) {
        foreach ($mahasiswa as $key => $value) {
            echo "$key: $value\n";
        }
    } else {
        echo "Data mahasiswa tidak ditemukan\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 