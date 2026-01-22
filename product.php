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
            max-width: 80%;
            max-height: 80%;
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
                    <li class="dropdown">
                        <a href="lab-testing.php">Lab Testing <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="report.php">Reports</a>
                            <a href="cpri.php">CPRI Testing</a>
                        </div>
                    </li>
                    <li><a href="product.php" class="active">Product Catalog</a></li>
                    <li><a href="contact.php">Contact Us</a></li>

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

        <!-- Product Categories -->
        <!-- <section class="categories-section">
            <h2 class="section-title">Browse by Category</h2>
            <div class="categories-grid">
                <div class="category-card" data-category="switchgear">
                    <div class="category-icon">
                        <i class="fas fa-toggle-on"></i>
                    </div>
                    <h3>Switchgear</h3>
                    <p>Circuit breakers, contactors, relays, and protection devices</p>
                    <div class="category-count">24 Products</div>
                </div>
                
                <div class="category-card" data-category="transformers">
                    <div class="category-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Transformers</h3>
                    <p>Power, distribution, and instrument transformers</p>
                    <div class="category-count">18 Products</div>
                </div>
                
                <div class="category-card" data-category="testing">
                    <div class="category-icon">
                        <i class="fas fa-flask"></i>
                    </div>
                    <h3>Testing Equipment</h3>
                    <p>Electrical testing instruments and lab equipment</p>
                    <div class="category-count">32 Products</div>
                </div>
                
                <div class="category-card" data-category="panels">
                    <div class="category-icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <h3>Control Panels</h3>
                    <p>Custom control panels and distribution boards</p>
                    <div class="category-count">15 Products</div>
                </div>
                
                <div class="category-card" data-category="cables">
                    <div class="category-icon">
                        <i class="fas fa-plug"></i>
                    </div>
                    <h3>Cables & Accessories</h3>
                    <p>Power cables, connectors, and cable accessories</p>
                    <div class="category-count">28 Products</div>
                </div>
                
                <div class="category-card" data-category="safety">
                    <div class="category-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Safety Equipment</h3>
                    <p>Personal protective equipment and safety devices</p>
                    <div class="category-count">22 Products</div>
                </div>
            </div>
        </section> -->

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
                    <p>Reliable delivery across India with tracking support</p>
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
                            <span>sales@srselectrical.com</span>
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
                    <a href="cpri.php">CPRI Testing</a>
                    <a href="product.php" class="active">Product Catalog</a>
                    <a href="faqs.php">FAQs</a>
                </div>

                <!-- Column 3: Social Media -->
                <div class="footer-column">
                    <h3>Connect With Us</h3>
                    <p>Follow us for product updates, industry news, and special offers.</p>

                    <div class="social-links">
                        <a href="#" class="social-icon">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="social-icon">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="social-icon">
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
    ₹${product.price.toLocaleString()}<small> / unit</small>
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
                            <div style="font-size: 2rem; font-weight: 700; color: var(--primary-blue);">₹${product.price.toLocaleString()}</div>
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

            // Close modal when clicking outside
            document.getElementById('productModal').addEventListener('click', (e) => {
                if (e.target === document.getElementById('productModal')) {
                    document.getElementById('productModal').style.display = 'none';
                }
            });

            // Update year in footer
            const currentYear = new Date().getFullYear();
            const yearElement = document.querySelector('.footer-bottom p');
            yearElement.innerHTML = yearElement.innerHTML.replace('2023', currentYear);
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

            // Fill form fields
            edit_product_id.value = product.id;
            edit_name.value = product.name;
            edit_price.value = product.price;
            edit_stock.value = product.stock;
            edit_description.value = product.description;
            document.getElementById("edit_category").value = product.category || "";
            document.getElementById("edit_voltage").value = product.voltage || "";
            document.getElementById("edit_certification").value = product.certification || "";
            document.getElementById("edit_badge").value = product.badge || "";
            document.getElementById("edit_featured").checked = product.featured == 1;

            // Image preview
            const img = document.getElementById("editImagePreview");
            img.src = product.image ?
                product.image :
                "assets/no-image.png";


            // Load specs
            const container = document.getElementById("editSpecsContainer");
            container.innerHTML = "";

            if (product.specs) {
                Object.entries(product.specs).forEach(([key, value]) => {
                    container.innerHTML += `
                <div class="filter-group">
                    <input type="text" class="spec-label filter-select" value="${key}" placeholder="Spec name">
                    <input type="text" class="spec-value filter-select" value="${value}" placeholder="Spec value">
                </div>
            `;
                });
            }

            // Show modal
            document.getElementById("editProductModal").style.display = "flex";
        });

        document.getElementById("closeEditModal").onclick = () => {
            document.getElementById("editProductModal").style.display = "none";
        };

        document.getElementById("editProductModal").onclick = e => {
            if (e.target.id === "editProductModal") {
                e.target.style.display = "none";
            }
        };

        document.getElementById("editProductForm").addEventListener("submit", e => {
            e.preventDefault();

            // Create FormData object (handles file upload automatically)
            const form = e.target;
            const formData = new FormData(form);

            // Collect specs manually into JSON and append
            const specs = {};
            document.querySelectorAll("#editSpecsContainer .spec-label").forEach((label, i) => {
                const value = document.querySelectorAll("#editSpecsContainer .spec-value")[i].value;
                if (label.value.trim() && value.trim()) specs[label.value.trim()] = value.trim();
            });
            formData.set("specs", JSON.stringify(Object.entries(specs).map(([label, value]) => ({
                label,
                value
            }))));

            // Ensure featured checkbox is included
            formData.set("featured", form.edit_featured.checked ? 1 : 0);

            fetch("api/update_product.php", {
                    method: "POST",
                    body: formData
                })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        showNotification(d.message, "success");
                        document.getElementById("editProductModal").style.display = "none";
                        fetchProducts();
                    } else {
                        showNotification(d.message, "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    showNotification("An error occurred while updating the product", "error");
                });
        });
    </script>

    <!-- EDIT PRODUCT MODAL -->
    <div id="editProductModal" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Edit Product</h3>
                <button class="modal-close" id="closeEditModal">&times;</button>
            </div>

            <div class="modal-body">
                <form id="editProductForm" enctype="multipart/form-data">

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
                        <label class="filter-label">Current Image</label>
                        <div class="image-preview" style="margin-bottom:10px;">
                            <img id="editImagePreview" src="" alt="Product Image" style="width:100px;height:100px;object-fit:cover;border-radius:5px;">
                        </div>
                        <label class="filter-label">Replace Image</label>
                        <input type="file" name="product_image" accept="image/*">
                    </div>

                    <!-- Product Specs -->
                    <h4 style="margin:20px 0 10px;color:var(--primary-blue)">Specifications</h4>
                    <div id="editSpecsContainer"></div>
                    <button type="button" id="addEditSpec" class="btn btn-secondary" style="margin-bottom:10px;">
                        + Add Specification
                    </button>

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