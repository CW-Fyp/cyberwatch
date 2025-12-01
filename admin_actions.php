<?php
session_start();
require_once 'admin_auth.php';
require_once 'admin_config.php';

verifyAdminAccess();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_user':
            addUser();
            break;
        case 'update_settings':
            updateSettings();
            break;
        case 'create_backup':
            createBackup();
            break;
        case 'restore_backup':
            restoreBackup();
            break;
        default:
            header('Location: admin_dashboard.php?error=Invalid action');
            exit();
    }
} else {
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'export_users':
            exportUsers();
            break;
        case 'export_security_logs':
            exportSecurityLogs();
            break;
        case 'clear_security_logs':
            clearSecurityLogs();
            break;
        default:
            header('Location: admin_dashboard.php?error=Invalid action');
            exit();
    }
}

function addUser() {
    global $pdo;
    
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $status = $_POST['status'] ?? 'active';
    
    if (empty($username) || empty($email) || empty($password)) {
        header('Location: admin_dashboard.php?error=All fields are required');
        exit();
    }
    
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$username, $email, $hashed_password, $role, $status]);
        
        // Log the action
        logAdminActivity($_SESSION['admin_username'], 'Add User', "Added user: $username");
        
        header('Location: admin_dashboard.php?success=User added successfully');
        exit();
        
    } catch (PDOException $e) {
        header('Location: admin_dashboard.php?error=Failed to add user: ' . $e->getMessage());
        exit();
    }
}

function updateSettings() {
    global $pdo;
    
    try {
        foreach ($_POST as $key => $value) {
            if ($key !== 'action') {
                $stmt = $pdo->prepare("UPDATE system_settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $key]);
            }
        }
        
        logAdminActivity($_SESSION['admin_username'], 'Update Settings', 'Updated system settings');
        
        header('Location: admin_dashboard.php?success=Settings updated successfully');
        exit();
        
    } catch (PDOException $e) {
        header('Location: admin_dashboard.php?error=Failed to update settings: ' . $e->getMessage());
        exit();
    }
}

function createBackup() {
    global $pdo;
    
    $backup_file = BACKUP_DIR . 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    
    // Ensure backup directory exists
    if (!is_dir(BACKUP_DIR)) {
        mkdir(BACKUP_DIR, 0755, true);
    }
    
    try {
        // Get all tables
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        
        $output = "";
        foreach ($tables as $table) {
            // Table structure
            $output .= "--\n-- Table structure for table `$table`\n--\n";
            $output .= "DROP TABLE IF EXISTS `$table`;\n";
            $create_table = $pdo->query("SHOW CREATE TABLE `$table`")->fetch();
            $output .= $create_table['Create Table'] . ";\n\n";
            
            // Table data
            $output .= "--\n-- Dumping data for table `$table`\n--\n";
            $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $output .= "INSERT INTO `$table` VALUES (";
                $values = [];
                foreach ($row as $value) {
                    $values[] = $pdo->quote($value);
                }
                $output .= implode(',', $values) . ");\n";
            }
            $output .= "\n";
        }
        
        // Write backup file
        file_put_contents($backup_file, $output);
        
        logAdminActivity($_SESSION['admin_username'], 'Create Backup', 'Created database backup');
        
        header('Location: admin_dashboard.php?success=Backup created successfully');
        exit();
        
    } catch (Exception $e) {
        header('Location: admin_dashboard.php?error=Backup failed: ' . $e->getMessage());
        exit();
    }
}

function exportUsers() {
    global $pdo;
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="users_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Add CSV headers
    fputcsv($output, ['ID', 'Username', 'Email', 'Role', 'Status', 'Last Login', 'Created At']);
    
    try {
        $stmt = $pdo->query("SELECT * FROM users ORDER BY id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, [
                $row['id'],
                $row['username'],
                $row['email'],
                $row['role'],
                $row['status'],
                $row['last_login'],
                $row['created_at']
            ]);
        }
        
        logAdminActivity($_SESSION['admin_username'], 'Export Users', 'Exported users to CSV');
        
    } catch (PDOException $e) {
        // Handle error
    }
    
    fclose($output);
    exit();
}

function clearSecurityLogs() {
    global $pdo;
    
    try {
        $pdo->exec("TRUNCATE TABLE security_logs");
        
        logAdminActivity($_SESSION['admin_username'], 'Clear Logs', 'Cleared all security logs');
        
        header('Location: admin_dashboard.php?success=Security logs cleared successfully');
        exit();
        
    } catch (PDOException $e) {
        header('Location: admin_dashboard.php?error=Failed to clear logs: ' . $e->getMessage());
        exit();
    }
}
?>