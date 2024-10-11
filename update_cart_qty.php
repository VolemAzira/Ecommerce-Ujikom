<?php
session_start();

if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $productId = $_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$productId])) {
        if ($action == 'increase') {
            $_SESSION['cart'][$productId]['amount'] += 1;
        } elseif ($action == 'decrease' && $_SESSION['cart'][$productId]['amount'] > 1) {
            $_SESSION['cart'][$productId]['amount'] -= 1;
        }
    }
}

header("Location: cart.php");
exit();
