<?php
session_start();
include("../config/conn.php");
header("Content-Type: application/json");

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$type = $_POST['edit_type'] ?? '';

/* ======================================
   EDIT GENERATED REPORT
====================================== */
if ($type === 'generated') {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        echo json_encode(["success" => false, "message" => "Missing report ID"]);
        exit;
    }

    $updates = [];
    $params = [];
    $types = "";

    if (!empty($_POST['name'])) {
        $updates[] = "report_name = ?";
        $params[] = $_POST['name'];
        $types .= "s";
    }
    if (!empty($_POST['report_type'])) {
        $updates[] = "report_type = ?";
        $params[] = $_POST['report_type'];
        $types .= "s";
    }
    if (!empty($_POST['format'])) {
        $updates[] = "format = ?";
        $params[] = $_POST['format'];
        $types .= "s";
    }
    if (!empty($_POST['status'])) {
        $updates[] = "status = ?";
        $params[] = $_POST['status'];
        $types .= "s";
    }
    if (isset($_POST['generated_by'])) {
        $updates[] = "generated_by = ?";
        $params[] = $_POST['generated_by'] === '' ? null : $_POST['generated_by'];
        $types .= "i";
    }

    if (empty($updates)) {
        echo json_encode(["success" => false, "message" => "No fields to update."]);
        exit;
    }

    $sql = "UPDATE generated_reports SET " . implode(", ", $updates) . " WHERE report_id = ?";
    $params[] = $id;
    $types .= "i";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

} elseif ($type === 'scheduled') {

    $id = $_POST['id'] ?? null;
    if (!$id) {
        echo json_encode(["success" => false, "message" => "Missing schedule ID"]);
        exit;
    }

    $updates = [];
    $params = [];
    $types = "";

    if (!empty($_POST['name'])) {
        $updates[] = "schedule_name = ?";
        $params[] = $_POST['name'];
        $types .= "s";
    }
    if (!empty($_POST['frequency'])) {
        $updates[] = "frequency = ?";
        $params[] = $_POST['frequency'];
        $types .= "s";
    }
    if (!empty($_POST['next_run'])) {
        $updates[] = "next_run = ?";
        $params[] = $_POST['next_run'];
        $types .= "s";
    }
    if (!empty($_POST['status'])) {
        $updates[] = "status = ?";
        $params[] = $_POST['status'];
        $types .= "s";
    }
    if (!empty($_POST['report_type'])) {
        $updates[] = "report_type = ?";
        $params[] = $_POST['report_type'];
        $types .= "s";
    }
    
    if (empty($updates)) {
        echo json_encode(["success" => false, "message" => "No fields to update."]);
        exit;
    }

    $sql = "UPDATE scheduled_reports SET " . implode(", ", $updates) . " WHERE schedule_id = ?";
    $params[] = $id;
    $types .= "i";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);


} else {
    echo json_encode(["success" => false, "message" => "Invalid edit type"]);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
