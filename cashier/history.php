<?php
session_start();

// Daftar Produk
$products = [
    1 => ['name' => 'Produk A', 'price' => 10000],
    2 => ['name' => 'Produk B', 'price' => 15000],
    3 => ['name' => 'Produk C', 'price' => 20000],
    4 => ['name' => 'Produk D', 'price' => 25000],
    5 => ['name' => 'Produk E', 'price' => 30000],
];

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah ke keranjang
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $quantity;
    } else {
        $_SESSION['cart'][$id] = $quantity;
    }
}

// Kurangi dari keranjang
if (isset($_POST['decrease_from_cart'])) {
    $id = $_POST['product_id'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]--;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
}

// Hapus dari keranjang
if (isset($_POST['remove_from_cart'])) {
    $id = $_POST['product_id'];
    unset($_SESSION['cart'][$id]);
}

// Hitung total harga dan diskon
function calculate_total() {
    global $products;
    $total = 0;
    
    foreach ($_SESSION['cart'] as $id => $qty) {
        $total += $products[$id]['price'] * $qty;
    }
    
    $discount = 0;
    if ($total > 50000) {
        $discount = $total * 0.1; // Diskon 10% jika total belanja di atas 50.000
    } elseif (array_sum($_SESSION['cart']) > 5) {
        $discount = $total * 0.05; // Diskon 5% jika jumlah barang lebih dari 5
    }
    
    return ['total' => $total, 'discount' => $discount, 'final_total' => $total - $discount];
}

$totals = calculate_total();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kasir PHP Native</title>
</head>
<body>
    <h2>Daftar Produk</h2>
    <?php foreach ($products as $id => $product): ?>
        <form method="POST">
            <p>
                <?= $product['name']; ?> - Rp<?= number_format($product['price']); ?>
                <input type="hidden" name="product_id" value="<?= $id; ?>">
                <input type="number" name="quantity" value="1" min="1">
                <button type="submit" name="add_to_cart">Tambah</button>
            </p>
        </form>
    <?php endforeach; ?>
    
    <h2>Keranjang</h2>
    <?php foreach ($_SESSION['cart'] as $id => $qty): ?>
        <form method="POST">
            <p>
                <?= $products[$id]['name']; ?> - <?= $qty; ?> x Rp<?= number_format($products[$id]['price']); ?>
                <button type="submit" name="decrease_from_cart">Kurang</button>
                <button type="submit" name="remove_from_cart">Hapus</button>
                <input type="hidden" name="product_id" value="<?= $id; ?>">
            </p>
        </form>
    <?php endforeach; ?>
    
    <h2>Invoice</h2>
    <p>Total: Rp<?= number_format($totals['total']); ?></p>
    <p>Diskon: Rp<?= number_format($totals['discount']); ?></p>
    <p>Total Bayar: Rp<?= number_format($totals['final_total']); ?></p>
</body>
</html>
