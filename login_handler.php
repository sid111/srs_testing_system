<?php
include("config/conn.php");
session_start();

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $remember = isset($_POST['remember']) ? true : false;

    if (empty($username) || empty($password)) {
        $response['message'] = 'Username and password are required';
        echo json_encode($response);
        exit;
    }

    // Query the admin table
    $query = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        $response['message'] = 'Database error: ' . mysqli_error($conn);
        echo json_encode($response);
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $admin['password'])) {
            $response['success'] = true;
            $response['message'] = 'Login successful!';

            // Set session
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['email'] = $admin['email'];

            // Set remember me cookie if requested
            if ($remember) {
                setcookie('username', $username, time() + (86400 * 30), "/");
                setcookie('remember', 'true', time() + (86400 * 30), "/");
            }
        } else {
            $response['message'] = 'Invalid username or password';
        }
    } else {
        $response['message'] = 'Invalid username or password';
    }
}

echo json_encode($response);
