<?php
header('Content-Type: application/json');
include("../config/conn.php");

$data = json_decode(file_get_contents("php://input"), true);

try {
    if (isset($data['id']) && !empty($data['id'])) {
        // Update schedule
        $stmt = $conn->prepare("UPDATE scheduled_reports SET name=?, report_type=?, frequency=?, next_run=?, email_recipients=? WHERE id=?");
        $stmt->bind_param("sssssi", $data['name'], $data['report_type'], $data['frequency'], $data['next_run'], $data['email_recipients'], $data['id']);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true, 'message' => 'Schedule updated']);
    } else {
        // Add new schedule
        $stmt = $conn->prepare("INSERT INTO scheduled_reports (name, report_type, frequency, next_run, email_recipients) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $data['name'], $data['report_type'], $data['frequency'], $data['next_run'], $data['email_recipients']);
        $stmt->execute();
        echo json_encode(['success' => true, 'schedule_id' => $stmt->insert_id]);
        $stmt->close();
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
