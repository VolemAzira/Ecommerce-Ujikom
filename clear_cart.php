<?php
session_start();

// Clear the cart
$_SESSION['cart'] = array();
$_SESSION['message'] = 'Cart cleared.';

// Redirect back to the cart page
header('Location: cart.php');
exit;
?>
