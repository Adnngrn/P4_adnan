<?php
$requireAdmin = true;
require '../auth.php';
require '../connection.php';

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

$query = $pdo->prepare("
    SELECT p.name AS product_name, SUM(td.quantity) AS total_sold, SUM(td.subtotal) AS total_revenue
    FROM transaction_details td
    JOIN products p ON td.product_id = p.id
    JOIN transactions t ON td.transaction_id = t.id
    WHERE t.created_at BETWEEN ? AND ?
    GROUP BY p.id
    ORDER BY total_sold DESC
");

$query->execute([$start_date, $end_date]);
$sales_summary = $query->fetchAll(PDO::FETCH_ASSOC);

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

    <div class="ml-60  w-full px-3 py-7">

        <table border="1">
            <tr>
                <th>Produk</th>
                <th>Jumlah Terjual</th>
                <th>Total Pendapatan</th>
            </tr>
            <?php foreach ($sales_summary as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= $row['total_sold'] ?></td>
                <td>Rp <?= number_format($row['total_revenue'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <canvas id="salesChart"></canvas>


    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch("get_sales_summary.php?start_date=Y-m-01&end_date=Y-m-t")
    .then(response => response.json())
    .then(data => {
        let productNames = data.map(item => item.product_name);
        let salesData = data.map(item => item.total_sold);

        let ctx = document.getElementById("salesChart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: productNames,
                datasets: [{
                    label: "Jumlah Terjual",
                    data: salesData,
                    backgroundColor: "rgba(75, 192, 192, 0.6)"
                }]
            }
        });
    });
</script>

<script src="script.js"></script>
</body>
</html>