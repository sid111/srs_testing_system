<?php
header('Content-Type: application/json');
include("../config/conn.php");

try {
    $query = "SELECT * FROM scheduled_reports ORDER BY next_run ASC";
    $result = $conn->query($query);

    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }

    echo json_encode([
        'success' => true,
        'schedules' => $schedules
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
