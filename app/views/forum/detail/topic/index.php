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
        <div class="bg-white shadow-sm pb-1">
            <div class="h-20 md:h-40 relative overflow-hidden bg-gray-300">

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
                        <a href="<?= BASEURL ?>/forum/<?php echo $forumById['ID']; ?>"
                            class="w-full md:w-auto flex items-center justify-center gap-2 bg-white rounded-xl px-4 py-2 ring-1 ring-gray-200 hover:ring-blue-600 transition-all">
                            <img src="<?= BASEURL; ?>/src/asset/icons/logout-grey.svg" class="size-6" alt="icon">
                            <span class="font-medium text-sm text-heyhao-secondary">Back</span>
                        </a>
                    </div>

                </div>


            </div>
        </div>

        <div class="max-w-4xl mx-auto mt-4 px-4 pb-8">

            <?php
            $allMedia = $topic['MEDIA'] ?? [];
            $images = array_filter($allMedia, fn($m) => $m['MEDIA_TYPE'] === 'IMAGE');
            $files = array_filter($allMedia, fn($m) => $m['MEDIA_TYPE'] === 'FILE');

            $isLikedByUser = $topic['IS_LIKED'] ?? false;
            $totalLikes = $topic['TOTAL_LIKES'] ?? 0;
            ?>

            <div class="topic-card bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8 hover:shadow-xl transition-shadow duration-300" id="topic-<?= $topic['ID'] ?? 0 ?>">

                <div class="p-4 pb-3">
                    <div class="flex items-start justify-between mb-5">
                        <div class="flex gap-4">
                            <div class="relative">
                                <div class="w-14 h-14 rounded-full overflow-hidden ring-2 ring-blue-100 ring-offset-2">
                                    <img src="<?= !empty($topic['PATH_PHOTO']) ? BASEURL . '/storage/users/photos/' . $topic['PATH_PHOTO'] : BASEURL . '/src/asset/image/default.png' ?>"
                                        class="w-full h-full object-cover"
                                        alt="User Avatar">
                                </div>
                            </div>

                            <div>
                                <h4 class="font-bold text-lg text-gray-900 leading-tight hover:text-blue-600 transition-colors cursor-pointer">
                                    <?= htmlspecialchars($topic['FULL_NAME'] ?? 'User') ?>
                                </h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="text-sm text-gray-500">@<?= htmlspecialchars($topic['USERNAME'] ?? 'username') ?></p>
                                    <span class="text-gray-300">•</span>
                                    <p class="text-sm text-gray-500 js-time-ago" data-time="<?= htmlspecialchars($topic['CREATED_AT']) ?>">
                                        <?= htmlspecialchars($topic['CREATED_AT']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <?php
                        $dropdownPath = __DIR__ . '/../../dropDownTopic.php';
                        if (file_exists($dropdownPath)) {
                            include $dropdownPath;
                        }
                        ?>
                    </div>

                    <!-- Content Section -->
                    <?php if (!empty($topic['CONTENT'])): ?>
                        <div class="text-gray-800 leading-relaxed text-[15.5px]">
                            <?= htmlspecialchars($topic['CONTENT']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Media Gallery -->
                <?php if (!empty($images)): ?>
                    <div class="bg-gradient-to-b from-gray-900 to-black overflow-hidden mb-2 -mx-4 sm:mx-0 ">
                        <div class="swiper myPostSwiper w-full aspect-square sm:aspect-video sm:h-96 sm:min-h-[300px] sm:max-h-[500px]">
                            <div class="swiper-wrapper">
                                <?php foreach ($images as $img): ?>
                                    <div class="swiper-slide flex items-center justify-center bg-black">
                                        <img src="<?= BASEURL ?>/storage/forums/topics/<?= $img['MEDIA_PATH'] ?>"
                                            loading="lazy"
                                            class="w-full h-full object-contain">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($images) > 1): ?>
                                <div class="swiper-button-next hidden sm:flex text-white bg-black/30 backdrop-blur-sm rounded-full w-10 h-10 items-center justify-center hover:bg-black/50 transition-all after:text-lg"></div>
                                <div class="swiper-button-prev hidden sm:flex text-white bg-black/30 backdrop-blur-sm rounded-full w-10 h-10 items-center justify-center hover:bg-black/50 transition-all after:text-lg"></div>

                                <div class="swiper-pagination !bottom-3"></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- File Attachments -->
                <?php if (!empty($files)): ?>
                    <div class="px-6 pb-5 flex flex-col gap-3">
                        <?php foreach ($files as $file): ?>
                            <a href="<?= BASEURL ?>/storage/forums/topics/<?= $file['MEDIA_PATH'] ?>" target="_blank" download="<?= htmlspecialchars($file['ORIGINAL_FILENAME']) ?>" class="group block">
                                <div class="flex items-center p-3 border border-gray-300 rounded-xl bg-gray-50 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-500 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-1 overflow-hidden">
                                        <h5 class="text-sm font-bold text-gray-900 group-hover:text-blue-700 truncate"><?= htmlspecialchars($file['ORIGINAL_FILENAME']) ?></h5>
                                        <p class="text-xs text-gray-500">Klik untuk mengunduh</p>
                                    </div>
                                    <div class="flex-shrink-0 ml-3 text-gray-400 group-hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Stats Bar -->
                <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50">
                    <div class="flex items-center justify-between text-sm text-gray-600">
                        <div class="flex items-center gap-2 hover:text-blue-600 transition-colors cursor-pointer">
                            <div class="flex -space-x-1">
                                <span class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full p-1 ring-2 ring-white">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v7.333a2 2 0 01-.826 1.57l-2.174.43z" />
                                    </svg>
                                </span>
                            </div>
                            <span class="font-semibold"><?= $totalLikes ?> Likes</span>
                        </div>
                        <div class="font-semibold hover:text-blue-600 transition-colors cursor-pointer">
                            <?= $topic['TOTAL_COMMENTS'] ?> Commentar
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-gray-100 p-2 flex justify-center gap-2 bg-white">
                    <button class="like-btn flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl transition-all hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 cursor-pointer group w-1/2 relative overflow-hidden"
                        data-topic-id="<?= $topic['ID'] ?? 0 ?>"
                        data-liked="<?= $isLikedByUser ? 'true' : 'false' ?>">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-500/0 to-pink-500/0 group-hover:from-red-500/5 group-hover:to-pink-500/5 transition-all duration-300"></div>
                        <svg class="w-5 h-5 transition-all duration-300 relative z-10 <?= $isLikedByUser ? 'text-red-500 fill-red-500 animate-pulse' : 'text-gray-600 group-hover:text-red-500 group-hover:scale-110' ?>"
                            fill="<?= $isLikedByUser ? 'currentColor' : 'none' ?>"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="text-gray-700 group-hover:text-red-500 text-sm font-semibold transition-colors relative z-10">Like</span>
                    </button>

                    <button onclick="document.getElementById('commentForm').focus()"
                        class="flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl transition-all hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50 cursor-pointer group w-1/2 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/0 to-cyan-500/0 group-hover:from-blue-500/5 group-hover:to-cyan-500/5 transition-all duration-300"></div>
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600 group-hover:scale-110 transition-all duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="text-gray-700 group-hover:text-blue-600 text-sm font-semibold transition-colors relative z-10">Comment</span>
                    </button>
                </div>
            </div>

            <!-- Comment Form -->
            <?php require_once 'app/views/components/forum/createTopicComment.php'; ?>



            <!-- Comments Section -->
            <?php require_once 'app/views/components/forum/listTopicComment.php'; ?>

        </div>
    </div>
</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var swiper = new Swiper(".myPostSwiper", {
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev"
            },
            spaceBetween: 0,
            grabCursor: true,
        });

        function timeAgo(dateString) {
            const date = new Date(dateString.replace(/-/g, "/"));
            const now = new Date();

            if (isNaN(date.getTime())) return dateString;

            const seconds = Math.floor((now - date) / 1000);

            let interval = seconds / 31536000;
            if (interval > 1) return Math.floor(interval) + "y ago";

            interval = seconds / 2592000;
            if (interval > 1) return Math.floor(interval) + "mo ago";

            interval = seconds / 86400;
            if (interval > 1) return Math.floor(interval) + "d ago";

            interval = seconds / 3600;
            if (interval > 1) return Math.floor(interval) + "h ago";

            interval = seconds / 60;
            if (interval > 1) return Math.floor(interval) + "m ago";

            return "Just now";
        }

        const timeElements = document.querySelectorAll('.js-time-ago');
        timeElements.forEach(function(el) {
            const rawDate = el.getAttribute('data-time');
            if (rawDate) {
                el.textContent = timeAgo(rawDate);
            }
        });
    });
</script>