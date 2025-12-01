<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin_dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="sidebar-brand-text mx-3">CyberWatch <sup>Admin</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Items -->
    <li class="nav-item <?php echo $current_page == 'admin_dashboard.php' ? 'active' : ''; ?>">
        <a class="nav-link" href="admin_dashboard.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#userManagementModal">
            <i class="fas fa-fw fa-users"></i>
            <span>User Management</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#systemSettingsModal">
            <i class="fas fa-fw fa-cog"></i>
            <span>System Settings</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#securityLogsModal">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Security Logs</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#backupModal">
            <i class="fas fa-fw fa-database"></i>
            <span>Backup & Restore</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="admin_logs.php">
            <i class="fas fa-fw fa-history"></i>
            <span>Activity Logs</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->