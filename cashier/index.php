<?php
// session_start();
// if (!isset($_SESSION['email']) || $_SESSION['role'] != 'cashier') {
//     header("Location: ../logout.php");
//     exit();
// }
// else{
//     eaderh("Location: cashier.php");
// }

$requireCashier = true;
require '../auth.php';

header("Location: cashier.php");