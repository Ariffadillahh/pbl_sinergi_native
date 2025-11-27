<?php

$hasBanner = !empty($forumById['PATH_THUMBNAIL']);
$bannerUrl = $hasBanner
    ? BASEURL . '/storage/forums/thumbnail/' . $forumById['PATH_THUMBNAIL']
    : null;

$iconUrl = !empty($forumById['PATH_PHOTO'])
    ? BASEURL . '/storage/forums/photos/' . $forumById['PATH_PHOTO']
    : 'https://ui-avatars.com/api/?name=' . urlencode($forumById['NAME']) . '&background=3B82F6&color=fff';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Forum <?= $forumById['NAME'] ?></title>
</head>

<body>
    <div class="w-full h-full overflow-y-auto">
        <div class="bg-white shadow-sm">
            <div class="h-48 md:h-72 relative overflow-hidden bg-gray-300">

                <?php if ($hasBanner): ?>
                    <img src="<?= htmlspecialchars($bannerUrl) ?>"
                        alt="Cover"
                        class="absolute inset-0 w-full h-full object-cover">
                <?php endif; ?>

                <div class="absolute inset-0 bg-gradient-to-r from-black to-gray-900 opacity-30"></div>

                <?php if (!$hasBanner): ?>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-white text-4xl md:text-5xl font-bold opacity-70 tracking-wide">
                            SINERGI
                        </span>
                    </div>
                <?php endif; ?>

            </div>



            <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-0">

                <div class="flex flex-col md:flex-row items-center md:items-end justify-between -mt-16 md:-mt-20 mb-4">

                    <div class="flex flex-col md:flex-row items-center md:items-end gap-4 w-full md:w-auto">
                        <div class="relative z-10">
                            <img src="<?= htmlspecialchars($iconUrl) ?>"
                                alt="Profile"
                                class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white bg-white shadow-lg">
                        </div>

                        <div class="mb-2 text-center md:text-left mt-2 md:mt-0">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">
                                <?= htmlspecialchars($forumById['NAME']) ?>
                            </h1>

                            <p class="text-gray-600 text-sm md:text-base flex items-center justify-center md:justify-start gap-2 mt-1">
                                <span>
                                    <?= ($forumById['IS_PRIVATE'] == 1) ? '🔒 Private' : '🌐 Public' ?>
                                </span>
                                <span>·</span>
                                <span class="font-medium">
                                    <?= number_format($forumById['TOTAL_MEMBERS']) ?> Followings
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row gap-2 mt-4 w-full md:w-auto justify-center md:justify-end mb-2">

                        <?php if ($isMember): ?>

                            <?php if ($forumById['OWNER_ID'] === $_SESSION['user_id']) : ?>
                                <div class="relative inline-block">
                                    <button onclick="openRequestModal(this)"
                                        data-id="<?= $forumById['ID'] ?>"
                                        class="group flex items-center gap-2 bg-white hover:bg-indigo-50 text-gray-700 border border-gray-200 px-6 py-2.5 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 ease-in-out font-medium">

                                        <?php if ($forumById['TOTAL_REQUESTS'] > 0): ?>
                                            <span id="badgeCount" class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-md ring-2 ring-white animate-pulse">
                                                <?= $forumById['TOTAL_REQUESTS'] ?>
                                            </span>
                                        <?php endif; ?>

                                        <span>Requests</span>
                                    </button>
                                </div>
                            <?php else: ?>
                                <button
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 md:px-6 py-2 rounded-lg font-semibold text-sm md:text-base w-full md:w-auto transition cursor-default">
                                    ✓ Joined
                                </button>
                            <?php endif; ?>


                            <div class="flex gap-3">
                                <button id="btn-open-leave-forum" class="w-full md:w-auto flex items-center justify-center gap-2 bg-white rounded-xl px-4 py-2 ring-1 ring-gray-200 hover:ring-blue-600 transition-all">
                                    <img src="<?= BASEURL; ?>/src/asset/icons/logout-grey.svg" class="size-6" alt="icon">
                                    <span class="font-medium text-sm">Leave Forum</span>
                                </button>

                                <?php if ($forumById['OWNER_ID'] !== $_SESSION['user_id']) : ?>
                                    <button id="btn-open-report-forum"
                                        class="w-full md:w-auto flex items-center justify-center bg-white rounded-xl px-4 py-2 ring-1 ring-gray-200 hover:ring-blue-600 transition-all">
                                        <img src="<?= BASEURL; ?>/src/asset/icons/report.png" class="size-6" alt="icon">
                                    </button>
                                <?php endif ?>
                            </div>
                        <?php else: ?>

                            <?php if ($forumById['IS_PRIVATE'] == 1): ?>
                                <button
                                    onclick="requestJoin('<?= $forumById['ID'] ?>')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold text-sm md:text-base w-full md:w-auto shadow-md transition transform hover:scale-105">
                                    Minta Bergabung
                                </button>

                            <?php else: ?>
                                <button
                                    onclick="joinForum('<?= $forumById['ID'] ?>')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold text-sm md:text-base w-full md:w-auto shadow-md transition transform hover:scale-105">
                                    Gabung Forum
                                </button>
                            <?php endif; ?>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="border-t border-gray-300">
                    <div class="flex gap-2 overflow-x-auto hide-scrollbar" id="forumTabs">
                        <button class="tab-btn active px-4 py-3 md:py-4 text-blue-600 border-b-4 border-blue-600 font-semibold whitespace-nowrap text-sm md:text-base" data-tab="discussion">
                            Discussion
                        </button>

                        <button class="tab-btn px-4 py-3 md:py-4 text-gray-600 hover:bg-gray-100 rounded-t-lg font-semibold whitespace-nowrap text-sm md:text-base" data-tab="people">
                            People
                        </button>

                        <button class="tab-btn px-4 py-3 md:py-4 text-gray-600 hover:bg-gray-100 rounded-t-lg font-semibold whitespace-nowrap text-sm md:text-base" data-tab="media">
                            Media
                        </button>
                        <button class="tab-btn px-4 py-3 md:py-4 text-gray-600 hover:bg-gray-100 rounded-t-lg font-semibold whitespace-nowrap text-sm md:text-base" data-tab="files">
                            Files
                        </button>
                        <?php if ($forumById['OWNER_ID'] === $_SESSION['user_id']) : ?>
                            <button
                                class="tab-btn px-4 py-3 md:py-4 text-gray-600 hover:bg-gray-100 rounded-t-lg font-semibold whitespace-nowrap text-sm md:text-base"
                                data-tab="settings">
                                Settings
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto mt-4 px-4 pb-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                <!-- Left Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-4">

                        <div class="bg-white rounded-lg shadow p-4 mb-4">
                            <h3 class="font-bold text-lg mb-3">About</h3>
                            <p class="text-gray-700 mb-3"><?= $forumById['ABOUT'] ?></p>

                            <div class="space-y-3 text-sm">
                                <?php if ($forumById['IS_PRIVATE'] == 1): ?>
                                    <div class="flex items-start gap-3">
                                        <span class="text-xl">🔒</span>
                                        <div>
                                            <p class="font-semibold">Private</p>
                                            <p class="text-gray-600 text-xs">Only members can see who's in the group and what they post</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-start gap-3">
                                        <span class="text-xl">🌐</span>
                                        <div>
                                            <p class="font-semibold">Public</p>
                                            <p class="text-gray-600 text-xs">Anyone can see who's in the group</p>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($forumById['STATUS'] == 'ACTIVE'): ?>
                                    <div class="flex items-start gap-3">
                                        <span class="text-xl">👁️</span>
                                        <div>
                                            <p class="font-semibold">Visible</p>
                                            <p class="text-gray-600 text-xs">Anyone can find this group</p>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-start gap-3">
                                        <span class="text-xl">👁️</span>
                                        <div>
                                            <p class="font-semibold">NONACTIVE</p>
                                            <p class="text-gray-600 text-xs">Anyone can't find this group</p>
                                        </div>
                                    </div>
                                <?php endif; ?>


                            </div>
                        </div>

                        <?php if (!empty($pinned_topics)): ?>
                            <?php foreach ($pinned_topics as $pin): ?>

                                <div class="bg-white rounded-lg shadow p-4 mb-4 border-l-4 border-blue-600 relative overflow-hidden">

                                    <div class="flex items-center gap-2 mb-3 text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform rotate-45" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <span class="text-xs font-bold uppercase tracking-wider">Pinned Post</span>

                                        <?php if ($can_unpin): ?>
                                            <button type="button" data-id="<?= $pin['ID'] ?>" class="btn-pin-action ml-auto text-xs text-red-500 hover:text-red-700 hover:underline font-semibold transition">
                                                Unpin Post
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex items-center gap-3 mb-3">
                                        <?php
                                        $userPhoto = !empty($pin['PROFILE_PIC']) ? $pin['PROFILE_PIC'] : (!empty($pin['PATH_PHOTO']) ? $pin['PATH_PHOTO'] : null);
                                        ?>
                                        <img src="<?= $userPhoto ? BASEURL . '/storage/users/photos/' . $userPhoto : BASEURL . '/src/asset/image/default.png' ?>"
                                            class="w-8 h-8 rounded-full object-cover">
                                        <div>
                                            <p class="font-bold text-sm text-gray-900"><?= htmlspecialchars($pin['USERNAME']) ?></p>
                                            <p class="text-xs text-gray-500"><?= date('d M Y H:i', strtotime($pin['CREATED_AT'])) ?></p>
                                        </div>
                                    </div>

                                    <div class="text-sm mb-3">
                                        <p class="text-gray-800 leading-relaxed">
                                            <?= nl2br(htmlspecialchars(substr($pin['CONTENT'], 0, 300))) ?>
                                            <?= strlen($pin['CONTENT']) > 300 ? '...' : '' ?>
                                        </p>
                                        <?php if (strlen($pin['CONTENT']) > 300): ?>
                                            <a href="<?= BASEURL ?>/topic/<?= $pin['ID'] ?>" class="text-blue-600 text-xs font-semibold hover:underline">Baca Selengkapnya</a>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($pin['MEDIA_PATH']) && strtoupper($pin['MEDIA_TYPE']) === 'FILE'): ?>
                                        <a href="<?= BASEURL ?>/storage/forums/topics/<?= $pin['MEDIA_PATH'] ?>" target="_blank" download="<?= htmlspecialchars($pin['ORIGINAL_FILENAME'] ?? 'Dokumen') ?>">
                                            <div class="bg-gray-50 rounded-lg p-2 mb-3 flex items-center gap-2 border border-gray-200 hover:bg-gray-100 transition cursor-pointer">
                                                <div class="bg-blue-100 p-2 rounded text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($pin['ORIGINAL_FILENAME'] ?? 'Dokumen Lampiran') ?></p>
                                                    <p class="text-xs text-gray-500">Klik untuk mengunduh</p>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (!empty($pin['MEDIA_PATH']) && strtoupper($pin['MEDIA_TYPE']) === 'IMAGE'): ?>
                                        <div class="mb-3">
                                            <img src="<?= BASEURL ?>/storage/forums/topics/<?= $pin['MEDIA_PATH'] ?>" class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                        </div>
                                    <?php endif; ?>

                                    <div class="border-t pt-2 flex items-center justify-between text-gray-500 text-xs">
                                        <span><?= $topic['TOTAL_COMMENTS'] ?> Comments</span>
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                            </svg>
                                            <span><?= $pin['TOTAL_LIKES'] ?> Like</span>
                                        </div>
                                    </div>

                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                </div>

                <div class="lg:col-span-2 mb-14 lg:mb-0">
                    <div id="tabContent">
                        <div class="tab-content active space-y-4" data-content="discussion">
                            <?php if ($isMember) : ?>
                                <?php require_once 'app/views/components/forum/createTopic.php'; ?>
                            <?php else : ?>
                                <button onclick="joinForum('<?= $forumById['ID'] ?>')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold text-sm md:text-base w-full shadow-md">
                                    Join to Create Post
                                </button>
                            <?php endif; ?>

                            <?php require_once 'app/views/components/forum/topics.php'; ?>
                        </div>


                        <!-- People Tab -->
                        <?php require_once 'app/views/components/forum/tabsMembers.php'; ?>


                        <!-- Media Tab -->
                        <?php require_once 'app/views/components/forum/tabsMedia.php'; ?>


                        <!-- Files Tab -->
                        <?php require_once 'app/views/components/forum/tabsFiles.php'; ?>


                        <!-- Settings -->
                        <?php require_once 'app/views/components/forum/tabsSetings.php'; ?>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <!-- Overlay -->
    <div id="modal-leave-forum"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden justify-center items-center">

        <div class="bg-white rounded-2xl p-6 w-[90%] max-w-md shadow-lg animate-fadeIn">
            <div class="flex justify-center mb-4">
                <svg class="w-14 h-14 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M12 9v4m0 4h.01M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <h2 class="text-lg font-semibold text-center text-gray-900">Leave Forum?</h2>

            <p class="text-center text-sm text-gray-600 mt-2">
                Are you sure you want to leave this forum? You will lose access to all discussions unless rejoined.
            </p>

            <div class="flex mt-6 gap-3">
                <button id="btn-cancel-leave-forum"
                    class="flex-1 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition">
                    Cancel
                </button>

                <button id="btn-confirm-leave-forum"
                    class="flex-1 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition">
                    Yes, Leave
                </button>
            </div>
        </div>
    </div>

    <?php require_once 'app/views/components/forum/modalReportForum.php'; ?>
    <?php require_once 'app/views/components/forum/modalReqJoin.php'; ?>



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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            if (tabButtons.length > 0) {
                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const targetTab = this.getAttribute('data-tab');

                        tabButtons.forEach(btn => {
                            btn.classList.remove('active', 'text-blue-600', 'border-b-4', 'border-blue-600');
                            btn.classList.add('text-gray-600');
                        });

                        this.classList.add('active', 'text-blue-600', 'border-b-4', 'border-blue-600');
                        this.classList.remove('text-gray-600');

                        tabContents.forEach(content => {
                            content.classList.add('hidden');
                            content.classList.remove('active');
                        });

                        const targetContent = document.querySelector(`[data-content="${targetTab}"]`);
                        if (targetContent) {
                            targetContent.classList.remove('hidden');
                            targetContent.classList.add('active');
                        }
                    });
                });
            }

            const modalLeave = document.getElementById("modal-leave-forum");
            const openLeaveBtn = document.getElementById("btn-open-leave-forum");
            const cancelLeaveBtn = document.getElementById("btn-cancel-leave-forum");
            const confirmLeaveBtn = document.getElementById("btn-confirm-leave-forum");

            openLeaveBtn.addEventListener("click", () => {
                modalLeave.classList.remove("hidden");
                modalLeave.classList.add("flex");
            });

            cancelLeaveBtn.addEventListener("click", () => {
                modalLeave.classList.add("hidden");
                modalLeave.classList.remove("flex");
            });

            confirmLeaveBtn.addEventListener("click", () => {
                const formData = new FormData();
                formData.append('forum_id', '<?= $forumById['ID'] ?>');
                fetch('<?= BASEURL ?>/forum/leave', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            location.href = '<?= BASEURL ?>/forums';
                        } else {
                            alert(result.message);
                        }
                    })
            });

        });
    </script>
</body>

</html>