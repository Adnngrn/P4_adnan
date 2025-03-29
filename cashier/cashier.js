let cart = JSON.parse(localStorage.getItem('cart')) || [];
let selectedCategory = "all";

function updateCart() {
    $('#cartItems').html('');
    let subtotal = 0;
    let totalDiscount = 0;

    cart.forEach((item, index) => {
        let itemTotal = item.price * item.qty;
        let discountAmount = 0;

        if (item.discountType === 'percentage' && item.qty >= item.minQuantity) {
            discountAmount = parseFloat((item.price * item.discountValue / 100) * item.qty);
        } else if (item.discountType === 'fixed' && item.qty >= item.minQuantity) {
            discountAmount = parseFloat(item.discountValue); // Diskon fixed hanya berlaku sekali
        }

        subtotal += itemTotal;
        totalDiscount += discountAmount;

        $('#cartItems').append(`
            <div class="grid grid-cols-3 items-center border-b pb-2 mb-2">
                <p class="col-span-2">${item.name}<br>
                    <span class="text-xs">${item.qty}x | Rp ${item.price.toLocaleString('id-ID')}</span>
                </p>
                <div class="flex justify-end gap-1">
                    <button class="bg-gray-300 px-2 rounded change-qty" data-index="${index}" data-action="-">-</button>
                    <button class="bg-gray-300 px-2 rounded change-qty" data-index="${index}" data-action="+">+</button>
                </div>
            </div>`);
    });

    let total = subtotal - totalDiscount;
    
    $('#totalPrice').text('Rp ' + total.toLocaleString('id-ID'));
    $('#subtotalPrice').text('Rp ' + subtotal.toLocaleString('id-ID'));
    $('#totalDiscount').text('Rp ' + totalDiscount.toLocaleString('id-ID'));
    
    localStorage.setItem('cart', JSON.stringify(cart));
}

function filterProducts() {
    let searchValue = $('#searchInput').val().toLowerCase();
    $('.product-card').each(function() {
        let matchesCategory = selectedCategory === "all" || $(this).data('category') == selectedCategory;
        let matchesSearch = $(this).data('name').includes(searchValue);
        $(this).toggle(matchesCategory && matchesSearch);
    });
}

$('.category-btn').click(function() {
    selectedCategory = $(this).data('category');
    $('.category-btn').removeClass('bg-blue-500 text-white').addClass('bg-white');
    $(this).removeClass('bg-white').addClass('bg-blue-500 text-white');
    filterProducts();
});

$('#searchInput').on('input', filterProducts);

$('.add-to-cart').click(function() {
    let stock = parseInt($(this).data('stock'));
    let productId = $(this).data('id');

    if (stock <= 0) {
        alert('Stok habis! Tidak dapat menambahkan ke keranjang.');
        return;
    }

    let item = cart.find(p => p.id == productId);
    if (item) {
        if (item.qty < stock) {
            item.qty++;
        } else {
            alert('Jumlah melebihi stok yang tersedia!');
        }
    } else {
        cart.push({
            id: $(this).data('id'),
            name: $(this).data('name'),
            price: $(this).data('price'),
            qty: 1,
            stock: stock,
            discountType: $(this).data('discount-type'),
            discountValue: $(this).data('discount-value'),
            minQuantity: $(this).data('min-quantity')
        });
    }
    updateCart();
});

$(document).on('click', '.change-qty', function() {
    let index = $(this).data('index');
    let action = $(this).data('action');

    if (action === '+') {
        if (cart[index].qty < cart[index].stock) {
            cart[index].qty++;
        } else {
            alert('Jumlah melebihi stok yang tersedia!');
        }
    } else {
        cart[index].qty--;
        if (cart[index].qty < 1) cart.splice(index, 1);
    }
    updateCart();
});

$('#clearCart').click(() => {
    cart = [];
    updateCart();
});

// $('#checkout').click(() => {
//     if (cart.length === 0) {
//         alert('Keranjang masih kosong!');
//         return;
//     }

//     let checkoutData = {
//         cart: cart,
//         subtotal: parseFloat($('#subtotalPrice').text().replace(/[^0-9]/g, '')),
//         totalDiscount: parseFloat($('#totalDiscount').text().replace(/[^0-9]/g, '')),
//         total: parseFloat($('#totalPrice').text().replace(/[^0-9]/g, ''))
//     };

//     $.ajax({
//         url: 'process_checkout.php',
//         type: 'POST',
//         contentType: 'application/json',
//         data: JSON.stringify(checkoutData),
//         success: function(response) {
//             if (response.status === 'success') {
//                 alert('Transaksi berhasil! Invoice: ' + response.invoice);
//                 localStorage.removeItem('cart');
//                 cart = [];
//                 updateCart();
//             } else {
//                 alert('Terjadi kesalahan: ' + response.message);
//             }
//         },
//         error: function() {
//             alert('Gagal memproses checkout!');
//         }
//     });
// });

$('#checkoutButton').click(function () {
    if (cart.length === 0) {
        alert("Keranjang masih kosong!");
        return;
    }

    if (!confirm("Yakin dengan pesanannya?")) {
        return; // Batalkan checkout jika user memilih "Cancel"
    }

    $.ajax({
        url: 'process_checkout.php',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(cart),
        success: function (response) {
            if (response.status === 'success') {
                alert(response.message);
                localStorage.removeItem('cart'); // Bersihkan keranjang
                window.location.href = response.redirect; // Redirect ke invoice
            } else {
                alert(response.message);
            }
        },
        error: function () {
            alert("Terjadi kesalahan saat checkout.");
        }
    });
});

updateCart();
