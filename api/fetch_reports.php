<?php
header('Content-Type: application/json');
include("../config/conn.php");

try {
    $query = "SELECT * FROM generated_reports ORDER BY date_generated DESC, id DESC";
    $result = $conn->query($query);

    $reports = [];
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }

    echo json_encode([
        'success' => true,
        'reports' => $reports
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>
