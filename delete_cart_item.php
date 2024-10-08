<?php
session_start();

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Remove the product from the cart
    unset($_SESSION['cart'][$productId]);
    $_SESSION['message'] = 'Item removed from cart.';
}

// Redirect back to the cart page
header('Location: cart.php');
exit;
?>
