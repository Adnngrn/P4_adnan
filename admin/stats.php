<?php
require '../connection.php';

// Ambil rentang tanggal dari input user (default bulan ini jika tidak diisi)
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

// Query total penjualan per hari
$query = $pdo->prepare("
    SELECT DATE(t.created_at) AS date, SUM(td.quantity) AS total_sold
    FROM transaction_details td
    JOIN transactions t ON td.transaction_id = t.id
    WHERE t.created_at BETWEEN ? AND ?
    GROUP BY DATE(t.created_at)
    ORDER BY date ASC
");
$query->execute([$start_date, $end_date]);
$sales_data = $query->fetchAll(PDO::FETCH_ASSOC);

// Query total penjualan per produk
$query = $pdo->prepare("
    SELECT p.name AS product_name, SUM(td.quantity) AS total_sold
    FROM transaction_details td
    JOIN products p ON td.product_id = p.id
    JOIN transactions t ON td.transaction_id = t.id
    WHERE t.created_at BETWEEN ? AND ?
    GROUP BY p.id
    ORDER BY total_sold DESC
");
$query->execute([$start_date, $end_date]);
$product_sales_data = $query->fetchAll(PDO::FETCH_ASSOC);

// Query total penjualan per kategori
$query = $pdo->prepare("
    SELECT c.category_name AS category_name, SUM(td.quantity) AS total_sold
    FROM transaction_details td
    JOIN products p ON td.product_id = p.id
    JOIN categories c ON p.category_id = c.id
    JOIN transactions t ON td.transaction_id = t.id
    WHERE t.created_at BETWEEN ? AND ?
    GROUP BY c.id
    ORDER BY total_sold DESC
");
$query->execute([$start_date, $end_date]);
$category_sales_data = $query->fetchAll(PDO::FETCH_ASSOC);

// Jika request berasal dari AJAX, kirimkan data dalam format JSON
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'sales' => $sales_data,
        'products' => $product_sales_data,
        'categories' => $category_sales_data
    ]);
    exit;
}

// Konversi data ke format JSON untuk JavaScript
$sales_json = json_encode([
    'sales' => $sales_data,
    'products' => $product_sales_data,
    'categories' => $category_sales_data
]);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        td{text-align:center}
    </style>
</head>
<body class="flex bg-stone-200">
    <div id="sidebar"></div>

    <div class="ml-60 w-9/12 px-3 py-7">
        <h1 class="text-2xl font-medium mb-5">Statistik Penjualan</h1>

        <!-- Form Input Tanggal -->
        <form id="dateForm" class="flex gap-4 mb-5">
            <input type="date" id="startDate" name="start_date" value="<?= $start_date; ?>" class="px-4 py-2 border rounded-md">
            <input type="date" id="endDate" name="end_date" value="<?= $end_date; ?>" class="px-4 py-2 border rounded-md">
            <button type="submit" class="bg-blue-500 text-white px-5 py-2 rounded-md">Filter</button>
        </form>

        <!-- Grafik Total Penjualan -->
        <h2 class="text-xl font-medium mb-3">Total Penjualan Per Hari</h2>
        <canvas id="salesChart" class="bg-white p-3 rounded-md shadow-md"></canvas>

        <!-- Grafik Penjualan Per Produk -->
        <h2 class="text-xl font-medium mt-6 mb-3">Penjualan Per Produk</h2>
        <canvas id="productChart" class="bg-white p-3 rounded-md shadow-md"></canvas>

        <!-- Grafik Penjualan Per Kategori -->
        <h2 class="text-xl font-medium mt-6 mb-3">Penjualan Per Kategori</h2>
        <canvas id="categoryChart" class="bg-white p-3 rounded-md shadow-md"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        var salesChart, productChart, categoryChart;

        function fetchSalesData(startDate, endDate) {
            $.ajax({
                url: 'stats.php',
                type: 'GET',
                data: { start_date: startDate, end_date: endDate, ajax: 1 },
                dataType: 'json',
                success: function(response) {
                    var dates = response.sales.map(row => row.date);
                    var sales = response.sales.map(row => row.total_sold);

                    var productNames = response.products.map(row => row.product_name);
                    var productSales = response.products.map(row => row.total_sold);

                    var categoryNames = response.categories.map(row => row.category_name);
                    var categorySales = response.categories.map(row => row.total_sold);

                    salesChart = updateChart(salesChart, 'salesChart', dates, sales, 'Total Produk Terjual');
                    productChart = updateChart(productChart, 'productChart', productNames, productSales, 'Total Terjual Per Produk');
                    categoryChart = updateChart(categoryChart, 'categoryChart', categoryNames, categorySales, 'Total Terjual Per Kategori');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        function updateChart(chart, canvasId, labels, data, label) {
            if (chart) {
                chart.destroy();
            }

            var ctx = document.getElementById(canvasId).getContext('2d');
            return new Chart(ctx, {
                type: (canvasId === 'salesChart') ? 'line' : 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: (canvasId === 'salesChart') ? 'rgba(75, 192, 192, 0.2)' : 'rgba(54, 162, 235, 0.5)',
                        borderWidth: 2,
                        fill: (canvasId === 'salesChart')
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Tanggal/Kategori' } },
                        y: { title: { display: true, text: 'Jumlah Terjual' }, beginAtZero: true }
                    }
                }
            });
        }

        // Pastikan tombol filter bekerja
        $('#dateForm').on('submit', function(e) {
            e.preventDefault();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            fetchSalesData(startDate, endDate);
        });

        // Panggil data saat halaman pertama kali dimuat
        fetchSalesData($('#startDate').val(), $('#endDate').val());
    });


</script>


<script src="script.js"></script>
</body>
</html>