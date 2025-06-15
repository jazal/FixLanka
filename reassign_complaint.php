<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


include('includes/dbconnect.php');

// Access check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

// Validate complaint ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid complaint ID.";
    exit();
}

$complaint_id = intval($_GET['id']);

// Get complaint data
$sql = "SELECT * FROM complaints WHERE complaint_id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $complaint_id);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();

if (!$complaint) {
    echo "Complaint not found.";
    exit();
}

// Debug output for complaint data
// echo "<pre>" . print_r($complaint, true) . "</pre>";

  
// Get departments
$dept_sql = "SELECT * FROM departments WHERE status = 'active'";
$dept_result = $conn->query($dept_sql);
if ($dept_result === false) {
    die("Department query failed: " . $conn->error);
}
if ($dept_result->num_rows == 0) {
    echo "No departments available.";
    exit();
}

  
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_dept = intval($_POST['department']);

    $update_sql = "UPDATE complaints SET dept_id = ?, status = 'pending', rejection_reason = NULL WHERE complaint_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    if ($update_stmt === false) {
        die("Update prepare failed: " . $conn->error);
    }
    $update_stmt->bind_param("ii", $new_dept, $complaint_id);

    if ($update_stmt->execute()) {
        header("Location: manage_rejected_complaints.php");
        exit();
    } else {
        echo "Error updating complaint: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reassign Complaint</title>
    <link rel="stylesheet" href="css/reassign_delete.css">
</head>
<body>
    <div class="container">
        <h2>Reassign Complaint: <?= htmlspecialchars($complaint['ref_number']) ?></h2>
        <form method="post">
            <label>Select New Department:</label><br>
            <select name="department" required>
                <option value="">-- Select Department --</option>
                <?php while ($dept = $dept_result->fetch_assoc()): ?>
                    <option value="<?= $dept['dept_id'] ?>"><?= htmlspecialchars($dept['dept_name']) ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <button type="submit" class="btn reassign">Reassign Complaint</button>
        </form>
        <br>
        <a href="manage_rejected_complaints.php" class="btn back">Back</a>
    </div>
</body>
</html>
