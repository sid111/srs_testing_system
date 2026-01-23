<?php
include '../config/conn.php';
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

$required = ['product_id', 'product_name', 'submission_date', 'status'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $response['message'] = "Missing field: $field";
        echo json_encode($response);
        exit;
    }
}

$product_id       = $_POST['product_id'];
$product_name     = $_POST['product_name'];
$submission_date  = $_POST['submission_date'];
$status            = $_POST['status'];
$cpri_reference    = $_POST['cpri_reference'] ?? null;
$test_date         = $_POST['test_date'] ?? null;
$certificate_no    = $_POST['certificate_no'] ?? null;
$valid_until       = $_POST['valid_until'] ?? null;
$testing_lab       = $_POST['testing_lab'] ?? null;

$certificate_image = null;

/* ✅ Handle certificate image upload */
if (!empty($_FILES['certificate']['name'])) {
    $dir = '../uploads/certificates/';
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $ext = pathinfo($_FILES['certificate']['name'], PATHINFO_EXTENSION);
    $file = 'cpri_' . time() . '_' . uniqid() . '.' . $ext;
    $path = $dir . $file;

    if (!move_uploaded_file($_FILES['certificate']['tmp_name'], $path)) {
        echo json_encode(['status' => 'error', 'message' => 'Certificate upload failed']);
        exit;
    }
    $certificate_image = 'uploads/certificates/' . $file;
}

/* ✅ Insert */
$stmt = $conn->prepare("
INSERT INTO cpri_reports
(product_id, product_name, submission_date, cpri_reference, test_date, status,
 certificate_no, valid_until, testing_lab, certificate_image)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssssssss",
    $product_id,
    $product_name,
    $submission_date,
    $cpri_reference,
    $test_date,
    $status,
    $certificate_no,
    $valid_until,
    $testing_lab,
    $certificate_image
);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'CPRI certificate submitted']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}
