# SRS Electrical Appliances

This is a web application for SRS Electrical Appliances, a company that specializes in electrical testing. The application provides a platform for managing products, tests, and test results.

## Features

*   **Product Catalog:** View and manage a catalog of electrical products.
*   **Lab Testing:** Track products that are currently under testing.
*   **Dashboard:** View key statistics and performance metrics related to product testing.
*   **Admin Login:** Secure admin area for managing the application.

## Technologies Used

*   **Frontend:** HTML, CSS, JavaScript
*   **Backend:** PHP
*   **Database:** MySQL / MariaDB

## Database Schema

The database schema is defined in the `database/srp.sql` file. It consists of the following tables:

*   `admin`: Stores admin user credentials.
*   `products`: Stores product information.
*   `tests`: Stores information about different types of tests.
*   `testers`: Stores information about the technicians who perform the tests.
*   `testing_records`: Stores the results of the tests performed on the products.
*   `product_specs`: Stores additional specifications for each product.

## API

The application uses a set of PHP scripts in the `api/` directory to provide a RESTful API for the frontend.

*   `get_products.php`: Retrieves a list of all products.
*   `add_product.php`: Adds a new product to the database.
*   `get_test_results.php`: Retrieves a list of all test results.
*   `add_test_result.php`: Adds a new test result.
*   `get_dashboard_stats.php`: Retrieves statistics for the dashboard.

## Setup

1.  Create a new database named `srp`.
2.  Import the `database/srp.sql` file into the `srp` database.
3.  Place the project files in your web server's document root (e.g., `htdocs` for XAMPP).
4.  Update the database connection details in `config/conn.php` if necessary.
5.  Access the application through your web browser.

## Admin Credentials

Default admin credentials are:
**Username:** admin
**Password:** admin123
