<?php
include 'session_validation.php';
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Initialize user information
$userInfo = [];
if (isset($_SESSION['userid'])) {
    $userInfo = getUserInfo($conn, $_SESSION['userid']);
} else {
    exit("No user ID in session.");
}

// Initialize cart and calculate grand total
$cart = $_SESSION['cart'] ?? [];
$grandTotal = calculateGrandTotal($cart);


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    processTransaction($conn, $userInfo, $grandTotal);
    sendFeedback($conn, $userInfo);
    sendEmailReceipt($userInfo, $cart, $grandTotal);
    clearCart();
    header("Location: index.php");
    exit();
}

// Add this function to handle cart clearing
function clearCart()
{
    $_SESSION['cart'] = [];
    $_SESSION['message'] = 'Cart cleared.';
}


// Functions
function getUserInfo($conn, $userId)
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?? [];
}

function calculateGrandTotal($cart)
{
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['amount'];
    }
    return $total;
}

function processTransaction($conn, $userInfo, $grandTotal)
{
    $stmt = $conn->prepare("INSERT INTO transaction (name, email, total) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $userInfo['username'], $userInfo['email'], $grandTotal);
    $stmt->execute();
}

function sendFeedback($conn, $userInfo)
{
    $message = $conn->real_escape_string($_POST['message']);
    $stmt = $conn->prepare("INSERT INTO feedbacks (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $userInfo['username'], $userInfo['email'], $message);
    $stmt->execute();
}

function sendEmailReceipt($userInfo, $cart, $grandTotal)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'volemalazr@gmail.com';
        $mail->Password = 'pdql sdin pgft guog';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('volemalazr@gmail.com', 'Volem Ecommerce');
        $mail->addAddress($userInfo['email']);

        $mail->isHTML(true);
        $mail->Subject = 'Transaksi Berhasil';
        $mail->Body = generateEmailBody($userInfo, $cart, $grandTotal);

        $mail->send();
    } catch (Exception $e) {
        echo "Email failed: {$mail->ErrorInfo}";
    }
}

function generateEmailBody($userInfo, $cart, $grandTotal)
{
    $body = "<h1>PasarSegar.id</h1>
             <h2>Purchase Summary</h2>
             <p>Date: " . date('Y-m-d') . "</p>
             <p>PayPal ID: {$userInfo['paypal_id']}</p>
             <table border='1' cellpadding='5'>
                 <tr><th>Product</th><th>Amount</th><th>Price</th><th>Total</th></tr>";
    foreach ($cart as $product) {
        $body .= "<tr>
                      <td>{$product['name']}</td>
                      <td>{$product['amount']}</td>
                      <td>Rp." . number_format($product['price']) . "</td>
                      <td>Rp." . number_format($product['price'] * $product['amount']) . "</td>
                  </tr>";
    }
    $body .= "<tr><th colspan='3'>Grand Total</th><th>Rp." . number_format($grandTotal) . "</th></tr></table>";
    return $body;
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

    <main class="m-10 flex flex-col items-center">
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
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm" onclick="showTransactionModal();">Pay Now</button>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm" onclick="window.print();">Print Invoice</button>
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div class="h-screen w-full fixed top-0 left-0 justify-center items-center hidden flex" id="transactionModal">
        <form method="post" class="bg-white p-8 rounded shadow-lg text-center border flex flex-col gap-5">
            <h5 class="text-xl font-bold">Transaction Successfully!</h5>
            <textarea name="message" id="message" rows="5" class="p-3 border rounded border-black" placeholder="Send Your Feedback !!" required></textarea>
            <p>Check your email for further details about your transaction.</p>
            <button type="submit" id="submitBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                Ok
            </button>
        </form>
    </div>

    <script>
        function showTransactionModal() {
            document.getElementById('transactionModal').classList.toggle('hidden');
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.classList.add('cursor-not-allowed');
            submitBtn.innerHTML = "<i class='fa-solid fa-spinner animate-spin'></i>";
        });
    </script>
</body>

</html>