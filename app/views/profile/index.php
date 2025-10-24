<?php
$user = $user ?? [];
$posts = $posts ?? [];
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= BASEURL ?>/src/css/output.css" rel="stylesheet">
    <title><?= htmlspecialchars($user['FULL_NAME'] ?? 'Profile') ?> | Sinergi</title>
</head>

<body class="bg-gray-100 text-gray-900">
    <!-- ================= COVER ================= -->
    <div class="relative w-full h-52 md:h-60 bg-gradient-to-r from-blue-600 to-indigo-600 shadow">
        <div class="absolute -bottom-16 left-1/2 transform -translate-x-1/2">
            <img src="<?= !empty($user['PATH_PHOTO'])
                ? BASEURL . '/storage/users/photos/' . $user['PATH_PHOTO']
                : BASEURL . '/src/asset/image/default.png' ?>"
                alt="Profile"
                class="w-32 h-32 rounded-full border-4 border-white shadow-xl object-cover bg-white">
        </div>
    </div>

    <!-- ================= PROFILE HEADER ================= -->
    <section class="max-w-5xl mx-auto mt-24 px-6 flex flex-col md:flex-row md:items-start md:justify-between gap-8">
        <!-- Info User -->
        <div class="text-center md:text-left flex-1">
            <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($user['FULL_NAME'] ?? '-') ?></h1>
            <p class="text-gray-500">@<?= htmlspecialchars($user['USERNAME'] ?? '-') ?></p>
            <p class="mt-2 text-gray-600 text-sm leading-snug">
                <?= htmlspecialchars($user['PROGRAM_STUDI'] ?? '-') ?> 
                • Tahun Masuk <?= htmlspecialchars($user['TAHUN_MASUK'] ?? '-') ?>
            </p>
        </div>

        <!-- Info Akun -->
        <div class="bg-white shadow-md rounded-2xl p-6 w-full md:w-80 border border-gray-200">
            <h2 class="text-lg font-semibold border-b border-gray-100 pb-3 mb-4 text-gray-800">Informasi Akun</h2>
            <div class="space-y-2 text-sm text-gray-700">
                <p><span class="font-semibold">Email:</span> <?= htmlspecialchars($user['EMAIL'] ?? '-') ?></p>
                <p><span class="font-semibold">Role:</span> <?= htmlspecialchars($user['ROLE'] ?? '-') ?></p>
                <p><span class="font-semibold">Jenjang Studi:</span> <?= htmlspecialchars($user['JENJANG_STUDI'] ?? '-') ?></p>
                <p><span class="font-semibold">Status:</span>
                    <span class="text-green-600 font-semibold"><?= htmlspecialchars($user['STATUS'] ?? 'Aktif') ?></span>
                </p>
            </div>
        </div>
    </section>

    <!-- ================= POSTINGAN USER ================= -->
    <section class="max-w-3xl mx-auto mt-16 px-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 border-b border-gray-200 pb-2">
            Postingan <?= htmlspecialchars($user['FULL_NAME'] ?? '') ?>
        </h2>

        <?php if (empty($posts)): ?>
            <div class="bg-white p-8 text-center text-gray-500 rounded-2xl border border-gray-200 shadow-sm">
                Belum ada postingan.
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($posts as $post): ?>
                    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition">
                        <!-- Header -->
                        <div class="flex items-center gap-3 mb-3">
                            <img src="<?= !empty($user['PATH_PHOTO'])
                                ? BASEURL . '/storage/users/photos/' . $user['PATH_PHOTO']
                                : BASEURL . '/src/asset/image/default.png' ?>"
                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                            <div>
                                <p class="font-semibold text-gray-900 leading-tight"><?= htmlspecialchars($user['FULL_NAME'] ?? '-') ?></p>
                                <p class="text-gray-500 text-sm"><?= date('d M Y', strtotime($post['CREATED_AT'])) ?></p>
                            </div>
                        </div>

                        <!-- Content -->
                        <?php
                        $content = $post['CONTENT'];
                        if ($content instanceof OCILob) {
                            $content = $content->load();
                        }
                        ?>
                        <p class="text-gray-800 leading-relaxed whitespace-pre-line"><?= nl2br(htmlspecialchars(trim($content ?? ''))) ?></p>

                        <!-- Media -->
                        <?php if (!empty($post['MEDIA'])): ?>
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <?php foreach ($post['MEDIA'] as $media): ?>
                                    <img src="<?= BASEURL . '/' . $media ?>" 
                                        alt="media" 
                                        class="rounded-xl object-cover w-full h-64 border border-gray-100">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Action -->
                        <div class="mt-4 flex items-center justify-between text-sm text-gray-500 border-t border-gray-100 pt-3">
                            <button class="flex items-center gap-1 hover:text-red-500 transition">
                                ❤️ <span>0</span>
                            </button>
                            <button class="flex items-center gap-1 hover:text-blue-500 transition">
                                💬 <span>0</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <div class="h-20"></div>
</body>
</html>
