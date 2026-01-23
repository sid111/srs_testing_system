<?php
include '../config/conn.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo "Invalid request.";
    exit;
}

// Use JOIN to get product name
$stmt = $conn->prepare("
    SELECT c.*, p.name AS product_name
    FROM cpri_reports c
    LEFT JOIN products p ON c.product_id = p.product_id
    WHERE c.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo '<p><strong>Product ID:</strong> ' . htmlspecialchars($row['product_id']) . '</p>';
    echo '<p><strong>Product Name:</strong> ' . htmlspecialchars($row['product_name'] ?? 'Unknown Product') . '</p>';
    echo '<p><strong>Submission Date:</strong> ' . $row['submission_date'] . '</p>';
    echo '<p><strong>CPRI Reference:</strong> ' . ($row['cpri_reference'] ?: 'N/A') . '</p>';
    echo '<p><strong>Test Date:</strong> ' . ($row['test_date'] ?: 'N/A') . '</p>';
    echo '<p><strong>Status:</strong> ' . ucfirst($row['status']) . '</p>';
} else {
    echo "Submission not found.";
}

$stmt->close();
$conn->close();
