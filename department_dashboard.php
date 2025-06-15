<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'department') {
    header("Location: unauthorized.php");
    exit();
}
include('includes/dbconnect.php');

// Add PHPMailer imports
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function sendStatusUpdateEmail($to_email, $to_name, $complaint_title, $status, $ref_number, $rejection_reason = '') {
    $mail = new PHPMailer(true);
    
    try {
        // Enable debug output
        $mail->SMTPDebug = 3; // Enable verbose debug output
        $mail->Debugoutput = function($str, $level) {
            error_log("PHPMailer Debug: $str");
        };

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'Omar727221@gmail.com';
        $mail->Password = 'mwjn yzqg pjrk ayzp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        // Add DKIM and SPF headers
        $mail->addCustomHeader('X-Mailer', 'FixLanka Complaint System');
        $mail->addCustomHeader('X-Priority', '1');
        $mail->addCustomHeader('X-MSMail-Priority', 'High');
        $mail->addCustomHeader('Importance', 'High');

        // Additional SMTP options for debugging
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom('Omar727221@gmail.com', 'FixLanka System', false);
        $mail->addReplyTo('Omar727221@gmail.com', 'FixLanka Support');
        $mail->addAddress($to_email, $to_name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "FixLanka - Complaint Status Update: $ref_number";
        
        // Create a more professional email template
        $message = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;'>
            <div style='background-color: #004080; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;'>
                <h1 style='margin: 0;'>FixLanka</h1>
                <p style='margin: 5px 0 0 0;'>Complaint Status Update</p>
            </div>
            <div style='padding: 20px; background-color: #f9f9f9;'>
                <p>Dear $to_name,</p>
                <p>Your complaint has been updated with the following details:</p>
                <div style='background-color: white; padding: 15px; border-radius: 5px; margin: 15px 0;'>
                    <p><strong>Reference Number:</strong> $ref_number</p>
                    <p><strong>Title:</strong> $complaint_title</p>
                    <p><strong>New Status:</strong> <span style='color: " . 
                    ($status === 'Resolved' ? '#28a745' : 
                    ($status === 'Rejected' ? '#dc3545' : 
                    ($status === 'In Progress' ? '#ffc107' : '#004080'))) . 
                    ";'>$status</span></p>";
        
        if ($status === 'Rejected' && !empty($rejection_reason)) {
            $message .= "<p><strong>Rejection Reason:</strong><br>$rejection_reason</p>";
        }
        
        $message .= "
                </div>
                <p>You can track your complaint status by visiting our website and entering your reference number.</p>
                <p>Thank you for using FixLanka.</p>
            </div>
            <div style='text-align: center; padding: 20px; color: #666; font-size: 12px; border-top: 1px solid #eee;'>
                <p>This is an automated message. Please do not reply to this email.</p>
                <p>&copy; " . date('Y') . " FixLanka. All rights reserved.</p>
            </div>
        </div>";
        
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        // Log attempt to send email
        error_log("Attempting to send email to: $to_email for complaint: $ref_number");
        
        if($mail->send()) {
            error_log("Email sent successfully to $to_email for complaint $ref_number");
            return true;
        } else {
            error_log("Email sending failed for complaint $ref_number. Error: " . $mail->ErrorInfo);
            return false;
        }
    } catch (Exception $e) {
        error_log("Email sending failed for complaint $ref_number. Exception: " . $e->getMessage());
        error_log("Full error details: " . print_r($e, true));
        return false;
    }
}

// Add a test function to verify email configuration
function testEmailConfiguration() {
    try {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 3; // Increased debug level
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'Omar727221@gmail.com';
        $mail->Password = 'mwjn yzqg pjrk ayzp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Add DKIM and SPF headers
        $mail->addCustomHeader('X-Mailer', 'FixLanka Complaint System');
        $mail->addCustomHeader('X-Priority', '1');
        $mail->addCustomHeader('X-MSMail-Priority', 'High');
        $mail->addCustomHeader('Importance', 'High');
        
        $mail->setFrom('Omar727221@gmail.com', 'FixLanka System', false);
        $mail->addReplyTo('Omar727221@gmail.com', 'FixLanka Support');
        $mail->addAddress('Omar727221@gmail.com', 'Test User');
        
        $mail->isHTML(true);
        $mail->Subject = 'FixLanka - Test Email';
        $mail->Body = '<h1>Test Email</h1><p>This is a test email to verify the email configuration.</p>';
        
        if($mail->send()) {
            error_log("Test email sent successfully");
            return true;
        } else {
            error_log("Test email failed to send. Error: " . $mail->ErrorInfo);
            return false;
        }
    } catch (Exception $e) {
        error_log("Test email failed with exception: " . $e->getMessage());
        return false;
    }
}

// Test email configuration when page loads
if(isset($_GET['test_email'])) {
    if(testEmailConfiguration()) {
        echo "Test email sent successfully. Check your email and the error log for details.";
    } else {
        echo "Test email failed. Check the error log for details.";
    }
    exit;
}

$dept_id = $_SESSION['user_id']; // department's user_id

// Update status or reject complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = $_POST['complaint_id'];
    $action = $_POST['action'];

    if ($action === 'reject') {
        $reason = $_POST['rejection_reason'];
        $sql = "UPDATE complaints SET status='Rejected', rejection_reason=? WHERE complaint_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $reason, $complaint_id);
        $stmt->execute();
        
        // Send email notification for rejection
        $sql = "SELECT c.*, u.email, u.name FROM complaints c 
                JOIN users u ON c.user_id = u.user_id 
                WHERE c.complaint_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $complaint_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            sendStatusUpdateEmail($row['email'], $row['name'], $row['title'], 'Rejected', $row['ref_number'], $reason);
        }
    } elseif ($action === 'resolve') {
        $media_path = "";
        if (!empty($_FILES['proof']['name'])) {
            $filename = basename($_FILES['proof']['name']);
            $tmpname = $_FILES['proof']['tmp_name'];
            $media_path = "uploads/" . $filename;
            move_uploaded_file($tmpname, $media_path);

            $sql = "UPDATE complaints SET status='Resolved', media_path=? WHERE complaint_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $media_path, $complaint_id);
            $stmt->execute();
            
            // Send email notification for resolution
            $sql = "SELECT c.*, u.email, u.name FROM complaints c 
                    JOIN users u ON c.user_id = u.user_id 
                    WHERE c.complaint_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $complaint_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                sendStatusUpdateEmail($row['email'], $row['name'], $row['title'], 'Resolved', $row['ref_number']);
            }
        }
    } elseif ($action === 'in_progress') {
        $sql = "UPDATE complaints SET status='In Progress' WHERE complaint_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $complaint_id);
        $stmt->execute();
        
        // Send email notification for in progress
        $sql = "SELECT c.*, u.email, u.name FROM complaints c 
                JOIN users u ON c.user_id = u.user_id 
                WHERE c.complaint_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $complaint_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            sendStatusUpdateEmail($row['email'], $row['name'], $row['title'], 'In Progress', $row['ref_number']);
        }
    }
}

// Fetch complaints for this department with user details
$sql = "SELECT complaints.*, users.name AS complainant_name, users.email AS complainant_email, users.mobile AS complainant_mobile 
        FROM complaints 
        LEFT JOIN users ON complaints.user_id = users.user_id 
        WHERE complaints.dept_id = ? 
        ORDER BY complaints.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dept_id);
$stmt->execute();
$result = $stmt->get_result();

$complaints = [];
while ($row = $result->fetch_assoc()) {
    $complaints[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Department Dashboard - FixLanka</title>
    <link rel="stylesheet" href="css/department.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      crossorigin=""></script>
</head>
<body>
    <div class="container">
        <h2>Department Dashboard</h2>
        <p><a href="completed_complaints.php">View Completed Complaints</a></p>

        <?php if (count($complaints) > 0): ?>
            <?php foreach ($complaints as $row): ?>
                <div class="complaint-box">
                    <h3><?= htmlspecialchars($row['title']) ?> (<?= $row['ref_number'] ?>)</h3>
                    <p><strong>Status:</strong> <?= $row['status'] ?></p>
                    <p><?= $row['description'] ?></p>
                    <p><strong>Location:</strong> 
                    (Lat: <?= htmlspecialchars($row['location_lat']) ?>, Lng: <?= htmlspecialchars($row['location_lng']) ?>)
                    </p>
                    <div id="map<?= $row['complaint_id'] ?>" style="height: 200px; width: 100%; margin-bottom: 10px;"></div>
                    <p><strong>Submitted:</strong> <?= $row['created_at'] ?></p>

    <p><strong>Complainant:</strong> <?= htmlspecialchars($row['complainant_name']) ?> (<?= htmlspecialchars($row['complainant_email']) ?>, <?= htmlspecialchars($row['complainant_mobile']) ?>)</p>

                    <?php if (!empty($row['media_path'])): ?>
                        <p><strong>Complaint Media:</strong></p>
                        <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $row['media_path'])): ?>
                            <img src="<?= htmlspecialchars($row['media_path']) ?>" alt="Complaint Image" style="max-width: 300px;">
                        <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $row['media_path'])): ?>
                            <video controls style="max-width: 300px;">
                                <source src="<?= htmlspecialchars($row['media_path']) ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if ($row['status'] === 'Pending'): ?>
                        <form method="POST">
                            <input type="hidden" name="complaint_id" value="<?= $row['complaint_id'] ?>">
                            <button name="action" value="in_progress">Mark as In Progress</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($row['status'] === 'In Progress'): ?>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="complaint_id" value="<?= $row['complaint_id'] ?>">
                            <label>Upload Proof (Image/Video):</label>
                            <input type="file" name="proof" accept="image/*,video/*" required><br>
                            <button name="action" value="resolve">Mark as Complete</button>
                        </form>

                        <form method="POST">
                            <input type="hidden" name="complaint_id" value="<?= $row['complaint_id'] ?>">
                            <label>Rejection Reason:</label><br>
                            <textarea name="rejection_reason" required></textarea><br>
                            <button name="action" value="reject">Reject Complaint</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No complaints assigned to this department.</p>
        <?php endif; ?>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function() {
<?php foreach ($complaints as $row): ?>
<?php if (is_numeric($row['location_lat']) && is_numeric($row['location_lng'])): ?>
    console.log("Initializing map for complaint <?= $row['complaint_id'] ?>");
    var map<?= $row['complaint_id'] ?> = L.map('map<?= $row['complaint_id'] ?>').setView([<?= $row['location_lat'] ?>, <?= $row['location_lng'] ?>], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map<?= $row['complaint_id'] ?>);
    L.marker([<?= $row['location_lat'] ?>, <?= $row['location_lng'] ?>]).addTo(map<?= $row['complaint_id'] ?>);
<?php else: ?>
    document.getElementById('map<?= $row['complaint_id'] ?>').innerHTML = '<p>Location data not available</p>';
<?php endif; ?>
<?php endforeach; ?>
});
</script>
</body>
</html>
