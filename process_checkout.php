<?php
session_start();

// Check if the checkout form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_method'])) {
    // Save the selected payment method to the session
    $_SESSION['payment_method'] = $_POST['payment_method'];
    header("Location: checkout.php");
    exit;
} else {
    // Handle the case where the form wasn't submitted correctly
    $_SESSION['error'] = 'Please select a payment method.';
    header("Location: cart.php"); // Redirect back to checkout page for correction
    exit;
}
?>
