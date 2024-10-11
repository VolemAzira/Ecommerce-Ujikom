<nav class="bg-black text-white p-5 px-10 flex justify-between items-center print:hidden">
    <div class="flex gap-5 items-center">
        <a class="text-xl font-bold" href="index.php">PasarSegar.id</a>
        <a href="index.php">Home</a>
    </div>
    <div class="flex gap-3 items-center">
        <a href="cart.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded relative">
            <i class="fa fa-shopping-cart"></i>
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="absolute top-0 right-0 inline-block w-5 h-5 bg-red-600 text-white text-xs leading-tight font-bold text-center rounded-full">
                    <?php echo count($_SESSION['cart']); ?>
                </span>
            <?php endif; ?>
        </a>

        <!-- Tautan Profil User -->
        <a href="profile.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fa fa-user"></i> 
        </a>

        <a class="bg-red-500 p-2 rounded hover:bg-red-800" href="logout.php" role="button">    <i class="fa fa-sign-out-alt mr-2"></i> </a>
    </div>
</nav>
