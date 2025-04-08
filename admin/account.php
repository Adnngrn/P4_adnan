<?php
$requireAdmin = true;
require '../auth.php';
require '../connection.php';

// Ambil semua user dan role
$users = $pdo->query("SELECT users.*, roles.role_name FROM users JOIN roles ON users.role_id = roles.id ORDER BY users.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Akun | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style> td { text-align: center; } </style>
</head>
<body class="flex bg-stone-200">
    <div id="sidebar"></div>

    <div class="ml-60 w-full px-5 py-7">
            <h1 class="text-2xl font-semibold mb-4">Daftar Akun</h1>
        <button id="btnTambah" class="mb-4 bg-blue-600 text-white px-4 py-2 rounded-md">Tambah Akun</button>

        <table class="w-full bg-white rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-3 text-center">Email</th>
                    <th class="px-4 py-3 text-center">Username</th>
                    <th class="px-4 py-3 text-center">Role</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="akunBody">
                <?php foreach ($users as $u): ?>
                <tr class="border-t">
                    <td class="px-4 py-2"><?= htmlspecialchars($u['email']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['username']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($u['role_name']) ?></td>
                    <td class="px-4 py-2 text-center">
                        <button class="edit-btn bg-yellow-400 text-white px-3 py-1 rounded" 
                            data-id="<?= $u['id'] ?>"
                            data-email="<?= $u['email'] ?>"
                            data-username="<?= $u['username'] ?>"
                            data-role="<?= $u['role_id'] ?>">
                            Edit
                        </button>
                        <button class="delete-btn bg-red-500 text-white px-3 py-1 rounded" 
                            data-id="<?= $u['id'] ?>">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <!-- Modal Tambah/Edit -->
    <div id="akunModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-1/2 relative">
            <button id="closeModal" class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-xl">&times;</button>
            <h2 class="text-xl font-semibold mb-3" id="modalTitle">Tambah Akun</h2>
            <form id="akunForm">
                <input type="hidden" name="id" id="userId">
                <input type="hidden" name="action" value="add" id="formAction">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="mb-3">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="mb-3 hidden" id="oldPasswordGroup">
                    <label>Password Saat Ini<span class="text-sm text-gray-500" id="pwNote"></span></label>
                    <input type="password" name="current_password" id="current_password" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="mb-3">
                    <label>Password  Baru<span class="text-sm text-gray-500" id="pwNote"></span></label>
                    <input type="password" name="password" id="password" class="w-full border px-3 py-2 rounded">
                </div>
                <div class="mb-3">
                    <label>Role</label>
                    <select name="role_id" id="role_id" class="w-full border px-3 py-2 rounded">
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= $r['role_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
            </form>
        </div>
    </div>


    <script src="script.js"></script>
    <script>
    $(document).ready(function () {
        // Buka modal tambah
        $('#btnTambah').on('click', function () {
            $('#akunForm')[0].reset();
            $('#formAction').val('add');
            $('#userId').val('');
            $('#modalTitle').text('Tambah Akun');
            $('#pwNote').show();
            $('#akunModal').removeClass('hidden').addClass('flex');
            $('#oldPasswordGroup').addClass('hidden');
        });

        // Buka modal edit
        $('.edit-btn').on('click', function () {
            $('#formAction').val('edit');
            $('#modalTitle').text('Edit Akun');
            $('#oldPasswordGroup').removeClass('hidden');
            $('#pwNote').text('(biarkan kosong jika tidak ingin ganti password)');

            $('#userId').val($(this).data('id'));
            $('#email').val($(this).data('email'));
            $('#username').val($(this).data('username'));
            $('#role_id').val($(this).data('role'));
            $('#password').val('');

            $('#akunModal').removeClass('hidden').addClass('flex');
        });

        // Tutup modal
        $('#closeModal').on('click', function () {
            $('#akunModal').addClass('hidden').removeClass('flex');
        });

        // Submit form
        $('#akunForm').on('submit', function (e) {
            e.preventDefault();
            $.post('account_process.php', $(this).serialize(), function (res) {
                if (res.success) {
                    location.reload();
                } else {
                    alert(res.message || 'Gagal menyimpan data');
                }
            }, 'json');
        });

        // Hapus akun
        $('.delete-btn').on('click', function () {
            if (confirm('Yakin ingin menghapus akun ini?')) {
                $.post('account_process.php', {
                    action: 'delete',
                    id: $(this).data('id')
                }, function (res) {
                    if (res.success) {
                        location.reload();
                    } else {
                        alert(res.message || 'Gagal menghapus akun');
                    }
                }, 'json');
            }
        });
    });
    </script>

</body>
</html>
