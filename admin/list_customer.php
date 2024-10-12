<?php
include 'admin_validation.php';
include '../db.php';
$sql = "SELECT * FROM users ORDER BY username ASC";
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
    <title>List Customers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include 'header.php'; ?>
    <main class="flex">
        <?php include 'sidebar.php'; ?>
        <section class="w-full flex flex-col gap-5 m-10">
            <h3 class="text-2xl font-bold">List Customers</h3>
            <div class="overflow-auto">
                <table class="w-full text-left table-auto border border-black">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="p-2">No</th>
                            <th>Username</th>
                            <th>Gander</th>
                            <th>Date of Birth</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th class="w-[5rem]"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            $no = 1;
                            // output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='p-2'>" . $no++ . "</td>";
                                echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["gender"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["dob"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["contact_no"]) . "</td>";
                                echo "<td class='flex gap-2'> 
                                        <a href='delete_customer.php?id=" . htmlspecialchars($row["id"]) . "' class='bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-xs'>Delete</a> 
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