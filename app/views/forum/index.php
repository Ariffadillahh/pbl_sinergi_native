<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Forum Komunitas</title>
</head>

<body class="bg-gray-50 ">

    <div class="w-full p-8 lg:p-12">

        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Forum Diskusi</h1>
                <p class="text-gray-500 text-sm">Temukan komunitas dan bergabunglah dalam diskusi.</p>
            </div>
            <button id="openModalBtn" class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-full shadow-lg hover:shadow-xl transition duration-300 flex items-center gap-2 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Forum Baru
            </button>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">

            <div class="w-full md:w-auto overflow-x-auto py-2 hide-scrollbar">
                <div class="flex gap-2 w-max">
                    <?php
                    function buildUrl($key, $val)
                    {
                        $params = $_GET;
                        $params[$key] = $val;
                        if ($key == 'filter') $params['page'] = 1;
                        return '?' . http_build_query($params);
                    }

                    $btnBase = "px-4 py-2 whitespace-nowrap rounded-full transition duration-200 font-medium text-sm border";
                    $activeClass = "bg-blue-600 text-white border-blue-600 shadow-md";
                    $inactiveClass = "bg-white text-gray-600 border-gray-300 hover:bg-gray-50 hover:text-blue-600";
                    ?>

                    <a href="<?= buildUrl('filter', 'all') ?>" class="<?= $btnBase ?> <?= $filter === 'all' ? $activeClass : $inactiveClass ?>">
                        All Forums
                    </a>
                    <a href="<?= buildUrl('filter', 'joined') ?>" class="<?= $btnBase ?> <?= $filter === 'joined' ? $activeClass : $inactiveClass ?>">
                        Joined Forum
                    </a>
                    <a href="<?= buildUrl('filter', 'owned') ?>" class="<?= $btnBase ?> <?= $filter === 'owned' ? $activeClass : $inactiveClass ?>">
                        Owned Forum
                    </a>
                </div>
            </div>

            <form action="" method="GET" class="w-full md:w-auto relative">
                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                <div class="relative group">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                        placeholder="Cari forum..."
                        class="w-full md:w-72 pl-10 pr-4 py-2.5 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all shadow-sm">
                    <div class="absolute left-3 top-3 text-gray-400 group-focus-within:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </form>
        </div>

        <?php if (empty($forums)): ?>
            <div class="py-20 text-center text-gray-500 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-10">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak ada forum ditemukan</h3>
                <?php if (!empty($search) || $filter !== 'all'): ?>
                    <p class="text-sm mt-2 text-gray-500">Kami tidak dapat menemukan apa yang Anda cari.</p>
                    <a href="?filter=all" class="mt-4 inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        Reset Filter & Pencarian
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </a>
                <?php else: ?>
                    <p class="text-sm mt-2 text-gray-500">Jadilah yang pertama membuat komunitas!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <?php foreach ($forums as $forum): ?>
                <?php
                $isActive  = ($forum['STATUS'] == 'ACTIVE');

                $isPrivate = ($forum['IS_PRIVATE'] == 1);
                $isMember  = ($forum['IS_MEMBER'] > 0);

                if ($filter === 'owned') {
                    $canAccess = true;
                } else {
                    $canAccess = $isActive && ($isMember || !$isPrivate);
                }

                $statusClass = $isPrivate ? 'bg-gray-100 text-gray-600 border-gray-200' : 'bg-green-50 text-green-700 border-green-200';
                $statusLabel = $isPrivate ? 'Private' : 'Public';

                $bannerUrl = !empty($forum['PATH_THUMBNAIL'])
                    ? BASEURL . '/storage/forums/thumbnail/' . $forum['PATH_THUMBNAIL']
                    : null;
                $iconUrl = !empty($forum['PATH_PHOTO']) ? BASEURL . '/storage/forums/photos/' . $forum['PATH_PHOTO'] : 'https://ui-avatars.com/api/?name=' . urlencode($forum['NAME']) . '&background=random';

                $targetLink = $canAccess ? BASEURL . '/forum/' . $forum['ID'] : 'javascript:void(0)';
                $cursorClass = $canAccess ? 'cursor-pointer' : 'cursor-not-allowed';
                ?>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300 group flex flex-col h-full relative">

                    <?php if (!$isActive): ?>
                        <div class="absolute top-0 left-0 right-0 z-20 bg-yellow-400 text-yellow-900 text-[10px] md:text-xs font-bold px-3 py-1.5 text-center uppercase tracking-wider shadow-sm">
                            Menunggu Dosen bergabung
                        </div>
                    <?php endif; ?>

                    <a href="<?= $targetLink ?>" class="h-32 bg-gray-200 relative overflow-hidden block <?= $cursorClass ?>">

                        <?php if ($bannerUrl): ?>
                            <img src="<?= htmlspecialchars($bannerUrl) ?>" alt="Banner"
                                class="w-full h-full object-cover transition duration-500 <?= $isActive ? ($canAccess ? 'group-hover:scale-105' : 'opacity-60 grayscale') : 'grayscale opacity-75' ?>">

                        <?php else: ?>
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center select-none transition duration-500 <?= $isActive ? ($canAccess ? 'group-hover:bg-gray-300' : 'opacity-60') : 'opacity-75' ?>">
                                <span class="text-gray-400 text-3xl font-black tracking-[0.2em] uppercase">
                                    SINERGI
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if (!$canAccess): ?>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/10">
                                <div class="bg-black/50 p-2 rounded-full text-white backdrop-blur-sm">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                        <?php elseif ($bannerUrl): ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <?php endif; ?>

                        <?php if ($isPrivate): ?>
                            <div class="absolute <?= $isActive ? 'top-3' : 'top-8' ?> right-3 bg-black/40 backdrop-blur-sm p-1.5 rounded-full text-white transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                    </a>

                    <div class="px-5 pb-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-end -mt-10 mb-3 relative pointer-events-none">
                            <img src="<?= htmlspecialchars($iconUrl) ?>" alt="Icon" class="w-20 h-20 rounded-2xl border-4 border-white shadow-sm bg-white object-cover pointer-events-auto">
                            <span class="mb-1 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border <?= $statusClass ?>">
                                <?= $statusLabel ?>
                            </span>
                        </div>

                        <div class="mb-5">
                            <a href="<?= $targetLink ?>" class="block transition-colors <?= $canAccess ? 'group-hover:text-blue-600' : 'cursor-not-allowed text-gray-500' ?>">
                                <h3 class="text-lg font-bold truncate flex items-center gap-2">
                                    <?= htmlspecialchars($forum['NAME']) ?>
                                    <?php if (!$canAccess && $isActive): ?>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    <?php endif; ?>
                                </h3>
                            </a>

                            <p class="text-xs text-gray-500 mt-1">Owner: <span class="font-medium text-gray-700"><?= htmlspecialchars($forum['OWNER_NAME']) ?></span></p>

                            <div class="flex items-center gap-4 mt-3 text-sm text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <?= number_format($forum['TOTAL_MEMBERS']) ?> Member
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <?php if ($isActive):
                            ?>

                                <?php if ($isMember): ?>
                                    <a href="<?= $targetLink ?>" class="block w-full text-center bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 font-semibold py-2 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2">
                                        <span>Masuk Forum</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>

                                <?php else: ?>
                                    <?php if ($isPrivate): ?>
                                        <button onclick="requestJoin('<?= $forum['ID'] ?>')" class="w-full bg-gray-900 hover:bg-black text-white font-medium py-2 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            Minta Bergabung
                                        </button>

                                    <?php else: ?>
                                        <div class="grid grid-cols-3 gap-2">
                                            <a href="<?= $targetLink ?>" class="col-span-2 text-center bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 font-medium py-2 px-2 rounded-xl transition duration-200 flex items-center justify-center gap-1 text-sm">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Lihat
                                            </a>

                                            <button onclick="joinForum('<?= $forum['ID'] ?>')" class="col-span-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-2 rounded-xl transition duration-200 flex items-center justify-center shadow-blue-200 shadow-md">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php else: ?>
                                <button disabled class="w-full bg-gray-100 text-gray-400 border border-gray-200 font-medium py-2 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Forum Belum Aktif
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <div class="flex justify-center items-center gap-2 mb-10 pb-20 lg:pb-0">
                <a href="<?= ($page > 1) ? buildUrl('page', $page - 1) : '#' ?>"
                    class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 transition <?= ($page > 1) ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed' ?>">
                    &laquo; Prev
                </a>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= buildUrl('page', $i) ?>"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border transition font-medium <?= $i == $page ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <a href="<?= ($page < $totalPages) ? buildUrl('page', $page + 1) : '#' ?>"
                    class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-gray-700 transition <?= ($page < $totalPages) ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed' ?>">
                    Next &raquo;
                </a>
            </div>
        <?php endif; ?>

    </div>

    <?php require_once 'app/views/components/forum/modalCreateForum.php'; ?>
    <?php require_once 'app/views/components/forum/modalJoinForum.php'; ?>


    <script>
        async function joinForum(forumId) {

            const formData = new FormData();
            formData.append('forum_id', forumId);

            try {
                const response = await fetch('<?= BASEURL ?>/forum/join', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message);
                    location.href = '<?= BASEURL ?>/forum/' + forumId;
                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error(error);
                alert('Gagal menghubungi server.');
            }
        }
    </script>
</body>

</html>