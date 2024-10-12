<nav class="bg-black text-white p-5 px-10 flex justify-between items-center print:hidden">
    <div class="flex gap-5 items-center">
        <a class="text-xl font-bold" href="index.php">PasarSegar.id</a>
        <a href="index.php" class="hidden md:block">Home</a>
    </div>

    <div class="gap-3 items-center flex">
        <a href="cart.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded relative hidden md:block">
            <i class="fa fa-shopping-cart"></i>
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="absolute top-0 right-0 inline-block w-5 h-5 bg-red-600 text-white text-xs leading-tight font-bold text-center rounded-full">
                    <?php echo count($_SESSION['cart']); ?>
                </span>
            <?php endif; ?>
        </a>
        <a href="profile.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden md:block">
            <i class="fa fa-user"></i>
        </a>
        <a href="logout.php" class="bg-red-500 p-2 px-4 rounded hover:bg-red-800 hidden md:block">
            <i class="fa fa-sign-out-alt"></i>
        </a>
        <button class="md:hidden" onclick="toggleMenu()">
            <i class="fa fa-bars text-xl"></i>
        </button>
    </div>
</nav>

<!-- Menu Mobile -->
<section class="bg-black text-white p-5 px-10 flex flex-col gap-5 hidden md:hidden" id="menu">
    <a href="index.php">
        <i class="fa fa-home mr-3"></i> Home
    </a>
    <a href="profile.php">
        <i class="fa fa-user mr-3"></i> Profil
    </a>
    <a href="logout.php">
        <i class="fa fa-sign-out-alt mr-3"></i> Logout
    </a>
</section>

<script>
    function toggleMenu() {
        document.querySelector('#menu').classList.toggle('hidden');
    }
</script>