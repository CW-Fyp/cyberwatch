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

$title = $description = $category = $priority = "";
$title_err = $description_err = $category_err = $priority_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title for the report.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Validate description
    if (empty(trim($_POST["description"]))) {
        $description_err = "Please provide a description.";
    } else {
        $description = trim($_POST["description"]);
    }

    // Validate category
    if (empty($_POST["category"])) {
        $category_err = "Please select a category.";
    } else {
        $category = $_POST["category"];
    }

    // Validate priority
    if (empty($_POST["priority"])) {
        $priority_err = "Please select a priority level.";
    } else {
        $priority = $_POST["priority"];
    }

    // Check for errors before inserting
    if (empty($title_err) && empty($description_err) && empty($category_err) && empty($priority_err)) {
        $sql = "INSERT INTO reports (user_id, title, description, category, priority, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("issss", $user_id, $title, $description, $category, $priority);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Report submitted successfully!";
                header("location: my_reports.php");
                exit();
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
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="cyberwatch-logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Report - CyberWatch</title>
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

        /* Form Styles */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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

        /* Priority Cards */
        .priority-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
        }

        .priority-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .priority-card.active {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }

        .priority-high.active { border-left: 4px solid var(--danger); }
        .priority-medium.active { border-left: 4px solid var(--warning); }
        .priority-low.active { border-left: 4px solid var(--info); }

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
            
            .form-card {
                padding: 25px;
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
                        <a class="nav-link active" href="new_report.php">
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
                            <h4 class="mb-0 text-dark fw-bold">Submit New Report</h4>
                            <small class="text-muted">Report a security incident or concern</small>
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

                <!-- Form Content -->
                <div class="container-fluid py-4">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="form-card animate-slide-in">
                                <h4 class="fw-bold mb-4">
                                    <i class="fas fa-plus-circle me-2 text-primary"></i>New Security Report
                                </h4>
                                
                                <?php if (isset($_SESSION['success_message'])): ?>
                                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                        <i class="fas fa-check-circle me-2"></i> 
                                        <?php echo $_SESSION['success_message']; ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    <?php unset($_SESSION['success_message']); ?>
                                <?php endif; ?>
                                
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Report Title *</label>
                                                <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" 
                                                       value="<?php echo htmlspecialchars($title); ?>" placeholder="Enter a clear and descriptive title">
                                                <div class="invalid-feedback"><?php echo $title_err; ?></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Category *</label>
                                                <select name="category" class="form-select <?php echo (!empty($category_err)) ? 'is-invalid' : ''; ?>">
                                                    <option value="">Select Category</option>
                                                    <option value="cyberbullying" <?php echo $category == 'cyberbullying' ? 'selected' : ''; ?>>Cyberbullying</option>
                                                    <option value="harassment" <?php echo $category == 'harassment' ? 'selected' : ''; ?>>Online Harassment</option>
                                                    <option value="threats" <?php echo $category == 'threats' ? 'selected' : ''; ?>>Threats & Intimidation</option>
                                                    <option value="impersonation" <?php echo $category == 'impersonation' ? 'selected' : ''; ?>>Impersonation</option>
                                                    <option value="privacy" <?php echo $category == 'privacy' ? 'selected' : ''; ?>>Privacy Violation</option>
                                                    <option value="other" <?php echo $category == 'other' ? 'selected' : ''; ?>>Other</option>
                                                </select>
                                                <div class="invalid-feedback"><?php echo $category_err; ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Priority Level *</label>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="priority-card priority-high <?php echo $priority == 'high' ? 'active' : ''; ?>" onclick="selectPriority('high')">
                                                    <div class="card-body text-center p-3">
                                                        <i class="fas fa-exclamation-triangle fa-2x text-danger mb-2"></i>
                                                        <h6 class="fw-bold">High Priority</h6>
                                                        <small class="text-muted">Critical incident requiring immediate attention</small>
                                                        <input type="radio" name="priority" value="high" <?php echo $priority == 'high' ? 'checked' : ''; ?> style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="priority-card priority-medium <?php echo $priority == 'medium' ? 'active' : ''; ?>" onclick="selectPriority('medium')">
                                                    <div class="card-body text-center p-3">
                                                        <i class="fas fa-exclamation-circle fa-2x text-warning mb-2"></i>
                                                        <h6 class="fw-bold">Medium Priority</h6>
                                                        <small class="text-muted">Serious concern that needs prompt review</small>
                                                        <input type="radio" name="priority" value="medium" <?php echo $priority == 'medium' ? 'checked' : ''; ?> style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="priority-card priority-low <?php echo $priority == 'low' ? 'active' : ''; ?>" onclick="selectPriority('low')">
                                                    <div class="card-body text-center p-3">
                                                        <i class="fas fa-info-circle fa-2x text-info mb-2"></i>
                                                        <h6 class="fw-bold">Low Priority</h6>
                                                        <small class="text-muted">General inquiry or minor concern</small>
                                                        <input type="radio" name="priority" value="low" <?php echo $priority == 'low' ? 'checked' : ''; ?> style="display: none;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (!empty($priority_err)): ?>
                                            <div class="text-danger mt-2"><?php echo $priority_err; ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Detailed Description *</label>
                                        <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>" 
                                                  rows="6" placeholder="Provide detailed information about the incident including:
- What happened
- When it occurred
- Who was involved
- Platform/website where it happened
- Any evidence you have"><?php echo htmlspecialchars($description); ?></textarea>
                                        <div class="invalid-feedback"><?php echo $description_err; ?></div>
                                        <small class="text-muted">Please be as detailed as possible. Include URLs, usernames, timestamps, and any relevant information.</small>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Evidence & Attachments (Optional)</label>
                                        <div class="border rounded p-3 bg-light">
                                            <div class="mb-3">
                                                <input type="file" class="form-control" id="evidenceFiles" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                You can attach screenshots, photos, documents, or other evidence. Maximum 5 files, 10MB each. Supported formats: JPG, PNG, PDF, DOC.
                                            </small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="confirmation" required>
                                            <label class="form-check-label" for="confirmation">
                                                I confirm that the information provided is accurate to the best of my knowledge
                                            </label>
                                            <div class="invalid-feedback">
                                                You must confirm the accuracy of your report before submitting.
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                        <a href="dashboard.php" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Secure Report
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectPriority(priority) {
            // Remove active class from all cards
            document.querySelectorAll('.priority-card').forEach(card => {
                card.classList.remove('active');
            });
            
            // Add active class to selected card
            event.currentTarget.classList.add('active');
            
            // Update radio button
            document.querySelector(`input[value="${priority}"]`).checked = true;
        }

        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('form')
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

            // Auto-select priority if already chosen (for form validation errors)
            const selectedPriority = document.querySelector('input[name="priority"]:checked');
            if (selectedPriority) {
                const priorityCard = selectedPriority.closest('.priority-card');
                if (priorityCard) {
                    priorityCard.classList.add('active');
                }
            }

            // Add some interactive animations
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