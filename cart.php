<?php include 'session_validation.php'; ?>

<?php

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Calculate the total price
$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css -->
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .navbar-nav .nav-link {
        color: white !important;
    }
</style>

<body>
    <?php include 'header.php' ?>

    <main class="min-h-screen m-10 flex flex-col gap-5">
        <h2 class="text-3xl font-bold">Shopping Cart</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <form action="clear_cart.php" method="post">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">Clear Cart</button>
            </form>
            <table class="w-full text-left table-auto border border-black">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="p-2">Name</th>
                        <th>Amount</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th class="w-[5rem]"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $productId => $product): ?>
                        <?php
                        $itemTotal = $product['amount'] * $product['price'];
                        $totalPrice += $itemTotal;
                        ?>
                        <tr>
                            <td class="p-2"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['amount']); ?></td>
                            <td>Rp.<?php echo number_format($product['price']); ?></td>
                            <td>Rp.<?php echo number_format($itemTotal); ?></td>
                            <td>
                                <form action="delete_cart_item.php" method="post" class="m-2">
                                    <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="text-lg">
                        <th colspan="3" class="text-right">Total Price : &nbsp</th>
                        <th colspan="2">Rp.<?php echo number_format($totalPrice); ?></th>
                    </tr>
                </tfoot>
            </table>
            <form action="process_checkout.php" method="POST" class="flex gap-5 justify-end">
                <label class="border-2 rounded-xl py-2 px-4 font-medium">
                    <input type="radio" name="payment_method" value="Prepaid" required> Prepaid
                </label>
                <label class="border-2 rounded-xl py-2 px-4 font-medium">
                    <input type="radio" name="payment_method" value="Postpaid" required> Postpaid
                </label>
                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Proceed to Checkout</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </main>


    <?php include 'footer.php' ?>
</body>

</html>