<?php include 'session_validation.php'; ?>
<?php
include 'db.php';
include 'add_cart.php';
$sql = "SELECT id, name, description, image, price FROM products";
if (isset($_GET['category']) && $_GET['category'] != 'All') {
    $category = $conn->real_escape_string($_GET['category']);
    $sql .= " WHERE category = '$category'";
}
$result = $conn->query($sql);

$products = [];
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PasarSegar.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="min-h-screen m-10 flex flex-col gap-10">
        <div class="text-3xl font-bold capitalize">
            <h1>Hay, <?php echo $_SESSION['username']; ?>! Welcome to our store</h1>
            <p>We Selling fresh fruits and veggies!</p>
        </div>
        <section class="flex justify-between">
            <div class="flex gap-5 flex-wrap w-full">
                <?php foreach ($products as $product): ?>
                    <div class="border-2 rounded-xl shadow overflow-hidden max-w-[20rem]">
                        <img src="<?php echo substr($product['image'], 1); ?>" class="w-[20rem] h-[12rem]" alt="<?php echo $product['name']; ?>">
                        <div class="p-5 flex flex-col gap-2">
                            <h5 class="text-xl font-bold"><?php echo $product['name']; ?>
                                <span class="text-green-500">Rp.<?php echo $product['price']; ?></span>
                            </h5>
                            <p class=" text-gray-500 text-sm">
                                <?php echo (strlen($product['description']) > 100) ? mb_substr($product['description'], 0, 100) . "..." : $product['description']; ?>
                            </p>
                            <div class="flex justify-between">
                                <a href="details.php?id=<?php echo $product['id']; ?>" class="text-blue-500">Detail</a>
                                <button type="button" class="bg-green-500 hover:bg-green-700 text-white font-bold p-1 px-2 text-xl rounded" 
                                    onclick="openModal('<?php echo $product['id']; ?>', '<?php echo $product['name']; ?>', '<?php echo $product['price']; ?>', '<?php echo substr($product['image'], 1); ?>')">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="border-2 rounded-xl p-5 h-full w-[30rem]">
                <h3 class="text-xl font-bold mb-3 text-center">Product Category</h3>
                <div class="flex flex-col gap-2 transition-all">
                    <a href="index.php?category=All" class="<?= $currentCategory === 'All' ? 'bg-black text-white' : '' ?> p-2">All</a>
                    <a href="index.php?category=Fruits" class="<?= $currentCategory === 'Fruits' ? 'bg-black text-white' : '' ?> p-2">Fruits</a>
                    <a href="index.php?category=Vegetables" class="<?= $currentCategory === 'Vegetables' ? 'bg-black text-white' : '' ?> p-2">Vegetables</a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'add_to_cart_modal.php'; ?>
    <?php include 'footer.php'; ?>

</body>

</html>
