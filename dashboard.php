<?php
include 'dashboard_auth.php';
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

// --- Fetch CPRI Reports ---
$cpri_reports = [];
$cpri_reports_result = $conn->query("
    SELECT c.*, p.name AS product_name
    FROM cpri_reports c
    LEFT JOIN products p ON c.product_id = p.product_id
    ORDER BY c.id DESC
");
if ($cpri_reports_result && $cpri_reports_result->num_rows > 0) {
    while ($row = $cpri_reports_result->fetch_assoc()) {
        $cpri_reports[] = $row;
    }
}

// Fetch report types for dropdown
$reportTypes = [
    ['type_id' => 1, 'type_name' => 'Analytics Summary'],
    ['type_id' => 2, 'type_name' => 'Compliance Report'],
    ['type_id' => 3, 'type_name' => 'Performance Summary'],
    ['type_id' => 4, 'type_name' => 'CPRI Report'],
    ['type_id' => 5, 'type_name' => 'Failure Analysis']
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SRS Electrical Appliances</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* --- Styles from dashboardnew.php (Layout & Base) --- */
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
        }

        body {
            line-height: 1.6;
            color: var(--dark-gray);
            background-color: var(--light-gray);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .muted {
            color: var(--medium-gray);
        }

        .tiny {
            font-size: 0.875rem;
        }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, var(--light-gray), var(--white));
            color: var(--primary-blue);
            padding: 40px 0;
            text-align: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 1.125rem;
            opacity: 0.9;
        }

        /* Dashboard Layout */
        .dashboard-root {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 200px);
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            padding-top: 20px;
            background: var(--white);
            border-right: 1px solid var(--border-color);
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            transition: var(--transition);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-blue);
            text-decoration: none;
            margin-bottom: 30px;
        }

        .brand i {
            font-size: 1.5rem;
        }

        .sidebar-section {
            margin-bottom: 30px;
        }

        .quick-actions .qa-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }

        .qa-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            padding: 15px 10px;
            background: var(--white);
            color: var(--primary-blue);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.875rem;
            text-align: center;
        }

        .qa-btn:hover {
            background: var(--light-gray);
            color: var(--light-blue);
        }

        .nav-list {
            list-style: none;
            margin-top: 10px;
        }

        .nav-list li {
            margin-bottom: 5px;
        }

        .nav-list button {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--primary-blue);
            text-decoration: none;
            transition: var(--transition);
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 1rem;
        }

        .nav-list button:hover,
        .nav-list button.active {
            background: var(--light-gray);
            font-weight: 600;
        }

        .nav-list a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--primary-blue);
            text-decoration: none;
            transition: var(--transition);
        }

        .nav-list a:hover {
            background: var(--light-gray);
        }

        /* Main Content */
        .main {
            flex: 1;
            padding: 20px;
            overflow-x: auto;
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary-blue);
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-blue);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 15px;
            color: var(--accent-blue);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--medium-gray);
            margin-bottom: 10px;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.75rem;
        }

        .trend-up {
            color: var(--success-green);
        }

        .trend-down {
            color: var(--danger-red);
        }

        /* --- Styles from dashboardold.php & lab-testing.php (Forms, Tables, Modals) --- */
        .card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-blue);
        }

        /* Forms */
        .form-container {
            display: none;
        }

        .form-container.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--dark-gray);
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 1rem;
            transition: var(--transition);
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(42, 134, 186, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-submit {
            background-color: var(--accent-blue);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: var(--transition);
        }

        .btn-submit:hover {
            background-color: var(--primary-blue);
        }

        /* Tables */
        .table-section {
            display: none;
        }

        .table-section.active {
            display: block;
        }

        .table-container {
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
        }

        .data-table,
        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead,
        .product-table thead {
            background-color: var(--primary-blue);
            color: var(--white);
        }

        .data-table th,
        .product-table th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
        }

        .data-table tbody tr,
        .product-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .data-table tbody tr:hover,
        .product-table tbody tr:hover {
            background-color: rgba(42, 134, 186, 0.05);
        }

        .data-table td,
        .product-table td {
            padding: 18px 15px;
            color: var(--dark-gray);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-completed,
        .status-approved,
        .status-passed {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--success-green);
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .status-processing,
        .status-in-progress {
            background-color: rgba(0, 123, 255, 0.15);
            color: #007bff;
        }

        .status-failed,
        .status-rejected {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--danger-red);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn,
        .btn-icon {
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid;
            background-color: transparent;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .edit-btn,
        .btn-edit {
            color: #ffc107;
            border-color: #ffc107;
        }

        .edit-btn:hover,
        .btn-edit:hover {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .delete-btn,
        .btn-delete {
            color: var(--danger-red);
            border-color: var(--danger-red);
        }

        .delete-btn:hover,
        .btn-delete:hover {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .view-btn,
        .btn-view {
            color: var(--accent-blue);
            border-color: var(--accent-blue);
        }

        .view-btn:hover,
        .btn-view:hover {
            background-color: rgba(42, 134, 186, 0.1);
        }

        /* Modals */
        .modal-overlay {
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

        .modal-content,
        .modal {
            background-color: var(--white);
            border-radius: 10px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: modalFadeIn 0.3s ease;
            padding: 0;
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

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--primary-blue);
            color: var(--white);
            border-radius: 10px 10px 0 0;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .close-modal,
        .modal-close {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--white);
            cursor: pointer;
            transition: var(--transition);
            line-height: 1;
        }

        .modal-body {
            padding: 30px;
        }

        /* Product Details Modal Specifics */
        .product-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .detail-label {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 8px;
        }

        .detail-value {
            color: var(--dark-gray);
            font-size: 1.1rem;
        }

        .product-description {
            line-height: 1.7;
            color: var(--dark-gray);
            padding: 15px;
            background-color: rgba(248, 249, 250, 0.8);
            border-radius: 6px;
            border-left: 4px solid var(--accent-blue);
        }

        .test-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
        }

        .action-btn.pass-btn {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-green);
            border: 2px solid var(--success-green);
            flex: 1;
        }

        .action-btn.fail-btn {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-red);
            border: 2px solid var(--danger-red);
            flex: 1;
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

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-root {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: static;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-root">
        <!-- Sidebar -->
        <aside class="sidebar">
            <a class="brand" href="index.php">
                <i class="fas fa-bolt"></i>
                <span class="title">SRS Electrical PK</span>
            </a>

            <div class="sidebar-section">
                <div class="muted tiny">Quick Actions</div>
                <div class="quick-actions">
                    <div class="qa-grid">
                        <button class="qa-btn" data-action="start-test">
                            <i class="fas fa-play-circle"></i><span>Start Test</span>
                        </button>
                        <button class="qa-btn" data-action="generate-report">
                            <i class="fas fa-file-export"></i><span>Generate</span>
                        </button>
                        <button class="qa-btn" data-action="add-product">
                            <i class="fas fa-plus-square"></i><span>Add Product</span>
                        </button>
                        <button class="qa-btn" data-action="cpri">
                            <i class="fas fa-certificate"></i><span>CPRI</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="sidebar-section">
                <div class="muted tiny">Navigation</div>
                <ul class="nav-list" role="navigation" aria-label="Sidebar navigation">
                    <li><a href="index.php"><i class="fas fa-home"></i> Homepage</a></li>
                    <li><a href="about.php"><i class="fas fa-certificate"></i> About</a></li>
                    <li><a href="product.php"><i class="fas fa-file-alt"></i> Catalogue</a></li>
                </ul>
            </div>

            <div class="sidebar-section">
                <div class="muted tiny">Data Views</div>
                <ul class="nav-list">
                    <li><button class="nav-btn active" data-target="recent-reports"><i class="fas fa-file-alt"></i> Recent Reports</button></li>
                    <li><button class="nav-btn" data-target="cpri-submissions"><i class="fas fa-certificate"></i> CPRI Submissions</button></li>
                    <li><button class="nav-btn" data-target="products-testing"><i class="fas fa-flask"></i> Products Under Test</button></li>
                </ul>
            </div>

            <div class="sidebar-section">
                <a href="logout.php" class="nav-list" style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: var(--danger-red); font-weight: 700; text-decoration: none; transition: var(--transition); margin-top: auto;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main">
            <header class="page-header">
                <div class="container">
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">Smart Testing Dashboard - Monitor tests, reports, and approvals in one place</p>
                </div>
            </header>

            <!-- Stats Grid (Visual only, matching dashboardnew.php) -->
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                        <div class="stat-value">94.7%</div>
                        <div class="stat-label">Overall Pass Rate</div>
                        <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i><span>+2.3% from last month</span></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="stat-value">5.3%</div>
                        <div class="stat-label">Failure Rate</div>
                        <div class="stat-trend trend-down"><i class="fas fa-arrow-down"></i><span>-1.1% from last month</span></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                        <div class="stat-value">1,247</div>
                        <div class="stat-label">Total Products Tested</div>
                        <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i><span>+128 this month</span></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-certificate"></i></div>
                        <div class="stat-value">312</div>
                        <div class="stat-label">CPRI Approvals</div>
                        <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i><span>+42 this quarter</span></div>
                    </div>
                </div>

                <!-- Forms Container (Hidden by default) -->
                <div id="forms-container">
                    <!-- Add Product Form -->
                    <div id="add-product-form" class="form-container card">
                        <h3>Add New Product</h3>
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
                                    <button type="button" onclick="removeImage()" style="display: block; margin-top: 10px; background: #dc3545; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">Remove Image</button>
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
                            <div class="modal-buttons">
                                <button type="submit" class="btn-submit">Add Product</button>
                            </div>
                        </form>
                    </div>

                    <!-- Start Test Form -->
                    <div id="start-test-form" class="form-container card">
                        <h3>Start Test</h3>
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
                                <button type="submit" class="btn-submit">Start Test</button>
                            </div>
                        </form>
                    </div>

                    <!-- CPRI Form -->
                    <div id="cpri-form" class="form-container card">
                        <h3>Submit Product to CPRI</h3>
                        <form id="addCpriForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="cpriProductId">Product <span style="color:red">*</span></label>
                                <select id="cpriProductId" name="product_id" required>
                                    <!-- Populate options dynamically -->
                                </select>
                                <input type="hidden" id="cpriProductName" name="product_name">
                            </div>
                            <div class="form-group">
                                <label for="submissionDate">Submission Date <span style="color:red">*</span></label>
                                <input type="date" id="submissionDate" name="submission_date" required>
                            </div>
                            <div class="form-group">
                                <label for="testDate">Test Date</label>
                                <input type="date" id="testDate" name="test_date">
                            </div>
                            <div class="form-group">
                                <label for="cpriStatus">Status</label>
                                <select id="cpriStatus" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="testingLab">Testing Lab</label>
                                <input type="text" id="testingLab" name="testing_lab">
                            </div>
                            <div class="form-group">
                                <label for="certificateFile">Certificate (PDF or Image)</label>
                                <input type="file" id="certificateFile" name="certificate_image" accept=".pdf,image/*">
                            </div>
                            <input type="hidden" id="cpriReference" name="cpri_reference">
                            <input type="hidden" id="certificateNo" name="certificate_no">
                            <div class="modal-buttons">
                                <button type="submit" class="btn-submit">Submit</button>
                            </div>
                        </form>
                    </div>

                    <!-- Generate Report Form -->
                    <div id="generate-report-form" class="form-container card">
                        <h3>Generate Custom Report</h3>
                        <form id="generateReportForm">
                            <div class="form-group">
                                <label for="reportName">Report Name</label>
                                <input type="text" id="reportName" placeholder="Enter new report name">
                            </div>
                            <div class="form-group">
                                <label for="reportType">Report Type</label>
                                <select id="reportType">
                                    <option value="">Select Report Type</option>
                                    <?php foreach ($reportTypes as $type): ?>
                                        <option value="<?= htmlspecialchars($type['type_name']) ?>"><?= htmlspecialchars($type['type_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="generatedBy">Generated By</label>
                                <select id="generatedBy">
                                    <option value="">Select Tester</option>
                                    <?php foreach ($testers as $t): ?>
                                        <option value="<?= $t['tester_id'] ?>"><?= htmlspecialchars($t['tester_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productTypeReport">Product Type</label>
                                <select id="productTypeReport">
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
                                <label for="format">Report Format</label>
                                <select id="format">
                                    <option value="pdf">PDF</option>
                                    <option value="doc">Word</option>
                                    <option value="excel">Excel</option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>
                            <div class="modal-buttons">
                                <button type="submit" class="btn-submit">Generate Report</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Tables Container -->
                <div id="tables-container">
                    <!-- Recent Reports Table -->
                    <div id="recent-reports" class="table-section active">
                        <h2 class="section-title">Recent Generated Reports</h2>
                        <div class="table-container">
                            <table class="data-table" id="recentReportsTable">
                                <thead>
                                    <tr>
                                        <th>Report Name</th>
                                        <th>Type</th>
                                        <th>Date Generated</th>
                                        <th>Format</th>
                                        <th>Status</th>
                                        <th>Generated By</th>
                                        <?php if ($isAdminLoggedIn): ?>
                                            <th>Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($recentReports) == 0): ?>
                                        <tr>
                                            <td colspan="<?= $isAdminLoggedIn ? 7 : 6 ?>" style="text-align:center;">No reports generated yet</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recentReports as $report): ?>
                                            <?php
                                            $statusClass = "status-pending";
                                            if ($report['status'] == "completed") $statusClass = "status-completed";
                                            elseif ($report['status'] == "processing") $statusClass = "status-processing";
                                            elseif ($report['status'] == "failed") $statusClass = "status-failed";
                                            ?>
                                            <tr>
                                                <td>
                                                    <a href="api/view_report.php?id=<?= $report['report_id'] ?>" target="_blank" style="text-decoration: none; color: var(--primary-blue); font-weight: 600;">
                                                        <?= htmlspecialchars($report['report_name']) ?>
                                                    </a>
                                                </td>
                                                <td><?= htmlspecialchars($report['report_type']) ?></td>
                                                <td><?= date('d M Y', strtotime($report['date_generated'])) ?></td>
                                                <td><?= strtoupper($report['format']) ?></td>
                                                <td><span class="status-badge <?= $statusClass ?>"><?= ucfirst($report['status']) ?></span></td>
                                                <td><?= htmlspecialchars($report['generated_by_name'] ?? '-') ?></td>
                                                <?php if ($isAdminLoggedIn): ?>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <button class="action-btn edit-btn"
                                                                data-type="generated"
                                                                data-id="<?= $report['report_id'] ?>"
                                                                data-name="<?= htmlspecialchars($report['report_name']) ?>"
                                                                data-rtype="<?= htmlspecialchars($report['report_type']) ?>"
                                                                data-format="<?= $report['format'] ?>"
                                                                data-status="<?= $report['status'] ?>"
                                                                data-generated_by="<?= $report['generated_by'] ?? '' ?>">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                            <button class="action-btn delete-btn" data-id="<?= $report['report_id'] ?>">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </div>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- CPRI Submissions Table -->
                    <div id="cpri-submissions" class="table-section">
                        <h2 class="section-title">CPRI Test Submissions</h2>
                        <div class="table-container">
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
                                <tbody>
                                    <?php
                                    if (count($cpri_reports) > 0) {
                                        foreach ($cpri_reports as $row) {
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
                                        echo '<tr><td colspan="7" style="text-align:center;">No CPRI submissions found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Products Under Test Table -->
                    <div id="products-testing" class="table-section">
                        <h2 class="section-title">Products Under Testing</h2>
                        <div class="table-container">
                            <table class="product-table" id="productTable">
                                <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Product Type</th>
                                        <th>Test Status</th>
                                        <th>Tester Name</th>
                                    </tr>
                                </thead>
                                <tbody id="productTableBody">
                                    <!-- Populated by JS -->
                                </tbody>
                            </table>
                        </div>
                        <div id="emptyState" style="display: none; text-align: center; padding: 20px; color: #666;">
                            <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>No products found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Edit Report Modal -->
    <div class="modal-overlay" id="editReportModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Report</h2>
                <button class="close-modal" id="closeEditModal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="editReportForm">
                    <input type="hidden" id="edit_type" name="edit_type">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label>Report Name</label>
                        <input type="text" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Report Type</label>
                        <input type="text" id="edit_rtype" name="report_type" required>
                    </div>
                    <div class="form-group">
                        <label>Format</label>
                        <input type="text" id="edit_format" name="format">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="edit_status" name="status">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="form-group" id="edit_generated_by_group">
                        <label>Generated By</label>
                        <select id="edit_generated_by" name="generated_by">
                            <option value="">Select Tester</option>
                            <?php foreach ($testers as $t): ?>
                                <option value="<?= $t['tester_id'] ?>"><?= htmlspecialchars($t['tester_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="modal-buttons">
                        <button type="submit" class="btn-submit">Update</button>
                        <button type="button" class="btn-submit" style="background-color: #6c757d;" id="cancelEdit">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CPRI Certificate Modal -->
    <div class="modal-overlay" id="certificateModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Certificate</h3>
                <button class="modal-close" onclick="closeCertificateModal()">&times;</button>
            </div>
            <div class="modal-body" id="certificateContent">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Product Details Modal -->
    <div class="modal-overlay" id="productModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Product Testing Details</h2>
                <button class="close-modal" id="closeProductModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="product-details-grid">
                    <div class="detail-item">
                        <div class="detail-label">Product ID</div>
                        <div class="detail-value" id="modalProductId">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Product Name</div>
                        <div class="detail-value" id="modalProductName">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Product Type</div>
                        <div class="detail-value" id="modalProductType">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Current Test Status</div>
                        <div class="detail-value"><span class="status-badge" id="modalTestStatus">-</span></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Test Date</div>
                        <div class="detail-value" id="modalTestDate">-</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Assigned Technician</div>
                        <div class="detail-value" id="modalTechnician">-</div>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Product Description</div>
                    <div class="product-description" id="modalProductDescription">No description available.</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Test Notes</div>
                    <div class="product-description" id="modalTestNotes">No test notes available.</div>
                </div>
                <div class="test-actions">
                    <button class="action-btn pass-btn" id="markPassBtn"><i class="fas fa-check-circle"></i> Mark as Pass</button>
                    <button class="action-btn fail-btn" id="markFailBtn"><i class="fas fa-times-circle"></i> Mark as Fail</button>
                </div>
            </div>
        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Navigation Logic ---
            const navBtns = document.querySelectorAll('.nav-btn');
            const tableSections = document.querySelectorAll('.table-section');
            const formContainers = document.querySelectorAll('.form-container');
            const qaBtns = document.querySelectorAll('.qa-btn');

            function hideAll() {
                tableSections.forEach(el => el.classList.remove('active'));
                formContainers.forEach(el => el.classList.remove('active'));
                navBtns.forEach(el => el.classList.remove('active'));
            }

            // Sidebar Navigation (Tables)
            navBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    hideAll();
                    btn.classList.add('active');
                    const targetId = btn.getAttribute('data-target');
                    document.getElementById(targetId).classList.add('active');

                    // If Products Under Test is selected, fetch data
                    if (targetId === 'products-testing') {
                        fetchTestResults();
                    }
                });
            });

            // Quick Actions (Forms)
            qaBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    hideAll();
                    const action = btn.getAttribute('data-action');
                    let formId = '';
                    switch (action) {
                        case 'start-test':
                            formId = 'start-test-form';
                            populateProducts();
                            populateTesters();
                            break;
                        case 'generate-report':
                            formId = 'generate-report-form';
                            break;
                        case 'add-product':
                            formId = 'add-product-form';
                            break;
                        case 'cpri':
                            formId = 'cpri-form';
                            populateCpriProducts();
                            break;
                    }
                    if (formId) {
                        document.getElementById(formId).classList.add('active');
                    }
                });
            });

            // --- Lab Testing Table Logic ---
            const productTableBody = document.getElementById('productTableBody');
            const emptyState = document.getElementById('emptyState');
            let testResults = [];

            async function fetchTestResults() {
                try {
                    const response = await fetch('api/get_testing-records.php');
                    const data = await response.json();
                    if (data.success) {
                        testResults = data.records;
                        renderProductTable(testResults);
                    } else {
                        console.error(data.message);
                    }
                } catch (error) {
                    console.error('Error fetching test results:', error);
                }
            }

            function renderProductTable(products) {
                productTableBody.innerHTML = '';
                if (products.length === 0) {
                    emptyState.style.display = 'block';
                    return;
                }
                emptyState.style.display = 'none';
                products.forEach(record => {
                    const row = document.createElement('tr');
                    let statusBadge = '';
                    const result = record.result ? record.result.toLowerCase() : '';
                    const status = record.status ? record.status.toLowerCase() : '';

                    if (result === 'pass') statusBadge = '<span class="status-badge status-completed">Passed</span>';
                    else if (result === 'fail') statusBadge = '<span class="status-badge status-failed">Failed</span>';
                    else if (status === 'in-progress') statusBadge = '<span class="status-badge status-in-progress">In Progress</span>';
                    else statusBadge = '<span class="status-badge status-pending">Pending</span>';

                    row.innerHTML = `
                        <td>${record.product_id}</td>
                        <td><span style="color: var(--accent-blue); font-weight: 600; cursor: pointer;" onclick="openProductModal('${record.test_id}')">${record.product_name || record.product_id}</span></td>
                        <td>${record.test_type}</td>
                        <td>${statusBadge}</td>
                        <td>${record.tester_name || '-'}</td>
                    `;
                    productTableBody.appendChild(row);
                });
            }

            // --- Product Modal Logic ---
            window.openProductModal = function(recordId) {
                const record = testResults.find(r => r.test_id == recordId);
                if (!record) return;

                document.getElementById('modalProductId').textContent = record.product_id;
                document.getElementById('modalProductName').textContent = record.product_name || record.product_id;
                document.getElementById('modalProductType').textContent = record.test_type;
                document.getElementById('modalTestDate').textContent = record.test_date;
                document.getElementById('modalTechnician').textContent = record.tester_name || '-';
                document.getElementById('modalProductDescription').textContent = record.notes || 'No description available.';
                document.getElementById('modalTestNotes').textContent = record.result || 'N/A';

                let statusText = 'Pending';
                let statusClass = 'status-pending';
                const result = record.result ? record.result.toLowerCase() : '';
                if (result === 'pass') {
                    statusText = 'Passed';
                    statusClass = 'status-completed';
                } else if (result === 'fail') {
                    statusText = 'Failed';
                    statusClass = 'status-failed';
                } else if (record.status === 'in-progress') {
                    statusText = 'In Progress';
                    statusClass = 'status-in-progress';
                }

                const statusEl = document.getElementById('modalTestStatus');
                statusEl.textContent = statusText;
                statusEl.className = `status-badge ${statusClass}`;

                document.getElementById('productModal').style.display = 'flex';
            };

            document.getElementById('closeProductModal').onclick = () => document.getElementById('productModal').style.display = 'none';

            // --- CPRI Modal Logic ---
            window.viewCertificate = function(id) {
                fetch('api/view_cpri.php?id=' + id).then(res => res.text()).then(html => {
                    document.getElementById('modalTitle').innerText = 'View Certificate';
                    document.getElementById('certificateContent').innerHTML = html;
                    document.getElementById('certificateModal').style.display = 'flex';
                });
            };
            window.editCertificate = function(id) {
                fetch('api/edit_cpri.php?id=' + id).then(res => res.text()).then(html => {
                    document.getElementById('modalTitle').innerText = 'Edit CPRI Record';
                    document.getElementById('certificateContent').innerHTML = html;
                    document.getElementById('certificateModal').style.display = 'flex';
                });
            };
            window.deleteCertificate = function(id) {
                if (confirm('Delete this submission?')) {
                    fetch('api/delete_cpri.php?id=' + id).then(res => res.text()).then(msg => {
                        alert(msg);
                        location.reload();
                    });
                }
            };
            window.closeCertificateModal = function() {
                document.getElementById('certificateModal').style.display = 'none';
            };

            // --- Report Edit Modal Logic ---
            const editReportModal = document.getElementById('editReportModal');
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('edit_id').value = btn.dataset.id;
                    document.getElementById('edit_type').value = btn.dataset.type;
                    document.getElementById('edit_name').value = btn.dataset.name;
                    document.getElementById('edit_rtype').value = btn.dataset.rtype;
                    document.getElementById('edit_format').value = btn.dataset.format;
                    document.getElementById('edit_status').value = btn.dataset.status;
                    if (btn.dataset.generated_by) document.getElementById('edit_generated_by').value = btn.dataset.generated_by;
                    editReportModal.style.display = 'flex';
                });
            });
            document.getElementById('closeEditModal').onclick = () => editReportModal.style.display = 'none';
            document.getElementById('cancelEdit').onclick = () => editReportModal.style.display = 'none';

            document.getElementById('editReportForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                // Map fields correctly for update_report.php
                if (fd.get('edit_type') === 'generated') {
                    fd.append('report_id', fd.get('id'));
                    fd.append('report_name', fd.get('name'));
                }
                fetch('api/update_report.php', {
                        method: 'POST',
                        body: fd
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            alert('Updated');
                            location.reload();
                        } else alert('Error: ' + res.message);
                    });
            });

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    if (confirm('Delete report?')) {
                        const fd = new URLSearchParams();
                        fd.append('report_id', btn.dataset.id);
                        fetch('api/delete_report.php', {
                                method: 'POST',
                                body: fd
                            })
                            .then(r => r.json())
                            .then(res => {
                                if (res.success) {
                                    btn.closest('tr').remove();
                                } else alert('Error: ' + res.message);
                            });
                    }
                });
            });

            // --- Form Population Helpers ---
            async function populateTesters() {
                const res = await fetch('api/get_testers.php');
                const data = await res.json();
                const select = document.getElementById('testerSelect');
                select.innerHTML = '<option value="">-- Select Tester --</option>';
                if (data.success) data.testers.forEach(t => {
                    select.innerHTML += `<option value="${t.tester_name}">${t.tester_name}</option>`;
                });
            }
            async function populateProducts() {
                const res = await fetch('api/get_products.php');
                const data = await res.json();
                const select = document.getElementById('productId');
                select.innerHTML = '<option value="">-- Select Product --</option>';
                if (data.success) data.products.forEach(p => {
                    select.innerHTML += `<option value="${p.product_id}">${p.product_id} - ${p.name}</option>`;
                });
            }
            async function populateCpriProducts() {
                const res = await fetch('api/get_products.php');
                const data = await res.json();
                const select = document.getElementById('cpriProductId');
                select.innerHTML = '<option value="">-- Select Product --</option>';
                if (data.success) data.products.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.product_id;
                    opt.textContent = `${p.product_id} - ${p.name}`;
                    opt.dataset.name = p.name;
                    select.appendChild(opt);
                });
            }
            document.getElementById('cpriProductId').addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                document.getElementById('cpriProductName').value = opt.dataset.name || '';
            });

            // --- Form Submissions ---
            document.getElementById('addProductForm').addEventListener('submit', function(e) {
                e.preventDefault();
                fetch('api/add_product.php', {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(r => r.json()).then(res => {
                        alert(res.message);
                        if (res.success) this.reset();
                    });
            });
            document.getElementById('startTestForm').addEventListener('submit', function(e) {
                e.preventDefault();
                fetch('api/add_test_result.php', {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(r => r.json()).then(res => {
                        alert(res.message);
                        if (res.success) this.reset();
                    });
            });
            document.getElementById('addCpriForm').addEventListener('submit', function(e) {
                e.preventDefault();
                fetch('api/add_cpri.php', {
                        method: 'POST',
                        body: new FormData(this)
                    })
                    .then(r => r.json()).then(res => {
                        alert(res.message);
                        if (res.status === 'success') this.reset();
                    });
            });
            document.getElementById('generateReportForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                fd.append('report_name', document.getElementById('reportName').value);
                fd.append('report_type', document.getElementById('reportType').value);
                fetch('api/insert_report.php', {
                        method: 'POST',
                        body: fd
                    })
                    .then(r => r.json()).then(res => {
                        if (res.success) {
                            alert('Report Generated');
                            window.open('api/view_report.php?id=' + res.report_id);
                            this.reset();
                        } else alert('Error: ' + res.message);
                    });
            });

            // Image Drop Zone
            const dropZone = document.getElementById('imageDropZone');
            const fileInput = document.getElementById('productImage');
            dropZone.onclick = () => fileInput.click();
            fileInput.onchange = (e) => {
                if (e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        document.getElementById('previewImg').src = ev.target.result;
                        document.getElementById('imagePreview').style.display = 'block';
                        dropZone.style.display = 'none';
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            };
            window.removeImage = function() {
                fileInput.value = '';
                document.getElementById('imagePreview').style.display = 'none';
                dropZone.style.display = 'block';
            };
        });
    </script>
</body>

</html>