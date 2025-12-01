<?php
session_start();
require_once 'admin_auth.php';
require_once 'admin_config.php';

verifyAdminAccess();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - CyberWatch Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Reuse sidebar from admin_dashboard.php -->
            <?php include('admin_sidebar.php'); ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><i class="fas fa-history me-2"></i>Activity Logs</h1>
                </div>

                <div class="card shadow">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">Admin Activity Log</h6>
                            <button class="btn btn-sm btn-outline-danger" onclick="clearActivityLogs()">
                                <i class="fas fa-trash me-1"></i>Clear Logs
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="activityLogsTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>Admin</th>
                                        <th>Action</th>
                                        <th>Description</th>
                                        <th>IP Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM admin_activity_log ORDER BY timestamp DESC");
                                    while ($log = $stmt->fetch()) {
                                        echo "<tr>
                                            <td>" . date('Y-m-d H:i:s', strtotime($log['timestamp'])) . "</td>
                                            <td>{$log['admin_username']}</td>
                                            <td>{$log['action']}</td>
                                            <td>{$log['description']}</td>
                                            <td>{$log['ip_address']}</td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#activityLogsTable').DataTable({
                "order": [[0, "desc"]],
                "pageLength": 25
            });
        });

        function clearActivityLogs() {
            if (confirm('Are you sure you want to clear all activity logs? This action cannot be undone.')) {
                window.location.href = 'admin_actions.php?action=clear_activity_logs';
            }
        }
    </script>
</body>
</html>