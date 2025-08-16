<?php
session_start();
include('dbconnect.php');
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Step 1: Try to find user in admins table
    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['user_id'] = $row['admin_id']; // Added for consistent session management
            $_SESSION['username'] = $row['name'];
            $_SESSION['role'] = 'admin';
            // Redirect to admin dashboard
            header("Location: admin/admin_dashboard.php");
            exit();
        } else {
            $msg = "Incorrect password.";
        }
    } else {
        // Step 2: Try to find user in users table (citizens)
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['name'];
                $_SESSION['role'] = 'citizen';
                // Redirect to citizen home/dashboard
                header("Location: ../home.php");
                exit();
            } else {
                $msg = "Incorrect password.";
            }
        } else {
            // Step 3: Try to find user in departments table
            $sql = "SELECT * FROM departments WHERE contact_email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                // NOTE: Adjust 'password' to 'password_hash' if you used that name
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['dept_id'];
                    $_SESSION['username'] = $row['dept_name'];
                    $_SESSION['role'] = 'department';
                    header("Location: ../Includes/department/department_dashboard.php");
                    exit();
                } else {
                    $msg = "Incorrect password.";
                }
            } else {
                $msg = "User not found.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>FixLanka - Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <h2>Login to FixLanka</h2>
    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
        
        <div class="links">
            <a href="citizen/forgot_password.php" class="forgot-password">Forgot Password?</a>
            <a href="citizen/register.php" class="register-link">Register</a>
            <a href="../home.php" class="register-link">Return to Home</a>
        </div>
    </form>

    <p><?= $msg ?></p>
</body>
</html>
