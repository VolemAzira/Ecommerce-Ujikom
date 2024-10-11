<?php

include 'session_validation.php'; // Validasi sesi
include 'db.php'; // Koneksi ke database

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek apakah session userid sudah diset
if (!isset($_SESSION['userid'])) {
    die("Session userid is not set.");
}

$userId = $_SESSION['userid']; // Ambil user ID dari sesi

// Ambil data user dari database
$stmt = $conn->prepare("SELECT username, email, gender, address, city, contact_no, paypal_id, dob FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

// Cek eksekusi query
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();
$userData = $result->fetch_assoc();

// Daftar gender
$genders = ['Male', 'Female', 'Other'];

// Daftar kota-kota (hardcoded array)
$cities = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Bekasi', 'Semarang', 'Tangerang', 'Depok', 'Palembang', 'Makassar'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update data user
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $contact_no = $_POST['contact_no'];
    $paypal_id = $_POST['paypal_id'];
    
    // Cek apakah password akan diupdate
    if (!empty($_POST['password'])) {
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, email = ?, gender = ?, dob = ?, address = ?, city = ?, contact_no = ?, paypal_id = ? WHERE id = ?");
        $stmt->bind_param("sssssssssi", $username, $hashed_password, $email, $gender, $dob, $address, $city, $contact_no, $paypal_id, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, gender = ?, dob = ?, address = ?, city = ?, contact_no = ?, paypal_id = ? WHERE id = ?");
        $stmt->bind_param("ssssssssi", $username, $email, $gender, $dob, $address, $city, $contact_no, $paypal_id, $userId);
    }

    // Cek apakah update berhasil
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profile updated successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to update profile. Error: " . $stmt->error;
    }

    // Redirect dan refresh halaman
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css -->
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .navbar-nav .nav-link {
        color: white !important;
    }
</style>

<body>
    <?php include 'header.php'; ?>

    <main class="min-h-screen w-full flex justify-center items-center mt-5">
        <form class="border-2 rounded-xl p-5 grid grid-cols-2 gap-5 w-3/4" action="profile.php" method="post">
            <h2 class="text-center text-2xl font-bold col-span-2">Update Profile</h2>

            <!-- Display success or error message from session -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success col-span-2" role="alert">
                    <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']); // Hapus pesan setelah ditampilkan
                    ?>
                </div>
            <?php elseif (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger col-span-2" role="alert">
                    <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']); // Hapus pesan setelah ditampilkan
                    ?>
                </div>
            <?php endif; ?>

            <!-- Form fields -->
            <div>
                <label for="username">Username</label>
                <input type="text" class="w-full p-2 border rounded" id="username" name="username" value="<?php echo $userData['username']; ?>" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" class="w-full p-2 border rounded" id="email" name="email" value="<?php echo $userData['email']; ?>" required>
            </div>
            <div>
                <label for="gender">Gender</label>
                <select class="w-full p-2 border rounded" id="gender" name="gender" required>
                    <?php foreach ($genders as $gender_option): ?>
                        <option value="<?php echo $gender_option; ?>" <?php if ($userData['gender'] == $gender_option) echo 'selected'; ?>><?php echo $gender_option; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="dob">Date of Birth</label>
                <input type="date" class="w-full p-2 border rounded" id="dob" name="dob" value="<?php echo $userData['dob']; ?>" required>
            </div>
            <div class="col-span-2">
                <label for="address">Address</label>
                <input type="text" class="w-full p-2 border rounded" id="address" name="address" value="<?php echo $userData['address']; ?>" required>
            </div>
            <div>
                <label for="city">City</label>
                <select class="w-full p-2 border rounded" id="city" name="city" required>
                    <?php foreach ($cities as $city_option): ?>
                        <option value="<?php echo $city_option; ?>" <?php if ($userData['city'] == $city_option) echo 'selected'; ?>><?php echo $city_option; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="contact_no">Contact No</label>
                <input type="text" class="w-full p-2 border rounded" id="contact_no" name="contact_no" value="<?php echo $userData['contact_no']; ?>" required>
            </div>
            <div>
                <label for="paypal_id">PayPal ID</label>
                <input type="text" class="w-full p-2 border rounded" id="paypal_id" name="paypal_id" value="<?php echo $userData['paypal_id']; ?>">
            </div>
            <div>
                <label for="password">New Password (leave blank if not changing)</label>
                <input type="password" class="w-full p-2 border rounded" id="password" name="password">
            </div>

            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded col-span-2">Update Profile</button>
        </form>
    </main>
</body>
</html>
