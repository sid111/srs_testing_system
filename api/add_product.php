<?php
header('Content-Type: application/json');
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized. Please login first.'
    ]);
    exit();
}

include("../config/conn.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit();
}

try {
    $name = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
    $category = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $voltage_rating = mysqli_real_escape_string($conn, trim($_POST['voltage_rating'] ?? ''));
    $certification = mysqli_real_escape_string($conn, trim($_POST['certification'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $badge = mysqli_real_escape_string($conn, trim($_POST['badge'] ?? ''));
    $image = '';

    // Validation
    if (empty($name)) {
        throw new Exception('Product name is required');
    } elseif (empty($category)) {
        throw new Exception('Category is required');
    } elseif (empty($voltage_rating)) {
        throw new Exception('Voltage rating is required');
    } elseif (empty($certification)) {
        throw new Exception('Certification is required');
    } elseif (empty($description)) {
        throw new Exception('Description is required');
    } elseif ($price <= 0) {
        throw new Exception('Price must be greater than 0');
    } elseif ($stock < 0) {
        throw new Exception('Stock cannot be negative');
    }

    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['product_image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        // Validate file type
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.');
        }

        // Validate file size
        if ($file['size'] > $max_size) {
            throw new Exception('File size exceeds 5MB limit.');
        }

        // Create uploads directory if it doesn't exist
        $upload_dir = '../uploads/products/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $unique_name = 'product_' . time() . '_' . uniqid() . '.' . $file_ext;
        $upload_path = $upload_dir . $unique_name;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            $image = 'uploads/products/' . $unique_name;
        } else {
            throw new Exception('Failed to upload image. Please try again.');
        }
    }

    // Generate product ID
    $lastProductQuery = "SELECT product_id FROM products ORDER BY product_id DESC LIMIT 1";
    $lastResult = $conn->query($lastProductQuery);

    if ($lastResult && $lastResult->num_rows > 0) {
        $lastRow = $lastResult->fetch_assoc();
        $lastId = $lastRow['product_id'];
        $number = intval(substr($lastId, 4)) + 1;
        $product_id = 'PRD-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    } else {
        $product_id = 'PRD-001';
    }

    // Insert product
    $insertQuery = "INSERT INTO products (product_id, name, category, voltage_rating, certification, description, price, stock, badge, featured, image) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    $featured = 0;
    $stmt->bind_param("ssssssdssis", $product_id, $name, $category, $voltage_rating, $certification, $description, $price, $stock, $badge, $featured, $image);
        
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => "Product added successfully! Product ID: " . $product_id,
            'product_id' => $product_id,
            'image_path' => $image
        ]);
    } else {
        // Delete uploaded image if database insert fails
        if (!empty($image) && file_exists('../' . $image)) {
            unlink('../' . $image);
        }
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    $stmt->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
