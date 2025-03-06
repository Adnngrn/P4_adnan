<?php
session_start();
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT users.id, users.username, users.password, roles.role_name 
                               FROM users 
                               JOIN roles ON users.role_id = roles.id 
                               WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role_name'];

            // Redirect berdasarkan role
            if ($user['role_name'] === 'admin') {
                header("Location: admin/index.php");
            } elseif ($user['role_name'] === 'cashier') {
                header("Location: cashier/index.php");
            } else {
                $_SESSION['error'] = "Role tidak valid!";
                header("Location: login.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Email atau password salah!";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
