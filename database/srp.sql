-- phpMyAdmin SQL Dump
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

-- --------------------------------------------------------
-- TABLE: testers
-- --------------------------------------------------------
CREATE TABLE testers (
    tester_id INT AUTO_INCREMENT PRIMARY KEY,
    tester_name VARCHAR(100) NOT NULL
);

INSERT INTO
    testers (tester_name)
VALUES ('Ali Khan'),
    ('Sara Ahmed'),
    ('Usman Raza');

-- --------------------------------------------------------
-- TABLE: products
-- --------------------------------------------------------
CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    product_description TEXT,
    product_image VARCHAR(255)
);

INSERT INTO
    products (
        product_name,
        product_description,
        product_image
    )
VALUES (
        'Switchgear X100',
        'High voltage industrial switchgear',
        'https://via.placeholder.com/300?text=Switchgear'
    ),
    (
        'Capacitor C20',
        'Power factor correction capacitor',
        'https://via.placeholder.com/300?text=Capacitor'
    ),
    (
        'Fuse F10',
        'Industrial safety fuse',
        'https://via.placeholder.com/300?text=Fuse'
    );

-- --------------------------------------------------------
-- TABLE: tests
-- --------------------------------------------------------
CREATE TABLE tests (
    test_id INT AUTO_INCREMENT PRIMARY KEY,
    test_name VARCHAR(100) NOT NULL
);

INSERT INTO
    tests (test_name)
VALUES ('Voltage Test'),
    ('Thermal Test'),
    ('Insulation Test'),
    ('Load Test');

-- --------------------------------------------------------
-- TABLE: test_results
-- --------------------------------------------------------
CREATE TABLE test_results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    tester_id INT NOT NULL,
    product_id INT NOT NULL,
    test_id INT NOT NULL,
    result ENUM('Pass', 'Fail') NOT NULL,
    test_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tester_id) REFERENCES testers (tester_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products (product_id) ON DELETE CASCADE,
    FOREIGN KEY (test_id) REFERENCES tests (test_id) ON DELETE CASCADE
);

INSERT INTO
    test_results (
        tester_id,
        product_id,
        test_id,
        result
    )
VALUES (1, 1, 1, 'Pass'),
    (1, 1, 2, 'Pass'),
    (2, 2, 3, 'Fail'),
    (3, 3, 4, 'Pass');

-- --------------------------------------------------------
-- TABLE: admin
-- --------------------------------------------------------
CREATE TABLE admin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO
    admin (username, password, email)
VALUES (
        'admin',
        'admin123',
        'admin@srselectrical.com'
    );

COMMIT;