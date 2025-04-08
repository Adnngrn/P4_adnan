<?php
include '../../auth.php';
?>

<div class="fixed flex flex-col justify-between w-60 h-screen bg-white drop-shadow-lg py-5">
    <div>
        <h1 class="text-center font-medium text-xl mt-3">
            <?php echo htmlspecialchars($_SESSION['username'] ?? 'Pengguna'); ?>
        </h1>
        <div class="mt-10 grid gap-3">
            <a class="px-10 py-3 w-full hover:bg-blue-500 hover:text-white" id="dashboard" href="dashboard.php">Dashboard</a><hr>
            <a class="px-10 py-3 w-full hover:bg-blue-500 hover:text-white" id="product" href="product.php">Produk</a>
            <a class="px-10 py-3 w-full hover:bg-blue-500 hover:text-white" id="discount" href="discount.php">Diskon</a>
            <a class="px-10 py-3 w-full hover:bg-blue-500 hover:text-white" id="stats" href="stats.php">Statistik</a>
            <a class="px-10 py-3 w-full hover:bg-blue-500 hover:text-white" id="history" href="history.php">Riwayat Transaksi</a>
            <a class="px-10 py-3 w-full hover:bg-blue-500 hover:text-white" id="account" href="account.php">Akun</a>
        </div>
    </div>
    
    <a class="px-10 py-3 w-full border-t hover:bg-red-500 hover:text-white text-center" href="../logout.php">Keluar</a>
</div>
