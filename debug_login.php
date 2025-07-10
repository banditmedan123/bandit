<?php
// Debug script untuk mengecek masalah login
header('Content-Type: text/html; charset=utf-8');

echo "<h1>Debug Login SIOM</h1>";

// 1. Cek koneksi database
echo "<h2>1. Testing Database Connection</h2>";
try {
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        echo "<p style='color: green;'>✅ Database connection successful!</p>";
    } else {
        echo "<p style='color: red;'>❌ Database connection failed!</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

// 2. Cek tabel users
echo "<h2>2. Checking Users Table</h2>";
try {
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Table 'users' exists</p>";
        
        // Cek struktur tabel
        $stmt = $db->query("DESCRIBE users");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<p>Columns in users table: " . implode(', ', $columns) . "</p>";
        
        // Cek data user
        $stmt = $db->query("SELECT id, username, role, full_name, email FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($users) > 0) {
            echo "<p style='color: green;'>✅ Found " . count($users) . " users in database</p>";
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Full Name</th><th>Email</th></tr>";
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['username'] . "</td>";
                echo "<td>" . $user['role'] . "</td>";
                echo "<td>" . $user['full_name'] . "</td>";
                echo "<td>" . $user['email'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>❌ No users found in database</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Table 'users' does not exist</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error checking users table: " . $e->getMessage() . "</p>";
}

// 3. Test login dengan admin
echo "<h2>3. Testing Login with Admin</h2>";
try {
    $username = 'admin';
    $password = 'password';
    
    // Cek apakah user admin ada
    $stmt = $db->prepare("SELECT id, username, password, role, full_name, email FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<p style='color: green;'>✅ User 'admin' found in database</p>";
        echo "<p>User data: " . json_encode($user, JSON_PRETTY_PRINT) . "</p>";
        
        // Test password verification
        if (password_verify($password, $user['password'])) {
            echo "<p style='color: green;'>✅ Password verification successful!</p>";
        } else {
            echo "<p style='color: red;'>❌ Password verification failed!</p>";
            echo "<p>Raw password in DB: " . substr($user['password'], 0, 20) . "...</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ User 'admin' not found in database</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error testing login: " . $e->getMessage() . "</p>";
}

// 4. Test API auth.php
echo "<h2>4. Testing API auth.php</h2>";
try {
    // Simulate POST request to auth.php
    $_POST = [
        'action' => 'login',
        'username' => 'admin',
        'password' => 'password'
    ];
    
    // Capture output
    ob_start();
    include 'api/auth.php';
    $output = ob_get_clean();
    
    echo "<p>API Response:</p>";
    echo "<pre>" . htmlspecialchars($output) . "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error testing API: " . $e->getMessage() . "</p>";
}

// 5. Fix password if needed
echo "<h2>5. Fixing Passwords</h2>";
try {
    $users_to_fix = [
        ['username' => 'admin', 'password' => 'password'],
        ['username' => 'dosen001', 'password' => 'password'],
        ['username' => 'mahasiswa001', 'password' => 'password'],
        ['username' => 'dosen002', 'password' => 'password'],
        ['username' => 'mahasiswa002', 'password' => 'password']
    ];
    
    $fixed = 0;
    foreach ($users_to_fix as $user_data) {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$user_data['username']]);
        
        if ($stmt->rowCount() > 0) {
            $hashed_password = password_hash($user_data['password'], PASSWORD_DEFAULT);
            $update_stmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
            $result = $update_stmt->execute([$hashed_password, $user_data['username']]);
            
            if ($result) {
                echo "<p style='color: green;'>✅ Fixed password for user: " . $user_data['username'] . "</p>";
                $fixed++;
            } else {
                echo "<p style='color: red;'>❌ Failed to fix password for user: " . $user_data['username'] . "</p>";
            }
        } else {
            echo "<p style='color: orange;'>⚠️ User not found: " . $user_data['username'] . "</p>";
        }
    }
    
    echo "<p style='color: green;'>✅ Fixed passwords for " . $fixed . " users</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error fixing passwords: " . $e->getMessage() . "</p>";
}

echo "<h2>6. Final Test</h2>";
echo "<p>Now try to login with:</p>";
echo "<ul>";
echo "<li>Username: admin, Password: password</li>";
echo "<li>Username: dosen001, Password: password</li>";
echo "<li>Username: mahasiswa001, Password: password</li>";
echo "</ul>";
echo "<p><a href='login.html' target='_blank'>Go to Login Page</a></p>";
echo "<p><a href='test_database_login.html' target='_blank'>Go to Test Page</a></p>";
?> 