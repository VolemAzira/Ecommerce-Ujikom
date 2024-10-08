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
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Product Details</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css -->
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .navbar-nav .nav-link {
        color: white !important;
    }

    .card-img-top {
        height: 300px;
        object-fit: cover;
    }

    .card-body {
        flex-grow: 1;
    }

    .card {
        width: 100%;
        margin-bottom: 1rem;
        padding-bottom: 5px;
    }

    .product-card {
        margin-bottom: 20px;
    }

    .card-buttons {
        display: flex;
        justify-content: space-between;
    }

    .container {
        padding: 0;
        margin-top: 2px;
    }

    .container-custom {
        padding-left: 15px;
        padding-right: 15px;
    }

    @media (min-width: 992px) {
        .col-lg-3 {
            flex: 0 0 auto;
            width: 25%;
        }
    }
</style>

<body>
    <?php include 'header.php' ?>

    <div class="container mt-5">
        <!-- Check if the product details are set before displaying -->
        <?php if (!empty($productDetails)): ?>
            <div class="row">
                <div class="col-md-6">
                    <!-- Display product image -->
                    <img src="<?php
                                $str = substr($productDetails['image'], 1);
                                echo $str;
                                ?>"
                        alt="<?php echo $productDetails['name']; ?>" class="img-fluid">
                </div>
                <div class="col-md-6">
                    <h1><?php echo $productDetails['name']; ?></h1>
                    <p><?php echo $productDetails['description']; ?></p>
                    <!-- More product details like price, etc. -->
                    <h3>Rp.<?php echo $productDetails['price']; ?></h3>
                    <form method="POST" action="add_cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $productDetails['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $productDetails['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $productDetails['price']; ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>Product details not available.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php' ?>
</body>

</html>