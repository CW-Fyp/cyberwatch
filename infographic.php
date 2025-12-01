<?php
require_once "config.php";

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$pageTitle = "Statistics - CyberWatch";
include "dashboard_header.php";
?>

<!-- Stats Grid -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card animate-slide-in" style="animation-delay: 0.1s">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number">42%</div>
            <div class="stat-label">Teens Experienced Cyberbullying</div>
            <div class="stat-trend text-warning">
                <i class="fas fa-chart-line me-1"></i> Critical Issue
            </div>
        </div>
    </div>
    


    <div class="col-xl-3 col-md-6">
        <div class="stat-card animate-slide-in" style="animation-delay: 0.2s">
            <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0e7490 100%);">
                <i class="fas fa-comments"></i>
            </div>
            <div class="stat-number">32%</div>
            <div class="stat-label">Offensive Name-Calling</div>
            <div class="stat-trend text-info">
                <i class="fas fa-exclamation-circle me-1"></i> Most Common
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card animate-slide-in" style="animation-delay: 0.3s">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <i class="fas fa-bullhorn"></i>
            </div>
            <div class="stat-number">22%</div>
            <div class="stat-label">False Rumors Spread</div>
            <div class="stat-trend text-danger">
                <i class="fas fa-chart-line me-1"></i> Serious Concern
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card animate-slide-in" style="animation-delay: 0.4s">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-number">67%</div>
            <div class="stat-label">Successfully Resolved</div>
            <div class="stat-trend text-success">
                <i class="fas fa-check me-1"></i> Positive Progress
            </div>
        </div>
    </div>
</div>





<!-- Charts Section -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="stat-card animate-slide-in" style="animation-delay: 0.5s">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-chart-line me-2 text-primary"></i>Cyberbullying Trends 2024
                </h5>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary active" data-period="monthly">Monthly</button>
                    <button class="btn btn-outline-primary" data-period="quarterly">Quarterly</button>
                    <button class="btn btn-outline-primary" data-period="yearly">Yearly</button>
                </div>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="trendsChart"></canvas>
            </div>
            
            <!-- Chart Statistics -->
            <div class="row mt-4 text-center">
                <div class="col-md-6">
                    <div class="border-end">
                        <h4 class="text-primary fw-bold" id="totalReports">725</h4>
                        <small class="text-muted">Total Reports This Year</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <h4 class="text-success fw-bold" id="resolutionRate">79%</h4>
                    <small class="text-muted">Average Resolution Rate</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="stat-card animate-slide-in" style="animation-delay: 0.6s">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">
                    <i class="fas fa-chart-pie me-2 text-success"></i>Platform Distribution
                </h5>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="distributionChart"></canvas>
            </div>
            
            <!-- Platform Stats -->
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small><i class="fas fa-circle text-primary me-2"></i>Social Media</small>
                    <small class="fw-bold">35%</small>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small><i class="fas fa-circle text-success me-2"></i>Messaging Apps</small>
                    <small class="fw-bold">25%</small>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small><i class="fas fa-circle text-warning me-2"></i>Gaming</small>
                    <small class="fw-bold">18%</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sample data - realistic cyberbullying statistics
    const monthlyData = {
        reports: [65, 59, 80, 81, 56, 55, 70, 75, 60, 65, 70, 68],
        resolved: [45, 42, 58, 62, 48, 40, 55, 60, 50, 55, 58, 62]
    };

    const quarterlyData = {
        reports: [204, 192, 205, 124],
        resolved: [145, 150, 165, 98]
    };

    const yearlyData = {
        reports: [625, 680, 720, 765],
        resolved: [480, 520, 580, 604]
    };

    let currentPeriod = 'monthly';
    let trendsChart;

    // Initialize Trends Chart
    function initTrendsChart(period = 'monthly') {
        const ctx = document.getElementById('trendsChart').getContext('2d');
        
        let labels, reportsData, resolvedData;
        
        switch(period) {
            case 'monthly':
                labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                reportsData = monthlyData.reports;
                resolvedData = monthlyData.resolved;
                break;
            case 'quarterly':
                labels = ['Q1', 'Q2', 'Q3', 'Q4'];
                reportsData = quarterlyData.reports;
                resolvedData = quarterlyData.resolved;
                break;
            case 'yearly':
                labels = ['2021', '2022', '2023', '2024'];
                reportsData = yearlyData.reports;
                resolvedData = yearlyData.resolved;
                break;
        }

        if (trendsChart) {
            trendsChart.destroy();
        }

        trendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Cyberbullying Reports',
                    data: reportsData,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3, // Reduced tension for less dramatic curves
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }, {
                    label: 'Resolved Cases',
                    data: resolvedData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3, // Reduced tension for less dramatic curves
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000, // Fixed animation duration
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 13,
                            weight: '600'
                        },
                        bodyFont: {
                            size: 12
                        },
                        padding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y} cases`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 100, // Fixed maximum value
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            stepSize: 20
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                elements: {
                    line: {
                        tension: 0.3 // Consistent tension
                    }
                }
            }
        });

        // Update statistics
        updateChartStats(period);
    }

    // Distribution Chart - Platform types
    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    const distributionChart = new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Social Media', 'Messaging Apps', 'Gaming', 'Email', 'Forums', 'Other'],
            datasets: [{
                data: [35, 25, 18, 12, 6, 4],
                backgroundColor: [
                    '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6b7280'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 10,
                    cornerRadius: 6,
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed}% of cases`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Update chart statistics
    function updateChartStats(period) {
        let totalReports, totalResolved, resolutionRate;
        
        switch(period) {
            case 'monthly':
                totalReports = monthlyData.reports.reduce((a, b) => a + b, 0);
                totalResolved = monthlyData.resolved.reduce((a, b) => a + b, 0);
                break;
            case 'quarterly':
                totalReports = quarterlyData.reports.reduce((a, b) => a + b, 0);
                totalResolved = quarterlyData.resolved.reduce((a, b) => a + b, 0);
                break;
            case 'yearly':
                totalReports = yearlyData.reports.reduce((a, b) => a + b, 0);
                totalResolved = yearlyData.resolved.reduce((a, b) => a + b, 0);
                break;
        }
        
        resolutionRate = Math.round((totalResolved / totalReports) * 100);
        
        // Update DOM elements
        document.getElementById('totalReports').textContent = totalReports;
        document.getElementById('resolutionRate').textContent = resolutionRate + '%';
    }

    // Period filter buttons functionality
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const period = this.dataset.period;
            
            // Remove active class from all buttons in the group
            this.parentElement.querySelectorAll('.btn').forEach(b => {
                b.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update chart with new period data
            initTrendsChart(period);
        });
    });

    // Initialize with monthly data
    initTrendsChart('monthly');
});
</script>

<!-- Information & Action Cards -->
<div class="row g-4">
    <!-- What is Cyberbullying -->
    <div class="col-lg-6">
        <div class="stat-card animate-slide-in" style="animation-delay: 0.7s">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-bullhorn me-2 text-primary"></i>What is Cyberbullying?
            </h5>
            <p class="text-muted mb-4">Cyberbullying is bullying that takes place over digital devices like cell phones, computers, and tablets. It can occur through SMS, Text, and apps, or online in social media, forums, or gaming where people can view, participate in, or share content.</p>
            
            <div class="alert alert-primary d-flex align-items-center mb-0">
                <i class="fas fa-info-circle fa-2x me-3"></i>
                <div>
                    <strong>Did you know?</strong> Cyberbullying can have serious emotional and psychological effects on victims.
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="col-lg-6">
        <div class="stat-card animate-slide-in" style="animation-delay: 0.8s">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-chart-bar me-2 text-danger"></i>Key Statistics
            </h5>
            <div class="space-y-3">
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-friends me-3 text-warning"></i>
                        <span>Teens experiencing cyberbullying</span>
                    </div>
                    <span class="badge bg-warning text-dark rounded-pill">Over 40%</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-comment-dots me-3 text-info"></i>
                        <span>Most common type</span>
                    </div>
                    <span class="badge bg-info rounded-pill">32%</span>
                </div>
                
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-gavel me-3 text-secondary"></i>
                        <span>False rumors cases</span>
                    </div>
                    <span class="badge bg-secondary rounded-pill">22%</span>
                </div>
                
                <div class="p-3 bg-danger text-white rounded">
                    <i class="fas fa-heart-broken me-2"></i>
                    Victims are at greater risk for self-harm and suicidal behaviors.
                </div>
            </div>
        </div>
    </div>

    <!-- Spotting the Signs -->
    <div class="col-lg-6">
        <div class="stat-card animate-slide-in" style="animation-delay: 0.9s">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-eye me-2 text-success"></i>Spotting the Signs
            </h5>
            <p class="text-muted mb-3">Changes in behavior can indicate someone is being cyberbullied. Look out for:</p>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-times-circle text-danger me-3"></i>
                        <span>Increased stress/anxiety</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-times-circle text-danger me-3"></i>
                        <span>Avoiding social situations</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-times-circle text-danger me-3"></i>
                        <span>Device abandonment</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-times-circle text-danger me-3"></i>
                        <span>Academic decline</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Plan -->
    <div class="col-lg-6">
        <div class="stat-card animate-slide-in" style="animation-delay: 1.0s">
            <h5 class="fw-bold mb-4">
                <i class="fas fa-shield-alt me-2 text-warning"></i>Action Plan
            </h5>
            <p class="text-muted mb-3">If you or someone you know is being cyberbullied, follow these steps:</p>
            
            <div class="space-y-3">
                <div class="d-flex align-items-start p-3 bg-light rounded">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                        <span class="fw-bold">1</span>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Don't Engage</h6>
                        <p class="text-muted mb-0">Do not reply to or forward cyberbullying messages.</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-start p-3 bg-light rounded">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                        <span class="fw-bold">2</span>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Save Evidence</h6>
                        <p class="text-muted mb-0">Take screenshots of posts, messages, or emails.</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-start p-3 bg-light rounded">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 30px; height: 30px; min-width: 30px;">
                        <span class="fw-bold">3</span>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Report It</h6>
                        <p class="text-muted mb-0">Use platform reporting tools and inform authorities.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resources Section -->
<div class="stat-card animate-slide-in mt-4" style="animation-delay: 1.1s">
    <div class="text-center py-4">
        <div class="mb-4">
            <i class="fas fa-life-ring fa-3x text-primary mb-3"></i>
            <h3 class="fw-bold text-dark mb-3">Need More Help or Information?</h3>
            <p class="text-muted mb-4">Access additional resources and support for cyberbullying prevention and awareness.</p>
        </div>
        
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://www.unicef.org/end-violence/how-to-stop-cyberbullying" class="action-card" target="_blank" style="max-width: 200px;">
                <div class="action-icon" style="background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                    <i class="fas fa-external-link-alt"></i>
                </div>
                <h6>UNICEF Resources</h6>
                <p class="text-muted mb-0 small">Global cyberbullying prevention guides</p>
            </a>
            
            <a href="contact.php" class="action-card" style="max-width: 200px;">
                <div class="action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-headset"></i>
                </div>
                <h6>Contact Support</h6>
                <p class="text-muted mb-0 small">Get help from our support team</p>
            </a>
            
            <a href="report.php" class="action-card pulse-button" style="max-width: 200px;">
                <div class="action-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h6>Report Incident</h6>
                <p class="text-muted mb-0 small">Submit a cyberbullying report</p>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trends Chart
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    const trendsChart = new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Cyberbullying Reports',
                data: [65, 59, 80, 81, 56, 55, 40],
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }, {
                label: 'Resolved Cases',
                data: [28, 48, 40, 19, 86, 27, 90],
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Distribution Chart
    const distributionCtx = document.getElementById('distributionChart').getContext('2d');
    const distributionChart = new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Social Media', 'Messaging Apps', 'Gaming', 'Email', 'Forums'],
            datasets: [{
                data: [35, 25, 20, 12, 8],
                backgroundColor: [
                    '#6366f1',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444',
                    '#8b5cf6'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 11,
                            weight: '600'
                        },
                        color: '#64748b'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed}%`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Chart filter buttons
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons in the group
            this.parentElement.querySelectorAll('.btn').forEach(b => {
                b.classList.remove('active');
            });
            // Add active class to clicked button
            this.classList.add('active');
        });
    });
});
</script>

<style>
.space-y-3 > * + * {
    margin-top: 1rem;
}

.bg-warning {
    background: #fef3c7 !important;
    color: #92400e !important;
}

.bg-info {
    background: #dbeafe !important;
    color: #1e40af !important;
}

.bg-secondary {
    background: #e5e7eb !important;
    color: #374151 !important;
}

.alert-primary {
    background: rgba(99, 102, 241, 0.1);
    border: 1px solid rgba(99, 102, 241, 0.2);
    color: #3730a3;
}

.bg-light {
    background: #f8fafc !important;
}

/* Pulse animation for report button */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.pulse-button {
    animation: pulse 2s infinite;
}
</style>

<?php 
include "dashboard_footer.php";
?>

<?php 
include "footer.php"; // Include your main public footer
?>