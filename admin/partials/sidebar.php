<div class="fixed w-60 h-screen bg-gray-300 py-5">
    <h1 class="text-center font-medium text-xl mt-3">Admin</h1>
    <div class="mt-10 grid gap-5">
        <a class="px-10 py-3 mb-2 w-fdivl bg-blue-300" id="dashboard" href="dashboard.php?page=dashboard">Dashboard</a><hr>
        <a class="px-10 py-3 mt-2 w-fdivl bg-blue-300" id="product" href="product.php?page=product">Product</a>
        <a class="px-10 py-3 w-fdivl bg-blue-300" id="promo" href="promo.php?page=promo">Promo</a>
        <a class="px-10 py-3 w-fdivl bg-blue-300" id="history" href="history.php?page=history">History</a>
        <a class="px-10 py-3 w-fdivl bg-blue-300" id="accounts" href="accounts.php?page=accounts">Accounts</a>
        <a class="px-10 py-3 w-fdivl bg-blue-300 mt-20" href="../logout.php">Logout</a>
    </div>
</div>

<script>
    const params = new URLSearchParams(window.location.search);
    const page = params.get('page');
    
    if (page) {
        document.getElementById(page)?.classList.add('bg-blue-500');
        document.getElementById(page)?.classList.remove('hover:bg-blue-600');
    }
</script>