<?php
session_start();
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to home or login page
header("Location: home.php");
exit();
?>
