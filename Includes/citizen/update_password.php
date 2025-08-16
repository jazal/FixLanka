<?php
session_start();
include('../dbconnect.php');
$alert = "";

if (!isset($_SESSION['allow_password_reset']) || !isset($_SESSION['reset_email'])) {
    header('Location: forgot_password.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['reset_email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if ($new_password !== $confirm_password) {
        $alert = "Swal.fire('Error', 'Passwords do not match.', 'error');";
    } else {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password_hash='$hash', reset_otp=NULL, reset_otp_expires=NULL WHERE email='$email'");
        unset($_SESSION['reset_email']);
        unset($_SESSION['allow_password_reset']);
        $alert = "Swal.fire('Success', 'Password reset successful! You can now log in.', 'success').then(() => { window.location='../login.php'; });";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Password - FixLanka</title>
    <link rel="stylesheet" href="citizen.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="forgot-password-container">
        <h2>Update Password</h2>
        <form method="POST" class="forgot-form">
            <label>Enter your new password:</label><br>
            <input type="password" name="new_password" required><br>
            <label>Confirm your new password:</label><br>
            <input type="password" name="confirm_password" required><br><br>
            <button type="submit">Update Password</button>
        </form>
        <script>
        <?php if (!empty($alert)) echo $alert; ?>
        </script>
    </div>
</body>
</html> 