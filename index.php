<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRS Electrical Appliances | Lab Automation & Electrical Testing</title>
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
            --light-gray: #f5f5f5;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            line-height: 1.6;
            color: var(--dark-gray);
            background-color: var(--white);
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        section {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            color: var(--primary-blue);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .section-title h2:after {
            content: '';
            position: absolute;
            width: 70px;
            height: 3px;
            background-color: var(--accent-blue);
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .section-title p {
            color: var(--medium-gray);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary-blue);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--accent-blue);
            transform: translateY(-3px);
            box-shadow: var(--shadow);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }

        .btn-secondary:hover {
            background-color: rgba(42, 134, 186, 0.1);
            transform: translateY(-3px);
        }

        .btn-accent {
            background-color: var(--accent-blue);
            color: var(--white);
        }

        .btn-accent:hover {
            background-color: var(--primary-blue);
            transform: translateY(-3px);
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

        .login-btn {
            /* background-color: var(--accent-blue); */
            color: white;
            padding: 10px 25px;
            border-radius: 4px;
        }

        .login-btn:hover {
            /* background-color: var(--primary-blue); */
            transform: translateY(-3px);
        }

        .mobile-toggle {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary-blue);
        }

        /* Login Modal */
        .login-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-color: var(--white);
            border-radius: 10px;
            width: 100%;
            max-width: 450px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 1.5rem;
            color: var(--medium-gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .close-modal:hover {
            color: var(--primary-blue);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: var(--primary-blue);
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .login-header p {
            color: var(--medium-gray);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-gray);
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(42, 134, 186, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password {
            color: var(--accent-blue);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-form-btn {
            width: 100%;
            padding: 14px;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
            color: var(--medium-gray);
        }

        .divider:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 45%;
            height: 1px;
            background-color: #ddd;
        }

        .divider:after {
            content: '';
            position: absolute;
            top: 50%;
            right: 0;
            width: 45%;
            height: 1px;
            background-color: #ddd;
        }

        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 1px solid #ddd;
            background-color: var(--white);
            color: var(--dark-gray);
            font-size: 1.2rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .social-btn:hover {
            background-color: var(--light-gray);
            transform: translateY(-3px);
        }

        .google-btn:hover {
            color: #DB4437;
            border-color: #DB4437;
        }

        .linkedin-btn:hover {
            color: #0077B5;
            border-color: #0077B5;
        }

        .register-link {
            text-align: center;
            color: var(--medium-gray);
        }

        .register-link a {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 600px;
            overflow: hidden;
            margin-top: 0;
        }

        .hero-slider {
            height: 100%;
            position: relative;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1s ease;
            display: flex;
            align-items: center;
        }

        .slide.active {
            opacity: 1;
        }

        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .slide-content {
            position: relative;
            z-index: 2;
            color: var(--white);
            max-width: 700px;
            padding: 0 40px;
        }

        .slide-content h1 {
            font-size: 3.2rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .slide-content p {
            font-size: 1.3rem;
            margin-bottom: 30px;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
        }

        .slider-indicators {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 3;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: var(--transition);
        }

        .indicator.active {
            background-color: var(--white);
            transform: scale(1.2);
        }

        /* Products Section */
        .products-section {
            background-color: var(--light-gray);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background-color: var(--white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            height: 200px;
            width: 100%;
            background-color: #e9f7fe;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 3.5rem;
        }

        .product-info {
            padding: 25px;
        }

        .product-info h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: var(--primary-blue);
        }

        .product-info p {
            color: var(--medium-gray);
        }

        /* Dashboard Section */
        .dashboard-section {
            background-color: var(--white);
        }

        .dashboard-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 50px;
        }

        .dashboard-content {
            flex: 1;
        }

        .dashboard-mock {
            flex: 1;
            background-color: #e9f7fe;
            border-radius: 10px;
            padding: 30px;
            box-shadow: var(--shadow);
            min-height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .dashboard-content h2 {
            font-size: 2.5rem;
            color: var(--primary-blue);
            margin-bottom: 20px;
        }

        .dashboard-content p {
            color: var(--medium-gray);
            margin-bottom: 30px;
            font-size: 1.1rem;
        }

        .dashboard-features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .feature i {
            color: var(--accent-blue);
        }

        .mock-chart {
            background-color: var(--white);
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .chart-bar {
            height: 10px;
            background-color: var(--light-blue);
            border-radius: 5px;
            margin: 10px 0;
        }

        .bar-1 {
            width: 85%;
        }

        .bar-2 {
            width: 70%;
        }

        .bar-3 {
            width: 95%;
        }

        .bar-4 {
            width: 60%;
        }

        .mock-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--medium-gray);
        }

        /* Newsletter Section */
        .newsletter-section {
            background-color: var(--light-gray);
            text-align: center;
        }

        .newsletter-box {
            max-width: 600px;
            margin: 0 auto;
            background-color: var(--white);
            padding: 50px;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }

        .newsletter-box h3 {
            font-size: 1.8rem;
            color: var(--primary-blue);
            margin-bottom: 15px;
        }

        .newsletter-box p {
            color: var(--medium-gray);
            margin-bottom: 30px;
        }

        .newsletter-form {
            display: flex;
            max-width: 450px;
            margin: 0 auto;
        }

        .newsletter-form input {
            flex: 1;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
            font-size: 1rem;
        }

        .newsletter-form button {
            border-radius: 0 4px 4px 0;
            padding: 15px 30px;
        }

        /* Footer */
        footer {
            background-color: #2c3e50;
            color: var(--white);
            padding: 60px 0 30px;
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
            .dashboard-container {
                flex-direction: column;
            }

            .slide-content h1 {
                font-size: 2.5rem;
            }

            .section-title h2 {
                font-size: 2.2rem;
            }

            .login-container {
                max-width: 400px;
                padding: 30px;
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

            .hero-buttons {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .newsletter-form input {
                border-radius: 4px;
                margin-bottom: 10px;
            }

            .newsletter-form button {
                border-radius: 4px;
            }
        }

        @media (max-width: 576px) {
            section {
                padding: 60px 0;
            }

            .slide-content h1 {
                font-size: 2rem;
            }

            .slide-content p {
                font-size: 1.1rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .login-container {
                max-width: 90%;
                padding: 25px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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
                            <a href="report.php">Report</a>
                            <a href="contact.php">CPRI Testing</a>
                        </div>
                    </li>
                    <li><a href="product.php">Product Catalog</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="dashboard.php" class="btn login-btn" id="loginBtn">Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Login Modal -->
    <div class="login-modal" id="loginModal">
        <div class="login-container">
            <div class="close-modal" id="closeModal">&times;</div>

            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Login to access your testing dashboard and reports</p>
            </div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control" placeholder="Enter your username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary login-form-btn">Login to Dashboard</button>

                <div class="divider">Or continue with</div>

                <div class="social-login">
                    <button type="button" class="social-btn google-btn">
                        <i class="fab fa-google"></i>
                    </button>
                    <button type="button" class="social-btn linkedin-btn">
                        <i class="fab fa-linkedin-in"></i>
                    </button>
                </div>

                <div class="register-link">
                    Don't have an account? <a href="register.php" id="registerLink">Register here</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-slider">
            <!-- Slide 1 -->
            <div class="slide active" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1>Reliable Electrical Testing and Lab Automation</h1>
                    <p>In-house testing and CPRI approved solutions for industrial and commercial applications</p>
                    <div class="hero-buttons">
                        <a href="lab-testing.php" class="btn btn-primary">View Testing Services</a>
                        <a href="dashboard.php" class="btn btn-secondary">Explore Dashboard</a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="slide" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1621905252507-b35492cc74b4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1>Precision Switchgear Testing</h1>
                    <p>Advanced testing facilities for high and low voltage switchgear with certified results</p>
                    <div class="hero-buttons">
                        <a href="lab-testing.php" class="btn btn-primary">View Testing Services</a>
                        <a href="dashboard.php" class="btn btn-secondary">Explore Dashboard</a>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="slide" style="background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1581094794329-c8112a89af12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h1>Automation Control Panels</h1>
                    <p>State-of-the-art automation solutions for industrial processes and quality control</p>
                    <div class="hero-buttons">
                        <a href="lab-testing.php" class="btn btn-primary">View Testing Services</a>
                        <a href="dashboard.php" class="btn btn-secondary">Explore Dashboard</a>
                    </div>
                </div>
            </div>

            <div class="slider-indicators">
                <div class="indicator active" data-slide="0"></div>
                <div class="indicator" data-slide="1"></div>
                <div class="indicator" data-slide="2"></div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section" id="products">
        <div class="container">
            <div class="section-title">
                <h2>Our Products</h2>
                <p>Tested. Certified. Reliable. Industry-standard electrical components and equipment</p>
            </div>

            <div class="products-grid">
                <!-- Product 1 -->
                <div class="product-card">
                    <div class="product-img">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <div class="product-info">
                        <h3>Switchgear</h3>
                        <p>High and low voltage switchgear with complete testing certification</p>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="product-card">
                    <div class="product-img">
                        <i class="fas fa-fire-extinguisher"></i>
                    </div>
                    <div class="product-info">
                        <h3>Fuses</h3>
                        <p>Certified fuses for industrial and commercial electrical protection</p>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="product-card">
                    <div class="product-img">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="product-info">
                        <h3>Capacitors</h3>
                        <p>Power factor correction capacitors with testing compliance</p>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="product-card">
                    <div class="product-img">
                        <i class="fas fa-resistance"></i>
                    </div>
                    <div class="product-info">
                        <h3>Resistors</h3>
                        <p>Precision resistors for electrical circuits and control systems</p>
                    </div>
                </div>

                <!-- Product 5 -->
                <div class="product-card">
                    <div class="product-img">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div class="product-info">
                        <h3>Control Panels</h3>
                        <p>Custom automation and control panels for industrial applications</p>
                    </div>
                </div>

                <!-- Product 6 -->
                <div class="product-card">
                    <div class="product-img">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="product-info">
                        <h3>Testing Equipment</h3>
                        <p>Advanced electrical testing instruments and measurement devices</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dashboard Section -->
    <section class="dashboard-section" id="dashboard">
        <div class="container">
            <div class="section-title">
                <h2>Smart Testing Dashboard</h2>
                <p>Monitor tests, reports, and approvals in one place with our comprehensive dashboard</p>
            </div>

            <div class="dashboard-container">
                <div class="dashboard-content">
                    <h2>Access Your Testing Dashboard</h2>
                    <p>Login to monitor real-time testing progress, access certification documents, and manage your electrical testing requirements efficiently. Our dashboard provides complete visibility into your testing projects.</p>

                    <div class="dashboard-features">
                        <div class="feature">
                            <i class="fas fa-check-circle"></i>
                            <span>Real-time test monitoring</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-file-certificate"></i>
                            <span>CPRI approved reports</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics & insights</span>
                        </div>
                        <div class="feature">
                            <i class="fas fa-bell"></i>
                            <span>Automated alerts</span>
                        </div>
                    </div>

                    <a href="#" class="btn btn-primary" id="dashboardLoginBtn" style="margin-top: 30px;">Login to Dashboard</a>
                </div>

                <div class="dashboard-mock">
                    <div class="mock-chart">
                        <h4>Test Completion Status</h4>
                        <div class="chart-bar bar-1"></div>
                        <div class="chart-bar bar-2"></div>
                        <div class="chart-bar bar-3"></div>
                        <div class="chart-bar bar-4"></div>
                    </div>

                    <div class="mock-stats">
                        <div class="stat">
                            <div class="stat-value">98%</div>
                            <div class="stat-label">Tests Passed</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value">24h</div>
                            <div class="stat-label">Avg. Turnaround</div>
                        </div>
                        <div class="stat">
                            <div class="stat-value">150+</div>
                            <div class="stat-label">Active Tests</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-box">
                <h3>Stay Updated on Testing Standards</h3>
                <p>Get updates on testing standards, CPRI approvals, and industry best practices directly to your inbox.</p>

                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email address" required>
                    <button type="submit" class="btn btn-accent">Subscribe</button>
                </form>
            </div>
        </div>
    </section>

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

        // Login Modal Functionality
        const loginModal = document.getElementById('loginModal');
        const loginBtn = document.getElementById('loginBtn');
        const dashboardLoginBtn = document.getElementById('dashboardLoginBtn');
        const closeModal = document.getElementById('closeModal');
        const registerLink = document.getElementById('registerLink');

        // Open modal when clicking login buttons
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            loginModal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });

        dashboardLoginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            loginModal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });

        // Close modal
        closeModal.addEventListener('click', () => {
            loginModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            if (e.target === loginModal) {
                loginModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // Login form submission
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;

            // Send login request to server
            const formData = new FormData();
            formData.append('username', username);
            formData.append('password', password);
            formData.append('remember', remember ? 1 : 0);

            fetch('login_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        sessionStorage.setItem('admin_logged_in', 'true');
                        sessionStorage.setItem('admin_username', data.username);
                        window.location.href = 'dashboard.php';
                    } else {
                        alert(data.message || 'Login failed');
                    }

                    // Close modal
                    loginModal.style.display = 'none';
                    document.body.style.overflow = 'auto';

                    // Reset form
                    loginForm.reset();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during login');

                    // Close modal
                    loginModal.style.display = 'none';
                    document.body.style.overflow = 'auto';

                    // Reset form
                    loginForm.reset();
                });
        });

        // Register link removed - now direct link

        // Hero Slider
        const slides = document.querySelectorAll('.slide');
        const indicators = document.querySelectorAll('.indicator');
        let currentSlide = 0;

        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => {
                slide.classList.remove('active');
            });

            // Remove active class from all indicators
            indicators.forEach(indicator => {
                indicator.classList.remove('active');
            });

            // Show the selected slide
            slides[index].classList.add('active');
            indicators[index].classList.add('active');
            currentSlide = index;
        }

        // Add click event to indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                showSlide(index);
            });
        });

        // Auto slide every 5 seconds
        setInterval(() => {
            let nextSlide = (currentSlide + 1) % slides.length;
            showSlide(nextSlide);
        }, 5000);

        // Newsletter form submission
        const newsletterForm = document.querySelector('.newsletter-form');
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const email = newsletterForm.querySelector('input').value;
            alert(`Thank you for subscribing with ${email}. You will receive updates on testing standards and approvals.`);
            newsletterForm.reset();
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>