<?php
header('Content-Type: application/json');
include("../config/conn.php");

try {
    $query = "SELECT stat_date, total_tests, passed_tests, failed_tests, 
              total_products_tested, cpri_approvals, pass_rate 
              FROM dashboard_stats 
              ORDER BY stat_date DESC 
              LIMIT 1";

    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $stats = $result->fetch_assoc();

    if (!$stats) {
        $stats = [
            'total_tests' => 0,
            'passed_tests' => 0,
            'failed_tests' => 0,
            'total_products_tested' => 0,
            'cpri_approvals' => 0,
            'pass_rate' => 0
        ];
    }

    echo json_encode([
        'success' => true,
        'stats' => $stats
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
$conn->close();
