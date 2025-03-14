<?php
$requireCashier = true;
require '../auth.php';
require '../connection.php';

$query = "SELECT * FROM products";
$stmt = $pdo->query($query);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * FROM categories";
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query = "SELECT * FROM discounts WHERE start_date <= CURDATE() AND end_date >= CURDATE()";
$stmt = $pdo->query($query);
$discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$discountData = [];
foreach ($discounts as $discount) {
    if ($discount['type'] == 'product') {
        $discountData['product'][$discount['product_id']] = $discount;
    } elseif ($discount['type'] == 'category') {
        $discountData['category'][$discount['category_id']] = $discount;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-stone-200">
    <div class="flex mb-10">
        <div class="pb-10 w-full h-full mr-[350px]">
            <div class="flex items-center h-20 p-5 w-full gap-5 justify-end">
                <input type="text" id="searchInput" class="py-1 px-2 h-full w-1/2 rounded-md outline-0 shadow" placeholder="Cari produk...">
            </div>
            <div class="flex items-center h-20 p-5 w-full gap-5">
                <button class="h-full px-5 bg-white rounded-md shadow category-btn" data-category="all">All</button>
                <?php foreach ($categories as $category): ?>
                    <button class="h-full px-5 bg-white rounded-md shadow category-btn" data-category="<?= $category['id']; ?>">
                        <?= htmlspecialchars($category['category_name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-4 h-full w-full px-5 gap-6" id="productContainer">
                <?php foreach ($products as $product): 
                    $discount = $discountData['product'][$product['id']] ?? $discountData['category'][$product['category_id']] ?? null;
                    $discountType = $discount['discount_type'] ?? null;
                    $discountValue = $discount['discount_value'] ?? 0;
                    $minQuantity = $discount['min_quantity'] ?? 1;
                ?>
                    <div class="bg-white h-64 rounded-lg shadow p-1 product-card" 
                        data-category="<?= $product['category_id']; ?>" 
                        data-name="<?= strtolower($product['name']); ?>"
                        data-stock="<?= $product['stock']; ?>"
                        data-discount-type="<?= $discountType; ?>"
                        data-discount-value="<?= $discountValue; ?>"
                        data-min-quantity="<?= $minQuantity; ?>">
                        
                        <img src="../product_img/<?= $product['image']; ?>" class="w-full h-40 rounded-md" alt="<?= $product['name']; ?>">
                        <div class="p-2">
                            <p class="font-semibold text-sm"> <?= $product['name']; ?> </p>
                            <p class="flex justify-between text-xs text-gray-600">
                                <span>Rp <?= number_format($product['price'], 0, ',', '.'); ?></span>
                                <span class="text-xs <?= ($product['stock'] > 0) ? 'text-green-600' : 'text-red-600' ?>">Stok: <?= $product['stock'] > 0 ? $product['stock'] : 'Habis'; ?></span>
                            </p>
                            
                            <button class="mt-2 w-full bg-blue-500 text-white py-1 rounded-md add-to-cart" 
                                    data-id="<?= $product['id']; ?>" 
                                    data-name="<?= $product['name']; ?>" 
                                    data-price="<?= $product['price']; ?>" 
                                    data-stock="<?= $product['stock']; ?>"
                                    data-discount-type="<?= $discountType; ?>"
                                    data-discount-value="<?= $discountValue; ?>"
                                    data-min-quantity="<?= $minQuantity; ?>"
                                    <?= ($product['stock'] == 0) ? 'disabled' : ''; ?>>Tambah</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="grid content-between bg-white w-[350px] fixed h-screen right-0 shadow p-8" id="cartSidebar">
            <div>
                <h1 class="text-xl font-medium">Order Details</h1>
                <div class="text-sm mt-5 mx-2 mb-10" id="cartItems"></div>
            </div>
            <div class="mb-8">
                <p class="text-sm flex justify-between">Subtotal <span class="font-medium" id="subtotalPrice">Rp 0</span></p>
                <p class="text-sm flex justify-between">Diskon <span class="font-medium" id="totalDiscount">Rp 0</span></p>
                <p class="text-sm flex justify-between">TOTAL <span class="font-medium" id="totalPrice">Rp 0</span></p>
                <div class="grid grid-cols-2 gap-1 px-5 mt-10">
                    <button class="bg-blue-600 text-white rounded-md py-1" id="clearCart">Hapus Semua</button>
                    <button class="bg-blue-600 text-white rounded-md py-1" id="checkoutButton">Checkout</button>
                </div>
            </div>
        </div>
    </div>
    <div id="navbar"></div>

    <script src="script.js"></script>
    <script src="cashier.js"></script>
    <script>
        
    </script>
        
</body>
</html>

