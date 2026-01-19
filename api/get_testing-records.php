<?php
// api/get_testing-records.php
header('Content-Type: application/json');
require_once '../config/conn.php';

// tester_id does not exist in testing_records, so remove the join to testers
$sql = "SELECT tr.test_id, tr.product_id, tr.test_date, tr.test_type, tr.status, tr.result, tr.notes, tr.tester_name, p.name AS product_name
        FROM testing_records tr
        LEFT JOIN products p ON tr.product_id = p.product_id
        ORDER BY tr.test_date DESC, tr.test_id DESC";

$result = $conn->query($sql);
$records = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}
echo json_encode(['success' => true, 'records' => $records]);
?>
