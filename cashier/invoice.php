<?php
session_start();
require '../connection.php';

if (!isset($_GET['invoice'])) {
    die('Nomor invoice tidak ditemukan!');
}

$invoice_number = $_GET['invoice'];

// Ambil data transaksi
$stmt = $pdo->prepare("SELECT t.*, u.username AS cashier_name 
                       FROM transactions t 
                       JOIN users u ON t.cashier_id = u.id 
                       WHERE t.invoice_number = ?");
$stmt->execute([$invoice_number]);
$transaction = $stmt->fetch();

if (!$transaction) {
    die('Transaksi tidak ditemukan!');
}

// Ambil detail transaksi
$stmt = $pdo->prepare("SELECT td.*, p.name AS product_name 
                       FROM transaction_details td 
                       JOIN products p ON td.product_id = p.id 
                       WHERE td.transaction_id = ?");
$stmt->execute([$transaction['id']]);
$details = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice <?= $invoice_number ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-5">

    <div class="max-w-lg mx-auto bg-white p-5 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold text-right">INVOICE</h2>
        <p class="text-sm text-gray-600 text-right"><?= $invoice_number ?></p>
        
        <div class="mt-4">
            <p><strong>Kasir:</strong> <?= htmlspecialchars($transaction['cashier_name']) ?></p>
            <p><strong>Tanggal:</strong> <?= $transaction['created_at'] ?></p>
        </div>

        <table class="w-full text-sm mt-4">
            <thead>
                <tr class="border-collapse border-2 border-black">
                    <th class="p-2">Produk</th>
                    <th class="p-2">Jumlah</th>
                    <th class="p-2">Harga Satuan</th>
                    <th class="p-2">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $item): ?>
                <tr>
                    <td class="p-2"><?= htmlspecialchars($item['product_name']) ?></td>
                    <td class="p-2 text-center"><?= $item['quantity'] ?></td>
                    <td class="p-2 text-right">Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td class="p-2 text-right">Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-4 grid grid-cols-2">
            <div class="col-start-2">
                <p class="flex justify-between"><strong>Subtotal:</strong> Rp<?= number_format($transaction['total_price'], 0, ',', '.') ?></p>
                <p class="flex justify-between text-sm px-2"><span>Diskon:</span> -Rp<?= number_format($transaction['discount'], 0, ',', '.') ?></p>
                <p class="flex justify-between text-lg font-semibold"><strong>Total:</strong> Rp.<?= number_format($transaction['final_price'], 0, ',', '.') ?></p>    
            </div>
        </div>

        <div class="text-center mt-20">
            <button onclick="window.print()" class="bg-blue-600 text-white py-2 px-4 rounded-md">Cetak</button>
            <a href="<?= $_SERVER['HTTP_REFERER'] ?? 'cashier.php' ?>" class="bg-gray-600 text-white py-2 px-4 rounded-md">Kembali</a>
        </div>
    </div>

</body>
</html>
