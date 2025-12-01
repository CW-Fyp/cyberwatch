<?php
session_start();
require_once 'config.php';

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password, role, status, is_admin FROM users WHERE username = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
            
            if ($stmt->execute()) {
                $stmt->store_result();
                
                // Check if username exists
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username, $hashed_password, $role, $status, $is_admin);
                    if ($stmt->fetch()) {
                        // Use plain text comparison
                        if ($password === $hashed_password) {
                            // Password is correct
                            if ($status === 'active') {
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                $_SESSION["role"] = $role;
                                $_SESSION["is_admin"] = $is_admin;
                                
                                // Update last login
                                $update_sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
                                if ($update_stmt = $conn->prepare($update_sql)) {
                                    $update_stmt->bind_param("i", $id);
                                    $update_stmt->execute();
                                    $update_stmt->close();
                                }
                                
                                // Log login activity
                                $log_sql = "INSERT INTO security_logs (event_type, username, ip_address, description, severity) VALUES (?, ?, ?, ?, ?)";
                                if ($log_stmt = $conn->prepare($log_sql)) {
                                    $event_type = 'Successful Login';
                                    $description = 'User logged in successfully';
                                    $severity = 'low';
                                    $log_stmt->bind_param("sssss", $event_type, $username, $_SERVER['REMOTE_ADDR'], $description, $severity);
                                    $log_stmt->execute();
                                    $log_stmt->close();
                                }
                                
                                // Redirect user based on role
                                if ($role === 'admin' || $is_admin == 1) {
                                    $_SESSION["admin_logged_in"] = true;
                                    $_SESSION["admin_username"] = $username;
                                    $_SESSION["admin_last_activity"] = time();
                                    
                                    // Log admin login
                                    $admin_log_sql = "INSERT INTO admin_activity_log (admin_username, action, description, ip_address) VALUES (?, ?, ?, ?)";
                                    if ($admin_log_stmt = $conn->prepare($admin_log_sql)) {
                                        $action = 'Login';
                                        $description = 'Admin logged in through unified login';
                                        $admin_log_stmt->bind_param("ssss", $username, $action, $description, $_SERVER['REMOTE_ADDR']);
                                        $admin_log_stmt->execute();
                                        $admin_log_stmt->close();
                                    }
                                    
                                    header("location: admin_dashboard.php");
                                } else {
                                    header("location: dashboard.php");
                                }
                                exit();
                            } else {
                                $password_err = "Your account has been suspended.";
                            }
                        } else {
                            // Password is not valid
                            $password_err = "The password you entered was not valid.";
                            
                            // Log failed login attempt
                            $log_sql = "INSERT INTO security_logs (event_type, username, ip_address, description, severity) VALUES (?, ?, ?, ?, ?)";
                            if ($log_stmt = $conn->prepare($log_sql)) {
                                $event_type = 'Failed Login';
                                $description = 'Incorrect password entered';
                                $severity = 'medium';
                                $log_stmt->bind_param("sssss", $event_type, $username, $_SERVER['REMOTE_ADDR'], $description, $severity);
                                $log_stmt->execute();
                                $log_stmt->close();
                            }
                        }
                    }
                } else {
                    // Username doesn't exist
                    $username_err = "No account found with that username.";
                    
                    // Log failed login attempt
                    $log_sql = "INSERT INTO security_logs (event_type, username, ip_address, description, severity) VALUES (?, ?, ?, ?, ?)";
                    if ($log_stmt = $conn->prepare($log_sql)) {
                        $event_type = 'Failed Login';
                        $description = 'Username not found';
                        $severity = 'medium';
                        $log_stmt->bind_param("sssss", $event_type, $username, $_SERVER['REMOTE_ADDR'], $description, $severity);
                        $log_stmt->execute();
                        $log_stmt->close();
                    }
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - CyberWatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 2;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
        }

        .login-header {
            background: var(--gradient);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .form-group {
            position: relative;
            margin-bottom: 24px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px 16px 50px;
            font-size: 16px;
            transition: all 0.3s ease;
            height: 56px;
        }

        .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }

        .form-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 1.2rem;
            z-index: 3;
        }

        .btn {
            border-radius: 12px;
            padding: 16px 32px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--gradient);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.6);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 16px 20px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .alert::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
        }

        .alert-danger::before { background: #ef4444; }
        .alert-success::before { background: #10b981; }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatElement 6s ease-in-out infinite;
        }

        @keyframes floatElement {
            0%, 100% { 
                transform: translateY(0px) rotate(0deg); 
                opacity: 0.7;
            }
            50% { 
                transform: translateY(-20px) rotate(180deg); 
                opacity: 0.3;
            }
        }

        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .test-credentials {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            border-left: 4px solid var(--primary);
        }

        .test-credentials h6 {
            color: var(--primary);
            margin-bottom: 15px;
        }

        .credential-item {
            margin-bottom: 10px;
            padding: 8px 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .credential-item:last-child {
            margin-bottom: 0;
        }

        .admin-badge {
            background: var(--primary);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7em;
            margin-left: 8px;
        }

        .user-badge {
            background: var(--success);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.7em;
            margin-left: 8px;
        }
    </style>
</head>
<body>
    <!-- Animated Background Elements -->
    <div class="floating-elements">
        <div class="floating-element" style="top: 15%; left: 5%; width: 35px; height: 35px; animation-delay: 0s;"></div>
        <div class="floating-element" style="top: 75%; left: 90%; width: 55px; height: 55px; animation-delay: 2s;"></div>
        <div class="floating-element" style="top: 25%; left: 92%; width: 25px; height: 25px; animation-delay: 4s;"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Welcome Back</h3>
                <p class="mb-0">Sign in to your CyberWatch account</p>
            </div>
            
            <div class="card-body p-4">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> 
                        <?php echo $_SESSION['success_message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="needs-validation" novalidate>
                    <div class="form-group">
                        <i class="form-icon fas fa-user"></i>
                        <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" 
                               value="<?php echo $username; ?>" placeholder="Enter your username" required>
                        <div class="invalid-feedback"><?php echo $username_err ?: 'Please enter your username.'; ?></div>
                    </div>
                    
                    <div class="form-group">
                        <i class="form-icon fas fa-lock"></i>
                        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
                               placeholder="Enter your password" required>
                        <div class="invalid-feedback"><?php echo $password_err ?: 'Please enter your password.'; ?></div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label text-muted" for="remember">Remember me</label>
                        </div>
                        <a href="#" class="login-link">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 py-3 mb-4">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </button>
                </form>

                <!-- Test Credentials -->
                <div class="test-credentials">
                    <h6><i class="fas fa-key me-2"></i>Test Credentials</h6>
                    
                    <div class="credential-item">
                        <strong>admin</strong> / <strong>admin123</strong>
                        <span class="admin-badge">Admin</span>
                        <small class="d-block text-muted mt-1">Full administrative access</small>
                    </div>
                    
                    <div class="credential-item">
                        <strong>qhuzairil</strong> / <strong>password123</strong>
                        <span class="user-badge">User</span>
                        <small class="d-block text-muted mt-1">Regular user account</small>
                    </div>
                    
                    <div class="credential-item">
                        <strong>amir</strong> / <strong>amir123</strong>
                        <span class="user-badge">User</span>
                        <small class="d-block text-muted mt-1">Regular user account</small>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <p class="text-muted mb-0">
                        Don't have an account? 
                        <a href="register.php" class="login-link">Create one here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // Add floating elements
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.body;
            for (let i = 0; i < 6; i++) {
                const element = document.createElement('div');
                element.className = 'floating-element';
                element.style.cssText = `
                    width: ${20 + Math.random() * 40}px;
                    height: ${20 + Math.random() * 40}px;
                    top: ${Math.random() * 100}%;
                    left: ${Math.random() * 100}%;
                    animation-delay: ${Math.random() * 6}s;
                `;
                container.appendChild(element);
            }
        });
    </script>
</body>
</html>