<?php
include("config/conn.php");

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, trim($_POST['username'] ?? ''));
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $password = mysqli_real_escape_string($conn, trim($_POST['password'] ?? ''));
    $confirm_password = mysqli_real_escape_string($conn, trim($_POST['confirm_password'] ?? ''));

    // Validation
    if (empty($username)) {
        $error = 'Username is required';
    } elseif (empty($email)) {
        $error = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (empty($password)) {
        $error = 'Password is required';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } else {
        // Check if username already exists
        $check_query = "SELECT * FROM admin WHERE username = '$username'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) > 0) {
            $error = 'Username already exists';
        } else {
            // Check if email already exists
            $email_check = "SELECT * FROM admin WHERE email = '$email'";
            $email_result = mysqli_query($conn, $email_check);

            if (mysqli_num_rows($email_result) > 0) {
                $error = 'Email already registered';
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new admin user
                $insert_query = "INSERT INTO admin (username, email, password) 
                               VALUES ('$username', '$email', '$hashed_password')";

                if (mysqli_query($conn, $insert_query)) {
                    $success = 'Registration successful! You can now login.';
                    // Clear form
                    $_POST = array();
                } else {
                    $error = 'Error during registration: ' . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SRS Electrical Appliances</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            --light-gray: #f5f5f5;
            --white: #ffffff;
            --success: #28a745;
            --error: #dc3545;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        body {
            line-height: 1.6;
            color: var(--dark-gray);
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background: var(--white);
            border-radius: 8px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-header h1 {
            color: var(--primary-blue);
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .register-header p {
            color: var(--medium-gray);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-gray);
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--light-gray);
            border-radius: 4px;
            font-size: 1rem;
            transition: var(--transition);
            background-color: #fafafa;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-blue);
            background-color: var(--white);
            box-shadow: 0 0 0 3px rgba(26, 95, 122, 0.1);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: none;
            animation: slideIn 0.3s ease;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.show {
            display: block;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary-blue);
            color: var(--white);
            margin-bottom: 15px;
        }

        .btn-primary:hover {
            background-color: var(--accent-blue);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26, 95, 122, 0.2);
        }

        .login-link {
            text-align: center;
            color: var(--medium-gray);
        }

        .login-link a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .login-link a:hover {
            color: var(--accent-blue);
            text-decoration: underline;
        }

        .password-requirements {
            background-color: #f8f9fa;
            border-left: 4px solid var(--light-blue);
            padding: 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            color: var(--medium-gray);
            margin-top: 8px;
        }

        .back-link {
            text-align: center;
            margin-bottom: 20px;
        }

        .back-link a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: var(--transition);
        }

        .back-link a:hover {
            gap: 10px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="back-link">
            <a href="index.html"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>

        <div class="register-header">
            <h1>Create Account</h1>
            <p>Join SRS Electrical as an Administrator</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error show">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success show">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <p style="margin-top: 10px;">
                    <a href="index.html" style="color: #155724; font-weight: bold;">Click here to login</a>
                </p>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Username
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required>
                <div class="password-requirements">
                    <i class="fas fa-info-circle"></i> Minimum 6 characters
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i> Confirm Password
                </label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm your password"
                    required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="index.html" onclick="document.getElementById('loginForm').submit(); return false;">Login here</a>
        </div>
    </div>
</body>

</html>