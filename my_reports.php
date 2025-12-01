<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$username = $_SESSION["username"];

// Get all user reports
$reports = [];
$sql = "SELECT 
        report_id, 
        report_details, 
        date_reported, 
        status
        FROM reports 
        WHERE user_id = ? 
        ORDER BY date_reported DESC";
        
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $reports = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Handle report deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM reports WHERE report_id = ? AND user_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $delete_id, $user_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Report deleted successfully!";
            header("location: my_reports.php");
            exit();
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reports - CyberWatch</title>
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

        /* Tables */
        .table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 20px;
            font-weight: 600;
        }

        .table td {
            padding: 20px;
            border-color: #f1f5f9;
            vertical-align: middle;
        }

        /* Badges */
        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-in-review { background: #dbeafe; color: #1e40af; }
        .badge-resolved { background: #d1fae5; color: #065f46; }

        /* Buttons */
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

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.875rem;
        }

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

        /* Report Details */
        .report-details {
            max-width: 400px;
        }

        .toggle-details {
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
        }

        .toggle-details:hover {
            text-decoration: underline;
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
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .btn-group-sm .btn {
                padding: 4px 8px;
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
                        <a class="nav-link active" href="my_reports.php">
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
                            <h4 class="mb-0 text-dark fw-bold">My Security Reports</h4>
                            <small class="text-muted">Manage and track your submitted reports</small>
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

                <!-- Reports Content -->
                <div class="container-fluid py-4">
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i> 
                            <?php echo $_SESSION['success_message']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card animate-slide-in">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon" style="background: var(--gradient); width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                        <i class="fas fa-clipboard-list text-white"></i>
                                    </div>
                                    <div>
                                        <div class="stat-number" style="font-size: 2rem; font-weight: 700;"><?php echo count($reports); ?></div>
                                        <div class="stat-label" style="color: #64748b;">Total Reports</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">All Reports (<?php echo count($reports); ?>)</h5>
                        <a href="report.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>New Report
                        </a>
                    </div>

                    <?php if (!empty($reports)): ?>
                        <!-- Table View -->
                        <div class="stat-card animate-slide-in">
                            <div class="table-container">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Report ID</th>
                                                <th>Report Details</th>
                                                <th>Status</th>
                                                <th>Date Submitted</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reports as $report): ?>
                                                <tr>
                                                    <td><strong>#<?php echo $report['report_id']; ?></strong></td>
                                                    <td>
                                                        <div class="report-details">
                                                            <?php 
                                                                $details = htmlspecialchars($report['report_details']);
                                                                if (strlen($details) > 100) {
                                                                    echo '<span class="short-text">' . substr($details, 0, 100) . '...</span>';
                                                                    echo '<span class="full-text d-none">' . $details . '</span>';
                                                                    echo '<a href="#" class="text-primary toggle-details ms-1">Show more</a>';
                                                                } else {
                                                                    echo $details;
                                                                }
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-<?php echo str_replace('_', '-', $report['status']); ?>">
                                                            <i class="fas fa-<?php 
                                                                if ($report['status'] == 'pending') echo 'clock';
                                                                elseif ($report['status'] == 'in_review') echo 'search';
                                                                else echo 'check';
                                                            ?> me-1"></i>
                                                            <?php echo ucfirst(str_replace('_', ' ', $report['status'])); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            <?php echo date('M j, Y, g:i A', strtotime($report['date_reported'])); ?>
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-outline-primary view-report-btn" 
                                                                    data-id="<?php echo $report['report_id']; ?>"
                                                                    data-details="<?php echo htmlspecialchars($report['report_details']); ?>"
                                                                    data-status="<?php echo $report['status']; ?>"
                                                                    data-created="<?php echo $report['date_reported']; ?>"
                                                                    title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-outline-danger" 
                                                                    onclick="confirmDelete(<?php echo $report['report_id']; ?>)" 
                                                                    title="Delete Report">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="stat-card animate-slide-in">
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-clipboard-list fa-4x text-muted opacity-50"></i>
                                </div>
                                <h5 class="text-muted mb-3">No Reports Found</h5>
                                <p class="text-muted mb-4">You haven't submitted any security reports yet.</p>
                                <a href="report.php" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Submit Your First Report
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Details Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-file-alt me-2"></i>Report Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Report ID:</strong>
                                <span id="modalReportId" class="fw-bold ms-2"></span>
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <span id="modalReportStatus" class="ms-2"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Date Submitted:</strong>
                                <span id="modalReportDate" class="ms-2"></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <strong>Report Details:</strong>
                        <div class="border rounded p-3 bg-light mt-2">
                            <p id="modalReportDetails" class="mb-0"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(reportId) {
            if (confirm('Are you sure you want to delete this report? This action cannot be undone.')) {
                window.location.href = 'my_reports.php?delete_id=' + reportId;
            }
        }

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

            // Report modal functionality
            const reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
            document.querySelectorAll('.view-report-btn').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('modalReportId').textContent = '#' + this.dataset.id;
                    document.getElementById('modalReportDetails').textContent = this.dataset.details;
                    document.getElementById('modalReportDate').textContent = new Date(this.dataset.created).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    // Set status with appropriate badge
                    const statusSpan = document.getElementById('modalReportStatus');
                    let statusClass = 'badge-pending';
                    let statusIcon = 'clock';
                    
                    if (this.dataset.status === 'in_review') {
                        statusClass = 'badge-in-review';
                        statusIcon = 'search';
                    } else if (this.dataset.status === 'resolved') {
                        statusClass = 'badge-resolved';
                        statusIcon = 'check';
                    }
                    
                    statusSpan.innerHTML = `<span class="badge ${statusClass}">
                        <i class="fas fa-${statusIcon} me-1"></i>
                        ${this.dataset.status.charAt(0).toUpperCase() + this.dataset.status.slice(1).replace('_', ' ')}
                    </span>`;
                    
                    reportModal.show();
                });
            });

            // Toggle report details
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('toggle-details')) {
                    e.preventDefault();
                    const detailsDiv = e.target.closest('.report-details');
                    const shortText = detailsDiv.querySelector('.short-text');
                    const fullText = detailsDiv.querySelector('.full-text');
                    
                    if (fullText.classList.contains('d-none')) {
                        shortText.classList.add('d-none');
                        fullText.classList.remove('d-none');
                        e.target.textContent = 'Show less';
                    } else {
                        shortText.classList.remove('d-none');
                        fullText.classList.add('d-none');
                        e.target.textContent = 'Show more';
                    }
                }
            });

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