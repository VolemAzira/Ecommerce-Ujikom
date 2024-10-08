<?php
include 'session_validation.php';
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Jika Anda menggunakan Composer


// Initialize an array to hold user information
$userInfo = [];

// Check if the user ID is set in the session
if (isset($_SESSION['userid'])) {
    $userId = $_SESSION['userid'];

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();



    if ($result->num_rows > 0) {
        $userInfo = $result->fetch_assoc();
    } else {
        echo "User not found.";
    }
} else {
    echo "No user ID in session.";
}
// Ensure the cart is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array(); // This should be replaced with actual cart data retrieval
}

// Calculate the grand total
$grandTotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $grandTotal += $item['price'] * $item['amount'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_user = $userInfo['email']; // Email pengguna dari form
    $item_price = $grandTotal; // Harga barang

    // Inisialisasi PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP(); // Menggunakan SMTP
        $mail->Host = 'smtp.gmail.com'; // Host SMTP
        $mail->SMTPAuth = true; // Aktifkan otentikasi SMTP
        $mail->Username = 'volemalazr@gmail.com'; // Email Anda
        $mail->Password = 'pdql sdin pgft guog'; // Password email Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Gunakan TLS
        $mail->Port = 587; // Port TCP untuk TLS

        // Penerima
        $mail->setFrom('volemalazr@gmail.com', 'Volem Ecommerce'); // Ganti 'Your Name' sesuai nama Anda
        $mail->addAddress($email_user); // Email penerima

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = 'Transaksi Berhasil';
        $mail->Body = "
    Terima kasih telah melakukan transaksi. Berikut adalah detail pembelian Anda:<br><br>
    
    <h2>Purchase Summary</h2>
    <p>Date: " . date('Y-m-d') . "</p>
    <p>PayPal ID: " . $userInfo['paypal_id'] . "</p>
    <p>Bank Name: My Bank</p>
    <p>Payment Method: " . htmlspecialchars($_SESSION['payment_method']) . "</p>
    <table border='1' cellpadding='5' cellspacing='0'>
        <tr>
            <th>Product</th>
            <th>Amount</th>
            <th>Price</th>
            <th>Total</th>
        </tr>";

        // Tambahkan produk langsung ke $mail->Body
        foreach ($_SESSION['cart'] as $product) {
            $mail->Body .= "
        <tr>
            <td>" . $product['name'] . "</td>
            <td>" . $product['amount'] . "</td>
            <td>Rp." . number_format($product['price']) . "</td>
            <td>Rp." . number_format($product['amount'] * $product['price']) . "</td>
        </tr>";
        }

        $mail->Body .= "
        <tr>
            <th colspan='3'>Grand Total</th>
            <th>Rp." . number_format($grandTotal) . "</th>
        </tr>
    </table>
    Transaksi Anda telah berhasil. Terima kasih telah berbelanja bersama kami!
";


        // Kirim email
        $mail->send();
    } catch (Exception $e) {
        echo "Gagal mengirim email. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- stylesheet -->
    <link
        rel="stylesheet"
        href="https://unpkg.com/@material-tailwind/html@latest/styles/material-tailwind.css" />

    <!-- script -->
    <script src="https://unpkg.com/@material-tailwind/html@latest/scripts/script-name.js"></script>
</head>

<body>
    <?php include 'header.php'; ?>

    <main class="min-h-screen m-10 flex flex-col items-center">
        <section class="lg:w-1/2 w-full flex flex-col gap-5">
            <h2 class="text-2xl font-bold">Purchase Summary</h2>
            <div class="flex gap-10 justify-between">
                <div class="">
                    <p>User ID: <?php echo $userInfo['id']; ?></p>
                    <p>Name: <?php echo $userInfo['username']; ?></p>
                    <p>Address: <?php echo $userInfo['address']; ?></p>
                    <p>Phone Number: <?php echo $userInfo['contact_no']; ?></p>
                </div>
                <div class="">
                    <p>Date: <?php echo date('Y-m-d'); ?></p>
                    <p>PayPal ID: <?php echo $userInfo['paypal_id']; ?></p>
                    <p>Bank Name: My Bank</p>
                    <?php
                    if (isset($_SESSION['payment_method'])) {
                        echo "<p>Payment Method: " . htmlspecialchars($_SESSION['payment_method']) . "</p>";
                    } else {
                        echo "<p>No payment method selected.</p>";
                    }
                    ?>
                </div>
            </div>
            <table class="w-full text-left table-auto border border-black">
                <thead class="bg-black text-white">
                    <tr>
                        <th class="p-2">Product</th>
                        <th>Amount</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $product): ?>
                        <tr>
                            <td class="p-2"><?php echo $product['name']; ?></td>
                            <td><?php echo $product['amount']; ?></td>
                            <td>Rp.<?php echo number_format($product['price']); ?></td>
                            <td>Rp.<?php echo number_format($product['amount'] * $product['price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-right">Grand Total : &nbsp</th>
                        <th>Rp.<?php echo number_format($grandTotal); ?></th>
                    </tr>
                </tfoot>
            </table>
            <div class="flex justify-end gap-2 print:hidden">
                <form action="" method="post">
                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm" onclick="showTransactionModal();">Pay Now</button>
                </form>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm" onclick="window.print();">Print Invoice</button>
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div class="h-screen w-full fixed top-0 left-0 justify-center items-center hidden" id="transactionModal">
        <div class="bg-white p-8 rounded shadow-lg text-center border flex flex-col gap-5">
            <h5 class="text-xl font-bold">Transaction Successfully!</h5>
            <p>Check your email for further details about your transaction.</p>
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex">OK</button>
        </div>
    </div>

    <script>
        function showTransactionModal() {
            document.getElementById('transactionModal').classList.toggle('hidden');
        }
    </script>
</body>

</html>