<?php
// session_start();
// if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
//     header("Location: ../logout.php");
//     exit();
// }
// else{
//     header("Location: dashboard.php");
// }
$requireAdmin = true;
require '../auth.php';

header("Location: dashboard.php");


