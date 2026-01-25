<?php
include '../config/conn.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo "<p style='color:red;'>Invalid request.</p>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM cpri_reports WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo "<p style='color:red;'>Submission not found.</p>";
    exit;
}

$product_id       = htmlspecialchars($row['product_id']);
$product_name     = htmlspecialchars($row['product_name']);
$submission_date  = $row['submission_date'];
$test_date        = $row['test_date'] ?: 'N/A';
$status           = ucfirst($row['status']);
$cpri_reference   = htmlspecialchars($row['cpri_reference']);
$certificate_image = $row['certificate_image'];
?>

<div class="view-cpri-container">
    <p><strong>Product ID:</strong> <?= $product_id ?></p>
    <p><strong>Product Name:</strong> <?= $product_name ?></p>
    <p><strong>Submission Date:</strong> <?= $submission_date ?></p>
    <p><strong>CPRI Reference:</strong> <?= $cpri_reference ?></p>
    <p><strong>Test Date:</strong> <?= $test_date ?></p>
    <p><strong>Status:</strong> <?= $status ?></p>

    <?php if ($certificate_image && file_exists("../" . $certificate_image)): ?>
        <div class="certificate-preview-wrapper">
            <img src="/srs/<?= htmlspecialchars($certificate_image) ?>" class="certificate-preview" alt="Certificate Image">
        </div>
        <a href="/srs/<?= htmlspecialchars($certificate_image) ?>" download class="btn btn-secondary" style="margin-top:10px;">Download Certificate</a>
    <?php else: ?>
        <p>No certificate uploaded.</p>
    <?php endif; ?>
</div>

<style>
    .view-cpri-container {
        padding: 15px;
        font-size: 0.95rem;
    }

    .certificate-preview-wrapper {
        text-align: center;
        margin-top: 10px;
    }

    .certificate-preview {
        max-width: 100%;
        max-height: 400px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>