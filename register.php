<?php
session_start();
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // $password = password_hash("123admin321", PASSWORD_DEFAULT);
    // $query = "INSERT INTO users (username, password, email, role_id) VALUES ('admin', '$password', 'admin32@gmail.com', 1)";
    // $conn->query($query);

}
?>

<!-- <form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form> -->

<?php if (isset($error)) echo "<p>$error</p>"; ?>
