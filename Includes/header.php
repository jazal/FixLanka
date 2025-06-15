<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                <?php if(isset($_SESSION['role'])): ?>
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'admin_dashboard.php' ? 'active' : ''; ?>" href="admin_dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'manage_accounts.php' ? 'active' : ''; ?>" href="manage_accounts.php">Manage Accounts</a>
                        </li>
                    <?php elseif($_SESSION['role'] === 'department'): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'department_dashboard.php' ? 'active' : ''; ?>" href="department_dashboard.php">Dashboard</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'home.php' ? 'active' : ''; ?>" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'submit_complaint.php' ? 'active' : ''; ?>" href="submit_complaint.php">Submit Complaint</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $current_page === 'my_complaints.php' ? 'active' : ''; ?>" href="my_complaints.php">My Complaints</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'login.php' ? 'active' : ''; ?>" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'register.php' ? 'active' : ''; ?>" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar-brand {
    font-weight: 600;
    font-size: 1.2rem;
}

.navbar-brand img {
    transition: transform 0.3s ease;
}

.navbar-brand:hover img {
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .navbar-brand img {
        height: 30px;
    }
    
    .navbar-brand span {
        font-size: 1rem;
    }
}
</style> 