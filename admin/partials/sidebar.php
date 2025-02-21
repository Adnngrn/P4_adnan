<div class="fixed w-60 h-screen bg-gray-300 py-5">
    <h1 class="text-center font-medium text-xl mt-3">Admin</h1>
    <ul class="mt-10 grid gap-5">
        <li class="px-10 py-3 mb-2 w-full bg-blue-300" id="dashboard"><a href="dashboard.php?page=dashboard">Dashboard</a></li><hr>
        <li class="px-10 py-3 mt-2 w-full bg-blue-300" id="product"><a href="product.php?page=product">Product</a></li>
        <li class="px-10 py-3 w-full bg-blue-300" id="promo"><a href="promo.php?page=promo">Promo</a></li>
        <li class="px-10 py-3 w-full bg-blue-300" id="history"><a href="history.php?page=history">History</a></li>
        <li class="px-10 py-3 w-full bg-blue-300" id="accounts"><a href="accounts.php?page=accounts">Accounts</a></li>
        <li class="px-10 py-3 w-full bg-blue-300 mt-20"><a href="../logout.php">Logout</a></li>
    </ul>
</div>

<script>
    const params = new URLSearchParams(window.location.search);
    const page = params.get('page');
    
    if (page) {
        document.getElementById(page)?.classList.add('bg-blue-500');
        document.getElementById(page)?.classList.remove('hover:bg-blue-600');
    }
</script>