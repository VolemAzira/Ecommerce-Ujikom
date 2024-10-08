<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function addToCart($productId, $name, $price) {
    // Check if the cart is already initialized
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // Increase the amount
        $_SESSION['cart'][$productId]['amount'] += 1;
    } else {
        // Add the product to the cart
        $_SESSION['cart'][$productId] = array(
            'name' => $name,
            'price' => $price,
            'amount' => 1
        );
    }
}

// Check for a POST request to add a product
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    
    // Call the function to add the product
    addToCart($productId, $productName, $productPrice);

    // Redirect back to the products page or to the cart page
    header("Location: index.php"); // Adjust as necessary
    exit;
}
?>
