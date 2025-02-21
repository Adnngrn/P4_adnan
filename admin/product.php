<?php
include '../connection.php';

$query = "SELECT p.*, s.stock FROM products p 
          JOIN stocks s ON p.stock_id = s.id_stock ORDER BY p.entry_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        td{text-align:center}
    </style>
</head>
<body class="flex">
    <div id="sidebar"></div>

    <div class="ml-60  w-full px-3 py-7">
        <div class="mb-10">
            <a class="py-3 flex justify-center items-center w-32 rounded-lg border border-2 border-blue-700" href="form_product.php?page=product">+Product</a>
        </div>
        <!-- <div class="grid grid-cols-4 gap-3 w-full">
            <div class="bg-blue-300">tolong</div>
            <div class="bg-blue-300">tolong</div>
            <div class="bg-blue-300">tolong</div>
            <div class="bg-blue-300">tolong</div>
        </div> -->
        <table border="1" class="w-full">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Gambar</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Tanggal Masuk</th>
                <th>Aksi</th>
            </tr>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['product_name']; ?></td>
                <td><img src="../product_img/<?= $row['product_img']; ?>" width="50"></td>
                <td>Rp <?= number_format($row['price'], 2, ',', '.'); ?></td>
                <td><?= ucfirst($row['stock']); ?></td>
                <td><?= $row['entry_date']; ?></td>
                <td>
                    <a href="form_product.php?id=<?= $row['id_product']; ?>">Edit</a> | 
                    <a href="delete.php?id=<?= $row['id_product']; ?>" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

<script src="script.js"></script>
</body>
</html>