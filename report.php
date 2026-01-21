<?php include 'config/admin_session.php'; ?>

<?php
include("config/conn.php");

// --- Fetch Analytics Cards ---
$analyticsCards = [];
$analyticsQuery = "SELECT label, value, type FROM analytics_summary ORDER BY id ASC";
$analyticsResult = $conn->query($analyticsQuery);
if ($analyticsResult) {
    while ($row = $analyticsResult->fetch_assoc()) {
        $analyticsCards[] = $row;
    }
}

// --- Fetch Recent Reports ---
$recentReports = [];
$recentQuery = "SELECT * FROM generated_reports ORDER BY date_generated DESC";
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
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics | SRS Electrical Lab Automation</title>
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
            --warning-orange: #ffc107;
            --danger-red: #dc3545;
        }

        body {
            line-height: 1.6;
            color: var(--dark-gray);
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        section {
            padding: 30px 0;
        }

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

        /* Page Header */
        .page-header {
            background-color: var(--white);
            padding: 30px 0;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2.5rem;
            color: var(--primary-blue);
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: var(--medium-gray);
            font-size: 1.1rem;
        }

        /* Section Headings */
        .section-title {
            font-size: 1.8rem;
            color: var(--primary-blue);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-blue);
        }

        /* Analytics Cards */
        .analytics-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 40px;
        }

        .analytics-card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 25px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border-top: 4px solid var(--accent-blue);
        }

        .analytics-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card-1 {
            border-top-color: var(--success-green);
        }

        .card-2 {
            border-top-color: var(--danger-red);
        }

        .card-3 {
            border-top-color: var(--accent-blue);
        }

        .card-4 {
            border-top-color: #9c27b0;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .card-1 .card-icon {
            color: var(--success-green);
        }

        .card-2 .card-icon {
            color: var(--danger-red);
        }

        .card-3 .card-icon {
            color: var(--accent-blue);
        }

        .card-4 .card-icon {
            color: #9c27b0;
        }

        .card-value {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark-gray);
        }

        .card-label {
            color: var(--medium-gray);
            font-size: 1rem;
            font-weight: 500;
        }

        .card-trend {
            display: flex;
            align-items: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .trend-up {
            color: var(--success-green);
        }

        .trend-down {
            color: var(--danger-red);
        }

        /* Form Styles */
        .form-section {
            background-color: var(--white);
            border-radius: 10px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
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
            box-shadow: 0 0 0 3px rgba(42, 134, 186, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--accent-blue);
            color: var(--white);
        }

        .btn-primary:hover {
            background-color: var(--primary-blue);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(42, 134, 186, 0.3);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: var(--white);
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-success {
            background-color: var(--success-green);
            color: var(--white);
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        /* Tables */
        .table-container {
            background-color: var(--white);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background-color: var(--primary-blue);
            color: var(--white);
        }

        .data-table th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 1rem;
        }

        .data-table tbody tr {
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .data-table tbody tr:hover {
            background-color: rgba(42, 134, 186, 0.05);
        }

        .data-table td {
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

        .status-completed {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--success-green);
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: var(--warning-orange);
        }

        .status-processing {
            background-color: rgba(0, 123, 255, 0.15);
            color: #007bff;
        }

        .status-failed {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--danger-red);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            background-color: transparent;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .view-btn {
            color: var(--accent-blue);
            border: 1px solid var(--accent-blue);
        }

        .view-btn:hover {
            background-color: rgba(42, 134, 186, 0.1);
        }

        .edit-btn {
            color: var(--warning-orange);
            border: 1px solid var(--warning-orange);
        }

        .edit-btn:hover {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .delete-btn {
            color: var(--danger-red);
            border: 1px solid var(--danger-red);
        }

        .delete-btn:hover {
            background-color: rgba(220, 53, 69, 0.1);
        }

        /* Schedule Report Modal */
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

        .modal-content {
            background-color: var(--white);
            border-radius: 10px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 30px;
            border-bottom: 1px solid var(--border-color);
            background-color: var(--primary-blue);
            color: var(--white);
            border-radius: 10px 10px 0 0;
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.8rem;
            color: var(--white);
            cursor: pointer;
            transition: var(--transition);
            line-height: 1;
        }

        .close-modal:hover {
            color: var(--light-blue);
        }

        .modal-body {
            padding: 30px;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .radio-group {
            margin-bottom: 20px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .radio-option input {
            margin-right: 10px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 2100;
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification-success {
            background-color: var(--success-green);
        }

        .notification-error {
            background-color: var(--danger-red);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: var(--medium-gray);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--border-color);
        }

        /* Responsive Styles */
        @media (max-width: 1200px) {
            .analytics-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .page-title {
                font-size: 2.2rem;
            }

            .section-title {
                font-size: 1.6rem;
            }

            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .analytics-cards {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 1.8rem;
            }

            .card-value {
                font-size: 2.2rem;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }

            .data-table {
                display: block;
                overflow-x: auto;
            }

            .action-buttons {
                flex-wrap: wrap;
            }

            .schedule-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .page-title {
                font-size: 1.6rem;
            }

            .modal-content {
                width: 95%;
                padding: 15px;
            }

            .modal-body {
                padding: 20px;
            }
        }

        /* Table responsive helper */
        .table-responsive {
            overflow-x: auto;
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

                </ul>
            </nav>
        </div>
    </header>

    <header class="page-header">
        <div class="container">
            <h1 class="page-title">Reports and Analytics</h1>
            <p class="page-subtitle">Comprehensive testing reports and analytics dashboard for lab automation system</p>
        </div>
    </header>

    <div class="container">
        <!-- Analytics Cards Section -->
        <section>
            <div class="analytics-cards">
                <?php
                $cardColors = ['card-1', 'card-2', 'card-3', 'card-4'];
                $cardIcons = ['fa-chart-line', 'fa-exclamation-triangle', 'fa-boxes', 'fa-certificate'];
                $i = 0;
                foreach ($analyticsCards as $card):
                    $colorClass = $cardColors[$i % count($cardColors)];
                    $iconClass = $cardIcons[$i % count($cardIcons)];
                ?>
                    <div class="analytics-card <?= $colorClass ?>">
                        <div class="card-icon"><i class="fas <?= $iconClass ?>"></i></div>
                        <div class="card-value"><?= htmlspecialchars($card['value']) ?></div>
                        <div class="card-label"><?= htmlspecialchars($card['label']) ?></div>
                    </div>
                <?php $i++;
                endforeach; ?>
            </div>
        </section>

        <?php if ($isAdminLoggedIn): ?>
            <!-- Custom Report Section -->
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

        <!-- Recent Reports Table -->
        <section>
            <h2 class="section-title">Recent Generated Reports</h2>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="data-table" id="recentReportsTable">
                        <thead>
                            <tr>
                                <th>Report Name</th>
                                <th>Type</th>
                                <th>Date Generated</th>
                                <th>Format</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recentReports) == 0): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;">No reports generated yet</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentReports as $report): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($report['report_name']) ?></td>
                                        <td><?= htmlspecialchars($report['report_type']) ?></td>
                                        <td><?= date('d M Y', strtotime($report['date_generated'])) ?></td>
                                        <td><?= strtoupper($report['format']) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = "status-pending";
                                            if ($report['status'] == "completed") $statusClass = "status-completed";
                                            elseif ($report['status'] == "processing") $statusClass = "status-processing";
                                            elseif ($report['status'] == "failed") $statusClass = "status-failed";
                                            ?>
                                            <span class="status-badge <?= $statusClass ?>"><?= ucfirst($report['status']) ?></span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn view-btn" data-id="<?= $report['report_id'] ?>"><i class="fas fa-eye"></i> View</button>
                                                <button class="action-btn edit-btn"
                                                    data-type="generated"
                                                    data-id="<?= $report['report_id'] ?>"
                                                    data-name="<?= htmlspecialchars($report['report_name']) ?>"
                                                    data-rtype="<?= htmlspecialchars($report['report_type']) ?>"
                                                    data-format="<?= $report['format'] ?>"
                                                    data-status="<?= $report['status'] ?>">
                                                    <i class="fas fa-edit"></i>Edit
                                                </button>
                                                <button class="action-btn delete-btn" data-id="<?= $report['report_id'] ?>"><i class="fas fa-trash"></i> Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Scheduled Reports Table -->
        <section>
            <h2 class="section-title">Scheduled Reports</h2>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="data-table" id="scheduledReportsTable">
                        <thead>
                            <tr>
                                <th>Schedule Name</th>
                                <th>Frequency</th>
                                <th>Next Run</th>
                                <th>Last Run</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($scheduledReports) == 0): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;">No scheduled reports</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($scheduledReports as $schedule): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($schedule['schedule_name']) ?></td>
                                        <td><?= ucfirst($schedule['frequency']) ?></td>
                                        <td><?= date('d M Y H:i', strtotime($schedule['next_run'])) ?></td>
                                        <td><?= $schedule['last_run'] ? date('d M Y H:i', strtotime($schedule['last_run'])) : '-' ?></td>
                                        <td>
                                            <span class="status-badge <?= $schedule['status'] == "active" ? "status-completed" : "status-pending" ?>"><?= ucfirst($schedule['status']) ?></span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="action-btn edit-btn"
                                                    data-type="scheduled"
                                                    data-id="<?= $schedule['schedule_id'] ?>"
                                                    data-name="<?= htmlspecialchars($schedule['schedule_name']) ?>"
                                                    data-frequency="<?= $schedule['frequency'] ?>"
                                                    data-next="<?= $schedule['next_run'] ?>"
                                                    data-status="<?= $schedule['status'] ?>"
                                                    data-rtype="<?= $schedule['report_type'] ?>">

                                                    <i class="fas fa-edit"></i>Edit
                                                </button>
                                                <button class="action-btn delete-btn" data-id="<?= $schedule['schedule_id'] ?>"><i class="fas fa-trash"></i> Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <script>
        // --- GENERATE REPORT ---
        document.getElementById("generateReportBtn").addEventListener("click", async function(e) {
            e.preventDefault();

            const reportName = document.getElementById("reportName").value.trim();
            const reportType = document.getElementById("reportType").value;
            const productType = document.getElementById("productType").value;
            const format = document.getElementById("format").value;

            if (!reportName) return alert("Please enter a report name.");
            if (!reportType) return alert("Please select a report type.");

            const formData = new FormData();
            formData.append("report_name", reportName);
            formData.append("report_type", reportType);
            formData.append("product_type", productType);
            formData.append("format", format);

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

                // Add new row to table dynamically
                const tableBody = document.querySelector("#recentReportsTable tbody");
                const emptyRow = tableBody.querySelector("tr td[colspan='6']");
                if (emptyRow) emptyRow.parentElement.remove();

                const tr = document.createElement("tr");

                const generatedDate = new Date(data.date_generated);
                const dateStr = generatedDate.toLocaleDateString("en-GB", {
                    day: "2-digit",
                    month: "short",
                    year: "numeric"
                });

                let statusClass = "status-processing";
                if (data.status === "completed") statusClass = "status-completed";
                else if (data.status === "failed") statusClass = "status-failed";
                else if (data.status === "pending") statusClass = "status-pending";

                tr.innerHTML = `
            <td>${reportName}</td>
            <td>${data.report_type}</td>
            <td>${dateStr}</td>
            <td>${format.toUpperCase()}</td>
            <td><span class="status-badge ${statusClass}">${data.status.replace("-", " ")}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn view-btn" data-id="${data.report_id}"><i class="fas fa-eye"></i> View</button>
                    <button class="action-btn delete-btn" data-id="${data.report_id}"><i class="fas fa-trash"></i> Delete</button>
                </div>
            </td>
        `;

                tableBody.prepend(tr);

                // Attach button events
                tr.querySelector(".view-btn").addEventListener("click", function() {
                    window.open("api/view_report.php?id=" + this.dataset.id, "_blank");
                });

                tr.querySelector(".delete-btn").addEventListener("click", function() {
                    const reportId = this.dataset.id;
                    if (confirm("Are you sure you want to delete this report?")) {
                        fetch("api/delete_report.php", {
                                method: "POST",
                                body: new URLSearchParams({
                                    report_id: reportId
                                })
                            })
                            .then(res => res.json())
                            .then(delData => {
                                if (delData.success) tr.remove();
                                else alert("Error: " + delData.message);
                            });
                    }
                });

                // Reset form
                document.getElementById("reportName").value = "";
                document.getElementById("reportType").value = "";
                document.getElementById("productType").value = "";
                document.getElementById("format").value = "pdf";

            } catch (err) {
                console.error(err);
                alert("❌ Unexpected error occurred.");
            }
        });


        // --- SCHEDULE REPORT ---
        document.getElementById("scheduleReportBtn").addEventListener("click", function() {
            const formData = new FormData();
            formData.append("report_type", document.getElementById("reportType").value);
            formData.append("start_date", document.getElementById("startDate").value);
            formData.append("end_date", document.getElementById("endDate").value);
            formData.append("product_type", document.getElementById("productType").value);
            formData.append("test_status", document.getElementById("testStatus").value);
            formData.append("format", document.getElementById("format").value);

            fetch("api/insert_schedule.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert("Report scheduled successfully!");
                        location.reload();
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Error scheduling report.");
                });
        });

        // // Delete & View Buttons
        // document.querySelectorAll(".delete-btn").forEach(btn => {
        // btn.addEventListener("click", function() {
        // const id = this.dataset.id;
        // if (confirm("Are you sure?")) {
        // fetch("api/delete_report.php", {
        // method: "POST",
        // body: new URLSearchParams({
        // id
        // })
        // })
        // .then(res => res.json())
        // .then(data => {
        // if (data.success) {
        // alert("Deleted successfully");
        // location.reload();
        // } else {
        // alert("Error: " + data.message);
        // }
        // });
        // }
        // });
        // });
        document.querySelectorAll(".view-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                window.open("api/view_report.php?id=" + this.dataset.id, "_blank");
            });
        });

        //EDIT BUTTONS
        document.querySelectorAll(".edit-btn").forEach(btn => {
            btn.addEventListener("click", () => {

                const type = btn.dataset.type;
                const fd = new URLSearchParams();
                fd.append("edit_type", type);

                if (type === "generated") {

                    fd.append("report_id", btn.dataset.id);
                    fd.append("report_name", prompt("Report name:", btn.dataset.name));
                    fd.append("report_type", prompt("Report type:", btn.dataset.rtype));
                    fd.append("format", prompt("Format:", btn.dataset.format));
                    fd.append("status", prompt("Status:", btn.dataset.status));

                } else {

                    fd.append("schedule_id", btn.dataset.id);
                    fd.append("schedule_name", prompt("Schedule name:", btn.dataset.name));
                    fd.append("frequency", prompt("Frequency:", btn.dataset.frequency));
                    fd.append("next_run", prompt("Next run (YYYY-MM-DD HH:MM:SS):", btn.dataset.next));
                    fd.append("status", prompt("Status:", btn.dataset.status));
                    fd.append("report_type", prompt("Report type:", btn.dataset.rtype));
                }

                fetch("api/update_report.php", {
                        method: "POST",
                        body: fd
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            alert("✅ Updated successfully");
                            location.reload();
                        } else {
                            alert("❌ " + res.message);
                        }
                    });
            });
        });

        // Delete Buttons
        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                const id = this.dataset.id;
                // Check if this button is inside the scheduled reports table
                const isScheduled = this.closest("#scheduledReportsTable") !== null;
                const bodyData = new URLSearchParams();
                if (isScheduled) {
                    bodyData.append("schedule_id", id);
                } else {
                    bodyData.append("report_id", id);
                }

                if (confirm("Are you sure you want to delete this report?")) {
                    fetch("api/delete_report.php", {
                            method: "POST",
                            body: bodyData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert("Deleted successfully");
                                location.reload();
                            } else {
                                alert("Error: " + data.message);
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Error deleting report.");
                        });
                }
            });
        });

        <?php if ($isAdminLoggedIn): ?>
            document.addEventListener("DOMContentLoaded", function() {
                const params = new URLSearchParams(window.location.search);

                // Only scroll when coming from dashboard
                if (params.get("from") !== "dashboard") return;

                const section = document.getElementById("generate-report");
                if (!section) return;

                // Scroll slightly ABOVE the heading
                const yOffset = -140; // tweak if needed
                const y = section.getBoundingClientRect().top + window.pageYOffset + yOffset;

                window.scrollTo({
                    top: y,
                    behavior: "smooth"
                });

                // Remove query param so refresh does NOT scroll again
                history.replaceState({}, document.title, window.location.pathname);
            });
        <?php endif; ?>
    </script>
</body>

</html>