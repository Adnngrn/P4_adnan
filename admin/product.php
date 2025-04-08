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
    <h1 class="text-2xl font-medium mb-5">Produk</h1>
        <div class="mb-10 flex gap-3">
            <a class="py-3 flex justify-center items-center w-32 rounded-lg border border-2 border-blue-700 text-blue-800 font-medium hover:bg-blue-700 hover:text-white" href="product_form.php">+Produk</a>
            <button onclick="openKategoriModal()" class="py-3 flex justify-center items-center w-32 rounded-lg border border-2 border-blue-700 text-blue-800 font-medium hover:bg-blue-700 hover:text-white">+Kategori</a>
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
        

        <div id="productList" class="w-full grid grid-cols-4 gap-5"></div>

    </div>

<!-- Modal Background -->
<div id="kategoriModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
<!-- Modal Content -->
    <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg relative">
        <h2 class="text-xl font-semibold mb-4">Manajemen Kategori</h2>

        <!-- Daftar Kategori -->
        <div id="kategoriList" class="space-y-2 mb-6 max-h-60 overflow-y-auto">
        <!-- Data kategori diisi lewat AJAX -->
        </div>

        <!-- Form Tambah Kategori -->
        <form id="formTambahKategori" class="flex gap-2">
            <input type="text" name="nama_kategori" placeholder="Nama kategori baru" required
                    class="flex-1 border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                Tambah
            </button>
            <button type="button" onclick="resetFormKategori()" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">
                Batal
            </button>

        </form>

        <!-- Tombol Tutup -->
        <button onclick="closeKategoriModal()" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
        ✕
        </button>
    </div>
</div>


<script src="script.js"></script>
<script>
    let editMode = false;
    let editId = null;

    function openKategoriModal() {
        document.getElementById('kategoriModal').classList.remove('hidden');
        loadKategori();
        resetFormKategori();
    }

    function closeKategoriModal() {
        document.getElementById('kategoriModal').classList.add('hidden');
        resetFormKategori();
    }

    function loadKategori() {
        fetch('category_load.php')
        .then(res => res.text())
        .then(data => {
            document.getElementById('kategoriList').innerHTML = data;
        });
    }

    function editKategori(id, namaLama) {
        editMode = true;
        editId = id;
        document.querySelector('input[name="nama_kategori"]').value = namaLama;

        const btn = document.querySelector('#formTambahKategori button[type="submit"]');
        btn.textContent = "Edit";
        btn.classList.remove('bg-green-600');
        btn.classList.add('bg-yellow-500', 'hover:bg-yellow-600');
    }

    function resetFormKategori() {
        editMode = false;
        editId = null;
        document.querySelector('#formTambahKategori').reset();

        const btn = document.querySelector('#formTambahKategori button[type="submit"]');
        btn.textContent = "Tambah";
        btn.classList.remove('bg-yellow-500', 'hover:bg-yellow-600');
        btn.classList.add('bg-green-600', 'hover:bg-green-700');
    }

    // Tangani submit form
    document.getElementById('formTambahKategori').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        if (editMode) {
        formData.append('action', 'edit');
        formData.append('id', editId);
        } else {
        formData.append('action', 'tambah');
        }

        fetch('category_process.php', {
        method: 'POST',
        body: formData
        }).then(res => res.text())
        .then(response => {
            loadKategori();
            this.reset();
            resetFormKategori();
        });
    });

    function hapusKategori(id) {
        if (confirm("Yakin ingin menghapus kategori ini?")) {
        const formData = new FormData();
        formData.append('action', 'hapus');
        formData.append('id', id);

        fetch('category_process.php', {
            method: 'POST',
            body: formData
        }).then(res => res.text())
            .then(response => {
            loadKategori();
            resetFormKategori();
            });
        }
    }
</script>


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