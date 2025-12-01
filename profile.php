<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$username = $_SESSION["username"];
$email = $_SESSION["email"] ?? '';

// Get user profile data
$user_data = [];
$sql = "SELECT username, email, created_at, last_login, password FROM users WHERE id = ?";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
}

// Handle profile update
$update_success = "";
$update_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $new_username = trim($_POST["username"]);
    $new_email = trim($_POST["email"]);
    $current_password = trim($_POST["current_password"]);
    $new_password = trim($_POST["new_password"]);

    // Basic validation
    if (empty($new_username) || empty($new_email)) {
        $update_error = "Please fill in all required fields.";
    } else {
        // Check if username already exists (excluding current user)
        $sql = "SELECT id FROM users WHERE username = ? AND id != ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("si", $new_username, $user_id);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                $update_error = "This username is already taken.";
            } else {
                // Check if email already exists (excluding current user)
                $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("si", $new_email, $user_id);
                    $stmt->execute();
                    $stmt->store_result();
                    
                    if ($stmt->num_rows > 0) {
                        $update_error = "This email is already registered.";
                    } else {
                        // Update profile
                        if (!empty($new_password)) {
                            // If changing password, verify current password
                            if ($current_password === $user_data['password']) {
                                $sql = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
                                if ($stmt = $conn->prepare($sql)) {
                                    $stmt->bind_param("sssi", $new_username, $new_email, $new_password, $user_id);
                                    if ($stmt->execute()) {
                                        $_SESSION["username"] = $new_username;
                                        $_SESSION["email"] = $new_email;
                                        $update_success = "Profile updated successfully!";
                                    }
                                    $stmt->close();
                                }
                            } else {
                                $update_error = "Current password is incorrect.";
                            }
                        } else {
                            // Update without changing password
                            $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
                                if ($stmt->execute()) {
                                    $_SESSION["username"] = $new_username;
                                    $_SESSION["email"] = $new_email;
                                    $update_success = "Profile updated successfully!";
                                }
                                $stmt->close();
                            }
                        }
                    }
                    $stmt->close();
                }
            }
            $stmt->close();
        }
    }
    
    // Refresh user data
    $sql = "SELECT username, email, created_at, last_login, password FROM users WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - CyberWatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #1f2937;
            --light: #f8fafc;
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar: #1e1b4b;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            background: var(--sidebar);
            color: white;
            min-height: 100vh;
            padding: 0;
        }

        .sidebar-brand {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 1.5rem;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }

        .nav-item {
            margin: 8px 16px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 16px 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
            text-decoration: none;
        }

        .nav-link:hover {
            color: white;
            background: rgba(99, 102, 241, 0.2);
            transform: translateX(8px);
        }

        .nav-link.active {
            background: var(--gradient);
            color: white;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }

        .nav-link i {
            width: 24px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            background: var(--light);
            min-height: 100vh;
        }

        /* Top Bar */
        .top-bar {
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient);
        }

        .btn {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--gradient);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.6);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 3rem;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 1000;
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar">
                <div class="sidebar-brand">
                    <div class="brand-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="text-white mb-1">CyberWatch</h5>
                    <small class="text-white-50">User Portal</small>
                </div>
                
                <nav class="nav flex-column mt-4">
                    <div class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link" href="report.php">
                            <i class="fas fa-plus-circle"></i>
                            <span>New Report</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link" href="my_reports.php">
                            <i class="fas fa-clipboard-list"></i>
                            <span>My Reports</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link" href="infographic.php">
                            <i class="fas fa-chart-pie"></i>
                            <span>Statistics</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link" href="contact.php">
                            <i class="fas fa-headset"></i>
                            <span>Support</span>
                        </a>
                    </div>

                    <div class="nav-item">
                        <a class="nav-link active" href="profile.php">
                            <i class="fas fa-user-cog"></i>
                            <span>Profile</span>
                        </a>
                    </div>
                    
                    <div class="nav-item mt-4">
                        <a class="nav-link text-danger" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-lg-10 main-content">
                <!-- Top Bar -->
                <nav class="top-bar">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-dark fw-bold">Profile Settings</h4>
                            <small class="text-muted">Manage your account information and preferences</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">
                                <?php echo strtoupper(substr($username, 0, 1)); ?>
                            </div>
                            <div>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($username); ?></div>
                                <small class="text-muted">Security User</small>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Profile Content -->
                <div class="container-fluid py-4">
                    <div class="row">
                        <!-- Profile Information -->
                        <div class="col-lg-4 mb-4">
                            <div class="stat-card">
                                <div class="profile-avatar">
                                    <?php echo strtoupper(substr($user_data['username'], 0, 1)); ?>
                                </div>
                                
                                <h4 class="text-center fw-bold mb-2"><?php echo htmlspecialchars($user_data['username']); ?></h4>
                                <p class="text-center text-muted mb-4">Security User</p>
                                
                                <div class="info-item">
                                    <span class="fw-bold">Member Since</span>
                                    <span class="text-muted"><?php echo date('M j, Y', strtotime($user_data['created_at'])); ?></span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="fw-bold">Last Login</span>
                                    <span class="text-muted">
                                        <?php echo $user_data['last_login'] ? date('M j, Y g:i A', strtotime($user_data['last_login'])) : 'Never'; ?>
                                    </span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="fw-bold">Account Status</span>
                                    <span class="badge bg-success">Active</span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="fw-bold">User Role</span>
                                    <span class="text-muted">Security Reporter</span>
                                </div>
                            </div>

                            <!-- Security Settings -->
                            <div class="stat-card mt-4">
                                <h5 class="fw-bold mb-4">Security Settings</h5>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-shield-alt me-2"></i>Two-Factor Authentication
                                    </button>
                                    <button class="btn btn-outline-warning">
                                        <i class="fas fa-history me-2"></i>Login History
                                    </button>
                                    <button class="btn btn-outline-info">
                                        <i class="fas fa-bell me-2"></i>Notification Settings
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Profile Form -->
                        <div class="col-lg-8">
                            <div class="stat-card">
                                <h5 class="fw-bold mb-4">Edit Profile Information</h5>
                                
                                <?php if ($update_success): ?>
                                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                        <i class="fas fa-check-circle me-2"></i> 
                                        <?php echo $update_success; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($update_error): ?>
                                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i> 
                                        <?php echo $update_error; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <input type="hidden" name="update_profile" value="1">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Username *</label>
                                                <input type="text" name="username" class="form-control" 
                                                       value="<?php echo htmlspecialchars($user_data['username']); ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Email Address *</label>
                                                <input type="email" name="email" class="form-control" 
                                                       value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Current Password</label>
                                                <input type="password" name="current_password" class="form-control" 
                                                       placeholder="Enter current password to make changes">
                                                <small class="text-muted">Required only if changing password</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">New Password</label>
                                                <input type="password" name="new_password" class="form-control" 
                                                       placeholder="Enter new password">
                                                <small class="text-muted">Leave blank to keep current password</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Profile Bio</label>
                                        <textarea name="bio" class="form-control" rows="3" 
                                                  placeholder="Tell us a bit about yourself...">Security enthusiast focused on protecting digital assets and reporting threats.</textarea>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-4">
                                        <a href="dashboard.php" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Account Statistics -->
                            <div class="stat-card mt-4">
                                <h5 class="fw-bold mb-4">Account Statistics</h5>
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="border rounded-3 p-3">
                                            <div class="h4 fw-bold text-primary mb-1">12</div>
                                            <div class="text-muted small">Reports Submitted</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded-3 p-3">
                                            <div class="h4 fw-bold text-success mb-1">8</div>
                                            <div class="text-muted small">Reports Resolved</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border rounded-3 p-3">
                                            <div class="h4 fw-bold text-warning mb-1">67%</div>
                                            <div class="text-muted small">Success Rate</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.createElement('button');
            sidebarToggle.className = 'btn btn-primary d-lg-none position-fixed';
            sidebarToggle.style.cssText = 'bottom: 20px; right: 20px; z-index: 1001; border-radius: 50%; width: 60px; height: 60px;';
            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            document.body.appendChild(sidebarToggle);

            sidebarToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('show');
            });
        });
    </script>
</body>
</html>