<?php
// Test script untuk API profil user
require_once 'config/database.php';

echo "=== Test API Profil User ===\n\n";

// Simulasi session login
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = 1; // ID user riyan
$_SESSION['username'] = '234234'; // Username riyan
$_SESSION['role'] = 'mahasiswa';
$_SESSION['full_name'] = 'riyan';
$_SESSION['email'] = 'riyan@example.com';

echo "Session data:\n";
echo "User ID: " . $_SESSION['user_id'] . "\n";
echo "Username: " . $_SESSION['username'] . "\n";
echo "Role: " . $_SESSION['role'] . "\n";
echo "Full Name: " . $_SESSION['full_name'] . "\n\n";

// Test API get_user_profile.php
echo "Testing API get_user_profile.php...\n";
$url = 'http://localhost:8000/api/get_user_profile.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, 'PHPSESSID=' . session_id());
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test API get_mahasiswa_by_username.php
echo "Testing API get_mahasiswa_by_username.php...\n";
$url2 = 'http://localhost:8000/api/get_mahasiswa_by_username.php';

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_COOKIE, 'PHPSESSID=' . session_id());
$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

echo "HTTP Code: $httpCode2\n";
echo "Response: $response2\n\n";

// Cek log files
echo "=== Checking Log Files ===\n";
if (file_exists('logs/api_debug.log')) {
    echo "API Debug Log:\n";
    echo file_get_contents('logs/api_debug.log');
} else {
    echo "API Debug Log not found\n";
}

if (file_exists('logs/profile_debug.log')) {
    echo "\nProfile Debug Log:\n";
    echo file_get_contents('logs/profile_debug.log');
} else {
    echo "Profile Debug Log not found\n";
}
?> 