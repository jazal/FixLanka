<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

?>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../unauthorized.php");
    exit();
}

include('../dbconnect.php');
$sql = "SELECT * FROM complaints WHERE status = 'rejected'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Rejected Complaints</title>
    <link rel="stylesheet" href="admin.css">


    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


</head>
<body>
    <div class="container">
        <h2>Rejected Complaints</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Ref Number</th>
                        <th>Title</th>
                        <th>Reason</th>
                        <th>Submitted By</th>
                        <th>Media</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ref_number']) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= isset($row['rejection_reason']) ? htmlspecialchars($row['rejection_reason']) : 'N/A' ?></td>
                            <td><?= htmlspecialchars($row['user_id']) ?></td>

                            <!-- Media preview -->
                            <td>
                                <?php
                                if (!empty($row['media_path'])) {
                                    $path = htmlspecialchars($row['media_path']);
                                    $ext = pathinfo($path, PATHINFO_EXTENSION);
                                    if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) {
                                        echo "<img src='$path' alt='Image'>";
                                    } elseif (in_array(strtolower($ext), ['mp4', 'webm'])) {
                                        echo "<video controls><source src='$path' type='video/$ext'></video>";
                                    } else {
                                        echo "<a href='$path' target='_blank'>View File</a>";
                                    }
                                } else {
                                    echo 'No Media';
                                }
                                ?>
                            </td>

                            <!-- Leaflet map -->
                            <td>
                                <?php if (!empty($row['location_lat']) && !empty($row['location_lng'])): ?>
                                    <div id="map<?= $row['complaint_id'] ?>" class="map-preview"></div>
                                    <script>
                                        var map<?= $row['complaint_id'] ?> = L.map('map<?= $row['complaint_id'] ?>').setView([<?= $row['location_lat'] ?>, <?= $row['location_lng'] ?>], 15);
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            attribution: '&copy; OpenStreetMap contributors'
                                        }).addTo(map<?= $row['complaint_id'] ?>);
                                        L.marker([<?= $row['location_lat'] ?>, <?= $row['location_lng'] ?>]).addTo(map<?= $row['complaint_id'] ?>);
                                    </script>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>

                            <td><?= isset($row['created_at']) ? htmlspecialchars($row['created_at']) : 'N/A' ?></td>
                            <td>
                                <a href="reassign_complaint.php?id=<?= $row['complaint_id'] ?>" class="btn reassign">Reassign</a>
                                <a href="delete_complaint.php?id=<?= $row['complaint_id'] ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this complaint?');">Delete</a>
                            </td>
                        </tr>   
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No rejected complaints found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
