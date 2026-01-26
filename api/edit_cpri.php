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
                <div class="image-preview" id="certificatePreview" style="width: 200px; max-height: 150px; overflow: hidden; border: 1px solid #ccc; border-radius: 4px; cursor: pointer;">
                    <img src="/srs/<?= htmlspecialchars($certificate_image) ?>" alt="Certificate Image" style="width: 100%; height: auto; display: block;">
                </div>
            <?php else: ?>
                <div class="image-preview" id="certificatePreview" style="width: 200px; max-height: 150px; overflow: hidden; border: 1px solid #ccc; border-radius: 4px; cursor: pointer;">
                    <p style="text-align:center; color:#777; font-size:0.9rem;">No certificate uploaded.</p>
                </div>
            <?php endif; ?>
            <input type="file" name="certificate_image" class="filter-select" accept="image/*" id="certificateInput">
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeCertificateModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Record</button>
    </div>
</form>

<!-- Fullscreen Image Modal -->
<div id="imageModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.8); justify-content:center; align-items:center; z-index:1000; cursor:pointer;">
    <img id="modalImg" src="" alt="Certificate Fullscreen" style="max-width:90%; max-height:90%; border-radius:6px; box-shadow:0 4px 20px rgba(0,0,0,0.5);">
</div>

<script>
    // Submit form
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

    // Certificate live preview & fullscreen
    const certificateInput = document.getElementById('certificateInput');
    const certificatePreview = document.getElementById('certificatePreview');
    const imageModal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImg');

    // Live preview on file select
    certificateInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                certificatePreview.innerHTML = `<img src="${e.target.result}" alt="Certificate Preview" style="width: 100%; height: auto; display: block;">`;
            }
            reader.readAsDataURL(file);
        } else {
            certificatePreview.innerHTML = '<p style="text-align:center; color:#777; font-size:0.9rem;">No certificate uploaded.</p>';
        }
    });

    // Open fullscreen modal on click
    certificatePreview.addEventListener('click', function() {
        const img = certificatePreview.querySelector('img');
        if (img) {
            modalImg.src = img.src;
            imageModal.style.display = 'flex';
        }
    });

    // Close fullscreen modal
    imageModal.addEventListener('click', function() {
        imageModal.style.display = 'none';
        modalImg.src = '';
    });
</script>