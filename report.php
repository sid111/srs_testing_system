<?php
include "includes/db.php";
include "includes/header.php";

$where = "1";
if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $where = "
        product_id LIKE '%$search%' OR 
        testing_id LIKE '%$search%' OR 
        tested_by LIKE '%$search%' OR 
        result LIKE '%$search%'
    ";
}

$data = $conn->query("SELECT * FROM testing_records WHERE $where ORDER BY created_at DESC");
?>

<div class="container">
    <h2>Testing Reports & Advanced Search</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Search Product ID / Test ID / Result / Tester">
        <button class="btn btn-primary">Search</button>
    </form>

    <br>

    <table border="1" width="100%" cellpadding="10">
        <tr>
            <th>Testing ID</th>
            <th>Product ID</th>
            <th>Product Type</th>
            <th>Test Type</th>
            <th>Result</th>
            <th>Tested By</th>
            <th>Date</th>
        </tr>

        <?php while ($row = $data->fetch_assoc()): ?>
        <tr>
            <td><?= $row['testing_id'] ?></td>
            <td><?= $row['product_id'] ?></td>
            <td><?= $row['product_type'] ?></td>
            <td><?= $row['test_type'] ?></td>
            <td style="color:<?= $row['result']=='PASS'?'green':'red' ?>">
                <?= $row['result'] ?>
            </td>
            <td><?= $row['tested_by'] ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include "includes/footer.php"; ?>
