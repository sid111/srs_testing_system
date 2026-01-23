<?php
include '../config/conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'] ?? '';
    $product_name = $_POST['product_name'] ?? '';

    if (!$product_id || !$product_name) {
        echo "All fields are required.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO cpri_reports (product_id, product_name, submission_date, status) VALUES (?, ?, NOW(), 'pending')");
    $stmt->bind_param("ss", $product_id, $product_name);

    if ($stmt->execute()) {
        echo "Submission successful! Product sent to CPRI.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
