<?php
$requireAdmin = true;
require '../auth.php';
require '../connection.php';

$start_date = date('Y-m-01'); // Awal bulan ini
$end_date = date('Y-m-t');    // Akhir bulan ini

$query = $pdo->prepare("
    SELECT DATE(t.created_at) AS date, SUM(td.quantity) AS total_sold
    FROM transaction_details td
    JOIN transactions t ON td.transaction_id = t.id
    WHERE MONTH(t.created_at) = MONTH(CURDATE()) 
    AND YEAR(t.created_at) = YEAR(CURDATE())
    GROUP BY DATE(t.created_at)
    ORDER BY date ASC
");
$query->execute();
$sales_data = $query->fetchAll(PDO::FETCH_ASSOC);


$stmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM products) AS totalProducts,
        (SELECT COUNT(*) FROM discounts WHERE CURDATE() BETWEEN start_date AND end_date) AS totalDiscounts,
        (SELECT COUNT(*) FROM transactions WHERE DATE(created_at) = CURDATE()) AS totalTransactionsToday
");
$counts = $stmt->fetch(PDO::FETCH_ASSOC);

$totalProducts = $counts['totalProducts'];
$totalDiscounts = $counts['totalDiscounts'];
$totalTransactionsToday = $counts['totalTransactionsToday'];

$sales_json = json_encode([
    'sales' => $sales_data
]);
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
<body class="flex bg-stone-200">
    <div id="sidebar"></div>

    <main class="ml-60 py-7 px-3 w-full flex flex-col gap-7 h-screen ">
    <h1 class="text-2xl font-medium mb-5">Dashboard</h1>
        <div class="grid grid-cols-4 w-full">
            
            <a href="product.php" class="grid grid-rows-4 h-32 w-64 py-2 px-4 bg-white drop-shadow-md rounded-md">
                <h2 class="text-xl font-medium">Produk</h2>
                <div class="row-span-3 flex  justify-center h-auto w-full text-6xl">
                    <p><?= $totalProducts ?></p>
                </div>
            </a>
            <a href="discount.php" class="grid grid-rows-4 h-32 w-64 py-2 px-4 bg-white drop-shadow-md rounded-md">
                <h2 class="text-xl font-medium">Diskon Aktif</h2>
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

        <a href="stats.php" class="bg-white w-5/6 h-[600px] rounded-lg drop-shadow-lg p-10 mb-10">
            <h2 class="text-xl font-medium mb-3">Total Penjualan Per Hari</h2>
            <canvas id="salesChart" class="bg-white p-3 rounded-md shadow-md mb-10"></canvas>
        </a>

        
    </main>

<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        const salesData = <?= $sales_json ?>;
        const ctx = document.getElementById('salesChart').getContext('2d');

        // Ambil data tanggal & jumlah produk terjual
        const labels = salesData.sales.map(item => item.date);
        const data = salesData.sales.map(item => item.total_sold);

        // Inisialisasi Chart.js
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Produk Terjual',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jumlah Terjual'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

</body>
</html>