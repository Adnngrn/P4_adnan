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
        <!-- MENU PRODUK -->
        <div class="pb-10 w-full h-full mr-[350px]">
            <!-- SEARCH -->
            <div class="flex items-center h-20 p-5 w-full gap-5 justify-end">
                <input type="text" id="searchInput" class="py-1 px-2 h-full w-1/2 rounded-md outline-0 shadow" placeholder="Cari produk...">
            </div>
            <!-- KATEGORI -->
            <div class="flex items-center h-20 p-5 w-full gap-5">
                <button class="h-full px-5 bg-white rounded-md shadow category-btn" data-category="all">All</button>
                <?php foreach ($categories as $category): ?>
                    <button class="h-full px-5 bg-white rounded-md shadow category-btn" data-category="<?= $category['id']; ?>">
                        <?= htmlspecialchars($category['category_name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- SEMUA PRODUK -->
            <div class="grid grid-cols-4 h-full w-full px-5 gap-6" id="productContainer">
                <?php foreach ($products as $product): 
                    $discount = $discountData['product'][$product['id']] ?? $discountData['category'][$product['category_id']] ?? null;
                    $discountType = $discount['discount_type'] ?? null;
                    $discountValue = $discount['discount_value'] ?? 0;
                    $minQuantity = $discount['min_quantity'] ?? 1;
                ?>
                    <div class="bg-white max-w-72 h-auto grid content-between rounded-lg shadow p-1 product-card" 
                        data-category="<?= $product['category_id']; ?>" 
                        data-name="<?= strtolower($product['name']); ?>"
                        data-stock="<?= $product['stock']; ?>"
                        data-discount-type="<?= $discountType; ?>"
                        data-discount-value="<?= $discountValue; ?>"
                        data-min-quantity="<?= $minQuantity; ?>">
                        
                        
                        <div class='h-40 w-full'>
                            <img 
                            src="../product_img/<?= $product['image']; ?>" 
                            class="object-contain h-40 w-full rounded-md product-image cursor-pointer" 
                            alt="<?= $product['name']; ?>"
                            data-name="<?= htmlspecialchars($product['name']); ?>"
                            data-price="<?= number_format($product['price'], 0, ',', '.'); ?>"
                            data-description="<?= htmlspecialchars($product['description']); ?>"
                            data-stock="<?= $product['stock']; ?>"
                            data-image="../product_img/<?= $product['image']; ?>"
                            data-discount-type="<?= $discountType; ?>"
                            data-discount-value="<?= $discountValue; ?>"
                            data-min-quantity="<?= $minQuantity; ?>"
                        >
                        </div>
                        <div class="p-2">
                            <p class="font-semibold text-sm"> <?= $product['name']; ?> </p>
                            <p class="flex justify-between text-xs text-gray-600">
                                <span>Rp <?= number_format($product['price'], 0, ',', '.'); ?></span>
                                <span class="text-xs <?= ($product['stock'] > 0) ? 'text-green-600' : 'text-red-600' ?>">Stok: <?= $product['stock'] > 0 ? $product['stock'] : 'Habis'; ?></span>
                            </p>
                            
                            <?php
                                $isOutOfStock = $product['stock'] == 0;
                                $buttonClass = $isOutOfStock ? 'bg-red-500 cursor-not-allowed' : 'bg-blue-500 hover:bg-blue-600';
                                $buttonText = $isOutOfStock ? 'Habis' : 'Tambah';
                            ?>
                            <button class="mt-2 w-full <?= $buttonClass ?> text-white py-1 rounded-md add-to-cart"
                                data-id="<?= $product['id']; ?>"
                                data-name="<?= $product['name']; ?>"
                                data-price="<?= $product['price']; ?>"
                                data-stock="<?= $product['stock']; ?>"
                                data-discount-type="<?= $discountType; ?>"
                                data-discount-value="<?= $discountValue; ?>"
                                data-min-quantity="<?= $minQuantity; ?>"
                                <?= $isOutOfStock ? 'disabled' : '' ?>>
                                <?= $buttonText ?>
                            </button>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- SIDEBAR -->
        <div class="grid content-between bg-white w-[350px] fixed h-screen right-0 shadow p-8" id="cartSidebar">
            <div>
                <h1 class="text-xl font-medium">Order Details</h1>
                <!-- KERANJANG -->
                <div class="text-sm mt-5 mx-2 mb-2 overflow-y-auto max-h-[calc(100vh-260px)]" id="cartItems">
                    <!-- PRODUK -->
                </div>
            </div>
            <!-- HARGA -->
            <div class="mb-8 border-t pt-2">
                <p class="text-sm flex justify-between">Subtotal <span class="font-medium" id="subtotalPrice">Rp 0</span></p>
                <p class="text-sm flex justify-between">Diskon <span class="font-medium" id="totalDiscount">Rp 0</span></p>
                <p class="text-sm flex justify-between">TOTAL <span class="font-medium" id="totalPrice">Rp 0</span></p>
                <div class="grid grid-cols-2 gap-1 px-5 mt-5">
                    <button class="bg-blue-600 text-white rounded-md py-1" id="clearCart">Hapus Semua</button>
                    <button class="bg-blue-600 text-white rounded-md py-1" id="checkoutButton">Checkout</button>
                </div>
            </div>
        </div>

    </div>
    <div id="navbar"></div>
    <!-- MODAL DETAIL PRODUK -->
    <div id="productModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
            <button id="closeModal" class="absolute top-2 right-2 text-gray-600 hover:text-black">&times;</button>
            <div id="modalContent">
                <img id="modalImage" src="" class="h-48 w-full object-contain rounded mb-4" alt="Product Image">
                <h2 class="text-lg font-bold" id="modalName"></h2>
                <p class="text-sm text-gray-600 mb-2" id="modalStock"></p>
                <p class="text-md font-medium mt-2 text-red-700" id="modalPrice"></p>
                <p class="text-sm" id="modalDiscount"></p>
                <p class="text-sm mt-2" id="modalDescript"></p>
            </div>
        </div>
    </div>


    
    <script src="script.js"></script>
    <script src="cashier.js"></script>
    <script>
        // Klik gambar produk untuk tampilkan modal
    $(document).on('click', '.product-image', function () {
        const name = $(this).data('name');
        const price = $(this).data('price');
        const stock = $(this).data('stock');
        const image = $(this).data('image');
        const description = $(this).data('description');
        const discountType = $(this).data('discount-type');
        const discountValue = $(this).data('discount-value');
        const minQuantity = $(this).data('min-quantity');

        $('#modalImage').attr('src', image);
        $('#modalName').text(name);
        $('#modalDescript').text(description);
        $('#modalStock').text(`Stok: ${stock > 0 ? stock : 'Habis'}`);
        $('#modalPrice').text(`Rp ${price}`);

        let discountText = 'Diskon: Tidak ada';
        if (discountType === 'percentage') {
            discountText = `Diskon: ${discountValue}% (min. ${minQuantity})`;
        } else if (discountType === 'fixed') {
            discountText = `Diskon: Rp ${parseInt(discountValue).toLocaleString('id-ID')} (min. ${minQuantity})`;
        }
        $('#modalDiscount').text(discountText);

        $('#productModal').removeClass('hidden');
    });

    // Tombol tutup modal
    $('#closeModal').click(function () {
        $('#productModal').addClass('hidden');
    });

    // Klik di luar modal untuk menutup
    $(window).click(function (e) {
        if ($(e.target).is('#productModal')) {
            $('#productModal').addClass('hidden');
        }
    });

    </script>
       

</body>
</html>

