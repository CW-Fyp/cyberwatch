//admin_dashboard.js


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
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
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
                    data: [30, 25, 45],
                    backgroundColor: ['#f6c23e', '#36b9cc', '#1cc88a'],
                    hoverBackgroundColor: ['#f8d36c', '#5ac6d4', '#3dd5a0']
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
        exportBtn.classList.add('loading');
        
        try {
            const response = await fetch('admin_export.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    type: 'reports',
                    format: 'csv'
                })
            });
            
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `cyberwatch-reports-${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }
        } catch (error) {
            console.error('Export failed:', error);
            alert('Export failed. Please try again.');
        } finally {
            exportBtn.classList.remove('loading');
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
                // Refresh the row or table
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showNotification('Action failed: ' + result.message, 'error');
            }
        } catch (error) {
            this.showNotification('Action failed. Please try again.', 'error');
        }
    }
    
    showReportDetails(reportData) {
        // Create and show a modal with report details
        const modal = new bootstrap.Modal(document.getElementById('reportDetailModal'));
        document.getElementById('modalReportId').textContent = reportData.id;
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
        // Simulate real-time updates
        setInterval(() => {
            this.updateDashboardStats();
        }, 30000); // Update every 30 seconds
    }
    
    async updateDashboardStats() {
        try {
            const response = await fetch('admin_stats.php');
            const stats = await response.json();
            this.updateStatsDisplay(stats);
        } catch (error) {
            console.error('Failed to update stats:', error);
        }
    }
    
    updateStatsDisplay(stats) {
        // Update the stats cards with new data
        Object.keys(stats).forEach(stat => {
            const element = document.querySelector(`[data-stat="${stat}"]`);
            if (element) {
                element.textContent = stats[stat];
            }
        });
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new AdminDashboard();
});