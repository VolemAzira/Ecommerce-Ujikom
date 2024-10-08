<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Extract and sanitize input
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $retypePassword = $_POST['retypePassword'];
    $email = $conn->real_escape_string($_POST['email']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $contactNo = $conn->real_escape_string($_POST['contactNo']);
    $paypalId = $conn->real_escape_string($_POST['paypalId']);

    // Check if passwords match
    if ($password !== $retypePassword) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert into the database
    $sql = "INSERT INTO users (username, password, email, dob, gender, address, city, contact_no, paypal_id) VALUES ('$username', '$passwordHash', '$email', '$dob', '$gender', '$address', '$city', '$contactNo', '$paypalId')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['register_success'] = "Registration successful. Please log in.";
        header('Location: login.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include 'header_nologin.php'; ?>
    <main class="min-h-screen w-full flex justify-center items-center mt-5">
        <form class="border-2 rounded-xl p-5 flex flex-col gap-5 w-1/2" action="register.php" method="post">
            <h2 class="text-center text-2xl font-bold">Register</h2>
            <div>
                <label for="username">Username</label>
                <input type="text" class="w-full p-2 border rounded" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password</label>
                <div class="flex gap-3 items-center">
                    <input type="password" class="w-full p-2 border rounded" id="password" name="password" required>
                    <i class="fa-solid fa-eye" onclick="togglePasswordVisibility('password', this)" style="cursor: pointer;"></i>
                </div>
            </div>
            <div>
                <label for="retypePassword">Retype Password</label>
                <div class="flex gap-3 items-center">
                    <input type="password" class="w-full p-2 border rounded" id="retypePassword" name="retypePassword" required>
                    <i class="fa-solid fa-eye" onclick="togglePasswordVisibility('retypePassword', this)" style="cursor: pointer;"></i>
                </div>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" class="w-full p-2 border rounded" id="email" name="email" required>
            </div>
            <div>
                <label for="dob">Date of Birth</label>
                <input type="date" class="w-full p-2 border rounded" id="dob" name="dob" required>
            </div>
            <div>
                <label for="gender">Gender</label>
                <select class="w-full p-2 border rounded" id="gender" name="gender" required>
                    <option value="" selected disabled>Choose...</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div>
                <label for="address">Address</label>
                <input type="text" class="w-full p-2 border rounded" id="address" name="address" required>
            </div>
            <div>
                <label for="city">City</label>
                <select class="w-full p-2 border rounded" id="city" name="city" required>
                    <option value="" selected disabled>Choose...</option>
                    <option value="Jakarta">Jakarta</option>
                    <option value="Surabaya">Surabaya</option>
                    <option value="Bandung">Bandung</option>
                    <option value="Medan">Medan</option>
                    <option value="Bekasi">Bekasi</option>
                    <option value="Semarang">Semarang</option>
                    <option value="Tangerang">Tangerang</option>
                    <option value="Depok">Depok</option>
                    <option value="Palembang">Palembang</option>
                    <option value="Makassar">Makassar</option>
                </select>
            </div>
            <div>
                <label for="contactNo">Contact No</label>
                <input type="text" class="w-full p-2 border rounded" id="contactNo" name="contactNo" required>
            </div>
            <div>
                <label for="paypalId">PayPal ID</label>
                <input type="text" class="w-full p-2 border rounded" id="paypalId" name="paypalId">
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Submit</button>
            <button type="reset" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Clear</button>
            <div class="text-center">
                Have an account? <a href="login.php" class="text-blue-500 font-bold">Login</a>
            </div>
        </form>
    </main>

    <script>
        function togglePasswordVisibility(inputId, toggleButton) {
            var passwordInput = document.getElementById(inputId);
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.classList.remove('fa-eye');
                toggleButton.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleButton.classList.remove('fa-eye-slash');
                toggleButton.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>