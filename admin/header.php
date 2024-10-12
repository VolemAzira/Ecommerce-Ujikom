<nav class="bg-black text-white p-5 px-10 flex justify-between items-center">
    <div>
        <button onclick="toggleSidebar()" class="mr-3">
            <i class="fa fa-bars text-xl"></i>
        </button>
        <a class="text-xl font-bold" href="index.php">ADMIN PasarSegar.id</a>
    </div>
    <a href="../logout.php" class="bg-red-500 p-2 rounded hover:bg-red-800" role="button">Log Out</a>
</nav>

<script>
    function toggleSidebar() {
        document.querySelector('#sidebar').classList.toggle('hidden');
    }
</script>