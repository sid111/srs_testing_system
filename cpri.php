    <?php
    include 'config/admin_session.php';
    include 'config/conn.php';
    ?>

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
            max-width: 1500px;
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
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/lab testing 1.jpg');
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
            display:flex;
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

        /* Footer social icons fix */
        .social-links a,
        .social-icons a,
        .footer-social a {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .social-links a i,
        .social-icons a i,
        .footer-social a i {
            line-height: 1;
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
                        <li><a href="product.php">Product Catalog</a></li>
                        <li class="dropdown">
                            <?php if ($isAdminLoggedIn): ?>
                                <a href="lab-testing.php">Lab Testing <i class="fas fa-chevron-down"></i></a>
                                <div class="dropdown-content">
                                    <a href="report.php">Report</a>
                                    <a href="cpri.php">CPRI Testing</a>
                                </div>
                            <?php endif; ?>
                        </li>
                        <li>
                            <?php if ($isAdminLoggedIn): ?>
                                <a href="config/logout.php" style="color: var(--danger-red); font-weight: 700;">Logout</a>
                            <?php else: ?>
                                <a href="dashboard.php" class="btn login-btn" id="loginBtn">Login</a>
                            <?php endif; ?>
                        </li>
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
                            <?php
                            $result = $conn->query("
                                SELECT c.*, p.name AS product_name
                                FROM cpri_reports c
                                LEFT JOIN products p ON c.product_id = p.product_id
                                ORDER BY c.id DESC
                            ");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $product_id = htmlspecialchars($row['product_id'] ?? '');
                                    $product_name = htmlspecialchars($row['product_name'] ?? '');
                                    $submission_date = $row['submission_date'] ?? '';
                                    $cpri_reference = $row['cpri_reference'] ?? 'N/A';
                                    $test_date = $row['test_date'] ?? 'N/A';
                                    $status = $row['status'] ?? 'pending';

                                    $statusClass = $status === 'approved' ? 'status-approved' : ($status === 'rejected' ? 'status-failed' : 'status-pending');

                                    echo "<tr>
                                        <td>{$product_id}</td>
                                        <td>{$product_name}</td>
                                        <td>{$submission_date}</td>
                                        <td>{$cpri_reference}</td>
                                        <td>{$test_date}</td>
                                        <td><span class='status-badge {$statusClass}'>" . ucfirst($status) . "</span></td>
                                        <td>
                                            <div class='action-buttons'>
                                                <button class='btn-icon btn-view' title='View Certificate' onclick='viewCertificate({$row['id']})'>
                                                    <i class='fas fa-eye'></i>
                                                </button>
                                                <button class='btn-icon btn-edit' title='Edit Record' onclick='editCertificate({$row['id']})'>
                                                    <i class='fas fa-edit'></i>
                                                </button>
                                                <button class='btn-icon btn-delete' title='Delete Submission' onclick='deleteCertificate({$row['id']})'>
                                                    <i class='fas fa-trash'></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo '<tr><td colspan="7">No CPRI submissions found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- CPRI Approval Status Cards -->
            <section id="cpri-status-section">
                <h2 class="section-title">CPRI Approval Status</h2>
                <div class="cards-container">
                    <?php
                    $result = $conn->query("
                    SELECT c.*, p.name AS product_name
                    FROM cpri_reports c
                    LEFT JOIN products p ON c.product_id = p.product_id
                    ORDER BY c.id DESC
                    ");
                    if ($result->num_rows > 0) {
                        while ($submission = $result->fetch_assoc()) {
                            $product_name = htmlspecialchars($submission['product_name'] ?? '');
                            $submission_date = $submission['submission_date'] ?? '';
                            $product_id = $submission['product_id'] ?? '';
                            $cpri_reference = $submission['cpri_reference'] ?? 'N/A';
                            $test_date = $submission['test_date'] ?? 'N/A';
                            $status = $submission['status'] ?? 'pending';

                            $cardClass = '';
                            $statusBadge = '';
                            switch ($status) {
                                case 'approved':
                                    $cardClass = 'approved';
                                    $statusBadge = '<span class="status-badge status-approved">Approved</span>';
                                    break;
                                case 'pending':
                                    $cardClass = 'pending';
                                    $statusBadge = '<span class="status-badge status-pending">Pending</span>';
                                    break;
                                case 'rejected':
                                    $cardClass = 'rejected';
                                    $statusBadge = '<span class="status-badge status-failed">Rejected</span>';
                                    break;
                            }

                            echo "<div class='card cpri-card {$cardClass}'>
                <div class='card-header'>
                    <div>
                        <div class='card-title'>{$product_name}</div>
                        <div style='color: var(--medium-gray); font-size: 0.95rem; margin-top: 5px;'>
                            Submitted: {$submission_date}
                        </div>
                    </div>
                    {$statusBadge}
                </div>
                <div class='cpri-details-grid'>
                    <div class='detail-item'><span class='detail-label'>Product ID</span><span class='detail-value'>{$product_id}</span></div>
                    <div class='detail-item'><span class='detail-label'>CPRI Reference</span><span class='detail-value'>{$cpri_reference}</span></div>
                    <div class='detail-item'><span class='detail-label'>Test Date</span><span class='detail-value'>{$test_date}</span></div>
                </div>
            </div>";
                        }
                    } else {
                        echo '<p>No CPRI submissions found.</p>';
                    }
                    ?>
                </div>

            </section>

        </div>
        <!-- Footer -->
        <footer id="contact">
            <div class="container">
                <div class="footer-content">
                    <!-- Column 1: Contact Info -->
                    <div class="footer-column">
                        <h3>Contact Us</h3>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>+92 300 1234567</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>info@srselectrical.com</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>SRS Electrical Appliances Plot No 45, Industrial Area<br>Korangi Industrial Area Karachi, Pakistan</span>
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
                        <p>SRS Electrical Appliances is a Pakistan based electrical testing and lab automation company providing certified testing and CPRI support.</p>

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
                    <p>&copy; © 2026 SRS Electrical Appliances. All Rights Reserved. Karachi, Pakistan. ISO compliant testing and certification support.</p>
                </div>
            </div>
        </footer>
            

            <!-- Modal Overlay (Single, used for View/Edit/Submit) -->
            <div class="modal-overlay" id="certificateModal">
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="modal-title" id="modalTitle">Certificate</h3>
                        <button class="modal-close" onclick="closeCertificateModal()">&times;</button>
                    </div>
                    <div class="modal-body" id="certificateContent">
                        <!-- Content loaded dynamically -->
                    </div>
                </div>
            </div>

            <script>
                // Close modal
                function closeCertificateModal() {
                    document.getElementById('certificateModal').style.display = 'none';
                    document.getElementById('certificateContent').innerHTML = '';
                }

                // ------------------ VIEW CERTIFICATE ------------------
                function viewCertificate(id) {
                    fetch('api/view_cpri.php?id=' + id)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('modalTitle').innerText = 'View Certificate';
                            document.getElementById('certificateContent').innerHTML = html;
                            document.getElementById('certificateModal').style.display = 'flex';
                        });
                }

                // ------------------ EDIT CERTIFICATE ------------------
                function editCertificate(id) {
                    fetch('api/edit_cpri.php?id=' + id)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('modalTitle').innerText = 'Edit CPRI Record';
                            document.getElementById('certificateContent').innerHTML = html;
                            document.getElementById('certificateModal').style.display = 'flex';
                        });
                }

                // ------------------ SUBMIT NEW CPRI ------------------
                document.getElementById('submitToCPRIBtn').addEventListener('click', function() {
                    document.getElementById('modalTitle').innerText = 'Submit New Product to CPRI';
                    document.getElementById('certificateContent').innerHTML = `
                        <form id="submitCpriForm" class="submit-cpri-form">
                            <div class="form-group">
                                <label>Product ID</label>
                                <input type="text" name="product_id" required>
                            </div>
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="product_name" required>
                            </div>
                            <div class="form-group">
                                <label>Submission Date</label>
                                <input type="date" name="submission_date" required>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary" onclick="closeCertificateModal()">Cancel</button>
                            </div>
                        </form>
                    `;
                    document.getElementById('certificateModal').style.display = 'flex';

                    document.getElementById('submitCpriForm').addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(this);
                        fetch('api/add_cpri.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(res => res.json())
                            .then(resp => {
                                alert(resp.message);
                                if (resp.status === 'success') closeCertificateModal();
                            });
                    });
                });

                // Delete submission
                function deleteCertificate(id) {
                    if (confirm('Are you sure you want to delete this submission?')) {
                        fetch('api/delete_cpri.php?id=' + id)
                            .then(res => res.text())
                            .then(msg => {
                                alert(msg);
                                location.reload();
                            });
                    }
                }
            </script>

    </body>

    </html>