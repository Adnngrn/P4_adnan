<?php
include '../connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data produk berdasarkan ID
    $query = "SELECT product_img FROM products WHERE id_product = $id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = $row['product_img'];

        // Hapus file gambar jika ada
        if (!empty($image) && file_exists("../product_img/" . $image)) {
            unlink("../product_img/" . $image);
        }

        // Hapus data produk dari database
        $delete_query = "DELETE FROM products WHERE id_product = $id";
        if ($conn->query($delete_query)) {
            header("Location: product.php?page=product&status=deleted");
            exit();
        } else {
            echo "Gagal menghapus produk! Error: " . $conn->error;
        }
    } else {
        echo "Produk tidak ditemukan!";
    }
} else {
    echo "ID produk tidak valid!";
}
?>
