<?php
$requireAdmin = true;
require '../auth.php';
require '../connection.php';

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? null;
$nama = $_POST['nama_kategori'] ?? '';

try {
  if ($action === 'tambah') {
    if (!empty($nama)) {
      $stmt = $pdo->prepare("INSERT INTO categories (category_name) VALUES (:nama)");
      $stmt->execute([':nama' => $nama]);
      echo "Kategori ditambahkan";
    }
  }

  elseif ($action === 'edit') {
    if (!empty($id) && !empty($nama)) {
      $stmt = $pdo->prepare("UPDATE categories SET category_name = :nama WHERE id = :id");
      $stmt->execute([':nama' => $nama, ':id' => $id]);
      echo "Kategori diubah";
    }
  }

  elseif ($action === 'hapus') {
    if (!empty($id)) {
      $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
      $stmt->execute([':id' => $id]);
      echo "Kategori dihapus";
    }
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
