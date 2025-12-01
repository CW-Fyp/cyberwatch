<?php
session_start();
require_once "config.php"; // For session AND database

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$username = $_SESSION["username"];
$pageTitle = "Contact Us";
$contact_success = "";
$contact_error = "";

// --- This is your existing code to save messages to the database ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($message)) {
        $sql = "INSERT INTO contact_messages (user_name, user_email, subject, message) VALUES (?, ?, ?, ?)";
        
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssss", $name, $email, $subject, $message);
            
            if($stmt->execute()){
                $contact_success = "Thank you for your message! It has been saved.";
            } else {
                $contact_error = "Oops! Something went wrong. Please try again.";
            }
            $stmt->close();
        } else {
            $contact_error = "Database error. Please try again later.";
        }
    } else {
        $contact_error = "Please fill out all required fields.";
    }
}
// --- End of existing code ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support - CyberWatch</title>
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
            transition: all 0.3s ease;
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
            backdrop-filter: blur(20px);
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

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
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

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .action-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
            color: inherit;
            text-decoration: none;
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
            background: var(--gradient);
            color: white;
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 20px;
            background: rgba(248, 250, 252, 0.8);
            border-radius: 12px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .contact-item:hover {
            background: white;
            transform: translateX(8px);
            box-shadow: var(--card-shadow);
        }

        .contact-item-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: var(--gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .form-group {
            position: relative;
            margin-bottom: 24px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
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

        textarea.form-control {
            height: auto;
            resize: vertical;
            min-height: 120px;
        }

        .btn {
            border-radius: 12px;
            padding: 16px 32px;
            font-weight: 600;
            font-size: 16px;
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

        .alert-success::before { background: var(--success); }
        .alert-danger::before { background: var(--danger); }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.6s ease-out;
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
                    <small class="text-white-50">Security Portal</small>
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
                        <a class="nav-link active" href="contact.php">
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
                            <h4 class="mb-0 text-dark fw-bold">Contact Support</h4>
                            <small class="text-muted">Get help and support from our team</small>
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

                <!-- Contact Content -->
                <div class="container-fluid py-4">
                    <div class="row g-4">
                        <!-- Contact Information -->
                        <div class="col-lg-5">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.1s">
                                <div class="action-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h4 class="fw-bold mb-4">Who to Contact</h4>
                                <p class="text-muted mb-4">For immediate concerns or technical support, please reach out to our dedicated team members.</p>
                                
                                <div class="contact-item">
                                    <div class="contact-item-icon">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Security Admin</h6>
                                        <p class="text-muted mb-0">qhuzairil@gmail.com</p>
                                        <small class="text-primary">Primary Contact</small>
                                    </div>
                                </div>

                                <div class="contact-item">
                                    <div class="contact-item-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Support Team</h6>
                                        <p class="text-muted mb-0">support@cyberwatch.com</p>
                                        <small class="text-success">Technical Support</small>
                                    </div>
                                </div>

                                <div class="contact-item">
                                    <div class="contact-item-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">Phone Support</h6>
                                        <p class="text-muted mb-0">+60 19-302 1803</p>
                                        <small class="text-warning">Available 24/7</small>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 bg-light rounded-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1 text-info"></i>
                                        For security incident reports, please use the dedicated report system in your dashboard.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Form -->
                        <div class="col-lg-7">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.2s">
                                <div class="action-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <h4 class="fw-bold mb-4">Send Us a Message</h4>
                                
                                <?php if(!empty($contact_success)): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?php echo $contact_success; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($contact_error)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <?php echo $contact_error; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if(empty($contact_success)): ?>
                                <form action="contact.php" method="post" class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name" class="form-label fw-semibold">Your Name *</label>
                                                <input type="text" class="form-control" id="name" name="name" required 
                                                       placeholder="Enter your full name">
                                                <div class="invalid-feedback">Please provide your name.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email" class="form-label fw-semibold">Your Email *</label>
                                                <input type="email" class="form-control" id="email" name="email" required 
                                                       placeholder="Enter your email address">
                                                <div class="invalid-feedback">Please provide a valid email.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="subject" class="form-label fw-semibold">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject" 
                                               placeholder="What is this regarding?">
                                    </div>

                                    <div class="form-group">
                                        <label for="message" class="form-label fw-semibold">Message *</label>
                                        <textarea class="form-control" id="message" name="message" rows="6" required 
                                                  placeholder="Please describe your inquiry in detail..."></textarea>
                                        <div class="invalid-feedback">Please provide your message.</div>
                                    </div>

                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg py-3">
                                            <i class="fas fa-paper-plane me-2"></i>Send Message
                                        </button>
                                    </div>
                                </form>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <div class="mb-4">
                                            <i class="fas fa-check-circle fa-4x text-success"></i>
                                        </div>
                                        <h5 class="text-success mb-3">Message Sent Successfully!</h5>
                                        <p class="text-muted mb-4">We'll get back to you as soon as possible.</p>
                                        <a href="contact.php" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Send Another Message
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
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

            // Animate elements on scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            document.querySelectorAll('.animate-slide-in').forEach((el) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'all 0.6s ease-out';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>

<?php 
include "footer.php"; // Include your main public footer
?>