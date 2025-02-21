<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'cashier') {
    header("Location: ../logout.php");
    exit();
}
else{
    header("Location: cashier.php?page=cashier");
}
?>
