<?php
require '../auth.php';
require_once '../connection.php'; // atau sesuaikan nama file koneksimu

$today = date('Y-m-d');

// Ambil diskon yang aktif hari ini
$stmt = $pdo->prepare("
    SELECT d.id, d.name, d.discount_type, d.discount_value, d.min_quantity, d.start_date, d.end_date,
           d.product_id, d.category_id,
           p.name AS product_name,
           c.category_name
    FROM discounts d
    LEFT JOIN products p ON d.product_id = p.id
    LEFT JOIN categories c ON d.category_id = c.id
    WHERE d.start_date <= :today AND d.end_date >= :today
    ORDER BY d.end_date ASC
");
$stmt->execute(['today' => $today]);
$activeDiscounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diskon | Cashier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-stone-200">
    <div class="flex flex-col p-5 mb-10">
        <!-- Menampilkan Diskon produk yang aktif berdasarkan tanggal -->
        <?php if (count($activeDiscounts) > 0): ?>
            <h1 class="text-2xl font-bold mb-4">Diskon Aktif Hari Ini (<?= date('d M Y') ?>)</h1>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($activeDiscounts as $d): ?>
                    <div class="bg-white shadow rounded-lg p-4">
                        <h2 class="text-lg font-semibold mb-1"><?= htmlspecialchars($d['name']) ?></h2>

                        <p class="text-sm text-gray-700">
                            <?= $d['product_id'] ? 'Produk: <b>' . htmlspecialchars($d['product_name']) . '</b>' : '' ?>
                            <?= $d['category_id'] ? 'Kategori: <b>' . htmlspecialchars($d['category_name']) . '</b>' : '' ?>
                        </p>

                        <p class="text-sm mt-2">
                            Diskon: 
                            <?php if ($d['discount_type'] == 'percentage'): ?>
                                <?= $d['discount_value'] ?>%
                            <?php else: ?>
                                Rp <?= number_format($d['discount_value'], 0, ',', '.') ?>
                            <?php endif; ?>
                            <?php if ($d['min_quantity'] > 1): ?>
                                <span class="text-gray-500">(min <?= $d['min_quantity'] ?> item)</span>
                            <?php endif; ?>
                        </p>

                        <p class="text-xs text-gray-500 mt-2">Berlaku: 
                            <?= date('d M Y', strtotime($d['start_date'])) ?> - 
                            <?= date('d M Y', strtotime($d['end_date'])) ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500 mt-10">Tidak ada diskon aktif hari ini.</p>
        <?php endif; ?>

    </div>

    <div id="navbar"></div>

    <script src="script.js"></script>
</body>
</html>
