<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.html");
    exit();
}

include 'db.php'; // Make sure this has your DB connection

// Fetch all CPRI reports
$query = "SELECT * FROM cpri_reports ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPRI Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #f4f4f4;
        }

        button {
            padding: 5px 10px;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 90%;
            max-height: 90%;
            overflow: auto;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
        }

        img,
        iframe {
            max-width: 100%;
            max-height: 80vh;
            display: block;
            margin: auto;
        }
    </style>
</head>

<body>

    <h2>CPRI Reports</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Test Date</th>
                <th>Status</th>
                <th>Certificate</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                    <td><?= $row['test_date'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <?php if ($row['certificate_pdf'] || $row['image']): ?>
                            <i class="fa fa-file-pdf"></i>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td>
                        <button onclick="viewCertificate(<?= $row['id'] ?>)">View</button>
                        <button onclick="editCertificate(<?= $row['id'] ?>)">Edit</button>
                        <button onclick="deleteCertificate(<?= $row['id'] ?>)">Delete</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Modal for View/Edit -->
    <div id="certificateModal" class="modal">
        <div class="modal-content" id="modalBody">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div id="certificateContent"></div>
        </div>
    </div>

    <script>
        function closeModal() {
            document.getElementById('certificateModal').style.display = 'none';
            document.getElementById('certificateContent').innerHTML = '';
        }

        function viewCertificate(id) {
            fetch('cpri.php?action=view&id=' + id)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('certificateContent').innerHTML = html;
                    document.getElementById('certificateModal').style.display = 'flex';
                });
        }

        function editCertificate(id) {
            fetch('cpri.php?action=edit&id=' + id)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('certificateContent').innerHTML = html;
                    document.getElementById('certificateModal').style.display = 'flex';
                });
        }

        function deleteCertificate(id) {
            if (confirm("Are you sure you want to delete this report?")) {
                fetch('cpri.php?action=delete&id=' + id)
                    .then(res => res.text())
                    .then(msg => {
                        alert(msg);
                        location.reload();
                    });
            }
        }
    </script>

</body>

</html>