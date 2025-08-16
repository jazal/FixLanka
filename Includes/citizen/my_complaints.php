<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include('../dbconnect.php');

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
    <link rel="stylesheet" href="citizen.css">
    <style>
        /* Image Modal Styles */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            animation: fadeIn 0.3s ease-in-out;
        }

        .image-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-modal-content {
            position: relative;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 80vw;
            max-height: 80vh;
            animation: slideIn 0.3s ease-in-out;
        }

        .image-modal-image {
            width: 100%;
            height: auto;
            max-width: 600px;
            max-height: 500px;
            border-radius: 10px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }

        .image-modal-close {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }

        .image-modal-close:hover,
        .image-modal-close:focus {
            color: #000;
        }

        .proof-img {
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .proof-img:hover {
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: scale(0.7) translateY(-50px);
            }
            to { 
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
    </style>
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
                        <?php 
                        // Handle different path formats for backward compatibility
                        $display_path = $row['media_path'];
                        
                        // If path starts with 'Includes/citizen/', make it relative to current location
                        if (strpos($display_path, 'Includes/citizen/') === 0) {
                            $display_path = str_replace('Includes/citizen/', '', $display_path);
                        }
                        // If path is just 'uploads/filename', it's relative to current location
                        elseif (strpos($display_path, 'uploads/') === 0) {
                            // Path is already correct for current location
                        }
                        // If no path prefix, assume it's in uploads folder
                        elseif (strpos($display_path, '/') === false) {
                            $display_path = 'uploads/' . $display_path;
                        }
                        ?>
                        <img src="<?= htmlspecialchars($display_path) ?>" alt="proof" class="proof-img" onclick="openImageModal('<?= htmlspecialchars($display_path) ?>')"></p>
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

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <div class="image-modal-content">
            <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
            <img id="imageModalImage" class="image-modal-image" src="" alt="Complaint Image">
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>
