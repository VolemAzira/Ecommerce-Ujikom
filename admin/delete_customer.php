<?php
include '../db.php'; // Adjust this path to your actual db.php file
session_start();
// Here, implement your access control checks
// For example, check if the user is logged in and has the correct privileges
// Check if the 'id' GET parameter is set and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare a statement for deleting the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // If the user was successfully deleted, redirect or inform the user
        $_SESSION['message'] = 'user deleted successfully.';
        header('Location: list_customer.php'); // Adjust this to where you want to redirect after deletion
        exit;
    } else {
        // Handle cases where the delete operation fails
        $_SESSION['error'] = 'Failed to delete user.';
        header('Location: list_customer.php'); // Adjust this as needed
        exit;
    }
} else {
    // If the ID is not set or not valid, redirect or display an error
    $_SESSION['error'] = 'Invalid user ID.';
    header('Location: list_customer.php'); // Adjust this as needed
    exit;
}
