<?php
session_start();
$isAdminLoggedIn = isset($_SESSION['admin_id']);
include("config/conn.php");

// --- Fetch Testers ---
$testers = [];
$testerQuery = "SELECT tester_id, tester_name FROM testers ORDER BY tester_name ASC";
$testerResult = $conn->query($testerQuery);
if ($testerResult) {
    while ($row = $testerResult->fetch_assoc()) {
        $testers[] = $row;
    }
}

// --- Fetch Recent Reports with tester name ---
$recentReports = [];
$recentQuery = "
    SELECT gr.*, t.tester_name AS generated_by_name
    FROM generated_reports gr
    LEFT JOIN testers t ON gr.generated_by = t.tester_id
    ORDER BY gr.date_generated DESC
";
$recentResult = $conn->query($recentQuery);
if ($recentResult) {
    while ($row = $recentResult->fetch_assoc()) {
        $recentReports[] = $row;
    }
}

// --- Fetch Scheduled Reports ---
$scheduledReports = [];
$scheduledQuery = "SELECT * FROM scheduled_reports ORDER BY next_run ASC";
$scheduledResult = $conn->query($scheduledQuery);
if ($scheduledResult) {
    while ($row = $scheduledResult->fetch_assoc()) {
        $scheduledReports[] = $row;
    }

    // Fetch report types for dropdown
    $reportTypes = [
        ['type_id' => 1, 'type_name' => 'Analytics Summary'],
        ['type_id' => 2, 'type_name' => 'Compliance Report'],
        ['type_id' => 3, 'type_name' => 'Performance Summary'],
        ['type_id' => 4, 'type_name' => 'CPRI Report'],
        ['type_id' => 5, 'type_name' => 'Failure Analysis']
    ];
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SRS Electrical Appliances</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Styles and Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-blue: #1a5f7a;
            --accent-blue: #2a86ba;
            --light-blue: #57c5e6;
            --dark-gray: #333;
            --medium-gray: #666;
            --light-gray: #f8f9fa;
            --white: #ffffff;
            --border-color: #e0e0e0;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
            --success-green: #28a745;
            --danger-red: #dc3545;
        }

        body {
            line-height: 1.6;
            color: var(--dark-gray);
            background-color: var(--light-gray);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        section {
            padding: 40px 0;
        }

        /* Navbar Styles - EXACTLY SAME AS OTHER PAGES */
        header {
            background-color: var(--white);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo-icon {
            color: var(--primary-blue);
            font-size: 2rem;
            margin-right: 10px;
        }

        .logo-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .nav-menu {
            display: flex;
            list-style: none;
        }

        .nav-menu li {
            position: relative;
            margin-left: 30px;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--dark-gray);
            font-weight: 600;
            transition: var(--transition);
            padding: 5px 0;
            position: relative;
        }

        .nav-menu a:hover {
            color: var(--primary-blue);
        }

        .nav-menu a.active {
            color: var(--primary-blue);
        }

        .nav-menu a.active:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background: var(--primary-blue);
            left: 0;
            bottom: 0;
        }

        .nav-menu a:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--primary-blue);
            left: 0;
            bottom: 0;
            transition: var(--transition);
        }

        .nav-menu a:hover:after {
            width: 100%;
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: var(--white);
            min-width: 200px;
            box-shadow: var(--shadow);
            border-radius: 4px;
            z-index: 1;
            top: 100%;
            left: 0;
            padding: 10px 0;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            display: block;
            padding: 10px 20px;
            color: var(--dark-gray);
        }

        .dropdown-content a:hover {
            background-color: var(--light-gray);
        }

        .dashboard-btn {
            background-color: var(--accent-blue);
            color: white;
            padding: 10px 25px;
            border-radius: 4px;
        }

        .dashboard-btn:hover {
            background-color: var(--primary-blue);
            transform: translateY(-3px);
        }

        .mobile-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary-blue);
        }

        /* Page Header - */
        .page-header {
            background-color: var(--white);
            padding: 30px 0;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 30px;
            position: relative;
            top: auto;
            z-index: 900;
        }

        .page-title {
            font-size: 2.5rem;
            color: var(--primary-blue);
            font-weight: 700;
            text-align: center;
        }

        .page-subtitle {
            text-align: center;
            color: var(--medium-gray);
            margin-top: 10px;
            font-size: 1.1rem;
        }

        /* Section Titles */
        .section-title {
            font-size: 1.8rem;
            color: var(--primary-blue);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-blue);
        }

        /* Dashboard Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-card-1 {
            border-top: 4px solid var(--success-green);
        }

        .stat-card-2 {
            border-top: 4px solid var(--danger-red);
        }

        .stat-card-3 {
            border-top: 4px solid var(--accent-blue);
        }

        .stat-card-4 {
            border-top: 4px solid #9c27b0;
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .stat-card-1 .stat-icon {
            color: var(--success-green);
        }

        .stat-card-2 .stat-icon {
            color: var(--danger-red);
        }

        .stat-card-3 .stat-icon {
            color: var(--accent-blue);
        }

        .stat-card-4 .stat-icon {
            color: #9c27b0;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark-gray);
        }

        .stat-label {
            color: var(--medium-gray);
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            font-size: 0.9rem;
        }

        .trend-up {
            color: var(--success-green);
        }

        .trend-down {
            color: var(--danger-red);
        }

        /* Dashboard Layout */
        .dashboard-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        /* Charts Section */
        .chart-container {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .chart-placeholder {
            height: 250px;
            background-color: #f8f9fa;
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--medium-gray);
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .chart-placeholder i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
            z-index: 1;
        }

        .chart-placeholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(42, 134, 186, 0.05) 50%, transparent 70%);
            animation: shimmer 2s infinite linear;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Progress Bars */
        .progress-container {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            box-shadow: var(--shadow);
        }

        .progress-item {
            margin-bottom: 20px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .progress-label {
            font-weight: 500;
            color: var(--dark-gray);
        }

        .progress-value {
            font-weight: 600;
            color: var(--primary-blue);
        }

        .progress-bar {
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 5px;
            transition: width 1.5s ease;
        }

        .fill-success {
            background-color: var(--success-green);
        }

        .fill-warning {
            background-color: #ffc107;
        }

        .fill-info {
            background-color: #17a2b8;
        }

        /* Recent Activity */
        .activity-container {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            height: fit-content;
        }

        .activity-list {
            list-style: none;
            margin-top: 20px;
        }

        .activity-item {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .activity-icon-success {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--success-green);
        }

        .activity-icon-warning {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .activity-icon-danger {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--danger-red);
        }

        .activity-icon-info {
            background-color: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
        }

        .activity-content {
            flex: 1;
        }

        .activity-text {
            margin-bottom: 5px;
        }

        .activity-time {
            font-size: 0.85rem;
            color: var (--medium-gray);
        }

        /* Quick Actions */
        .quick-actions-container {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            box-shadow: var (--shadow);
            height: fit-content;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 10px;
            background-color: rgba(42, 134, 186, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            text-decoration: none;
            color: var(--dark-gray);
            transition: var(--transition);
        }

        .action-btn:hover {
            background-color: rgba(42, 134, 186, 0.1);
            border-color: var(--accent-blue);
            transform: translateY(-3px);
        }

        .action-icon {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: var(--accent-blue);
        }

        .action-text {
            font-weight: 500;
            text-align: center;
            font-size: 0.9rem;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }

        .modal-content {
            background-color: var(--white);
            margin: 5% auto;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 15px;
        }

        .modal-header h2 {
            color: var(--primary-blue);
            font-size: 1.5rem;
        }

        .close-btn {
            font-size: 28px;
            font-weight: bold;
            color: var(--medium-gray);
            cursor: pointer;
            background: none;
            border: none;
            transition: var(--transition);
        }

        .close-btn:hover {
            color: var(--dark-gray);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 14px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: var(--transition);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 5px rgba(26, 95, 122, 0.3);
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .modal-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-submit {
            background: var(--primary-blue);
            color: var(--white);
        }

        .btn-submit:hover {
            background: var(--accent-blue);
        }

        .btn-cancel {
            background: var(--light-gray);
            color: var(--dark-gray);
        }

        .btn-cancel:hover {
            background: var(--border-color);
        }

        /* Footer - EXACTLY SAME AS OTHER PAGES */
        footer {
            background-color: #2c3e50;
            color: var(--white);
            padding: 60px 0 30px;
            margin-top: 40px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-column h3 {
            font-size: 1.3rem;
            margin-bottom: 25px;
            color: var(--light-blue);
            position: relative;
            padding-bottom: 10px;
        }

        .footer-column h3:after {
            content: '';
            position: absolute;
            width: 40px;
            height: 2px;
            background-color: var(--accent-blue);
            bottom: 0;
            left: 0;
        }

        .footer-column p,
        .footer-column a {
            color: #bdc3c7;
            margin-bottom: 15px;
            display: block;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-column a:hover {
            color: var(--light-blue);
            padding-left: 5px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .contact-item i {
            color: var(--accent-blue);
            width: 20px;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: var (--white);
            text-decoration: none;
            transition: var (--transition);
        }

        .social-icon:hover {
            background-color: var(--accent-blue);
            transform: translateY(-5px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #95a5a6;
            font-size: 0.9rem;
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-layout {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .page-title {
                font-size: 2.2rem;
            }

            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .nav-menu {
                position: fixed;
                top: 80px;
                left: 0;
                width: 100%;
                background-color: var(--white);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                transform: translateY(-150%);
                transition: transform 0.5s ease;
                z-index: 999;
            }

            .nav-menu.active {
                transform: translateY(0);
            }

            .nav-menu li {
                margin: 0 0 20px 0;
            }

            .page-title {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-value {
                font-size: 2.2rem;
            }

            .actions-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.6rem;
            }

            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .action-btn {
                padding: 15px 5px;
            }

            .action-icon {
                font-size: 1.5rem;
            }

            .action-text {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header & Navigation  -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo">
                    <i class="fas fa-bolt logo-icon"></i>
                    <span class="logo-text">SRS Electrical</span>
                </a>

                <div class="mobile-toggle" id="mobileToggle">
                    <i class="fas fa-bars"></i>
                </div>

                <ul class="nav-menu" id="navMenu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li class="dropdown">
                        <a href="lab-testing.php">Lab Testing <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="report.php">Reports</a>
                            <a href="cpri.php">CPRI Testing</a>
                        </div>
                    </li>
                    <li><a href="product.php">Product Catalog</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="config/logout.php" style="color: var(--danger-red); font-weight: 700;">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Page Header -  -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Smart Testing Dashboard - Monitor tests, reports, and approvals in one place</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Stats Cards Section -->
        <section>
            <h2 class="section-title">Testing Overview</h2>

            <div class="stats-grid">
                <div class="stat-card stat-card-1">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value">94.7%</div>
                    <div class="stat-label">Overall Pass Rate</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>+2.3% from last month</span>
                    </div>
                </div>

                <div class="stat-card stat-card-2">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-value">5.3%</div>
                    <div class="stat-label">Failure Rate</div>
                    <div class="stat-trend trend-down">
                        <i class="fas fa-arrow-down"></i>
                        <span>-1.1% from last month</span>
                    </div>
                </div>

                <div class="stat-card stat-card-3">
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-value">1,247</div>
                    <div class="stat-label">Total Products Tested</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>+128 this month</span>
                    </div>
                </div>

                <div class="stat-card stat-card-4">
                    <div class="stat-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="stat-value">312</div>
                    <div class="stat-label">CPRI Approvals</div>
                    <div class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>+42 this quarter</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dashboard Content Section -->
        <section>
            <h2 class="section-title">Performance Analytics</h2>

            <div class="dashboard-layout">
                <!-- Left Column: Charts and Progress -->
                <div>
                    <!-- Performance Chart -->
                    <div class="chart-container">
                        <h3 style="font-size: 1.4rem; color: var(--primary-blue); margin-bottom: 15px;">Testing Performance Trend</h3>
                        <p style="color: var(--medium-gray); margin-bottom: 15px;">Monthly performance trend for product testing</p>
                        <div class="chart-placeholder">
                            <i class="fas fa-chart-line"></i>
                            <p>Performance trend visualization</p>
                            <small style="margin-top: 5px;">Line chart showing pass/fail rates over time</small>
                        </div>
                    </div>

                    <!-- Testing Progress -->
                    <div class="progress-container">
                        <h3 style="font-size: 1.4rem; color: var(--primary-blue); margin-bottom: 20px;">Current Testing Progress</h3>
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-label">Switchgear Testing</span>
                                <span class="progress-value">85%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill fill-success" style="width: 85%;"></div>
                            </div>
                        </div>

                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-label">Control Panels</span>
                                <span class="progress-value">72%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill fill-warning" style="width: 72%;"></div>
                            </div>
                        </div>

                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-label">Capacitor Banks</span>
                                <span class="progress-value">45%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill fill-info" style="width: 45%;"></div>
                            </div>
                        </div>

                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-label">Fuse Testing</span>
                                <span class="progress-value">92%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill fill-success" style="width: 92%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Activity and Quick Actions -->
                <div>
                    <!-- Recent Activity -->
                    <div class="activity-container">
                        <h3 style="font-size: 1.4rem; color: var(--primary-blue); margin-bottom: 20px;">Recent Activity</h3>
                        <ul class="activity-list">
                            <li class="activity-item">
                                <div class="activity-icon activity-icon-success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">HV Switchgear Panel passed all tests</div>
                                    <div class="activity-time">10 minutes ago</div>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon activity-icon-info">
                                    <i class="fas fa-file-upload"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">CPRI report generated for Q3 2023</div>
                                    <div class="activity-time">45 minutes ago</div>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon activity-icon-warning">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">Capacitor Bank requires recalibration</div>
                                    <div class="activity-time">2 hours ago</div>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon activity-icon-danger">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">Resistor Array failed tolerance test</div>
                                    <div class="activity-time">3 hours ago</div>
                                </div>
                            </li>
                            <li class="activity-item">
                                <div class="activity-icon activity-icon-success">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-text">New CPRI approval granted</div>
                                    <div class="activity-time">5 hours ago</div>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions-container">
                        <h3 style="font-size: 1.4rem; color: var(--primary-blue); margin-bottom: 20px;">Quick Actions</h3>
                        <div class="actions-grid">
                            <button class="action-btn" onclick="openStartTestModal()">
                                <i class="fas fa-flask action-icon"></i>
                                <span class="action-text">Start Test</span>
                            </button>
                            <?php if (isset($_SESSION['admin_id'])): ?>
                                <button class="action-btn" onclick="openGenerateReportModal()">
                                    <i class="fas fa-file-download action-icon"></i>
                                    <span class="action-text">Generate Report</span>
                                </button>
                            <?php endif; ?>
                            <button class="action-btn" onclick="openAddProductModal()">
                                <i class="fas fa-box-open action-icon"></i>
                                <span class="action-text">Add Product</span>
                            </button>
                            <button class="action-btn" onclick="openAddCpriModal()">
                                <i class="fas fa-certificate action-icon"></i>
                                <span class="action-text">Add CPRI</span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h2>Add New Product</h2>
                <button class="close-btn" onclick="closeAddProductModal()">&times;</button>
            </div>
            <form id="addProductForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="productImage">Product Image</label>
                    <div style="border: 2px dashed #ccc; padding: 20px; border-radius: 5px; text-align: center; cursor: pointer;" id="imageDropZone">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--accent-blue); margin-bottom: 10px;"></i>
                        <p>Drag and drop image here or click to select</p>
                        <small style="color: #666;">JPG, PNG, GIF, WebP (Max 5MB)</small>
                        <input type="file" id="productImage" name="product_image" accept="image/*" style="display: none;">
                    </div>
                    <div id="imagePreview" style="display: none; margin-top: 15px; text-align: center;">
                        <img id="previewImg" src="" style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                        <button type="button" onclick="removeImage()" style="display: block; margin-top: 10px; background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                            Remove Image
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="productName">Product Name <span style="color: red;">*</span></label>
                    <input type="text" id="productName" name="name" required>
                </div>

                <div class="form-group">
                    <label for="productCategory">Category <span style="color: red;">*</span></label>
                    <select id="productCategory" name="category" required>
                        <option value="">Select Category</option>
                        <option value="switchgear">Switchgear</option>
                        <option value="transformers">Transformers</option>
                        <option value="testing">Testing Equipment</option>
                        <option value="panels">Control Panels</option>
                        <option value="cables">Cables & Accessories</option>
                        <option value="safety">Safety Equipment</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="productPrice">Price (₹) <span style="color: red;">*</span></label>
                    <input type="number" id="productPrice" name="price" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="productVoltage">Voltage Rating <span style="color: red;">*</span></label>
                    <select id="productVoltage" name="voltage_rating" required>
                        <option value="">Select Voltage</option>
                        <option value="lv">Low Voltage (≤1kV)</option>
                        <option value="mv">Medium Voltage (1kV-33kV)</option>
                        <option value="hv">High Voltage (>33kV)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="productCert">Certification <span style="color: red;">*</span></label>
                    <select id="productCert" name="certification" required>
                        <option value="">Select Certification</option>
                        <option value="cpri">CPRI Certified</option>
                        <option value="iso">ISO Certified</option>
                        <option value="iec">IEC Compliant</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="productStock">Stock <span style="color: red;">*</span></label>
                    <input type="number" id="productStock" name="stock" min="0" required>
                </div>

                <div class="form-group">
                    <label for="productDescription">Description <span style="color: red;">*</span></label>
                    <textarea id="productDescription" name="description" placeholder="Enter product description" required></textarea>
                </div>

                <div class="form-group">
                    <label for="productBadge">Badge</label>
                    <select id="productBadge" name="badge">
                        <option value="">No Badge</option>
                        <option value="new">New</option>
                        <option value="popular">Popular</option>
                        <option value="cpri">CPRI</option>
                        <option value="limited">Limited</option>
                    </select>
                </div>

                <div id="statusMessage" style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center;"></div>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeAddProductModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Start Test Modal -->
    <div id="startTestModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Start Test</h2>
                <button class="close-btn" onclick="closeStartTestModal()">&times;</button>
            </div>
            <form id="startTestForm">
                <div class="form-group">
                    <label for="testerSelect">Tester <span style="color: red;">*</span></label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <select id="testerSelect" name="tester_name" required style="flex: 1;"></select>
                        <input type="text" id="newTesterInput" placeholder="Add new tester" style="display:none; flex: 1;" />
                        <button type="button" id="addTesterBtn" style="padding: 8px 12px;">Add New</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="productId">Product <span style="color: red;">*</span></label>
                    <select id="productId" name="product_id" required></select>
                </div>
                <div class="form-group">
                    <label for="productType">Product Type <span style="color: red;">*</span></label>
                    <select id="productType" name="product_type" required>
                        <option value="">-- Select Product Type --</option>
                        <option value="switchgear">Switchgear</option>
                        <option value="transformers">Transformers</option>
                        <option value="testing">Testing Equipment</option>
                        <option value="panels">Control Panels</option>
                        <option value="cables">Cables & Accessories</option>
                        <option value="safety">Safety Equipment</option>
                        <option value="capacitors">Capacitors</option>
                        <option value="resistors">Resistors</option>
                        <option value="equipment">Power Equipment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="testType">Test Type <span style="color: red;">*</span></label>
                    <select id="testType" name="test_type" required>
                        <option value="">-- Select Test Type --</option>
                        <option value="electrical">Electrical</option>
                        <option value="thermal">Thermal</option>
                        <option value="performance">Performance</option>
                        <option value="cpri">CPRI</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="testStatus">Test Status <span style="color: red;">*</span></label>
                    <select id="testStatus" name="status" required>
                        <option value="in-progress">Pending</option>
                        <option value="pass">Pass</option>
                        <option value="fail">Fail</option>
                    </select>
                </div>
                <div id="startTestStatusMessage" style="display: none; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center;"></div>
                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeStartTestModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Start Test</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add CPRI Modal -->
    <div id="addCpriModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Submit Product to CPRI</h2>
                <button class="close-btn" onclick="closeAddCpriModal()">&times;</button>
            </div>
            <form id="addCpriForm" enctype="multipart/form-data">
                <!-- Product Selection -->
                <div class="form-group">
                    <label for="cpriProductId">Product <span style="color:red">*</span></label>
                    <select id="cpriProductId" name="product_id" required>
                        <!-- Populate options dynamically from products table -->
                    </select>
                    <input type="hidden" id="cpriProductName" name="product_name">
                </div>

                <!-- Submission Date -->
                <div class="form-group">
                    <label for="submissionDate">Submission Date <span style="color:red">*</span></label>
                    <input type="date" id="submissionDate" name="submission_date" required>
                </div>

                <!-- Test Date -->
                <div class="form-group">
                    <label for="testDate">Test Date</label>
                    <input type="date" id="testDate" name="test_date">
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label for="cpriStatus">Status</label>
                    <select id="cpriStatus" name="status">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <!-- Testing Lab -->
                <div class="form-group">
                    <label for="testingLab">Testing Lab</label>
                    <input type="text" id="testingLab" name="testing_lab">
                </div>

                <!-- Certificate Upload -->
                <div class="form-group">
                    <label for="certificateFile">Certificate (PDF or Image)</label>
                    <input type="file" id="certificateFile" name="certificate_image" accept=".pdf,image/*">
                </div>

                <input type="hidden" id="cpriReference" name="cpri_reference">
                <input type="hidden" id="certificateNo" name="certificate_no">

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeAddCpriModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Submit</button>
                </div>
            </form>

        </div>
    </div>

    <!-- Generate Report Modal -->
    <div id="generateReportModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Generate Custom Report</h2>
                <button class="close-btn" onclick="closeGenerateReportModal()">&times;</button>
            </div>

            <form id="generateReportForm">

                <div class="form-group">
                    <label for="reportName">Report Name</label>
                    <input type="text" id="reportName" placeholder="Enter new report name">
                </div>

                <div class="form-group">
                    <label class="form-label" for="reportType">Report Type</label>
                    <select class="form-control" id="reportType">
                        <option value="">Select Report Type</option>
                        <?php foreach ($reportTypes as $type): ?>
                            <option value="<?= htmlspecialchars($type['type_name']) ?>"><?= htmlspecialchars($type['type_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="generatedBy">Generated By</label>
                    <select class="form-control" id="generatedBy">
                        <option value="">Select Tester</option>
                        <?php foreach ($testers as $t): ?>
                            <option value="<?= $t['tester_id'] ?>"><?= htmlspecialchars($t['tester_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate">
                </div>

                <div class="form-group">
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate">
                </div>

                <div class="form-group">
                    <label for="productType">Product Type</label>
                    <select id="productType">
                        <option value="">All Product Types</option>
                        <option value="switchgear">Switchgear</option>
                        <option value="control-panels">Control Panels</option>
                        <option value="capacitors">Capacitors</option>
                        <option value="fuses">Fuses</option>
                        <option value="resistors">Resistors</option>
                        <option value="testing-equipment">Testing Equipment</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="testStatus">Test Status</label>
                    <select id="testStatus">
                        <option value="">All Statuses</option>
                        <option value="passed">Passed</option>
                        <option value="failed">Failed</option>
                        <option value="pending">Pending</option>
                        <option value="in-progress">In Progress</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="format">Report Format</label>
                    <select id="format">
                        <option value="pdf">PDF</option>
                        <option value="doc">Word</option>
                        <option value="excel">Excel</option>
                        <option value="csv">CSV</option>
                    </select>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeGenerateReportModal()">Cancel</button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-file-download"></i> Generate Report
                    </button>
                </div>

            </form>
        </div>
    </div>


    <!-- Footer  -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <!-- Column 1: Contact Info -->
                <div class="footer-column">
                    <h3>Contact Us</h3>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+91 98765 43210</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@srselectrical.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Industrial Area, Phase II<br>Bengaluru, Karnataka 560058</span>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="footer-column">
                    <h3>Quick Links</h3>
                    <a href="about.php">About Us</a>
                    <a href="contact.php">Contact Us</a>
                    <a href="cpri.php">CPRI Certification</a>
                    <a href="faqs.php">FAQs</a>
                    <a href="report.php">Testing Reports</a>
                </div>

                <!-- Column 3: Social Media -->
                <div class="footer-column">
                    <h3>Connect With Us</h3>
                    <p>Follow us on social media for updates on electrical testing standards and industry news.</p>

                    <div class="social-links">
                        <a href="https://www.facebook.com/" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://pk.linkedin.com/" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://www.whatsapp.com/" class="social-icon">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://x.com/" class="social-icon">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2023 SRS Electrical Appliances. All Rights Reserved. | ISO 9001:2015 Certified | CPRI Approved Testing Facility</p>
            </div>
        </div>
    </footer>

    <script>
        // AUTO GENERATION LOGIC
        (function generateCPRIFields() {
            const now = new Date();

            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');

            // MMDD format
            const mmdd = month + day;

            // Auto values
            document.getElementById('cpriReference').value = `CPRI-${year}-${mmdd}`;
            document.getElementById('certificateNo').value = `CPRI-CERT-${mmdd}`;
        })();

        // Mobile Navigation Toggle - EXACTLY SAME AS OTHER PAGES
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');

        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileToggle.innerHTML = navMenu.classList.contains('active') ?
                '<i class="fas fa-times"></i>' :
                '<i class="fas fa-bars"></i>';
        });

        // Modal Functions for Add Product
        function openAddProductModal() {
            document.getElementById("addProductModal").style.display = "block";
            document.body.style.overflow = "hidden";
        }

        function closeAddProductModal() {
            document.getElementById("addProductModal").style.display = "none";
            document.body.style.overflow = "auto";
            document.getElementById("addProductForm").reset();
        }

        // Modal Functions for Start Test
        function openStartTestModal() {
            document.getElementById("startTestModal").style.display = "block";
            document.body.style.overflow = "hidden";
            populateProducts();
            populateTesters();
        }

        function closeStartTestModal() {
            const statusMessage = document.getElementById('startTestStatusMessage');
            document.getElementById("startTestModal").style.display = "none";
            document.body.style.overflow = "auto";
            document.getElementById("startTestForm").reset();
            document.getElementById('newTesterInput').style.display = 'none';
            document.getElementById('testerSelect').style.display = '';
            statusMessage.style.display = 'none';

        }

        // Populate testers dropdown
        async function populateTesters() {
            try {
                const response = await fetch('api/get_testers.php');
                const data = await response.json();
                const testerSelect = document.getElementById('testerSelect');
                testerSelect.innerHTML = '';
                if (data.success && data.testers.length > 0) {
                    data.testers.forEach(tester => {
                        const option = document.createElement('option');
                        option.value = tester.tester_name;
                        option.textContent = tester.tester_name;
                        testerSelect.appendChild(option);
                    });
                }
                // Add a blank option at the top
                const blankOption = document.createElement('option');
                blankOption.value = '';
                blankOption.textContent = '-- Select Tester --';
                testerSelect.insertBefore(blankOption, testerSelect.firstChild);
                testerSelect.value = '';
            } catch (error) {
                console.error('Error fetching testers:', error);
            }
        }

        // Add New Tester logic
        document.addEventListener('DOMContentLoaded', function() {
            const addTesterBtn = document.getElementById('addTesterBtn');
            const newTesterInput = document.getElementById('newTesterInput');
            const testerSelect = document.getElementById('testerSelect');
            addTesterBtn.addEventListener('click', function() {
                if (newTesterInput.style.display === 'none') {
                    newTesterInput.style.display = '';
                    testerSelect.style.display = 'none';
                    newTesterInput.focus();
                    addTesterBtn.textContent = 'Use Existing';
                } else {
                    newTesterInput.style.display = 'none';
                    testerSelect.style.display = '';
                    addTesterBtn.textContent = 'Add New';
                }
            });
        });

        // Populate products dropdown and auto-select type
        async function populateProducts() {
            try {
                const response = await fetch('api/get_products.php');
                const data = await response.json();
                const productSelect = document.getElementById('productId');
                const productTypeSelect = document.getElementById('productType');
                productSelect.innerHTML = '<option value="">-- Select Product --</option>';
                if (data.success && data.products.length > 0) {
                    data.products.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.product_id;
                        option.textContent = product.product_id + ' - ' + product.name;
                        option.dataset.type = product.category;
                        productSelect.appendChild(option);
                    });
                }
                // Reset product type on open
                if (productTypeSelect) productTypeSelect.value = '';
            } catch (error) {
                console.error('Error fetching products:', error);
            }
        }

        // Auto-select product type on product select
        document.addEventListener('DOMContentLoaded', function() {
            const productSelect = document.getElementById('productId');
            const productTypeSelect = document.getElementById('productType');
            productSelect.addEventListener('change', function() {
                const selected = productSelect.options[productSelect.selectedIndex];
                if (selected && selected.dataset.type) {
                    productTypeSelect.value = selected.dataset.type;
                } else {
                    productTypeSelect.value = '';
                }
            });
        });

        // Handle Add Product Form Submission
        document.getElementById("addProductForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = this.querySelector('[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adding...';

            fetch('api/add_product.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        closeAddProductModal();
                        document.getElementById("addProductForm").reset();
                    } else {
                        alert('Error: ' + data.message);
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Add Product';
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Add Product';
                });
        });

        // Handle Start Test Form Submission
        document.getElementById("startTestForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const testerSelect = document.getElementById('testerSelect');
            const newTesterInput = document.getElementById('newTesterInput');
            const statusMessage = document.getElementById('startTestStatusMessage');
            let testerName = '';
            if (newTesterInput.style.display !== 'none' && newTesterInput.value.trim() !== '') {
                testerName = newTesterInput.value.trim();
            } else {
                testerName = testerSelect.value;
            }
            if (!testerName) {
                statusMessage.textContent = 'Please select or enter a tester name.';
                statusMessage.style.display = 'block';
                statusMessage.style.color = 'red';
                return;
            }
            const formData = new FormData(this);
            formData.set('tester_name', testerName);
            const submitBtn = this.querySelector('[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Starting...';
            fetch('api/add_test_result.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        statusMessage.textContent = data.message;
                        statusMessage.style.display = 'block';
                        statusMessage.style.color = 'green';
                        setTimeout(() => {
                            closeStartTestModal();
                        }, 2000);
                    } else {
                        statusMessage.textContent = 'Error: ' + data.message;
                        statusMessage.style.display = 'block';
                        statusMessage.style.color = 'red';
                    }
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Start Test';
                })
                .catch(error => {
                    statusMessage.textContent = 'Error: ' + error.message;
                    statusMessage.style.display = 'block';
                    statusMessage.style.color = 'red';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Start Test';
                });
        });

        // Close mobile menu when clicking on a link - EXACTLY SAME AS OTHER PAGES
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const imageDropZone = document.getElementById('imageDropZone');
            const productImage = document.getElementById('productImage');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            // Trigger file input on click
            imageDropZone.addEventListener('click', () => {
                productImage.click();
            });

            // Handle file selection
            productImage.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        previewImg.src = event.target.result;
                        imagePreview.style.display = 'block';
                        imageDropZone.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle drag and drop
            imageDropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                imageDropZone.style.borderColor = 'var(--primary-blue)';
            });

            imageDropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                imageDropZone.style.borderColor = '#ccc';
            });

            imageDropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                imageDropZone.style.borderColor = '#ccc';
                const file = e.dataTransfer.files[0];
                if (file) {
                    productImage.files = e.dataTransfer.files;
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        previewImg.src = event.target.result;
                        imagePreview.style.display = 'block';
                        imageDropZone.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        function removeImage() {
            const productImage = document.getElementById('productImage');
            const imagePreview = document.getElementById('imagePreview');
            const imageDropZone = document.getElementById('imageDropZone');

            productImage.value = ''; // Reset file input
            imagePreview.style.display = 'none';
            imageDropZone.style.display = 'block';
        }

        // Animate progress bars on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Update year in footer - Same as other pages
            const currentYear = new Date().getFullYear();
            const yearElement = document.querySelector('.footer-bottom p');
            yearElement.innerHTML = yearElement.innerHTML.replace('2023', currentYear);

            // Animate progress bars
            setTimeout(() => {
                document.querySelectorAll('.progress-fill').forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }, 500);
        });

        // // --- Generate Report Modal ---
        // const generateModal = document.getElementById("generateReportModal");
        // const closeGenerateBtns = [
        //     document.getElementById("closeGenerateModal"),
        //     document.getElementById("cancelGenerate")
        // ];

        // // Close modal
        // closeGenerateBtns.forEach(btn => {
        //     if (btn) btn.onclick = () => generateModal.style.display = "none";
        // });

        // // Optional: click outside closes
        // window.addEventListener("click", e => {
        //     if (e.target === generateModal) generateModal.style.display = "none";
        // });

        // Open & Close Modal
        function openAddCpriModal() {
            document.getElementById('addCpriModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
            populateCpriProducts();
        }

        function closeAddCpriModal() {
            document.getElementById('addCpriModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('addCpriForm').reset();
        }

        // Populate products dropdown
        async function populateCpriProducts() {
            const select = document.getElementById('cpriProductId');
            select.innerHTML = '<option value="">-- Select Product --</option>';
            try {
                const res = await fetch('api/get_products.php');
                const data = await res.json();
                if (data.success) {
                    data.products.forEach(p => {
                        const option = document.createElement('option');
                        option.value = p.product_id;
                        option.dataset.productName = p.name;
                        option.textContent = `${p.product_id} - ${p.name}`;
                        select.appendChild(option);
                    });
                }
            } catch (err) {
                console.error('Error fetching products', err);
            }
        }

        function openGenerateReportModal() {
            document.getElementById("generateReportModal").style.display = "block";
            document.body.style.overflow = "hidden";
        }

        function closeGenerateReportModal() {
            document.getElementById("generateReportModal").style.display = "none";
            document.body.style.overflow = "auto";
            document.getElementById("generateReportForm").reset();
        }

        // document.addEventListener("DOMContentLoaded", () => {
        //     const openBtn = document.getElementById("openGenerateReport");
        //     const modal = document.getElementById("generateReportModal");

        //     openBtn.addEventListener("click", () => {
        //         modal.style.display = "flex";
        //     });

        //     // Close when clicking outside
        //     window.addEventListener("click", (e) => {
        //         if (e.target === modal) {
        //             modal.style.display = "none";
        //         }
        //     });
        // });

        // Update hidden product_name field
        document.getElementById('cpriProductId').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('cpriProductName').value = selectedOption.dataset.productName || '';
        });

        // Handle Add CPRI Form Submission
        document.getElementById('addCpriForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Ensure product_name is included
            const productSelect = document.getElementById('cpriProductId');
            formData.set('product_name', productSelect.options[productSelect.selectedIndex].dataset.productName || '');

            const submitBtn = this.querySelector('[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';

            try {
                const res = await fetch('api/add_cpri.php', {
                    method: 'POST',
                    body: formData
                });
                const responseText = await res.text();

                try {
                    const data = JSON.parse(responseText);
                    if (data.status === 'success') {
                        alert(data.message);
                        closeAddCpriModal();
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                } catch (e) {
                    console.error('Invalid JSON:', responseText);
                    alert('Server returned invalid response. Check console.');
                }
            } catch (err) {
                alert('Network error: ' + err.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';
            }
        });
        // --- GENERATE REPORT FORM SUBMISSION ---
        document.getElementById("generateReportForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            const reportName = document.getElementById("reportName").value.trim();
            const reportType = document.getElementById("reportType").value;
            const productType = document.getElementById("productType").value;
            const format = document.getElementById("format").value;
            const generatedBy = document.getElementById("generatedBy").value;

            if (!reportName) return alert("Please enter a report name.");
            if (!reportType) return alert("Please select a report type.");

            const formData = new FormData();
            formData.append("report_name", reportName);
            formData.append("report_type", reportType);
            formData.append("product_type", productType);
            formData.append("format", format);
            formData.append("generated_by", generatedBy);

            const submitBtn = this.querySelector('[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = "Generating...";

            try {
                const res = await fetch("api/insert_report.php", {
                    method: "POST",
                    body: formData
                });

                if (!res.ok) return alert("Server error: " + res.status);

                const data = await res.json();

                if (!data.success) {
                    alert("⚠️ Error: " + data.message);
                    if (data.stmt_error) console.error("DB Error:", data.stmt_error);
                    return;
                }

                alert("✅ Report generated successfully!");

                const tableBody = document.querySelector("#recentReportsTable tbody");
                if (tableBody) {
                    const emptyRow = tableBody.querySelector("tr td[colspan='7'], tr td[colspan='6']");
                    if (emptyRow) emptyRow.parentElement.remove();

                    const tr = document.createElement("tr");
                    const generatedDate = new Date(data.date_generated);
                    const dateStr = generatedDate.toLocaleDateString("en-GB", {
                        day: "2-digit",
                        month: "short",
                        year: "numeric",
                    });

                    let statusClass = "status-processing";
                    if (data.status === "completed") statusClass = "status-completed";
                    else if (data.status === "failed") statusClass = "status-failed";
                    else if (data.status === "pending") statusClass = "status-pending";

                    const testerName = data.generated_by_name || "-";
                    const isAdmin = <?= json_encode($isAdminLoggedIn); ?>;

                    let actionsCell = "";
                    if (isAdmin) {
                        actionsCell = `
                <td>
                    <div class="action-buttons">
                        <button class="action-btn edit-btn"
                            data-type="generated"
                            data-id="${data.report_id}"
                            data-name="${reportName}"
                            data-rtype="${reportType}"
                            data-format="${format}"
                            data-status="${data.status}"
                            data-generated_by="${generatedBy}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="action-btn delete-btn" data-id="${data.report_id}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </td>`;
                    }

                    tr.innerHTML = `
                <td>
                    <a href="api/view_report.php?id=${data.report_id}" target="_blank"
                        style="text-decoration:none;color:var(--primary-blue);font-weight:600;">
                        ${reportName}
                    </a>
                </td>
                <td>${reportType}</td>
                <td>${dateStr}</td>
                <td>${format.toUpperCase()}</td>
                <td><span class="status-badge ${statusClass}">${data.status.replace("-", " ")}</span></td>
                <td>${testerName}</td>
                ${actionsCell}
            `;

                    tableBody.prepend(tr);

                    // Attach event listeners for edit/delete buttons
                    attachRowActions(tr);
                }

                closeGenerateReportModal();
                this.reset();
            } catch (err) {
                console.error(err);
                alert("❌ Unexpected error occurred.");
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = "Generate Report";
            }
        });
    </script>

    <?php if ($isAdminLoggedIn): ?>
        <!-- Generate Custom Report -->
        <section id="generate-report" class="generate-report-wrapper">
            <div class="form-section generate-report-card">
                <h2 class="section-title">Generate Custom Report</h2>
                <div class="filter-grid">
                    <div class="form-group">
                        <label class="form-label" for="reportName">Report Name</label>
                        <input type="text" class="form-control" id="reportName" placeholder="Enter new report name">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="reportType">Report Type</label>
                        <select class="form-control" id="reportType">
                            <option value="">Select Report Type</option>
                            <?php foreach ($reportTypes as $type): ?>
                                <option value="<?= htmlspecialchars($type['type_name']) ?>"><?= htmlspecialchars($type['type_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="generatedBy">Generated By</label>
                        <select class="form-control" id="generatedBy">
                            <option value="">Select Tester</option>
                            <?php foreach ($testers as $t): ?>
                                <option value="<?= $t['tester_id'] ?>"><?= htmlspecialchars($t['tester_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="startDate">Start Date</label>
                        <input type="date" class="form-control" id="startDate" value="">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="endDate">End Date</label>
                        <input type="date" class="form-control" id="endDate" value="">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="productType">Product Type</label>
                        <select class="form-control" id="productType">
                            <option value="">All Product Types</option>
                            <option value="switchgear">Switchgear</option>
                            <option value="control-panels">Control Panels</option>
                            <option value="capacitors">Capacitors</option>
                            <option value="fuses">Fuses</option>
                            <option value="resistors">Resistors</option>
                            <option value="testing-equipment">Testing Equipment</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="testStatus">Test Status</label>
                        <select class="form-control" id="testStatus">
                            <option value="">All Statuses</option>
                            <option value="passed">Passed</option>
                            <option value="failed">Failed</option>
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="format">Report Format</label>
                        <select class="form-control" id="format">
                            <option value="pdf">PDF Document</option>
                            <option value="doc">Word Document</option>
                            <option value="excel">Excel Spreadsheet</option>
                            <option value="csv">CSV Data</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn btn-primary" id="generateReportBtn"><i class="fas fa-file-download"></i> Generate Report</button>
                    <button class="btn btn-secondary" id="scheduleReportBtn"><i class="fas fa-calendar-plus"></i> Schedule Report</button>
                </div>
            </div>
        </section>
    <?php endif; ?>
</body>

</html>