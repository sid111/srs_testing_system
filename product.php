<?php include 'config/admin_session.php'; ?>
<script>
    const IS_ADMIN = <?= isset($_SESSION['admin_id']) ? 'true' : 'false' ?>;
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog | SRS Electrical Appliances</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Styles and Reset - EXACTLY SAME AS OTHER PAGES */
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
            --purple: #6f42c1;
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

        .mobile-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary-blue);
        }

        /* Page Header  */
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

        /* Section Titles - EXACTLY SAME AS OTHER PAGES */
        .section-title {
            font-size: 1.8rem;
            color: var(--primary-blue);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-blue);
        }

        /* Product Catalog Specific Styles */

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/product.jpeg');
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

        /* Filters Section */
        .filters-section {
            background-color: var(--white);
            border-radius: 10px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .filters-title {
            font-size: 1.4rem;
            color: var(--primary-blue);
            font-weight: 600;
        }

        .filter-reset {
            color: var(--accent-blue);
            background: none;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            padding: 8px 16px;
            border-radius: 6px;
        }

        .filter-reset:hover {
            background-color: rgba(42, 134, 186, 0.1);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
            font-size: 0.95rem;
        }

        .filter-select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background-color: white;
            font-size: 0.95rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(42, 134, 186, 0.2);
        }

        .filter-range {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .range-value {
            min-width: 60px;
            font-weight: 600;
            color: var(--primary-blue);
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .product-card {
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 1;
        }

        .badge-new {
            background-color: var(--success-green);
            color: white;
        }

        .badge-popular {
            background-color: var(--warning-orange);
            color: #212529;
        }

        .badge-cpri {
            background-color: var(--accent-blue);
            color: white;
        }

        .badge-limited {
            background-color: var(--danger-red);
            color: white;
        }

        .product-image {
            height: 200px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: var(--transition);
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-image-placeholder {
            font-size: 4rem;
            color: var(--primary-blue);
            opacity: 0.7;
        }

        .product-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            font-size: 0.85rem;
            color: var(--medium-gray);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-title {
            font-size: 1.3rem;
            color: var(--primary-blue);
            margin-bottom: 10px;
            font-weight: 600;
            line-height: 1.4;
        }

        .product-description {
            color: var(--medium-gray);
            margin-bottom: 20px;
            font-size: 0.95rem;
            flex-grow: 1;
        }

        .product-specs {
            margin-bottom: 20px;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.9rem;
        }

        .spec-label {
            color: var(--medium-gray);
        }

        .spec-value {
            font-weight: 600;
            color: var(--dark-gray);
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .product-footer>div:last-child {
            display: flex;
            gap: 10px;
        }

        .product-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .product-price small {
            font-size: 0.9rem;
            color: var(--medium-gray);
            font-weight: 400;
        }

        /* Buttons */
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

        .btn-outline {
            background-color: transparent;
            color: var(--accent-blue);
            border: 1px solid var(--accent-blue);
        }

        .btn-outline:hover {
            background-color: rgba(42, 134, 186, 0.1);
            transform: translateY(-2px);
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

        .btn-sm {
            padding: 6px 16px;
            font-size: 0.85rem;
        }

        /* Product Categories */
        .categories-section {
            margin-bottom: 40px;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .category-card {
            background-color: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: var(--shadow);
            text-align: center;
            transition: var(--transition);
            cursor: pointer;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .category-icon {
            font-size: 3rem;
            color: var(--accent-blue);
            margin-bottom: 20px;
        }

        .category-count {
            display: inline-block;
            background-color: rgba(42, 134, 186, 0.1);
            color: var(--accent-blue);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            margin-top: 10px;
        }

        /* Product Features */
        .features-section {
            background-color: var(--white);
            border-radius: 10px;
            padding: 40px;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .feature-item {
            text-align: center;
            padding: 20px;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--accent-blue);
            margin-bottom: 15px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 40px;
        }

        .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 6px;
            background-color: white;
            color: var(--dark-gray);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: 1px solid var(--border-color);
        }

        .page-link:hover {
            background-color: rgba(42, 134, 186, 0.1);
            color: var(--accent-blue);
        }

        .page-link.active {
            background-color: var(--accent-blue);
            color: white;
            border-color: var(--accent-blue);
        }

        .page-link.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            color: white;
            padding: 60px 0;
            border-radius: 10px;
            margin-bottom: 40px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
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

        .social-icon i {
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            width: 100%;
            height: 100%;
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
            max-width: 800px;
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

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group-full {
            grid-column: 1 / -1;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .drop-zone {
            border: 2px dashed var(--border-color);
            border-radius: 6px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .drop-zone:hover {
            border-color: var(--accent-blue);
            background-color: #f8f9fa;
        }

        .hidden-file-input {
            display: none;
        }

        .image-preview {
            position: relative;
            width: 200px;
        }

        .image-preview img {
            width: 100%;
            border-radius: 6px;
        }

        .image-preview .btn {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .product-footer {
            display: flex;
            flex-direction: column;
            /* ⬅️ critical */
            align-items: center;
            width: 100%;
            box-sizing: border-box;
        }

        .product-price {
            width: 100%;
            text-align: center;
        }

        .product-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 8px;

            flex-wrap: nowrap;
            /* ❗ force single line */
            white-space: nowrap;
            /* ❗ no line breaks */
            width: 100%;
        }

        .product-actions .btn {
            padding: 4px 8px;
            /* slightly tighter */
            font-size: 12px;
            line-height: 1.2;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .page-title {
                font-size: 2.2rem;
            }

            .hero-content h1 {
                font-size: 2.2rem;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }

            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .btn-danger {
            background-color: var(--danger-red);
            color: white;
            border: 1px solid var(--danger-red);
        }

        .btn-danger:hover {
            background-color: #b02a37;
            transform: translateY(-2px);
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

            .hero-section {
                padding: 60px 20px;
            }

            .hero-content h1 {
                font-size: 1.8rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }

            .product-footer {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .product-footer .btn {
                width: 100%;
                justify-content: center;
            }

            .features-section {
                padding: 30px 20px;
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

            .filters-section {
                padding: 20px;
            }

            .product-content {
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
            <h1 class="page-title">Product Catalog</h1>
            <p class="page-subtitle">Premium Electrical Testing Equipment & Components</p>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1>Electrical Testing Solutions</h1>
                <p>Discover our comprehensive range of electrical testing equipment, lab instruments, and certified components for power systems and industrial applications.</p>
            </div>
        </section>


        <!-- Filters Section -->
        <section class="filters-section">
            <div class="filters-header">
                <h3 class="filters-title">Filter Products</h3>
                <button class="filter-reset" id="resetFilters">
                    <i class="fas fa-redo"></i>
                    Reset Filters
                </button>
            </div>

            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">Product Category</label>
                    <select class="filter-select" id="categoryFilter">
                        <option value="all">All Categories</option>
                        <option value="switchgear">Switchgear</option>
                        <option value="transformers">Transformers</option>
                        <option value="testing">Testing Equipment</option>
                        <option value="panels">Control Panels</option>
                        <option value="cables">Cables & Accessories</option>
                        <option value="safety">Safety Equipment</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Voltage Rating</label>
                    <select class="filter-select" id="voltageFilter">
                        <option value="all">All Voltage Ratings</option>
                        <option value="lv">Low Voltage (≤1kV)</option>
                        <option value="mv">Medium Voltage (1kV-33kV)</option>
                        <option value="hv">High Voltage (>33kV)</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Certification</label>
                    <select class="filter-select" id="certificationFilter">
                        <option value="all">All Certifications</option>
                        <option value="cpri">CPRI Certified</option>
                        <option value="iso">ISO Certified</option>
                        <option value="iec">IEC Compliant</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Sort By</label>
                    <select class="filter-select" id="sortFilter">
                        <option value="name">Name (A-Z)</option>
                        <option value="name-desc">Name (Z-A)</option>
                        <option value="price-low">Price (Low to High)</option>
                        <option value="price-high">Price (High to Low)</option>
                        <option value="newest">Newest First</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Products Grid -->
        <section>
            <h2 class="section-title">Featured Products</h2>
            <div class="product-grid" id="productGrid">
                <!-- Products loaded via JavaScript -->
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination">
                <!-- Pagination loaded via JavaScript -->
            </div>
        </section>

        <!-- Product Features -->
        <section class="features-section">
            <h2 class="section-title" style="text-align: center; margin-bottom: 40px;">Why Choose Our Products?</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3>CPRI Certified</h3>
                    <p>All products tested and certified by Central Power Research Institute</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Quality Assurance</h3>
                    <p>Stringent quality control and testing at every production stage</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Technical Support</h3>
                    <p>Comprehensive technical support and installation guidance</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Nationwide Delivery</h3>
                    <p>Reliable delivery across Pakistan with tracking support</p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-item">
                    <h3>200+</h3>
                    <p>Products in Catalog</p>
                </div>

                <div class="stat-item">
                    <h3>98%</h3>
                    <p>Customer Satisfaction</p>
                </div>

                <div class="stat-item">
                    <h3>5000+</h3>
                    <p>Units Delivered</p>
                </div>

                <div class="stat-item">
                    <h3>15+</h3>
                    <p>Years Experience</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Product Detail Modal -->
    <div class="modal-overlay" id="productModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title" id="modalProductTitle">Product Details</h3>
                <button class="modal-close" id="closeProductModal">&times;</button>
            </div>

            <div class="modal-body" id="modalProductContent">
                <!-- Product details loaded via JavaScript -->
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal-overlay" id="editProductModal">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Edit Product</h3>
                <button class="modal-close" id="closeEditModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_product_id" name="product_id">
                    <input type="hidden" id="remove_image_flag" name="remove_image" value="0">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="edit_name" class="filter-label">Product Name</label>
                            <input type="text" id="edit_name" name="name" class="filter-select" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_price" class="filter-label">Price</label>
                            <input type="number" id="edit_price" name="price" class="filter-select" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_stock" class="filter-label">Stock</label>
                            <input type="number" id="edit_stock" name="stock" class="filter-select" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_category" class="filter-label">Category</label>
                            <select id="edit_category" name="category" class="filter-select">
                                <option value="switchgear">Switchgear</option>
                                <option value="transformers">Transformers</option>
                                <option value="testing">Testing Equipment</option>
                                <option value="panels">Control Panels</option>
                                <option value="cables">Cables & Accessories</option>
                                <option value="safety">Safety Equipment</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_voltage" class="filter-label">Voltage Rating</label>
                            <select id="edit_voltage" name="voltage_rating" class="filter-select">
                                <option value="lv">Low Voltage</option>
                                <option value="mv">Medium Voltage</option>
                                <option value="hv">High Voltage</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_certification" class="filter-label">Certification</label>
                            <select id="edit_certification" name="certification" class="filter-select">
                                <option value="cpri">CPRI Certified</option>
                                <option value="iso">ISO Certified</option>
                                <option value="iec">IEC Compliant</option>
                                <option value="none">None</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_badge" class="filter-label">Badge</label>
                            <select id="edit_badge" name="badge" class="filter-select">
                                <option value="">None</option>
                                <option value="new">New</option>
                                <option value="popular">Popular</option>
                                <option value="cpri">CPRI</option>
                                <option value="limited">Limited</option>
                            </select>
                        </div>
                        <div class="form-group form-group-full">
                            <label for="edit_description" class="filter-label">Description</label>
                            <textarea id="edit_description" name="description" class="filter-select" rows="3"></textarea>
                        </div>
                        <div class="form-group form-group-full">
                            <div class="form-check">
                                <input type="checkbox" id="edit_featured" name="featured" value="1">
                                <label for="edit_featured">Featured Product</label>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group form-group-full">
                            <label class="filter-label">Product Image</label>
                            <div id="editImageDropZone" class="drop-zone">
                                <i class="fas fa-upload"></i>
                                <span>Drag & drop or click to upload</span>
                                <input type="file" id="editProductImage" name="product_image" accept="image/*" class="hidden-file-input">
                            </div>
                            <div id="editImagePreviewContainer" class="image-preview" style="display:none;">
                                <img id="editPreviewImg" src="" alt="Image Preview">
                                <button type="button" id="removeEditImageBtn" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline" id="closeEditModalBtn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
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
                    <div class="contact-info-footer">
                        <div class="contact-item-footer">
                            <i class="fas fa-phone"></i>
                            <span>+92 300 1234567</span>
                        </div>
                        <div class="contact-item-footer">
                            <i class="fas fa-envelope"></i>
                            <span>info@srselectrical.pk</span>
                        </div>
                        <div class="contact-item-footer">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>SRS Electrical Appliances Plot No 45, Industrial Area Korangi Industrial Area<br>Karachi , Pakistan</span>
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
                <p>&copy; 2026 SRS Electrical Appliances. All Rights Reserved. Karachi, Pakistan. ISO compliant testing and certification support.</p>
            </div>
        </div>
    </footer>
    <script>
        // Fetch products from API
        let allProducts = [];

        async function fetchProducts() {
            try {
                const response = await fetch('api/get_products.php');
                const data = await response.json();

                if (data.success) {
                    allProducts = data.products.map(p => ({
                        id: p.product_id,
                        name: p.name,
                        category: p.category,
                        voltage: p.voltage_rating,
                        certification: p.certification,
                        description: p.description,
                        price: parseFloat(p.price),
                        specs: p.specs,
                        badge: p.badge,
                        stock: p.stock,
                        featured: p.featured,
                        image: p.image

                    }));

                    // CRITICAL FIX
                    filteredProducts = [...allProducts];

                    loadProducts();
                } else {
                    showNotification('Failed to load products: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error fetching products:', error);
                showNotification('Error loading products from database', 'error');
            }
        }

        // Mobile Navigation Toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const navMenu = document.getElementById('navMenu');

        mobileToggle.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            mobileToggle.innerHTML = navMenu.classList.contains('active') ?
                '<i class="fas fa-times"></i>' :
                '<i class="fas fa-bars"></i>';
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.nav-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });

        // Current state
        let currentPage = 1;
        const productsPerPage = 6;
        let filteredProducts = [...allProducts];
        let currentCategory = 'all';
        let currentVoltage = 'all';
        let currentCertification = 'all';
        let currentSort = 'name';

        // Load products
        function loadProducts() {
            const productGrid = document.getElementById('productGrid');
            productGrid.innerHTML = '';

            // Calculate pagination
            const startIndex = (currentPage - 1) * productsPerPage;
            const endIndex = startIndex + productsPerPage;
            const paginatedProducts = filteredProducts.slice(startIndex, endIndex);

            if (paginatedProducts.length === 0) {
                productGrid.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 10px;">
                        <i class="fas fa-search" style="font-size: 3rem; color: var(--medium-gray); margin-bottom: 20px;"></i>
                        <h3 style="color: var(--primary-blue); margin-bottom: 10px;">No products found</h3>
                        <p style="color: var(--medium-gray);">Try adjusting your filters or browse other categories.</p>
                        <button class="btn btn-primary" id="resetFiltersBtn" style="margin-top: 20px;">
                            <i class="fas fa-redo"></i>
                            Reset Filters
                        </button>
                    </div>
                `;

                document.getElementById('resetFiltersBtn').addEventListener('click', resetFilters);
                document.getElementById('pagination').innerHTML = '';
                return;
            }

            paginatedProducts.forEach(product => {
                let badgeClass = '';
                let badgeText = '';

                switch (product.badge) {
                    case 'new':
                        badgeClass = 'badge-new';
                        badgeText = 'New';
                        break;
                    case 'popular':
                        badgeClass = 'badge-popular';
                        badgeText = 'Popular';
                        break;
                    case 'cpri':
                        badgeClass = 'badge-cpri';
                        badgeText = 'CPRI';
                        break;
                    case 'limited':
                        badgeClass = 'badge-limited';
                        badgeText = 'Limited';
                        break;
                }

                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.innerHTML = `
                    ${product.badge ? `<div class="product-badge ${badgeClass}">${badgeText}</div>` : ''}
                    <div class="product-image">
                        ${product.image ?
                            `<img src="${product.image}" alt="${product.name}">` :
                            `<div class="product-image-placeholder"><i class="fas fa-bolt"></i></div>`
                        }
                        </div>
                    </div>
                    <div class="product-content">
                        <div class="product-category">${getCategoryName(product.category)}</div>
                        <h3 class="product-title">${product.name}</h3>
                        <p class="product-description">${product.description}</p>
                        
                        <div class="product-specs">
                            <div class="spec-item">
                                <span class="spec-label">Product ID:</span>
                                <span class="spec-value">${product.id}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Voltage:</span>
                                <span class="spec-value">${getVoltageName(product.voltage)}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Certification:</span>
                                <span class="spec-value">${getCertificationName(product.certification)}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Stock:</span>
                                <span class="spec-value">${product.stock} units</span>
                            </div>
                        </div>
                        
                       <div class="product-footer">
   <div class="product-price">
    Rs. ${product.price.toLocaleString()}<small> / unit</small>
</div>

<div class="product-actions">
    <button class="btn btn-outline btn-sm view-details-btn" data-id="${product.id}">
        <i class="fas fa-eye"></i> View
    </button>

    <button class="btn btn-primary btn-sm quote-btn" data-id="${product.id}">
        <i class="fas fa-file-alt"></i> Quote
    </button>

    ${IS_ADMIN ? `
        <button class="btn btn-success btn-sm edit-btn" data-id="${product.id}">
            <i class="fas fa-edit"></i> Edit
        </button>

        <button class="btn btn-danger btn-sm delete-btn" data-id="${product.id}">
            <i class="fas fa-trash"></i> Delete
        </button>
    ` : ''}
</div>

</div>
                    </div>
                `;

                productGrid.appendChild(productCard);
            });

            // Add event listeners
            document.querySelectorAll('.view-details-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const productId = e.currentTarget.getAttribute('data-id');
                    showProductDetails(productId);
                });
            });

            document.querySelectorAll('.quote-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const productId = e.currentTarget.getAttribute('data-id');
                    requestQuote(productId);
                });
            });

            // Load pagination
            loadPagination();
        }

        // Get category name
        function getCategoryName(category) {
            const categories = {
                'switchgear': 'Switchgear',
                'transformers': 'Transformers',
                'testing': 'Testing Equipment',
                'panels': 'Control Panels',
                'cables': 'Cables & Accessories',
                'safety': 'Safety Equipment'
            };
            return categories[category] || category;
        }

        // Get product image path based on category
        function getProductImage(category) {
            const images = {
                'switchgear': 'assets/images/prodcut/Switches/American Electrical Inc. 19208-11/Switches2.jpg',
                'transformers': 'assets/images/prodcut/Inductors/Panasonic Electronic Components ELJ-NC12NKF/inductors2.jpg',
                'testing': 'assets/images/prodcut/Testing Equipment/lab testing 1.jpg',
                'panels': 'assets/images/prodcut/Control Panels/product.jpeg',
                'cables': 'assets/images/prodcut/Fuses/Cartridge Fuses/1280_cn1hNihnwl831oei.jpg',
                'safety': 'assets/images/prodcut/Switches/American Electrical Inc. 19213-11/Switches1.jpg'
            };
            return images[category] || 'assets/images/prodcut/Capacitors/Panasonic Electronic Components CAP ALUM POLY 10UF 20% 6.3V SMD/SP,CB SERIES 3.3H,7.9L,5.3W.jpg'; // default image
        }

        // Get voltage name
        function getVoltageName(voltage) {
            const voltages = {
                'lv': 'Low Voltage',
                'mv': 'Medium Voltage',
                'hv': 'High Voltage'
            };
            return voltages[voltage] || voltage;
        }

        // Get certification name
        function getCertificationName(certification) {
            const certifications = {
                'cpri': 'CPRI Certified',
                'iso': 'ISO Certified',
                'iec': 'IEC Compliant'
            };
            return certifications[certification] || certification;
        }

        // Load pagination
        function loadPagination() {
            const pagination = document.getElementById('pagination');
            const totalPages = Math.ceil(filteredProducts.length / productsPerPage);

            if (totalPages <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';

            // Previous button
            if (currentPage > 1) {
                paginationHTML += `
                    <a href="#" class="page-link" data-page="${currentPage - 1}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                `;
            } else {
                paginationHTML += `<span class="page-link disabled"><i class="fas fa-chevron-left"></i></span>`;
            }

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    paginationHTML += `<span class="page-link active">${i}</span>`;
                } else {
                    paginationHTML += `<a href="#" class="page-link" data-page="${i}">${i}</a>`;
                }
            }

            // Next button
            if (currentPage < totalPages) {
                paginationHTML += `
                    <a href="#" class="page-link" data-page="${currentPage + 1}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                `;
            } else {
                paginationHTML += `<span class="page-link disabled"><i class="fas fa-chevron-right"></i></span>`;
            }

            pagination.innerHTML = paginationHTML;

            // Add event listeners to pagination links
            document.querySelectorAll('.page-link[data-page]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    currentPage = parseInt(e.currentTarget.getAttribute('data-page'));
                    loadProducts();
                    window.scrollTo({
                        top: document.querySelector('.product-grid').offsetTop - 100,
                        behavior: 'smooth'
                    });
                });
            });
        }

        // Filter products
        function filterProducts() {
            currentCategory = document.getElementById('categoryFilter').value;
            currentVoltage = document.getElementById('voltageFilter').value;
            currentCertification = document.getElementById('certificationFilter').value;
            currentSort = document.getElementById('sortFilter').value;
            currentPage = 1;

            filteredProducts = allProducts.filter(product => {
                // Category filter
                if (currentCategory !== 'all' && product.category !== currentCategory) return false;

                // Voltage filter
                if (currentVoltage !== 'all' && product.voltage !== currentVoltage) return false;

                // Certification filter
                if (currentCertification !== 'all' && product.certification !== currentCertification) return false;

                return true;
            });

            // Sort products
            sortProducts();

            // Load products
            loadProducts();
        }

        // Sort products
        function sortProducts() {
            switch (currentSort) {
                case 'name':
                    filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name-desc':
                    filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
                    break;
                case 'price-low':
                    filteredProducts.sort((a, b) => a.price - b.price);
                    break;
                case 'price-high':
                    filteredProducts.sort((a, b) => b.price - a.price);
                    break;
                case 'newest':
                    filteredProducts.sort((a, b) => b.id.localeCompare(a.id));
                    break;
            }
        }

        // Reset filters
        function resetFilters() {
            document.getElementById('categoryFilter').value = 'all';
            document.getElementById('voltageFilter').value = 'all';
            document.getElementById('certificationFilter').value = 'all';
            document.getElementById('sortFilter').value = 'name';
            filterProducts();
            showNotification('Filters reset successfully', 'success');
        }

        // Show product details modal
        function showProductDetails(productId) {
            const product = allProducts.find(p => p.id === productId);
            if (!product) return;

            document.getElementById('modalProductTitle').textContent = product.name;

            let specsHTML = '';
            for (const [key, value] of Object.entries(product.specs)) {
                specsHTML += `
                    <div class="spec-item">
                        <span class="spec-label">${key}:</span>
                        <span class="spec-value">${value}</span>
                    </div>
                `;
            }

            document.getElementById('modalProductContent').innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
                    <div>
                        <div style="background: #f8f9fa; padding: 30px; border-radius: 8px; text-align: center;">
                            <i class="fas fa-bolt" style="font-size: 4rem; color: var(--accent-blue);"></i>
                        </div>
                        <div style="margin-top: 20px; text-align: center;">
                            <div style="font-size: 2rem; font-weight: 700; color: var(--primary-blue);">₨${product.price.toLocaleString()}</div>
                            <div style="color: var(--medium-gray); margin-top: 5px;">per unit</div>
                            <div style="margin-top: 15px; color: ${product.stock > 10 ? 'var(--success-green)' : 'var(--warning-orange)'}; font-weight: 600;">
                                <i class="fas fa-box"></i> ${product.stock} units in stock
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div style="margin-bottom: 20px;">
                            <span style="background: rgba(42, 134, 186, 0.1); color: var(--accent-blue); padding: 4px 12px; border-radius: 4px; font-size: 0.85rem;">
                                ${getCategoryName(product.category)}
                            </span>
                            <span style="background: rgba(40, 167, 69, 0.1); color: var(--success-green); padding: 4px 12px; border-radius: 4px; font-size: 0.85rem; margin-left: 10px;">
                                ${getCertificationName(product.certification)}
                            </span>
                        </div>
                        
                        <p style="margin-bottom: 25px; color: var(--dark-gray);">${product.description}</p>
                        
                        <h4 style="color: var(--primary-blue); margin-bottom: 15px;">Technical Specifications</h4>
                        <div class="product-specs">
                            ${specsHTML}
                        </div>
                        
                        <div style="display: flex; gap: 15px; margin-top: 30px;">
                            <button class="btn btn-primary" id="modalQuoteBtn">
                                <i class="fas fa-file-alt"></i>
                                Request Quote
                            </button>
                            <button class="btn btn-outline" id="modalContactBtn">
                                <i class="fas fa-phone"></i>
                                Contact Sales
                            </button>
                        </div>
                    </div>
                </div>
            `;

            // Add event listeners to modal buttons
            setTimeout(() => {
                document.getElementById('modalQuoteBtn').addEventListener('click', () => {
                    requestQuote(productId);
                });

                document.getElementById('modalContactBtn').addEventListener('click', () => {
                    window.location.href = 'contact.php';
                });
            }, 100);

            document.getElementById('productModal').style.display = 'flex';
        }

        // Request quote
        function requestQuote(productId) {
            const product = allProducts.find(p => p.id === productId);
            if (!product) return;

            showNotification(`Quote request sent for ${product.name}. Our sales team will contact you shortly.`, 'success');
        }

        // Show notification
        function showNotification(message, type) {
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
            }

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);

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

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch and load products from API
            fetchProducts();

            // Add event listeners to filters
            document.getElementById('categoryFilter').addEventListener('change', filterProducts);
            document.getElementById('voltageFilter').addEventListener('change', filterProducts);
            document.getElementById('certificationFilter').addEventListener('change', filterProducts);
            document.getElementById('sortFilter').addEventListener('change', filterProducts);
            document.getElementById('resetFilters').addEventListener('click', resetFilters);

            // Modal close button
            document.getElementById('closeProductModal').addEventListener('click', () => {
                document.getElementById('productModal').style.display = 'none';
            });
            document.getElementById('closeEditModal').addEventListener('click', () => {
                document.getElementById("editProductModal").style.display = "none";
            });

            // Close modal when clicking outside
            document.getElementById('productModal').addEventListener('click', (e) => {
                if (e.target.id === 'productModal') {
                    e.target.style.display = 'none';
                }
            });
            document.getElementById('editProductModal').addEventListener('click', e => {
                if (e.target.id === "editProductModal") {
                    e.target.style.display = "none";
                }
            });

            document.getElementById('editProductForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                // Collect specs
                const specs = [];
                document.querySelectorAll('#editSpecsContainer .spec-row').forEach(row => {
                    const labelEl = row.querySelector('.spec-label-select, .spec-label-input');
                    const valueEl = row.querySelector('.spec-value');
                    if (labelEl && valueEl && labelEl.value && valueEl.value) {
                        specs.push({
                            label: labelEl.value,
                            value: valueEl.value
                        });
                    }
                });

                const formData = new FormData(this);
                formData.append('specs', JSON.stringify(specs));

                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                try {
                    const response = await fetch('api/update_product.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        showNotification('Product updated successfully!', 'success');
                        document.getElementById('editProductModal').style.display = 'none';
                        fetchProducts(); // Refresh the product list
                    } else {
                        showNotification('Error updating product: ' + result.message, 'error');
                    }
                } catch (error) {
                    showNotification('An unexpected error occurred. Please try again.', 'error');
                    console.error('Update product error:', error);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Save Changes';
                }
            });

            document.getElementById('closeEditModalBtn').addEventListener('click', () => {
                document.getElementById('editProductModal').style.display = 'none';
            });


            // Update year in footer
            const currentYear = new Date().getFullYear();
            const yearElement = document.querySelector('.footer-bottom p');
            yearElement.innerHTML = yearElement.innerHTML.replace('2023', currentYear);

            // --- Logic for adding new spec rows in Edit Modal ---
            document.getElementById('addEditSpec').addEventListener('click', function() {
                const container = document.getElementById("editSpecsContainer");
                const specRow = document.createElement('div');
                specRow.className = 'filter-group spec-row';

                let options = allSpecLabels.map(label => `<option value="${label}">${label}</option>`).join('');

                specRow.innerHTML = `
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <div class="spec-label-container" style="flex: 1;">
                            <select class="filter-select spec-label-select">
                                <option value="">-- Select Spec --</option>
                                ${options}
                                <option value="_new_">-- Add New Label --</option>
                            </select>
                        </div>
                        <input type="text" class="spec-value filter-select" placeholder="Spec value" style="flex: 1;">
                        <button type="button" class="btn btn-danger btn-sm remove-spec-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                container.appendChild(specRow);
            });

            // --- Event delegation for dynamic spec rows ---
            document.getElementById('editSpecsContainer').addEventListener('click', function(e) {
                // Remove spec row
                if (e.target.closest('.remove-spec-btn')) {
                    e.target.closest('.spec-row').remove();
                }
            });

            document.getElementById('editSpecsContainer').addEventListener('change', function(e) {
                // Handle new spec label selection
                if (e.target.classList.contains('spec-label-select') && e.target.value === '_new_') {
                    const container = e.target.parentElement;
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = 'New Spec Label';
                    input.className = 'spec-label-input filter-select';
                    container.innerHTML = '';
                    container.appendChild(input);
                    input.focus();
                }
            });
        });

        document.addEventListener("click", e => {
            if (e.target.closest(".delete-btn")) {
                const id = e.target.closest(".delete-btn").dataset.id;

                if (!confirm("Delete this product permanently?")) return;

                fetch("api/delete_product.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `product_id=${id}`
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            showNotification("Product deleted", "success");
                            fetchProducts();
                        } else {
                            showNotification(d.message, "error");
                        }
                    });
            }

        });

        document.addEventListener("click", e => {
            const editBtn = e.target.closest(".edit-btn");
            if (!editBtn) return;

            const productId = editBtn.dataset.id;
            const product = allProducts.find(p => p.id == productId);
            if (!product) return;

            // --- Populate basic form fields ---
            document.getElementById("edit_product_id").value = product.id;
            document.getElementById("edit_name").value = product.name;
            document.getElementById("edit_price").value = product.price;
            document.getElementById("edit_stock").value = product.stock;
            document.getElementById("edit_description").value = product.description;
            document.getElementById("edit_category").value = product.category || "";
            document.getElementById("edit_voltage").value = product.voltage || "";
            document.getElementById("edit_certification").value = product.certification || "";
            document.getElementById("edit_badge").value = product.badge || "";
            document.getElementById("edit_featured").checked = product.featured == 1;

            // --- Handle Image Preview ---
            const previewContainer = document.getElementById("editImagePreviewContainer");
            const previewImg = document.getElementById("editPreviewImg");
            const dropZone = document.getElementById("editImageDropZone");
            const imageInput = document.getElementById("editProductImage");
            const removeFlag = document.getElementById("remove_image_flag");

            imageInput.value = ""; // Clear file input
            removeFlag.value = "0"; // Reset remove flag

            if (product.image) {
                previewImg.src = product.image;
                previewContainer.style.display = "block";
                dropZone.style.display = "none";
            } else {
                previewContainer.style.display = "none";
                dropZone.style.display = "block";
            }

            // Show modal
            document.getElementById("editProductModal").style.display = "flex";
        });

        // --- Add event listeners for the edit image modal ---
        document.addEventListener('DOMContentLoaded', function() {
            const dropZone = document.getElementById('editImageDropZone');
            const imageInput = document.getElementById('editProductImage');
            const previewContainer = document.getElementById('editImagePreviewContainer');
            const previewImg = document.getElementById('editPreviewImg');
            const removeBtn = document.getElementById('removeEditImageBtn');
            const removeFlag = document.getElementById('remove_image_flag');

            // Click to upload
            dropZone.addEventListener('click', () => imageInput.click());

            // Remove image
            removeBtn.addEventListener('click', () => {
                imageInput.value = ''; // Clear the file input
                removeFlag.value = '1'; // Flag for removal
                previewContainer.style.display = 'none';
                dropZone.style.display = 'block';
            });

            // Preview selected image
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImg.src = e.target.result;
                        removeFlag.value = '0'; // A new file is selected, so don't remove
                        previewContainer.style.display = 'block';
                        dropZone.style.display = 'none';
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Drag and drop listeners
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.style.borderColor = 'var(--primary-blue)';
            });
            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.style.borderColor = '#ccc';
            });
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.style.borderColor = '#ccc';
                if (e.dataTransfer.files.length > 0) {
                    imageInput.files = e.dataTransfer.files;
                    // Manually trigger the change event
                    const changeEvent = new Event('change');
                    imageInput.dispatchEvent(changeEvent);
                }
            });
        });

        document.getElementById("closeEditModal").addEventListener('click', () => {
            document.getElementById("editProductModal").style.display = "none";
        });

        document.getElementById("editProductModal").addEventListener('click', e => {
            if (e.target.id === "editProductModal") {
                e.target.style.display = "none";
            }
        });

        document.getElementById("addEditSpec").addEventListener('click', function() {
            const container = document.getElementById("editSpecsContainer");
            const specRow = document.createElement('div');
            specRow.className = 'filter-group spec-row';

            let options = allSpecLabels.map(label => `<option value="${label}">${label}</option>`).join('');

            specRow.innerHTML = `
                <div style="display: flex; gap: 10px; align-items: center;">
                    <div class="spec-label-container" style="flex: 1;">
                        <select class="filter-select spec-label-select">
                            <option value="">-- Select Spec --</option>
                            ${options}
                            <option value="_new_">-- Add New Label --</option>
                        </select>
                    </div>
                    <input type="text" class="spec-value filter-select" placeholder="Spec value" style="flex: 1;">
                    <button type="button" class="btn btn-danger btn-sm remove-spec-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            container.appendChild(specRow);
        });

        // --- Event delegation for dynamic spec rows ---
        const specsContainer = document.getElementById('editSpecsContainer');

        specsContainer.addEventListener('click', function(e) {
            // Remove spec row
            if (e.target.closest('.remove-spec-btn')) {
                e.target.closest('.spec-row').remove();
            }
        });

        specsContainer.addEventListener('change', function(e) {
            // Handle new spec label selection
            if (e.target.classList.contains('spec-label-select') && e.target.value === '_new_') {
                const container = e.target.parentElement;
                const input = document.createElement('input');
                input.type = 'text';
                input.placeholder = 'New Spec Label';
                input.className = 'spec-label-input filter-select';
                container.innerHTML = '';
                container.appendChild(input);
                input.focus();
            }
        });

        // --- Handle Edit Form Submission ---
        document.getElementById("editProductForm").addEventListener("submit", e => {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            // Collect specs manually from the dynamic rows
            const specs = [];
            document.querySelectorAll("#editSpecsContainer .spec-row").forEach(row => {
                let label = '';
                const labelSelect = row.querySelector('.spec-label-select');
                const labelInput = row.querySelector('.spec-label-input');
                const valueInput = row.querySelector('.spec-value');

                if (labelSelect) {
                    label = labelSelect.value;
                } else if (labelInput) {
                    label = labelInput.value;
                }

                const value = valueInput ? valueInput.value : '';

                if (label && label !== '_new_' && value) {
                    specs.push({
                        label: label.trim(),
                        value: value.trim()
                    });
                }
            });
            formData.set("specs", JSON.stringify(specs));

            // Ensure featured checkbox is included
            formData.set("featured", form.querySelector('#edit_featured').checked ? 1 : 0);

            fetch("api/update_product.php", {
                    method: "",
                    body: formData
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        showNotification(d.message, "success");
                        document.getElementById("editProductModal").style.display = "none";
                        fetchProducts(); // Refresh product list
                    } else {
                        showNotification(d.message || "An error occurred", "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    showNotification("An error occurred while updating the product.", "error");
                });
        });

        document.getElementById("closeEditModal").onclick = () => {
            document.getElementById("editProductModal").style.display = "none";
        };

        document.getElementById("editProductModal").onclick = e => {
            if (e.target.id === "editProductModal") {
                e.target.style.display = "none";
            }
        };
    </script>

    <!-- EDIT PRODUCT MODAL -->
    <div id="editProductModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Edit Product</h3>
                <button class="modal-close" id="closeEditModal">&times;</button>
            </div>

            <div class="modal-body">
                <form id="editProductForm" enctype="multipart/form-data" method="POST">

                    <input type="hidden" name="product_id" id="edit_product_id">

                    <!-- Product Basic Info -->
                    <div class="filter-group">
                        <label class="filter-label">Product Name *</label>
                        <input type="text" name="name" id="edit_name" class="filter-select" required>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Category</label>
                        <select name="category" id="edit_category" class="filter-select">
                            <option value="switchgear">Switchgear</option>
                            <option value="transformers">Transformers</option>
                            <option value="testing">Testing Equipment</option>
                            <option value="panels">Control Panels</option>
                            <option value="cables">Cables & Accessories</option>
                            <option value="safety">Safety Equipment</option>

                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Voltage Rating</label>
                        <select name="voltage_rating" id="edit_voltage" class="filter-select">
                            <option value="">Select Voltage</option>
                            <option value="lv">Low Voltage (≤1kV)</option>
                            <option value="mv">Medium Voltage (1kV-33kV)</option>
                            <option value="hv">High Voltage (>33kV)</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Certification</label>
                        <select name="certification" id="edit_certification" class="filter-select">
                            <option value="">None</option>
                            <option value="cpri">CPRI</option>
                            <option value="iso">ISO</option>
                            <option value="iec">IEC</option>
                            <option value="ce">CE</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Description</label>
                        <textarea name="description" id="edit_description" class="filter-select" rows="3"></textarea>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Price (PKR)</label>
                        <input type="number" name="price" id="edit_price" class="filter-select">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Stock</label>
                        <input type="number" name="stock" id="edit_stock" class="filter-select">
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Badge</label>
                        <select name="badge" id="edit_badge" class="filter-select">
                            <option value="">None</option>
                            <option value="new">New</option>
                            <option value="popular">Popular</option>
                            <option value="cpri">CPRI</option>
                            <option value="limited">Limited</option>
                        </select>
                    </div>

                    <div class="filter-group checkbox-group">
                        <label>
                            <input type="checkbox" name="featured" id="edit_featured">
                            Featured Product
                        </label>
                    </div>

                    <!-- Image Preview & Upload -->
                    <div class="filter-group">
                        <label class="filter-label">Product Image</label>
                        <div style="border: 2px dashed #ccc; padding: 20px; border-radius: 5px; text-align: center; cursor: pointer;" id="editImageDropZone">
                            <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: var(--accent-blue); margin-bottom: 10px;"></i>
                            <p>Drag and drop to replace, or click to select</p>
                        </div>
                        <div id="editImagePreviewContainer" style="display: none; margin-top: 15px; text-align: center;">
                            <img id="editPreviewImg" src="" style="max-width: 200px; max-height: 200px; border-radius: 5px;">
                            <button type="button" id="removeEditImageBtn" style="display: block; margin-top: 10px; background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                                Remove Image
                            </button>
                        </div>
                        <input type="file" id="editProductImage" name="product_image" accept="image/*" style="display: none;">
                        <input type="hidden" name="remove_image" id="remove_image_flag" value="0">
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>