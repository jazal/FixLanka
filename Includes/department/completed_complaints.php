<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'department') {
    header("Location: ../unauthorized.php");
    exit();
}
include('../dbconnect.php');

$user_id = $_SESSION['user_id'];

// Handle complaint deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_complaint'])) {
    $complaint_id = $_POST['complaint_id'];
    
    // First, get the complaint details to check if it belongs to this department and get media path
    $check_sql = "SELECT media_path FROM complaints WHERE complaint_id = ? AND dept_id = ? AND status = 'Resolved'";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $complaint_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $complaint_data = $check_result->fetch_assoc();
        
        // Delete associated media file if it exists
        if (!empty($complaint_data['media_path'])) {
            $media_path = $complaint_data['media_path'];
            
            // Handle different path formats
            if (strpos($media_path, 'Includes/department/') === 0) {
                $file_path = str_replace('Includes/department/', '', $media_path);
            } else {
                $file_path = $media_path;
            }
            
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        // Delete the complaint from database
        $delete_sql = "DELETE FROM complaints WHERE complaint_id = ? AND dept_id = ? AND status = 'Resolved'";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $complaint_id, $user_id);
        
        if ($delete_stmt->execute()) {
            $success_message = "Complaint deleted successfully.";
        } else {
            $error_message = "Error deleting complaint.";
        }
    } else {
        $error_message = "Complaint not found or you don't have permission to delete it.";
    }
}

// Get department info
$dept_sql = "SELECT dept_name FROM departments WHERE dept_id = ?";
$dept_stmt = $conn->prepare($dept_sql);
$dept_stmt->bind_param("i", $user_id);
$dept_stmt->execute();
$dept_result = $dept_stmt->get_result();
$department = $dept_result->fetch_assoc();
$dept_name = $department['dept_name'] ?? 'Department';

// Build SQL query for completed/resolved complaints
$sql = "SELECT c.*, u.name AS complainant_name, u.email AS complainant_email, u.mobile AS complainant_mobile 
        FROM complaints c
        LEFT JOIN users u ON c.user_id = u.user_id
        WHERE c.dept_id = ? AND c.status = 'Resolved'";

$params = [$user_id];
$types = "i";

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

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$complaints = [];
while ($row = $result->fetch_assoc()) {
    $complaints[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Completed Complaints - <?= htmlspecialchars($dept_name) ?></title>
    <link rel="stylesheet" href="department.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <div class="container">
        <a href="department_dashboard.php" class="back-btn">
            ← Back to Dashboard
        </a>

        <div class="header-section">
            <div style="text-align: center;">
                <h1>Completed Complaints</h1>
                <h2><?= htmlspecialchars($dept_name) ?></h2>
            </div>
        </div>



        <!-- Search Section -->
        <div class="search-section">
            <h3>Search & Filter</h3>
            <form method="GET" class="search-form">
                <div class="form-group">
                    <label for="ref_number">Reference Number</label>
                    <input type="text" id="ref_number" name="ref_number" value="<?= htmlspecialchars($search_ref) ?>" placeholder="Enter reference number">
                </div>
                <div class="form-group">
                    <label for="date">Submission Date</label>
                    <input type="date" id="date" name="date" value="<?= htmlspecialchars($search_date) ?>">
                </div>
                <button type="submit" class="search-btn">Search</button>
                <a href="completed_complaints.php" class="clear-btn">Clear</a>
            </form>
        </div>

        <!-- Statistics -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-number"><?= count($complaints) ?></div>
                <div class="stat-label">Total Resolved</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php
                    $thisMonth = 0;
                    foreach ($complaints as $complaint) {
                        if (date('Y-m', strtotime($complaint['created_at'])) === date('Y-m')) {
                            $thisMonth++;
                        }
                    }
                    echo $thisMonth;
                    ?>
                </div>
                <div class="stat-label">This Month</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">
                    <?php
                    $thisWeek = 0;
                    $weekStart = date('Y-m-d', strtotime('monday this week'));
                    foreach ($complaints as $complaint) {
                        if (date('Y-m-d', strtotime($complaint['created_at'])) >= $weekStart) {
                            $thisWeek++;
                        }
                    }
                    echo $thisWeek;
                    ?>
                </div>
                <div class="stat-label">This Week</div>
            </div>
        </div>

        <!-- Complaints Grid -->
        <?php if (count($complaints) > 0): ?>
            <div class="complaints-grid">
                <?php foreach ($complaints as $complaint): ?>
                    <div class="complaint-card">
                        <div class="complaint-header">
                            <h3 class="complaint-title"><?= htmlspecialchars($complaint['title']) ?></h3>
                            <span class="complaint-ref"><?= htmlspecialchars($complaint['ref_number']) ?></span>
                        </div>

                        <div class="complaint-info">
                            <div class="info-row">
                                <span class="info-label">Complainant:</span>
                                <span class="info-value"><?= htmlspecialchars($complaint['complainant_name']) ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email:</span>
                                <span class="info-value"><?= htmlspecialchars($complaint['complainant_email']) ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Mobile:</span>
                                <span class="info-value"><?= htmlspecialchars($complaint['complainant_mobile']) ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Submitted:</span>
                                <span class="info-value"><?= date('M d, Y H:i', strtotime($complaint['created_at'])) ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Status:</span>
                                <span class="info-value">✅ <?= htmlspecialchars($complaint['status']) ?></span>
                            </div>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Description:</span>
                        </div>
                        <p style="margin-bottom: 1rem; color: #555;"><?= htmlspecialchars($complaint['description']) ?></p>

                        <?php if (!empty($complaint['media_path'])): ?>
                            <div class="complaint-media">
                                <strong>Complaint Media:</strong><br>
                                <?php 
                                // Adjust path for completed complaints page display
                                $display_path = $complaint['media_path'];
                                
                                // If path starts with 'Includes/department/', it's a resolved complaint proof - use relative path
                                if (strpos($display_path, 'Includes/department/') === 0) {
                                    $display_path = str_replace('Includes/department/', '', $display_path);
                                }
                                // If path starts with 'Includes/citizen/', adjust for department folder location
                                elseif (strpos($display_path, 'Includes/citizen/') === 0) {
                                    $display_path = '../citizen/' . str_replace('Includes/citizen/', '', $display_path);
                                }
                                // If path is just 'uploads/filename', adjust for department location
                                elseif (strpos($display_path, 'uploads/') === 0) {
                                    $display_path = '../citizen/' . $display_path;
                                }
                                // If no path prefix, assume it's in citizen uploads folder
                                elseif (strpos($display_path, '/') === false) {
                                    $display_path = '../citizen/uploads/' . $display_path;
                                }
                                ?>
                                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $complaint['media_path'])): ?>
                                    <img src="<?= htmlspecialchars($display_path) ?>" alt="Complaint Image" class="media-image" onclick="openImageModal('<?= htmlspecialchars($display_path) ?>')">
                                <?php elseif (preg_match('/\.(mp4|webm|ogg)$/i', $complaint['media_path'])): ?>
                                    <video controls style="max-width: 100%; max-height: 200px;">
                                        <source src="<?= htmlspecialchars($display_path) ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($complaint['location_lat']) && !empty($complaint['location_lng'])): ?>
                            <div id="map<?= $complaint['complaint_id'] ?>" class="complaint-map"></div>
                        <?php endif; ?>

                        <!-- Delete Button -->
                        <div style="margin-top: 1rem; text-align: right;">
                            <button type="button" class="delete-btn" onclick="confirmDelete(<?= $complaint['complaint_id'] ?>, '<?= htmlspecialchars($complaint['ref_number']) ?>')">
                                🗑️ Delete Complaint
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-complaints">
                <h3>No Completed Complaints Found</h3>
                <p>
                    <?php if (!empty($search_ref) || !empty($search_date)): ?>
                        No completed complaints match your search criteria.
                    <?php else: ?>
                        No complaints have been completed yet.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <div class="image-modal-content">
            <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
            <img id="imageModalImage" class="image-modal-image" src="" alt="Complaint Image">
        </div>
    </div>

    <script>
        // Show success/error messages with SweetAlert
        <?php if (isset($success_message)): ?>
        window.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: '<?= addslashes($success_message) ?>',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#00bfff'
            });
        });
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
        window.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                text: '<?= addslashes($error_message) ?>',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        });
        <?php endif; ?>

        // Initialize maps
        document.addEventListener('DOMContentLoaded', function() {
            <?php foreach ($complaints as $complaint): ?>
                <?php if (!empty($complaint['location_lat']) && !empty($complaint['location_lng'])): ?>
                    const map<?= $complaint['complaint_id'] ?> = L.map('map<?= $complaint['complaint_id'] ?>').setView([<?= $complaint['location_lat'] ?>, <?= $complaint['location_lng'] ?>], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map<?= $complaint['complaint_id'] ?>);
                    L.marker([<?= $complaint['location_lat'] ?>, <?= $complaint['location_lng'] ?>]).addTo(map<?= $complaint['complaint_id'] ?>);
                <?php endif; ?>
            <?php endforeach; ?>
        });

        // Image Modal Functions
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('imageModalImage');
            
            modalImage.src = imageSrc;
            modal.classList.add('show');
            
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.remove('show');
            
            // Restore body scrolling
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });

        // Delete confirmation with SweetAlert
        function confirmDelete(complaintId, refNumber) {
            Swal.fire({
                title: 'Delete Complaint?',
                html: `Are you sure you want to delete complaint <strong>${refNumber}</strong>?<br><br><small>This action cannot be undone and will permanently remove the complaint and its associated files.</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete It!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete the complaint.',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Create and submit hidden form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';
                    
                    const complaintIdInput = document.createElement('input');
                    complaintIdInput.type = 'hidden';
                    complaintIdInput.name = 'complaint_id';
                    complaintIdInput.value = complaintId;
                    
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'delete_complaint';
                    deleteInput.value = '1';
                    
                    form.appendChild(complaintIdInput);
                    form.appendChild(deleteInput);
                    document.body.appendChild(form);
                    
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
