<?php
require '../connection.php';

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

// Penjualan per hari
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

// Penjualan per produk
$query = $pdo->prepare("
    SELECT p.name AS product_name, COALESCE(SUM(td.quantity), 0) AS total_sold
    FROM products p
    LEFT JOIN (
        SELECT td.product_id, td.quantity
        FROM transaction_details td
        JOIN transactions t ON td.transaction_id = t.id
        WHERE t.created_at BETWEEN ? AND ?
    ) td ON td.product_id = p.id
    GROUP BY p.id
    ORDER BY total_sold DESC
");
$query->execute([$start_date, $end_date]);
$product_sales_data = $query->fetchAll(PDO::FETCH_ASSOC);

// Penjualan per kategori
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

// Pendapatan per hari
$query = $pdo->prepare("
    SELECT DATE(created_at) AS date, SUM(final_price) AS total_income
    FROM transactions
    WHERE created_at BETWEEN ? AND ?
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");
$query->execute([$start_date, $end_date]);
$income_data = $query->fetchAll(PDO::FETCH_ASSOC);

// Jika request berasal dari AJAX, kirimkan data dalam format JSON
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode([
        'sales' => $sales_data,
        'products' => $product_sales_data,
        'categories' => $category_sales_data,
        'income' => $income_data
    ]);
    exit;
}

// Konversi data ke format JSON untuk JavaScript
// $sales_json = json_encode([
//     'sales' => $sales_data,
//     'products' => $product_sales_data,
//     'categories' => $category_sales_data,
//     'income' => $income_data
// ]);
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

        <!-- Grafik Total Penjualan dan Pendapatan -->
        <h2 class="text-xl font-medium mb-3">Penjualan & Pendapatan Per Hari</h2>
        <button onclick="exportToExcel('sales')" class="bg-green-500 text-white px-4 py-2 rounded-md mb-3">Export to Excel</button>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <canvas id="salesChart" class="bg-white p-3 rounded-md shadow-md"></canvas>
            </div>
            <div class="">
                <canvas id="incomeChart" class="bg-white p-3 rounded-md shadow-md"></canvas>
            </div>
        </div>

        <!-- Grafik Penjualan Per Produk -->
        <h2 class="text-xl font-medium mt-6 mb-3">Penjualan Per Produk</h2>
        <button onclick="exportToExcel('products')" class="bg-green-500 text-white px-4 py-2 rounded-md mb-3">Export to Excel</button>
        <canvas id="productChart" class="bg-white p-3 rounded-md shadow-md"></canvas>

        
        <!-- Grafik Penjualan Per Kategori -->
        <h2 class="text-xl font-medium mt-6 mb-3">Penjualan Per Kategori</h2>
        <button onclick="exportToExcel('categories')" class="bg-green-500 text-white px-4 py-2 rounded-md mb-3">Export to Excel</button>
        <canvas id="categoryChart" class="bg-white p-3 rounded-md shadow-md"></canvas>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function exportToExcel(type) {
            let startDate = document.getElementById('startDate').value;
            let endDate = document.getElementById('endDate').value;
            window.location.href = `export_excel.php?type=${type}&start_date=${startDate}&end_date=${endDate}`;
        }

        $(document).ready(function() {
            var salesChart, productChart, categoryChart, incomeChart;

            function fetchSalesData(startDate, endDate) {
                $.ajax({
                    url: 'stats.php',
                    type: 'GET',
                    data: { start_date: startDate, end_date: endDate, ajax: 1 },
                    dataType: 'json',
                    success: function(response) {
                        var dates = response.sales.map(row => row.date);
                        var sales = response.sales.map(row => row.total_sold);

                        // Produk chart (mengganti tabel dengan grafik)
                        var productNames = response.products.map(row => row.product_name);
                        var productSales = response.products.map(row => row.total_sold);

                        var categoryNames = response.categories.map(row => row.category_name);
                        var categorySales = response.categories.map(row => row.total_sold);

                        var incomeDates = response.income.map(row => row.date);
                        var incomeTotals = response.income.map(row => row.total_income);

                        salesChart = updateChart(salesChart, 'salesChart', dates, sales, 'Total Produk Terjual', 'line');
                        productChart = updateChart(productChart, 'productChart', productNames, productSales, 'Total Terjual per Produk', 'bar');
                        categoryChart = updateChart(categoryChart, 'categoryChart', categoryNames, categorySales, 'Total Terjual Per Kategori', 'bar');
                        incomeChart = updateChart(incomeChart, 'incomeChart', incomeDates, incomeTotals, 'Pendapatan (Rp)', 'line');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            }

            function updateChart(chart, canvasId, labels, data, label, type) {
                if (chart) chart.destroy();

                var ctx = document.getElementById(canvasId).getContext('2d');
                return new Chart(ctx, {
                    type: type,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: data,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: type === 'line' ? 'rgba(255, 99, 132, 0.2)' : 'rgba(54, 162, 235, 0.5)',
                            borderWidth: 2,
                            fill: type === 'line'
                        }]
                    },
                    options: {
                        responsive: true,
                        indexAxis: type === 'bar' && labels.length > 8 ? 'y' : 'x',
                        scales: {
                            x: { title: { display: true, text: type === 'bar' && labels.length > 8 ? label : 'Tanggal' } },
                            y: { beginAtZero: true, title: { display: true, text: label } }
                        }
                    }
                });
            }

            $('#dateForm').on('submit', function(e) {
                e.preventDefault();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                fetchSalesData(startDate, endDate);
            });

            fetchSalesData($('#startDate').val(), $('#endDate').val());
        });
    </script>

<script src="script.js"></script>
</body>
</html>