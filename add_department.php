<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/dbconnect.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

$msg = "";

// Check if uploads directory exists and is writable
$upload_dir = "uploads/";
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dept_name = $_POST['dept_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $description = $_POST['description'];

    if ($password !== $confirm_password) {
        $msg = "❌ Password and Confirm Password do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $logo_path = "";
        if (!empty($_FILES['logo']['name'])) {
            $logo_name = basename($_FILES['logo']['name']);
            $logo_tmp = $_FILES['logo']['tmp_name'];
            $logo_path = $upload_dir . $logo_name;

            if (!move_uploaded_file($logo_tmp, $logo_path)) {
                $msg = "❌ Error uploading logo file.";
            }
        }

        if (empty($msg)) {
            $sql = "INSERT INTO departments (dept_name, contact_email, password, description, logo, status) VALUES (?, ?, ?, ?, ?, 'active')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $dept_name, $email, $hashed_password, $description, $logo_path);

            if ($stmt->execute()) {
                $msg = "✅ Department added successfully!";
            } else {
                $msg = "❌ Error: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Department</title>
    <link rel="stylesheet" href="css/add_department.css">
</head>
<body>
    
    <form method="POST" enctype="multipart/form-data">

        <h2>Add New Department</h2>

        <label>Department Name:</label><br>
        <input type="text" name="dept_name" required><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br>

        <label>Description:</label><br>
        <textarea name="description" rows="4" cols="50"></textarea><br>

        <label>Logo (optional):</label><br>
        <input type="file" name="logo" accept="image/*"><br><br>

        <button type="submit">Add Department</button>
    </form>
    <p><?php echo $msg; ?></p>
</body>
</html>
