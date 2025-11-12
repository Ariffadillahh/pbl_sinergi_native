<?php
$posts = $posts ?? [];
$userById = $userById ?? [];
$role = htmlspecialchars($userById['ROLE'] ?? '-');

$roleClasses = [
    'ADMIN' => 'bg-red-100 text-red-800',
    'DOSEN' => 'bg-green-100 text-green-800',
    'MAHASISWA' => 'bg-blue-100 text-blue-800',
];

$badgeClass = $roleClasses[$role] ?? 'bg-gray-100 text-gray-800';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile @<?= $userById['USERNAME'] ?></title>
</head>

<body>
    <main class="w-full h-screen overflow-y-auto border-gray-200 hide-scrollbar relative z-[999]">
        <div class="sticky top-0 z-10 bg-white w-full px-5 py-3 border-b border-gray-200">
            <button onclick="window.history.back()" class="flex items-center gap-3 text-black font-semibold cursor-pointer">
                <img src="<?php echo BASEURL . '/src/asset/icons/left-arrow-svgrepo-com.svg'; ?>" alt="icon" class="w-6 h-6">
                <h1 class="text-xl">Profile <span class="text-blue-600">@<?= $userById['USERNAME'] ?></span></h1>
            </button>
        </div>
        <div class="max-w-xl mx-auto px-5 md:p-0 mb-20 md:mb-10">
            <div class="relative w-full h-48 bg-gradient-to-r from-blue-500 via-indigo-600 to-purple-600">
                <div class="absolute -bottom-16 left-6">
                    <div class="relative">
                        <img src="<?= !empty($userById['PATH_PHOTO'])
                                        ? BASEURL . '/storage/users/photos/' . $userById['PATH_PHOTO']
                                        : BASEURL . '/src/asset/image/default.png' ?>"
                            alt="<?= htmlspecialchars($userById['FULL_NAME'] ?? '-') ?>"
                            class="w-32 h-32 rounded-full border-4 border-white shadow-xl object-cover bg-white">
                        <?php if (!empty($userById['IS_ONLINE'])): ?>
                            <span class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full"></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="px-6 pt-20 pb-4 bg-white border-b border-gray-200">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            <?= htmlspecialchars($userById['FULL_NAME'] ?? '-') ?>
                        </h1>
                        <div class="flex gap-1">
                            <p class="text-gray-500">
                                @<?= htmlspecialchars($userById['USERNAME'] ?? '-') ?>
                            </p>
                            <p class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold <?= $badgeClass ?>">
                                <?= $role ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border-b border-gray-200 px-6 py-3 rounded-b-2xl">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Posts</h2>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                        <?= count($posts) ?>
                    </span>
                </div>
            </div>

            <main class="w-full min-h-screen overflow-y-auto border-gray-200 hide-scrollbar">
                <div class="bg-[#F3F4F6]">
                    <?php if (empty($posts)): ?>
                        <div class="flex flex-col items-center justify-center py-16 px-6 mt-5 rounded-2xl bg-white">
                            <div class="bg-gray-100 rounded-full p-6 mb-4">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada postingan</h3>
                        </div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="my-5">
                                <div class="bg-white text-gray-900 border border-gray-200 rounded-2xl shadow-sm p-4">
                                    <div class="flex items-start space-x-3">
                                        <img src="<?= !empty($userById['PATH_PHOTO'])
                                                        ? BASEURL . '/storage/users/photos/' . $userById['PATH_PHOTO']
                                                        : BASEURL . '/src/asset/image/default.png' ?>"
                                            alt="Profile" class="w-12 h-12 rounded-full object-cover flex-shrink-0">

                                        <div class="flex-1">
                                            <div class="text-lg">
                                                <span class="font-semibold text-gray-700"><?= htmlspecialchars($userById['FULL_NAME']) ?></span>
                                            </div>
                                            <div class="text-sm">
                                                <span class="text-gray-500">@<?= htmlspecialchars($userById['USERNAME']) ?></span>
                                                <span class="text-gray-400">· <?= date('d M Y', strtotime($post['CREATED_AT'])) ?></span>
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <div class="relative inline-block text-left">
                                                <button
                                                    class="p-2 rounded-full hover:bg-gray-100 transition-colors"
                                                    onclick="toggleDropdown('dropdown-<?= $post['POST_ID'] ?>')">
                                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                        <circle cx="5" cy="12" r="2" />
                                                        <circle cx="12" cy="12" r="2" />
                                                        <circle cx="19" cy="12" r="2" />
                                                    </svg>
                                                </button>

                                                <div
                                                    id="dropdown-<?= $post['POST_ID'] ?>"
                                                    class="hidden absolute right-0 mt-2 w-36 bg-white border border-gray-200 rounded-lg shadow-lg z-20 overflow-hidden">
                                                    <?php if ($isOwner): ?>
                                                        <button
                                                            type="button"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100"
                                                            onclick="openDeletePostModal('<?= $post['POST_ID'] ?>')">
                                                            Hapus
                                                        </button>
                                                    <?php else: ?>
                                                        <button
                                                            type="button"
                                                            class="report-btn w-full text-left px-4 py-2 text-sm text-black hover:bg-gray-100"
                                                            data-post-id="<?= $post['POST_ID']; ?>">
                                                            Report
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <p class="mt-2 text-black text-[15px leading-relaxed"><?= $post['CONTENT_FORMATTED'] ?? '' ?></p>
                                    </div>

                                    <?php if (!empty($post['MEDIA'])): ?>
                                        <div class="mt-4 rounded-2xl overflow-hidden border border-gray-100 ">
                                            <swiper-container class="mySwiper aspect-video w-full min-h-[250px] md:min-h-[400px]" init="false">
                                                <?php foreach ($post['MEDIA'] as $mediaPath): ?>
                                                    <swiper-slide>
                                                        <img src="<?= BASEURL . '/' . $mediaPath ?>" class="w-full h-full object-contain bg-gray-50">
                                                    </swiper-slide>
                                                <?php endforeach; ?>
                                            </swiper-container>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-3 flex items-center justify-between text-gray-500 text-sm border-t border-gray-100 pt-3">
                                        <div class="flex items-center space-x-6">
                                            <button class="like-btn flex items-center hover:text-red-500 transition-colors group cursor-pointer" data-post-id="<?= $post['POST_ID'] ?>" data-liked="<?= $post['IS_LIKED'] ? 'true' : 'false' ?>">
                                                <div class="p-2">
                                                    <svg class="w-5 h-5 <?= $post['IS_LIKED'] ? 'text-red-500 fill-red-500' : '' ?>" fill="<?= $post['IS_LIKED'] ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                </div>
                                                <span class="like-count"><?= htmlspecialchars($post['TOTAL_LIKES'] ?? 0) ?></span>
                                            </button>


                                            <a href="<?= BASEURL ?>/homepage/reply/<?= $post['POST_ID'] ?>" class="flex items-center hover:text-blue-600 transition-colors group cursor-pointer">
                                                <div class="p-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                </div>
                                                <span><?= htmlspecialchars($post['TOTAL_COMMENT'] ?? 0) ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </main>

        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <script>
        customElements.whenDefined('swiper-container').then(() => {
            const swiperElements = document.querySelectorAll('swiper-container.mySwiper');

            const style = `
            .swiper-button-next,
            .swiper-button-prev {
                opacity: 0;
                transition: opacity 0.3s ease;
                color: #ffffff; 
                padding: 6px;
                background-color: rgba(0, 0, 0, 0.2); 
                border-radius: 50%;
                width: 15px;
                height: 15px;
                --swiper-navigation-size: 16px; 
            }

            :host(:hover) .swiper-button-next,
            :host(:hover) .swiper-button-prev {
                opacity: 1;
            }

            .swiper-button-disabled {
                opacity: 0 !important;
                pointer-events: none;
            }
        `;

            const swiperParams = {
                navigation: true,
                pagination: {
                    clickable: true,
                    dynamicBullets: true,
                },
                injectStyles: [style],
            };

            swiperElements.forEach(swiperEl => {
                Object.assign(swiperEl, swiperParams);
                swiperEl.initialize();
            });
        });
    </script>

</body>

</html>