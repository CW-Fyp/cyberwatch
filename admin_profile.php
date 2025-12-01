<?php
session_start();
require_once 'admin_auth.php';
require_once 'admin_config.php';

verifyAdminAccess();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        
        // Validate current password (you should use proper authentication)
        if ($current_password === 'your_secure_password') {
            // Update profile logic here
            $success = "Profile updated successfully";
        } else {
            $error = "Current password is incorrect";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - CyberWatch Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Reuse sidebar from admin_dashboard.php -->
            <?php include('admin_sidebar.php'); ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-user-cog me-2"></i>Admin Profile</h1>
                </div>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h6 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i>Update Profile</h6>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="action" value="update_profile">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" name="username" value="admin" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="admin@cyberwatch.com">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control" name="current_password" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="new_password">
                                        <small class="form-text text-muted">Leave blank to keep current password</small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h6 class="card-title mb-0"><i class="fas fa-shield-alt me-2"></i>Security Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Last Login:</strong><br>
                                    <?php
                                    $stmt = $pdo->prepare("SELECT timestamp FROM admin_activity_log WHERE admin_username = ? AND action = 'Login' ORDER BY timestamp DESC LIMIT 1,1");
                                    $stmt->execute([$_SESSION['admin_username']]);
                                    $last_login = $stmt->fetch();
                                    echo $last_login ? date('Y-m-d H:i:s', strtotime($last_login['timestamp'])) : 'Never';
                                    ?>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Current Session IP:</strong><br>
                                    <?php echo $_SERVER['REMOTE_ADDR']; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>Session Started:</strong><br>
                                    <?php echo date('Y-m-d H:i:s', $_SESSION['admin_last_activity']); ?>
                                </div>
                                
                                <div class="mb-3">
                                    <strong>User Agent:</strong><br>
                                    <small class="text-muted"><?php echo $_SERVER['HTTP_USER_AGENT']; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>