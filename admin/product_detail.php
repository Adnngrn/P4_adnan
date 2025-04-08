<?php
require '../connection.php';

// Pastikan ID produk ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Produk tidak ditemukan.";
    exit;
}

$product_id = $_GET['id'];

// Ambil data produk berdasarkan ID
$query = "SELECT p.*, c.category_name FROM products p 
          JOIN categories c ON p.category_id = c.id 
          WHERE p.id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika produk tidak ditemukan
if (!$product) {
    echo "Produk tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="flex bg-stone-200">
    <div id="sidebar"></div>

    <div class="ml-60 w-9/12 px-3 py-7">
        <h1 class="text-2xl font-bold mb-5">Detail Produk</h1>
        <div class="flex justify-between mt-5">
            <a href="product.php" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-t-md">Kembali</a>
            <div>
                <a href="product_form.php?id=<?= $product['id']; ?>" class="inline-block bg-yellow-500 text-white px-4 py-2 rounded-t-md">Edit</a>
                 <!-- |  -->
                <!-- <a href="product_process.php?delete=<?= $product['id']; ?>" class="inline-block bg-red-500 text-white px-4 py-2 rounded-t-md" 
                onclick="return confirm('Hapus <?= $product['name']; ?>?')">
                Hapus
                </a> -->
            </div>

        </div>

        <div class="bg-white grid grid-cols-3 gap-8 p-5 h-auto rounded-b-lg shadow-md">
            <div class="h-80 w-full">
                <img src="../product_img/<?= $product['image']; ?>" class="object-contain h-80 w-full">
            </div>
            <div class="col-span-2 flex flex-col justify-between h-auto">
                <div>
                    <h2 class="text-xl font-bold"><?= $product['name']; ?></h2>
                    <p class="text-gray-600">(<?= $product['category_name']; ?>)</p>
                    <p class="mt-3 text-red-600 text-xl font-bold">Rp <?= number_format($product['price'], 2, ',', '.'); ?></p>
                    <p class="mt-3"><strong>Stok:</strong> <?= $product['stock']; ?></p>
                    <p class="mt-3"><strong>Deskripsi:</strong><br><?= $product['description']; ?></p>
                </div>
                <p class="mt-3 self-end"><strong>Tanggal Masuk:</strong> <?= $product['arrival_date']; ?></p>
                
            </div>
            
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
