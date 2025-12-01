<?php
session_start();
require_once "config.php";
$pageTitle = "CyberWatch - Secure Cyberbullying Reporting";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberWatch - Secure Cyberbullying Reporting</title>
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

        /* Hero Section */
        .hero-section {
            background: var(--gradient);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Hero Visual */
        .hero-visual {
            position: relative;
            height: 500px;
        }

        .floating-card {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            width: 140px;
            animation: float 6s ease-in-out infinite;
        }

        .floating-card i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: white;
        }

        .floating-card h5 {
            font-size: 0.9rem;
            margin-bottom: 5px;
            color: white;
        }

        .floating-card p {
            font-size: 0.8rem;
            opacity: 0.8;
            margin: 0;
            color: white;
        }

        .card-1 {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .card-2 {
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .card-3 {
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .main-visual {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        /* Hero Wave */
        .hero-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .hero-wave svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 150px;
        }

        .hero-wave path {
            fill: #f8fafc;
        }

        /* Sections */
        .features-section, .process-section, .cta-section {
            padding: 80px 0;
            position: relative;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 3rem;
        }

        /* Feature Cards */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            text-align: center;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 2rem;
            color: white;
        }

        .feature-card h4 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.6;
        }

        /* Process Steps */
        .process-step {
            text-align: center;
            padding: 40px 20px;
            position: relative;
        }

        .step-number {
            font-size: 4rem;
            font-weight: 700;
            color: var(--primary);
            opacity: 0.1;
            margin-bottom: 1rem;
            line-height: 1;
        }

        .step-content h4 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .step-content p {
            color: #64748b;
            line-height: 1.6;
        }

        .process-section {
            background: var(--light);
        }

        /* CTA Section */
        .cta-section {
            background: var(--gradient);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Buttons */
        .btn {
            border-radius: 12px;
            padding: 16px 32px;
            font-weight: 600;
            font-size: 16px;
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

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            background: transparent;
        }

        .btn-outline-light:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-2px);
        }

        .btn-light {
            background: white;
            color: var(--primary);
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.4);
        }

        .btn-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.6);
            color: var(--primary);
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
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-stats {
                flex-direction: column;
                gap: 1rem;
            }
            
            .hero-actions {
                flex-direction: column;
            }
            
            .hero-visual {
                height: 300px;
                margin-top: 2rem;
            }
            
            .main-visual {
                width: 200px;
                height: 200px;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            <span style="color: white;">Protect</span> Your Digital Space
                        </h1>
                        <p class="hero-subtitle">
                            Report cyberbullying incidents securely and anonymously. Your safety is our priority. 
                            Join thousands who have taken a stand against online harassment.
                        </p>
                        <div class="hero-stats">
                            <div class="stat-item">
                                <div class="stat-number">10K+</div>
                                <div class="stat-label">Reports Handled</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">95%</div>
                                <div class="stat-label">Resolution Rate</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Support Available</div>
                            </div>
                        </div>
                        <div class="hero-actions">
                            <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                                <a href="dashboard.php" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                </a>
                                <a href="report.php" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>New Report
                                </a>
                            <?php else: ?>
                                <a href="register.php" class="btn btn-primary btn-lg me-3 pulse-button">
                                    <i class="fas fa-shield-alt me-2"></i>Get Protected
                                </a>
                                <a href="login.php" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-visual">
                        <div class="floating-card card-1">
                            <i class="fas fa-shield-check"></i>
                            <h5>Secure Reporting</h5>
                            <p>100% Anonymous</p>
                        </div>
                        <div class="floating-card card-2">
                            <i class="fas fa-clock"></i>
                            <h5>Quick Response</h5>
                            <p>24-48 Hours</p>
                        </div>
                        <div class="floating-card card-3">
                            <i class="fas fa-chart-line"></i>
                            <h5>Track Progress</h5>
                            <p>Real-time Updates</p>
                        </div>
                        <div class="main-visual">
                            <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Cpath fill='%23ffffff' d='M200,50 Q300,100 200,150 Q100,200 200,250 Q300,300 200,350' opacity='0.3'/%3E%3Ccircle cx='200' cy='150' r='80' fill='%23ffffff' opacity='0.2'/%3E%3C/svg%3E" 
                                 alt="Cyber Protection" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" fill="currentColor"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" fill="currentColor"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="currentColor"></path>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">How CyberWatch Protects You</h2>
                    <p class="section-subtitle">Comprehensive tools to combat cyberbullying and ensure your online safety</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: var(--gradient);">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h4>Anonymous Reporting</h4>
                        <p>Report incidents without revealing your identity. Your privacy is our top priority throughout the process.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>24/7 Monitoring</h4>
                        <p>Our team works around the clock to review reports and take appropriate action promptly.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4>Progress Tracking</h4>
                        <p>Monitor the status of your reports in real-time with our intuitive dashboard and updates.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="process-section">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">Simple & Secure Process</h2>
                    <p class="section-subtitle">Three easy steps to report and resolve cyberbullying incidents</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="process-step">
                        <div class="step-number">01</div>
                        <div class="step-content">
                            <h4>Report Incident</h4>
                            <p>Submit details of the cyberbullying incident through our secure, anonymous form.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="process-step">
                        <div class="step-number">02</div>
                        <div class="step-content">
                            <h4>Expert Review</h4>
                            <p>Our trained team reviews your report and investigates the situation thoroughly.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="process-step">
                        <div class="step-number">03</div>
                        <div class="step-content">
                            <h4>Take Action</h4>
                            <p>We take appropriate measures and keep you updated on the resolution progress.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="cta-title">Ready to Take a Stand Against Cyberbullying?</h2>
                    <p class="cta-subtitle">Join our community of empowered users fighting back against online harassment.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>
                        <a href="register.php" class="btn btn-light btn-lg pulse-button">
                            <i class="fas fa-user-plus me-2"></i>Start Protecting Yourself
                        </a>
                    <?php else: ?>
                        <a href="report.php" class="btn btn-light btn-lg pulse-button">
                            <i class="fas fa-plus-circle me-2"></i>Submit New Report
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include "footer.php"; ?>