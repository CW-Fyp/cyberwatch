<?php
require_once 'admin_config.php';

echo "<h3>Fixing Database Structure</h3>";

try {
    // Add is_admin column if it doesn't exist
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) DEFAULT 0");
    echo "✓ Added is_admin column if missing<br>";
    
    // Add status column if it doesn't exist
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'active'");
    echo "✓ Added status column if missing<br>";
    
    // Add last_login column if it doesn't exist
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login TIMESTAMP NULL");
    echo "✓ Added last_login column if missing<br>";
    
    // Add created_at column if it doesn't exist
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
    echo "✓ Added created_at column if missing<br>";
    
    // Remove password_hash column if it exists (wrong column name)
    try {
        $pdo->exec("ALTER TABLE users DROP COLUMN password_hash");
        echo "✓ Removed password_hash column if it existed<br>";
    } catch (Exception $e) {
        echo "✓ password_hash column doesn't exist or couldn't be removed<br>";
    }
    
    // Create admin users with plain text passwords
    $admin_users = [
        'admin' => 'admin123',
        'qhuzairil' => 'password123', 
        'amir' => 'amir123'
    ];
    
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, is_admin, status, created_at) 
                          VALUES (?, ?, ?, 'admin', 1, 'active', NOW())
                          ON DUPLICATE KEY UPDATE 
                          password = VALUES(password), 
                          role = VALUES(role), 
                          is_admin = VALUES(is_admin),
                          status = VALUES(status)");
    
    foreach ($admin_users as $username => $password) {
        $email = $username . '@cyberwatch.com';
        $stmt->execute([$username, $email, $password]);
        echo "✓ Updated admin user: $username<br>";
    }
    
    echo "<hr><h4>✅ Database Fix Complete!</h4>";
    echo "<h4>Test Admin Credentials:</h4>";
    foreach ($admin_users as $username => $password) {
        echo "Username: <strong>$username</strong> | Password: <strong>$password</strong><br>";
    }
    
    echo "<hr>";
    echo "<a href='admin_login.php' class='btn btn-primary'>Go to Admin Login</a>";
    echo " <a href='register.php' class='btn btn-success'>Test Registration</a>";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>