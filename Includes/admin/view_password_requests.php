<?php
session_start();
include('../dbconnect.php');
$alert = "";

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../unauthorized.php");
    exit();
}

// Ensure 'read' column exists (run this only once, then comment out)
// $conn->query("ALTER TABLE password_reset_requests ADD COLUMN `read` TINYINT(1) DEFAULT 0");

// Handle delete
if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $conn->query("DELETE FROM password_reset_requests WHERE id=$delete_id");
    $alert = "Swal.fire('Deleted', 'Request deleted successfully.', 'success');";
}

// Handle mark as read
if (isset($_POST['read_id'])) {
    $read_id = intval($_POST['read_id']);
    $conn->query("UPDATE password_reset_requests SET `read`=1 WHERE id=$read_id");
    $alert = "Swal.fire('Marked as Read', 'Request marked as read.', 'success');";
}

// Handle search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where = '';
if ($search !== '') {
    $where = "WHERE (r.username LIKE '%$search%' OR r.email LIKE '%$search%')";
}

// Fetch all requests
$result = $conn->query("SELECT r.id, r.username, r.email, r.description, r.requested_password, r.created_at, r.`read`, u.name FROM password_reset_requests r LEFT JOIN users u ON r.user_id = u.user_id $where ORDER BY r.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Password Reset Requests - Admin</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .unread-row { background: #fffbe6 !important; }
    </style>
</head>
<body>
    <div class="admin-content-container">
        <h2 class="admin-title">Password Reset Requests</h2>
        <div class="admin-btn-row">
            <a href="admin_dashboard.php" class="admin-btn admin-btn-secondary">Back to Admin Dashboard</a>
            <a href="change_user_password.php" class="admin-btn">Update Password Panel</a>
        </div>
        <form method="GET" class="admin-search-box">
            <input type="text" name="search" class="admin-search-input" placeholder="Search by username or email" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="admin-btn">Search</button>
        </form>
        <table class="admin-table">
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Description</th>
                <th>Requested Password</th>
                <th>Requested At</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="<?= $row['read'] ? '' : 'unread-row' ?>">
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                <td><?= htmlspecialchars($row['requested_password']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= $row['read'] ? '<span style="color:green;">Read</span>' : '<span style="color:#b8860b;">Unread</span>' ?></td>
                <td>
                    <?php if (!$row['read']): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="read_id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit" class="admin-btn" style="background:#28a745;">Mark as Read</button>
                    </form>
                    <?php endif; ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit" class="admin-btn" onclick="return confirm('Are you sure you want to delete this request?');" style="background:#dc3545;">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <script>
        <?php if (!empty($alert)) echo str_replace(array("\r", "\n"), '', $alert); ?>
        </script>
    </div>
</body>
</html> 