<?php
ob_start();
session_start();
header('Content-Type: application/json');
include("../config/conn.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get POST data
$report_name  = trim($_POST['report_name'] ?? '');
$report_type  = $_POST['report_type'] ?? '';
$format       = $_POST['format'] ?? 'pdf';
$generated_by = $_POST['generated_by'] ?? null; // tester_id from dropdown

// Optional: sanitize allowed values
$allowed_formats = ['pdf', 'doc', 'excel', 'csv'];
if (!in_array(strtolower($format), $allowed_formats)) $format = 'pdf';

// Validation
if (empty($report_name)) {
    echo json_encode(['success' => false, 'message' => 'Report name is required']);
    exit;
}
if (empty($report_type)) {
    echo json_encode(['success' => false, 'message' => 'Report type is required']);
    exit;
}

// Random size generation
$bytes = rand(0, 10 * 1024 * 1024); // 0 → 10 MB
if ($bytes < 1024) {
    $size = $bytes . ' KB';
} elseif ($bytes < 1024 * 1024) {
    $size = round($bytes / 1024, 2) . ' KB';
} else {
    $size = round($bytes / (1024 * 1024), 2) . ' MB';
}

// Date
$date_generated = date('Y-m-d H:i:s');

// Insert into generated_reports including generated_by
$stmt = $conn->prepare("
    INSERT INTO generated_reports 
    (report_name, report_type, date_generated, format, status, size, generated_by) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

// Default status is pending
$status = 'Pending';

$stmt->bind_param(
    "sssssss",
    $report_name,
    $report_type,
    $date_generated,
    $format,
    $status,
    $size,
    $generated_by
);

// Execute
if ($stmt->execute()) {

    $report_id = $stmt->insert_id;

    // Fetch tester name if selected
    $tester_name = '-';
    if (!empty($generated_by)) {
        $tStmt = $conn->prepare("SELECT tester_name FROM testers WHERE tester_id = ?");
        $tStmt->bind_param("i", $generated_by);
        $tStmt->execute();
        $tStmt->bind_result($tester_nameResult);
        if ($tStmt->fetch()) $tester_name = $tester_nameResult;
        $tStmt->close();
    }

    echo json_encode([
        'success'        => true,
        'message'        => 'Report generated successfully',
        'report_id'      => $report_id,
        'report_type'    => $report_type,
        'status'         => $status,
        'date_generated' => $date_generated,
        'size'           => $size,
        'generated_by_name' => $tester_name
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database error',
        'stmt_error' => $stmt->error
    ]);
}

$stmt->close();
ob_end_flush();
