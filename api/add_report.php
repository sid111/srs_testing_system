<?php
header('Content-Type: application/json');
include("../config/conn.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name'], $data['type'], $data['format'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO generated_reports (name, type, date_generated, format, status, size, created_by) VALUES (?, ?, ?, ?, 'processing', '', ?)");
    $today = date('Y-m-d');
    $created_by = 1; // Adjust if you have session/user ID
    $stmt->bind_param("ssssi", $data['name'], $data['type'], $today, $data['format'], $created_by);
    $stmt->execute();

    echo json_encode([
        'success' => true,
        'report_id' => $stmt->insert_id
    ]);

    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
