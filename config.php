<?php
// Enhanced session security
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// Database credentials with error handling
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'dramranc_cyberwatch');
define('DB_PASSWORD', 'h1q0FR0JGNLd');
define('DB_NAME', 'dramranc_cyberwatch');

try {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if($conn->connect_error){
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    die("System maintenance in progress. Please try again later.");
}

// Environment-based error reporting
if ($_SERVER['SERVER_NAME'] === 'localhost') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Function to check if user is admin
function isAdminUser($username, $password, $conn) {
    $sql = "SELECT username, password, role, is_admin FROM users WHERE username = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($db_username, $db_password, $db_role, $db_is_admin);
            $stmt->fetch();
            
            // Check if password matches and user is admin
            if ($password === $db_password) {
                return ($db_role === 'admin' || $db_is_admin == 1);
            }
        }
        $stmt->close();
    }
    return false;
}
?>