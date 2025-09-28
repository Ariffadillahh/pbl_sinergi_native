<!-- <?php session_start(); 
?> -->
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Home Page</title>
</head>

<body class="p-6">
    <?php if (isset($_SESSION['flash_message'])): ?>
        <?php
        $flash = $_SESSION['flash_message'];
        $alertClass = $flash['type'] === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        ?>
        <div class="border px-4 py-3 rounded relative my-4 <?= $alertClass ?>" role="alert">
            <span class="block sm:inline"><?= htmlspecialchars($flash['message']) ?></span>
        </div>
        <?php
        unset($_SESSION['flash_message']);
        ?>
    <?php endif; ?>

    <!-- Form Create -->
    <form method="POST" class="mb-6 p-4 border rounded bg-gray-100">
        <h2 class="text-xl font-bold mb-2">Tambah User</h2>
        <input type="text" name="name" placeholder="Nama" required class="border p-2 mr-2">
        <input type="email" name="email" placeholder="Email" required class="border p-2 mr-2">
        <button type="submit" name="create" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah</button>
    </form>

    <!-- Tabel Data User -->
    <table class="table-auto border-collapse border border-gray-500 w-full">
        <thead>
            <tr>
                <th class="border px-4 py-2">Nama</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="3" class="text-center border p-2">Tidak ada data pengguna untuk ditampilkan.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['NAME']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['EMAIL']); ?></td>
                        <td class="border px-4 py-2">
                            <form method="POST" class="flex gap-2">
                                <input type="hidden" name="id" value="<?php echo $user['ID']; ?>">
                                <input type="text" name="name" value="<?php echo htmlspecialchars($user['NAME']); ?>" class="border p-1">
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['EMAIL']); ?>" class="border p-1">
                                <button type="submit" name="update" class="bg-green-500 text-white px-2 py-1 rounded">Update</button>
                                <button type="submit" name="delete" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>
</body>

</html>