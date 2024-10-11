<?php
include '../db.php'; // Adjust this path to your actual db.php file
session_start();
// Here, implement your access control checks
// For example, check if the user is logged in and has the correct privileges
// Check if the 'id' GET parameter is set and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $feedbackId = $_GET['id'];

    // Prepare a statement for deleting the feedback
    $stmt = $conn->prepare("DELETE FROM feedbacks WHERE id = ?");
    $stmt->bind_param("i", $feedbackId);

    if ($stmt->execute()) {
        // If the feedback was successfully deleted, redirect or inform the user
        $_SESSION['message'] = 'feedback deleted successfully.';
        header('Location: list_feedback.php'); // Adjust this to where you want to redirect after deletion
        exit;
    } else {
        // Handle cases where the delete operation fails
        $_SESSION['error'] = 'Failed to delete feedback.';
        header('Location: list_feedback.php'); // Adjust this as needed
        exit;
    }
} else {
    // If the ID is not set or not valid, redirect or display an error
    $_SESSION['error'] = 'Invalid feedback ID.';
    header('Location: list_feedback.php'); // Adjust this as needed
    exit;
}
