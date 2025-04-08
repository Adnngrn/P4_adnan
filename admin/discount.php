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
    <h1 class="text-2xl font-medium mb-5">Diskon</h1>
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

        <table class="w-full bg-white rounded-lg shadow overflow-hidden">
    <thead class="bg-gray-100 text-left ">
        <tr>
            <th class="px-4 py-3 text-center">No</th>
            <th class="px-4 py-3 text-center">Judul</th>
            <th class="px-4 py-3 text-center">Tipe</th>
            <th class="px-4 py-3 text-center">Produk</th>
            <th class="px-4 py-3 text-center">Kategori</th>
            <th class="px-4 py-3 text-center">Tipe Diskon</th>
            <th class="px-4 py-3 text-center">Nilai Diskon</th>
            <th class="px-4 py-3 text-center">Jumlah Minimal</th>
            <th class="px-4 py-3 text-center">Mulai</th>
            <th class="px-4 py-3 text-center">Berakhir</th>
            <th class="px-4 py-3 text-center">Status</th>
            <th class="px-4 py-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody id="discountList">
        <?php
        $no = 1;
        $today = date('Y-m-d');
        foreach ($discounts as $discount): 
            $isActive = ($today >= $discount['start_date'] && $today <= $discount['end_date']);
        ?>
        <tr class="border-t">
            <td class="px-4 py-2"><?= $no++; ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($discount['name']); ?></td>
            <td class="px-4 py-2"><?= htmlspecialchars($discount['type']); ?></td>
            <td class="px-4 py-2"><?= $discount['product_name'] ?: '-'; ?></td>
            <td class="px-4 py-2"><?= $discount['category_name'] ?: '-'; ?></td>
            <td class="px-4 py-2"><?= ucfirst($discount['discount_type']); ?></td>
            <td class="px-4 py-2">
                <?= $discount['discount_type'] === 'percentage' 
                    ? number_format($discount['discount_value'], 2, ',', '.') . '%' 
                    : 'Rp ' . number_format($discount['discount_value'], 0, ',', '.'); ?>
            </td>
            <td class="px-4 py-2"><?= $discount['min_quantity'] ?: '-'; ?></td>
            <td class="px-4 py-2"><?= date('d-m-Y', strtotime($discount['start_date'])); ?></td>
            <td class="px-4 py-2"><?= date('d-m-Y', strtotime($discount['end_date'])); ?></td>
            <td class="px-4 py-2">
                <span class="px-3 py-1 rounded-full text-white text-sm font-medium <?= $isActive ? 'bg-green-500' : 'bg-red-500' ?>"></span>
            </td>
            <td class="px-4 py-2 text-center">
                <a href="discount_form.php?id=<?= $discount['id']; ?>" class="bg-yellow-400 text-white px-3 py-1 rounded mr-1 inline-block">Edit</a>
                <a href="discount_process.php?delete=<?= $discount['id']; ?>" class="bg-red-500 text-white px-3 py-1 rounded inline-block"
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