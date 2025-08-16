<?php
session_start();
include('../dbconnect.php');
$alert = "";

// PHPMailer setup (not used for this logic, but keep for future use)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../../phpmailer/src/Exception.php';
require_once '../../phpmailer/src/PHPMailer.php';
require_once '../../phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $description = $_POST['description'];
    $new_password = $_POST['new_password'];

    // Check if email exists in users table
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    if ($stmt->fetch()) {
        $stmt->close();
        // Insert into password_reset_requests
        $stmt2 = $conn->prepare("INSERT INTO password_reset_requests (user_id, username, email, description, requested_password) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("issss", $user_id, $username, $email, $description, $new_password);
        $stmt2->execute();
        $stmt2->close();
        $alert = "Swal.fire({title: 'Request Submitted', text: 'Your request was submitted successfully. You will receive your new password within 24 hours.', icon: 'success', showCancelButton: false, confirmButtonText: 'Go to Home', allowOutsideClick: false}).then((result) => { if (result.isConfirmed) { window.location = '../../home.php'; } });";
    } else {
        $stmt->close();
        $alert = "Swal.fire({title: 'No Account Found', text: 'You do not have an account. Please register to continue.', icon: 'error', showCancelButton: false, confirmButtonText: 'Register', allowOutsideClick: false}).then((result) => { if (result.isConfirmed) { window.location = 'register.php'; } });";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password - FixLanka</title>
    <link rel="stylesheet" href="citizen.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Forgot Password</h2>
        <form method="POST" class="forgot-form">
            <label>Username:</label><br>
            <input type="text" name="username" required><br>
            <label>Email:</label><br>
            <input type="email" name="email" required><br>
            <label>Short Description:</label><br>
            <textarea name="description" rows="3" required></textarea><br>
            <label>Requested New Password:</label><br>
            <input type="text" name="new_password" required><br><br>
            <button type="submit">Send Request</button>
            <div class="links">
                <a href="../login.php">Back to Login</a>
            </div>
        </form>
        <script>
        <?php if (!empty($alert)) echo str_replace(array("\r", "\n"), '', $alert); ?>
        </script>
    </div>
</body>
</html> 