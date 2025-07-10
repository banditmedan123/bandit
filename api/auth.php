<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Auth {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Login user
    public function login($username, $password) {
        try {
            // Query untuk mencari user berdasarkan username
            $query = "SELECT id, username, password, role, full_name, email FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verifikasi password dengan password_verify
                if(password_verify($password, $row['password'])) {
                    // Password benar, buat session
                    session_start();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['full_name'] = $row['full_name'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['logged_in'] = true;
                    $_SESSION['login_time'] = time();

                    // Jika user adalah mahasiswa, simpan NIM (username) untuk akses data mahasiswa
                    if($row['role'] === 'mahasiswa') {
                        $_SESSION['nim'] = $row['username']; // NIM = username untuk mahasiswa
                    }

                    // Set session cookie untuk konsistensi
                    session_write_close();
                    session_start();

                    return array(
                        "success" => true,
                        "message" => "Login berhasil!",
                        "user" => array(
                            "id" => $row['id'],
                            "username" => $row['username'],
                            "role" => $row['role'],
                            "full_name" => $row['full_name'],
                            "email" => $row['email'],
                            "nim" => $row['role'] === 'mahasiswa' ? $row['username'] : null
                        )
                    );
                } else {
                    return array(
                        "success" => false,
                        "message" => "Password salah!"
                    );
                }
            } else {
                return array(
                    "success" => false,
                    "message" => "Username tidak ditemukan!"
                );
            }
        } catch(PDOException $e) {
            return array(
                "success" => false,
                "message" => "Error: " . $e->getMessage()
            );
        }
    }

    // Logout user
    public function logout() {
        session_start();
        session_destroy();
        
        // Hapus session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        return array(
            "success" => true,
            "message" => "Logout berhasil!"
        );
    }

    // Cek status login
    public function checkLogin() {
        session_start();
        
        // Cek apakah session ada dan valid
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && 
           isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
            
            // Cek apakah session tidak expired (24 jam)
            if(isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) < 86400) {
                return array(
                    "success" => true,
                    "user" => array(
                        "id" => $_SESSION['user_id'],
                        "username" => $_SESSION['username'],
                        "role" => $_SESSION['role'],
                        "full_name" => $_SESSION['full_name'],
                        "email" => $_SESSION['email'],
                        "nim" => isset($_SESSION['nim']) ? $_SESSION['nim'] : null
                    )
                );
            } else {
                // Session expired, logout
                session_destroy();
                return array(
                    "success" => false,
                    "message" => "Session expired!"
                );
            }
        } else {
            return array(
                "success" => false,
                "message" => "User tidak login!"
            );
        }
    }
}

// Handle POST request untuk login
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $auth = new Auth($db);

    // Ambil data dari request
    $data = json_decode(file_get_contents("php://input"), true);
    
    if(isset($data['action'])) {
        switch($data['action']) {
            case 'login':
                if(isset($data['username']) && isset($data['password'])) {
                    $result = $auth->login($data['username'], $data['password']);
                    echo json_encode($result);
                } else {
                    echo json_encode(array(
                        "success" => false,
                        "message" => "Username dan password diperlukan!"
                    ));
                }
                break;
                
            case 'logout':
                $result = $auth->logout();
                echo json_encode($result);
                break;
                
            case 'check':
                $result = $auth->checkLogin();
                echo json_encode($result);
                break;
                
            default:
                echo json_encode(array(
                    "success" => false,
                    "message" => "Action tidak valid!"
                ));
        }
    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "Action diperlukan!"
        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Method tidak diizinkan!"
    ));
}
?> 