<?php
include 'dashboard_auth.php';
include 'config/conn.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPRI Testing | SRS Electrical Appliances</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/cpri.css">
    <style>
        /* Modal adjustments */
        .modal {
            max-width: 600px;
            width: 90%;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .modal-body {
            margin-top: 10px;
        }

        /* Form styling */
        .edit-cpri-form .form-group,
        .submit-cpri-form .form-group {
            margin-bottom: 12px;
        }

        .edit-cpri-form label,
        .submit-cpri-form label {
            display: block;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .edit-cpri-form input,
        .edit-cpri-form select,
        .submit-cpri-form input,
        .submit-cpri-form select {
            width: 100%;
            padding: 6px 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .certificate-preview {
            max-width: 100%;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <!-- Header & Navigation -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="index.php" class="logo"> <i class="fas fa-bolt logo-icon"></i> <span class="logo-text">SRS Electrical</span> </a>
                <div class="mobile-toggle" id="mobileToggle"> <i class="fas fa-bars"></i> </div>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li class="dropdown">
                        <a href="lab-testing.php" class="active">Lab Testing <i class="fas fa-chevron-down"></i></a>
                        <div class="dropdown-content">
                            <a href="report.php">Reports</a>
                            <a href="cpri.php">CPRI Testing</a>
                        </div>
                    </li>
                    <li><a href="product.php">Product Catalog</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <h1 class="page-title">CPRI Testing & Certification</h1>
            <p class="page-subtitle">Central Power Research Institute Approved Testing Services for Electrical Equipment</p>
        </div>
    </header>

    <div class="container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1>CPRI Certified Testing Laboratory</h1>
                <p>SRS Electrical Appliances is a CPRI approved testing facility offering comprehensive testing and certification services for electrical equipment as per Indian and international standards.</p>
            </div>
        </section>

        <!-- CPRI Submission Table -->
        <section>
            <div class="table-container">
                <div class="table-header">
                    <h3>CPRI Test Submissions</h3>
                    <div class="table-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="cpriSearch" placeholder="Search CPRI submissions...">
                        </div>
                        <button class="btn btn-primary" id="submitToCPRIBtn">
                            <i class="fas fa-paper-plane"></i>
                            Submit to CPRI
                        </button>
                    </div>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Submission Date</th>
                            <th>CPRI Reference</th>
                            <th>Test Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cpriTableBody">
                        <?php
                        $result = $conn->query("
                            SELECT c.*, p.name AS product_name
                            FROM cpri_reports c
                            LEFT JOIN products p ON c.product_id = p.product_id
                            ORDER BY c.id DESC
                        ");
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $product_id = htmlspecialchars($row['product_id'] ?? '');
                                $product_name = htmlspecialchars($row['product_name'] ?? '');
                                $submission_date = $row['submission_date'] ?? '';
                                $cpri_reference = $row['cpri_reference'] ?? 'N/A';
                                $test_date = $row['test_date'] ?? 'N/A';
                                $status = $row['status'] ?? 'pending';

                                $statusClass = $status === 'approved' ? 'status-approved' : ($status === 'rejected' ? 'status-failed' : 'status-pending');

                                echo "<tr>
                                    <td>{$product_id}</td>
                                    <td>{$product_name}</td>
                                    <td>{$submission_date}</td>
                                    <td>{$cpri_reference}</td>
                                    <td>{$test_date}</td>
                                    <td><span class='status-badge {$statusClass}'>" . ucfirst($status) . "</span></td>
                                    <td>
                                        <div class='action-buttons'>
                                            <button class='btn-icon btn-view' title='View Certificate' onclick='viewCertificate({$row['id']})'>
                                                <i class='fas fa-eye'></i>
                                            </button>
                                            <button class='btn-icon btn-edit' title='Edit Record' onclick='editCertificate({$row['id']})'>
                                                <i class='fas fa-edit'></i>
                                            </button>
                                            <button class='btn-icon btn-delete' title='Delete Submission' onclick='deleteCertificate({$row['id']})'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo '<tr><td colspan="7">No CPRI submissions found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Modal Overlay (Single, used for View/Edit/Submit) -->
        <div class="modal-overlay" id="certificateModal">
            <div class="modal">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalTitle">Certificate</h3>
                    <button class="modal-close" onclick="closeCertificateModal()">&times;</button>
                </div>
                <div class="modal-body" id="certificateContent">
                    <!-- Content loaded dynamically -->
                </div>
            </div>
        </div>

        <?php include 'assets/footer.php'; ?>

        <script>
            // Close modal
            function closeCertificateModal() {
                document.getElementById('certificateModal').style.display = 'none';
                document.getElementById('certificateContent').innerHTML = '';
            }

            // ------------------ VIEW CERTIFICATE ------------------
            function viewCertificate(id) {
                fetch('api/view_cpri.php?id=' + id)
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('modalTitle').innerText = 'View Certificate';
                        document.getElementById('certificateContent').innerHTML = html;
                        document.getElementById('certificateModal').style.display = 'flex';
                    });
            }

            // ------------------ EDIT CERTIFICATE ------------------
            function editCertificate(id) {
                fetch('api/edit_cpri.php?id=' + id)
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('modalTitle').innerText = 'Edit CPRI Record';
                        document.getElementById('certificateContent').innerHTML = html;
                        document.getElementById('certificateModal').style.display = 'flex';
                    });
            }

            // ------------------ SUBMIT NEW CPRI ------------------
            document.getElementById('submitToCPRIBtn').addEventListener('click', function() {
                document.getElementById('modalTitle').innerText = 'Submit New Product to CPRI';
                document.getElementById('certificateContent').innerHTML = `
                    <form id="submitCpriForm" class="submit-cpri-form">
                        <div class="form-group">
                            <label>Product ID</label>
                            <input type="text" name="product_id" required>
                        </div>
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="product_name" required>
                        </div>
                        <div class="form-group">
                            <label>Submission Date</label>
                            <input type="date" name="submission_date" required>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" onclick="closeCertificateModal()">Cancel</button>
                        </div>
                    </form>
                `;
                document.getElementById('certificateModal').style.display = 'flex';

                document.getElementById('submitCpriForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch('api/add_cpri.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(resp => {
                            alert(resp.message);
                            if (resp.status === 'success') closeCertificateModal();
                        });
                });
            });

            // Delete submission
            function deleteCertificate(id) {
                if (confirm('Are you sure you want to delete this submission?')) {
                    fetch('api/delete_cpri.php?id=' + id)
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