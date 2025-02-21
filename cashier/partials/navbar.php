<div class="fixed grid grid-cols-7 items-center justify-center w-screen bg-blue-700 text-white h-10 bottom-0 left-0 px-20 z-50 drop">
    <div class="flex justify-center h-10 items-center text-white hover:bg-blue-600 border-l border-blue-500" id="cashier"><a href="cashier.php?page=cashier">Kasir</a></div>
    <div class="flex justify-center h-10 items-center text-white hover:bg-blue-600 border-l border-blue-500" id="product"><a href="?page=product">Produk</a></div>
    <div class="flex justify-center h-10 items-center text-white hover:bg-blue-600 border-l border-blue-500" id="history"><a href="history.php?page=history">History</a></div>
    <div class="flex justify-center col-start-7 hover:bg-red-500 h-10 items-center text-white border-x border-blue-500"><a href="../logout.php">Logout</a></div>
</div>

<script>
    const params = new URLSearchParams(window.location.search);
    const page = params.get('page');
    
    if (page) {
        document.getElementById(page)?.classList.add('bg-blue-500');
        document.getElementById(page)?.classList.remove('hover:bg-blue-600');
    }
</script>