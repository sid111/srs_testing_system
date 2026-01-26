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
    <div class="product-specs">
        <div class="spec-item">
            <span class="spec-label">Product ID:</span>
            <span class="spec-value"><?= $product_id ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Product Name:</span>
            <span class="spec-value"><?= $product_name ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Submission Date:</span>
            <span class="spec-value"><?= $submission_date ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">CPRI Reference:</span>
            <span class="spec-value"><?= $cpri_reference ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Test Date:</span>
            <span class="spec-value"><?= $test_date ?></span>
        </div>
        <div class="spec-item">
            <span class="spec-label">Status:</span>
            <span class="spec-value"><?= $status ?></span>
        </div>
    </div>

    <?php if ($certificate_image && file_exists("../" . $certificate_image)): ?>
        <div class="certificate-preview-wrapper" style="text-align: center; margin-top: 20px;">
            <img src="/srs/<?= htmlspecialchars($certificate_image) ?>" style="max-width: 100%; max-height: 600px; border-radius: 5px;" alt="Certificate Image">
        </div>
        <div style="text-align: center; margin-top: 15px;">
            <a href="/srs/<?= htmlspecialchars($certificate_image) ?>" download class="btn btn-primary">Download Certificate</a>
        </div>
    <?php else: ?>
        <p style="text-align: center; margin-top: 20px; color: var(--medium-gray);">No certificate uploaded.</p>
    <?php endif; ?>
</div>