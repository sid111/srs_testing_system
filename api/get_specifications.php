<?php
header('Content-Type: application/json');
include("../config/conn.php");

try {
    $query = "SELECT DISTINCT spec_label FROM product_specs ORDER BY spec_label ASC";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $spec_labels = [];
    while ($row = $result->fetch_assoc()) {
        $spec_labels[] = $row['spec_label'];
    }
    
    echo json_encode([
        'success' => true,
        'spec_labels' => $spec_labels
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>