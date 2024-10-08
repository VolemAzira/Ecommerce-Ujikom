<?php include 'session_validation.php'; ?>
<?php
include 'db.php';
include 'add_cart.php';
$sql = "SELECT id, name, description, image, price FROM products";
// Check if a category is selected
if (isset($_GET['category']) && $_GET['category'] != 'All') {
    $category = $conn->real_escape_string($_GET['category']);
    // Update SQL query to filter by the selected category
    $sql .= " WHERE category = '$category'";
}
$result = $conn->query($sql);

$products = []; // Array to hold product data
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "0 results";
}

$currentCategory = isset($_GET['category']) ? $_GET['category'] : 'All';

?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>E-Commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>


<body>

    <?php include 'header.php' ?>

    <main class="min-h-screen m-10 flex flex-col gap-5">
        <div class="text-3xl font-bold capitalize">
            <h1>Hay, <?php echo $_SESSION['username']; ?>! Welcome to our store</h1>
            <p>We Selling fresh fruits and veggies!</p>
        </div>


        <section class="flex justify-between">
            <!-- Product grid -->
            <div class="flex gap-5 flex-wrap">
                <?php foreach ($products as $product): ?>
                    <div class="border-2 rounded-xl shadow overflow-hidden">
                        <img src="<?php
                                    $str = substr($product['image'], 1);
                                    echo $str;
                                    ?>"
                            class="w-[20rem] h-[12rem]" alt="<?php echo $product['name']; ?>">
                        <div class="p-5 flex flex-col gap-2">
                            <h5 class="text-xl font-bold"><?php echo $product['name']; ?></h5>
                            <p class=" text-gray-500 text-sm">
                                <?php
                                echo (strlen($product['description']) > 100)
                                    ? mb_substr($product['description'], 0, 100) . "..."
                                    : $product['description'];
                                ?>
                            </p>
                            <div class="flex justify-between">
                                <a href="details.php?id=<?php echo $product['id']; ?>" class="text-blue-500">Detail</a>
                                <form method="POST" action="add_cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $product['name']; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                                    <button type="submit" name="add_to_cart" class="bg-green-500 hover:bg-green-700 text-white font-bold p-1 px-2 text-xl rounded">+</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Category Sidebar -->
            <div class="border-2 rounded-xl p-5">
                <h3 class="text-xl font-bold mb-3">Product Category</h3>
                <div class="flex flex-col gap-2 transition-all">
                    <a href="index.php?category=All" class="<?= $currentCategory === 'All' ? 'bg-black text-white' : '' ?> p-2">All</a>
                    <a href="index.php?category=Fruits" class="<?= $currentCategory === 'Fruits' ? 'bg-black text-white' : '' ?> p-2">Fruits</a>
                    <a href="index.php?category=Vegetables" class="<?= $currentCategory === 'Vegetables' ? 'bg-black text-white' : '' ?> p-2">Vegetables</a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php' ?>

</body>

</html>