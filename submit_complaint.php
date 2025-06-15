<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include('includes/dbconnect.php');

$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $dept_id = $_POST['department'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $media_path = "";
    if (!empty($_FILES['media']['name'])) {
        $file_name = basename($_FILES['media']['name']);
        $file_tmp = $_FILES['media']['tmp_name'];
        $media_path = "uploads/" . $file_name;
        move_uploaded_file($file_tmp, $media_path);
    }

    $sql = "INSERT INTO complaints (user_id, dept_id, title, description, location_lat, location_lng, media_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissdds", $user_id, $dept_id, $title, $description, $latitude, $longitude, $media_path);

    $msg = $stmt->execute()
        ? "✅ Complaint submitted successfully!"
        : "❌ Error: " . $conn->error;
}

$departments = $conn->query("SELECT * FROM departments WHERE status = 'active'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Complaint - FixLanka</title>
    <link rel="stylesheet" href="css/complaint.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    <h2>Submit a Complaint</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Issue Title:</label>
        <input type="text" name="title" required>

        <label>Select Department:</label>
        <select name="department" required>
            <option value="">-- Select --</option>
            <?php while ($row = $departments->fetch_assoc()): ?>
                <option value="<?= $row['dept_id'] ?>"><?= $row['dept_name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Select Location:</label>
        <button type="button" onclick="getLocation()">📍 Use My Current Location</button>
        <div id="map" style="height: 300px; margin-top: 10px;"></div>
        <input type="hidden" name="latitude" id="lat">
        <input type="hidden" name="longitude" id="lng">

        <label>Upload Photo/Video:</label>
        <input type="file" name="media" accept="image/*,video/*">

        <button type="submit">Submit Complaint</button>
    </form>

    <p><?= $msg ?></p>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([7.8731, 80.7718], 8);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let marker;
        map.on('click', function(e) {
            placeMarker(e.latlng.lat, e.latlng.lng);
        });

        function placeMarker(lat, lng) {
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            if (!marker) {
                marker = L.marker([lat, lng]).addTo(map);
            } else {
                marker.setLatLng([lat, lng]);
            }

            map.setView([lat, lng], 13);
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(pos) {
                    placeMarker(pos.coords.latitude, pos.coords.longitude);
                }, function() {
                    alert("Location access denied or unavailable.");
                });
            } else {
                alert("Your browser doesn't support location access.");
            }
        }
    </script>
</body>
</html>
