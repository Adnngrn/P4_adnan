<?php
$requireAdmin = true;
require '../auth.php';
require '../connection.php';

$stmt = $pdo->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $stmt->fetchAll();

foreach ($categories as $kategori): ?>
    <div class="flex justify-between items-center border p-2 rounded">
        <span><?= htmlspecialchars($kategori['category_name']) ?></span>
        <div class="flex gap-2">
        <button onclick="editKategori(<?= $kategori['id'] ?>, '<?= htmlspecialchars($kategori['category_name'], ENT_QUOTES) ?>')" class="text-blue-500 hover:text-blue-700">✎</button>
        <button onclick="hapusKategori(<?= $kategori['id'] ?>)" class="text-red-500 hover:text-red-700">🗑</button>
        </div>
    </div>
<?php endforeach; 
