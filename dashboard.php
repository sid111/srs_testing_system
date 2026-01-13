<?php
include "includes/db.php";
include "includes/header.php";

$total   = $conn->query("SELECT COUNT(*) total FROM testing_records")->fetch_assoc()['total'];
$passed  = $conn->query("SELECT COUNT(*) total FROM testing_records WHERE result='PASS'")->fetch_assoc()['total'];
$failed  = $conn->query("SELECT COUNT(*) total FROM testing_records WHERE result='FAIL'")->fetch_assoc()['total'];
?>

<div class="container">
    <h2>Testing Dashboard</h2>

    <div class="products-grid">
        <div class="product-card">
            <h3>Total Tests</h3>
            <p><?= $total ?></p>
        </div>

        <div class="product-card">
            <h3>Passed</h3>
            <p style="color:green"><?= $passed ?></p>
        </div>

        <div class="product-card">
            <h3>Failed</h3>
            <p style="color:red"><?= $failed ?></p>
        </div>
    </div>

    <br>
    <p>
        ✔ Failed products → Re-manufacturing  
        ✔ Passed products → CPRI testing
    </p>
</div>

<?php include "includes/footer.php"; ?>
