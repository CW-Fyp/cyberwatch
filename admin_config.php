<?php
// admin_config.php - Database configuration and setup

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'dramranc_cyberwatch');
define('DB_USER', 'dramranc_cyberwatch');
define('DB_PASS', 'h1q0FR0JGNLd');

// Admin configuration
define('ADMIN_SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('BACKUP_DIR', '../backups/');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Create necessary tables if they don't exist
function createAdminTables() {
    global $pdo;
    
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
        )",
        
        "CREATE TABLE IF NOT EXISTS system_alerts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            alert_type VARCHAR(100) NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            severity ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
            resolved BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            resolved_at TIMESTAMP NULL
        )",
        
        "CREATE TABLE IF NOT EXISTS sessions (
            id VARCHAR(128) PRIMARY KEY,
            user_id INT,
            last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $table) {
        try {
            $pdo->exec($table);
        } catch (PDOException $e) {
            error_log("Table creation error: " . $e->getMessage());
        }
    }
    
    // Insert default settings
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
}

// Initialize tables
createAdminTables();
?>