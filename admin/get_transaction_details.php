<?php
require '../connection.php';

$id = $_GET['id'] ?? 0;

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID tidak valid']);
    exit;
}

// Ambil detail produk yang dibeli
$query = $pdo->prepare("
    SELECT td.*, p.name AS product_name
    FROM transaction_details td
    LEFT JOIN products p ON td.product_id = p.id
    WHERE td.transaction_id = ?
");
$query->execute([$id]);
$details = $query->fetchAll(PDO::FETCH_ASSOC);

// Jika produk telah dihapus dari tabel products, tampilkan nama produk sebagai "Produk telah dihapus"
foreach ($details as &$d) {
    if (!$d['product_name']) {
        $d['product_name'] = 'Produk telah dihapus';
    }
}

// Ambil info transaksi untuk discount dan final_price
$query = $pdo->prepare("SELECT discount, final_price FROM transactions WHERE id = ?");
$query->execute([$id]);
$transaction = $query->fetch(PDO::FETCH_ASSOC);

// Kirim response dalam format JSON
echo json_encode([
    'details' => $details,
    'transaction' => $transaction
]);
