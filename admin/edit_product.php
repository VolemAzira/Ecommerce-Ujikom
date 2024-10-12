<?php include '../session_validation.php'; ?>
<?php
include '../db.php'; // Ensure this path is correct

// Initialize variables
$productData = [
    'name' => '',
    'category' => '',
    'description' => '',
    'price' => '',
    'image' => ''
];

// Check if the 'id' GET parameter is set
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // Fetch product data from database
    $sql = "SELECT * FROM products WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $productData = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "No product ID provided.";
    exit;
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $updatedName = $conn->real_escape_string($_POST['productName']);
    $updatedCategory = $conn->real_escape_string($_POST['productCategory']);
    $updatedDescription = $conn->real_escape_string($_POST['productDescription']);
    $updatedPrice = $conn->real_escape_string($_POST['productPrice']);

    $target_dir = "../uploads/"; // Make sure this directory exists and is writable
    $fileUploaded = false;

    // Check if a new file was uploaded
    if ($_FILES['productImage']['error'] == 0) {
        $target_file = $target_dir . basename($_FILES["productImage"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["productImage"]["tmp_name"]);
        if ($check !== false) {
            // File is an image - " . $check["mime"] . "."
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size - for example, limit to 5MB
        if ($_FILES["productImage"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["productImage"]["name"])) . " has been uploaded.";
                $fileUploaded = true;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Update the database
    if ($fileUploaded) {
        $sql = "UPDATE products SET name='$updatedName', category='$updatedCategory', description='$updatedDescription', price='$updatedPrice', image='$target_file' WHERE id='$id'";
    } else {
        $sql = "UPDATE products SET name='$updatedName', category='$updatedCategory',description='$updatedDescription', price='$updatedPrice' WHERE id='$id'";
    }

    if ($conn->query($sql) === TRUE) {
        session_start(); // Start the session at the beginning of the script
        $_SESSION['message'] = 'Product updated successfully.';
        header('Location: index.php');
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <?php include 'header.php'; ?>

    <main class="flex">
        <?php include 'sidebar.php'; ?>
        <div class="m-5 w-full">
            <h3 class="mb-5 text-2xl font-bold">Edit Product</h3>
            <form action="edit_product.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                <div>
                    <label for="productName" class="form-label">Product Name</label> <br>
                    <input value="<?php echo htmlspecialchars($productData['name']); ?>" type="text" class="p-3 border rounded my-1 w-full" id="productName" name="productName" required>
                </div>
                <div>
                    <label for="productCategory" class="form-label">Category</label>
                    <select class="p-3 border rounded my-1 w-full" id="productCategory" name="productCategory" required>
                        <option value="Fruits" <?php if ($productData['category'] === 'Fruits') echo 'selected'; ?>>Fruits</option>
                        <option value="Vegetables" <?php if ($productData['category'] === 'Vegetables') echo 'selected'; ?>>Vegetables</option>
                    </select>
                </div>
                <div>
                    <label for="productDescription" class="form-label">Description</label>
                    <textarea class="p-3 border rounded my-1 w-full" id="productDescription" name="productDescription" rows="3" required><?php echo htmlspecialchars($productData['description']); ?></textarea>
                </div>
                <div>
                    <label for="productPrice" class="form-label">Price</label>
                    <input value="<?php echo htmlspecialchars($productData['price']); ?>" type="number" step="0.01" class="p-3 border rounded my-1 w-full" id="productPrice" name="productPrice" required>
                </div>
                <div>
                    <label for="productImage" class="form-label">Product Image (Current: <?php echo htmlspecialchars($productData['image']); ?>)</label> <br>
                    <input type="file" id="productImage" name="productImage" accept="image/*">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Edit Product</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>