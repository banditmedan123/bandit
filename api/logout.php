<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Logout {
    public function logout() {
        session_start();
        session_destroy();
        
        return array(
            "success" => true,
            "message" => "Logout berhasil!"
        );
    }
}

// Handle POST request untuk logout
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logout = new Logout();
    $result = $logout->logout();
    echo json_encode($result);
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Method tidak diizinkan!"
    ));
}
?> 