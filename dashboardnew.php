<?php include 'dashboard_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | SRS Electrical Appliances</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    /* Reset and Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    :root {
      --primary-blue: #1a5f7a;
      --accent-blue: #2a86ba;
      --light-blue: #57c5e6;
      --dark-gray: #333;
      --medium-gray: #666;
      --light-gray: #f8f9fa;
      --white: #ffffff;
      --border-color: #e0e0e0;
      --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      --transition: all 0.3s ease;
      --success-green: #28a745;
      --danger-red: #dc3545;
    }

    body {
      line-height: 1.6;
      color: var(--dark-gray);
      background-color: var(--light-gray);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Utility Classes */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .muted {
      color: var(--medium-gray);
    }

    .tiny {
      font-size: 0.875rem;
    }

    /* Page Header */
    .page-header {
      background: linear-gradient(135deg, var(--light-gray), var(--white));
      color: var(--primary-blue);
      padding: 40px 0;

      text-align: center;
    }

    .page-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .page-subtitle {
      font-size: 1.125rem;
      opacity: 0.9;
    }

    /* Dashboard Layout */
    .dashboard-root {
      display: flex;
      flex: 1;
      min-height: calc(100vh - 200px);
      /* Adjust based on header/footer */
    }

    /* Sidebar */
    .sidebar {
      width: 280px;
      padding-top: 100px;
      background: var(--white);
      border-right: 1px solid var(--border-color);
      padding: 20px;
      display: flex;
      flex-direction: column;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
      transition: var(--transition);
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--primary-blue);
      text-decoration: none;
      margin-bottom: 30px;
    }

    .brand i {
      font-size: 1.5rem;
    }

    .sidebar-section {
      margin-bottom: 30px;
    }

    .quick-actions .qa-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-top: 10px;
    }

    .qa-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 5px;
      padding: 15px 10px;
      background: var(--white);
      color: var(--primary-blue);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      cursor: pointer;
      transition: var(--transition);
      font-size: 0.875rem;
      text-align: center;
    }

    .qa-btn:hover {
      background: var(--light-gray);
      color: var(--light-blue);
    }

    .nav-list {
      list-style: none;
      margin-top: 10px;
    }

    .nav-list li {
      margin-bottom: 5px;
    }

    .nav-list a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 12px;
      border-radius: 8px;
      color: var(--primary-blue);
      text-decoration: none;
      transition: var(--transition);
    }

    .nav-list a:hover {
      background: var(--light-gray);
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 20px;
      overflow-x: auto;
    }

    .section-title {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: var(--primary-blue);
    }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }

    .stat-card {
      background: var(--white);
      border-radius: 12px;
      padding: 20px;
      box-shadow: var(--shadow);
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      transition: var(--transition);
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
      font-size: 2rem;
      margin-bottom: 15px;
      color: var(--accent-blue);
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary-blue);
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 0.875rem;
      color: var(--medium-gray);
      margin-bottom: 10px;
    }

    .stat-trend {
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 0.75rem;
    }

    .trend-up {
      color: var(--success-green);
    }

    .trend-down {
      color: var(--danger-red);
    }

    /* Content Grid */
    .content-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 20px;
    }

    /* Cards */
    .card {
      background: var(--white);
      border-radius: 12px;
      padding: 20px;
      box-shadow: var(--shadow);
    }

    .card h3 {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 15px;
      color: var(--primary-blue);
    }

    /* Forms */
    .form {
      display: none;
    }

    .form.active {
      display: block;
    }

    .row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }

    .full {
      grid-column: 1 / -1;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: var(--dark-gray);
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid var(--border-color);
      border-radius: 6px;
      font-size: 1rem;
      transition: var(--transition);
    }

    input:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: var(--accent-blue);
      box-shadow: 0 0 0 3px rgba(42, 134, 186, 0.1);
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    .actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
    }

    /* Buttons */
    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 500;
      transition: var(--transition);
    }

    .btn-primary {
      background: var(--accent-blue);
      color: var(--white);
    }

    .btn-primary:hover {
      background: var(--primary-blue);
    }

    .btn-outline {
      background: transparent;
      color: var(--medium-gray);
      border: 1px solid var(--border-color);
    }

    .btn-outline:hover {
      background: var(--light-gray);
    }

    /* Chart Wrap */
    .chart-wrap {
      text-align: center;
    }

    /* KPIs */
    .kpis {
      display: flex;
      justify-content: space-around;
      margin-top: 15px;
    }

    .kpi {
      text-align: center;
    }

    .kpi .value {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-blue);
    }

    .kpi .label {
      font-size: 0.75rem;
      color: var(--medium-gray);
    }

    /* Recent Activity */
    ul li {
      padding: 5px 0;
      border-bottom: 1px solid var(--border-color);
    }

    ul li:last-child {
      border-bottom: none;
    }

    /* Footer */
    footer {
      background-color: #2c3e50;
      color: var(--white);
      padding: 60px 0 30px;

    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 40px;
      margin-bottom: 50px;
    }

    .footer-column h3 {
      font-size: 1.3rem;
      margin-bottom: 25px;
      color: var(--light-blue);
      position: relative;
      padding-bottom: 10px;
    }

    .footer-column h3:after {
      content: '';
      position: absolute;
      width: 40px;
      height: 2px;
      background-color: var(--accent-blue);
      bottom: 0;
      left: 0;
    }

    .footer-column p,
    .footer-column a {
      color: #bdc3c7;
      margin-bottom: 15px;
      display: block;
      text-decoration: none;
      transition: var(--transition);
    }

    .footer-column a:hover {
      color: var(--light-blue);
      padding-left: 5px;
    }

    .contact-info {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .contact-item {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .contact-item i {
      color: var(--accent-blue);
      width: 20px;
    }

    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }

    .social-icon {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      color: var(--white);
      text-decoration: none;
      transition: var(--transition);
    }

    /* Footer social icons fix */
    .social-links a,
    .social-icons a,
    .footer-social a {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .social-links a i,
    .social-icons a i,
    .footer-social a i {
      line-height: 1;
    }


    .social-icon:hover {
      background-color: var(--accent-blue);
      transform: translateY(-5px);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 30px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: #95a5a6;
      font-size: 0.9rem;
    }

    /* Responsive Design */

    /* Tablet */
    @media (max-width: 1024px) {
      .sidebar {
        width: 250px;
      }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .content-grid {
        grid-template-columns: 1fr;
      }

      .page-title {
        font-size: 2rem;
      }

      .page-subtitle {
        font-size: 1rem;
      }
    }

    /* Mobile */
    @media (max-width: 768px) {
      .dashboard-root {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        height: auto;
        position: static;
        border-right: none;
        border-bottom: 1px solid var(--border-color);
        padding: 15px;
      }

      .sidebar.open {
        display: block;
      }

      .sidebar:not(.open) {
        display: none;
      }

      .quick-actions .qa-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .row {
        grid-template-columns: 1fr;
      }

      .actions {
        flex-direction: column;
      }

      .btn {
        width: 100%;
      }

      .page-header {
        padding: 20px 0;
      }

      .page-title {
        font-size: 1.75rem;
      }

      .page-subtitle {
        font-size: 0.875rem;
      }

      .footer-content {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .contact-item {
        justify-content: center;
      }

      .social-links {
        justify-content: center;
      }

      .main {
        padding: 15px;
      }

      .container {
        padding: 0 15px;
      }
    }

    /* Small Mobile */
    @media (max-width: 480px) {
      .quick-actions .qa-grid {
        grid-template-columns: 1fr;
      }

      .kpis {
        flex-direction: column;
        gap: 10px;
      }

      .stat-card {
        padding: 15px;
      }

      .stat-value {
        font-size: 1.5rem;
      }

      .card {
        padding: 15px;
      }
    }
  </style>
</head>

<body>
  <div class="container_mt_5">


    <div class="dashboard-root">
      <!-- Sidebar -->
      <aside class="sidebar" id="sidebar" aria-label="Dashboard sidebar">
        <a class="brand" href="index.php" aria-label="SRS Electrical Pakistan home">
          <i class="fas fa-bolt" aria-hidden="true"></i>
          <span class="title">SRS Electrical PK</span>
        </a>

        <div class="sidebar-section">
          <div class="muted tiny">Quick Actions</div>
          <div class="quick-actions" role="toolbar" aria-label="Quick actions">
            <div class="qa-grid">
              <button class="qa-btn" data-action="start-test" type="button"><i class="fas fa-play-circle"
                  aria-hidden="true"></i><span style="font-size:0.9rem">Start Test</span></button>
              <button class="qa-btn" data-action="generate-report" type="button"><i class="fas fa-file-export"
                  aria-hidden="true"></i><span style="font-size:0.9rem">Generate</span></button>
              <button class="qa-btn" data-action="add-product" type="button"><i class="fas fa-plus-square"
                  aria-hidden="true"></i><span style="font-size:0.9rem">Add Product</span></button>
              <button class="qa-btn" data-action="cpri" type="button"><i class="fas fa-certificate"
                  aria-hidden="true"></i><span style="font-size:0.9rem">CPRI</span></button>
            </div>
          </div>
        </div>

        <div class="sidebar-section">
          <div class="muted tiny">Navigation</div>
          <ul class="nav-list" role="navigation" aria-label="Sidebar navigation">
            <li><a href="index.php"><i class="fas fa-home"></i> Home Page</a></li>
            <li><a href="cpri.php"><i class="fas fa-certificate"></i> CPRI Page</a></li>
            <li><a href="report.php"><i class="fas fa-file-alt"></i> Reports Page</a></li>
            <li><a href="product.php"><i class="fas fa-box-open"></i> Product Catalog</a></li>
          </ul>
        </div>

        <div class="sidebar-section">
          <a href="logout.php" class="nav-list" style="display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: var(--danger-red); font-weight: 700; text-decoration: none; transition: var(--transition); margin-top: auto;">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </aside>

      <!-- Page Header -  -->

      <!-- Main Content -->
      <div class="main">
        <header class="page-header" aria-hidden="false">
          <div class="container">
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Smart Testing Dashboard - Monitor tests, reports, and approvals in one place</p>
          </div>
        </header>
        <!-- Stats Cards Section -->
        <section>
          <h2 class="section-title">Testing Overview</h2>

          <div class="stats-grid">
            <div class="stat-card stat-card-1" aria-live="polite">
              <div class="stat-icon">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
              </div>
              <div class="stat-value">94.7%</div>
              <div class="stat-label">Overall Pass Rate</div>
              <div class="stat-trend trend-up">
                <i class="fas fa-arrow-up" aria-hidden="true"></i>
                <span>+2.3% from last month</span>
              </div>
            </div>

            <div class="stat-card stat-card-2">
              <div class="stat-icon">
                <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
              </div>
              <div class="stat-value">5.3%</div>
              <div class="stat-label">Failure Rate</div>
              <div class="stat-trend trend-down">
                <i class="fas fa-arrow-down" aria-hidden="true"></i>
                <span>-1.1% from last month</span>
              </div>
            </div>

            <div class="stat-card stat-card-3">
              <div class="stat-icon">
                <i class="fas fa-boxes" aria-hidden="true"></i>
              </div>
              <div class="stat-value">1,247</div>
              <div class="stat-label">Total Products Tested</div>
              <div class="stat-trend trend-up">
                <i class="fas fa-arrow-up" aria-hidden="true"></i>
                <span>+128 this month</span>
              </div>
            </div>

            <div class="stat-card stat-card-4">
              <div class="stat-icon">
                <i class="fas fa-certificate" aria-hidden="true"></i>
              </div>
              <div class="stat-value">312</div>
              <div class="stat-label">CPRI Approvals</div>
              <div class="stat-trend trend-up">
                <i class="fas fa-arrow-up" aria-hidden="true"></i>
                <span>+42 this quarter</span>
              </div>
            </div>
          </div>
        </section>

        <!-- Main -->



        <div class="content-grid">
          <section>
            <div class="card">
              <h3>Quick Action Form</h3>
              <p class="tiny muted">Click a Quick Action on the left to open the corresponding form here.</p>

              <div class="form-area" id="formArea">
                <!-- Start Test -->
                <form id="startTestForm" class="form" aria-hidden="true" novalidate>
                  <h4>Start New Test</h4>
                  <div class="row">
                    <div>
                      <label for="testId">Test ID</label>
                      <input id="testId" type="text" placeholder="Auto / Enter ID" />
                    </div>
                    <div>
                      <label for="clientName">Client Name</label>
                      <input id="clientName" type="text" placeholder="Company or Client" />
                    </div>
                    <div>
                      <label for="productSelect">Product</label>
                      <select id="productSelect">
                        <option>Switchgear - Model SWS-200</option>
                        <option>Capacitor - PF-50</option>
                        <option>Control Panel - CP-XL</option>
                        <option>Testing Equipment - TM-10</option>
                      </select>
                    </div>
                    <div>
                      <label for="testType">Test Type</label>
                      <select id="testType">
                        <option>High Voltage</option>
                        <option>Insulation Resistance</option>
                        <option>Functional / Automation</option>
                        <option>CPRI Compliance Check</option>
                      </select>
                    </div>

                    <div class="full">
                      <label for="notes">Notes / Remarks (Pakistan site info)</label>
                      <textarea id="notes" placeholder="Add site-specific notes or acceptance criteria"></textarea>
                    </div>

                    <div class="full actions">
                      <button type="button" class="btn btn-primary" id="startTestSubmit">Start Test</button>
                      <button type="reset" class="btn btn-outline">Reset</button>
                    </div>
                  </div>
                </form>

                <!-- Generate Report -->
                <form id="generateReportForm" class="form" aria-hidden="true" novalidate>
                  <h4>Generate Report</h4>
                  <div class="row">
                    <div>
                      <label for="reportTestId">Test ID</label>
                      <input id="reportTestId" type="text" placeholder="Enter Test ID" />
                    </div>
                    <div>
                      <label for="reportType">Report Type</label>
                      <select id="reportType">
                        <option>CPRI Certification Report</option>
                        <option>Internal Test Summary</option>
                        <option>Customer Acceptance Report</option>
                      </select>
                    </div>

                    <div class="full">
                      <label for="reportRemarks">Additional Remarks</label>
                      <textarea id="reportRemarks" placeholder="Any notes to include in report"></textarea>
                    </div>

                    <div class="full actions">
                      <button type="button" class="btn btn-primary" id="generateReportBtn">Generate & Preview</button>
                      <button type="button" class="btn btn-outline" id="downloadReportBtn">Download PDF</button>
                    </div>
                  </div>
                </form>

                <!-- Add Product -->
                <form id="addProductForm" class="form" aria-hidden="true" novalidate>
                  <h4>Add Product</h4>
                  <div class="row">
                    <div>
                      <label for="prodName">Product Name</label>
                      <input id="prodName" type="text" placeholder="e.g., Switchgear SWS-200" />
                    </div>
                    <div>
                      <label for="prodSku">SKU / Model</label>
                      <input id="prodSku" type="text" placeholder="SKU or Model" />
                    </div>
                    <div>
                      <label for="prodCategory">Category</label>
                      <select id="prodCategory">
                        <option>Switchgear</option>
                        <option>Capacitors</option>
                        <option>Control Panels</option>
                        <option>Testing Equipment</option>
                      </select>
                    </div>
                    <div>
                      <label for="prodCountry">Manufactured / Supplied From</label>
                      <input id="prodCountry" type="text" value="Pakistan" />
                    </div>

                    <div class="full">
                      <label for="prodDesc">Description</label>
                      <textarea id="prodDesc" placeholder="Short description & compliance details"></textarea>
                    </div>

                    <div class="full actions">
                      <button type="button" class="btn btn-primary" id="addProductBtn">Add Product</button>
                      <button type="reset" class="btn btn-outline">Reset</button>
                    </div>
                  </div>
                </form>

                <!-- CPRI -->
                <form id="cpriForm" class="form" aria-hidden="true" novalidate>
                  <h4>CPRI Submission / Record</h4>
                  <div class="row">
                    <div>
                      <label for="cpriRef">CPRI Ref. No</label>
                      <input id="cpriRef" type="text" placeholder="Enter CPRI reference (if any)" />
                    </div>
                    <div>
                      <label for="cpriTest">Related Test ID</label>
                      <input id="cpriTest" type="text" placeholder="Related Test ID" />
                    </div>
                    <div>
                      <label for="cpriResult">Result</label>
                      <select id="cpriResult">
                        <option>Passed</option>
                        <option>Conditional</option>
                        <option>Failed</option>
                      </select>
                    </div>
                    <div>
                      <label for="cpriDate">Date</label>
                      <input id="cpriDate" type="date" />
                    </div>

                    <div class="full">
                      <label for="cpriNotes">Notes / Certification Details</label>
                      <textarea id="cpriNotes" placeholder="Attach certification notes or sample remarks"></textarea>
                    </div>

                    <div class="full actions">
                      <button type="button" class="btn btn-primary" id="saveCpriBtn">Save CPRI Record</button>
                      <button type="reset" class="btn btn-outline">Reset</button>
                    </div>
                  </div>
                </form>

                <!-- Default info -->
                <div id="defaultInfo" class="form active" aria-hidden="false">
                  <h4>Welcome to SRS Electrical PK Dashboard</h4>
                  <p class="muted">Use Quick Actions on the left to start tests, generate reports, add products or manage
                    CPRI records. All data shown is frontend demo content for a Pakistani-based company.</p>



                </div>
              </div>
          </section>

          <!-- Right column: Charts & KPIs -->
          <aside>
            <div class="card chart-wrap" aria-label="Test progress">
              <h3>Test Progress</h3>
              <div style="position:relative; height:220px;">
                <canvas id="progressChart" aria-label="Progress chart" role="img"></canvas>
              </div>

              <div class="kpis" style="margin-top:10px;">
                <div class="kpi">
                  <div class="value" id="kpiPass">92%</div>
                  <div class="label">Tests Passed</div>
                </div>
                <div class="kpi">
                  <div class="value" id="kpiActive">48</div>
                  <div class="label">Active Tests</div>
                </div>
                <div class="kpi">
                  <div class="value" id="kpiTurn">24h</div>
                  <div class="label">Avg. Turnaround</div>
                </div>
              </div>
            </div>

            <div class="card" style="margin-top:12px;">
              <h3>Recent Activity (PK)</h3>
              <ul style="list-style:none; margin-top:8px; padding-left:0;">
                <li class="tiny"><strong>#T-1024</strong> — Switchgear HV test started (Karachi lab)</li>
                <li class="tiny"><strong>#T-1019</strong> — CPRI draft generated (Lahore)</li>
                <li class="tiny"><strong>#P-333</strong> — New product added: Capacitor PF-50</li>
              </ul>
            </div>
          </aside>
        </div>


        <!-- Dashboard Content Section -->
        <!-- Footer  -->
        <!-- Footer -->

      </div>
    </div>
  </div>
  <footer id="contact">
    <div class="container">
      <div class="footer-content">
        <!-- Column 1: Contact Info -->
        <div class="footer-column">
          <h3>Contact Us</h3>
          <div class="contact-info">
            <div class="contact-item">
              <i class="fas fa-phone"></i>
              <span>+92 300 1234567</span>
            </div>
            <div class="contact-item">
              <i class="fas fa-envelope"></i>
              <span>info@srselectrical.com</span>
            </div>
            <div class="contact-item">
              <i class="fas fa-map-marker-alt"></i>
              <span>SRS Electrical Appliances Plot No 45, Industrial Area<br>Korangi Industrial Area Karachi, Pakistan</span>
            </div>
          </div>
        </div>

        <!-- Column 2: Quick Links -->
        <div class="footer-column">
          <h3>Quick Links</h3>
          <a href="about.php">About Us</a>
          <a href="contact.php">Contact Us</a>
          <a href="cpri.php">CPRI Certification</a>
          <a href="faqs.php">FAQs</a>
          <a href="report.php">Testing Reports</a>
        </div>

        <!-- Column 3: Social Media -->
        <div class="footer-column">
          <h3>Connect With Us</h3>
          <p>SRS Electrical Appliances is a Pakistan based electrical testing and lab automation company providing certified testing and CPRI support.</p>

          <div class="social-links">
            <a href="https://www.facebook.com/" class="social-icon">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://pk.linkedin.com/" class="social-icon">
              <i class="fab fa-linkedin-in"></i>
            </a>
            <a href="https://www.whatsapp.com/" class="social-icon">
              <i class="fab fa-whatsapp"></i>
            </a>
            <a href="https://x.com/" class="social-icon">
              <i class="fab fa-twitter"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="footer-bottom">
        <p>&copy; © 2026 SRS Electrical Appliances. All Rights Reserved. Karachi, Pakistan. ISO compliant testing and certification support.</p>
      </div>
    </div>
  </footer>
  <script>
    /* Initialization wrapped in DOMContentLoaded to avoid null element errors */
    document.addEventListener('DOMContentLoaded', function() {
      // Elements
      const sidebar = document.getElementById('sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const qaButtons = document.querySelectorAll('.qa-btn');
      const forms = {
        'start-test': document.getElementById('startTestForm'),
        'generate-report': document.getElementById('generateReportForm'),
        'add-product': document.getElementById('addProductForm'),
        'cpri': document.getElementById('cpriForm')
      };
      const defaultInfo = document.getElementById('defaultInfo');

      // Helper to safely toggle forms
      function hideAllForms() {
        Object.values(forms).forEach(f => {
          if (f) {
            f.classList.remove('active');
            f.setAttribute('aria-hidden', 'true');
          }
        });
        if (defaultInfo) {
          defaultInfo.classList.remove('active');
          defaultInfo.setAttribute('aria-hidden', 'true');
        }
      }

      // Quick action click handlers
      if (qaButtons && qaButtons.length) {
        qaButtons.forEach(btn => {
          btn.addEventListener('click', function() {
            const action = btn.getAttribute('data-action');
            hideAllForms();
            if (forms[action]) {
              forms[action].classList.add('active');
              forms[action].setAttribute('aria-hidden', 'false');
            } else if (defaultInfo) {
              defaultInfo.classList.add('active');
              defaultInfo.setAttribute('aria-hidden', 'false');
            }
            // close sidebar on small screens
            if (window.innerWidth <= 768 && sidebar) {
              sidebar.classList.remove('open');
            }
          });
        });
      }

      // Show default info on load
      if (defaultInfo) {
        hideAllForms();
        defaultInfo.classList.add('active');
        defaultInfo.setAttribute('aria-hidden', 'false');
      }

      // Sidebar toggle (mobile)
      if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
          sidebar.classList.toggle('open');
        });
      }

      // Demo action handlers (safe guards if elements exist)
      function safeAddListener(id, cb) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', cb);
      }

      document.getElementById("generateReportBtn")?.addEventListener("click", async function(e) {
        e.preventDefault();

        const reportName = document.getElementById("reportTestId")?.value.trim();
        const reportType = document.getElementById("reportType")?.value;
        const remarks = document.getElementById("reportRemarks")?.value || "";

        if (!reportName) {
          alert("Please enter Test ID / Report reference.");
          return;
        }

        const formData = new FormData();
        formData.append("report_name", reportName);
        formData.append("report_type", reportType);
        formData.append("remarks", remarks);

        try {
          const res = await fetch("api/insert_report.php", {
            method: "POST",
            body: formData
          });

          if (!res.ok) {
            alert("Server error: " + res.status);
            return;
          }

          const data = await res.json();
          if (!data.success) {
            alert("⚠️ Error: " + data.message);
            if (data.stmt_error) console.error(data.stmt_error);
            return;
          }

          alert("✅ Report generated successfully!");
          window.open("api/view_report.php?id=" + data.report_id, "_blank");

        } catch (err) {
          console.error(err);
          alert("❌ Unexpected error occurred.");
        }
      });

      // Chart.js: only initialize when canvas exists
      const canvas = document.getElementById('progressChart');
      let progressChart = null;
      if (canvas) {
        const ctx = canvas.getContext('2d');
        let passPercent = 92;

        const doughnutData = {
          labels: ['Passed', 'Failed'],
          datasets: [{
            data: [passPercent, 100 - passPercent],
            backgroundColor: [getComputedStyle(document.documentElement).getPropertyValue('--light-blue').trim() || '#57c5e6', 'rgba(200,200,200,0.25)'],
            borderWidth: 0
          }]
        };

        progressChart = new Chart(ctx, {
          type: 'doughnut',
          data: doughnutData,
          options: {
            cutout: '70%',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    return context.label + ': ' + context.parsed + '%';
                  }
                }
              }
            }
          }
        });

        // Update function (demo)
        window.updateDoughnut = function() {
          if (!progressChart) return;
          passPercent = Math.max(70, Math.min(99, passPercent + (Math.random() * 4 - 1)));
          const p = Math.round(passPercent);
          progressChart.data.datasets[0].data = [p, 100 - p];
          progressChart.update();
          const kpiPass = document.getElementById('kpiPass');
          if (kpiPass) kpiPass.textContent = p + '%';
        };

        // auto update for demo
        setInterval(function() {
          try {
            window.updateDoughnut();
          } catch (e) {
            /* ignore */ }
        }, 5000);
      }

      // Close sidebar on resize for desktop
      window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && sidebar) sidebar.classList.remove('open');
      });

      // Smooth anchor behavior for left nav links that target local anchors
      document.querySelectorAll('.nav-list a').forEach(a => {
        a.addEventListener('click', function(e) {
          const href = a.getAttribute('href') || '';
          if (href && href.startsWith('#')) {
            e.preventDefault();
            const el = document.querySelector(href);
            if (el) window.scrollTo({
              top: el.offsetTop - 70,
              behavior: 'smooth'
            });
          }
        });
      });
    }); // DOMContentLoaded
  </script>
</body>

</html>