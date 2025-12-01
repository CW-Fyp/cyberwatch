<?php
function verifyAdminAccess() {
    session_start();
    
    // Check if user is logged in and is admin
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php");
        exit();
    }
    
    // Check if user has admin role or is_admin flag
    $is_admin = ($_SESSION["role"] === 'admin' || (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1));
    
    if (!$is_admin) {
        header("location: dashboard.php");
        exit();
    }
    
    // Update admin session variables for backward compatibility
    $_SESSION["admin_logged_in"] = true;
    $_SESSION["admin_username"] = $_SESSION["username"];
    if (!isset($_SESSION["admin_last_activity"])) {
        $_SESSION["admin_last_activity"] = time();
    }
    
    // Check for session timeout (30 minutes)
    if (isset($_SESSION["admin_last_activity"]) && (time() - $_SESSION["admin_last_activity"] > 1800)) {
        session_unset();
        session_destroy();
        header("location: login.php?timeout=1");
        exit();
    }
    
    // Update last activity time
    $_SESSION["admin_last_activity"] = time();
}

function getDashboardStats() {
    require_once 'config.php';
    
    $stats = [
        'total_users' => 0,
        'active_sessions' => 0,
        'security_events' => 0,
        'system_alerts' => 0
    ];
    
    try {
        // Total Users
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $conn->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $stats['total_users'] = $row['total'];
        }
        
        // Active Sessions (users who logged in last 30 minutes)
        $sql = "SELECT COUNT(*) as active FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)";
        $result = $conn->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $stats['active_sessions'] = $row['active'];
        }
        
        // Security Events (last 24 hours)
        $sql = "SELECT COUNT(*) as events FROM security_logs WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
        $result = $conn->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $stats['security_events'] = $row['events'];
        }
        
        // System Alerts (high severity events)
        $sql = "SELECT COUNT(*) as alerts FROM security_logs WHERE severity = 'high' AND timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
        $result = $conn->query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            $stats['system_alerts'] = $row['alerts'];
        }
        
    } catch (Exception $e) {
        error_log("Dashboard stats error: " . $e->getMessage());
    }
    
    $conn->close();
    return $stats;
}

function getRecentActivity() {
    require_once 'config.php';
    
    $html = '';
    $sql = "SELECT admin_username, action, timestamp, ip_address, status 
            FROM admin_activity_log 
            ORDER BY timestamp DESC 
            LIMIT 10";
    
    try {
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $status_class = $row['status'] === 'success' ? 'badge-success' : 'badge-warning';
                $html .= "<tr>
                    <td>" . date('M j, Y H:i', strtotime($row['timestamp'])) . "</td>
                    <td>" . htmlspecialchars($row['admin_username']) . "</td>
                    <td>" . htmlspecialchars($row['action']) . "</td>
                    <td>" . htmlspecialchars($row['ip_address']) . "</td>
                    <td><span class='badge {$status_class}'>" . ucfirst($row['status']) . "</span></td>
                </tr>";
            }
        } else {
            $html = "<tr><td colspan='5' class='text-center text-muted'>No recent activity</td></tr>";
        }
    } catch (Exception $e) {
        error_log("Recent activity error: " . $e->getMessage());
        $html = "<tr><td colspan='5' class='text-center text-danger'>Error loading activity</td></tr>";
    }
    
    $conn->close();
    return $html;
}
?>