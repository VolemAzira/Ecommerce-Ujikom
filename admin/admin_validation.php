<?php
session_start();

// Check if the session variable for username is set and equals "admin"
if (!isset($_SESSION['username']) || $_SESSION['username'] !== "admin") {
    // Redirect to login page if not admin
    header("Location: ../login.php");
    exit;
}
?>