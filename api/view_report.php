<?php
// view_report.php
session_start();
include("../config/conn.php");

// Get report ID
$report_id = $_GET['id'] ?? null;
if (!$report_id) die("No report specified.");

// Fetch report info from generated_reports
$stmt = $conn->prepare("SELECT * FROM generated_reports WHERE report_id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();
if (!$report) die("Report not found.");
$stmt->close();

$testStmt = $conn->prepare("
    SELECT 
        tr.test_id,
        tr.product_id,
        p.name AS product_name,
        t.tester_name,
        tr.test_type,
        tr.status,
        tr.test_date
    FROM testing_records tr
    LEFT JOIN products p ON tr.product_id = p.product_id
    LEFT JOIN testers t ON tr.tester_id = t.tester_id
    ORDER BY RAND()
    LIMIT 5
");

$testStmt->execute();
$records = $testStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$testStmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($report['report_name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
            padding: 20px;
        }

        h1 {
            color: #1a5f7a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #1a5f7a;
            color: #fff;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }

        .status-completed {
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }

        .status-in-progress {
            background: rgba(0, 123, 255, 0.15);
            color: #007bff;
        }

        .status-failed {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }
    </style>
</head>

<body>

    <h1><?= htmlspecialchars($report['report_name']) ?></h1>
    <p><strong>Type:</strong> <?= htmlspecialchars($report['report_type']) ?></p>
    <p><strong>Date Generated:</strong> <?= date('d M Y', strtotime($report['date_generated'])) ?></p>
    <p><strong>Status:</strong>
        <span class="status-badge <?= $report['status'] == 'completed' ? 'status-completed' : ($report['status'] == 'in-progress' ? 'status-in-progress' : 'status-pending') ?>">
            <?= ucfirst($report['status']) ?>
        </span>
    </p>
    <p><strong>Format:</strong> <?= strtoupper($report['format']) ?></p>
    <p><strong>Size:</strong> <?= htmlspecialchars($report['size'] ?? '-') ?></p>

    <h2>Test Records</h2>
    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Tester</th>
                <th>Product Type</th>
                <th>Status</th>
                <th>Test Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['product_id'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($r['product_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($r['tester_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($r['test_type'] ?? 'N/A') ?></td>
                    <td>
                        <?php
                        $statusClass = 'status-pending';
                        if (($r['status'] ?? '') == 'completed') $statusClass = 'status-completed';
                        elseif (($r['status'] ?? '') == 'in-progress') $statusClass = 'status-in-progress';
                        elseif (($r['status'] ?? '') == 'failed') $statusClass = 'status-failed';
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= ucfirst($r['status'] ?? 'Pending') ?></span>
                    </td>
                    <td><?= date('d M Y', strtotime($r['test_date'] ?? date('Y-m-d'))) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>