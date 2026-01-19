<?php
// api/insert_report.php
header('Content-Type: application/json');
include("../config/conn.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$report_type = $_POST['report_type'] ?? '';
$format = $_POST['format'] ?? 'pdf';

if (empty($report_type)) {
    echo json_encode(['success' => false, 'message' => 'Report type is required']);
    exit;
}

// Generate a random report name
$report_name = ucwords(str_replace('-', ' ', $report_type)) . " Report";

// Current date
$date_generated = date('Y-m-d H:i:s');

// Status
$status = 'completed';

// Insert into generated_reports
$stmt = $conn->prepare("INSERT INTO generated_reports (report_name, report_type, date_generated, format, status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $report_name, $report_type, $date_generated, $format, $status);
if ($stmt->execute()) {
    $report_id = $stmt->insert_id;

    // Optional: associate random test records for this report
    $testsResult = $conn->query("SELECT test_id FROM testing_records ORDER BY RAND() LIMIT 5");
    while ($row = $testsResult->fetch_assoc()) {
        $conn->query("INSERT INTO report_tests (report_id, test_id) VALUES ($report_id, " . $row['test_id'] . ")");
    }

    echo json_encode(['success' => true, 'message' => 'Report generated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
}
