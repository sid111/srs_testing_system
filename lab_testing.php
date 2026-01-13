<?php
require_once "includes/db.php";
include "includes/header.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_id   = $_POST['product_id'];
    $product_type = $_POST['product_type'];
    $test_type    = $_POST['test_type'];
    $result       = $_POST['result'];
    $remarks      = $_POST['remarks'];
    $tested_by    = $_POST['tested_by'];

    // Auto-generate 12 digit testing ID
    $testing_id = substr($product_id, 0, 6) . rand(100000, 999999);

    $stmt = $conn->prepare("
        INSERT INTO testing_records 
        (testing_id, product_id, product_type, test_type, result, remarks, tested_by)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssssss",
        $testing_id,
        $product_id,
        $product_type,
        $test_type,
        $result,
        $remarks,
        $tested_by
    );

    if ($stmt->execute()) {
        $message = "✅ Testing record saved successfully. Testing ID: $testing_id";
    } else {
        $message = "❌ Error saving record.";
    }
}
?>

<div class="container">
    <h2>Lab Testing Entry</h2>

    <?php if ($message): ?>
        <p><strong><?= $message ?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <label>Product ID (10 digits)</label>
        <input type="text" name="product_id" required>

        <label>Product Type</label>
        <select name="product_type" required>
            <option>Switchgear</option>
            <option>Fuse</option>
            <option>Capacitor</option>
            <option>Resistor</option>
        </select>

        <label>Testing Type</label>
        <select name="test_type" required>
            <option>Voltage Test</option>
            <option>Current Test</option>
            <option>Insulation Test</option>
            <option>Load Test</option>
        </select>

        <label>Result</label>
        <select name="result" required>
            <option value="PASS">PASS</option>
            <option value="FAIL">FAIL</option>
        </select>

        <label>Remarks</label>
        <textarea name="remarks" rows="4"></textarea>

        <label>Tested By</label>
        <input type="text" name="tested_by" required>

        <br><br>
        <button class="btn btn-primary">Submit Test</button>
    </form>
</div>

<?php include "includes/footer.php"; ?>
