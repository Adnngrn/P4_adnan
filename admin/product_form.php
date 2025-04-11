<?php
require '../connection.php';

// Cek apakah sedang edit atau tambah produk
$product = ['id' => '', 'name' => '', 'description' => '', 'category_id' => '', 'price' => '', 'stock' => '', 'image' => '', 'arrival_date' => ''];

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Ambil data kategori untuk dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div id="sidebar"></div>

    <div class="ml-60 w-9/12 px-3 py-7">
        <h2 class="text-2xl font-bold mb-4"><?= $product['id'] ? 'Edit' : 'Tambah' ?> Produk</h2>

        <form action="product_process.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 w-98 shadow-md">
            <input type="hidden" name="id" value="<?= $product['id'] ?>">

            <label>Nama Produk:</label>
            <input type="text" name="name" value="<?= $product['name'] ?>" class="border p-2 w-full" required>

            <label class="block mt-2">Deskripsi:</label>
            <textarea name="description" class="border p-2 w-full"><?= $product['description'] ?></textarea>

            <label class="block mt-2">Kategori:</label>
            <select name="category_id" class="border p-2 w-full" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $product['category_id'] == $category['id'] ? 'selected' : '' ?>>
                        <?= $category['category_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="block mt-2">Harga:</label>
            <input type="number" name="price" value="<?= $product['price'] ?>" class="border p-2 w-full" required>

            <label class="block mt-2">Stok:</label>
            <input type="number" name="stock" value="<?= $product['stock'] ?>" class="border p-2 w-full" required>

            <label class="block mt-2">Tanggal Masuk:</label>
            <input type="date" name="arrival_date" value="<?= $product['arrival_date'] ?>" class="border p-2 w-full" required>

            <label class="block mt-2">Gambar Produk:</label>
            <input type="file" name="image" accept="image/*" class="border p-2 w-full">
            <?php if (!empty($product['image'])): ?>
                <img src="../product_img/<?= $product['image'] ?>" class="w-32 mt-2">
            <?php endif; ?>

            <button type="submit" name="save" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md">
                Simpan
            </button>
            <a href="product.php" class="px-4 py-2 bg-gray-500 text-white rounded-md">Batal</a>
        </form>
    </div>

    <script src="script.js"></script>
    <script>
        $(document).ready(function () {
            $('form').on('submit', function (e) {
                const harga = parseInt($('input[name="price"]').val());
                if (harga < 1000) {
                    alert("Harga produk tidak boleh kurang dari 1000!");
                    e.preventDefault(); // Mencegah form dikirim
                }
            });
        });
    </script>

</body>
</html>
