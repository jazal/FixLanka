<?php
include('dbconnect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dept_id'], $_POST['action'])) {
    $dept_id = $_POST['dept_id'];
    $action = $_POST['action'] === 'deactivate' ? 'inactive' : 'active';

    $stmt = $conn->prepare("UPDATE departments SET status = ? WHERE dept_id = ?");
    $stmt->bind_param("si", $action, $dept_id);
    $stmt->execute();
}

header("Location: manage_accounts.php");
exit();
