<?php
// api/add_schedule.php
header('Content-Type: application/json');
include('../config/conn.php');

$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Logging ---
    $log_file = __DIR__ . '/debug.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "--- [{$timestamp}] New Schedule Request ---\n", FILE_APPEND);
    file_put_contents($log_file, "POST Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

    // --- Main Fields ---
    $schedule_name = $_POST['schedule_name'] ?? null;
    $report_type = $_POST['report_type'] ?? null;
    $frequency = isset($_POST['frequency']) ? ucfirst($_POST['frequency']) : 'Weekly';
    $next_run = $_POST['next_run'] ?? date('Y-m-d H:i:s', strtotime('+1 week'));
    $status = 'active';

    // --- Optional Filter Fields ---
    $generated_by = !empty($_POST['generated_by']) ? (int)$_POST['generated_by'] : null;
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $product_type = !empty($_POST['product_type']) ? $_POST['product_type'] : null;
    $test_status = !empty($_POST['test_status']) ? $_POST['test_status'] : null;
    $format = !empty($_POST['format']) ? $_POST['format'] : null;


    if ($schedule_name && $report_type) {
        $sql = "INSERT INTO scheduled_reports (schedule_name, report_type, frequency, next_run, status, generated_by, start_date, end_date, product_type, test_status, format) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        file_put_contents($log_file, "SQL: " . $sql . "\n", FILE_APPEND);
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param(
                "sssssisssss",
                $schedule_name,
                $report_type,
                $frequency,
                $next_run,
                $status,
                $generated_by,
                $start_date,
                $end_date,
                $product_type,
                $test_status,
                $format
            );

            if ($stmt->execute()) {
                $new_id = $stmt->insert_id;
                $response = [
                    'success' => true,
                    'message' => '✅ Schedule added successfully!',
                    'schedule_id' => $new_id,
                    'schedule_name' => $schedule_name,
                    'report_type' => $report_type,
                    'frequency' => $frequency,
                    'next_run' => $next_run,
                    'status' => $status,
                    'generated_by' => $generated_by,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'product_type' => $product_type,
                    'test_status' => $test_status,
                    'format' => $format
                ];
                file_put_contents($log_file, "Result: Success\n", FILE_APPEND);
            } else {
                $response['message'] = 'Database error: Could not execute statement.';
                $response['stmt_error'] = $stmt->error;
                file_put_contents($log_file, "Execution Error: " . $stmt->error . "\n", FILE_APPEND);
            }
            $stmt->close();
        } else {
            $response['message'] = 'Database error: Could not prepare statement.';
            $response['conn_error'] = $conn->error;
            file_put_contents($log_file, "Prepare Error: " . $conn->error . "\n", FILE_APPEND);
        }
    } else {
        $response['message'] = 'Missing required fields: schedule_name and report_type are required.';
        file_put_contents($log_file, "Result: Missing Fields\n", FILE_APPEND);
    }
}

$conn->close();
echo json_encode($response);
?>