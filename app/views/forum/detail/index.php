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
                                <?php if ($forumById['IS_PRIVATE'] == 1): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                        Private
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 18z" clip-rule="evenodd" />
                                        </svg>
                                        Public
                                    </span>
                                <?php endif; ?>
                                <span>·</span>
                                <span class="font-medium">
                                    <?= number_format($forumById['TOTAL_MEMBERS']) ?> Followers
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
                                        class="cursor-pointer group flex items-center gap-2 bg-white hover:bg-indigo-50 text-gray-700 border border-gray-200 px-6 py-2.5 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 ease-in-out font-medium">

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
                                <?php if ($forumById['OWNER_ID'] !== $_SESSION['user_id']) : ?>
                                    <button id="btn-open-leave-forum" class="cursor-pointer w-full md:w-auto flex items-center justify-center gap-2 bg-white rounded-xl px-4 py-2 ring-1 ring-gray-200 hover:ring-blue-600 transition-all">
                                        <img src="<?= BASEURL; ?>/src/asset/icons/logout-grey.svg" class="size-6" alt="icon">
                                        <span class="font-medium text-sm">Leave Forum</span>
                                    </button>

                                    <button id="btn-open-report-forum"
                                        class="cursor-pointer w-full md:w-auto flex items-center justify-center bg-white rounded-xl px-4 py-2 ring-1 ring-gray-200 hover:ring-blue-600 transition-all">
                                        <img src="<?= BASEURL; ?>/src/asset/icons/report.png" class="size-6" alt="icon">
                                    </button>
                                <?php endif ?>
                            </div>
                        <?php else: ?>

                            <?php if ($forumById['IS_PRIVATE'] == 1): ?>
                                <button
                                    onclick="requestJoin('<?= $forumById['ID'] ?>')"
                                    class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold text-sm md:text-base w-full md:w-auto shadow-md transition transform hover:scale-105">
                                    Request to Join
                                </button>

                            <?php else: ?>
                                <button
                                    onclick="joinForum('<?= $forumById['ID'] ?>')"
                                    class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold text-sm md:text-base w-full md:w-auto shadow-md transition transform hover:scale-105">
                                    Join Forum
                                </button>
                            <?php endif; ?>

                        <?php endif; ?>

                    </div>

                </div>

                <div class="border-t border-gray-300">
                    <div class="flex gap-2 overflow-x-auto hide-scrollbar" id="forumTabs">
                        <button class="cursor-pointer tab-btn active px-4 py-3 md:py-4 text-blue-600 border-b-4 border-blue-600 font-semibold whitespace-nowrap text-sm md:text-base" data-tab="discussion">
                            Discussion
                        </button>

                        <button class="cursor-pointer tab-btn px-4 py-3 md:py-4 text-gray-600 hover:bg-gray-100 rounded-t-lg font-semibold whitespace-nowrap text-sm md:text-base" data-tab="people">
                            People
                        </button>

                        <button class="cursor-pointer tab-btn px-4 py-3 md:py-4 text-gray-600 hover:bg-gray-100 rounded-t-lg font-semibold whitespace-nowrap text-sm md:text-base" data-tab="media">
                            Media
                        </button>
                        <button class="cursor-pointer tab-btn px-4 py-3 md:py-4 text-gray-600 hover:bg-gray-100 rounded-t-lg font-semibold whitespace-nowrap text-sm md:text-base" data-tab="files">
                            Files
                        </button>
                        <?php if ($forumById['OWNER_ID'] === $_SESSION['user_id']) : ?>
                            <button
                                class="cursor-pointer tab-btn px-4 py-3 md:py-4 text-gray-600 hover:bg-gray-100 rounded-t-lg font-semibold whitespace-nowrap text-sm md:text-base"
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

                <div class="lg:col-span-1">
                    <div class="sticky top-4">

                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5 mb-4">
                            <h3 class="font-bold text-lg text-gray-800 mb-4 border-b border-gray-200 pb-2">About Forum</h3>

                            <p class="text-gray-600 mb-6 leading-relaxed">
                                <?= htmlspecialchars($forumById['ABOUT'] ?? "") ?>
                            </p>

                            <div class="space-y-4 text-sm">

                                <div class="flex items-start gap-4">
                                    <div class="mt-1 p-2 rounded-full bg-gray-50 text-gray-600">
                                        <?php if ($forumById['IS_PRIVATE'] == 1): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                            </svg>
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 18z" clip-rule="evenodd" />
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            <?= $forumById['IS_PRIVATE'] == 1 ? 'Private Group' : 'Public Group' ?>
                                        </p>
                                        <p class="text-gray-500 text-xs mt-0.5">
                                            <?= $forumById['IS_PRIVATE'] == 1
                                                ? "Only members can see posts and members."
                                                : "Anyone can see who's in the group." ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div class="mt-1 p-2 rounded-full bg-gray-50 text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            <?= $forumById['STATUS'] == 'ACTIVE' ? 'Visible' : 'Hidden / Non-Active' ?>
                                        </p>
                                        <p class="text-gray-500 text-xs mt-0.5">
                                            <?= $forumById['STATUS'] == 'ACTIVE'
                                                ? "Anyone can find this group."
                                                : "This group is hidden from search." ?>
                                        </p>
                                    </div>
                                </div>

                                <?php
                                $isPrivate = ($forumById['IS_PRIVATE'] == 1);

                                $userRole = $_SESSION['role'] ?? '';
                                $isAuthorized = ($forumById['OWNER_ID'] === $_SESSION['user_id']) ||
                                    in_array($userRole, ['DOSEN', 'ADMIN']);

                                if ($isPrivate && $isAuthorized) :
                                ?>
                                    <div class="flex items-start gap-4">
                                        <div class="mt-1 p-2 rounded-full bg-gray-50 text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 010-2z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900">Access Key</p>

                                            <div class="flex items-center gap-2 mt-1">
                                                <div class="bg-gray-100 px-3 py-1.5 rounded text-gray-700 font-mono text-sm tracking-wide select-all" id="accessKeyText">
                                                    <?= htmlspecialchars($forumById['ACCESS_KEY']) ?>
                                                </div>

                                                <button
                                                    type="button"
                                                    id="btnCopyAccessKey"
                                                    class="cursor-pointer p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors duration-200"
                                                    title="Copy Access Key">

                                                    <span id="iconClipboard">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                        </svg>
                                                    </span>

                                                    <span id="iconCheck" class="hidden text-green-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </button>
                                            </div>
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
                                            <button type="button" data-id="<?= $pin['ID'] ?>" class="cursor-pointer btn-pin-action ml-auto text-xs text-red-500 hover:text-red-700 hover:underline font-semibold transition">
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
                                            <p class="font-bold text-sm text-gray-900"><?= htmlspecialchars($pin['FULL_NAME']) ?></p>
                                            <p class="text-xs text-gray-500"><?= date('d M Y', strtotime($pin['CREATED_AT'])) ?></p>
                                        </div>
                                    </div>

                                    <div class="text-sm mb-3">
                                        <p class="text-gray-800 leading-relaxed">
                                            <?= nl2br(htmlspecialchars(substr($pin['CONTENT'], 0, 300))) ?>
                                            <?= strlen($pin['CONTENT']) > 300 ? '...' : '' ?>
                                        </p>
                                        <?php if (strlen($pin['CONTENT']) > 300): ?>
                                            <a href="<?= BASEURL ?>/topic/<?= $pin['ID'] ?>" class="cursor-pointer text-blue-600 text-xs font-semibold hover:underline">Read More</a>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($pin['MEDIA_PATH']) && strtoupper($pin['MEDIA_TYPE']) === 'FILE'): ?>
                                        <a href="<?= BASEURL ?>/storage/forums/topics/<?= $pin['MEDIA_PATH'] ?>" target="_blank" download="<?= htmlspecialchars($pin['ORIGINAL_FILENAME'] ?? 'Document') ?>">
                                            <div class="bg-gray-50 rounded-lg p-2 mb-3 flex items-center gap-2 border border-gray-200 hover:bg-gray-100 transition cursor-pointer">
                                                <div class="bg-blue-100 p-2 rounded text-blue-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="text-sm font-semibold text-gray-800 truncate"><?= htmlspecialchars($pin['ORIGINAL_FILENAME'] ?? 'Attached Document') ?></p>
                                                    <p class="text-xs text-gray-500">Click to download</p>
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
                                        <a href="<?= BASEURL ?>/forum/topic/<?= $pin['ID'] ?>" class="cursor-pointer hover:underline">
                                            <span><?= $pin['TOTAL_COMMENTS'] ?> Comments</span>
                                        </a>
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                            </svg>
                                            <span><?= $pin['TOTAL_LIKES'] ?> Likes</span>
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
                                <button onclick="joinForum('<?= $forumById['ID'] ?>')" class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold text-sm md:text-base w-full shadow-md">
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
                Are you sure you want to leave this forum? You will lose access to all discussions unless you rejoin.
            </p>

            <div class="flex mt-6 gap-3">
                <button id="btn-cancel-leave-forum"
                    class="cursor-pointer flex-1 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition">
                    Cancel
                </button>

                <button id="btn-confirm-leave-forum"
                    class="cursor-pointer flex-1 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition">
                    Yes, Leave
                </button>
            </div>
        </div>
    </div>

    <div class="bg-green-100 border border-green-600 text-green-600 rounded-lg p-3 fixed top-5 right-5 hidden" id="succsesDiv"></div>
    <div class="bg-red-100 border border-red-600 text-red-600 rounded-lg p-3 fixed top-5 right-5 hidden" id="errorDivReq"></div>

    <?php require_once 'app/views/components/forum/modalReportForum.php'; ?>
    <?php require_once 'app/views/components/forum/modalReqJoin.php'; ?>



    <script>
        const succsesDiv = document.getElementById("succsesDiv")
        const errorDivReq = document.getElementById("errorDivReq")
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
                    succsesDiv.classList.remove('hidden')
                    succsesDiv.innerHTML = result.message

                    setTimeout(() => {
                        location.href = '<?= BASEURL ?>/forum/' + forumId;

                    }, 1000)
                } else {
                    errorDivReq.classList.remove('hidden')
                    errorDivReq.innerHTML = result.message

                    setTimeout(() => {
                        errorDivReq.classList.add("hidden")
                    }, 2000)
                }
            } catch (error) {
                console.error(error);
                alert('Failed to connect to server.');
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

            const btnCopy = document.getElementById('btnCopyAccessKey');
            const keyTextElement = document.getElementById('accessKeyText');
            const iconClipboard = document.getElementById('iconClipboard');
            const iconCheck = document.getElementById('iconCheck');

            if (btnCopy && keyTextElement) {
                btnCopy.addEventListener('click', function() {
                    const keyText = keyTextElement.innerText.trim();

                    navigator.clipboard.writeText(keyText).then(() => {
                        iconClipboard.classList.add('hidden');
                        iconCheck.classList.remove('hidden');

                        setTimeout(() => {
                            iconCheck.classList.add('hidden');
                            iconClipboard.classList.remove('hidden');
                        }, 2000);

                    }).catch(err => {
                        console.error('Failed to copy text: ', err);
                        alert('Failed to copy key automatically.');
                    });
                });
            }

            const modalLeave = document.getElementById("modal-leave-forum");
            const openLeaveBtn = document.getElementById("btn-open-leave-forum");
            const cancelLeaveBtn = document.getElementById("btn-cancel-leave-forum");
            const confirmLeaveBtn = document.getElementById("btn-confirm-leave-forum");

            if (openLeaveBtn && modalLeave) {
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
                        .catch(err => console.error(err));
                });
            }
        });
    </script>
</body>

</html>