<?php
include '../connection.php';

// Cek apakah sedang edit atau tambah data
$id = isset($_GET['id']) ? $_GET['id'] : '';
$edit_mode = false;
$product_name = $price = $stock_id = $entry_date = $product_img = "";

// Jika ID ada, maka kita dalam mode edit
if ($id) {
    $edit_mode = true;
    $query = "SELECT * FROM products WHERE id_product = $id";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_name = $row['product_name'];
        $price = $row['price'];
        $entry_date = $row['entry_date']; // Perbaikan di sini
        $stock_id = $row['stock_id'];
        $product_img = $row['product_img'];
    }
}

// Proses Simpan (Tambah/Edit)
if (isset($_POST['submit'])) {
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $entry_date = isset($_POST['entry_date']) ? $_POST['entry_date'] : date("Y-m-d"); // Pastikan tidak undefined
    $stock_id = $_POST['stock_id'];
    $image = $_FILES['product_img']['name'];

    // Proses upload gambar jika ada
    if (!empty($image)) {
        $target = "../product_img/" . basename($image);
        move_uploaded_file($_FILES['product_img']['tmp_name'], $target);
    } else {
        $image = $product_img; // Gunakan gambar lama jika tidak ada upload baru
    }

    if ($edit_mode) {
        $query = "UPDATE products SET product_name='$name', product_img='$image', stock_id='$stock_id', price='$price', entry_date='$entry_date' WHERE id_product=$id";
    } else {
        $query = "INSERT INTO products (product_name, product_img, stock_id, price, entry_date) 
                  VALUES ('$name', '$image', '$stock_id', '$price', '$entry_date')";
    }

    if ($conn->query($query)) {
        header("Location: product.php?page=product");
        exit();
    } else {
        echo "Gagal menyimpan produk! Error: " . $conn->error;
    }
}
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
<body class="flex">
    <div id="sidebar"></div>

    <div class="ml-60 w-full px-3 py-7">
        <div class="mb-10">
            <a class="py-1 flex justify-center items-center w-24 rounded-lg border border-2 border-blue-700" href="product.php?page=product">Kembali</a>
        </div>

        <div>
            <form method="POST" enctype="multipart/form-data">
                <label>Nama Produk:</label>
                <input type="text" name="product_name" value="<?= $product_name; ?>" required><br>

                <label>Harga:</label>
                <input type="number" name="price" value="<?= $price; ?>" required><br>

                <label>Gambar:</label>
                <input type="file" name="product_img"><br>
                <?php if ($edit_mode && !empty($product_img)): ?>
                    <img src="../product_img/<?= $product_img; ?>" width="50"><br>
                <?php endif; ?>

                <label>Tanggal Masuk Produk:</label>
                <input type="date" name="entry_date" value="<?= $entry_date; ?>" required><br>

                <label>Stok:</label>
                <select name="stock_id">
                    <option value="1" <?= ($stock_id == 1) ? 'selected' : ''; ?>>Tersedia</option>
                    <option value="2" <?= ($stock_id == 2) ? 'selected' : ''; ?>>Habis</option>
                </select><br>

                <button type="submit" name="submit"><?= $edit_mode ? 'Update' : 'Simpan' ?></button>
                <a href="product.php?page=product">Batal</a>
            </form>
        </div>
    </div>

<script src="script.js"></script>
</body>
</html>
