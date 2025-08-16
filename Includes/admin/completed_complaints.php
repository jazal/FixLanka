<?php
session_start();
include('../dbconnect.php');

// Check if user is logged in and is either admin or department
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'department')) {
    header("Location: ../unauthorized.php");
    exit();
}

$user_role = $_SESSION['role'];
$user_identifier = $_SESSION['user_id']; // This is user_id for admin/department user

// Build SQL query
$sql = "SELECT c.*, u_comp.name AS complainant_name, u_comp.email AS complainant_email, u_comp.mobile AS complainant_mobile 
        FROM complaints c
        LEFT JOIN users u_comp ON c.user_id = u_comp.user_id
        WHERE c.status = 'Resolved'";

$params = [];
$types = "";

// Filter by department for department users
if ($user_role === 'department') {
    // For department users, their user_id is the dept_id they manage
    $sql .= " AND c.dept_id = ?";
    $params[] = $user_identifier; // Use user_id directly as dept_id
    $types .= "i";
}

// Add search filters
$search_ref = $_GET['ref_number'] ?? '';
$search_date = $_GET['date'] ?? '';

if (!empty($search_ref)) {
    $sql .= " AND c.ref_number LIKE ?";
    $params[] = "%" . $search_ref . "%";
    $types .= "s";
}

if (!empty($search_date)) {
    $sql .= " AND DATE(c.created_at) = ?";
    $params[] = $search_date;
    $types .= "s";
}

$sql .= " ORDER BY c.created_at DESC";

$complaints = [];

// Execute query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $complaints[] = $row;
}
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completed Complaints - FixLanka</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <h2>Completed Complaints</h2>

        <div class="admin-content-container">
            <div class="admin-btn-row">
                <a href="admin_dashboard.php" class="admin-btn admin-btn-secondary">Back to Dashboard</a>
            </div>
        </div>

        <form method="GET" class="search-form">
            <input type="text" name="ref_number" placeholder="Search by Ref Number" value="<?= htmlspecialchars($search_ref) ?>">
            <input type="date" name="date" value="<?= htmlspecialchars($search_date) ?>">
            <button type="submit">Search</button>
            <button type="button" onclick="window.location.href='completed_complaints.php'">Clear Search</button>
        </form>

        <?php if ($user_role === 'department' && empty($complaints)): // Check if department user has no complaints found after filtering ?>
            <p>No completed complaints found for your department.</p>
        <?php elseif ($user_role === 'admin' && empty($complaints)): // Check if admin has no complaints found after filtering ?>
             <p>No completed complaints found.</p>
        <?php elseif (count($complaints) > 0): ?>
            <table class="complaints-table">
                <thead>
                    <tr>
                        <th>Ref Number</th>
                        <th>Title</th>
                        <th>Complainant</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($complaints as $complaint): ?>
                        <tr class="complaint-row" data-complaint='<?= json_encode($complaint) ?>'>
                            <td><?= htmlspecialchars($complaint['ref_number']) ?></td>
                            <td><?= htmlspecialchars($complaint['title']) ?></td>
                            <td><?= htmlspecialchars($complaint['complainant_name'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($complaint['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Complaint Details Modal -->
        <div id="detailsModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3>Complaint Details</h3>
                <div id="modal-body">
                    <!-- Details will be loaded here by JS -->
                </div>
            </div>
        </div>

    </div>

    <script>
        const modal = document.getElementById('detailsModal');
        const span = document.getElementsByClassName('close')[0];
        const modalBody = document.getElementById('modal-body');

        document.querySelectorAll('.complaint-row').forEach(row => {
            row.addEventListener('click', function() {
                const complaint = JSON.parse(this.dataset.complaint);
                let mediaHtml = '';
                if (complaint.media_path) {
                    // Adjust path for admin panel display
                    let displayPath = complaint.media_path;
                    
                    // If path starts with 'Includes/citizen/', adjust for admin folder location
                    if (displayPath.startsWith('Includes/citizen/')) {
                        displayPath = '../citizen/' + displayPath.replace('Includes/citizen/', '');
                    }
                    // If path is just 'uploads/filename', adjust for admin location
                    else if (displayPath.startsWith('uploads/')) {
                        displayPath = '../citizen/' + displayPath;
                    }
                    // If no path prefix, assume it's in citizen uploads folder
                    else if (displayPath.indexOf('/') === -1) {
                        displayPath = '../citizen/uploads/' + displayPath;
                    }
                    
                    const mediaExtension = complaint.media_path.split('.').pop().toLowerCase();
                    if (['jpg', 'jpeg', 'png', 'gif'].includes(mediaExtension)) {
                        mediaHtml = '<p><strong>Complaint Media:</strong></p><img src="' + displayPath + '" alt="Complaint Image" style="max-width: 100%; height: auto; cursor: pointer;" onclick="openImageModal(\'' + displayPath + '\')">';
                    } else if (['mp4', 'webm', 'ogg'].includes(mediaExtension)) {
                         mediaHtml = '<p><strong>Complaint Media:</strong></p><video controls style="max-width: 100%; height: auto;"><source src="' + displayPath + '" type="video/' + mediaExtension + '">Your browser does not support the video tag.</video>';
                    } else {
                         mediaHtml = '<p><strong>Complaint Media:</strong> <a href="' + displayPath + '" target="_blank">Download Media</a></p>';
                    }
                }

                 modalBody.innerHTML = `
                    <p><strong>Ref Number:</strong> ${complaint.ref_number}</p>
                    <p><strong>Title:</strong> ${complaint.title}</p>
                    <p><strong>Status:</strong> ${complaint.status}</p>
                    <p><strong>Description:</strong> ${complaint.description}</p>
                    <p><strong>Location:</strong> Lat: ${complaint.location_lat}, Lng: ${complaint.location_lng}</p>
                    <p><strong>Submitted:</strong> ${complaint.created_at}</p>
                    <p><strong>Resolved At:</strong> ${complaint.updated_at}</p>
                    ${complaint.rejection_reason ? '<p><strong>Rejection Reason:</strong> ' + complaint.rejection_reason + '</p>' : ''}
                    <p><strong>Complainant:</strong> ${complaint.complainant_name ?? 'N/A'} (Email: ${complaint.complainant_email ?? 'N/A'}, Mobile: ${complaint.complainant_mobile ?? 'N/A'})</p>
                    ${mediaHtml}
                `;
                modal.style.display = "block";
            });
        });

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html> 