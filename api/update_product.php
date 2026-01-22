<?php
session_start();
header("Content-Type: application/json");
include("../config/conn.php");

// Check admin session
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Collect POST data
$product_id    = trim($_POST['product_id'] ?? '');
$name          = trim($_POST['name'] ?? '');
$category      = trim($_POST['category'] ?? '');
$voltage       = trim($_POST['voltage_rating'] ?? '');
$certification = trim($_POST['certification'] ?? '');
$description   = trim($_POST['description'] ?? '');
$price         = floatval($_POST['price'] ?? 0);
$stock         = intval($_POST['stock'] ?? 0);
$badge         = trim($_POST['badge'] ?? '');
$featured      = isset($_POST['featured']) ? intval($_POST['featured']) : 0;

// Decode specs JSON
$specs = json_decode($_POST['specs'] ?? '[]', true);

// Required fields check
if (!$product_id || !$name) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

/* Fetch old image */
$oldImg = '';
$stmt = $conn->prepare("SELECT image FROM products WHERE product_id=?");
$stmt->bind_param("s", $product_id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) $oldImg = $row['image'];
$stmt->close();

/* Handle image upload */
$newImg = $oldImg;
if (!empty($_FILES['product_image']['name'])) {
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $ext = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Invalid image type']);
        exit;
    }
    if ($_FILES['product_image']['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Image too large']);
        exit;
    }

    $dir = "../uploads/products/";
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $fname = 'product_' . time() . '_' . uniqid() . '.' . $ext;
    $path  = $dir . $fname;

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $path)) {
        $newImg = 'uploads/products/' . $fname;
        if ($oldImg && file_exists("../" . $oldImg)) unlink("../" . $oldImg);
    } else {
        echo json_encode(['success' => false, 'message' => 'Image upload failed']);
        exit;
    }
}

/* Normalize category & voltage (optional, ensure lowercase in DB) */
$category = strtolower($category);
$voltage  = strtolower($voltage);

/* Update product */
$stmt = $conn->prepare("UPDATE products SET name=?, category=?, voltage_rating=?, certification=?, description=?, price=?, stock=?, badge=?, image=?, featured=? WHERE product_id=?");
$stmt->bind_param("sssssdissis", $name, $category, $voltage, $certification, $description, $price, $stock, $badge, $newImg, $featured, $product_id);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Product update failed']);
    exit;
}
$stmt->close();

/* Update specs */
if (is_array($specs)) {
    // Delete old specs
    $stmt = $conn->prepare("DELETE FROM product_specs WHERE product_id=?");
    $stmt->bind_param("s", $product_id);
    $stmt->execute();
    $stmt->close();

    // Insert new specs
    $stmt = $conn->prepare("INSERT INTO product_specs (product_id,spec_label,spec_value) VALUES (?,?,?)");
    foreach ($specs as $s) {
        $label = trim($s['label'] ?? '');
        $value = trim($s['value'] ?? '');
        if ($label && $value) {
            $stmt->bind_param("sss", $product_id, $label, $value);
            $stmt->execute();
        }
    }
    $stmt->close();
}

$conn->close();
echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
