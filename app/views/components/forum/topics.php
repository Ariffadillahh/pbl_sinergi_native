<?php


$visibleTopics = $topics;
$hasMorePosts  = false;

if (isset($postLimit) && $postLimit !== null && count($topics) > $postLimit) {
    $visibleTopics = array_slice($topics, 0, $postLimit);
    $hasMorePosts  = true;
}
?>

<div class="flex flex-col gap-6">

    <?php if (empty($topics)) : ?>
        <div class="flex flex-col items-center gap-4 p-6 bg-white rounded-lg shadow border border-gray-200">
            <svg class="w-24 h-24 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 64 64" stroke="currentColor">
                <circle cx="32" cy="32" r="30" stroke-width="2" class="stroke-gray-300" />
                <path d="M20 32h24M32 20v24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-900">Belum ada diskusi</h3>
                <p class="mt-2 text-sm text-gray-500">Jadilah yang pertama memulai diskusi di forum ini!</p>
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($visibleTopics as $topic): ?>
        <?php
        $allMedia = $topic['MEDIA'] ?? [];
        $images = array_filter($allMedia, fn($m) => $m['MEDIA_TYPE'] === 'IMAGE');
        $files = array_filter($allMedia, fn($m) => $m['MEDIA_TYPE'] === 'FILE');

        $isLikedByUser = isset($topic['IS_LIKED']) ? $topic['IS_LIKED'] : false;
        ?>

        <div class="topic-card bg-white rounded-lg shadow border border-gray-200 overflow-hidden" id="topic-<?= $topic['ID'] ?>">
            <div class="p-4 pb-2">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0 border border-gray-100">
                            <img src="<?= !empty($topic['PATH_PHOTO']) ? BASEURL . '/storage/users/photos/' . $topic['PATH_PHOTO'] : BASEURL . '/src/asset/image/default.png' ?>" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 leading-tight"><?= htmlspecialchars($topic['FULL_NAME']) ?></h4>
                            <p class="text-sm text-gray-500 mt-0.5">
                                @<?= htmlspecialchars($topic['USERNAME']) ?> ·
                                <span class="time-ago" data-time="<?= $topic['CREATED_AT'] ?>">
                                    <?= $topic['CREATED_AT'] ?> </span>
                            </p>
                        </div>
                    </div>
                    <?php include __DIR__ . '/dropDownTopic.php'; ?>
                </div>

                <?php if (isset($topic['IS_PINNED']) && $topic['IS_PINNED'] == 1): ?>
                    <div class="inline-flex items-center gap-1 bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium mb-2 ml-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        Pinned
                    </div>
                <?php endif; ?>

                <?php if (!empty($topic['CONTENT'])): ?>
                    <p class="text-gray-900 mb-2 whitespace-pre-wrap leading-relaxed"><?= htmlspecialchars($topic['CONTENT']) ?></p>
                <?php endif; ?>
            </div>

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

            <?php if (!empty($files)): ?>
                <div class="px-4 pb-4 flex flex-col gap-2 mt-2">
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

            <div class="border-t border-gray-100 px-4 py-2 mt-2">
                <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                    <div class="flex items-center gap-1 like-count-container">
                        <span class="bg-blue-500 text-white rounded-full p-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v7.333a2 2 0 01-.826 1.57l-2.174.43z" />
                            </svg>
                        </span>
                        <span class="like-count-display font-medium"><?= $topic['TOTAL_LIKES'] ?></span>
                    </div>
                    <div class="flex gap-3">
                        <span><?= $topic['TOTAL_COMMENTS'] ?> comments</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-1 flex justify-center gap-1 select-none">

                    <button class="like-btn flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition hover:bg-gray-100 cursor-pointer group w-1/2"
                        data-topic-id="<?= $topic['ID'] ?>"
                        data-liked="<?= $isLikedByUser ? 'true' : 'false' ?>">

                        <svg class="w-5 h-5 transition-all transform duration-200 <?= $isLikedByUser ? 'text-red-500 fill-red-500' : 'text-gray-500 group-hover:text-red-500' ?>"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>

                        <span class="text-gray-600 group-hover:text-red-500 text-sm font-medium">Like</span>
                    </button>

                    <a href="<?= BASEURL ?>/forum/topic/<?= $topic['ID'] ?>" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition hover:bg-gray-100 cursor-pointer group w-1/2">
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="text-gray-600 group-hover:text-blue-600 text-sm font-medium">Comment</span>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if ($hasMorePosts): ?>
        <div class="bg-blue-50 border border-dashed border-blue-300 rounded-lg p-8 text-center mt-2">
            <button onclick="joinForum('<?= $forumById['ID'] ?>')" class="bg-blue-600 text-white px-6 py-2 rounded-full font-medium hover:bg-blue-700 transition shadow-sm">
                Bergabung Sekarang
            </button>
        </div>
    <?php endif; ?>

</div>

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
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        function timeAgo(dateString) {
            if (!dateString) return '';

            const safeDateString = dateString.replace(' ', 'T');

            const date = new Date(safeDateString);
            const now = new Date();

            if (isNaN(date.getTime())) {
                console.error("Invalid date:", dateString);
                return dateString;
            }

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

        const timeElements = document.querySelectorAll('.time-ago');

        timeElements.forEach(function(el) {
            const rawDate = el.getAttribute('data-time');

            if (rawDate) {
                el.textContent = timeAgo(rawDate);
            }
        });

        const likeButtons = document.querySelectorAll('.like-btn');

        likeButtons.forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();

                const topicId = this.getAttribute('data-topic-id');
                const icon = this.querySelector('svg');

                const card = this.closest('.topic-card');

                const countSpan = card ? card.querySelector('.like-count-display') : null;

                if (!card) console.error("Error: Tidak bisa menemukan elemen .topic-card");
                if (!countSpan) console.error("Error: Tidak bisa menemukan elemen .like-count-display");

                try {
                    const res = await fetch('<?= BASEURL ?>/like/toggle/topic', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            'topic_id': topicId
                        })
                    });

                    const data = await res.json();

                    if (data.success) {
                        const isLiked = data.action === 'liked';

                        this.setAttribute('data-liked', isLiked ? 'true' : 'false');

                        console.log("Like toggled:", data);

                        if (countSpan) {
                            countSpan.textContent = data.total_likes;

                            countSpan.classList.add('scale-150', 'text-blue-600');
                            setTimeout(() => countSpan.classList.remove('scale-150', 'text-blue-600'), 200);
                        }

                        if (isLiked) {
                            icon.classList.remove('text-gray-500', 'group-hover:text-red-500');
                            icon.classList.add('text-red-500', 'fill-red-500');
                        } else {
                            icon.classList.remove('text-red-500', 'fill-red-500');
                            icon.classList.add('text-gray-500', 'group-hover:text-red-500');
                        }

                    } else {
                        console.error("Server Error:", data.message);
                    }
                } catch (err) {
                    console.error('Fetch Error:', err);
                }
            });
        });
    });
</script>