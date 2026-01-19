<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.html");
    exit();
}

include("../config/conn.php");

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
    $category = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $voltage_rating = mysqli_real_escape_string($conn, trim($_POST['voltage_rating'] ?? ''));
    $certification = mysqli_real_escape_string($conn, trim($_POST['certification'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $badge = mysqli_real_escape_string($conn, trim($_POST['badge'] ?? ''));
    $featured = isset($_POST['featured']) ? 1 : 0;

    // Validation
    if (empty($name)) {
        $error = 'Product name is required';
    } elseif (empty($category)) {
        $error = 'Category is required';
    } elseif (empty($voltage_rating)) {
        $error = 'Voltage rating is required';
    } elseif (empty($certification)) {
        $error = 'Certification is required';
    } elseif (empty($description)) {
        $error = 'Description is required';
    } elseif ($price <= 0) {
        $error = 'Price must be greater than 0';
    } elseif ($stock < 0) {
        $error = 'Stock cannot be negative';
    } else {
        // Generate product ID
        $lastProductQuery = "SELECT product_id FROM products ORDER BY product_id DESC LIMIT 1";
        $lastResult = $conn->query($lastProductQuery);

        if ($lastResult->num_rows > 0) {
            $lastRow = $lastResult->fetch_assoc();
            $lastId = $lastRow['product_id'];
            $number = intval(substr($lastId, 4)) + 1;
            $product_id = 'PRD-' . str_pad($number, 3, '0', STR_PAD_LEFT);
        } else {
            $product_id = 'PRD-001';
        }

        // Insert product
        $insertQuery = "INSERT INTO products (product_id, name, category, voltage_rating, certification, description, price, stock, badge, featured) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssssssddsi", $product_id, $name, $category, $voltage_rating, $certification, $description, $price, $stock, $badge, $featured);

        if ($stmt->execute()) {
            $success = "Product added successfully! Product ID: $product_id";

            // Add specs if provided
            $specsJson = $_POST['specs_json'] ?? '';
            if (!empty($specsJson)) {
                $specs = json_decode($specsJson, true);
                foreach ($specs as $label => $value) {
                    if (!empty($label) && !empty($value)) {
                        $specQuery = "INSERT INTO product_specs (product_id, spec_label, spec_value) VALUES (?, ?, ?)";
                        $specStmt = $conn->prepare($specQuery);
                        $specStmt->bind_param("sss", $product_id, $label, $value);
                        $specStmt->execute();
                        $specStmt->close();
                    }
                }
            }

            // Reset form
            $_POST = array();
        } else {
            $error = 'Error adding product: ' . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | SRS Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-blue: #1a5f7a;
            --accent-blue: #2a86ba;
            --light-blue: #57c5e6;
            --dark-gray: #333;
            --medium-gray: #666;
            --light-gray: #f8f9fa;
            --white: #ffffff;
            --border-color: #e0e0e0;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
            --success-green: #28a745;
            --danger-red: #dc3545;
        }

        body {
            line-height: 1.6;
            color: var(--dark-gray);
            background-color: var(--light-gray);
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .header h1 {
            color: var(--primary-blue);
            font-size: 2rem;
        }

        .back-link {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
        }

        .back-link:hover {
            color: var(--primary-blue);
            transform: translateX(-5px);
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-green);
            border: 1px solid var(--success-green);
        }

        .alert-error {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-red);
            border: 1px solid var(--danger-red);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-row.full {
            grid-template-columns: 1fr;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-gray);
        }

        .required {
            color: var(--danger-red);
        }

        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.95rem;
            transition: var(--transition);
            font-family: inherit;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(42, 134, 186, 0.2);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .section-title {
            font-size: 1.3rem;
            color: var(--primary-blue);
            margin-top: 30px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-blue);
        }

        .specs-container {
            background-color: var(--light-gray);
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .spec-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 10px;
            margin-bottom: 15px;
            align-items: flex-end;
        }

        .spec-row input {
            margin-bottom: 0;
        }

        .spec-row button {
            padding: 12px 15px;
            background-color: var(--danger-red);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
        }

        .spec-row button:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .add-spec-btn {
            background-color: var(--accent-blue);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .add-spec-btn:hover {
            background-color: var(--primary-blue);
            transform: translateY(-2px);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
        }

        .btn-primary {
            background-color: var(--accent-blue);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-blue);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(42, 134, 186, 0.3);
        }

        .btn-secondary {
            background-color: var(--medium-gray);
            color: white;
        }

        .btn-secondary:hover {
            background-color: var(--dark-gray);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .spec-row {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-plus-circle"></i> Add New Product</h1>
            <a href="../dashboard.html" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>

        <div class="form-container">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" id="productForm">
                <div class="section-title">Basic Information</div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Product Name <span class="required">*</span></label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Price (₹) <span class="required">*</span></label>
                        <input type="number" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Category <span class="required">*</span></label>
                        <select name="category" required>
                            <option value="">Select Category</option>
                            <option value="switchgear" <?php echo ($_POST['category'] ?? '') === 'switchgear' ? 'selected' : ''; ?>>Switchgear</option>
                            <option value="transformers" <?php echo ($_POST['category'] ?? '') === 'transformers' ? 'selected' : ''; ?>>Transformers</option>
                            <option value="testing" <?php echo ($_POST['category'] ?? '') === 'testing' ? 'selected' : ''; ?>>Testing Equipment</option>
                            <option value="panels" <?php echo ($_POST['category'] ?? '') === 'panels' ? 'selected' : ''; ?>>Control Panels</option>
                            <option value="cables" <?php echo ($_POST['category'] ?? '') === 'cables' ? 'selected' : ''; ?>>Cables & Accessories</option>
                            <option value="safety" <?php echo ($_POST['category'] ?? '') === 'safety' ? 'selected' : ''; ?>>Safety Equipment</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Stock <span class="required">*</span></label>
                        <input type="number" name="stock" min="0" value="<?php echo htmlspecialchars($_POST['stock'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Voltage Rating <span class="required">*</span></label>
                        <select name="voltage_rating" required>
                            <option value="">Select Voltage</option>
                            <option value="lv" <?php echo ($_POST['voltage_rating'] ?? '') === 'lv' ? 'selected' : ''; ?>>Low Voltage (≤1kV)</option>
                            <option value="mv" <?php echo ($_POST['voltage_rating'] ?? '') === 'mv' ? 'selected' : ''; ?>>Medium Voltage (1kV-33kV)</option>
                            <option value="hv" <?php echo ($_POST['voltage_rating'] ?? '') === 'hv' ? 'selected' : ''; ?>>High Voltage (>33kV)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Certification <span class="required">*</span></label>
                        <select name="certification" required>
                            <option value="">Select Certification</option>
                            <option value="cpri" <?php echo ($_POST['certification'] ?? '') === 'cpri' ? 'selected' : ''; ?>>CPRI Certified</option>
                            <option value="iso" <?php echo ($_POST['certification'] ?? '') === 'iso' ? 'selected' : ''; ?>>ISO Certified</option>
                            <option value="iec" <?php echo ($_POST['certification'] ?? '') === 'iec' ? 'selected' : ''; ?>>IEC Compliant</option>
                        </select>
                    </div>
                </div>

                <div class="form-group form-row full">
                    <div>
                        <label>Description <span class="required">*</span></label>
                        <textarea name="description" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="section-title">Display Options</div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Badge</label>
                        <select name="badge">
                            <option value="">No Badge</option>
                            <option value="new" <?php echo ($_POST['badge'] ?? '') === 'new' ? 'selected' : ''; ?>>New</option>
                            <option value="popular" <?php echo ($_POST['badge'] ?? '') === 'popular' ? 'selected' : ''; ?>>Popular</option>
                            <option value="cpri" <?php echo ($_POST['badge'] ?? '') === 'cpri' ? 'selected' : ''; ?>>CPRI</option>
                            <option value="limited" <?php echo ($_POST['badge'] ?? '') === 'limited' ? 'selected' : ''; ?>>Limited</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="checkbox-group">
                            <input type="checkbox" id="featured" name="featured" <?php echo (isset($_POST['featured']) && $_POST['featured']) ? 'checked' : ''; ?>>
                            <label for="featured" style="margin-bottom: 0;">Featured Product</label>
                        </div>
                    </div>
                </div>

                <div class="section-title">Technical Specifications</div>

                <div class="specs-container" id="specsContainer">
                    <div class="spec-row">
                        <input type="text" placeholder="Specification Label (e.g., Voltage Rating)" class="spec-label">
                        <input type="text" placeholder="Value (e.g., 33kV)" class="spec-value">
                        <button type="button" class="remove-spec" style="display: none;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <button type="button" class="add-spec-btn" onclick="addSpec()">
                    <i class="fas fa-plus"></i>
                    Add Specification
                </button>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Add Product
                    </button>
                    <a href="../dashboard.html" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addSpec() {
            const container = document.getElementById('specsContainer');
            const newRow = document.createElement('div');
            newRow.className = 'spec-row';
            newRow.innerHTML = `
                <input type="text" placeholder="Specification Label" class="spec-label">
                <input type="text" placeholder="Value" class="spec-value">
                <button type="button" class="remove-spec" onclick="this.parentElement.remove();">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(newRow);

            // Show remove buttons if more than one spec
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.spec-row');
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector('.remove-spec');
                removeBtn.style.display = rows.length > 1 ? 'block' : 'none';
            });
        }

        document.getElementById('productForm').addEventListener('submit', function(e) {
            const specs = {};
            const specRows = document.querySelectorAll('.spec-row');

            specRows.forEach(row => {
                const label = row.querySelector('.spec-label').value.trim();
                const value = row.querySelector('.spec-value').value.trim();
                if (label && value) {
                    specs[label] = value;
                }
            });

            // Add hidden input with specs JSON
            let specsInput = document.querySelector('input[name="specs_json"]');
            if (!specsInput) {
                specsInput = document.createElement('input');
                specsInput.type = 'hidden';
                specsInput.name = 'specs_json';
                this.appendChild(specsInput);
            }
            specsInput.value = JSON.stringify(specs);
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateRemoveButtons();
        });
    </script>
</body>

</html>