<?php
require '../connection.php'; // Koneksi ke database

// Ambil semua transaksi
$query = "SELECT invoice_number, created_at FROM transactions ORDER BY created_at DESC";
$stmt = $pdo->query($query);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-stone-200">
    <div class="flex flex-col p-5 mb-10">
        <h1 class="text-xl font-medium mb-5">History</h1>
        <div class="flex flex-col gap-3 w-full">
            <?php foreach ($transactions as $transaction): ?>
                <a href="invoice.php?invoice=<?= $transaction['invoice_number'] ?>" 
                   class="px-10 py-3 bg-white rounded-md drop-shadow-md flex justify-between items-center">
                    <span class="text-lg font-medium"><?= htmlspecialchars($transaction['invoice_number']) ?></span>
                    <span class="text-sm text-gray-500"><?= date('d-m-Y H:i:s', strtotime($transaction['created_at'])) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="navbar"></div>

    <script src="script.js"></script>
    <script src="cashier.js"></script>
</body>
</html>
