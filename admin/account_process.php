<?php
require '../auth.php';
require '../connection.php';

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Aksi tidak valid'];

if ($action === 'add') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role_id = $_POST['role_id'];

    $stmt = $pdo->prepare("INSERT INTO users (email, username, password, role_id, created_at) VALUES (?, ?, ?, ?, NOW())");
    if ($stmt->execute([$email, $username, $password, $role_id])) {
        $response = ['success' => true];
    }
}

if ($action == 'edit') {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $role_id = $_POST['role_id'];
    $password = $_POST['password'];
    $current_password = $_POST['current_password'];

    // Ambil data user lama
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User tidak ditemukan.']);
        exit;
    }

    // Jika ingin ganti password
    if (!empty($password)) {
        // Cek password lama
        if (empty($current_password)) {
            echo json_encode(['success' => false, 'message' => 'Password saat ini wajib diisi.']);
            exit;
        }

        if (!password_verify($current_password, $user['password'])) {
            echo json_encode(['success' => false, 'message' => 'Password saat ini salah.']);
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET email = ?, username = ?, role_id = ?, password = ?, updated_at = NOW() WHERE id = ?";
        $params = [$email, $username, $role_id, $hashed_password, $id];
    } else {
        $sql = "UPDATE users SET email = ?, username = ?, role_id = ?, updated_at = NOW() WHERE id = ?";
        $params = [$email, $username, $role_id, $id];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode(['success' => true]);
    exit;
}


elseif ($action === 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$id])) {
        $response = ['success' => true];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
