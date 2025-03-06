<?php
require '../connection.php';

// Cek apakah sedang edit atau tambah diskon
$discount = [
    'id' => '','name' => '', 'type' => '', 'product_id' => '', 'category_id' => '',
    'discount_type' => '', 'discount_value' => '', 'min_quantity' => '1',
    'start_date' => '', 'end_date' => ''
];

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM discounts WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $discount = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ambil data produk dan kategori untuk dropdown
$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Diskon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">

    <div id="sidebar"></div>

    <div class="ml-60 w-9/12 px-3 py-7">
        <h2 class="text-2xl font-bold mb-4"><?= $discount['id'] ? 'Edit' : 'Tambah' ?> Diskon</h2>

        <form action="discount_process.php" method="POST" class="bg-white p-6 w-98 shadow-md">
            <input type="hidden" name="id" value="<?= $discount['id'] ?>">

            <label>Nama Diskon:</label>
            <input type="text" name="name" value="<?= $discount['name'] ?>" 
                class="border p-2 w-full" required>

            <label>Tipe Diskon:</label>
            <select name="type" id="discountType" class="border p-2 w-full" required>
                <option value="">Pilih Tipe</option>
                <option value="product" <?= $discount['type'] == 'product' ? 'selected' : '' ?>>Produk</option>
                <option value="category" <?= $discount['type'] == 'category' ? 'selected' : '' ?>>Kategori</option>
            </select>

            <div id="productSelect" class="mt-2 ml-5" style="display: none;">
                <label>Pilih Produk:</label>
                <select name="product_id" class="border p-2 w-full">
                    <option value="">Pilih Produk</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?= $product['id'] ?>" <?= $discount['product_id'] == $product['id'] ? 'selected' : '' ?>>
                            <?= $product['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="categorySelect" class="mt-2 ml-5" style="display: none;">
                <label>Pilih Kategori:</label>
                <select name="category_id" class="border p-2 w-full">
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $discount['category_id'] == $category['id'] ? 'selected' : '' ?>>
                            <?= $category['category_name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <label class="block mt-2">Jenis Diskon:</label>
            <select name="discount_type" class="border p-2 w-full" required>
                <option value="percentage" <?= $discount['discount_type'] == 'percentage' ? 'selected' : '' ?>>Persentase</option>
                <option value="fixed" <?= $discount['discount_type'] == 'fixed' ? 'selected' : '' ?>>Nominal</option>
            </select>

            <label class="block mt-2">Nilai Diskon:</label>
            <input type="number" name="discount_value" value="<?= $discount['discount_value'] ?>" 
                class="border p-2 w-full" required>

            <label class="block mt-2">Minimal Pembelian:</label>
            <input type="number" name="min_quantity" value="<?= $discount['min_quantity'] ?>" class="border p-2 w-full">

            <label class="block mt-2">Tanggal Mulai:</label>
            <input type="date" name="start_date" value="<?= $discount['start_date'] ?>" class="border p-2 w-full" required>

            <label class="block mt-2">Tanggal Berakhir:</label>
            <input type="date" name="end_date" value="<?= $discount['end_date'] ?>" class="border p-2 w-full" required>

            <button type="submit" name="save" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md">
                Simpan
            </button>
            <a href="discount.php" class="px-4 py-2 bg-gray-500 text-white rounded-md">Batal</a>
        </form>

    </div>

    <script src="script.js"></script>
    <script>
        $(document).ready(function() {
            function toggleFields() {
                let type = $('#discountType').val();
                $('#productSelect').toggle(type === 'product');
                $('#categorySelect').toggle(type === 'category');
            }
            toggleFields();
            $('#discountType').change(toggleFields);
        });
    </script>
</body>
</html>
