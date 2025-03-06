<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../logout.php");
    exit();
}

// Jika halaman khusus admin
if (isset($requireAdmin) && $_SESSION['role'] !== 'admin') {
    header("Location: cashier/index.php");
    exit();
}

// Jika halaman khusus cashier
if (isset($requireCashier) && $_SESSION['role'] !== 'cashier') {
    header("Location: admin/index.php");
    exit();
}
?>
