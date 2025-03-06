<?php
// $host = "localhost";
// $user = "root";
// $password = "";
// $dbname = "program_discount";

// $conn = new mysqli($host, $user, $password, $dbname);

// if ($conn->connect_error) {
//     die("Koneksi gagal: " . $conn->connect_error);
// }

$host = "localhost"; // Sesuaikan dengan server database
$dbname = "discount_program"; // Ganti dengan nama database kamu
$username = "root"; // Sesuaikan dengan user database
$password = ""; // Sesuaikan dengan password database

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

