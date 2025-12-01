<?php
require_once 'admin_config.php';

echo "<h3>Fixing Admin Login System</h3>";

try {
    // 1. Ensure admin users exist with proper hashed passwords
    $admin_users = [
        'admin' => password_hash('admin123', PASSWORD_DEFAULT),
        'qhuzairil' => password_hash('password123', PASSWORD_DEFAULT),
        'amir' => password_hash('amir123', PASSWORD_DEFAULT)
    ];
    
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, is_admin, status, created_at) 
                          VALUES (?, ?, ?, 'admin', 1, 'active', NOW())
                          ON DUPLICATE KEY UPDATE 
                          password = VALUES(password), 
                          role = VALUES(role), 
                          is_admin = VALUES(is_admin),
                          status = VALUES(status)");
    
    foreach ($admin_users as $username => $hashed_password) {
        $email = $username . '@cyberwatch.com';
        $stmt->execute([$username, $email, $hashed_password]);
        echo "✓ Updated admin user: $username<br>";
    }
    
    // 2. Create necessary tables if they don't exist
    $tables = [
        "CREATE TABLE IF NOT EXISTS admin_activity_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admin_username VARCHAR(100) NOT NULL,
            action VARCHAR(255) NOT NULL,
            description TEXT,
            ip_address VARCHAR(45) NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS security_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            event_type VARCHAR(100) NOT NULL,
            username VARCHAR(100),
            ip_address VARCHAR(45) NOT NULL,
            description TEXT NOT NULL,
            severity ENUM('low', 'medium', 'high') DEFAULT 'low',
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS system_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            description TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $table) {
        try {
            $pdo->exec($table);
            echo "✓ Created table if missing<br>";
        } catch (PDOException $e) {
            echo "Table creation: " . $e->getMessage() . "<br>";
        }
    }
    
    // 3. Insert default settings
    $default_settings = [
        ['site_name', 'CyberWatch', 'Website name'],
        ['maintenance_mode', '0', 'Enable maintenance mode'],
        ['session_timeout', '60', 'Session timeout in minutes'],
        ['max_login_attempts', '5', 'Maximum login attempts'],
        ['enable_registration', '1', 'Enable user registration'],
        ['enable_2fa', '0', 'Enable two-factor authentication']
    ];
    
    foreach ($default_settings as $setting) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO system_settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
        $stmt->execute($setting);
    }
    echo "✓ Added default settings<br>";
    
    echo "<hr><h4>✅ Admin System Fix Complete!</h4>";
    echo "<h4>Test Credentials:</h4>";
    echo "Username: <strong>admin</strong> | Password: <strong>admin123</strong><br>";
    echo "Username: <strong>qhuzairil</strong> | Password: <strong>password123</strong><br>";
    echo "Username: <strong>amir</strong> | Password: <strong>amir123</strong><br>";
    echo "<hr>";
    echo "<a href='admin_login.php' class='btn btn-primary'>Go to Admin Login</a>";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>