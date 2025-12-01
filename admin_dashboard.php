<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login.php");
    exit;
}
amir hensem

$admin_username = $_SESSION["admin_username"];

// Get admin stats
$stats = [
    'total_reports' => 0,
    'pending_reports' => 0,
    'resolved_reports' => 0,
    'in_review_reports' => 0,
    'total_users' => 0,
    'reports_today' => 0
];

try {
    // Total Reports
    $sql_total = "SELECT COUNT(report_id) AS total FROM reports";
    $result_total = $conn->query($sql_total);
    if ($result_total) {
        $row_total = $result_total->fetch_assoc();
        $stats['total_reports'] = $row_total['total'];
    }

    // Pending Reports
    $sql_pending = "SELECT COUNT(report_id) AS pending FROM reports WHERE status = 'pending'";
    $result_pending = $conn->query($sql_pending);
    if ($result_pending) {
        $row_pending = $result_pending->fetch_assoc();
        $stats['pending_reports'] = $row_pending['pending'];
    }

    // Resolved Reports
    $sql_resolved = "SELECT COUNT(report_id) AS resolved FROM reports WHERE status = 'resolved'";
    $result_resolved = $conn->query($sql_resolved);
    if ($result_resolved) {
        $row_resolved = $result_resolved->fetch_assoc();
        $stats['resolved_reports'] = $row_resolved['resolved'];
    }

    // In Review Reports
    $sql_review = "SELECT COUNT(report_id) AS in_review FROM reports WHERE status = 'in_review'";
    $result_review = $conn->query($sql_review);
    if ($result_review) {
        $row_review = $result_review->fetch_assoc();
        $stats['in_review_reports'] = $row_review['in_review'];
    }

    // Total Users
    $sql_users = "SELECT COUNT(id) AS total FROM users";
    $result_users = $conn->query($sql_users);
    if ($result_users) {
        $row_users = $result_users->fetch_assoc();
        $stats['total_users'] = $row_users['total'];
    }

    // Reports Today
    $sql_today = "SELECT COUNT(report_id) AS today FROM reports WHERE DATE(date_reported) = CURDATE()";
    $result_today = $conn->query($sql_today);
    if ($result_today) {
        $row_today = $result_today->fetch_assoc();
        $stats['reports_today'] = $row_today['today'];
    }

} catch (Exception $e) {
    error_log("Admin dashboard stats error: " . $e->getMessage());
}

// Get recent reports for admin
$recent_reports = [];
$sql_reports = "SELECT r.report_id, r.user_id, u.username, r.report_details, r.date_reported, 
                       r.status, r.priority, r.category 
                FROM reports r 
                LEFT JOIN users u ON r.user_id = u.id 
                ORDER BY r.date_reported DESC 
                LIMIT 10";
try {
    $result_reports = $conn->query($sql_reports);
    while ($row = $result_reports->fetch_assoc()) {
        $recent_reports[] = $row;
    }
} catch (Exception $e) {
    error_log("Recent reports fetch error: " . $e->getMessage());
}

// Get recent users
$recent_users = [];
$sql_users = "SELECT id, username, email, created_at, last_login 
              FROM users 
              ORDER BY created_at DESC 
              LIMIT 5";
try {
    $result_users = $conn->query($sql_users);
    while ($row = $result_users->fetch_assoc()) {
        $recent_users[] = $row;
    }
} catch (Exception $e) {
    error_log("Recent users fetch error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CyberWatch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

 /* Smooth scroll for recent users */
    .recent-users-container::-webkit-scrollbar {
        width: 4px;
    }
    
    .recent-users-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .recent-users-container::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 10px;
    }
    
    .recent-users-container::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    
    /* Ensure footer stays at bottom */
    .main-content {
        min-height: calc(100vh - 200px); /* Adjust based on your header/footer height */
        padding-bottom: 80px; /* Space before footer */
    }
    
    /* Responsive adjustments */
    @media (max-height: 768px) {
        .recent-users-container {
            max-height: 220px !important;
        }
    }
    
    @media (max-height: 600px) {
        .recent-users-container {
            max-height: 180px !important;
        }
    }

    
        footer {
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content-box {
                max-height: 60vh;
                padding: 15px;
            }
            
            .number-list {
                grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
                gap: 8px;
            }
        }

        @media (max-width: 480px) {
            .content-box {
                max-height: 50vh;
                padding: 10px;
            }
            
            .number-list {
                grid-template-columns: repeat(auto-fill, minmax(35px, 1fr));
                gap: 5px;
            }
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

        /* Charts */
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            height: 100%;
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table th {
            background: var(--gradient);
            color: white;
            border: none;
            padding: 20px;
            font-weight: 600;
        }

        .table td {
            padding: 15px 20px;
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

        /* Quick Actions */
        .quick-action-btn {
            padding: 8px 12px;
            border-radius: 8px;
            border: none;
            background: var(--gradient);
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
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

<div class="container">
        <div class="content-box">
            <ul class="number-list">
            
            </ul>
        </div>
    </div>



    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 sidebar">
                <div class="sidebar-brand">
                    <div class="brand-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 class="text-white mb-1">CyberWatch</h5>
                    <small class="text-white-50">Admin Portal</small>
                </div>
                
                <nav class="nav flex-column mt-4">
                    <div class="nav-item">
                        <a class="nav-link active" href="admin_dashboard.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link" href="admin_reports.php">
                            <i class="fas fa-clipboard-list"></i>
                            <span>Reports</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link" href="admin_users.php">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link" href="admin_analytics.php">
                            <i class="fas fa-chart-pie"></i>
                            <span>Analytics</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link" href="admin_settings.php">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                    
                    <div class="nav-item mt-4">
                        <a class="nav-link text-danger" href="admin_logout.php">
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
                            <h4 class="mb-0 text-dark fw-bold">Admin Dashboard</h4>
                            <small class="text-muted">Welcome back, <?php echo htmlspecialchars($admin_username); ?>! 👑</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">
                                <?php echo strtoupper(substr($admin_username, 0, 1)); ?>
                            </div>
                            <div>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($admin_username); ?></div>
                                <small class="text-muted">Administrator</small>
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
                                <div class="stat-number" data-stat="total_reports"><?php echo $stats['total_reports']; ?></div>
                                <div class="stat-label">Total Reports</div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-muted small">Today: <?php echo $stats['reports_today']; ?></span>
                                    <a href="admin_reports.php" class="text-primary small">View All</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.2s">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-number" data-stat="pending_reports"><?php echo $stats['pending_reports']; ?></div>
                                <div class="stat-label">Pending Reports</div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-warning small">Needs attention</span>
                                    <button class="btn btn-sm btn-warning" onclick="filterReports('pending')">Review</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.3s">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-number" data-stat="resolved_reports"><?php echo $stats['resolved_reports']; ?></div>
                                <div class="stat-label">Resolved Reports</div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-success small">Completed</span>
                                    <span class="text-muted small"><?php echo $stats['total_reports'] > 0 ? round(($stats['resolved_reports'] / $stats['total_reports']) * 100) : 0; ?>% success</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.4s">
                                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-number" data-stat="total_users"><?php echo $stats['total_users']; ?></div>
                                <div class="stat-label">Total Users</div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-muted small">Active users</span>
                                    <a href="admin_users.php" class="text-primary small">Manage</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts & Tables -->
                    <div class="row g-4">
                        <!-- Charts -->
                        <div class="col-lg-8">
                            <div class="row g-4">
                                <!-- Reports Trend -->
                                <div class="col-lg-6">
                                    <div class="chart-container animate-slide-in" style="animation-delay: 0.5s">
                                        <h5 class="fw-bold mb-4">Reports Trend</h5>
                                        <div style="height: 300px;">
                                            <canvas id="reportsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Distribution Chart -->
                                <div class="col-lg-6">
                                    <div class="chart-container animate-slide-in" style="animation-delay: 0.6s">
                                        <h5 class="fw-bold mb-4">Reports Distribution</h5>
                                        <div style="height: 300px;">
                                            <canvas id="distributionChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Recent Reports -->
                                <div class="col-12">
                                    <div class="chart-container animate-slide-in" style="animation-delay: 0.7s">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h5 class="fw-bold mb-0">Recent Reports</h5>
                                            <button class="btn btn-primary btn-sm" id="exportReports">
                                                <i class="fas fa-download me-1"></i>Export
                                            </button>
                                        </div>
                                        
                                       
                                        <?php if (!empty($recent_reports)): ?>
                                         
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>User</th>
                                                            <th>Date</th>
                                                            <th>Priority</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($recent_reports as $report): ?>
                                                            <tr>
                                                                <td><strong>#<?php echo $report['report_id']; ?></strong></td>
                                                                <td><?php echo htmlspecialchars($report['username']); ?></td>
                                                                <td><?php echo date("d M Y", strtotime($report['date_reported'])); ?></td>
                                                                <td>
                                                                    <span class="badge <?php echo $report['priority'] == 'high' ? 'badge-high' : ($report['priority'] == 'medium' ? 'badge-medium' : 'badge-low'); ?>">
                                                                        <?php echo ucfirst($report['priority']); ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="badge <?php echo $report['status'] == 'pending' ? 'badge-pending' : ($report['status'] == 'in_review' ? 'badge-in-review' : 'badge-resolved'); ?>">
                                                                        <?php echo ucfirst($report['status']); ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <button class="btn btn-outline-primary view-report" 
                                                                                data-id="<?php echo $report['report_id']; ?>"
                                                                                data-user="<?php echo htmlspecialchars($report['username']); ?>"
                                                                                data-date="<?php echo $report['date_reported']; ?>"
                                                                                data-status="<?php echo $report['status']; ?>"
                                                                                data-details="<?php echo htmlspecialchars(substr($report['report_details'], 0, 100)); ?>">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                        <button class="btn btn-outline-success quick-action" 
                                                                                data-action="resolve" 
                                                                                data-id="<?php echo $report['report_id']; ?>">
                                                                            <i class="fas fa-check"></i>
                                                                        </button>
                                                                        <button class="btn btn-outline-warning quick-action" 
                                                                                data-action="review" 
                                                                                data-id="<?php echo $report['report_id']; ?>">
                                                                            <i class="fas fa-search"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center py-4">
                                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No reports yet</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar Stats & Recent Users -->
                        <div class="col-lg-4">
                            <!-- Quick Actions -->
                            <div class="stat-card animate-slide-in" style="animation-delay: 0.8s">
                                <h5 class="fw-bold mb-4">Quick Actions</h5>
                                <div class="d-grid gap-3">
                                    <button class="btn btn-primary btn-lg" onclick="window.location.href='admin_reports.php'">
                                        <i class="fas fa-list me-2"></i>View All Reports
                                    </button>
                                    <button class="btn btn-success btn-lg" onclick="window.location.href='admin_users.php'">
                                        <i class="fas fa-user-plus me-2"></i>Add New User
                                    </button>
                                    <button class="btn btn-info btn-lg" onclick="showAnalytics()">
                                        <i class="fas fa-chart-bar me-2"></i>View Analytics
                                    </button>
                                </div>
                            </div>

                          <!-- Recent Users -->
                        <div class="stat-card mt-4 animate-slide-in" style="animation-delay: 0.9s">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Recent Users</h5>
                        <a href="users.php" class="text-primary small">View All</a>
                        </div>
        <?php if (!empty($recent_users)): ?>
                        <div class="list-group list-group-flush recent-users-container" style="max-height: 280px; overflow-y: auto;">
            <?php foreach ($recent_users as $user): ?>
                        <div class="list-group-item border-0 px-0 py-2">
                        <div class="d-flex align-items-center">
                        <div class="user-avatar d-flex align-items-center justify-content-center" 
                             style="width: 36px; height: 36px; font-size: 0.8rem; background-color: #e9ecef; border-radius: 50%;">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="fw-bold small"><?php echo htmlspecialchars($user['username']); ?></div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                Joined <?php echo date("M j, Y", strtotime($user['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-3">
            <i class="fas fa-users fa-lg text-muted mb-2"></i>
            <p class="text-muted small mb-0">No users yet</p>
        </div>
        <div>
      
        </div>
    <?php endif; ?>
</div>

<!-- System Status -->
<div class="stat-card mt-4 animate-slide-in mb-4" style="animation-delay: 1s">
    <h5 class="fw-bold mb-4">System Status</h5>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="small">Platform Security</span>
        <span class="badge bg-success">Protected</span>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="small">Database</span>
        <span class="badge bg-success">Online</span>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="small">Reports Queue</span>
        <span class="badge bg-info"><?php echo $stats['pending_reports']; ?> pending</span>
    </div>
    <div class="d-flex justify-content-between align-items-center">
        <span class="small">Last Update</span>
        <span class="text-muted small">Just now</span>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Details Modal -->
    <div class="modal fade" id="reportDetailModal" tabindex="-1">
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
                                <strong>Submitted By:</strong>
                                <span id="modalReportUser" class="ms-2"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Date Submitted:</strong>
                                <span id="modalReportDate" class="ms-2"></span>
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
                    <button type="button" class="btn btn-primary" onclick="updateReportStatus()">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Admin Dashboard Functionality
        class AdminDashboard {
            constructor() {
                this.initCharts();
                this.initEventListeners();
                this.initRealTimeUpdates();
            }
            
            initCharts() {
                // Reports Trend Chart
                this.reportsChart = new Chart(document.getElementById('reportsChart'), {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Total Reports',
                            data: [65, 59, 80, 81, 56, 55],
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
                
                // Distribution Chart
                this.distributionChart = new Chart(document.getElementById('distributionChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'In Review', 'Resolved'],
                        datasets: [{
                            data: [
                                <?php echo $stats['pending_reports']; ?>,
                                <?php echo $stats['in_review_reports']; ?>,
                                <?php echo $stats['resolved_reports']; ?>
                            ],
                            backgroundColor: ['#f59e0b', '#06b6d4', '#10b981'],
                            hoverBackgroundColor: ['#fbbf24', '#22d3ee', '#34d399']
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        cutout: '70%'
                    }
                });
            }
            
            initEventListeners() {
                // Export functionality
                document.getElementById('exportReports').addEventListener('click', () => {
                    this.exportData();
                });
                
                // Quick actions
                document.querySelectorAll('.quick-action').forEach(button => {
                    button.addEventListener('click', (e) => {
                        const action = e.currentTarget.dataset.action;
                        const reportId = e.currentTarget.dataset.id;
                        this.performQuickAction(action, reportId);
                    });
                });
                
                // View report details
                document.querySelectorAll('.view-report').forEach(button => {
                    button.addEventListener('click', (e) => {
                        this.showReportDetails(e.currentTarget.dataset);
                    });
                });
            }
            
            async exportData() {
                const exportBtn = document.getElementById('exportReports');
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Exporting...';
                exportBtn.disabled = true;
                
                try {
                    // Simulate export
                    await new Promise(resolve => setTimeout(resolve, 1500));
                    
                    // Create CSV content
                    const csvContent = "data:text/csv;charset=utf-8," 
                        + "ID,User,Date,Priority,Status,Details\n"
                        + <?php 
                            $csvRows = [];
                            foreach ($recent_reports as $report) {
                                $csvRows[] = '"#' . $report['report_id'] . '","' . 
                                            htmlspecialchars($report['username']) . '","' . 
                                            date("Y-m-d", strtotime($report['date_reported'])) . '","' . 
                                            $report['priority'] . '","' . 
                                            $report['status'] . '","' . 
                                            htmlspecialchars(substr($report['report_details'], 0, 50)) . '"';
                            }
                            echo json_encode(implode("\\n", $csvRows));
                        ?>;
                    
                    const encodedUri = encodeURI(csvContent);
                    const link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", "cyberwatch-reports-" + new Date().toISOString().split('T')[0] + ".csv");
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    this.showNotification('Reports exported successfully!', 'success');
                } catch (error) {
                    console.error('Export failed:', error);
                    this.showNotification('Export failed. Please try again.', 'error');
                } finally {
                    exportBtn.innerHTML = '<i class="fas fa-download me-1"></i>Export';
                    exportBtn.disabled = false;
                }
            }
            
            async performQuickAction(action, reportId) {
                try {
                    const response = await fetch('admin_quick_action.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: action,
                            report_id: reportId
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        this.showNotification('Action completed successfully', 'success');
                        // Refresh the page after 1 second
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        this.showNotification('Action failed: ' + result.message, 'error');
                    }
                } catch (error) {
                    this.showNotification('Action failed. Please try again.', 'error');
                }
            }
            
            showReportDetails(reportData) {
                const modal = new bootstrap.Modal(document.getElementById('reportDetailModal'));
                document.getElementById('modalReportId').textContent = '#' + reportData.id;
                document.getElementById('modalReportUser').textContent = reportData.user;
                document.getElementById('modalReportDate').textContent = new Date(reportData.date).toLocaleString();
                document.getElementById('modalReportStatus').textContent = reportData.status;
                document.getElementById('modalReportDetails').textContent = reportData.details;
                modal.show();
            }
            
            showNotification(message, type = 'info') {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
                alert.style.cssText = 'top: 20px; right: 20px; z-index: 1060; min-width: 300px;';
                alert.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                setTimeout(() => alert.remove(), 5000);
            }
            
            initRealTimeUpdates() {
                // Update stats every 30 seconds
                setInterval(() => {
                    this.updateDashboardStats();
                }, 30000);
            }
            
            async updateDashboardStats() {
                try {
                    // Simulate API call - replace with actual API endpoint
                    await new Promise(resolve => setTimeout(resolve, 1000));
                    
                    // For now, just show a notification
                    this.showNotification('Dashboard stats updated', 'info');
                } catch (error) {
                    console.error('Failed to update stats:', error);
                }
            }
        }

        // Initialize dashboard when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new AdminDashboard();
        });

        // Helper functions
        function filterReports(status) {
            window.location.href = `admin_reports.php?status=${status}`;
        }

        function showAnalytics() {
            window.location.href = 'admin_analytics.php';
        }

        function updateReportStatus() {
            alert('Update status functionality would go here');
            // Implement status update logic
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
        });
    </script>
</body>
</html>

<?php include "footer.php"; // Include your main public footer ?>