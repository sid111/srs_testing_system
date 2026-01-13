CREATE DATABASE srs_testing_system;
USE srs_testing_system;

-- ========================
-- Testing Records Table
-- ========================
CREATE TABLE testing_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    testing_id VARCHAR(12) UNIQUE NOT NULL,
    product_id VARCHAR(10) NOT NULL,
    product_type VARCHAR(50),
    test_type VARCHAR(50),
    result ENUM('PASS','FAIL'),
    remarks TEXT,
    tested_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================
-- Dummy Data
-- ========================
INSERT INTO testing_records 
(testing_id, product_id, product_type, test_type, result, remarks, tested_by)
VALUES
('SWG001123456','SWG0010001','Switchgear','Voltage Test','PASS','Normal operation','Engineer A'),
('CAP002654321','CAP0020002','Capacitor','Load Test','FAIL','Overheating','Engineer B'),
('FUS003987654','FUS0030003','Fuse','Current Test','PASS','Within limits','Engineer C'),
('RES004456789','RES0040004','Resistor','Insulation Test','FAIL','Insulation weak','Engineer A');
