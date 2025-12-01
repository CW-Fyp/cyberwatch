<?php
class AdminAnalytics {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getPlatformStats() {
        $stats = [];
        
        // Total Reports
        $sql = "SELECT COUNT(*) as total FROM reports";
        $result = $this->conn->query($sql);
        $stats['total_reports'] = $result->fetch_assoc()['total'];
        
        // Reports Today
        $sql = "SELECT COUNT(*) as today FROM reports WHERE DATE(date_reported) = CURDATE()";
        $result = $this->conn->query($sql);
        $stats['reports_today'] = $result->fetch_assoc()['today'];
        
        // Pending Reports
        $sql = "SELECT COUNT(*) as pending FROM reports WHERE status = 'pending'";
        $result = $this->conn->query($sql);
        $stats['pending_reports'] = $result->fetch_assoc()['pending'];
        
        // Resolved Reports
        $sql = "SELECT COUNT(*) as resolved FROM reports WHERE status = 'resolved'";
        $result = $this->conn->query($sql);
        $stats['resolved_reports'] = $result->fetch_assoc()['resolved'];
        
        // Resolution Rate
        $stats['resolution_rate'] = $stats['total_reports'] > 0 ? 
            round(($stats['resolved_reports'] / $stats['total_reports']) * 100) : 0;
        
        // Total Users
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->conn->query($sql);
        $stats['total_users'] = $result->fetch_assoc()['total'];
        
        // Active Users (reported in last 30 days)
        $sql = "SELECT COUNT(DISTINCT user_id) as active FROM reports 
                WHERE date_reported >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $result = $this->conn->query($sql);
        $stats['active_users'] = $result->fetch_assoc()['active'];
        
        return $stats;
    }
    
    public function getRecentReports($limit = 10) {
        $sql = "SELECT r.report_id, r.user_id, r.report_details, r.date_reported, r.status, u.username 
                FROM reports r 
                JOIN users u ON r.user_id = u.id 
                ORDER BY r.date_reported DESC 
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getTopReporters($limit = 5) {
        $sql = "SELECT 
                    u.username,
                    COUNT(r.report_id) as report_count,
                    SUM(CASE WHEN r.status = 'resolved' THEN 1 ELSE 0 END) as resolved_count
                FROM users u
                JOIN reports r ON u.id = r.user_id
                GROUP BY u.id, u.username
                ORDER BY report_count DESC
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getReportTrends($days = 30) {
        $sql = "SELECT 
                    DATE(date_reported) as date,
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'in_review' THEN 1 ELSE 0 END) as in_review,
                    SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved
                FROM reports 
                WHERE date_reported >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(date_reported)
                ORDER BY date";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $days);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>