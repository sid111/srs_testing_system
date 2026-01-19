<?php
// api/add_test_result.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
require_once '../config/conn.php';

$response = ['success' => false, 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit();
}

// POST data
$tester_name  = trim($_POST['tester_name'] ?? '');
$product_type = trim($_POST['product_type'] ?? ''); // existing column
$product_id   = trim($_POST['product_id'] ?? '');
$test_type    = trim($_POST['test_type'] ?? ''); // 4 test types
$status       = trim($_POST['status'] ?? 'in-progress');

session_start();
$created_by = $_SESSION['admin_id'] ?? 1;

// Validation
if (empty($tester_name) || empty($product_type) || empty($product_id) || empty($test_type)) {
    $response['message'] = 'Tester name, product type, product, and test type are required.';
    echo json_encode($response);
    exit();
}

// Map test_type to test_name in DB
$test_name_map = [
    'electrical'   => 'Voltage Test',
    'thermal'      => 'Thermal Test',
    'performance'  => 'Load Test',
    'cpri'         => 'Insulation Test'
];

$test_name_actual = $test_name_map[$test_type] ?? '';
if (empty($test_name_actual)) {
    $response['message'] = 'Invalid test type specified.';
    echo json_encode($response);
    exit();
}

// Test date
$test_date = date('Y-m-d');

try {
    $stmt = $conn->prepare("
        INSERT INTO testing_records (
            product_id,
            test_date,
            test_type,
            test_name,
            status,
            tester_name,
            created_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssi",
        $product_id,
        $test_date,
        $product_type,
        $test_name_actual,
        $status,
        $tester_name,
        $created_by
    );

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Test record added successfully!";
        $response['debug'] = [
            'product_id' => $product_id,
            'product_type' => $product_type,
            'test_name_actual' => $test_name_actual,
            'status' => $status,
            'tester_name' => $tester_name,
            'created_by' => $created_by
        ];
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

$conn->close();
echo json_encode($response);
?>
