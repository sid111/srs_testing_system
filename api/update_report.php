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

    $id     = $_POST['id'] ?? null;
    $name   = $_POST['name'] ?? '';
    $rtype  = $_POST['report_type'] ?? '';
    $format = $_POST['format'] ?? '';
    $status = $_POST['status'] ?? '';

    if (!$id || !$name || !$rtype || !$format || !$status) {
        echo json_encode(["success" => false, "message" => "Missing fields"]);
        exit;
    }

    $stmt = $conn->prepare("
        UPDATE generated_reports 
        SET report_name=?, report_type=?, format=?, status=?
        WHERE report_id=?
    ");
    $stmt->bind_param("ssssi", $name, $rtype, $format, $status, $id);

    /* ======================================
   EDIT SCHEDULED REPORT
====================================== */
} elseif ($type === 'scheduled') {

    $id       = $_POST['id'] ?? null;
    $name     = $_POST['name'] ?? '';
    $freq     = $_POST['frequency'] ?? '';
    $next_run = $_POST['next_run'] ?? '';
    $status   = $_POST['status'] ?? '';
    $rtype    = $_POST['report_type'] ?? '';

    if (!$id || !$name || !$freq || !$next_run || !$status || !$rtype) {
        echo json_encode(["success" => false, "message" => "Missing fields"]);
        exit;
    }

    $stmt = $conn->prepare("
        UPDATE scheduled_reports
        SET schedule_name=?, frequency=?, next_run=?, status=?, report_type=?
        WHERE schedule_id=?
    ");
    $stmt->bind_param("sssssi", $name, $freq, $next_run, $status, $rtype, $id);
} else {
    echo json_encode(["success" => false, "message" => "Invalid edit type"]);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
