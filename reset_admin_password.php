<?php
require_once 'admin_config.php';

$new_password = 'admin123'; // Change this to your desired password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
    $stmt->execute([$hashed_password]);
    
    echo "Password reset successfully!<br>";
    echo "New password: " . $new_password . "<br>";
    echo "Hashed: " . $hashed_password . "<br>";
    
    // Verify it works
    $verify_stmt = $pdo->prepare("SELECT password FROM users WHERE username = 'admin'");
    $verify_stmt->execute();
    $user = $verify_stmt->fetch();
    
    if (password_verify($new_password, $user['password'])) {
        echo "✓ Password verification successful!";
    } else {
        echo "✗ Password verification failed!";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>