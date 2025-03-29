<?php
$requireAdmin = true;
require '../auth.php';
require '../connection.php';

// $query = "SELECT p.*, c.category_name FROM products p
//           JOIN discount c ON p.category_id = c.id ORDER BY p.id DESC";
// $stmt = $pdo->query($query);
// $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$search = $_GET['search'] ?? '';

$query = "SELECT d.*, 
                 p.name AS product_name, 
                 c.category_name 
          FROM discounts d
          LEFT JOIN products p ON d.product_id = p.id
          LEFT JOIN categories c ON d.category_id = c.id
          WHERE d.name LIKE :search 
             OR d.discount_type LIKE :search
             OR p.name LIKE :search
             OR c.category_name LIKE :search";

$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$search%"]); 
$discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discounts | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        td{text-align:center}
    </style>
</head>
<body class="flex bg-stone-200">
    <div id="sidebar"></div>

    <div class="ml-60  w-full px-3 py-7">
        <div class="mb-10">
            <a class="py-3 flex justify-center items-center w-32 rounded-lg border border-2 border-blue-700 text-blue-800 font-medium hover:bg-blue-700 hover:text-white" href="discount_form.php">+Discount</a>
        </div>

        <div class="flex gap-3 mb-5">

            <!-- Input Pencarian -->
             <form class="flex items-center" method="GET" >
                <input type="text" id="searchInput" name="search" placeholder="Cari diskon..." class="border rounded-s px-4 py-2">

                <button id="searchButton" class="bg-blue-500 text-white px-4 py-2 rounded-e">Cari</button>
             </form>
            
        </div>

        <table border="1" class="w-full bg-white">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Produk</th>
                    <th>Kategori</th>
                    <th>Tipe Diskon</th>
                    <th>Nilai Diskon</th>
                    <th>Jumlah Minimal</th>
                    <th>Mulai</th>
                    <th>Berkahir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="discountList">
                <?php
                $no = 1;
                foreach ($discounts as $discount): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($discount['name']); ?></td>
                        <td><?= htmlspecialchars($discount['type']); ?></td>
                        <td><?= $discount['product_name'] ? $discount['product_name'] : '-'; ?></td>
                        <td><?= $discount['category_name'] ? $discount['category_name'] : '-'; ?></td>
                        <td><?= ucfirst($discount['discount_type']); ?></td>
                        <td>
                            <?= $discount['discount_type'] === 'percentage' 
                                ? number_format($discount['discount_value'], 2, ',', '.') . '%' 
                                : 'Rp ' . number_format($discount['discount_value'], 0, ',', '.'); ?>
                        </td>
                        <td><?= $discount['min_quantity'] ? $discount['min_quantity'] : '-'; ?></td>
                        <td><?= date('d-m-Y', strtotime($discount['start_date'])); ?></td>
                        <td><?= date('d-m-Y', strtotime($discount['end_date'])); ?></td>
                        <td class="border p-2">
                            <a href="discount_form.php?id=<?= $discount['id']; ?>" class="text-blue-500">Edit</a> | 
                            <a href="discount_process.php?delete=<?= $discount['id']; ?>" class="text-red-500" 
                            onclick="return confirm('Hapus diskon ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>



            
        </table>

    </div>

<script src="script.js"></script>
<script>
$(document).ready(function () {
    $("#searchButton").click(function (e) {
        e.preventDefault(); // Mencegah reload halaman
        
        let searchText = $("#searchInput").val().trim();

        $.ajax({
            url: "discount.php",
            type: "GET",
            data: { search: searchText },
            success: function (response) {
                let tableBody = $("#discountList");
                tableBody.html($(response).find("#discountList").html());
            }
        });
    });

    // Enter Key untuk Pencarian
    $("#searchInput").on("keypress", function (e) {
        if (e.which === 13) {
            e.preventDefault();
            $("#searchButton").click();
        }
    });
});

</script>

</body>
</html>