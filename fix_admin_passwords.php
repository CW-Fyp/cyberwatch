<?php
require_once 'admin_config.php';

echo "<h3>Fixing Admin Passwords and Structure</h3>";

try {
    // Add is_admin column if it doesn't exist
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS is_admin TINYINT(1) DEFAULT 0");
    echo "✓ Added is_admin column if missing<br>";
    
    // Mark admin users
    $admin_users = ['admin', 'qhuzairil', 'amir'];
    $stmt = $pdo->prepare("UPDATE users SET is_admin = 1 WHERE username = ?");
    foreach ($admin_users as $username) {
        $stmt->execute([$username]);
        echo "✓ Marked $username as admin<br>";
    }
    
    // Set plain text passwords
    $passwords = [
        'admin' => 'admin123',
        'qhuzairil' => 'password123', 
        'amir' => 'amir123'
    ];
    
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
    foreach ($passwords as $username => $password) {
        $stmt->execute([$password, $username]);
        echo "✓ Set password for $username: $password<br>";
    }
    
    echo "<hr><h4>✅ Fix Complete!</h4>";
    echo "<h4>Test Logins:</h4>";
    foreach ($passwords as $username => $password) {
        echo "Username: <strong>$username</strong> | Password: <strong>$password</strong><br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>