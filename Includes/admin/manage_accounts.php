<?php
include('../dbconnect.php');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../unauthorized.php");
    exit();
}

$departments = $conn->query("SELECT * FROM departments");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Departments</title>
    <link rel="stylesheet" href="../../admin.css">
</head>
<body>
    <h2>Manage Departments</h2>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Area</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $departments->fetch_assoc()): ?>
        <tr>
            <td><?= $row['dept_name'] ?></td>
            <td><?= $row['contact'] ?></td>
            <td><?= $row['area'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <form method="POST" action="toggle_dept_status.php">
                    <input type="hidden" name="dept_id" value="<?= $row['dept_id'] ?>">
                    <button name="action" value="<?= $row['status'] === 'active' ? 'deactivate' : 'activate' ?>">
                        <?= $row['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                    </button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
