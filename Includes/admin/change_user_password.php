<?php
session_start();
include('../dbconnect.php');
$alert = "";
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    unset($_SESSION['alert']);
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../unauthorized.php");
    exit();
}


// PHPMailer setup
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once '../../phpmailer/src/Exception.php';
require_once '../../phpmailer/src/PHPMailer.php';
require_once '../../phpmailer/src/SMTP.php';

// Handle password update
if (isset($_POST['user_id']) && isset($_POST['new_password'])) {
    $user_id = intval($_POST['user_id']);
    $new_password = $_POST['new_password'];
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $update = $conn->query("UPDATE users SET password_hash='$hash' WHERE user_id=$user_id");
    if ($update) {
        // Get user email and username
        $user_result = $conn->query("SELECT email, name FROM users WHERE user_id=$user_id LIMIT 1");
        if ($user_result && $user_row = $user_result->fetch_assoc()) {
            $user_email = $user_row['email'];
            $user_name = $user_row['name'];
            // Send email with new password
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'Omar727221@gmail.com';
                $mail->Password = 'mwjn yzqg pjrk ayzp';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('Omar727221@gmail.com', 'FixLanka Admin');
                $mail->addAddress($user_email, $user_name);

                $mail->isHTML(true);
                $mail->Subject = 'Your FixLanka Password Has Been Changed';
                $mail->Body    = '<div style="font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; border:1px solid #eee; border-radius:10px; box-shadow:0 2px 8px #eee; padding:24px;">
                    <div style="text-align:center; margin-bottom:18px;">
                        <img src="https://i.ibb.co/6bQ7QwB/FIXLANKA-LOGO.png" alt="FixLanka Logo" style="height:48px; margin-bottom:8px;">
                        <h2 style="color:#007bff; margin:0;">FixLanka</h2>
                    </div>
                    <p>Hi <b>' . htmlspecialchars($user_name) . '</b>,</p>
                    <p>Your password has been <b>changed by the admin</b> for your FixLanka account.</p>
                    <div style="background:#f5faff; border:1px solid #007bff; border-radius:6px; padding:16px; margin:18px 0; text-align:center;">
                        <span style="color:#333; font-size:1.1em;">Your new password:</span><br>
                        <span style="display:inline-block; margin-top:8px; font-size:1.3em; color:#007bff; letter-spacing:2px;"><b>' . htmlspecialchars($new_password) . '</b></span>
                    </div>
                    <p style="margin-bottom:0;">You can now <a href="http://localhost/FixLanka/Includes/login.php" style="color:#007bff; text-decoration:underline;">log in</a> with your new password.</p>
                    <p style="color:#888; font-size:0.98em; margin-top:18px;">If you did not request this change, please contact FixLanka support immediately.</p>
                    <div style="margin-top:24px; color:#aaa; font-size:0.95em; text-align:center;">&copy; ' . date('Y') . ' FixLanka</div>
                </div>';

                // Add SMTPOptions to bypass SSL verification for local dev
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->send();
                // Mark all unread requests for this user as read
                $conn->query("UPDATE password_reset_requests SET `read`=1 WHERE user_id=$user_id AND `read`=0");
                $_SESSION['alert'] = "Swal.fire('Success', 'Password updated and email sent to user!', 'success');";
            } catch (Exception $e) {
                $_SESSION['alert'] = "Swal.fire('Warning', 'Password updated, but email could not be sent. Error: " . addslashes($mail->ErrorInfo) . "', 'warning');";
            }
        } else {
            $_SESSION['alert'] = "Swal.fire('Success', 'Password updated, but user email not found.', 'success');";
        }
    } else {
        $_SESSION['alert'] = "Swal.fire('Error', 'Failed to update password.', 'error');";
    }
    header('Location: change_user_password.php');
    exit();
}

// Handle search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where = '';
if ($search !== '') {
    $where = "WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}
$result = $conn->query("
    SELECT u.user_id, u.name, u.email, u.mobile
    FROM users u
    INNER JOIN password_reset_requests r ON u.user_id = r.user_id
    WHERE r.`read` = 0
    GROUP BY u.user_id
    ORDER BY MAX(r.created_at) DESC
");

// Debug output
echo '<!-- DEBUG: $alert = ' . htmlspecialchars($alert) . ' -->';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change User Password - Admin</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="admin-content-container">
        <h2 class="admin-title admin-title-center">Change User Password</h2>
        <div class="admin-btn-row">
            <a href="view_password_requests.php" class="admin-btn">View Password Requests</a>
            <a href="admin_dashboard.php" class="admin-btn" style="background: #6c757d;">Back to Dashboard</a>
        </div>
        </br>
        <form method="GET" class="admin-search-box">
            <input type="text" name="search" class="admin-search-input" placeholder="Search by name or email" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="admin-btn">Search</button>
        </form>
        <table class="admin-table">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Contact Number</th>
                <th>New Password</th>
                <th>Action</th>
            </tr>
            <?php while ($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['mobile']) ?></td>
                <td>
                    <form method="POST" class="admin-pw-form">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>">
                        <input type="text" name="new_password" class="admin-input" required placeholder="New password">
                </td>
                <td>
                        <button type="submit" class="admin-btn">Update</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <script>
        <?php if (!empty($alert)) {
            echo str_replace(array("\r", "\n"), '', $alert);
        } ?>
        </script>
    </div>
</body>
</html> 