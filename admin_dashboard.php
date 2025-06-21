<?php
// admin_dashboard.php
session_start();
require_once 'Includes/dbconnect.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: unauthorized.php");
    exit();
}

// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);

// Handle user deletion
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    // First, delete reviews by this user
    $sql = "DELETE FROM reviews WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Then, delete complaints by this user
    $sql = "DELETE FROM complaints WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Now, delete the user
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Handle department deletion
if (isset($_POST['delete_department'])) {
    $dept_id = $_POST['dept_id'];

    // Start a transaction for data integrity
    $conn->begin_transaction();

    try {
        // 1. Update complaints linked to this department to set dept_id to NULL
        $sql_update_complaints = "UPDATE complaints SET dept_id = NULL WHERE dept_id = ?";
        $stmt_update = $conn->prepare($sql_update_complaints);
        $stmt_update->bind_param("i", $dept_id);
        $stmt_update->execute();
        $stmt_update->close();

        // 2. Now delete the department
        $sql_delete_department = "DELETE FROM departments WHERE dept_id = ?";
        $stmt_delete = $conn->prepare($sql_delete_department);
        $stmt_delete->bind_param("i", $dept_id);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Commit the transaction
        $conn->commit();

        $_SESSION['message'] = ['type' => 'success', 'text' => 'Department and associated complaints updated successfully.'];

    } catch (mysqli_sql_exception $exception) {
        // Rollback transaction on error
        $conn->rollback();
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'Error deleting department: ' . $exception->getMessage()];
    }

    // Redirect to prevent form resubmission
    header("Location: admin_dashboard.php");
    exit();
}

// Handle department update
if (isset($_POST['update_department'])) {
    $dept_id = $_POST['dept_id'];
    $dept_name = $_POST['dept_name'];
    $description = $_POST['description'];
    $contact_email = $_POST['contact_email'];
    $status = $_POST['status'];
    
    $sql = "UPDATE departments SET dept_name = ?, description = ?, contact_email = ?, status = ? WHERE dept_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $dept_name, $description, $contact_email, $status, $dept_id);
    $stmt->execute();
}

// Handle password change for department user
if (isset($_POST['change_password'])) {
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];

    // Validate and hash the new password
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ? WHERE user_id = ? AND role = 'department'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Password updated successfully
            $_SESSION['message'] = ['type' => 'success', 'text' => 'Department user password updated successfully.'];
        } else {
            // No rows affected (user not found or not a department user)
            $_SESSION['message'] = ['type' => 'warning', 'text' => 'Could not update password. User not found or not a department user.'];
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = ['type' => 'danger', 'text' => 'New password cannot be empty.'];
    }
    
    // Redirect to prevent form resubmission and show message
    header("Location: admin_dashboard.php");
    exit();
}

// Fetch all users with their details
$users_sql = "SELECT u.*, 
    (SELECT COUNT(*) FROM complaints WHERE user_id = u.user_id) as total_complaints,
    (SELECT COUNT(*) FROM complaints WHERE user_id = u.user_id AND status = 'Resolved') as resolved_complaints
    FROM users u ORDER BY u.created_at DESC";
$users_result = $conn->query($users_sql);

// Fetch all departments with their details
$dept_sql = "SELECT d.*, 
    (SELECT COUNT(*) FROM complaints WHERE dept_id = d.dept_id) as total_complaints,
    (SELECT COUNT(*) FROM complaints WHERE dept_id = d.dept_id AND status = 'Resolved') as resolved_complaints
    FROM departments d ORDER BY d.created_at DESC";
$dept_result = $conn->query($dept_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FixLanka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="home.php">
                <img src="uploads/logos/FIXLANKA_LOGO.png" alt="FixLanka Logo" height="40" class="me-2">
                <span>FixLanka</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_dashboard.php">Dashboard</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="create_department.php">Create Department</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="completed_complaints.php">View Completed Complaints</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Admin Dashboard</h2>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <div class="quick-action-card">
                <i class="fas fa-plus-circle"></i>
                <h3>Add Department</h3>
                <a href="add_department.php">Add New Department</a>
            </div>
            <div class="quick-action-card">
                <i class="fas fa-exclamation-circle"></i>
                <h3>Rejected Complaints</h3>
                <a href="manage_rejected_complaints.php">View Rejected Complaints</a>
            </div>
        </div>

        <!-- Users Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="mb-0">Manage Users</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>District</th>
                                <th>Role</th>
                                <th>Total Complaints</th>
                                <th>Resolved</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = $users_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if($user['profile_picture']): ?>
                                            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" class="profile-image me-2" alt="Profile">
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($user['name']); ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['mobile']); ?></td>
                                <td><?php echo htmlspecialchars($user['district']); ?></td>
                                <td>
                                    <span class="role-badge role-<?php echo strtolower($user['role']); ?>">
                                        <?php echo htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="stats-card">
                                        <div class="stat-value"><?php echo $user['total_complaints']; ?></div>
                                        <div class="stat-label">Total</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="stats-card">
                                        <div class="stat-value"><?php echo $user['resolved_complaints']; ?></div>
                                        <div class="stat-label">Resolved</div>
                                    </div>
                                </td>
                                <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if ($user['role'] === 'department'): ?>
                                            <button type="button" class="btn btn-secondary btn-sm change-password-btn" data-bs-toggle="modal" data-bs-target="#changePasswordModal" data-user-id="<?php echo $user['user_id']; ?>">Change Password</button>
                                        <?php endif; ?>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                            <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Departments Section -->
        <div class="card">
            <div class="card-header">
                <h3 class="mb-0">Manage Departments</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Department Name</th>
                                <th>Description</th>
                                <th>Contact Email</th>
                                <th>Total Complaints</th>
                                <th>Resolved</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($dept = $dept_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $dept['dept_id']; ?></td>
                                <td>
                                    <form method="POST" class="update-form">
                                        <input type="hidden" name="dept_id" value="<?php echo $dept['dept_id']; ?>">
                                        <input type="text" name="dept_name" value="<?php echo htmlspecialchars($dept['dept_name']); ?>" class="form-control">
                                </td>
                                <td>
                                        <input type="text" name="description" value="<?php echo htmlspecialchars($dept['description']); ?>" class="form-control">
                                </td>
                                <td>
                                        <input type="email" name="contact_email" value="<?php echo htmlspecialchars($dept['contact_email']); ?>" class="form-control">
                                </td>
                                <td>
                                    <div class="stats-card">
                                        <div class="stat-value"><?php echo $dept['total_complaints']; ?></div>
                                        <div class="stat-label">Total</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="stats-card">
                                        <div class="stat-value"><?php echo $dept['resolved_complaints']; ?></div>
                                        <div class="stat-label">Resolved</div>
                                    </div>
                                </td>
                                <td>
                                    <select name="status" class="form-control">
                                        <option value="active" <?php echo $dept['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo $dept['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </td>
                                <td><?php echo date('Y-m-d', strtotime($dept['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="submit" name="update_department" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                        <input type="hidden" name="dept_id" value="<?php echo $dept['dept_id']; ?>">
                                        <button type="submit" name="delete_department" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Change Department Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" id="modal-user-id">
                        <div class="mb-3">
                            <label for="new-password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new-password" name="new_password" required>
                        </div>
                         <div class="mb-3">
                            <label for="confirm-password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
                            <div class="invalid-feedback">Passwords do not match.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="change_password" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add confirmation for delete actions
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (this.querySelector('button[type="submit"]').classList.contains('btn-danger')) {
                    if (!confirm('Are you sure you want to delete this item?')) {
                        e.preventDefault();
                    }
                }
            });
        });

        // Handle change password modal
        const changePasswordModal = document.getElementById('changePasswordModal');
        changePasswordModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const modalUserIdInput = changePasswordModal.querySelector('#modal-user-id');
            modalUserIdInput.value = userId;
        });

        // Add password confirmation validation
        const passwordForm = changePasswordModal.querySelector('form');
        const newPasswordInput = passwordForm.querySelector('#new-password');
        const confirmPasswordInput = passwordForm.querySelector('#confirm-password');

        passwordForm.addEventListener('submit', function(event) {
            if (newPasswordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.classList.add('is-invalid');
                event.preventDefault(); // Prevent form submission
            } else {
                confirmPasswordInput.classList.remove('is-invalid');
            }
        });

         // Clear modal form when closed
         changePasswordModal.addEventListener('hidden.bs.modal', function () {
            passwordForm.reset();
            confirmPasswordInput.classList.remove('is-invalid');
        });

    </script>
</body>
</html>
