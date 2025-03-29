<?php
$requireAdmin = true;
require '../auth.php';
require '../connection.php';

// $query = "SELECT p.*, c.category_name FROM products p
//           JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC";
// $stmt = $pdo->query($query);
// $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Products | Admin</title>
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
            <a class="py-3 flex justify-center items-center w-32 rounded-lg border border-2 border-blue-700 text-blue-800 font-medium hover:bg-blue-700 hover:text-white" href="product_form.php">+Product</a>
        </div>
        <!-- Dropdown Filter -->
        <!-- Form Filter dan Pencarian -->
        <div class="flex gap-3 mb-5">
        <!-- Dropdown Filter Kategori -->
            <select id="categoryFilter" class="border rounded px-4 py-2">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['category_name'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Input Pencarian -->
             <div class="flex items-center">
                <input type="text" id="searchInput" placeholder="Cari produk..." class="border rounded-s px-4 py-2">

                <button id="searchButton" class="bg-blue-500 text-white px-4 py-2 rounded-e">Cari</button>
             </div>
            
        </div>

        <!-- <div class="grid grid-cols-4 gap-3 w-full">
            <div class="bg-blue-300">tolong</div>
            <div class="bg-blue-300">tolong</div>
            <div class="bg-blue-300">tolong</div>
            <div class="bg-blue-300">tolong</div>
        </div> -->
        

        <div id="productList" class="w-full grid grid-cols-4"></div>

    </div>

<script src="script.js"></script>
 <script>
$(document).ready(function(){

    function loadProducts(categoryId = "", searchQuery = "") {
        $.ajax({
            url: "product_load.php",
            type: "GET",
            data: { category_id: categoryId, search: searchQuery },
            success: function(data) {
                $("#productList").html(data);
            }
        });
    }

    // Load semua produk saat halaman pertama kali dibuka
    loadProducts();

    // Saat dropdown kategori diubah, filter produk
    $("#categoryFilter").change(function() {
        let categoryId = $(this).val();
        let searchQuery = $("#searchInput").val();
        loadProducts(categoryId, searchQuery);
    });

    // Saat tombol cari ditekan
    $("#searchButton").click(function() {
        let categoryId = $("#categoryFilter").val();
        let searchQuery = $("#searchInput").val();
        loadProducts(categoryId, searchQuery);
    });

    // Bisa juga menekan Enter untuk pencarian
    $("#searchInput").keypress(function(event) {
        if (event.which == 13) { // 13 = Enter
            let categoryId = $("#categoryFilter").val();
            let searchQuery = $("#searchInput").val();
            loadProducts(categoryId, searchQuery);
        }
    });
});

 </script>
</body>
</html>