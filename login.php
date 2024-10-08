<?php
session_start();

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = '$username' OR email = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['userid'] = $user['id'];

            if ($user['username'] === 'admin') {
                header("Location: admin/index.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            $login_error = "Invalid password.";
        }
    } else {
        $login_error = "User does not exist.";
    }
}

// Check for a registration success message
if (isset($_SESSION['register_success'])) {
    $register_success = $_SESSION['register_success'];
    unset($_SESSION['register_success']);
} else {
    $register_success = '';
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include 'header_nologin.php'; ?>
    <main class="min-h-screen w-full flex justify-center items-center mt-5">
        <form class="border-2 rounded-xl p-5 flex flex-col gap-5 w-1/2" action="login.php" method="post">
            <h2 class="text-center text-2xl font-bold">Login</h2>
            <?php if ($login_error != ''): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $login_error; ?>
                </div>
            <?php endif; ?>
            <div>
                <label for="username">Username or Email</label>
                <input type="text" class="w-full p-2 border rounded" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password</label>
                <div class="flex gap-3 items-center">
                    <input type="password" class="w-full p-2 border rounded" id="password" name="password" required>
                    <i class="fa-solid fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                </div>
            </div>
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Login</button>
            <div class="text-center">
                Don't have account? <a href="register.php" class="text-blue-500 font-bold">Register</a>
            </div>
        </form>
    </main>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>