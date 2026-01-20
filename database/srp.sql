-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2026 at 08:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

--
-- Database: `srp`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
    `activity_id` int(11) NOT NULL,
    `admin_id` int(11) NOT NULL,
    `activity_type` varchar(50) NOT NULL,
    `description` text NOT NULL,
    `reference_id` varchar(50) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
    `admin_id` int(11) NOT NULL,
    `username` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `email` varchar(100) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO
    `admin` (
        `admin_id`,
        `username`,
        `password`,
        `email`,
        `created_at`
    )
VALUES (
        1,
        'admin',
        '$2y$10$8v7iVfB4.8kJ6F9XhK2N5O9mL3pQ1rS2tU5vW6xY7zA8bC9dE0fF',
        'admin@srs.com',
        '2026-01-19 09:38:44'
    ),
    (
        2,
        'shaaz',
        '$2y$10$Bm3ZiFLv2LVATX5qtjBmZ.V2UAVwPTQq4tEW4Wq83fWVn73uMstky',
        'shaaz.scorpy@gmail.com',
        '2026-01-19 11:06:48'
    );

-- --------------------------------------------------------

--
-- Table structure for table `analytics_summary`
--

CREATE TABLE `analytics_summary` (
    `id` int(11) NOT NULL,
    `label` varchar(100) NOT NULL,
    `value` varchar(50) NOT NULL,
    `type` varchar(50) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `analytics_summary`
--

INSERT INTO
    `analytics_summary` (
        `id`,
        `label`,
        `value`,
        `type`,
        `created_at`
    )
VALUES (
        1,
        'Overall Pass Rate',
        '94.7',
        'percentage',
        '2026-01-19 18:31:52'
    ),
    (
        2,
        'Failure Rate',
        '5.3',
        'percentage',
        '2026-01-19 18:31:52'
    ),
    (
        3,
        'Total Products Tested',
        '1247',
        'count',
        '2026-01-19 18:31:52'
    ),
    (
        4,
        'CPRI Approvals',
        '312',
        'count',
        '2026-01-19 18:31:52'
    );

-- --------------------------------------------------------

--
-- Table structure for table `cpri_certifications`
--

CREATE TABLE `cpri_certifications` (
    `cpri_id` int(11) NOT NULL,
    `product_id` varchar(20) NOT NULL,
    `certification_number` varchar(50) NOT NULL,
    `certification_date` date NOT NULL,
    `expiry_date` date DEFAULT NULL,
    `status` varchar(20) DEFAULT NULL,
    `test_report_id` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_stats`
--

CREATE TABLE `dashboard_stats` (
    `stat_id` int(11) NOT NULL,
    `stat_date` date NOT NULL,
    `total_tests` int(11) DEFAULT 0,
    `passed_tests` int(11) DEFAULT 0,
    `failed_tests` int(11) DEFAULT 0,
    `total_products_tested` int(11) DEFAULT 0,
    `cpri_approvals` int(11) DEFAULT 0,
    `pass_rate` decimal(5, 2) DEFAULT NULL,
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `dashboard_stats`
--

INSERT INTO
    `dashboard_stats` (
        `stat_id`,
        `stat_date`,
        `total_tests`,
        `passed_tests`,
        `failed_tests`,
        `total_products_tested`,
        `cpri_approvals`,
        `pass_rate`,
        `updated_at`
    )
VALUES (
        1,
        '2026-01-19',
        1247,
        1175,
        72,
        1247,
        312,
        94.23,
        '2026-01-19 09:38:44'
    );

-- --------------------------------------------------------

--
-- Table structure for table `generated_reports`
--

CREATE TABLE `generated_reports` (
    `report_id` int(11) NOT NULL,
    `report_name` varchar(255) NOT NULL,
    `report_type` varchar(100) NOT NULL,
    `date_generated` date NOT NULL,
    `format` varchar(50) NOT NULL,
    `status` enum(
        'completed',
        'processing',
        'pending',
        'failed'
    ) NOT NULL,
    `size` varchar(50) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `generated_reports`
--

INSERT INTO
    `generated_reports` (
        `report_id`,
        `report_name`,
        `report_type`,
        `date_generated`,
        `format`,
        `status`,
        `size`
    )
VALUES (
        1,
        'Q3 2023 Testing Summary',
        'Monthly Summary',
        '2023-11-10',
        'PDF',
        'completed',
        '2.4 MB'
    ),
    (
        2,
        'Failure Analysis - Switchgear',
        'Failure Analysis',
        '2023-11-08',
        'Excel',
        'completed',
        '1.8 MB'
    ),
    (
        3,
        'CPRI Compliance Report',
        'Compliance Report',
        '2023-11-05',
        'PDF',
        'completed',
        '3.2 MB'
    ),
    (
        4,
        'October Performance Report',
        'Performance Summary',
        '2023-11-02',
        'Word',
        'completed',
        '1.5 MB'
    ),
    (
        5,
        'Real-time Test Analytics',
        'Analytics Dashboard',
        '2023-11-15',
        'PDF',
        'processing',
        'Processing...'
    );

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
    `product_id` varchar(20) NOT NULL,
    `name` varchar(255) NOT NULL,
    `category` varchar(50) NOT NULL,
    `voltage_rating` varchar(20) NOT NULL,
    `certification` varchar(50) NOT NULL,
    `description` text NOT NULL,
    `price` decimal(10, 2) NOT NULL,
    `stock` int(11) NOT NULL,
    `badge` varchar(20) DEFAULT NULL,
    `featured` tinyint(1) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO
    `products` (
        `product_id`,
        `name`,
        `category`,
        `voltage_rating`,
        `certification`,
        `description`,
        `price`,
        `stock`,
        `badge`,
        `featured`,
        `created_at`
    )
VALUES (
        'ELEC-2023-001',
        'HV Switchgear Panel',
        'switchgear',
        'hv',
        'cpri',
        'High voltage switchgear panel designed for industrial power distribution with advanced safety features and remote monitoring capabilities.',
        125000.00,
        12,
        'cpri',
        1,
        '2026-01-19 13:43:51'
    ),
    (
        'ELEC-2023-002',
        'Power Capacitor Bank',
        'capacitors',
        'mv',
        'iec',
        'Three-phase power factor correction capacitor bank with automatic switching and overload protection.',
        75000.00,
        20,
        'popular',
        1,
        '2026-01-19 13:43:51'
    ),
    (
        'ELEC-2023-003',
        'Industrial Fuse Set',
        'fuses',
        'lv',
        'iso',
        'High-breaking capacity fuses for industrial applications with time-delay characteristics.',
        5000.00,
        100,
        NULL,
        0,
        '2026-01-19 13:43:51'
    ),
    (
        'ELEC-2023-004',
        'PLC Control Panel',
        'panels',
        'lv',
        'iec',
        'Programmable Logic Controller based automation panel with HMI interface for process control.',
        185000.00,
        5,
        'new',
        1,
        '2026-01-19 13:43:51'
    ),
    (
        'ELEC-2023-005',
        'Precision Resistor Array',
        'resistors',
        'lv',
        'iso',
        'High-precision resistor network with low temperature coefficient for measurement applications.',
        15000.00,
        50,
        NULL,
        0,
        '2026-01-19 13:43:51'
    ),
    (
        'ELEC-2023-006',
        'Digital Multimeter',
        'testing',
        'lv',
        'iec',
        'Industrial-grade digital multimeter with true RMS measurement and data logging capabilities.',
        25000.00,
        30,
        'new',
        1,
        '2026-01-19 13:43:51'
    ),
    (
        'ELEC-2023-007',
        'Circuit Breaker Module',
        'switchgear',
        'mv',
        'cpri',
        'Modular circuit breaker unit with ground fault protection and remote trip capability.',
        45000.00,
        25,
        NULL,
        0,
        '2026-01-19 13:43:51'
    ),
    (
        'ELEC-2023-008',
        'Voltage Stabilizer',
        'equipment',
        'lv',
        'iso',
        'Automatic voltage stabilizer with microprocessor control for sensitive electronic equipment.',
        35000.00,
        15,
        NULL,
        0,
        '2026-01-19 13:43:51'
    ),
    (
        'PRD-001',
        'HV Circuit Breaker XT-5000',
        'switchgear',
        'hv',
        'cpri',
        'High voltage circuit breaker with vacuum interrupter technology for reliable power distribution.',
        125000.00,
        12,
        'cpri',
        1,
        '2026-01-19 09:38:44'
    ),
    (
        'PRD-002',
        'Digital Energy Meter DEM-2023',
        'testing',
        'lv',
        'iso',
        'Advanced digital energy meter with LCD display and data logging capabilities.',
        8500.00,
        45,
        'new',
        1,
        '2026-01-19 09:38:44'
    ),
    (
        'PRD-003',
        'Distribution Transformer 500kVA',
        'transformers',
        'mv',
        'cpri',
        'Oil-cooled distribution transformer for industrial and commercial applications.',
        325000.00,
        8,
        'popular',
        1,
        '2026-01-19 09:38:44'
    ),
    (
        'PRD-004',
        'Motor Control Center Panel',
        'panels',
        'lv',
        'iec',
        'Custom designed motor control center with PLC integration and HMI interface.',
        185000.00,
        3,
        'limited',
        1,
        '2026-01-19 09:38:44'
    ),
    (
        'PRD-005',
        'MV Switchgear MV-3200',
        'switchgear',
        'mv',
        'cpri',
        'Medium voltage switchgear with advanced protection relays and remote monitoring.',
        275000.00,
        15,
        'cpri',
        1,
        '2026-01-19 09:38:44'
    ),
    (
        'PRD-006',
        'Insulation Tester Megger MIT-500',
        'testing',
        'hv',
        'iso',
        'Portable insulation resistance tester for preventive maintenance of electrical equipment.',
        45000.00,
        22,
        'new',
        1,
        '2026-01-19 09:38:44'
    ),
    (
        'PRD-007',
        'Power Cable XLPE 3.5 Core',
        'cables',
        'mv',
        'iec',
        'XLPE insulated, armoured power cable for underground and overhead installations.',
        850.00,
        1500,
        NULL,
        0,
        '2026-01-19 09:38:44'
    ),
    (
        'PRD-008',
        'Safety Kit Complete',
        'safety',
        'lv',
        'iso',
        'Complete electrical safety kit for HV/LV line maintenance personnel.',
        12500.00,
        35,
        NULL,
        0,
        '2026-01-19 09:38:44'
    ),
    (
        'PRD-009',
        'Protection Relay PR-2023',
        'switchgear',
        'mv',
        'cpri',
        'Numerical protection relay with multiple protection functions and communication.',
        65000.00,
        18,
        'cpri',
        1,
        '2026-01-19 09:38:44'
    ),
    (
        'PRD-010',
        'Test Product',
        'testing',
        'lv',
        'cpri',
        'Test',
        5000.00,
        2,
        'popular',
        0,
        '2026-01-19 10:09:08'
    ),
    (
        'PRD-011',
        'Test Product Two',
        'safety',
        'lv',
        'cpri',
        'Debugging',
        5000.00,
        2,
        'limited',
        0,
        '2026-01-19 13:56:46'
    ),
    (
        'PRD-012',
        'Test Product Three',
        'cables',
        'lv',
        'iso',
        'Hmm',
        2345.00,
        10,
        'limited',
        0,
        '2026-01-19 14:03:32'
    ),
    (
        'PRD-013',
        'Test Product Three',
        'cables',
        'mv',
        'cpri',
        'Testing again',
        2350.00,
        50,
        'limited',
        0,
        '2026-01-19 14:05:44'
    );

-- --------------------------------------------------------

--
-- Table structure for table `product_specs`
--

CREATE TABLE `product_specs` (
    `spec_id` int(11) NOT NULL,
    `product_id` varchar(20) NOT NULL,
    `spec_label` varchar(100) NOT NULL,
    `spec_value` varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `product_specs`
--

INSERT INTO
    `product_specs` (
        `spec_id`,
        `product_id`,
        `spec_label`,
        `spec_value`
    )
VALUES (
        1,
        'PRD-001',
        'Voltage Rating',
        '33kV'
    ),
    (
        2,
        'PRD-001',
        'Breaking Capacity',
        '25kA'
    ),
    (
        3,
        'PRD-001',
        'Operating Mechanism',
        'Spring Charged'
    ),
    (
        4,
        'PRD-001',
        'IP Rating',
        'IP54'
    ),
    (
        5,
        'PRD-001',
        'Standards',
        'IEC 62271-100'
    ),
    (
        6,
        'PRD-002',
        'Accuracy Class',
        '0.5S'
    ),
    (
        7,
        'PRD-002',
        'Voltage',
        '230V AC'
    ),
    (
        8,
        'PRD-002',
        'Current',
        '5-100A'
    ),
    (
        9,
        'PRD-002',
        'Display',
        'LCD Backlit'
    ),
    (
        10,
        'PRD-002',
        'Communication',
        'RS-485, Bluetooth'
    ),
    (
        11,
        'PRD-003',
        'Capacity',
        '500 kVA'
    ),
    (
        12,
        'PRD-003',
        'Primary Voltage',
        '11kV'
    ),
    (
        13,
        'PRD-003',
        'Secondary Voltage',
        '433V'
    ),
    (
        14,
        'PRD-003',
        'Cooling',
        'ONAN'
    ),
    (
        15,
        'PRD-003',
        'Vector Group',
        'Dyn11'
    ),
    (
        16,
        'PRD-004',
        'IP Rating',
        'IP55'
    ),
    (
        17,
        'PRD-004',
        'Busbar Rating',
        '1600A'
    ),
    (
        18,
        'PRD-004',
        'Incoming Supply',
        '415V, 3 Phase'
    ),
    (
        19,
        'PRD-004',
        'Control Voltage',
        '24V DC'
    ),
    (
        20,
        'PRD-004',
        'PLC',
        'Siemens S7-1200'
    ),
    (
        21,
        'PRD-005',
        'Voltage Rating',
        '11kV'
    ),
    (
        22,
        'PRD-005',
        'Short Circuit Rating',
        '31.5kA/3s'
    ),
    (
        23,
        'PRD-005',
        'Busbar Rating',
        '2000A'
    ),
    (
        24,
        'PRD-005',
        'Protection Relay',
        'Microprocessor Based'
    ),
    (
        25,
        'PRD-005',
        'Communication',
        'IEC 61850'
    ),
    (
        26,
        'PRD-006',
        'Test Voltage',
        '50V to 5kV'
    ),
    (
        27,
        'PRD-006',
        'Resistance Range',
        '1kΩ to 10TΩ'
    ),
    (
        28,
        'PRD-006',
        'Display',
        'Digital LCD'
    ),
    (
        29,
        'PRD-006',
        'Memory',
        '1000 Test Results'
    ),
    (
        30,
        'PRD-006',
        'Battery',
        'Rechargeable Li-ion'
    ),
    (
        31,
        'PRD-007',
        'Conductor',
        'Copper, 240 sq.mm'
    ),
    (
        32,
        'PRD-007',
        'Insulation',
        'XLPE'
    ),
    (
        33,
        'PRD-007',
        'Armouring',
        'Galvanized Steel'
    ),
    (
        34,
        'PRD-007',
        'Voltage Grade',
        '1.1kV'
    ),
    (
        35,
        'PRD-007',
        'Standards',
        'IS 1554'
    ),
    (
        36,
        'PRD-008',
        'Helmet',
        'FRP with Face Shield'
    ),
    (
        37,
        'PRD-008',
        'Gloves',
        'Class 00 Leather'
    ),
    (
        38,
        'PRD-008',
        'Boots',
        'Electrical Hazard'
    ),
    (
        39,
        'PRD-008',
        'Tools',
        'Insulated Set'
    ),
    (
        40,
        'PRD-008',
        'Bag',
        'Carry Case Included'
    ),
    (
        41,
        'PRD-009',
        'Protection Functions',
        'Overcurrent, Earth Fault'
    ),
    (
        42,
        'PRD-009',
        'Communication',
        'RS-485, Ethernet'
    ),
    (
        43,
        'PRD-009',
        'Inputs',
        '6 CT, 4 PT'
    ),
    (
        44,
        'PRD-009',
        'Outputs',
        '8 Relay, 4 Digital'
    ),
    (
        45,
        'PRD-009',
        'Display',
        'Graphic LCD'
    );

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
    `report_id` int(11) NOT NULL,
    `test_id` int(11) NOT NULL,
    `report_type` varchar(50) NOT NULL,
    `title` varchar(255) NOT NULL,
    `description` text DEFAULT NULL,
    `file_path` varchar(255) DEFAULT NULL,
    `generated_date` timestamp NOT NULL DEFAULT current_timestamp(),
    `generated_by` int(11) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_reports`
--

CREATE TABLE `scheduled_reports` (
    `schedule_id` int(11) NOT NULL,
    `schedule_name` varchar(255) NOT NULL,
    `frequency` enum(
        'Daily',
        'Weekly',
        'Monthly',
        'Quarterly',
        'Custom'
    ) NOT NULL,
    `next_run` datetime NOT NULL,
    `last_run` datetime NOT NULL,
    `status` enum('active', 'paused') NOT NULL,
    `report_type` varchar(100) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `scheduled_reports`
--

INSERT INTO
    `scheduled_reports` (
        `schedule_id`,
        `schedule_name`,
        `frequency`,
        `next_run`,
        `last_run`,
        `status`,
        `report_type`
    )
VALUES (
        1,
        'Daily Test Summary',
        'Daily',
        '2023-11-16 09:00:00',
        '2023-11-15 09:00:00',
        'active',
        'Daily Summary'
    ),
    (
        2,
        'Weekly Compliance Report',
        'Weekly',
        '2023-11-20 10:00:00',
        '2023-11-13 10:00:00',
        'active',
        'Compliance Report'
    ),
    (
        3,
        'Monthly Performance Review',
        'Monthly',
        '2023-12-01 08:00:00',
        '2023-11-01 08:00:00',
        'active',
        'Performance Summary'
    ),
    (
        4,
        'Quarterly CPRI Submission',
        'Quarterly',
        '2024-01-01 11:00:00',
        '2023-10-01 11:00:00',
        'paused',
        'CPRI Report'
    );

-- --------------------------------------------------------

--
-- Table structure for table `testers`
--

CREATE TABLE `testers` (
    `tester_id` int(11) NOT NULL,
    `tester_name` varchar(255) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `testers`
--

INSERT INTO
    `testers` (`tester_id`, `tester_name`)
VALUES (1, 'Alizay'),
    (7, 'Arun Verma'),
    (2, 'Dr. Anil Sharma'),
    (6, 'Meera Desai'),
    (4, 'Priya Singh'),
    (3, 'Rajesh Kumar'),
    (5, 'Vikram Patel');

-- --------------------------------------------------------

--
-- Table structure for table `testing_records`
--

CREATE TABLE `testing_records` (
    `test_id` int(11) NOT NULL,
    `product_id` varchar(20) NOT NULL,
    `test_date` date NOT NULL,
    `test_type` varchar(100) NOT NULL,
    `test_name` varchar(50) DEFAULT NULL,
    `voltage_rating` varchar(20) DEFAULT NULL,
    `status` varchar(20) DEFAULT NULL,
    `result` varchar(50) DEFAULT NULL,
    `notes` text DEFAULT NULL,
    `created_by` int(11) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `tester_name` varchar(255) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `testing_records`
--

INSERT INTO
    `testing_records` (
        `test_id`,
        `product_id`,
        `test_date`,
        `test_type`,
        `test_name`,
        `voltage_rating`,
        `status`,
        `result`,
        `notes`,
        `created_by`,
        `created_at`,
        `tester_name`
    )
VALUES (
        1,
        'ELEC-2023-001',
        '2023-11-15',
        'Switchgear',
        NULL,
        NULL,
        'in-progress',
        NULL,
        'Currently undergoing dielectric strength testing. Preliminary results are positive.',
        1,
        '2026-01-19 13:44:20',
        'Dr. Anil Sharma'
    ),
    (
        2,
        'ELEC-2023-002',
        '2023-11-05',
        'Capacitors',
        NULL,
        NULL,
        'in-progress',
        NULL,
        'Currently undergoing capacitance measurement test. Preliminary results show excellent performance.',
        1,
        '2026-01-19 13:44:20',
        'Arun Verma'
    ),
    (
        3,
        'ELEC-2023-003',
        '2023-11-10',
        'Fuses',
        NULL,
        NULL,
        'pending',
        NULL,
        'Scheduled for testing next week. Preparing test setup for short-circuit testing.',
        1,
        '2026-01-19 13:44:20',
        'Rajesh Kumar'
    ),
    (
        4,
        'ELEC-2023-004',
        '2023-10-28',
        'Control Panels',
        NULL,
        NULL,
        'completed',
        'Pass',
        'All functions tested successfully. Communication protocols verified with multiple devices.',
        1,
        '2026-01-19 13:44:20',
        'Meera Desai'
    ),
    (
        5,
        'ELEC-2023-005',
        '2023-11-02',
        'Resistors',
        NULL,
        NULL,
        'failed',
        'Fail',
        'Failed tolerance test on resistors R3 and R7. Requires recalibration or replacement.',
        1,
        '2026-01-19 13:44:20',
        'Rajesh Kumar'
    ),
    (
        6,
        'ELEC-2023-006',
        '2023-11-07',
        'Testing Equipment',
        NULL,
        NULL,
        'in-progress',
        NULL,
        'Accuracy testing in progress. DC voltage measurements completed, AC testing underway.',
        1,
        '2026-01-19 13:44:20',
        'Dr. Anil Sharma'
    ),
    (
        7,
        'ELEC-2023-007',
        '2023-10-20',
        'Switchgear',
        NULL,
        NULL,
        'completed',
        'Pass',
        'All protection functions tested successfully. Trip timing within specified limits.',
        1,
        '2026-01-19 13:44:20',
        'Meera Desai'
    ),
    (
        8,
        'ELEC-2023-008',
        '2023-11-12',
        'Power Equipment',
        NULL,
        NULL,
        'pending',
        NULL,
        'Awaiting arrival of specialized test equipment for transient response testing.',
        1,
        '2026-01-19 13:44:20',
        'Meera Desai'
    );

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
    `test_id` int(11) NOT NULL,
    `test_name` varchar(255) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO
    `tests` (`test_id`, `test_name`)
VALUES (4, 'Insulation Test'),
    (3, 'Load Test'),
    (2, 'Thermal Test'),
    (1, 'Voltage Test');

-- --------------------------------------------------------

--
-- Table structure for table `test_results`
--

CREATE TABLE `test_results` (
    `result_id` int(11) NOT NULL,
    `test_id` int(11) NOT NULL,
    `parameter` varchar(100) NOT NULL,
    `expected_value` varchar(100) DEFAULT NULL,
    `actual_value` varchar(100) DEFAULT NULL,
    `status` varchar(20) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
ADD PRIMARY KEY (`activity_id`),
ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
ADD PRIMARY KEY (`admin_id`),
ADD UNIQUE KEY `username` (`username`),
ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `analytics_summary`
--
ALTER TABLE `analytics_summary` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cpri_certifications`
--
ALTER TABLE `cpri_certifications`
ADD PRIMARY KEY (`cpri_id`),
ADD UNIQUE KEY `certification_number` (`certification_number`),
ADD KEY `product_id` (`product_id`),
ADD KEY `test_report_id` (`test_report_id`);

--
-- Indexes for table `dashboard_stats`
--
ALTER TABLE `dashboard_stats` ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `generated_reports`
--
ALTER TABLE `generated_reports` ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products` ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_specs`
--
ALTER TABLE `product_specs`
ADD PRIMARY KEY (`spec_id`),
ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
ADD PRIMARY KEY (`report_id`),
ADD KEY `test_id` (`test_id`),
ADD KEY `generated_by` (`generated_by`);

--
-- Indexes for table `scheduled_reports`
--
ALTER TABLE `scheduled_reports` ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `testers`
--
ALTER TABLE `testers`
ADD PRIMARY KEY (`tester_id`),
ADD UNIQUE KEY `tester_name` (`tester_name`);

--
-- Indexes for table `testing_records`
--
ALTER TABLE `testing_records`
ADD PRIMARY KEY (`test_id`),
ADD KEY `product_id` (`product_id`),
ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
ADD PRIMARY KEY (`test_id`),
ADD UNIQUE KEY `test_name` (`test_name`);

--
-- Indexes for table `test_results`
--
ALTER TABLE `test_results`
ADD PRIMARY KEY (`result_id`),
ADD KEY `test_id` (`test_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

--
-- AUTO_INCREMENT for table `analytics_summary`
--
ALTER TABLE `analytics_summary`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT for table `cpri_certifications`
--
ALTER TABLE `cpri_certifications`
MODIFY `cpri_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboard_stats`
--
ALTER TABLE `dashboard_stats`
MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

--
-- AUTO_INCREMENT for table `generated_reports`
--
ALTER TABLE `generated_reports`
MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 10;

--
-- AUTO_INCREMENT for table `product_specs`
--
ALTER TABLE `product_specs`
MODIFY `spec_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 46;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduled_reports`
--
ALTER TABLE `scheduled_reports`
MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT for table `testers`
--
ALTER TABLE `testers`
MODIFY `tester_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 9;

--
-- AUTO_INCREMENT for table `testing_records`
--
ALTER TABLE `testing_records`
MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 11;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 6;

--
-- AUTO_INCREMENT for table `test_results`
--
ALTER TABLE `test_results`
MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `cpri_certifications`
--
ALTER TABLE `cpri_certifications`
ADD CONSTRAINT `cpri_certifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
ADD CONSTRAINT `cpri_certifications_ibfk_2` FOREIGN KEY (`test_report_id`) REFERENCES `reports` (`report_id`);

--
-- Constraints for table `product_specs`
--
ALTER TABLE `product_specs`
ADD CONSTRAINT `product_specs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `testing_records` (`test_id`),
ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`generated_by`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `testing_records`
--
ALTER TABLE `testing_records`
ADD CONSTRAINT `testing_records_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
ADD CONSTRAINT `testing_records_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `admin` (`admin_id`);

--
-- Constraints for table `test_results`
--
ALTER TABLE `test_results`
ADD CONSTRAINT `test_results_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `testing_records` (`test_id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;