<?php
require '../connection.php'; // Koneksi ke database

// Ambil semua transaksi berdasarkan waktu
$query = "SELECT invoice_number, created_at FROM transactions ORDER BY created_at DESC";
$stmt = $pdo->query($query);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filters = [
    "today" => [],
    "yesterday" => [],
    "thisWeek" => [],
    "thisMonth" => [],
    "lastMonth" => [],
    "older" => []
];

// Waktu sekarang
$now = strtotime(date('Y-m-d 00:00:00'));
$yesterday = strtotime('-1 day', $now);
$weekAgo = strtotime('-7 days', $now);
$monthAgo = strtotime('-1 month', $now);

foreach ($transactions as $transaction) {
    $created_at = strtotime($transaction['created_at']);
    
    if ($created_at >= $now) {
        $filters["today"][] = $transaction;
    } elseif ($created_at >= $yesterday) {
        $filters["yesterday"][] = $transaction;
    } elseif ($created_at >= $weekAgo) {
        $filters["thisWeek"][] = $transaction;
    } elseif ($created_at >= $monthAgo) {
        $filters["thisMonth"][] = $transaction;
    } else {
        $filters["older"][] = $transaction;
    }
}

// Kategori yang mencakup data sebelumnya
$filters["thisWeek"] = array_merge($filters["yesterday"], $filters["thisWeek"]);
$filters["thisMonth"] = array_merge($filters["thisWeek"], $filters["thisMonth"]);

function renderTransactionHTML($transactions) {
    if (empty($transactions)) {
        return "<p class='text-gray-500'>Tidak ada transaksi</p>";
    }

    $output = "";
    foreach ($transactions as $transaction) {
        $output .= "<a href='invoice.php?invoice={$transaction['invoice_number']}' class='mt-8 px-10 py-3 bg-white rounded-md drop-shadow-md flex justify-between items-center'>
                    <span class='text-lg font-medium'>".htmlspecialchars($transaction['invoice_number'])."</span>
                    <span class='text-sm text-gray-500'>".date('d-m-Y H:i:s', strtotime($transaction['created_at']))."</span>
                </a>";
    }
    return $output;
}

// Encode transaksi ke JSON untuk JavaScript
$transactionsJSON = json_encode($filters);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History | Cashier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-stone-200">
    <div class="flex flex-col p-5 mb-10">
        <h1 class="text-2xl font-medium mb-5">History</h1>

        <!-- Dropdown Filter -->
        <select id="filterSelect" class="w-96 mb-5 px-4 py-2 border rounded-md shadow">
            <option value="today">Hari Ini</option>
            <option value="yesterday">Kemarin</option>
            <option value="thisWeek">7 Hari Kebelakang</option>
            <option value="thisMonth">30 Hari Kebelakang</option>
            <option value="older">Terlama</option>
        </select>

        <!-- Kontainer Transaksi -->
        <div id="transactionContainer">
            <?= renderTransactionHTML($filters["today"]); ?>
        </div>
    </div>

    <div id="navbar"></div>

    <script src="script.js"></script>
    <script>
        $(document).ready(function () {
            var transactions = <?= $transactionsJSON; ?>;

            function renderTransactions(category) {
                var data = transactions[category] || [];
                if (data.length === 0) {
                    return "<p class='text-gray-500'>Tidak ada transaksi</p>";
                }
                return data.map(t => `
                    <a href='invoice.php?invoice=${t.invoice_number}' class='mt-3 px-10 py-3 bg-white rounded-md drop-shadow-md flex justify-between items-center'>
                        <span class='text-lg font-medium'>${t.invoice_number}</span>
                        <span class='text-sm text-gray-500'>${new Date(t.created_at).toLocaleString('id-ID')}</span>
                    </a>
                `).join("");
            }

            $('#filterSelect').change(function () {
                var selectedCategory = $(this).val();
                $('#transactionContainer').html(renderTransactions(selectedCategory));
            });
        });
    </script>
</body>
</html>
