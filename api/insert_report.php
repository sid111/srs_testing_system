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
$status       = $_POST['test_status'] ?? 'Pending'; // FROM DROPDOWN

// Optional: sanitize allowed values
$allowed_statuses = ['Passed', 'Failed', 'Pending', 'In progress'];
if (!in_array($status, $allowed_statuses)) {
    $status = 'Pending';
}


// Validation
if (empty($report_name)) {
    echo json_encode(['success' => false, 'message' => 'Report name is required']);
    exit;
}
if (empty($report_type)) {
    echo json_encode(['success' => false, 'message' => 'Report type is required']);
    exit;
}

/* -----------------------
   RANDOM SIZE GENERATION
   0 KB → 10 MB
------------------------*/
$bytes = rand(0, 10 * 1024 * 1024); // 0 to 10 MB in bytes

if ($bytes < 1024) {
    $size = $bytes . ' KB';
} elseif ($bytes < 1024 * 1024) {
    $size = round($bytes / 1024, 2) . ' KB';
} else {
    $size = round($bytes / (1024 * 1024), 2) . ' MB';
}

// Date
$date_generated = date('Y-m-d H:i:s');

// Insert
$stmt = $conn->prepare("
    INSERT INTO generated_reports 
    (report_name, report_type, date_generated, format, status, size) 
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssss",
    $report_name,
    $report_type,
    $date_generated,
    $format,
    $status,
    $size
);

if ($stmt->execute()) {
    echo json_encode([
        'success'        => true,
        'message'        => 'Report generated successfully',
        'report_id'      => $stmt->insert_id,
        'report_type'    => $report_type,
        'status'         => $status,
        'date_generated' => $date_generated,
        'size'           => $size
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database error',
        'error'   => $stmt->error
    ]);
}

ob_end_flush();
