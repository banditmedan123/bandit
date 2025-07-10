<?php
// Script untuk membuat data sample mahasiswa
header('Content-Type: text/html; charset=utf-8');

echo "<h1>Create Sample Mahasiswa Data</h1>";

try {
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        echo "<p style='color: red;'>❌ Database connection failed!</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ Database connected successfully!</p>";
    
    // 1. Buat data fakultas
    echo "<h2>1. Creating Fakultas Data</h2>";
    $fakultas_data = [
        ['nama_fakultas' => 'Fakultas Teknik', 'kode_fakultas' => 'FT'],
        ['nama_fakultas' => 'Fakultas Ekonomi', 'kode_fakultas' => 'FE'],
        ['nama_fakultas' => 'Fakultas Hukum', 'kode_fakultas' => 'FH']
    ];
    
    foreach ($fakultas_data as $fakultas) {
        $stmt = $db->prepare("INSERT INTO fakultas (nama_fakultas, kode_fakultas) VALUES (?, ?)");
        $result = $stmt->execute([$fakultas['nama_fakultas'], $fakultas['kode_fakultas']]);
        if ($result) {
            echo "<p style='color: green;'>✅ Created fakultas: " . $fakultas['nama_fakultas'] . "</p>";
        }
    }
    
    // 2. Buat data prodi
    echo "<h2>2. Creating Prodi Data</h2>";
    $prodi_data = [
        ['nama_prodi' => 'Informatika', 'kode_prodi' => 'IF', 'id_fakultas' => 1],
        ['nama_prodi' => 'Teknik Elektro', 'kode_prodi' => 'TE', 'id_fakultas' => 1],
        ['nama_prodi' => 'Manajemen', 'kode_prodi' => 'MNJ', 'id_fakultas' => 2],
        ['nama_prodi' => 'Akuntansi', 'kode_prodi' => 'AKT', 'id_fakultas' => 2]
    ];
    
    foreach ($prodi_data as $prodi) {
        $stmt = $db->prepare("INSERT INTO prodi (nama_prodi, kode_prodi, id_fakultas) VALUES (?, ?, ?)");
        $result = $stmt->execute([$prodi['nama_prodi'], $prodi['kode_prodi'], $prodi['id_fakultas']]);
        if ($result) {
            echo "<p style='color: green;'>✅ Created prodi: " . $prodi['nama_prodi'] . "</p>";
        }
    }
    
    // 3. Buat data mahasiswa
    echo "<h2>3. Creating Mahasiswa Data</h2>";
    $mahasiswa_data = [
        [
            'nim' => 'mahasiswa001',
            'nama_lengkap' => 'Jane Smith',
            'email' => 'mahasiswa001@siom.com',
            'angkatan' => '2024',
            'status' => 'aktif',
            'id_fakultas' => 1,
            'id_prodi' => 1
        ],
        [
            'nim' => 'mahasiswa002',
            'nama_lengkap' => 'Mike Wilson',
            'email' => 'mahasiswa002@siom.com',
            'angkatan' => '2024',
            'status' => 'aktif',
            'id_fakultas' => 1,
            'id_prodi' => 1
        ]
    ];
    
    foreach ($mahasiswa_data as $mhs) {
        $stmt = $db->prepare("INSERT INTO mahasiswa (nim, nama_lengkap, email, angkatan, status, id_fakultas, id_prodi) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $mhs['nim'],
            $mhs['nama_lengkap'],
            $mhs['email'],
            $mhs['angkatan'],
            $mhs['status'],
            $mhs['id_fakultas'],
            $mhs['id_prodi']
        ]);
        if ($result) {
            echo "<p style='color: green;'>✅ Created mahasiswa: " . $mhs['nama_lengkap'] . " (NIM: " . $mhs['nim'] . ")</p>";
        }
    }
    
    // 4. Buat data dosen
    echo "<h2>4. Creating Dosen Data</h2>";
    $dosen_data = [
        ['nama_dosen' => 'Dr. John Doe', 'nip' => '198001011234567890', 'email' => 'john.doe@siom.com'],
        ['nama_dosen' => 'Dr. Sarah Johnson', 'nip' => '198502021234567890', 'email' => 'sarah.johnson@siom.com'],
        ['nama_dosen' => 'Dr. Rizki Pratama', 'nip' => '199003031234567890', 'email' => 'rizki.pratama@siom.com']
    ];
    
    foreach ($dosen_data as $dosen) {
        $stmt = $db->prepare("INSERT INTO dosen (nama_dosen, nip, email) VALUES (?, ?, ?)");
        $result = $stmt->execute([$dosen['nama_dosen'], $dosen['nip'], $dosen['email']]);
        if ($result) {
            echo "<p style='color: green;'>✅ Created dosen: " . $dosen['nama_dosen'] . "</p>";
        }
    }
    
    // 5. Buat data matakuliah
    echo "<h2>5. Creating Matakuliah Data</h2>";
    $matakuliah_data = [
        ['kode_matakuliah' => 'IF101', 'nama_matakuliah' => 'Pemrograman Web', 'sks' => 3, 'semester' => 1],
        ['kode_matakuliah' => 'IF102', 'nama_matakuliah' => 'Basis Data', 'sks' => 3, 'semester' => 1],
        ['kode_matakuliah' => 'IF103', 'nama_matakuliah' => 'Algoritma & Struktur Data', 'sks' => 4, 'semester' => 1]
    ];
    
    foreach ($matakuliah_data as $mk) {
        $stmt = $db->prepare("INSERT INTO matakuliah (kode_matakuliah, nama_matakuliah, sks, semester) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$mk['kode_matakuliah'], $mk['nama_matakuliah'], $mk['sks'], $mk['semester']]);
        if ($result) {
            echo "<p style='color: green;'>✅ Created matakuliah: " . $mk['nama_matakuliah'] . "</p>";
        }
    }
    
    // 6. Buat data ruangan
    echo "<h2>6. Creating Ruangan Data</h2>";
    $ruangan_data = [
        ['nama_ruangan' => 'Lab 1', 'kapasitas' => 30],
        ['nama_ruangan' => 'Ruang 201', 'kapasitas' => 40],
        ['nama_ruangan' => 'Ruang 301', 'kapasitas' => 35]
    ];
    
    foreach ($ruangan_data as $ruangan) {
        $stmt = $db->prepare("INSERT INTO ruangan (nama_ruangan, kapasitas) VALUES (?, ?)");
        $result = $stmt->execute([$ruangan['nama_ruangan'], $ruangan['kapasitas']]);
        if ($result) {
            echo "<p style='color: green;'>✅ Created ruangan: " . $ruangan['nama_ruangan'] . "</p>";
        }
    }
    
    // 7. Buat data KRS
    echo "<h2>7. Creating KRS Data</h2>";
    $krs_data = [
        ['nim' => 'mahasiswa001', 'id_matakuliah' => 1, 'semester' => '2024/2025-1'],
        ['nim' => 'mahasiswa001', 'id_matakuliah' => 2, 'semester' => '2024/2025-1'],
        ['nim' => 'mahasiswa001', 'id_matakuliah' => 3, 'semester' => '2024/2025-1']
    ];
    
    foreach ($krs_data as $krs) {
        $stmt = $db->prepare("INSERT INTO krs (nim, id_matakuliah, semester) VALUES (?, ?, ?)");
        $result = $stmt->execute([$krs['nim'], $krs['id_matakuliah'], $krs['semester']]);
        if ($result) {
            echo "<p style='color: green;'>✅ Created KRS for mahasiswa001</p>";
        }
    }
    
    // 8. Buat data jadwal
    echo "<h2>8. Creating Jadwal Data</h2>";
    $jadwal_data = [
        ['nim' => 'mahasiswa001', 'id_matakuliah' => 1, 'id_dosen' => 1, 'id_ruangan' => 1, 'hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '10:30'],
        ['nim' => 'mahasiswa001', 'id_matakuliah' => 2, 'id_dosen' => 2, 'id_ruangan' => 2, 'hari' => 'Senin', 'jam_mulai' => '10:30', 'jam_selesai' => '13:00'],
        ['nim' => 'mahasiswa001', 'id_matakuliah' => 3, 'id_dosen' => 3, 'id_ruangan' => 3, 'hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '16:30']
    ];
    
    foreach ($jadwal_data as $jadwal) {
        $stmt = $db->prepare("INSERT INTO jadwal (nim, id_matakuliah, id_dosen, id_ruangan, hari, jam_mulai, jam_selesai) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([
            $jadwal['nim'],
            $jadwal['id_matakuliah'],
            $jadwal['id_dosen'],
            $jadwal['id_ruangan'],
            $jadwal['hari'],
            $jadwal['jam_mulai'],
            $jadwal['jam_selesai']
        ]);
        if ($result) {
            echo "<p style='color: green;'>✅ Created jadwal for mahasiswa001</p>";
        }
    }
    
    // 9. Buat data keuangan
    echo "<h2>9. Creating Keuangan Data</h2>";
    $keuangan_data = [
        ['nim' => 'mahasiswa001', 'jenis_tagihan' => 'SPP Semester 1', 'jumlah_tagihan' => 5000000, 'status_pembayaran' => 'lunas'],
        ['nim' => 'mahasiswa001', 'jenis_tagihan' => 'SPP Semester 2', 'jumlah_tagihan' => 5000000, 'status_pembayaran' => 'belum_lunas']
    ];
    
    foreach ($keuangan_data as $keuangan) {
        $stmt = $db->prepare("INSERT INTO keuangan (nim, jenis_tagihan, jumlah_tagihan, status_pembayaran) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([
            $keuangan['nim'],
            $keuangan['jenis_tagihan'],
            $keuangan['jumlah_tagihan'],
            $keuangan['status_pembayaran']
        ]);
        if ($result) {
            echo "<p style='color: green;'>✅ Created keuangan data for mahasiswa001</p>";
        }
    }
    
    echo "<h2>✅ Sample Data Created Successfully!</h2>";
    echo "<p>Now you can login with:</p>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> mahasiswa001</li>";
    echo "<li><strong>Password:</strong> password</li>";
    echo "</ul>";
    echo "<p><a href='login.html' target='_blank'>Go to Login Page</a></p>";
    echo "<p><a href='pages/mahasiswa-dashboard.html' target='_blank'>Go to Mahasiswa Dashboard</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?> 