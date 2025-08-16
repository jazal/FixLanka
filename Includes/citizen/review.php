<?php
session_start();
include '../dbconnect.php';

$message = "";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];

    // Verify user_id exists in users table
    $user_check_stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $user_check_stmt->bind_param("i", $user_id);
    $user_check_stmt->execute();
    $user_check_result = $user_check_stmt->get_result();

    if ($user_check_result->num_rows === 0) {
        $message = "Invalid user. Please log in again.";
        $user_check_stmt->close();
    } else {
        $user_check_stmt->close();

        $ref_number = trim($_POST['ref_number']);
        $review_text = trim($_POST['review_text']);

        // Validate inputs
        if (empty($ref_number) || empty($review_text) || !isset($_FILES['before_picture']) || !isset($_FILES['after_picture'])) {
            $message = "Please fill in all required fields and upload both pictures.";
        } else {
            // Handle file uploads
            $upload_dir = 'uploads/reviews/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $before_file = $_FILES['before_picture'];
            $after_file = $_FILES['after_picture'];

            $before_path = $upload_dir . basename($before_file['name']);
            $after_path = $upload_dir . basename($after_file['name']);

            $upload_ok = true;
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($before_file['type'], $allowed_types) || !in_array($after_file['type'], $allowed_types)) {
                $message = "Only JPG, PNG, and GIF files are allowed.";
                $upload_ok = false;
            }

            if ($upload_ok) {
                if (move_uploaded_file($before_file['tmp_name'], $before_path) && move_uploaded_file($after_file['tmp_name'], $after_path)) {
                    // Insert into database
                    $stmt = $conn->prepare("INSERT INTO reviews (user_id, ref_number, before_image, after_image, review_text) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("issss", $user_id, $ref_number, $before_path, $after_path, $review_text);

                    if ($stmt->execute()) {
                        $message = "Review submitted successfully!";
                    } else {
                        $message = "Error submitting review: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $message = "Error uploading files.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Update Your Reviews - FixLanka</title>
  <link rel="stylesheet" href="citizen.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="review-container">
    <h1>UPDATE YOUR REVIEWS</h1>
    <form method="POST" enctype="multipart/form-data" class="review-form">
      <label for="ref_number">Complain Ref.No</label>
      <input type="text" id="ref_number" name="ref_number" required />

      <div class="pictures">
        <div class="picture-upload">
          <label for="before_picture">Before picture</label>
          <label class="custom-file-label">
            <svg viewBox="0 0 20 20"><path d="M16.88 9.94a1 1 0 0 0-1.41 0l-2.47 2.47V4a1 1 0 1 0-2 0v8.41l-2.47-2.47a1 1 0 1 0-1.41 1.41l4.24 4.24a1 1 0 0 0 1.41 0l4.24-4.24a1 1 0 0 0 0-1.41z"/></svg>
            <span>Select Before Image</span>
            <input type="file" id="before_picture" name="before_picture" accept="image/*" required onchange="showFileName(this, 'before-file-name')" />
          </label>
          <span class="selected-file" id="before-file-name"></span>
        </div>
        <div class="picture-upload">
          <label for="after_picture">After picture</label>
          <label class="custom-file-label">
            <svg viewBox="0 0 20 20"><path d="M16.88 9.94a1 1 0 0 0-1.41 0l-2.47 2.47V4a1 1 0 1 0-2 0v8.41l-2.47-2.47a1 1 0 1 0-1.41 1.41l4.24 4.24a1 1 0 0 0 1.41 0l4.24-4.24a1 1 0 0 0 0-1.41z"/></svg>
            <span>Select After Image</span>
            <input type="file" id="after_picture" name="after_picture" accept="image/*" required onchange="showFileName(this, 'after-file-name')" />
          </label>
          <span class="selected-file" id="after-file-name"></span>
        </div>
      </div>

      <label for="review_text">Input your Review</label>
      <textarea id="review_text" name="review_text" rows="4" required></textarea>

      <button type="submit" class="submit-btn">Submit</button>
      <button type="button" class="home-btn" onclick="window.location.href='home.php'">Return to Home</button>
    </form>
  </div>
  <?php if ($message): ?>
    <script>
      Swal.fire({
        icon: '<?php echo (strpos($message, "successfully") !== false) ? "success" : "error"; ?>',
        title: '<?php echo (strpos($message, "successfully") !== false) ? "Success" : "Error"; ?>',
        text: '<?php echo htmlspecialchars($message); ?>',
        confirmButtonText: 'OK'
      });
    </script>
  <?php endif; ?>
  <script>
  function showFileName(input, spanId) {
    const span = document.getElementById(spanId);
    if (input.files && input.files[0]) {
      span.textContent = input.files[0].name;
    } else {
      span.textContent = '';
    }
  }
  </script>
</body>
</html>
