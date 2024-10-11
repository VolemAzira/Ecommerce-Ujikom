<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
function addToCart($productId, $name, $price, $qty) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['amount'] += $qty;
    } else {
        $_SESSION['cart'][$productId] = array(
            'name' => $name,
            'price' => $price,
            'amount' => $qty
        );
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $productPrice = $_POST['product_price'];
    $productQty = $_POST['product_qty'];  // Get the selected qty
    
    addToCart($productId, $productName, $productPrice, $productQty);
    
    header("Location: index.php");
    exit;
}
?>