<?php
session_start();
include("../config/conn.php");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$id = $_POST['id'] ?? null;
if (!$id) {
    echo json_encode(["status" => "error", "message" => "CPRI record ID is missing."]);
    exit;
}

$fields = [];
$params = [];
$types = '';

// Dynamically build the query based on posted data
if (isset($_POST['product_id'])) {
    $fields[] = "product_id = ?";
    $params[] = $_POST['product_id'];
    $types .= 's';
}
if (isset($_POST['product_name'])) {
    $fields[] = "product_name = ?";
    $params[] = $_POST['product_name'];
    $types .= 's';
}
if (isset($_POST['submission_date'])) {
    $fields[] = "submission_date = ?";
    $params[] = $_POST['submission_date'];
    $types .= 's';
}
if (isset($_POST['cpri_reference'])) {
    $fields[] = "cpri_reference = ?";
    $params[] = $_POST['cpri_reference'];
    $types .= 's';
}
if (isset($_POST['test_date'])) {
    $fields[] = "test_date = ?";
    $params[] = $_POST['test_date'];
    $types .= 's';
}
if (isset($_POST['status'])) {
    $fields[] = "status = ?";
    $params[] = $_POST['status'];
    $types .= 's';
}

// Handle file upload
if (isset($_FILES['certificate_image']) && $_FILES['certificate_image']['error'] == 0) {
    $target_dir = "../uploads/cpri/";
    $image_name = "cpri_" . uniqid() . '.' . pathinfo($_FILES["certificate_image"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . $image_name;

    // Attempt to move the uploaded file
    if (move_uploaded_file($_FILES["certificate_image"]["tmp_name"], $target_file)) {
        $fields[] = "certificate_image = ?";
        $params[] = "uploads/cpri/" . $image_name;
        $types .= 's';
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to upload certificate image."]);
        exit;
    }
}

if (empty($fields)) {
    echo json_encode(["status" => "error", "message" => "No fields to update."]);
    exit;
}

$sql = "UPDATE cpri_reports SET " . implode(', ', $fields) . " WHERE id = ?";
$params[] = $id;
$types .= 'i';

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "SQL Statement preparation failed: " . $conn->error]);
    exit;
}

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "CPRI record updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>