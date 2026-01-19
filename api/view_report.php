<?php
// view_report.php
session_start();
include("../config/conn.php");

$report_id = $_GET['id'] ?? null;
if (!$report_id) {
    die("No report specified.");
}

// Fetch report info
$stmt = $conn->prepare("SELECT * FROM generated_reports WHERE report_id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$reportRes = $stmt->get_result();
$report = $reportRes->fetch_assoc();
if (!$report) {
    die("Report not found.");
}

// Fetch associated test records (use pre-existing testing_records if no link)
$records = [];
$testRes = $conn->query("SELECT tr.*, p.name as product_name 
                        FROM testing_records tr 
                        LEFT JOIN products p ON tr.product_id = p.product_id 
                        ORDER BY RAND() LIMIT 10"); // 10 random records
while ($r = $testRes->fetch_assoc()) {
    $records[] = $r;
}

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
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
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
    <p><strong>Status:</strong> <span class="status-badge <?= $report['status'] == 'completed' ? 'status-completed' : ($report['status'] == 'in-progress' ? 'status-in-progress' : 'status-pending') ?>"><?= ucfirst($report['status']) ?></span></p>
    <p><strong>Format:</strong> <?= strtoupper($report['format']) ?></p>
    <p><strong>Size:</strong> <?= htmlspecialchars($report['size'] ?? '-') ?></p>

    <h2>Test Records</h2>
    <table>
        <thead>
            <tr>
                <th>Test ID</th>
                <th>Product</th>
                <th>Tester</th>
                <th>Product Type</th>
                <th>Test Name</th>
                <th>Status</th>
                <th>Result</th>
                <th>Test Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($records) == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">No test records found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($records as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id'] ?? $r['test_id']) ?></td>
                        <td><?= htmlspecialchars($r['product_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($r['tester_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($r['test_type'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($r['test_name'] ?? 'N/A') ?></td>
                        <td>
                            <?php
                            $statusClass = 'status-pending';
                            if (($r['status'] ?? '') == 'completed') $statusClass = 'status-completed';
                            elseif (($r['status'] ?? '') == 'in-progress') $statusClass = 'status-in-progress';
                            elseif (($r['status'] ?? '') == 'failed') $statusClass = 'status-failed';
                            ?>
                            <span class="status-badge <?= $statusClass ?>"><?= ucfirst($r['status'] ?? 'Pending') ?></span>
                        </td>
                        <td><?= htmlspecialchars($r['result'] ?? '-') ?></td>
                        <td><?= date('d M Y', strtotime($r['test_date'] ?? date('Y-m-d'))) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>