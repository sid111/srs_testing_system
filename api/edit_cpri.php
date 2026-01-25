<?php
include '../config/conn.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo '<p style="color:red;">Invalid request.</p>';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM cpri_reports WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo '<p style="color:red;">CPRI record not found.</p>';
    exit;
}

// Pre-fill values
$product_id       = htmlspecialchars($row['product_id']);
$product_name     = htmlspecialchars($row['product_name']);
$submission_date  = $row['submission_date'];
$test_date        = $row['test_date'] ?: '';
$status           = $row['status'];
$cpri_reference   = htmlspecialchars($row['cpri_reference']);
$certificate_image = $row['certificate_image'];
?>

<form id="editCpriForm" class="edit-cpri-form" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $row['id'] ?>">

    <div class="form-group">
        <label>Product ID</label>
        <input type="text" name="product_id" value="<?= $product_id ?>" required>
    </div>

    <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="product_name" value="<?= $product_name ?>" required>
    </div>

    <div class="form-group">
        <label>Submission Date</label>
        <input type="date" name="submission_date" value="<?= $submission_date ?>" required>
    </div>

    <div class="form-group">
        <label>CPRI Reference</label>
        <input type="text" name="cpri_reference" value="<?= $cpri_reference ?>">
    </div>

    <div class="form-group">
        <label>Test Date</label>
        <input type="date" name="test_date" value="<?= $test_date ?>">
    </div>

    <div class="form-group">
        <label>Status</label>
        <select name="status">
            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
    </div>

    <div class="form-group">
        <label>Certificate Image</label>
        <?php if ($certificate_image && file_exists("../$certificate_image")): ?>
            <div>
                <img src="/srs/<?= htmlspecialchars($certificate_image) ?>" class="certificate-preview" alt="Certificate Image">
            </div>
            <p>Current file: <?= basename($certificate_image) ?></p>
        <?php else: ?>
            <p>No certificate uploaded.</p>
        <?php endif; ?>
        <input type="file" name="certificate_image" accept="image/*">
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Record</button>
        <button type="button" class="btn btn-secondary" onclick="closeCertificateModal()">Cancel</button>
    </div>
</form>

<script>
    document.getElementById('editCpriForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('api/update_cpri.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(resp => {
                alert(resp.message);
                if (resp.status === 'success') closeCertificateModal();
            })
            .catch(err => {
                console.error(err);
                alert('Error updating CPRI record.');
            });
    });
</script>