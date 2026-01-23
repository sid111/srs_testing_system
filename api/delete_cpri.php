<?php
include '../config/conn.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo "Invalid request.";
    exit;
}

$stmt = $conn->prepare("DELETE FROM cpri_reports WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Submission deleted successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
