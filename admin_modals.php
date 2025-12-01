<?php
// admin_modals.php - Modal dialogs for admin dashboard
?>

<!-- User Management Modal -->
<div class="modal fade" id="userManagementModal" tabindex="-1" aria-labelledby="userManagementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userManagementModalLabel">
                    <i class="fas fa-users me-2"></i>User Management
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i>Add New User
                    </button>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportUsers()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="refreshUsers()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="usersTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo getUsersTable(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Add New User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="admin_actions.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_user">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="moderator">Moderator</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- System Settings Modal -->
<div class="modal fade" id="systemSettingsModal" tabindex="-1" aria-labelledby="systemSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="systemSettingsModalLabel">
                    <i class="fas fa-cog me-2"></i>System Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="admin_actions.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update_settings">
                    
                    <div class="mb-3">
                        <label class="form-label">Site Name</label>
                        <input type="text" class="form-control" name="site_name" value="<?php echo htmlspecialchars(getSetting('site_name')); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Maintenance Mode</label>
                        <select class="form-select" name="maintenance_mode">
                            <option value="0" <?php echo getSetting('maintenance_mode') == '0' ? 'selected' : ''; ?>>Disabled</option>
                            <option value="1" <?php echo getSetting('maintenance_mode') == '1' ? 'selected' : ''; ?>>Enabled</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Session Timeout (minutes)</label>
                        <input type="number" class="form-control" name="session_timeout" value="<?php echo htmlspecialchars(getSetting('session_timeout')); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Max Login Attempts</label>
                        <input type="number" class="form-control" name="max_login_attempts" value="<?php echo htmlspecialchars(getSetting('max_login_attempts')); ?>">
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="enable_registration" value="1" <?php echo getSetting('enable_registration') == '1' ? 'checked' : ''; ?>>
                        <label class="form-check-label">Enable User Registration</label>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="enable_2fa" value="1" <?php echo getSetting('enable_2fa') == '1' ? 'checked' : ''; ?>>
                        <label class="form-check-label">Enable Two-Factor Authentication</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Security Logs Modal -->
<div class="modal fade" id="securityLogsModal" tabindex="-1" aria-labelledby="securityLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="securityLogsModalLabel">
                    <i class="fas fa-clipboard-list me-2"></i>Security Logs
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <div class="btn-group">
                        <button class="btn btn-outline-danger btn-sm" onclick="clearSecurityLogs()">
                            <i class="fas fa-trash me-1"></i>Clear Logs
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="exportSecurityLogs()">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary btn-sm" onclick="refreshSecurityLogs()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="securityLogsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Timestamp</th>
                                <th>Event Type</th>
                                <th>User</th>
                                <th>IP Address</th>
                                <th>Description</th>
                                <th>Severity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo getSecurityLogsTable(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Backup Modal -->
<div class="modal fade" id="backupModal" tabindex="-1" aria-labelledby="backupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="backupModalLabel">
                    <i class="fas fa-database me-2"></i>Backup & Restore
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-download fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Create Backup</h5>
                                <p class="card-text">Create a full database backup</p>
                                <form action="admin_actions.php" method="POST">
                                    <input type="hidden" name="action" value="create_backup">
                                    <button type="submit" class="btn btn-info">Backup Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fas fa-upload fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Restore</h5>
                                <p class="card-text">Restore from backup file</p>
                                <form action="admin_actions.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="restore_backup">
                                    <div class="mb-3">
                                        <input type="file" class="form-control" name="backup_file" accept=".sql,.gz">
                                    </div>
                                    <button type="submit" class="btn btn-warning">Restore</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Recent Backups</h6>
                    <div class="list-group">
                        <?php echo getBackupList(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Modal -->
<div class="modal fade" id="quickStatsModal" tabindex="-1" aria-labelledby="quickStatsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="quickStatsModalLabel">
                    <i class="fas fa-chart-bar me-2"></i>Quick Statistics
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">User Statistics</h6>
                                <div id="userStatsPlaceholder" class="text-center text-muted">
                                    <i class="fas fa-chart-pie fa-3x mb-3"></i>
                                    <p>User statistics chart placeholder</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Security Events</h6>
                                <div id="securityStatsPlaceholder" class="text-center text-muted">
                                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                                    <p>Security events chart placeholder</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportUsers() {
    window.location.href = 'admin_actions.php?action=export_users';
}

function refreshUsers() {
    location.reload();
}

function clearSecurityLogs() {
    if (confirm('Are you sure you want to clear all security logs? This action cannot be undone.')) {
        window.location.href = 'admin_actions.php?action=clear_security_logs';
    }
}

function exportSecurityLogs() {
    window.location.href = 'admin_actions.php?action=export_security_logs';
}

function refreshSecurityLogs() {
    location.reload();
}
</script>