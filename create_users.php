<?php
// Script untuk membuat data user jika belum ada
header('Content-Type: text/html; charset=utf-8');

echo "<h1>Create Users for SIOM</h1>";

try {
    require_once 'config/database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        echo "<p style='color: red;'>❌ Database connection failed!</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ Database connected successfully!</p>";
    
    // Cek apakah tabel users ada
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo "<p style='color: red;'>❌ Table 'users' does not exist!</p>";
        echo "<p>Please create the users table first.</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ Table 'users' exists!</p>";
    
    // Data user yang akan dibuat
    $users = [
        [
            'username' => 'admin',
            'password' => 'password',
            'role' => 'admin',
            'full_name' => 'Administrator',
            'email' => 'admin@siom.com'
        ],
        [
            'username' => 'dosen001',
            'password' => 'password',
            'role' => 'dosen',
            'full_name' => 'Dr. John Doe',
            'email' => 'dosen001@siom.com'
        ],
        [
            'username' => 'mahasiswa001',
            'password' => 'password',
            'role' => 'mahasiswa',
            'full_name' => 'Jane Smith',
            'email' => 'mahasiswa001@siom.com'
        ],
        [
            'username' => 'dosen002',
            'password' => 'password',
            'role' => 'dosen',
            'full_name' => 'Dr. Sarah Johnson',
            'email' => 'dosen002@siom.com'
        ],
        [
            'username' => 'mahasiswa002',
            'password' => 'password',
            'role' => 'mahasiswa',
            'full_name' => 'Mike Wilson',
            'email' => 'mahasiswa002@siom.com'
        ]
    ];
    
    $created = 0;
    $updated = 0;
    
    foreach ($users as $user_data) {
        // Cek apakah user sudah ada
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$user_data['username']]);
        
        if ($stmt->rowCount() > 0) {
            // User sudah ada, update password
            $hashed_password = password_hash($user_data['password'], PASSWORD_DEFAULT);
            $update_stmt = $db->prepare("UPDATE users SET password = ?, role = ?, full_name = ?, email = ? WHERE username = ?");
            $result = $update_stmt->execute([
                $hashed_password,
                $user_data['role'],
                $user_data['full_name'],
                $user_data['email'],
                $user_data['username']
            ]);
            
            if ($result) {
                echo "<p style='color: blue;'>🔄 Updated user: " . $user_data['username'] . "</p>";
                $updated++;
            } else {
                echo "<p style='color: red;'>❌ Failed to update user: " . $user_data['username'] . "</p>";
            }
        } else {
            // User belum ada, buat baru
            $hashed_password = password_hash($user_data['password'], PASSWORD_DEFAULT);
            $insert_stmt = $db->prepare("INSERT INTO users (username, password, role, full_name, email) VALUES (?, ?, ?, ?, ?)");
            $result = $insert_stmt->execute([
                $user_data['username'],
                $hashed_password,
                $user_data['role'],
                $user_data['full_name'],
                $user_data['email']
            ]);
            
            if ($result) {
                echo "<p style='color: green;'>✅ Created user: " . $user_data['username'] . "</p>";
                $created++;
            } else {
                echo "<p style='color: red;'>❌ Failed to create user: " . $user_data['username'] . "</p>";
            }
        }
    }
    
    echo "<h2>Summary</h2>";
    echo "<p style='color: green;'>✅ Created: " . $created . " users</p>";
    echo "<p style='color: blue;'>🔄 Updated: " . $updated . " users</p>";
    
    // Tampilkan semua user yang ada
    echo "<h2>All Users in Database</h2>";
    $stmt = $db->query("SELECT id, username, role, full_name, email FROM users ORDER BY id");
    $all_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($all_users) > 0) {
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Full Name</th><th>Email</th></tr>";
        foreach ($all_users as $user) {
            echo "<tr>";
            echo "<td>" . $user['id'] . "</td>";
            echo "<td>" . $user['username'] . "</td>";
            echo "<td>" . $user['role'] . "</td>";
            echo "<td>" . $user['full_name'] . "</td>";
            echo "<td>" . $user['email'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h2>Login Credentials</h2>";
    echo "<p>You can now login with:</p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> username: admin, password: password</li>";
    echo "<li><strong>Dosen:</strong> username: dosen001, password: password</li>";
    echo "<li><strong>Mahasiswa:</strong> username: mahasiswa001, password: password</li>";
    echo "<li><strong>Dosen 2:</strong> username: dosen002, password: password</li>";
    echo "<li><strong>Mahasiswa 2:</strong> username: mahasiswa002, password: password</li>";
    echo "</ul>";
    
    echo "<p><a href='login.html' target='_blank'>Go to Login Page</a></p>";
    echo "<p><a href='debug_login.php' target='_blank'>Run Debug Script</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?> 