<?php
session_start();
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "srp";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $login_username = $_POST['username'] ?? '';
    $login_password = $_POST['password'] ?? '';
    $remember = $_POST['remember'] ?? 0;

    if (empty($login_username) || empty($login_password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Username and password are required'
        ]);
        exit;
    }

    // Query database
    $stmt = $conn->prepare("SELECT admin_id, username, password FROM admin WHERE username = ?");

    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $login_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Username not found'
        ]);
        $stmt->close();
        $conn->close();
        exit;
    }

    $row = $result->fetch_assoc();

    // Verify password
    if (!password_verify($login_password, $row['password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Incorrect password'
        ]);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Login successful
    $_SESSION['admin_id'] = $row['admin_id'];
    $_SESSION['admin_username'] = $row['username'];

    // Set remember me cookie if checked
    if ($remember) {
        setcookie('admin_username', $row['username'], time() + (86400 * 30), "/");
    }

    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'username' => $row['username']
    ]);

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
