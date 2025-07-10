<?php
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

try {
    // Data mahasiswa contoh
    $mahasiswaData = [
        [
            'nim' => '2024001',
            'nama_lengkap' => 'Ahmad Fauzi',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-05-15',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'no_hp' => '081234567890',
            'email' => 'ahmad.fauzi@email.com',
            'fakultas' => 'Teknik',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => 2024,
            'status' => 'aktif',
            'ipk' => 3.75,
            'total_sks' => 24
        ],
        [
            'nim' => '2024002',
            'nama_lengkap' => 'Siti Nurhaliza',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2001-03-20',
            'jenis_kelamin' => 'P',
            'agama' => 'Islam',
            'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
            'no_hp' => '081234567891',
            'email' => 'siti.nurhaliza@email.com',
            'fakultas' => 'Ekonomi',
            'program_studi' => 'Manajemen',
            'angkatan' => 2024,
            'status' => 'aktif',
            'ipk' => 3.80,
            'total_sks' => 20
        ],
        [
            'nim' => '2024003',
            'nama_lengkap' => 'Budi Santoso',
            'tempat_lahir' => 'Surabaya',
            'tanggal_lahir' => '2000-08-10',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'alamat' => 'Jl. Pemuda No. 67, Surabaya',
            'no_hp' => '081234567892',
            'email' => 'budi.santoso@email.com',
            'fakultas' => 'Hukum',
            'program_studi' => 'Ilmu Hukum',
            'angkatan' => 2024,
            'status' => 'aktif',
            'ipk' => 3.65,
            'total_sks' => 18
        ]
    ];
    
    $insertQuery = "INSERT IGNORE INTO mahasiswa (nim, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, no_hp, email, fakultas, program_studi, angkatan, status, ipk, total_sks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $db->prepare($insertQuery);
    
    $added = 0;
    foreach ($mahasiswaData as $data) {
        try {
            $result = $insertStmt->execute([
                $data['nim'],
                $data['nama_lengkap'],
                $data['tempat_lahir'],
                $data['tanggal_lahir'],
                $data['jenis_kelamin'],
                $data['agama'],
                $data['alamat'],
                $data['no_hp'],
                $data['email'],
                $data['fakultas'],
                $data['program_studi'],
                $data['angkatan'],
                $data['status'],
                $data['ipk'],
                $data['total_sks']
            ]);
            
            if ($result) {
                $added++;
                echo "Berhasil menambahkan mahasiswa: " . $data['nama_lengkap'] . " (NIM: " . $data['nim'] . ")\n";
            }
        } catch (PDOException $e) {
            echo "Gagal menambahkan mahasiswa " . $data['nama_lengkap'] . ": " . $e->getMessage() . "\n";
        }
    }
    
    echo "\nTotal berhasil ditambahkan: $added mahasiswa\n";
    
    // Tampilkan data mahasiswa yang ada
    echo "\n=== DATA MAHASISWA YANG ADA ===\n";
    $stmt = $db->query("SELECT nim, nama_lengkap, fakultas, program_studi FROM mahasiswa ORDER BY nim");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($data as $row) {
        echo "NIM: " . $row['nim'] . " - " . $row['nama_lengkap'] . " (" . $row['fakultas'] . " - " . $row['program_studi'] . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?> 