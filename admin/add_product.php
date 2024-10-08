<?php include '../session_validation.php'; ?>
<?php
include '../db.php';
// session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $productName = $conn->real_escape_string($_POST['productName']);
    $productCategory = $conn->real_escape_string($_POST['productCategory']);
    $productDescription = $conn->real_escape_string($_POST['productDescription']);
    $productPrice = $conn->real_escape_string($_POST['productPrice']);

    // Handle file upload
    $target_dir = "../uploads/"; // Specify where you want to store the files
    $target_file = $target_dir . basename($_FILES["productImage"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    // You can add file validation here (e.g., size, type)
    move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file);

    // Insert into database
    $sql = "INSERT INTO products (name, category, description, price, image) VALUES ('$productName', '$productCategory', '$productDescription', '$productPrice', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "New product created successfully";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include 'header.php'; ?>

    <main class="flex">
        <?php include 'sidebar.php'; ?>
        <div class="m-5 w-full">
            <h3 class="mb-5 text-2xl font-bold">Add New Product</h3>
            <?php if (!empty($success_message)) {
                echo "<div class='alert alert-success'>$success_message</div>";
            } ?>
            <?php if (!empty($error_message)) {
                echo "<div class='alert alert-danger'>$error_message</div>";
            } ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                <div>
                    <label for="productName" class="form-label">Product Name</label> <br>
                    <input type="text" class="p-3 border rounded my-1 w-full" id="productName" name="productName" required>
                </div>
                <div>
                    <label for="productCategory" class="form-label">Category</label>
                    <select class="p-3 border rounded my-1 w-full" id="productCategory" name="productCategory" required>
                        <option value="" selected disabled>Select a category</option>
                        <option value="Fruits">Fruits</option>
                        <option value="Vegetables">Vegetables</option>
                    </select>
                </div>
                <div>
                    <label for="productDescription" class="form-label">Description</label>
                    <textarea class="p-3 border rounded my-1 w-full" id="productDescription" name="productDescription" rows="3" required></textarea>
                </div>
                <div>
                    <label for="productPrice" class="form-label">Price</label>
                    <input type="number" step="0.01" class="p-3 border rounded my-1 w-full" id="productPrice" name="productPrice" required>
                </div>
                <div>
                    <label for="productImage" class="form-label">Product Image</label> <br>
                    <input type="file" id="productImage" name="productImage" accept="image/*">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Product</button>
                </div>
            </form>
        </div>
    </main>

</body>

</html>