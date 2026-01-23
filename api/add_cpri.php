<?php
header('Content-Type: application/json');
include '../config/conn.php'; // Database connection

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Required fields
    $product_id      = trim($_POST['product_id']);
    $submission_date = $_POST['submission_date'] ?? null;
    $test_date       = $_POST['test_date'] ?? null;
    $status          = $_POST['status'] ?? 'pending';
    $testing_lab     = $_POST['testing_lab'] ?? null;

    if (!$product_id || !$submission_date) {
        echo json_encode(['error' => 'Product and Submission Date are required.']);
        exit;
    }

    // Generate CPRI Reference and Certificate No
    $year           = date('Y');
    $mmdd           = date('md');
    $cpri_reference = "CPRI-$year-$mmdd";
    $certificate_no = "CPRI-CERT-$mmdd";

    // Handle certificate upload (optional)
    $certificate_file_path = null;
    if (!empty($_FILES['certificate_file']['name'])) {
        $file     = $_FILES['certificate_file'];
        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_name = "cpri_cert_" . time() . "." . $ext;
        $upload_path = '../uploads/cpri_certificates/' . $new_name;

        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            echo json_encode(['error' => 'Failed to upload certificate']);
            exit;
        }
        $certificate_file_path = 'uploads/cpri_certificates/' . $new_name;
    }

    // Insert record
    $stmt = $conn->prepare("INSERT INTO cpri_reports 
        (product_id, submission_date, cpri_reference, test_date, status, certificate_no, testing_lab, certificate_image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssss",
        $product_id,
        $submission_date,
        $cpri_reference,
        $test_date,
        $status,
        $certificate_no,
        $testing_lab,
        $certificate_file_path
    );

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'CPRI record added successfully',
            'cpri_reference' => $cpri_reference,
            'certificate_no' => $certificate_no
        ];
    } else {
        $response = ['error' => 'Database error: ' . $stmt->error];
    }

    $stmt->close();
    $conn->close();

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
