<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getUsers();
        break;
    case 'POST':
        addUser();
        break;
    case 'PUT':
        updateUser();
        break;
    case 'DELETE':
        deleteUser();
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

function getUsers() {
    global $db;
    
    try {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        
        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row;
        }
        
        echo json_encode([
            'status' => 'success',
            'data' => $users
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

function addUser() {
    global $db;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Debug: log received data
    error_log("Received data: " . json_encode($data));
    
    if (!$data) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid JSON data'
        ]);
        return;
    }
    
    // Validate required fields
    $required_fields = ['username', 'email', 'password', 'role', 'full_name'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => "Field '$field' is required"
            ]);
            return;
        }
    }
    
    // Validate field lengths
    if (strlen($data['username']) > 50) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Username maksimal 50 karakter'
        ]);
        return;
    }
    
    if (strlen($data['email']) > 100) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Email maksimal 100 karakter'
        ]);
        return;
    }
    
    if (strlen($data['full_name']) > 100) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Nama lengkap maksimal 100 karakter'
        ]);
        return;
    }
    
    // Validate phone (optional field)
    if (isset($data['phone']) && !empty($data['phone']) && strlen($data['phone']) > 20) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Nomor telepon maksimal 20 karakter'
        ]);
        return;
    }
    
    // Validate role
    $valid_roles = ['admin', 'dosen', 'mahasiswa'];
    if (!in_array($data['role'], $valid_roles)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Role tidak valid'
        ]);
        return;
    }
    
    // Validate status (optional field)
    if (isset($data['status'])) {
        $valid_statuses = ['aktif', 'nonaktif'];
        if (!in_array($data['status'], $valid_statuses)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Status tidak valid'
            ]);
            return;
        }
    }
    
    // Check if username already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$data['username']]);
    
    if ($stmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Username already exists'
        ]);
        return;
    }
    
    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$data['email']]);
    
    if ($stmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Email already exists'
        ]);
        return;
    }
    
    // Hash password
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    try {
        // Clean and validate data
        $username = trim($data['username']);
        $email = trim($data['email']);
        $role = trim($data['role']);
        $full_name = trim($data['full_name']);
        
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password, role, full_name, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        
        $result = $stmt->execute([
            $username,
            $email,
            $hashed_password,
            $role,
            $full_name
        ]);
        
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'User added successfully',
                'id' => $db->lastInsertId()
            ]);
        } else {
            throw new Exception("Failed to add user");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

function updateUser() {
    global $db;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['id'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'User ID is required'
        ]);
        return;
    }
    
    $user_id = $data['id'];
    
    // Check if user exists
    $stmt = $db->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'User not found'
        ]);
        return;
    }
    
    // Check if username already exists (excluding current user)
    if (isset($data['username'])) {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$data['username'], $user_id]);
        
        if ($stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Username already exists'
            ]);
            return;
        }
    }
    
    // Check if email already exists (excluding current user)
    if (isset($data['email'])) {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$data['email'], $user_id]);
        
        if ($stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Email already exists'
            ]);
            return;
        }
    }
    
    try {
        $update_fields = [];
        $values = [];
        
        if (isset($data['username'])) {
            $update_fields[] = "username = ?";
            $values[] = $data['username'];
        }
        
        if (isset($data['email'])) {
            $update_fields[] = "email = ?";
            $values[] = $data['email'];
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $update_fields[] = "password = ?";
            $values[] = $hashed_password;
        }
        
        if (isset($data['role'])) {
            $update_fields[] = "role = ?";
            $values[] = $data['role'];
        }
        
        // Note: status field is not available in current table structure
        // if (isset($data['status'])) {
        //     $update_fields[] = "status = ?";
        //     $values[] = $data['status'];
        // }
        
        if (isset($data['full_name'])) {
            $update_fields[] = "full_name = ?";
            $values[] = $data['full_name'];
        }
        
        // Note: phone field is not available in current table structure
        // if (isset($data['phone'])) {
        //     $update_fields[] = "phone = ?";
        //     $values[] = $data['phone'];
        // }
        
        if (empty($update_fields)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'No fields to update'
            ]);
            return;
        }
        
        $update_fields[] = "updated_at = NOW()";
        $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = ?";
        $values[] = $user_id;
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute($values);
        
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'User updated successfully'
            ]);
        } else {
            throw new Exception("Failed to update user");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}

function deleteUser() {
    global $db;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data || !isset($data['id'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'User ID is required'
        ]);
        return;
    }
    
    $user_id = $data['id'];
    
    // Check if user exists
    $stmt = $db->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    
    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'User not found'
        ]);
        return;
    }
    
    try {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $result = $stmt->execute([$user_id]);
        
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        } else {
            throw new Exception("Failed to delete user");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
}
?> 