<?php
session_start();
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session
header("Location: /agri-platform/index.php"); // Redirect to homepage
exit;
?>
