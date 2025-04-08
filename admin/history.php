<?php
$requireAdmin = true;
require '../auth.php';
require '../connection.php';

$start = $_GET['start_date'] ?? null;
$end = $_GET['end_date'] ?? null;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$params = [];
$where = 'WHERE 1'; // default kondisi true, supaya bisa tambah AND dengan mudah

if ($start && $end) {
    $where .= " AND DATE(created_at) BETWEEN ? AND ?";
    $params[] = $start;
    $params[] = $end;
}

// Hitung total transaksi
$countQuery = $pdo->prepare("SELECT COUNT(*) FROM transactions $where");
$countQuery->execute($params);
$totalData = $countQuery->fetchColumn();
$totalPages = ceil($totalData / $limit);

// Ambil transaksi sesuai halaman dengan filter & pagination
$mainQuery = $pdo->prepare("SELECT * FROM transactions $where ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
$mainQuery->execute($params);

$transactions = $mainQuery->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>History | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style> td { text-align: center; } </style>
</head>
<body class="flex bg-stone-200">
    <div id="sidebar"></div>

    <div class="ml-60 w-full px-5 py-7">
        <h1 class="text-2xl font-semibold mb-4">Riwayat Transaksi</h1>

        <!-- Form Filter Tanggal -->
        <form method="GET" class="flex gap-3 items-center mb-6">
            <input type="date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>" class="border px-3 py-2 rounded-md">
            <input type="date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>" class="border px-3 py-2 rounded-md">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Filter</button>
        </form>

        <table class="w-full bg-white rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-3 text-center">No</th>
                    <th class="px-4 py-3 text-center">Tanggal</th>
                    <th class="px-4 py-3 text-center">Total</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $i => $t): ?>
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="px-4 py-2"><?= $offset + $i + 1 ?></td>
                    <td class="px-4 py-2"><?= date('d M Y H:i', strtotime($t['created_at'])) ?></td>
                    <td class="px-4 py-2">Rp<?= number_format($t['final_price'], 0, ',', '.') ?></td>
                    <td class="px-4 py-2">
                        <button 
                            class="bg-blue-500 text-white px-3 py-1 rounded detail-btn" 
                            data-id="<?= $t['id'] ?>">Lihat Detail</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-5 flex justify-center space-x-2">
            <?php if ($totalPages > 1): ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a 
                        href="?page=<?= $i ?><?= $start ? "&start_date=$start" : '' ?><?= $end ? "&end_date=$end" : '' ?>"
                        class="px-3 py-1 rounded-md <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white border text-gray-700' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            <?php endif; ?>
        </div>


        <!-- Modal -->
        <div id="modalDetail" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
            <div class="bg-white w-2/3 rounded-lg shadow-lg p-6 relative">
                <button id="closeModal" class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-xl">&times;</button>
                <h2 class="text-xl font-semibold mb-3">Detail Transaksi</h2>
                <table class="w-full bg-white rounded-lg shadow overflow-hidden">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-4 py-2 text-center">Produk</th>
                            <th class="px-4 py-2 text-center">Harga</th>
                            <th class="px-4 py-2 text-center">Jumlah</th>
                            <th class="px-4 py-2 text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        $('.detail-btn').on('click', function () {
            const id = $(this).data('id');

            $.ajax({
                url: 'get_transaction_details.php',
                method: 'GET',
                data: { id },
                dataType: 'json',
                success: function (res) {
                    let rows = '';
                    let total = 0;

                    res.details.forEach(item => {
                        const subtotal = item.price * item.quantity;
                        total += subtotal;
                        rows += `
                            <tr class="border-t">
                                <td class="px-4 py-2">${item.product_name}</td>
                                <td class="px-4 py-2">Rp${Number(item.price).toLocaleString()}</td>
                                <td class="px-4 py-2">${item.quantity}</td>
                                <td class="px-4 py-2">Rp${Number(subtotal).toLocaleString()}</td>
                            </tr>
                        `;
                    });

                    const discount = Number(res.transaction.discount);
                    const finalPrice = Number(res.transaction.final_price);

                    rows += `
                        <tr class="border-t bg-yellow-50">
                            <td colspan="3" class="px-4 py-2 text-right font-semibold">Potongan Diskon</td>
                            <td class="px-4 py-2 text-red-500">- Rp${discount.toLocaleString()}</td>
                        </tr>
                        <tr class="border-t bg-gray-100 font-bold">
                            <td colspan="3" class="px-4 py-2 text-right">Total Dibayar</td>
                            <td class="px-4 py-2">Rp${finalPrice.toLocaleString()}</td>
                        </tr>
                    `;

                    $('#detailBody').html(rows);
                    $('#modalDetail').removeClass('hidden').addClass('flex');
                },
                error: function () {
                    alert('Gagal mengambil detail transaksi');
                }
            });
        });

        $('#closeModal').on('click', function () {
            $('#modalDetail').addClass('hidden').removeClass('flex');
        });

        $('#modalDetail').on('click', function (e) {
            if (e.target === this) {
                $(this).addClass('hidden').removeClass('flex');
            }
        });
    });
    </script>

    <script src="script.js"></script>
</body>
</html>
