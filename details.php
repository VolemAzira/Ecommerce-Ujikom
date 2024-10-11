<?php include 'session_validation.php'; ?>
<?php
include 'db.php';

// Check if the 'id' GET parameter is set
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = intval($_GET['id']);

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $productDetails = $result->fetch_assoc();
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid product ID.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'header.php' ?>

    <main class="min-h-screen m-10 flex flex-col gap-5 items-center">
        <section class="flex gap-10 mt-[10rem]">
            <!-- Check if the product details are set before displaying -->
            <?php if (!empty($productDetails)): ?>
                <img src="<?php
                            $str = substr($productDetails['image'], 1);
                            echo $str;
                            ?>" 
                    alt="<?php echo $productDetails['name']; ?>" class="w-[20rem] h-[12rem] rounded-xl shadow-xl">
                <div class="flex flex-col gap-5">
                    <div>
                        <div class="flex gap-2 items-end">
                            <h1 class="text-4xl font-bold"><?php echo $productDetails['name']; ?></h1>
                            <p><?php echo $productDetails['category']; ?></p>
                        </div>
                        <h3 class="text-xl text-green-500">Rp.<?php echo $productDetails['price']; ?></h3>
                    </div>
                    <p><?php echo $productDetails['description']; ?></p>
                    
                    <!-- Button to open modal -->
                    <button type="button" class="bg-green-500 hover:bg-green-700 text-white p-1 px-2 text-xl rounded" 
                            onclick="openModal('<?php echo $productDetails['id']; ?>', '<?php echo $productDetails['name']; ?>', '<?php echo $productDetails['price']; ?>', '<?php echo substr($productDetails['image'], 1); ?>')">
                        + Add to cart
                    </button>
                </div>
            <?php else: ?>
                <p>Product details not available.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'add_to_cart_modal.php'; ?>
    <?php include 'footer.php'; ?>
</body>

</html>
