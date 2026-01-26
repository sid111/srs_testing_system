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
    <div class="form-grid">
        <div class="form-group">
            <label class="filter-label">Product ID</label>
            <input type="text" name="product_id" class="filter-select" value="<?= $product_id ?>" required>
        </div>

        <div class="form-group">
            <label class="filter-label">Product Name</label>
            <input type="text" name="product_name" class="filter-select" value="<?= $product_name ?>" required>
        </div>

        <div class="form-group">
            <label class="filter-label">Submission Date</label>
            <input type="date" name="submission_date" class="filter-select" value="<?= $submission_date ?>" required>
        </div>

        <div class="form-group">
            <label class="filter-label">CPRI Reference</label>
            <input type="text" name="cpri_reference" class="filter-select" value="<?= $cpri_reference ?>">
        </div>

        <div class="form-group">
            <label class="filter-label">Test Date</label>
            <input type="date" name="test_date" class="filter-select" value="<?= $test_date ?>">
        </div>

        <div class="form-group">
            <label class="filter-label">Status</label>
            <select name="status" class="filter-select">
                <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
                <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>

        <div class="form-group form-group-full">
            <label class="filter-label">Certificate Image</label>
            <?php if ($certificate_image && file_exists("../$certificate_image")): ?>
                <div class="image-preview" style="width: 200px;">
                    <img src="/srs/<?= htmlspecialchars($certificate_image) ?>" alt="Certificate Image">
                </div>
            <?php else: ?>
                <p>No certificate uploaded.</p>
            <?php endif; ?>
            <input type="file" name="certificate_image" class="filter-select" accept="image/*">
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeCertificateModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Record</button>
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
                if (resp.status === 'success') {
                    closeCertificateModal();
                    location.reload();
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error updating CPRI record.');
            });
    });
</script>