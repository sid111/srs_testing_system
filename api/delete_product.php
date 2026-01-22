<?php
session_start();
include("../config/conn.php");
header("Content-Type: application/json");

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$product_id = $_POST['product_id'] ?? '';

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Product ID missing']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
$stmt->bind_param("s", $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $stmt->error]);
}

$stmt->close();
$conn->close();
