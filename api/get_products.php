<?php
header('Content-Type: application/json');
include("../config/conn.php");

try {
    $query = "SELECT p.product_id, p.name, p.category, p.voltage_rating, p.certification, 
              p.description, p.price, p.stock, p.badge, p.featured 
              FROM products p 
              ORDER BY p.name ASC";
    
    $result = $conn->query($query);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $row['name'] = ucwords($row['name']);
        // Get specs for this product
        $specQuery = "SELECT spec_label, spec_value FROM product_specs WHERE product_id = ?";
        $specStmt = $conn->prepare($specQuery);
        $specStmt->bind_param("s", $row['product_id']);
        $specStmt->execute();
        $specResult = $specStmt->get_result();
        
        $specs = [];
        while ($spec = $specResult->fetch_assoc()) {
            $specs[$spec['spec_label']] = $spec['spec_value'];
        }
        
        $row['specs'] = $specs;
        $row['voltage'] = $row['voltage_rating'];
        $products[] = $row;
        $specStmt->close();
    }
    
    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
$conn->close();
?>