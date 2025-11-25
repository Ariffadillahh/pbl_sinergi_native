<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Forum Komunitas</title>
</head>

<body class="bg-gray-50 ">
    <div class="w-full p-8 lg:p-12">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

            <div class="w-full overflow-x-auto py-2 hide-scrollbar">
                <div class="flex gap-2 w-max">
                    <?php
                    function buildUrl($key, $val)
                    {
                        $params = $_GET;
                        $params[$key] = $val;
                        if ($key == 'filter') $params['page'] = 1;
                        return '?' . http_build_query($params);
                    }

                    $btnBase = "px-4 py-2 whitespace-nowrap rounded-full transition duration-200 font-medium text-sm";
                    $activeClass = "bg-blue-600 text-white shadow-md";
                    $inactiveClass = "bg-blue-50 text-blue-600 hover:bg-blue-100";
                    ?>

                    <a href="<?= buildUrl('filter', 'all') ?>"
                        class="<?= $btnBase ?> <?= $filter === 'all' ? $activeClass : $inactiveClass ?> border border-blue-300">
                        All Forums
                    </a>

                    <a href="<?= buildUrl('filter', 'joined') ?>"
                        class="<?= $btnBase ?> <?= $filter === 'joined' ? $activeClass : $inactiveClass ?> border border-blue-300">
                        Joined Forum
                    </a>

                    <a href="<?= buildUrl('filter', 'owned') ?>"
                        class="<?= $btnBase ?> <?= $filter === 'owned' ? $activeClass : $inactiveClass ?> border border-blue-300">
                        Owned Forum
                    </a>
                </div>
            </div>

            <form action="" method="GET" class="w-full md:w-auto relative">
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                <div class="relative">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                        placeholder="Cari forum..."
                        class="w-full md:w-64 pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        <?php if (empty($forums)): ?>
            <div class="py-20 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300 p-10">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2z"></path>
                </svg>
                <p class="text-lg font-medium text-gray-600">Tidak ada forum ditemukan.</p>
                <?php if (!empty($search) || $filter !== 'all'): ?>
                    <p class="text-sm mt-2 text-gray-400">Coba ubah kata kunci pencarian atau filter Anda.</p>
                    <a href="?filter=all" class="mt-4 inline-block text-blue-600 hover:underline">Reset Filter</a>
                <?php else: ?>
                    <p class="text-sm mt-2">Cobalah membuat forum baru untuk memulai komunitas Anda!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <?php foreach ($forums as $forum): ?>
                <?php
                $isPrivate = ($forum['IS_PRIVATE'] == 1);
                $isMember  = ($forum['IS_MEMBER'] > 0);
                $statusClass = $isPrivate ? 'bg-gray-100 text-gray-700 border-gray-200' : 'bg-green-100 text-green-700 border-green-200';
                $statusLabel = $isPrivate ? 'Private' : 'Public';

                $bannerUrl = !empty($forum['PATH_THUMBNAIL']) ? BASEURL . '/storage/forums/thumbnail/' . $forum['PATH_THUMBNAIL'] : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&q=80';
                $iconUrl = !empty($forum['PATH_PHOTO']) ? BASEURL . '/storage/forums/photos/' . $forum['PATH_PHOTO'] : 'https://ui-avatars.com/api/?name=' . urlencode($forum['NAME']) . '&background=random';
                ?>
                <a href="">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300 group flex flex-col h-full">
                        <div class="h-28 bg-gray-200 relative overflow-hidden">
                            <img src="<?= htmlspecialchars($bannerUrl) ?>" alt="Banner" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-black/10"></div>
                            <?php if ($isPrivate): ?>
                                <div class="absolute top-2 right-2 bg-black/30 backdrop-blur-sm p-1.5 rounded-full text-white/80">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="px-5 pb-5 flex-1 flex flex-col">
                            <div class="flex justify-between items-end -mt-10 mb-3 relative">
                                <img src="<?= htmlspecialchars($iconUrl) ?>" alt="Icon" class="w-20 h-20 rounded-xl border-4 border-white shadow-sm bg-white object-cover">
                                <span class="mb-1 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $statusClass ?>"><?= $statusLabel ?></span>
                            </div>
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900 hover:text-blue-600 cursor-pointer truncate"><?= htmlspecialchars($forum['NAME']) ?></h3>
                                <p class="text-xs text-gray-500 mt-0.5">Owner: <span class="font-medium text-gray-700"><?= htmlspecialchars($forum['OWNER_NAME']) ?></span></p>
                                <p class="text-sm text-gray-500 flex items-center gap-2 mt-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <?= number_format($forum['TOTAL_MEMBERS']) ?> Member
                                </p>
                            </div>
                            <div class="mt-auto">
                                <?php if ($isMember): ?>
                                    <a href="<?= BASEURL ?>/forum/<?= $forum['ID'] ?>" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                        <span>Lihat Forum</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                <?php else: ?>
                                    <?php if ($isPrivate): ?>
                                        <button onclick="requestJoin('<?= $forum['ID'] ?>')" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                            </svg>
                                            Minta Bergabung
                                        </button>
                                    <?php else: ?>
                                        <button onclick="joinForum('<?= $forum['ID'] ?>')" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                            <span>Gabung Forum</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center gap-2 mb-10 pb-20 lg:pb-0">
                <?php if ($page > 1): ?>
                    <a href="<?= buildUrl('page', $page - 1) ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        &laquo; Prev
                    </a>
                <?php else: ?>
                    <span class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-400 cursor-not-allowed">
                        &laquo; Prev
                    </span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= buildUrl('page', $i) ?>"
                        class="px-4 py-2 rounded-lg border transition <?= $i == $page ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="<?= buildUrl('page', $page + 1) ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Next &raquo;
                    </a>
                <?php else: ?>
                    <span class="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg text-gray-400 cursor-not-allowed">
                        Next &raquo;
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
    </div>

    <?php require_once 'app/views/components/forum/modalCreateForum.php'; ?>

</body>

</html>