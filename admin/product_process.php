<?php
require '../connection.php';

// Simpan atau Edit Produk
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $arrival_date = $_POST['arrival_date'];
    $imageName = '';

    // Proses Upload Gambar (jika ada)
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../product_img/" . $imageName);
    }

    if ($id) {
        // Edit produk
        if ($imageName) {
            $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, category_id=?, price=?, stock=?, image=?, arrival_date=? WHERE id=?");
            $stmt->execute([$name, $description, $category_id, $price, $stock, $imageName, $arrival_date, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, category_id=?, price=?, stock=?, arrival_date=? WHERE id=?");
            $stmt->execute([$name, $description, $category_id, $price, $stock, $arrival_date, $id]);
        }
    } else {
        // Tambah produk baru
        $stmt = $pdo->prepare("INSERT INTO products (name, description, category_id, price, stock, image, arrival_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $category_id, $price, $stock, $imageName, $arrival_date]);
    }

    header("Location: product.php");
    exit;
}

// Hapus Produk
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Hapus gambar jika ada
    $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($product && !empty($product['image'])) {
        unlink("../product_img/" . $product['image']);
    }

    // Hapus produk dari database
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: product.php");
    exit;
}
