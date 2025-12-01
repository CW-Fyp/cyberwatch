<?php
session_start();

// Log logout activity
if (isset($_SESSION['admin_username'])) {
    require_once 'admin_config.php';
    
    $stmt = $pdo->prepare("INSERT INTO admin_activity_log (admin_username, action, description, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['admin_username'], 'Logout', 'Admin logged out', $_SERVER['REMOTE_ADDR']]);
}

// Destroy session
session_unset();
session_destroy();

// Redirect to login page
header('Location: admin_login.php?success=Logged out successfully');
exit();
?>