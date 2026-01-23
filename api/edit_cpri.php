<?php
include '../config/conn.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    echo "Invalid request.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM cpri_reports WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "Submission not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpri_reference = $_POST['cpri_reference'] ?? '';
    $test_date = $_POST['test_date'] ?? '';
    $status = $_POST['status'] ?? 'pending';

    $update = $conn->prepare("UPDATE cpri_reports SET cpri_reference = ?, test_date = ?, status = ? WHERE id = ?");
    $update->bind_param("sssi", $cpri_reference, $test_date, $status, $id);

    if ($update->execute()) {
        echo "Certificate updated successfully!";
    } else {
        echo "Error: " . $update->error;
    }
    $update->close();
    $conn->close();
    exit;
}

// Show edit form
?>
<form id="editCertificateForm" method="POST">
    <label>CPRI Reference</label>
    <input type="text" name="cpri_reference" value="<?php echo htmlspecialchars($row['cpri_reference']); ?>">
    <label>Test Date</label>
    <input type="date" name="test_date" value="<?php echo $row['test_date']; ?>">
    <label>Status</label>
    <select name="status">
        <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
        <option value="approved" <?php if($row['status']=='approved') echo 'selected'; ?>>Approved</option>
        <option value="rejected" <?php if($row['status']=='rejected') echo 'selected'; ?>>Rejected</option>
    </select>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

<script>
document.getElementById('editCertificateForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);
    fetch('edit_cpri_certificate.php?id=<?php echo $id; ?>', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(msg => {
            alert(msg);
            location.reload();
        });
});
</script>
