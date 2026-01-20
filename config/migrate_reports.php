<?php
include("../config/conn.php"); // DB connection

// --- STATIC DATA FROM JS --- //
$recentReports = [
    [
        'name' => "Q3 2023 Testing Summary",
        'type' => "Monthly Summary",
        'date' => "2023-11-10",
        'format' => "PDF",
        'status' => "completed",
        'size' => "2.4 MB"
    ],
    [
        'name' => "Failure Analysis - Switchgear",
        'type' => "Failure Analysis",
        'date' => "2023-11-08",
        'format' => "Excel",
        'status' => "completed",
        'size' => "1.8 MB"
    ],
    [
        'name' => "CPRI Compliance Report",
        'type' => "Compliance Report",
        'date' => "2023-11-05",
        'format' => "PDF",
        'status' => "completed",
        'size' => "3.2 MB"
    ],
    [
        'name' => "October Performance Report",
        'type' => "Performance Summary",
        'date' => "2023-11-02",
        'format' => "Word",
        'status' => "completed",
        'size' => "1.5 MB"
    ],
    [
        'name' => "Real-time Test Analytics",
        'type' => "Analytics Dashboard",
        'date' => "2023-11-15",
        'format' => "PDF",
        'status' => "processing",
        'size' => "Processing..."
    ]
];

$scheduledReports = [
    [
        'name' => "Daily Test Summary",
        'report_type' => "Daily Summary",
        'frequency' => "Daily",
        'next_run' => "2023-11-16 09:00:00",
        'last_run' => "2023-11-15 09:00:00",
        'status' => "active",
        'email_recipients' => "user1@example.com,user2@example.com"
    ],
    [
        'name' => "Weekly Compliance Report",
        'report_type' => "Compliance Report",
        'frequency' => "Weekly",
        'next_run' => "2023-11-20 10:00:00",
        'last_run' => "2023-11-13 10:00:00",
        'status' => "active",
        'email_recipients' => "compliance@example.com"
    ],
    [
        'name' => "Monthly Performance Review",
        'report_type' => "Performance Summary",
        'frequency' => "Monthly",
        'next_run' => "2023-12-01 08:00:00",
        'last_run' => "2023-11-01 08:00:00",
        'status' => "active",
        'email_recipients' => "manager@example.com"
    ],
    [
        'name' => "Quarterly CPRI Submission",
        'report_type' => "CPRI Report",
        'frequency' => "Quarterly",
        'next_run' => "2024-01-01 11:00:00",
        'last_run' => "2023-10-01 11:00:00",
        'status' => "paused",
        'email_recipients' => "cpri@example.com"
    ]
];

// --- INSERT RECENT REPORTS --- //
foreach ($recentReports as $report) {
    $stmt = $conn->prepare("INSERT INTO generated_reports (name, type, date_generated, format, status, size) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "ssssss",
        $report['name'],
        $report['type'],
        $report['date'],
        $report['format'],
        $report['status'],
        $report['size']
    );
    $stmt->execute();
    $stmt->close();
}

// --- INSERT SCHEDULED REPORTS --- //
foreach ($scheduledReports as $schedule) {
    $stmt = $conn->prepare("INSERT INTO scheduled_reports (name, report_type, frequency, next_run, last_run, status, email_recipients) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssss",
        $schedule['name'],
        $schedule['report_type'],
        $schedule['frequency'],
        $schedule['next_run'],
        $schedule['last_run'],
        $schedule['status'],
        $schedule['email_recipients']
    );
    $stmt->execute();
    $stmt->close();
}

echo "Migration complete. Static data inserted into database.";
$conn->close();
