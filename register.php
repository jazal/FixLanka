<?php
include('includes/dbconnect.php');
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $district = $_POST['district'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Handle profile picture
    $pic_name = $_FILES['profile_pic']['name'];
    $pic_tmp = $_FILES['profile_pic']['tmp_name'];
    $upload_path = "uploads/" . basename($pic_name);
    move_uploaded_file($pic_tmp, $upload_path);

    // Default role = citizen
    $sql = "INSERT INTO users (name, email, mobile, district, password_hash, profile_picture, role)
            VALUES (?, ?, ?, ?, ?, ?, 'citizen')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $mobile, $district, $password, $upload_path);

    if ($stmt->execute()) {
        $msg = "Registration successful! You can now login.";
    } else {
        $msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FixLanka - Register</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <h2>Register - FixLanka</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Full Name:</label><br>
        <input type="text" name="name" required><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Mobile Number:</label><br>
        <input type="text" name="mobile"><br>

        <label>District:</label><br>
        <select name="district" required>
            <option value="">-- Select District --</option>
            <option value="Ampara">Ampara</option>
            <option value="Anuradhapura">Anuradhapura</option>
            <option value="Badulla">Badulla</option>
            <option value="Batticaloa">Batticaloa</option>
            <option value="Colombo">Colombo</option>
            <option value="Galle">Galle</option>
            <option value="Gampaha">Gampaha</option>
            <option value="Hambantota">Hambantota</option>
            <option value="Jaffna">Jaffna</option>
            <option value="Kalutara">Kalutara</option>
            <option value="Kandy">Kandy</option>
            <option value="Kegalle">Kegalle</option>
            <option value="Kilinochchi">Kilinochchi</option>
            <option value="Kurunegala">Kurunegala</option>
            <option value="Mannar">Mannar</option>
            <option value="Matale">Matale</option>
            <option value="Matara">Matara</option>
            <option value="Monaragala">Monaragala</option>
            <option value="Mullaitivu">Mullaitivu</option>
            <option value="Nuwara Eliya">Nuwara Eliya</option>
            <option value="Polonnaruwa">Polonnaruwa</option>
            <option value="Puttalam">Puttalam</option>
            <option value="Ratnapura">Ratnapura</option>
            <option value="Trincomalee">Trincomalee</option>
            <option value="Vavuniya">Vavuniya</option>
        </select><br>

        <label>Password:</label><br>
<input type="password" name="password" id="password" required><br>

<label>Confirm Password:</label><br>
<input type="password" id="confirm_password" required><br>

<input type="checkbox" onclick="togglePassword()"> Show Password<br><br>

        <label>Profile Picture:</label><br>
        <input type="file" name="profile_pic" accept="image/*" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p><?= $msg ?></p>

    <script>
function togglePassword() {
    const pass = document.getElementById("password");
    const confirm = document.getElementById("confirm_password");
    pass.type = pass.type === "password" ? "text" : "password";
    confirm.type = confirm.type === "password" ? "text" : "password";
}

document.querySelector("form").addEventListener("submit", function(e) {
    const pass = document.getElementById("password").value;
    const confirm = document.getElementById("confirm_password").value;
    if (pass !== confirm) {
        alert("Passwords do not match!");
        e.preventDefault(); // stop form submission
    }
});
</script>

</body>
</html>
