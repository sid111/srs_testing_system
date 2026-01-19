<?php
// api/get_testers.php
header('Content-Type: application/json');
require_once '../config/conn.php';

$sql = "SELECT tester_id, tester_name FROM testers ORDER BY tester_name ASC";
$result = $conn->query($sql);
$testers = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $testers[] = $row;
    }
}
echo json_encode(['success' => true, 'testers' => $testers]);
?>
