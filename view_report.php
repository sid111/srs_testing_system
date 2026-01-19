<?php
// view_report.php
session_start();
include("config/conn.php");

// Get report ID from URL
$report_id = intval($_GET['id'] ?? 0);
if (!$report_id) {
    die("Invalid report ID.");
}

// Fetch report metadata
$reportStmt = $conn->prepare("SELECT * FROM generated_reports WHERE id = ?");
$reportStmt->bind_param("i", $report_id);
$reportStmt->execute();
$reportResult = $reportStmt->get_result();
if ($reportResult->num_rows === 0) {
    die("Report not found.");
}
$report = $reportResult->fetch_assoc();
$reportStmt->close();

// Prepare filters from report
$startDate = $report['start_date'];
$endDate = $report['end_date'];
$productType = $report['product_type'] ?: '%';
$testStatus = $report['test_status'] ?: '%';

// Fetch actual test results
$resultsQuery = "SELECT tr.id, tr.test_date, tr.tester_name, tr.product_id, tr.test_type, tr.test_result, p.name as product_name, p.category
                 FROM testing_records tr
                 LEFT JOIN products p ON tr.product_id = p.product_id
                 WHERE tr.test_date BETWEEN ? AND ?
                 AND tr.test_type LIKE ?
                 AND tr.test_result LIKE ?
                 ORDER BY tr.test_date DESC";

$resultsStmt = $conn->prepare($resultsQuery);
$resultsStmt->bind_param("ssss", $startDate, $endDate, $productType, $testStatus);
$resultsStmt->execute();
$resultsResult = $resultsStmt->get_result();
$testResults = [];
while ($row = $resultsResult->fetch_assoc()) {
    $testResults[] = $row;
}
$resultsStmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Report | <?= htmlspecialchars($report['report_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            padding: 20px;
        }

        h1 {
            color: #1a5f7a;
            margin-bottom: 10px;
        }

        .report-meta {
            margin-bottom: 20px;
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
        }

        .data-table th {
            background: #1a5f7a;
            color: #fff;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-pass {
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .status-fail {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        .status-in-progress {
            background: rgba(0, 123, 255, 0.15);
            color: #007bff;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }
    </style>
</head>

<body>
    <h1><?= htmlspecialchars($report['report_name']) ?></h1>
    <div class="report-meta">
        <p><strong>Report Type:</strong> <?= htmlspecialchars($report['report_type']) ?></p>
        <p><strong>Date Generated:</strong> <?= date('d M Y', strtotime($report['date_generated'])) ?></p>
        <p><strong>Filters:</strong>
            Start: <?= $startDate ?>,
            End: <?= $endDate ?>,
            Product Type: <?= htmlspecialchars($report['product_type'] ?: 'All') ?>,
            Status: <?= htmlspecialchars($report['test_status'] ?: 'All') ?>
        </p>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Test Date</th>
                <th>Tester</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Test Type</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($testResults) === 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">No test results found for this report</td>
                </tr>
            <?php else: ?>
                <?php $i = 1;
                foreach ($testResults as $res): ?>
                    <?php
                    $statusClass = "status-pending";
                    if ($res['test_result'] === 'Pass') $statusClass = "status-pass";
                    elseif ($res['test_result'] === 'Fail') $statusClass = "status-fail";
                    elseif ($res['test_result'] === 'In Progress') $statusClass = "status-in-progress";
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= date('d M Y', strtotime($res['test_date'])) ?></td>
                        <td><?= htmlspecialchars($res['tester_name']) ?></td>
                        <td><?= htmlspecialchars($res['product_id']) ?></td>
                        <td><?= htmlspecialchars($res['product_name']) ?></td>
                        <td><?= htmlspecialchars($res['category']) ?></td>
                        <td><?= htmlspecialchars($res['test_type']) ?></td>
                        <td><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($res['test_result']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>