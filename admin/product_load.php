<?php
require '../connection.php';

// Ambil kategori dan pencarian dari request
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Query dasar
$query = "SELECT p.*, c.category_name FROM products p 
          JOIN categories c ON p.category_id = c.id 
          WHERE 1=1";

// Tambahkan filter kategori jika dipilih
if (!empty($category_id)) {
    $query .= " AND p.category_id = :category_id";
}

// Tambahkan pencarian jika ada input
if (!empty($search_query)) {
    $query .= " AND p.name LIKE :search";
}

$query .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($query);

// Bind parameter
if (!empty($category_id)) {
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
}
if (!empty($search_query)) {
    $search_param = "%$search_query%";
    $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
}

$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($products) > 0) {
    $no = 1;
    foreach ($products as $row) {
        echo "
        <a href='product_detail.php?id={$row['id']}' class='w-56 h-auto bg-white drop-shadow-md rounded-xl py-2 px-2'>
            <img src='../product_img/{$row['image']}' alt='' class='max-h-40 w-full bg-red-200'>
            <div class='mt-3'>
                <h3 class='font-medium text-md'>{$row['name']}</h3>
                <p class='text-gray-400 text-sm'>{$row['category_name']}</p>
                <p class='text-md text-right'>Rp. " . number_format($row['price'], 2, ',', '.') . "</p>
                <p class='flex justify-between text-sm'><span>stok : <span>{$row['stock']}</span></span><span class='text-gray-400'>{$row['arrival_date']}</span></p>
            </div>
        </a>
        
        ";
        $no++;
    }
} else {
    echo "<tr><td colspan='8' class='text-center text-gray-500 p-3'>Tidak ada produk ditemukan.</td></tr>";
}
?>
