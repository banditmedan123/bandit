<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

// Log untuk debugging
error_log("API Mahasiswa CRUD - Method: " . $method);

try {
    switch($method) {
        case 'GET':
            // Ambil semua data mahasiswa
            $query = "SELECT id, nim, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, no_hp, email, fakultas, program_studi, angkatan, status, ipk, total_sks, created_at, updated_at FROM mahasiswa ORDER BY nama_lengkap";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            $data = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            
            error_log("GET - Found " . count($data) . " mahasiswa");
            
            echo json_encode([
                "status" => "success",
                "data" => $data,
                "count" => count($data)
            ]);
            break;
            
        case 'POST':
            // Tambah data mahasiswa baru
            $input = json_decode(file_get_contents("php://input"), true);
            
            error_log("POST - Received data: " . json_encode($input));
            
            // Validasi input wajib
            if (!isset($input['nim']) || !isset($input['nama_lengkap']) || !isset($input['fakultas']) || 
                !isset($input['program_studi']) || !isset($input['angkatan']) || !isset($input['status']) || !isset($input['email'])) {
                throw new Exception("Semua field wajib harus diisi");
            }
            
            // Cek apakah NIM sudah ada
            $check_query = "SELECT id FROM mahasiswa WHERE nim = ?";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->execute([$input['nim']]);
            
            if ($check_stmt->rowCount() > 0) {
                throw new Exception("NIM sudah terdaftar");
            }
            
            // Cek apakah email sudah ada
            $check_email_query = "SELECT id FROM mahasiswa WHERE email = ?";
            $check_email_stmt = $db->prepare($check_email_query);
            $check_email_stmt->execute([$input['email']]);
            
            if ($check_email_stmt->rowCount() > 0) {
                throw new Exception("Email sudah terdaftar");
            }
            
            // Siapkan data untuk insert
            $insert_data = [
                $input['nim'],
                $input['nama_lengkap'],
                $input['fakultas'],
                $input['program_studi'],
                $input['angkatan'],
                $input['status'],
                $input['email']
            ];
            
            // Tambahkan field opsional jika ada
            $optional_fields = ['tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat', 'no_hp'];
            $field_names = ['nim', 'nama_lengkap', 'fakultas', 'program_studi', 'angkatan', 'status', 'email'];
            
            foreach ($optional_fields as $field) {
                if (isset($input[$field]) && !empty($input[$field])) {
                    $field_names[] = $field;
                    $insert_data[] = $input[$field];
                }
            }
            
            $query = "INSERT INTO mahasiswa (" . implode(', ', $field_names) . ") VALUES (" . str_repeat('?,', count($field_names) - 1) . "?)";
            error_log("POST - Query: " . $query);
            error_log("POST - Data: " . json_encode($insert_data));
            
            $stmt = $db->prepare($query);
            $result = $stmt->execute($insert_data);
            
            if ($result) {
                $id = $db->lastInsertId();
                error_log("POST - Success, ID: " . $id);
                echo json_encode([
                    "status" => "success",
                    "message" => "Data mahasiswa berhasil ditambahkan",
                    "id" => $id
                ]);
            } else {
                throw new Exception("Gagal menambahkan data mahasiswa");
            }
            break;
            
        case 'PUT':
            // Update data mahasiswa
            $input = json_decode(file_get_contents("php://input"), true);
            
            if (!isset($input['id']) || !isset($input['nim']) || !isset($input['nama_lengkap']) || 
                !isset($input['fakultas']) || !isset($input['program_studi']) || !isset($input['angkatan']) || 
                !isset($input['status']) || !isset($input['email'])) {
                throw new Exception("Semua field wajib harus diisi");
            }
            
            // Cek apakah NIM sudah ada (kecuali untuk mahasiswa yang sedang diedit)
            $check_query = "SELECT id FROM mahasiswa WHERE nim = ? AND id != ?";
            $check_stmt = $db->prepare($check_query);
            $check_stmt->execute([$input['nim'], $input['id']]);
            
            if ($check_stmt->rowCount() > 0) {
                throw new Exception("NIM sudah terdaftar");
            }
            
            // Cek apakah email sudah ada (kecuali untuk mahasiswa yang sedang diedit)
            $check_email_query = "SELECT id FROM mahasiswa WHERE email = ? AND id != ?";
            $check_email_stmt = $db->prepare($check_email_query);
            $check_email_stmt->execute([$input['email'], $input['id']]);
            
            if ($check_email_stmt->rowCount() > 0) {
                throw new Exception("Email sudah terdaftar");
            }
            
            // Siapkan data untuk update
            $update_fields = [
                'nim = ?',
                'nama_lengkap = ?',
                'fakultas = ?',
                'program_studi = ?',
                'angkatan = ?',
                'status = ?',
                'email = ?'
            ];
            $update_data = [
                $input['nim'],
                $input['nama_lengkap'],
                $input['fakultas'],
                $input['program_studi'],
                $input['angkatan'],
                $input['status'],
                $input['email']
            ];
            
            // Tambahkan field opsional jika ada
            $optional_fields = ['tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat', 'no_hp'];
            
            foreach ($optional_fields as $field) {
                if (isset($input[$field])) {
                    $update_fields[] = $field . ' = ?';
                    $update_data[] = $input[$field];
                }
            }
            
            $update_data[] = $input['id']; // ID untuk WHERE clause
            
            $query = "UPDATE mahasiswa SET " . implode(', ', $update_fields) . " WHERE id = ?";
            $stmt = $db->prepare($query);
            $result = $stmt->execute($update_data);
            
            if ($result) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Data mahasiswa berhasil diupdate"
                ]);
            } else {
                throw new Exception("Gagal mengupdate data mahasiswa");
            }
            break;
            
        case 'DELETE':
            // Hapus data mahasiswa
            $input = json_decode(file_get_contents("php://input"), true);
            
            if (!isset($input['id'])) {
                throw new Exception("ID mahasiswa harus disediakan");
            }
            
            $query = "DELETE FROM mahasiswa WHERE id = ?";
            $stmt = $db->prepare($query);
            $result = $stmt->execute([$input['id']]);
            
            if ($result) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Data mahasiswa berhasil dihapus"
                ]);
            } else {
                throw new Exception("Gagal menghapus data mahasiswa");
            }
            break;
            
        default:
            throw new Exception("Method tidak diizinkan");
    }
    
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?> 