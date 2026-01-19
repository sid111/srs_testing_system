<?php
// api/add_test_result.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Include the database connection
require_once '../config/conn.php';

// Function to get tester ID, or create a new tester if not found
function get_or_create_tester_id($conn, $tester_name) {
    $stmt = $conn->prepare("SELECT tester_id FROM testers WHERE tester_name = ?");
    $stmt->bind_param("s", $tester_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['tester_id'];
    } else {
        $stmt = $conn->prepare("INSERT INTO testers (tester_name) VALUES (?)");
        $stmt->bind_param("s", $tester_name);
        $stmt->execute();
        return $conn->insert_id;
    }
}

// Function to get test ID from test name
function get_test_id($conn, $test_name) {
    $stmt = $conn->prepare("SELECT test_id FROM tests WHERE test_name = ?");
    $stmt->bind_param("s", $test_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row ? $row['test_id'] : null;
}

$response = ['success' => false, 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tester_name = trim($_POST['tester_name'] ?? '');
    $test_type = trim($_POST['test_type'] ?? '');
    $product_id = trim($_POST['product_id'] ?? '');
    $product_type = trim($_POST['product_type'] ?? '');
    $status = trim($_POST['status'] ?? 'in-progress');

    if (empty($tester_name) || empty($test_type) || empty($product_id) || empty($product_type)) {
        $response['message'] = 'Tester name, test type, product, and product type are required.';
    } else {
        // Map test_type from form to test_name in DB
        $test_name_map = [
            'electrical' => 'Voltage Test',
            'thermal' => 'Thermal Test',
            'performance' => 'Load Test',
            'cpri' => 'Insulation Test'
        ];
        $test_name = $test_name_map[$test_type] ?? '';

        if (empty($test_name)) {
            $response['message'] = 'Invalid test type specified.';
        } else {
            $tester_id = get_or_create_tester_id($conn, $tester_name);
            $test_id = get_test_id($conn, $test_name);
            $result = null;
            $test_date = date('Y-m-d');

            // Set result if status is pass/fail
            if ($status === 'pass') {
                $result = 'Pass';
            } elseif ($status === 'fail') {
                $result = 'Fail';
            }

            if ($tester_id && $test_id && $product_id) {
                $stmt = $conn->prepare("INSERT INTO testing_records (tester_id, product_id, test_id, test_date, test_type, status, result, voltage_rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $voltage_rating = null; // You can fetch this if needed
                $stmt->bind_param("issiisss", $tester_id, $product_id, $test_id, $test_date, $product_type, $status, $result, $voltage_rating);

                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Test started successfully!";
                } else {
                    $response['message'] = 'Error inserting test record: ' . $stmt->error;
                }
            } else {
                $response['message'] = 'Failed to retrieve necessary IDs.';
            }
        }
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
