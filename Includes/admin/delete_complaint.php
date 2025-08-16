<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('../dbconnect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../unauthorized.php");
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $complaint_id = intval($_GET['id']);

    $sql = "DELETE FROM complaints WHERE complaint_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $complaint_id);

    if ($stmt->execute()) {
        header("Location: manage_rejected_complaints.php");
        exit();
    } else {
        echo "Error deleting complaint: " . $stmt->error;
    }
} else {
    echo "Invalid complaint ID.";
}
?>
