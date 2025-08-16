<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
include('../dbconnect.php');

// Check if user is logged in and is a citizen
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'citizen') {
    header("Location: ../unauthorized.php");
    exit();
}


$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $dept_id = $_POST['department'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    
    // Validate that department is selected and exists
    if (empty($dept_id)) {
        $msg = "❌ Please select a department.";
    } else {
        // Check if the department exists and is active
        $dept_check = $conn->prepare("SELECT dept_id FROM departments WHERE dept_id = ? AND status = 'active'");
        $dept_check->bind_param("i", $dept_id);
        $dept_check->execute();
        $dept_result = $dept_check->get_result();
        
        if ($dept_result->num_rows === 0) {
            $msg = "❌ Invalid department selected. Please choose a valid department.";
        } else {

    $media_path = "";
    if (!empty($_FILES['media']['name'])) {
        $file_name = basename($_FILES['media']['name']);
        $file_tmp = $_FILES['media']['tmp_name'];
        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        // Store full web-accessible path for proper display
        $media_path = "Includes/citizen/uploads/" . $file_name;
        move_uploaded_file($file_tmp, $upload_dir . $file_name);
    }

    $sql = "INSERT INTO complaints (user_id, dept_id, title, description, location_lat, location_lng, media_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissdds", $user_id, $dept_id, $title, $description, $latitude, $longitude, $media_path);

    if ($stmt->execute()) {
        $msg = "✅ Complaint submitted successfully!";
    } else {
        // More detailed error message for debugging
        $error_details = "Database Error: " . $conn->error;
        $error_details .= "<br>Department ID: " . $dept_id;
        $error_details .= "<br>User ID: " . $user_id;
        $msg = "❌ Error submitting complaint.<br>" . $error_details;
    }
        }
    }
}

$departments = $conn->query("SELECT * FROM departments WHERE status = 'active'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Submit Complaint - FixLanka</title>
    <link rel="stylesheet" href="citizen.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    <main class="submit-complaint-container">
        <h2 class="complaint-title">Report the Complain Here</h2>
        <form method="POST" enctype="multipart/form-data" class="complaint-form" novalidate>
            
            <label for="title">Complain Title</label>
            <input type="text" id="title" name="title" placeholder="Enter title..." required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" placeholder="Describe the issue..." required></textarea>

            <label for="media">Upload Photo / Video</label>
            <input type="file" id="media" name="media" accept="image/*,video/*">

            <label for="department">Select Department</label>
            <select name="department" id="department" required>
                <option value="">-- Select --</option>
                <?php while ($row = $departments->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['dept_id']) ?>">
                        <?= htmlspecialchars($row['dept_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Location</label>
            <div id="map" class="map-placeholder"></div>
            <input type="hidden" name="latitude" id="lat">
            <input type="hidden" name="longitude" id="lng">

            <button type="button" class="location-btn" onclick="getLocation()">Use My Current Location</button>
            <button type="submit" class="submit-btn">Submit</button>
            <button type="button" class="back-btn" onclick="window.location.href='../../home.php'">Back To Home</button>
        </form>
        <p class="message"><?= htmlspecialchars($msg) ?></p>
    </main>

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
                    alert("Unable to retrieve your location.");
                });
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }
        
        // Form validation before submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const department = document.getElementById('department').value;
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            
            // Check if department is selected
            if (!department || department === '') {
                alert('❌ Please select a department before submitting.');
                e.preventDefault();
                return false;
            }
            
            // Check if title is provided
            if (!title) {
                alert('❌ Please enter a complaint title.');
                e.preventDefault();
                return false;
            }
            
            // Check if description is provided
            if (!description) {
                alert('❌ Please enter a description for your complaint.');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>
