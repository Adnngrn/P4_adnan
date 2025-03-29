<?php
$requireAdmin = true;
require '../auth.php';
require '../connection.php';

$stmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM products) AS totalProducts, 
        (SELECT COUNT(*) FROM discounts) AS totalDiscounts, 
        (SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = CURDATE()) AS totalTransactionsToday
");
$counts = $stmt->fetch(PDO::FETCH_ASSOC);

$totalProducts = $counts['totalProducts'];
$totalDiscounts = $counts['totalDiscounts'];
$totalTransactionsToday = $counts['totalTransactionsToday'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="flex">
    <div id="sidebar"></div>

    <main class="ml-60 p-8 w-full flex flex-col gap-7 h-screen bg-stone-200">
        <div class="grid grid-cols-4 w-full">
            
            <a href="product.php" class="grid grid-rows-4 h-32 w-64 py-2 px-4 bg-white drop-shadow-md rounded-md">
                <h2 class="text-xl font-medium">Produk</h2>
                <div class="row-span-3 flex  justify-center h-auto w-full text-6xl">
                    <p><?= $totalProducts ?></p>
                </div>
            </a>
            <a href="discount.php" class="grid grid-rows-4 h-32 w-64 py-2 px-4 bg-white drop-shadow-md rounded-md">
                <h2 class="text-xl font-medium">Diskon</h2>
                <div class="row-span-3 flex  justify-center h-auto w-full text-6xl">
                    <p><?= $totalDiscounts ?></p>
                </div>
            </a>
            <a href="history.php" class="grid grid-rows-4 h-32 w-64 py-2 px-4 bg-white drop-shadow-md rounded-md">
                <h2 class="text-xl font-medium">Transaksi Hari ini</h2>
                <div class="row-span-3 flex  justify-center h-auto w-full text-6xl">
                    <p><?= $totalTransactionsToday ?></p>
                </div>
            </a>
        </div>

        <div class="bg-white w-full h-48 rounded-lg drop-shadow-lg"></div>

        
    </main>

<script src="script.js"></script>
</body>
</html>