<?php
session_start();
require_once '../dbconnect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




require_once '../../phpmailer/src/Exception.php';
require_once '../../phpmailer/src/PHPMailer.php';
require_once '../../phpmailer/src/SMTP.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$message = '';
$email = '';

// Get user's email from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_email = $user['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['request_otp'])) {
        $email = $_POST['email'];
        
        // Verify if entered email matches user's email
        if ($email !== $user_email) {
            $message = "The email address does not match your registered email.";
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_email'] = $email;
            
            // Send OTP via email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your-email@gmail.com'; // Replace with your email
                $mail->Password = 'your-password'; // Replace with your password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                
                $mail->setFrom('your-email@gmail.com', 'FixLanka');
                $mail->addAddress($email);
                $mail->Subject = 'Password Change OTP';
                $mail->Body = "Your OTP for password change is: " . $otp;
                
                $mail->send();
                $message = "OTP has been sent to your email.";
            } catch (Exception $e) {
                $message = "Error sending OTP. Please try again. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
    
    if (isset($_POST['verify_otp'])) {
        $entered_otp = $_POST['otp'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if ($entered_otp == $_SESSION['otp']) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $email = $_SESSION['otp_email'];
                
                $sql = "UPDATE users SET password = ? WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $hashed_password, $email);
                
                if ($stmt->execute()) {
                    $message = "Password changed successfully!";
                    unset($_SESSION['otp']);
                    unset($_SESSION['otp_email']);
                } else {
                    $message = "Error changing password. Please try again.";
                }
            } else {
                $message = "Passwords do not match!";
            }
        } else {
            $message = "Invalid OTP!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - FixLanka</title>
    <link rel="stylesheet" href="citizen.css">
</head>
<body>
    <div class="container">
        <div class="password-change-box">
            <h2>Change Password</h2>
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if (!isset($_SESSION['otp'])): ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
                        <small class="help-text">This is your registered email address</small>
                    </div>
                    <button type="submit" name="request_otp" class="btn">Request OTP</button>
                </form>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="otp">Enter OTP</label>
                        <input type="text" id="otp" name="otp" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="verify_otp" class="btn">Change Password</button>
                </form>
            <?php endif; ?>
            <div class="back-link">
                <a href="home.php">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html> 