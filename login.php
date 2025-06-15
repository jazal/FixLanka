<?php
session_start();
include('includes/dbconnect.php');
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Step 1: Try to find user in users table
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on role
            if ($row['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: home.php");
            }
            exit();
        } else {
            $msg = "Incorrect password.";
        }

    } else {
        // Step 2: Try to find user in departments table
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

                header("Location: department_dashboard.php");
                exit();
            } else {
                $msg = "Incorrect password.";
            }
        } else {
            $msg = "User not found.";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>FixLanka - Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <h2>Login to FixLanka</h2>
    <form method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
        </br>
        </br>
        </br>
        </br>
        <a href="register.php" class="login-btn">Register</a>

    </form>

    <p><?= $msg ?></p>
</body>
</html>
