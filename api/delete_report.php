<?php
// api/delete_report.php
header('Content-Type: application/json');
include("../config/conn.php");

$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = $_POST['report_id'] ?? null;

    if (!$report_id) {
        $response['message'] = 'Report ID is required.';
    } else {
        // Prepare delete statement
        $stmt = $conn->prepare("DELETE FROM generated_reports WHERE report_id = ?");
        $stmt->bind_param("i", $report_id);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Report deleted successfully.';
        } else {
            $response['message'] = 'Failed to delete report: ' . $stmt->error;
        }

        $stmt->close();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();
echo json_encode($response);
