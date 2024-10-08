<?php
include 'admin_validation.php';
include '../db.php';
$sql = "SELECT id, name, description, price, image FROM products ORDER BY id DESC";
$result = $conn->query($sql);

if (isset($_SESSION['message'])) {
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include 'header.php'; ?>
    <main class="flex">
        <?php include 'sidebar.php'; ?>
        <section class="w-full flex flex-col gap-5 m-10">
            <h3 class="text-2xl font-bold">List Product</h3>
            <div>
                <a class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" href="add_product.php">Add Product</a>
            </div>
            <div class="overflow-auto">
                <table class="w-full text-left table-auto border border-black">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="p-2">No</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th class="w-[5rem]"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, name, description, price, image FROM products ORDER BY id ASC";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            $no = 1;
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='p-2'>" . $no++ . "</td>";
                                echo "<td><img src='" . htmlspecialchars($row["image"]) . "' class='w-24' alt='Product Image'></td>";
                                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                                echo "<td>Rp." . htmlspecialchars($row["price"]) . "</td>";
                                echo "<td class='flex gap-2'> 
                                        <a href='edit_product.php?id=" . htmlspecialchars($row["id"]) . "' class='bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-xs'>Edit</a>
                                        <a href='delete_product.php?id=" . htmlspecialchars($row["id"]) . "' class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-xs'>Delete</a> 
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No products found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>