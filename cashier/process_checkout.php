<?php
session_start();
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan user adalah kasir yang login
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $cashier_id = $_SESSION['user_id'];
    $cart = json_decode(file_get_contents('php://input'), true);

    if (empty($cart)) {
        echo json_encode(['status' => 'error', 'message' => 'Keranjang kosong!']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Generate nomor invoice unik
        $invoice_number = 'INV-' . time();

        // Hitung total harga dan diskon
        $total_price = 0;
        $total_discount = 0;
        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['qty'];
            $discountAmount = 0;

            if ($item['discountType'] === 'percentage' && $item['qty'] >= $item['minQuantity']) {
                $discountAmount = ($item['price'] * $item['discountValue'] / 100) * $item['qty'];
            } elseif ($item['discountType'] === 'fixed' && $item['qty'] >= $item['minQuantity']) {
                $discountAmount = $item['discountValue'];
            }

            $total_price += $subtotal;
            $total_discount += $discountAmount;
        }

        $final_price = $total_price - $total_discount;

        // Simpan transaksi ke tabel `transactions`
        $stmt = $pdo->prepare("INSERT INTO transactions (invoice_number, cashier_id, total_price, discount, final_price) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$invoice_number, $cashier_id, $total_price, $total_discount, $final_price]);

        // Ambil ID transaksi terakhir
        $transaction_id = $pdo->lastInsertId();

        // Simpan detail transaksi ke `transaction_details` dan update stok produk
        $stmt_detail = $pdo->prepare("INSERT INTO transaction_details (transaction_id, product_id, quantity, price, subtotal) 
                                      VALUES (?, ?, ?, ?, ?)");
        $stmt_update_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['qty'];

            // Simpan detail transaksi
            $stmt_detail->execute([$transaction_id, $item['id'], $item['qty'], $item['price'], $subtotal]);

            // Kurangi stok produk
            $stmt_update_stock->execute([$item['qty'], $item['id']]);
        }

        $pdo->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Transaksi berhasil!',
            'invoice' => $invoice_number,
            'redirect' => 'invoice.php?invoice=' . $invoice_number
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode tidak diizinkan']);
}
