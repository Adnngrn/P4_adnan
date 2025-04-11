<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'];
    $type = $_POST['type'];
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $min_quantity = $_POST['min_quantity'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Tentukan product_id dan category_id sesuai tipe diskon
    $product_id = $type === 'product' ? ($_POST['product_id'] ?? null) : null;
    $category_id = $type === 'category' ? ($_POST['category_id'] ?? null) : null;

    // Validasi data tidak boleh kosong
    if (!$type || !$discount_type || !$discount_value || !$start_date || !$end_date) {
        die("Semua data harus diisi!");
    }
    // Cek jika diskon persentase melebihi 100
    if ($discount_type === 'percentage' && $discount_value >= 100) {
        die("Diskon persentase tidak boleh lebih dari 100%.");
    }
    if ($discount_type === 'fixed' && $discount_value >= 100000) {
        die("Potongan diskon tidak boleh lebih dari Rp 100.000");
    }


    if ($id) {
        // Update diskon
        $stmt = $pdo->prepare("UPDATE discounts 
            SET name = ?, type = ?, product_id = ?, category_id = ?, discount_type = ?, discount_value = ?, min_quantity = ?, start_date = ?, end_date = ? 
            WHERE id = ?");
        $stmt->execute([$name, $type, $product_id, $category_id, $discount_type, $discount_value, $min_quantity, $start_date, $end_date, $id]);
    } else {
        // Tambah diskon baru
        $stmt = $pdo->prepare("INSERT INTO discounts (name, type, product_id, category_id, discount_type, discount_value, min_quantity, start_date, end_date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $type, $product_id, $category_id, $discount_type, $discount_value, $min_quantity, $start_date, $end_date]);
    }

    header('Location: discount.php');
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM discounts WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: discount.php');
    exit();
}
?>
