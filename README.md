# SRS Electrical Appliances - Lab Automation & Electrical Testing

This project is a web-based Product Testing System for SRS Electrical Appliances, a company specializing in lab automation and electrical testing. The application is built with PHP and uses a MySQL database to manage products, testing procedures, and reporting.

## Overview

The SRS application provides a centralized platform for managing the entire lifecycle of electrical appliance testing. It includes a public-facing website with information about the company's services and a secure admin dashboard for managing the testing process.

## Features

- **Public Website:** A responsive website with information about the company, its products, and services.
- **Admin Dashboard:** A secure dashboard for administrators to manage the testing process.
- **Product Management:** Add, view, and manage the products to be tested.
- **Testing Workflow:** Initiate tests, track their status, and record results.
- **CPRI Testing:** Manage the submission of products for CPRI testing and certification.
- **Report Generation:** Generate and manage test reports in various formats (PDF, DOC, etc.).
- **User Management:** A registration and login system for administrators.

## Technologies Used

- **Backend:** PHP
- **Frontend:** HTML, CSS, JavaScript
- **Database:** MySQL
- **Other:** Chart.js (for data visualization in the dashboard)

## Setup and Installation

1.  **Prerequisites:**
    - A web server with PHP support (e.g., Apache, Nginx).
    - A MySQL database server.

2.  **Clone the repository:**

    ```bash
    git clone <repository-url>
    ```

3.  **Database Setup:**
    - Create a new database named `srp`.
    - Import the database schema from `database/srp.sql`.

4.  **Configuration:**
    - Update the database connection details in `config/conn.php` if they are different from the default:
      ```php
      <?php
      $conn = mysqli_connect("localhost", "root", "", "srp");
      if (!$conn) die("Database connection failed");
      ```
    - The login credentials in `config/login_handler.php` are also hardcoded to the same values.

5.  **Running the application:**
    - Place the project files in the web root of your server (e.g., `htdocs` for XAMPP).
    - Access the application through your web browser.

## Database

The database schema is located in `database/srp.sql`. The main tables are:

- `admin`: Stores administrator credentials.
- `products`: Stores information about the electrical products.
- `generated_reports`: Stores information about the generated test reports.
- `cpri_reports`: Stores information about CPRI submissions.
- `testers`: Stores information about the testers.

## API Endpoints

The `api/` directory contains a set of PHP scripts that serve as API endpoints for various actions, such as:

- `add_product.php`: Adds a new product.
- `add_report.php`: Adds a new report.
- `add_test_result.php`: Adds a new test result.
- `delete_product.php`: Deletes a product.
- `get_products.php`: Retrieves a list of products.
- `update_product.php`: Updates a product.
- and more...
