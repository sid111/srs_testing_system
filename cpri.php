<?php
include 'dashboard_auth.php';
include 'config/conn.php'; // Make sure this connects to your database
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPRI Testing | SRS Electrical Appliances</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/cpri.css">
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
                            $sql = "SELECT cpri_reports.*, products.name AS product_name 
                            FROM cpri_reports LEFT JOIN products ON cpri_reports.product_id = products.id 
                            ORDER BY cpri_reports.id DESC";
                            $result = $conn->query("SELECT c.*, p.name AS product_name 
                            FROM cpri_reports c LEFT JOIN products p 
                            ON c.product_id = p.product_id ORDER BY c.id DESC");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $product_id = $row['product_id'] ?? '';
                                    $product_name = htmlspecialchars($row['product_name'] ?? '');
                                    $submission_date = $row['submission_date'] ?? '';
                                    $cpri_reference = $row['cpri_reference'] ?? 'N/A';
                                    $test_date = $row['test_date'] ?? 'N/A';
                                    $status = $row['status'] ?? 'pending';

                                    $statusClass = '';
                                    switch ($status) {
                                        case 'approved':
                                            $statusClass = 'status-approved';
                                            break;
                                        case 'pending':
                                            $statusClass = 'status-pending';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'status-failed';
                                            break;
                                    }

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
                                                <button class='btn-icon btn-edit' title='Upload/Edit Certificate' onclick='editCertificate({$row['id']})'>
                                                    <i class='fas fa-upload'></i>
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

            <!-- CPRI Approval Status Cards -->
            <section id="cpri-status-section">
                <h2 class="section-title">CPRI Approval Status</h2>
                <?php
                $result = $conn->query("
                SELECT c.*, p.name AS product_name
                FROM cpri_reports c
                LEFT JOIN products p ON c.product_id = p.product_id
                ORDER BY c.id DESC");
                if ($result->num_rows > 0) {
                    while ($submission = $result->fetch_assoc()) {
                        $product_name = htmlspecialchars($submission['product_name'] ?? '');
                        $submission_date = $submission['submission_date'] ?? '';
                        $product_id = $submission['product_id'] ?? '';
                        $cpri_reference = $submission['cpri_reference'] ?? 'N/A';
                        $test_date = $submission['test_date'] ?? 'N/A';
                        $status = $submission['status'] ?? 'pending';

                        $cardClass = '';
                        $statusBadge = '';
                        switch ($status) {
                            case 'approved':
                                $cardClass = 'approved';
                                $statusBadge = '<span class="status-badge status-approved">Approved</span>';
                                break;
                            case 'pending':
                                $cardClass = 'pending';
                                $statusBadge = '<span class="status-badge status-pending">Pending</span>';
                                break;
                            case 'rejected':
                                $cardClass = 'rejected';
                                $statusBadge = '<span class="status-badge status-failed">Rejected</span>';
                                break;
                        }

                        echo "<div class='card cpri-card {$cardClass}'>
                            <div class='card-header'>
                                <div>
                                    <div class='card-title'>{$product_name}</div>
                                    <div style='color: var(--medium-gray); font-size: 0.95rem; margin-top: 5px;'>
                                        Submitted: {$submission_date}
                                    </div>
                                </div>
                                {$statusBadge}
                            </div>
                            <div class='cpri-details-grid'>
                                <div class='detail-item'><span class='detail-label'>Product ID</span><span class='detail-value'>{$product_id}</span></div>
                                <div class='detail-item'><span class='detail-label'>CPRI Reference</span><span class='detail-value'>{$cpri_reference}</span></div>
                                <div class='detail-item'><span class='detail-label'>Test Date</span><span class='detail-value'>{$test_date}</span></div>
                            </div>
                        </div>";
                    }
                }
                ?>
            </section>

            <!-- Modal Overlay -->
            <div class="modal-overlay" id="certificateModal">
                <div class="modal">
                    <div class="modal-header">
                        <h3 class="modal-title">Certificate</h3>
                        <button class="modal-close" onclick="closeCertificateModal()">&times;</button>
                    </div>
                    <div class="modal-body" id="certificateContent">
                        <!-- Content loaded dynamically -->
                    </div>
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

            // View certificate
            function viewCertificate(id) {
                fetch('api/view_cpri.php?id=' + id)
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('certificateContent').innerHTML = html;
                        document.getElementById('certificateModal').style.display = 'flex';
                    });
            }

            // Edit certificate
            function editCertificate(id) {
                fetch('api/edit_cpri.php?id=' + id)
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('certificateContent').innerHTML = html;
                        document.getElementById('certificateModal').style.display = 'flex';
                    });
            }

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

            // Submit to CPRI modal
            document.getElementById('submitToCPRIBtn').addEventListener('click', function() {
                document.getElementById('certificateContent').innerHTML = `
                    <h4>Submit New Product to CPRI</h4>
                    <form id="submitCpriForm">
                        <label>Product ID</label>
                        <input type="text" name="product_id" required>
                        <label>Product Name</label>
                        <input type="text" name="product_name" required>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                `;
                document.getElementById('certificateModal').style.display = 'flex';

                document.getElementById('submitCpriForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    fetch('api/submit_cpri.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.text())
                        .then(msg => {
                            alert(msg);
                            location.reload();
                        });
                });
            });
        </script>

        <script src="assets/cpri.js"></script>
</body>

</html>