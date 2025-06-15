<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include('includes/dbconnect.php');

$user_id = $_SESSION['user_id'];
$sql = "SELECT c.*, d.dept_name FROM complaints c 
        JOIN departments d ON c.dept_id = d.dept_id
        WHERE c.user_id = ? ORDER BY c.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Complaints - FixLanka</title>
    <link rel="stylesheet" href="css/my_complaints.css">
</head>
<body>
    <h2>My Submitted Complaints</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="complaints-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="complaint-card">
                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                    <p><strong>Department:</strong> <?= htmlspecialchars($row['dept_name']) ?></p>
                    <p><strong>Status:</strong> <span class="status <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                        <?= $row['status'] ?>
                    </span></p>
                    <p><strong>Reference ID:</strong> <?= $row['ref_number'] ?></p>
                    <p><strong>Submitted on:</strong> <?= $row['created_at'] ?></p>
                    <?php if (!empty($row['media_path'])): ?>
                        <p><strong>Proof:</strong><br>
                        <img src="<?= $row['media_path'] ?>" alt="proof" class="proof-img"></p>
                    <?php endif; ?>
                    <?php if ($row['status'] === 'Rejected'): ?>
                        <p><strong>Rejection Reason:</strong> <?= htmlspecialchars($row['rejection_reason']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No complaints submitted yet.</p>
    <?php endif; ?>
</body>
</html>
