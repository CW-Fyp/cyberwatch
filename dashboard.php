<?php
session_start();
require_once "config.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$username = $_SESSION["username"];

// Enhanced Statistics with Error Handling
$stats = [
    'total_reports' => 0,
    'pending_reports' => 0,
    'resolved_reports' => 0,
    'in_review_reports' => 0
];

try {
    // Total Reports
    $sql_total = "SELECT COUNT(report_id) AS total FROM reports WHERE user_id = ?";
    if($stmt_total = $conn->prepare($sql_total)) {
        $stmt_total->bind_param("i", $user_id);
        if($stmt_total->execute()) {
            $result_total = $stmt_total->get_result();
            if($result_total) {
                $row_total = $result_total->fetch_assoc();
                $stats['total_reports'] = $row_total['total'];
            }
        }
        $stmt_total->close();
    }

    // Pending Reports
    $sql_pending = "SELECT COUNT(report_id) AS pending FROM reports WHERE user_id = ? AND status = 'pending'";
    if($stmt_pending = $conn->prepare($sql_pending)) {
        $stmt_pending->bind_param("i", $user_id);
        if($stmt_pending->execute()) {
            $result_pending = $stmt_pending->get_result();
            if($result_pending) {
                $row_pending = $result_pending->fetch_assoc();
                $stats['pending_reports'] = $row_pending['pending'];
            }
        }
        $stmt_pending->close();
    }

    // Resolved Reports
    $sql_resolved = "SELECT COUNT(report_id) AS resolved FROM reports WHERE user_id = ? AND status = 'resolved'";
    if($stmt_resolved = $conn->prepare($sql_resolved)) {
        $stmt_resolved->bind_param("i", $user_id);
        if($stmt_resolved->execute()) {
            $result_resolved = $stmt_resolved->get_result();
            if($result_resolved) {
                $row_resolved = $result_resolved->fetch_assoc();
                $stats['resolved_reports'] = $row_resolved['resolved'];
            }
        }
        $stmt_resolved->close();
    }

    // In Review Reports
    $sql_review = "SELECT COUNT(report_id) AS in_review FROM reports WHERE user_id = ? AND status = 'in_review'";
    if($stmt_review = $conn->prepare($sql_review)) {
        $stmt_review->bind_param("i", $user_id);
        if($stmt_review->execute()) {
            $result_review = $stmt_review->get_result();
            if($result_review) {
                $row_review = $result_review->fetch_assoc();
                $stats['in_review_reports'] = $row_review['in_review'];
            }
        }
        $stmt_review->close();
    }

} catch (Exception $e) {
    error_log("Dashboard statistics error: " . $e->getMessage());
}

// Recent Reports with Enhanced Data
$my_reports = [];
$sql_myreports = "SELECT report_id, report_details, date_reported, status, priority 
                  FROM reports WHERE user_id = ? 
                  ORDER BY date_reported DESC LIMIT 5";
                  
try {
    if($stmt_myreports = $conn->prepare($sql_myreports)) {
        $stmt_myreports->bind_param("i", $user_id);
        if($stmt_myreports->execute()) {
            $result_myreports = $stmt_myreports->get_result();
            while ($row = $result_myreports->fetch_assoc()) {
                $my_reports[] = $row;
            }
        }
        $stmt_myreports->close();
    }
} catch (Exception $e) {
    error_log("Recent reports fetch error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CyberWatch</title>
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

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            background: var(--gradient);
            color: white;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-label {
            color: #64748b;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .stat-trend {
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 10px;
        }

        /* Action Cards */
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
        .badge-high { background: #fee2e2; color: #dc2626; }
        .badge-medium { background: #fef3c7; color: #d97706; }
        .badge-low { background: #dbeafe; color: #1e40af; }

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

        /* Pulse Animation */
        .pulse-button {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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

        /* Report Details Styles */
        .report-details {
            max-width: 300px;
        }

        .short-text, .full-text {
            display: inline;
        }

        .toggle-details {
            font-size: 12px;
            cursor: pointer;
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
                        <a class="nav-link active" href="dashboard.php">
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
                            <h4 class="mb-0 text-dark fw-bold">Dashboard Overview</h4>
                            <small class="text-muted">Welcome back, <?php echo htmlspecialchars($username); ?>! 👋</small>
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

                <!-- Dashboard Content -->
                <div class="container-fluid py-4">
                    <!-- Stats Grid -->
                    <div class="row g-4 mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.1s">
                                <div class="stat-icon">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <div class="stat-number"><?php echo $stats['total_reports']; ?></div>
                                <div class="stat-label">Total Reports</div>
                                <div class="stat-trend text-success">
                                    <i class="fas fa-chart-line me-1"></i> All time
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.2s">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-number"><?php echo $stats['pending_reports']; ?></div>
                                <div class="stat-label">Pending Reports</div>
                                <div class="stat-trend text-warning">
                                    <i class="fas fa-exclamation-circle me-1"></i> Needs attention
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.3s">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-number"><?php echo $stats['resolved_reports']; ?></div>
                                <div class="stat-label">Resolved Reports</div>
                                <div class="stat-trend text-success">
                                    <i class="fas fa-check me-1"></i> Completed
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.4s">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-number">
                                    <?php echo $stats['total_reports'] > 0 ? round(($stats['resolved_reports'] / $stats['total_reports']) * 100) : 0; ?>%
                                </div>
                                <div class="stat-label">Resolution Rate</div>
                                <div class="stat-trend text-info">
                                    <i class="fas fa-trend-up me-1"></i> Success rate
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Recent Activity -->
                    <div class="row g-4">
                        <!-- Quick Actions -->
                        <div class="col-lg-4">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.5s">
                                <h5 class="fw-bold mb-4">Quick Actions</h5>
                                <div class="d-grid gap-3">
                                    <a href="report.php" class="action-card">
                                        <div class="action-icon">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                        <h6>Submit New Report</h6>
                                        <p class="text-muted mb-0 small">Report cyberbullying incidents securely and anonymously</p>
                                    </a>
                                    
                                    <a href="my_reports.php" class="action-card">
                                        <div class="action-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <h6>View My Reports</h6>
                                        <p class="text-muted mb-0 small">Check status of your submissions and track progress</p>
                                    </a>
                                    
                                    <a href="infographic.php" class="action-card">
                                        <div class="action-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                        <h6>View Statistics</h6>
                                        <p class="text-muted mb-0 small">Explore cyberbullying trends and insights</p>
                                    </a>
                                </div>
                            </div>

                            <!-- System Status -->
                            <div class="stat-card mt-4 animate-slide-in" style="animation-delay: 0.6s">
                                <h5 class="fw-bold mb-4">Security Status</h5>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Platform Security</span>
                                    <span class="badge bg-success">Protected</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Data Encryption</span>
                                    <span class="badge bg-success">Active</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Response Time</span>
                                    <span class="badge bg-info">< 24h</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Last Activity</span>
                                    <span class="text-muted">Just now</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="col-lg-8">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.7s">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="fw-bold mb-0">Recent Reports</h5>
                                    <a href="my_reports.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View All
                                    </a>
                                </div>
                                
                                <?php if (!empty($my_reports)): ?>
                                    <div class="table-container">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Report ID</th>
                                                        <th>Date Submitted</th>
                                                        <th>Priority</th>
                                                        <th>Status</th>
                                                        <th>Details</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($my_reports as $report): ?>
                                                        <tr>
                                                            <td><strong>#<?php echo $report['report_id']; ?></strong></td>
                                                            <td>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-calendar me-1"></i>
                                                                    <?php echo date("d M Y, H:i", strtotime($report['date_reported'])); ?>
                                                                </small>
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                    $priority_class = 'badge-low';
                                                                    $priority_icon = 'arrow-down';
                                                                    if ($report['priority'] == 'high') {
                                                                        $priority_class = 'badge-high';
                                                                        $priority_icon = 'arrow-up';
                                                                    } elseif ($report['priority'] == 'medium') {
                                                                        $priority_class = 'badge-medium';
                                                                        $priority_icon = 'minus';
                                                                    }
                                                                ?>
                                                                <span class="badge <?php echo $priority_class; ?>">
                                                                    <i class="fas fa-<?php echo $priority_icon; ?> me-1"></i>
                                                                    <?php echo ucfirst($report['priority'] ?? 'low'); ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                    $status_class = 'badge-pending';
                                                                    $status_icon = 'clock';
                                                                    if ($report['status'] == 'in_review') {
                                                                        $status_class = 'badge-in-review';
                                                                        $status_icon = 'search';
                                                                    }
                                                                    if ($report['status'] == 'resolved') {
                                                                        $status_class = 'badge-resolved';
                                                                        $status_icon = 'check';
                                                                    }
                                                                ?>
                                                                <span class="badge <?php echo $status_class; ?>">
                                                                    <i class="fas fa-<?php echo $status_icon; ?> me-1"></i>
                                                                    <?php echo ucfirst($report['status']); ?>
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="report-details">
                                                                    <?php 
                                                                        $snippet = htmlspecialchars($report['report_details']);
                                                                        if (strlen($snippet) > 100) {
                                                                            $snippet = substr($snippet, 0, 100) . '...';
                                                                            echo '<span class="short-text">' . $snippet . '</span>';
                                                                            echo '<span class="full-text d-none">' . htmlspecialchars($report['report_details']) . '</span>';
                                                                            echo ' <a href="#" class="text-primary toggle-details" style="font-size: 12px;">Show more</a>';
                                                                        } else {
                                                                            echo $snippet;
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <button class="btn btn-outline-primary view-report" 
                                                                            data-id="<?php echo $report['report_id']; ?>"
                                                                            data-details="<?php echo htmlspecialchars($report['report_details']); ?>"
                                                                            data-status="<?php echo $report['status']; ?>"
                                                                            data-priority="<?php echo $report['priority']; ?>"
                                                                            data-date="<?php echo $report['date_reported']; ?>">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <div class="mb-4">
                                            <i class="fas fa-clipboard-list fa-4x text-muted opacity-50"></i>
                                        </div>
                                        <h5 class="text-muted mb-3">No Reports Yet</h5>
                                        <p class="text-muted mb-4">Start by submitting your first cyberbullying report</p>
                                        <a href="report.php" class="btn btn-primary pulse-button">
                                            <i class="fas fa-plus me-2"></i>Submit First Report
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
                                <strong>Date Submitted:</strong>
                                <span id="modalReportDate" class="ms-2"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Priority:</strong>
                                <span id="modalReportPriority" class="ms-2"></span>
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <span id="modalReportStatus" class="ms-2"></span>
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
        // Enhanced Animated Counter
        function animateCounter(element, target, duration = 1000) {
            let current = 0;
            const increment = target / (duration / 30);
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current).toLocaleString();
                }
            }, 30);
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Animate statistics counters
            animateCounter(document.querySelector('.stat-card:nth-child(1) .stat-number'), <?php echo $stats['total_reports']; ?>);
            animateCounter(document.querySelector('.stat-card:nth-child(2) .stat-number'), <?php echo $stats['pending_reports']; ?>);
            animateCounter(document.querySelector('.stat-card:nth-child(3) .stat-number'), <?php echo $stats['resolved_reports']; ?>);

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

            // Mobile sidebar toggle
            const sidebarToggle = document.createElement('button');
            sidebarToggle.className = 'btn btn-primary d-lg-none position-fixed';
            sidebarToggle.style.cssText = 'bottom: 20px; right: 20px; z-index: 1001; border-radius: 50%; width: 60px; height: 60px;';
            sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            document.body.appendChild(sidebarToggle);

            sidebarToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('show');
            });

            // Report details toggle
            document.querySelectorAll('.toggle-details').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const detailsDiv = this.closest('.report-details');
                    const shortText = detailsDiv.querySelector('.short-text');
                    const fullText = detailsDiv.querySelector('.full-text');
                    
                    if (shortText.classList.contains('d-none')) {
                        shortText.classList.remove('d-none');
                        fullText.classList.add('d-none');
                        this.textContent = 'Show more';
                    } else {
                        shortText.classList.add('d-none');
                        fullText.classList.remove('d-none');
                        this.textContent = 'Show less';
                    }
                });
            });

            // Report modal functionality
            const reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
            document.querySelectorAll('.view-report').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('modalReportId').textContent = '#' + this.dataset.id;
                    document.getElementById('modalReportDate').textContent = 
                        new Date(this.dataset.date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    document.getElementById('modalReportDetails').textContent = this.dataset.details;
                    
                    // Set priority with appropriate badge
                    const prioritySpan = document.getElementById('modalReportPriority');
                    let priorityClass = 'badge-low';
                    let priorityIcon = 'arrow-down';
                    
                    if (this.dataset.priority === 'high') {
                        priorityClass = 'badge-high';
                        priorityIcon = 'arrow-up';
                    } else if (this.dataset.priority === 'medium') {
                        priorityClass = 'badge-medium';
                        priorityIcon = 'minus';
                    }
                    
                    prioritySpan.innerHTML = 
                        `<span class="badge ${priorityClass}">
                            <i class="fas fa-${priorityIcon} me-1"></i>
                            ${this.dataset.priority.charAt(0).toUpperCase() + this.dataset.priority.slice(1)}
                        </span>`;
                    
                    // Set status with appropriate badge
                    const statusSpan = document.getElementById('modalReportStatus');
                    let statusClass = 'badge-pending';
                    let statusIcon = 'clock';
                    
                    if (this.dataset.status === 'in_review') {
                        statusClass = 'badge-in-review';
                        statusIcon = 'search';
                    }
                    if (this.dataset.status === 'resolved') {
                        statusClass = 'badge-resolved';
                        statusIcon = 'check';
                    }
                    
                    statusSpan.innerHTML = 
                        `<span class="badge ${statusClass}">
                            <i class="fas fa-${statusIcon} me-1"></i>
                            ${this.dataset.status.charAt(0).toUpperCase() + this.dataset.status.slice(1)}
                        </span>`;
                    
                    reportModal.show();
                });
            });
        });
    </script>
</body>
</html>

<?php 
include "footer.php"; // Include your main public footer
?>