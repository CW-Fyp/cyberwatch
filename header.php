<?php
// We require config.php on every page, which already starts the session.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? "CyberWatch - Secure Reporting"; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="style.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: 800;
            font-size: 1.8rem;
            background: linear-gradient(135deg, #3498db, #2c3e50);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .nav-link {
            font-weight: 600;
            margin: 0 0.5rem;
            transition: color 0.3s ease;
        }
        
        .nav-link:hover {
            color: #3498db !important;
        }
        
        .btn-nav {
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-nav-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            color: white;
        }
        
        .btn-nav-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }
    </style>

    <!-- Add this to your header or navigation -->
<div class="navbar-brand">
    <img src="assets/images/cyberwatch-logo.png" alt="CyberWatch" height="40" class="me-2">
    <span class="fw-bold text-primary">CyberWatch</span>
</div>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
  <div class="container">
    <a class="navbar-brand" href="index.php">
        <i class="fas fa-shield-alt me-2"></i>CyberWatch
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      
      <ul class="navbar-nav ms-auto align-items-center">
        
        <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <li class="nav-item">
              <span class="navbar-text me-3">
                Welcome, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>
              </span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">
                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="report.php">
                <i class="fas fa-plus-circle me-1"></i>New Report
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="infographic.php">
                <i class="fas fa-chart-pie me-1"></i>Statistics
              </a>
            </li>

             <div class="nav-item">
                        <a class="nav-link active" href="profile.php">
                            <i class="fas fa-user-cog"></i>
                            <span>Profile</span>
                        </a>
                    </div>

            <li class="nav-item ms-2">
              <a class="btn btn-nav btn-nav-primary" href="logout.php">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
              </a>
            </li>
        <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">
                <i class="fas fa-sign-in-alt me-1"></i>Sign In
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin_login.php">
                <i class="fas fa-user-shield me-1"></i>Admin
              </a>
            </li>
            <li class="nav-item ms-2">
              <a class="btn btn-nav btn-nav-primary" href="register.php">
                <i class="fas fa-user-plus me-1"></i>Get Started
              </a>
            </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>