<?php
include "includes/db.php";
include "includes/header.php";

$products = $conn->query("
    SELECT product_id, product_type, 
    MAX(result) as status 
    FROM testing_records 
    GROUP BY product_id
");
?>

<div class="container">
    <h2>Product Catalog & Testing Status</h2>

    <table border="1" width="100%" cellpadding="10">
        <tr>
            <th>Product ID</th>
            <th>Product Type</th>
            <th>Testing Status</th>
            <th>Action</th>
        </tr>

        <?php while ($row = $products->fetch_assoc()): ?>
        <tr>
            <td><?= $row['product_id'] ?></td>
            <td><?= $row['product_type'] ?></td>
            <td style="color:<?= $row['status']=='PASS'?'green':'red' ?>">
                <?= $row['status'] ?>
            </td>
            <td>
                <?= $row['status']=='PASS'
                    ? 'Send to CPRI'
                    : 'Re-manufacture'
                ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include "includes/footer.php"; ?>
