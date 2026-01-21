<?php include 'dashboard_auth.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPRI Testing | SRS Electrical Appliances</title>
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
            --warning-orange: #ffc107;
            --info-cyan: #17a2b8;
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

        /* Navbar Styles */
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

        /* CPRI Dashboard Specific Styles */

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
            margin-bottom: 40px;
            border-radius: 10px;
        }

        .hero-content h1 {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: white;
        }

        .hero-content p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto 30px;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-approved {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--success-green);
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: #b58900;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-failed {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--danger-red);
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        /* Cards */
        .card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.3rem;
            color: var(--primary-blue);
            font-weight: 600;
        }

        /* CPRI Submission Cards */
        .cpri-card {
            border-left: 4px solid var(--accent-blue);
        }

        .cpri-card.approved {
            border-left-color: var(--success-green);
            background-color: rgba(40, 167, 69, 0.03);
        }

        .cpri-card.pending {
            border-left-color: var(--warning-orange);
            background-color: rgba(255, 193, 7, 0.03);
        }

        .cpri-card.rejected {
            border-left-color: var(--danger-red);
            background-color: rgba(220, 53, 69, 0.03);
        }

        .cpri-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.85rem;
            color: var(--medium-gray);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Checklist */
        .checklist-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
            padding: 12px;
            background: white;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .checklist-item:hover {
            border-color: var(--accent-blue);
        }

        .checklist-item.completed {
            background: rgba(40, 167, 69, 0.08);
            border-color: rgba(40, 167, 69, 0.3);
        }

        .checkmark {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--medium-gray);
            flex-shrink: 0;
        }

        .checklist-item.completed .checkmark {
            background: var(--success-green);
            color: white;
        }

        /* BUTTONS - FIXED STYLING */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            line-height: 1.5;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
        }

        .btn i {
            font-size: 0.9em;
        }

        .btn-primary {
            background-color: var(--accent-blue);
            color: white;
            border: 1px solid var(--accent-blue);
        }

        .btn-primary:hover {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(42, 134, 186, 0.3);
        }

        .btn-success {
            background-color: var(--success-green);
            color: white;
            border: 1px solid var(--success-green);
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .btn-warning {
            background-color: var(--warning-orange);
            color: #212529;
            border: 1px solid var(--warning-orange);
        }

        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: 1px solid #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        .btn-info {
            background-color: var(--info-cyan);
            color: white;
            border: 1px solid var(--info-cyan);
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
        }

        /* Report Specific Button Styles - FIXED */
        .btn-report {
            background-color: #8e44ad;
            color: white;
            border: 1px solid #8e44ad;
        }

        .btn-report:hover {
            background-color: #7d3c98;
            border-color: #6c3483;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(142, 68, 173, 0.3);
        }

        .btn-download {
            background-color: #3498db;
            color: white;
            border: 1px solid #3498db;
        }

        .btn-download:hover {
            background-color: #2980b9;
            border-color: #2471a3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        /* Button Sizes */
        .btn-sm {
            padding: 6px 16px;
            font-size: 0.85rem;
            gap: 6px;
        }

        .btn-lg {
            padding: 14px 30px;
            font-size: 1.1rem;
            gap: 10px;
        }

        /* Button Group */
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        /* Progress Bar */
        .progress {
            height: 8px;
            background: var(--light-gray);
            border-radius: 4px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-bar {
            height: 100%;
            background: var(--accent-blue);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .progress-bar.warning {
            background: var(--warning-orange);
        }

        .progress-bar.success {
            background: var(--success-green);
        }

        /* Tables */
        .table-container {
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h3 {
            color: var(--primary-blue);
            font-size: 1.4rem;
        }

        .table-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--medium-gray);
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            width: 250px;
            transition: var(--transition);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(42, 134, 186, 0.2);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background-color: #f8f9fa;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: var(--primary-blue);
            border-bottom: 2px solid var(--border-color);
        }

        .data-table td {
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table tr:hover {
            background-color: rgba(42, 134, 186, 0.03);
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        /* Action Buttons in Tables - FIXED */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            border: none;
            background: transparent;
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
        }

        .btn-icon:hover {
            background-color: rgba(42, 134, 186, 0.1);
            color: var(--accent-blue);
            transform: translateY(-2px);
        }

        .btn-icon.btn-view {
            color: var(--accent-blue);
        }

        .btn-icon.btn-download {
            color: var(--success-green);
        }

        .btn-icon.btn-edit {
            color: var(--warning-orange);
        }

        .btn-icon.btn-delete {
            color: var(--danger-red);
        }

        .btn-icon.btn-report {
            color: #8e44ad;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal {
            background-color: var(--white);
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            color: var(--primary-blue);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--danger-red);
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(42, 134, 186, 0.2);
        }

        .form-control.select {
            background-color: white;
            cursor: pointer;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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
            color: var(--white);
            text-decoration: none;
            transition: var(--transition);
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
        @media (max-width: 992px) {
            .page-title {
                font-size: 2.2rem;
            }

            .hero-content h1 {
                font-size: 2.2rem;
            }

            .cpri-details-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }

            .nav-menu {
                position: absolute;
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

            .hero-section {
                padding: 60px 20px;
            }

            .hero-content h1 {
                font-size: 1.8rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .card-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .cpri-details-grid {
                grid-template-columns: 1fr;
            }

            .btn-group {
                flex-wrap: wrap;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .table-actions {
                width: 100%;
            }

            .search-box input {
                width: 100%;
            }

            .data-table {
                display: block;
                overflow-x: auto;
            }

            .action-buttons {
                flex-wrap: wrap;
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

            .hero-section {
                padding: 50px 15px;
            }

            .card {
                padding: 20px;
            }

            .modal {
                width: 95%;
                margin: 10px;
            }

            .btn {
                padding: 8px 20px;
                font-size: 0.9rem;
            }

            .btn-group {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-group .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- Header & Navigation -->
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
                        <a href="lab-testing.php" class="active">Lab Testing <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="report.php">Reports</a>
                            <a href="cpri.php">CPRI Testing</a>
                        </div>
                    </li>
                    <li><a href="product.php">Product Catalog</a></li>
                    <li><a href="contact.php">Contact Us</a></li>

                </ul>
            </nav>
        </div>
    </header>

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">CPRI Testing & Certification</h1>
            <p class="page-subtitle">Central Power Research Institute Approved Testing Services for Electrical Equipment</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1>CPRI Certified Testing Laboratory</h1>
                <p>SRS Electrical Appliances is a CPRI approved testing facility offering comprehensive testing and certification services for electrical equipment as per Indian and international standards.</p>
            </div>
        </section>

        <!-- CPRI Submission Table -->
        <section>
            <div class="table-container">
                <div class="table-header">
                    <h3>CPRI Test Submissions</h3>
                    <div class="table-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="cpriSearch" placeholder="Search CPRI submissions...">
                        </div>
                        <button class="btn btn-primary" id="submitToCPRIBtn">
                            <i class="fas fa-paper-plane"></i>
                            Submit to CPRI
                        </button>
                        <button class="btn btn-report" id="generateReportBtn">
                            <i class="fas fa-file-alt"></i>
                            Generate Report
                        </button>
                    </div>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Submission Date</th>
                            <th>CPRI Reference</th>
                            <th>Test Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cpriTableBody">
                        <!-- Data loaded via JavaScript -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- CPRI Approval Status -->
        <section>
            <h2 class="section-title">CPRI Approval Status</h2>

            <!-- Approved Card -->
            <div class="card cpri-card approved">
                <div class="card-header">
                    <div>
                        <div class="card-title">High Voltage Circuit Breaker - Model XT-5000</div>
                        <div style="color: var(--medium-gray); font-size: 0.95rem; margin-top: 5px;">
                            Submitted: 2023-10-20 | Approved: 2023-11-15
                        </div>
                    </div>
                    <span class="status-badge status-approved">Approved</span>
                </div>

                <div class="cpri-details-grid">
                    <div class="detail-item">
                        <span class="detail-label">CPRI Reference</span>
                        <span class="detail-value">CPRI-2023-1245</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Certificate No</span>
                        <span class="detail-value">CPRI/CERT/4523/2023</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Valid Until</span>
                        <span class="detail-value">2026-11-15</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Testing Lab</span>
                        <span class="detail-value">CPRI Central Lab, Bangalore</span>
                    </div>
                </div>

                <div style="margin: 20px 0;">
                    <h4 style="color: var(--primary-blue); margin-bottom: 15px;">Tests Performed:</h4>
                    <div class="checklist-item completed">
                        <div class="checkmark">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <div>Dielectric Test</div>
                            <small style="color: var(--success-green);">Passed - 50kV withstand</small>
                        </div>
                    </div>
                    <div class="checklist-item completed">
                        <div class="checkmark">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <div>Temperature Rise Test</div>
                            <small style="color: var(--success-green);">Passed - Max temp: 65°C</small>
                        </div>
                    </div>
                    <div class="checklist-item completed">
                        <div class="checkmark">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <div>Short Circuit Test</div>
                            <small style="color: var(--success-green);">Passed - 25kA breaking capacity</small>
                        </div>
                    </div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-success">
                        <i class="fas fa-download"></i>
                        Download Certificate
                    </button>
                    <button class="btn btn-download">
                        <i class="fas fa-file-pdf"></i>
                        Download Report
                    </button>
                    <button class="btn btn-info">
                        <i class="fas fa-print"></i>
                        Print Report
                    </button>
                </div>
            </div>

            <!-- Pending Card -->
            <div class="card cpri-card pending">
                <div class="card-header">
                    <div>
                        <div class="card-title">Medium Voltage Switchgear - Model MV-3200</div>
                        <div style="color: var(--medium-gray); font-size: 0.95rem; margin-top: 5px;">
                            Submitted: 2023-11-02 | CPRI Reference: CPRI-2023-1356
                        </div>
                    </div>
                    <span class="status-badge status-pending">Testing in Progress</span>
                </div>

                <div class="cpri-details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Current Status</span>
                        <span class="detail-value">Dielectric Testing Phase</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Lab Contact</span>
                        <span class="detail-value">Mr. A. Kumar (+91 9876543210)</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Estimated Completion</span>
                        <span class="detail-value">2023-11-25</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Priority</span>
                        <span class="detail-value" style="color: var(--warning-orange);">High</span>
                    </div>
                </div>

                <div style="margin: 20px 0;">
                    <h4 style="color: var(--primary-blue); margin-bottom: 10px;">Test Progress</h4>
                    <div class="progress">
                        <div class="progress-bar warning" style="width: 60%;"></div>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 8px; font-size: 0.9rem;">
                        <span>Dielectric Test (In Progress)</span>
                        <span>60% complete</span>
                    </div>
                </div>

                <div class="btn-group">
                    <button class="btn btn-warning" id="trackStatusBtn">
                        <i class="fas fa-sync-alt"></i>
                        Refresh Status
                    </button>
                    <button class="btn btn-secondary">
                        <i class="fas fa-file-alt"></i>
                        View Test Plan
                    </button>
                    <button class="btn btn-primary">
                        <i class="fas fa-phone"></i>
                        Contact Lab
                    </button>
                    <button class="btn btn-report">
                        <i class="fas fa-chart-line"></i>
                        Progress Report
                    </button>
                </div>
            </div>

            <!-- Rejected Card -->
            <div class="card cpri-card rejected">
                <div class="card-header">
                    <div>
                        <div class="card-title">Digital Energy Meter - Model DEM-2023</div>
                        <div style="color: var(--medium-gray); font-size: 0.95rem; margin-top: 5px;">
                            Submitted: 2023-10-15 | Rejected: 2023-11-05
                        </div>
                    </div>
                    <span class="status-badge status-failed">Rejected</span>
                </div>

                <div class="cpri-details-grid">
                    <div class="detail-item">
                        <span class="detail-label">CPRI Reference</span>
                        <span class="detail-value">CPRI-2023-1123</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Test Date</span>
                        <span class="detail-value">2023-10-30</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Testing Lab</span>
                        <span class="detail-value">CPRI Regional Lab, Chennai</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Report No</span>
                        <span class="detail-value">TR-2023-1123-MTR</span>
                    </div>
                </div>

                <div style="background: rgba(220, 53, 69, 0.05); padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid rgba(220, 53, 69, 0.2);">
                    <h4 style="color: var(--danger-red); margin-bottom: 10px;">Rejection Reasons</h4>
                    <ul style="margin-left: 20px; margin-bottom: 10px;">
                        <li>Accuracy test failed beyond ±1% tolerance</li>
                        <li>Display visibility issues under low light conditions</li>
                        <li>Sealing mechanism not meeting IP54 requirements</li>
                    </ul>
                    <p style="font-size: 0.95rem;">
                        <strong>Recommendation:</strong> Redesign measurement circuit and improve display backlight.
                    </p>
                </div>

                <div class="btn-group">
                    <button class="btn btn-primary" id="resubmitBtn">
                        <i class="fas fa-redo"></i>
                        Prepare for Re-submission
                    </button>
                    <button class="btn btn-download">
                        <i class="fas fa-download"></i>
                        Download Test Report
                    </button>
                    <button class="btn btn-report">
                        <i class="fas fa-file-excel"></i>
                        Export Analysis
                    </button>
                </div>
            </div>
        </section>

        <!-- Upcoming CPRI Schedules -->
        <section>
            <div class="table-container">
                <div class="table-header">
                    <h3>Upcoming CPRI Test Schedules</h3>
                    <div class="table-actions">
                        <button class="btn btn-report">
                            <i class="fas fa-calendar-alt"></i>
                            Schedule Report
                        </button>
                    </div>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Scheduled Date</th>
                            <th>Test Type</th>
                            <th>Lab Location</th>
                            <th>Preparedness</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Smart Circuit Breaker</td>
                            <td>2023-11-28</td>
                            <td>Type Test</td>
                            <td>CPRI Bangalore</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="progress" style="flex: 1;">
                                        <div class="progress-bar" style="width: 80%;"></div>
                                    </div>
                                    <span>80%</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-icon btn-report" title="Generate Report">
                                        <i class="fas fa-file-alt"></i>
                                    </button>
                                    <button class="btn-icon btn-download" title="Download Schedule">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Protection Relay</td>
                            <td>2023-12-05</td>
                            <td>Routine Test</td>
                            <td>CPRI Hyderabad</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="progress" style="flex: 1;">
                                        <div class="progress-bar warning" style="width: 45%;"></div>
                                    </div>
                                    <span>45%</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-icon btn-report" title="Generate Report">
                                        <i class="fas fa-file-alt"></i>
                                    </button>
                                    <button class="btn-icon btn-download" title="Download Schedule">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <!-- Submit to CPRI Modal -->
    <div class="modal-overlay" id="submitCPRIModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Submit Product to CPRI</h3>
                <button class="modal-close" id="closeCPRIModal">&times;</button>
            </div>

            <div class="modal-body">
                <form id="submitCPRIForm">
                    <div class="form-group">
                        <label for="cpriProductSelect">Select Product</label>
                        <select id="cpriProductSelect" class="form-control select" required>
                            <option value="">Select product for CPRI submission</option>
                            <option value="PRD-007">PRD-007 - Smart Circuit Breaker</option>
                            <option value="PRD-008">PRD-008 - Distribution Transformer</option>
                            <option value="PRD-009">PRD-009 - Protection Relay</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Required Tests</label>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-top: 8px;">
                            <label style="display: flex; align-items: center; gap: 8px; padding: 8px; background: var(--light-gray); border-radius: 4px;">
                                <input type="checkbox" name="cpriTest" value="dielectric"> Dielectric Test
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 8px; background: var(--light-gray); border-radius: 4px;">
                                <input type="checkbox" name="cpriTest" value="temperature"> Temperature Rise Test
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 8px; background: var(--light-gray); border-radius: 4px;">
                                <input type="checkbox" name="cpriTest" value="short_circuit"> Short Circuit Test
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 8px; background: var(--light-gray); border-radius: 4px;">
                                <input type="checkbox" name="cpriTest" value="accuracy"> Accuracy Test
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 8px; background: var(--light-gray); border-radius: 4px;">
                                <input type="checkbox" name="cpriTest" value="safety"> Safety Compliance Test
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; padding: 8px; background: var(--light-gray); border-radius: 4px;">
                                <input type="checkbox" name="cpriTest" value="environmental"> Environmental Test
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cpriPriority">Priority Level</label>
                        <select id="cpriPriority" class="form-control select" required>
                            <option value="normal">Normal (4-6 weeks)</option>
                            <option value="high">High Priority (2-3 weeks)</option>
                            <option value="urgent">Urgent (1 week, extra charges apply)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cpriLab">Preferred CPRI Lab</label>
                        <select id="cpriLab" class="form-control select" required>
                            <option value="bangalore">Bangalore (Central Lab)</option>
                            <option value="chennai">Chennai (Regional Lab)</option>
                            <option value="hyderabad">Hyderabad (Regional Lab)</option>
                            <option value="kolkata">Kolkata (Regional Lab)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cpriNotes">Additional Notes</label>
                        <textarea id="cpriNotes" class="form-control" rows="3" placeholder="Any special instructions for CPRI lab..."></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelCPRIBtn">Cancel</button>
                <button class="btn btn-primary" id="saveCPRIBtn">Submit to CPRI</button>
                <button class="btn btn-report" id="saveAndReportBtn">
                    <i class="fas fa-file-export"></i>
                    Submit & Generate Report
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
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
                    <a href="lab-testing.php">Lab Testing</a>
                    <a href="cpri.php" class="active">CPRI Testing</a>
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
        // Mobile Navigation Toggle - EXACTLY SAME AS OTHER PAGES
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');

        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileToggle.innerHTML = navMenu.classList.contains('active') ?
                '<i class="fas fa-times"></i>' :
                '<i class="fas fa-bars"></i>';
        });

        // Close mobile menu when clicking on a link - EXACTLY SAME AS OTHER PAGES
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });

        // CPRI Testing specific JavaScript
        const cpriSubmissions = [{
                id: 'PRD-001',
                name: 'HV Circuit Breaker',
                submissionDate: '2023-10-20',
                cpriRef: 'CPRI-2023-1245',
                testDate: '2023-11-05',
                status: 'approved'
            },
            {
                id: 'PRD-005',
                name: 'Medium Voltage Switchgear',
                submissionDate: '2023-11-02',
                cpriRef: 'CPRI-2023-1356',
                testDate: '2023-11-15',
                status: 'pending'
            },
            {
                id: 'PRD-002',
                name: 'Digital Energy Meter',
                submissionDate: '2023-10-15',
                cpriRef: 'CPRI-2023-1123',
                testDate: '2023-10-30',
                status: 'rejected'
            }
        ];

        function loadCPRITable() {
            const tbody = document.getElementById('cpriTableBody');
            tbody.innerHTML = '';

            cpriSubmissions.forEach(submission => {
                let statusClass = '';
                let statusText = '';

                switch (submission.status) {
                    case 'approved':
                        statusClass = 'status-approved';
                        statusText = 'Approved';
                        break;
                    case 'pending':
                        statusClass = 'status-pending';
                        statusText = 'Pending';
                        break;
                    case 'rejected':
                        statusClass = 'status-failed';
                        statusText = 'Rejected';
                        break;
                }

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${submission.id}</td>
                    <td>${submission.name}</td>
                    <td>${submission.submissionDate}</td>
                    <td>${submission.cpriRef}</td>
                    <td>${submission.testDate}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon btn-view" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn-icon btn-report" title="Generate Report">
                                <i class="fas fa-file-alt"></i>
                            </button>
                            <button class="btn-icon btn-download" title="Download Certificate">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </td>
                `;

                tbody.appendChild(row);
            });
        }

        // Modal functionality
        document.getElementById('submitToCPRIBtn').addEventListener('click', () => {
            document.getElementById('submitCPRIModal').style.display = 'flex';
        });

        document.getElementById('generateReportBtn').addEventListener('click', () => {
            showNotification('Generating CPRI Testing Report...', 'success');
            setTimeout(() => {
                showNotification('Report generated successfully! Ready for download.', 'success');
            }, 1500);
        });

        document.getElementById('closeCPRIModal').addEventListener('click', () => {
            document.getElementById('submitCPRIModal').style.display = 'none';
        });

        document.getElementById('cancelCPRIBtn').addEventListener('click', () => {
            document.getElementById('submitCPRIModal').style.display = 'none';
        });

        document.getElementById('saveCPRIBtn').addEventListener('click', () => {
            handleCPRISubmission(false);
        });

        document.getElementById('saveAndReportBtn').addEventListener('click', () => {
            handleCPRISubmission(true);
        });

        function handleCPRISubmission(generateReport) {
            const productSelect = document.getElementById('cpriProductSelect');
            const priority = document.getElementById('cpriPriority').value;
            const lab = document.getElementById('cpriLab').value;
            const notes = document.getElementById('cpriNotes').value;

            if (!productSelect.value) {
                showNotification('Please select a product', 'error');
                return;
            }

            // Get selected tests
            const selectedTests = Array.from(document.querySelectorAll('input[name="cpriTest"]:checked'))
                .map(cb => cb.value);

            if (selectedTests.length === 0) {
                showNotification('Please select at least one test', 'error');
                return;
            }

            // Simulate submission
            setTimeout(() => {
                const productId = productSelect.value;
                const productName = productSelect.options[productSelect.selectedIndex].text;
                const cpriRef = 'CPRI-2023-' + (Math.floor(Math.random() * 9000) + 1000);

                // Add to submissions
                cpriSubmissions.unshift({
                    id: productId,
                    name: productName.split(' - ')[1],
                    submissionDate: new Date().toISOString().split('T')[0],
                    cpriRef: cpriRef,
                    testDate: '',
                    status: 'pending'
                });

                // Update table
                loadCPRITable();

                // Close modal
                document.getElementById('submitCPRIModal').style.display = 'none';

                // Reset form
                document.getElementById('submitCPRIForm').reset();

                // Show success
                let message = `Product submitted to CPRI successfully! Reference: ${cpriRef}.`;
                if (generateReport) {
                    message += ' Report generation in progress...';
                    setTimeout(() => {
                        showNotification('CPRI Submission Report generated and ready for download.', 'success');
                    }, 1000);
                }
                showNotification(message, 'success');
            }, 1500);
        }

        // Track status button
        document.getElementById('trackStatusBtn').addEventListener('click', () => {
            showNotification('Fetching latest status from CPRI...', 'success');

            setTimeout(() => {
                showNotification('Status updated: Dielectric test completed, starting temperature rise test.', 'success');
            }, 2000);
        });

        // Resubmit button
        document.getElementById('resubmitBtn').addEventListener('click', () => {
            if (confirm('Move this product to re-manufacturing for corrections?')) {
                showNotification('Product moved to re-manufacturing queue for corrections.', 'success');
            }
        });

        // Search functionality
        document.getElementById('cpriSearch').addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('#cpriTableBody tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Add click handlers for report buttons in the table
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-icon.btn-report')) {
                const row = e.target.closest('tr');
                const productId = row.cells[0].textContent;
                const productName = row.cells[1].textContent;
                showNotification(`Generating report for ${productName} (${productId})...`, 'success');
                setTimeout(() => {
                    showNotification(`Report for ${productName} generated successfully!`, 'success');
                }, 1500);
            }

            if (e.target.closest('.btn-icon.btn-download')) {
                const row = e.target.closest('tr');
                const productId = row.cells[0].textContent;
                const productName = row.cells[1].textContent;
                showNotification(`Downloading certificate for ${productName}...`, 'success');
            }

            if (e.target.closest('.btn-icon.btn-view')) {
                const row = e.target.closest('tr');
                const productId = row.cells[0].textContent;
                const productName = row.cells[1].textContent;
                showNotification(`Opening details for ${productName}...`, 'info');
            }
        });

        // Notification function
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 6px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                animation: slideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
            `;

            if (type === 'success') {
                notification.style.backgroundColor = 'var(--success-green)';
                notification.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            } else if (type === 'error') {
                notification.style.backgroundColor = 'var(--danger-red)';
                notification.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            } else if (type === 'info') {
                notification.style.backgroundColor = 'var(--info-cyan)';
                notification.innerHTML = `<i class="fas fa-info-circle"></i> ${message}`;
            } else {
                notification.style.backgroundColor = 'var(--accent-blue)';
                notification.innerHTML = `<i class="fas fa-bell"></i> ${message}`;
            }

            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);

            // Add CSS for animations
            if (!document.querySelector('#notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOut {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
        }

        // Update year in footer
        document.addEventListener('DOMContentLoaded', function() {
            loadCPRITable();
            const currentYear = new Date().getFullYear();
            const yearElement = document.querySelector('.footer-bottom p');
            yearElement.innerHTML = yearElement.innerHTML.replace('2023', currentYear);

            // Add event listeners to all report buttons
            document.querySelectorAll('.btn-report').forEach(btn => {
                btn.addEventListener('click', function() {
                    showNotification('Generating report...', 'info');
                });
            });
        });
    </script>
</body>

</html>