<?php
header('Content-Type: application/json');
include("../config/conn.php");

try {
    // Get total tests
    $total_tests_result = $conn->query("SELECT COUNT(*) as total_tests FROM test_results");
    $total_tests = $total_tests_result->fetch_assoc()['total_tests'];

    // Get passed tests
    $passed_tests_result = $conn->query("SELECT COUNT(*) as passed_tests FROM test_results WHERE result = 'Pass'");
    $passed_tests = $passed_tests_result->fetch_assoc()['passed_tests'];

    // Get failed tests
    $failed_tests = $total_tests - $passed_tests;

    // Get total products tested
    $total_products_tested_result = $conn->query("SELECT COUNT(DISTINCT product_id) as total_products_tested FROM test_results");
    $total_products_tested = $total_products_tested_result->fetch_assoc()['total_products_tested'];

    // Get CPRI approvals
    $cpri_test_id_result = $conn->query("SELECT test_id FROM tests WHERE test_name = 'Insulation Test'");
    $cpri_test_id = $cpri_test_id_result->fetch_assoc()['test_id'];
    $cpri_approvals_result = $conn->query("SELECT COUNT(*) as cpri_approvals FROM test_results WHERE test_id = $cpri_test_id AND result = 'Pass'");
    $cpri_approvals = $cpri_approvals_result->fetch_assoc()['cpri_approvals'];

    // Calculate pass rate
    $pass_rate = ($total_tests > 0) ? ($passed_tests / $total_tests) * 100 : 0;

    $stats = [
        'total_tests' => $total_tests,
        'passed_tests' => $passed_tests,
        'failed_tests' => $failed_tests,
        'total_products_tested' => $total_products_tested,
        'cpri_approvals' => $cpri_approvals,
        'pass_rate' => round($pass_rate, 2)
    ];

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
?>
