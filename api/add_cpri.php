<?php
require_once '../config/conn.php';
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // REQUIRED
    $product_id       = $_POST['product_id'] ?? null;
    $submission_date  = $_POST['submission_date'] ?? null;
    $status           = $_POST['status'] ?? 'pending';

    if (!$product_id || !$submission_date) {
        throw new Exception('Missing required fields');
    }

    // OPTIONAL
    $test_date   = $_POST['test_date'] ?? null;
    $testing_lab = $_POST['testing_lab'] ?? null;

    // AUTO-GENERATED VALUES
    $now  = new DateTime();
    $year = $now->format('Y');
    $mmdd = $now->format('md');

    $cpri_reference = "CPRI-$year-$mmdd";
    $certificate_no = "CPRI-CERT-$mmdd";

    // CERTIFICATE IMAGE (single upload)
    $certificate_image = null;

    if (!empty($_FILES['certificate_image']['name'])) {
        $uploadDir = '../uploads/cpri/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['certificate_image']['name'], PATHINFO_EXTENSION));
        $filename = uniqid('cpri_') . '.' . $ext;
        $target = $uploadDir . $filename;

        if (!move_uploaded_file($_FILES['certificate_image']['tmp_name'], $target)) {
            throw new Exception('Failed to upload certificate image');
        }

        $certificate_image = 'uploads/cpri/' . $filename;
    }

    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO cpri_reports (
            product_id,
            submission_date,
            test_date,
            status,
            testing_lab,
            cpri_reference,
            certificate_no,
            certificate_image
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssss",
        $product_id,
        $submission_date,
        $test_date,
        $status,
        $testing_lab,
        $cpri_reference,
        $certificate_no,
        $certificate_image
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    echo json_encode([
        'status'  => 'success',
        'message' => 'CPRI record added successfully'
    ]);
} catch (Throwable $e) {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
}
