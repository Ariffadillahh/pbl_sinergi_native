<?php

$bannerUrl = !empty($forumById['PATH_THUMBNAIL'])
    ? BASEURL . '/storage/forums/thumbnail/' . $forumById['PATH_THUMBNAIL']
    : 'https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&q=80';

$iconUrl = !empty($forumById['PATH_PHOTO'])
    ? BASEURL . '/storage/forums/photos/' . $forumById['PATH_PHOTO']
    : 'https://ui-avatars.com/api/?name=' . urlencode($forumById['NAME']) . '&background=random';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>Forum <?= $forumById['NAME'] ?></title>
    <style>
        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .swiper-button-next,
        .swiper-button-prev {
            background: rgba(255, 255, 255, 0.9);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }

        .swiper:hover .swiper-button-next,
        .swiper:hover .swiper-button-prev {
            opacity: 1;
        }

        .swiper-button-next.swiper-button-disabled,
        .swiper-button-prev.swiper-button-disabled {
            opacity: 0 !important;
        }

        .swiper-pagination-bullet {
            width: 8px;
            height: 8px;
            background: #fff;
            opacity: 0.5;
            transition: all 0.3s ease;
        }

        .swiper-pagination-bullet-active {
            opacity: 1;
            background: #fff;
            width: 24px;
            border-radius: 4px;
        }

        .swiper-pagination {
            bottom: 12px !important;
        }
    </style>
</head>

<body>
    <div class="w-full h-full overflow-y-auto">
        <div class="bg-white shadow-sm">
            <div class="h-48 md:h-72 bg-gradient-to-r from-blue-900 via-blue-700 to-cyan-500 relative overflow-hidden">
                <img src="<?= htmlspecialchars($bannerUrl) ?>" alt="Cover" class="w-full h-full object-cover opacity-60">
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
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight"><?= $forumById['NAME'] ?></h1>
                            <p class="text-gray-600 text-sm md:text-base">🌐 Public group · 235.5K members</p>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4 md:mt-0 w-full md:w-auto justify-center md:justify-end mb-2">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 md:px-6 py-2 rounded-lg font-semibold text-sm md:text-base flex-1 md:flex-none">
                            + Invite
                        </button>
                        <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 md:px-6 py-2 rounded-lg font-semibold text-sm md:text-base flex-1 md:flex-none">
                            ✓ Joined
                        </button>
                        <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg flex-none">
                            ⋯
                        </button>
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

                        <div class="bg-white rounded-lg shadow p-4 mb-4 border-l-4 border-blue-600 relative overflow-hidden">

                            <div class="flex items-center gap-2 mb-3 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform rotate-45" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-wider">Featured Post</span>
                            </div>

                            <div class="flex items-center gap-3 mb-3">
                                <img src="https://ui-avatars.com/api/?name=Admin+Group&background=random" alt="Admin" class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="font-bold text-sm text-gray-900">Admin Group</p>
                                    <p class="text-xs text-gray-500">Updated 2 hours ago</p>
                                </div>
                            </div>

                            <div class="text-sm">
                                <h4 class="font-bold text-gray-900 mb-1">⚠️ PERATURAN GRUP (Wajib Baca)</h4>
                                <p class="text-gray-700 leading-relaxed mb-3">
                                    Dilarang keras melakukan spam link phising. Transaksi wajib menggunakan REKBER (Rekening Bersama) admin yang bertugas.
                                </p>
                            </div>

                            <div class="bg-gray-100 rounded-lg p-2 mb-3 flex items-center gap-2">
                                <div class="bg-blue-100 p-2 rounded text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-800">Daftar_Rekber_Resmi.pdf</p>
                                    <p class="text-xs text-gray-500">120 KB</p>
                                </div>
                            </div>

                            <div class="border-t pt-2 flex items-center justify-between text-gray-500 text-xs">
                                <span>124 comments</span>
                                <span>45 Like</span>
                            </div>

                        </div>

                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div id="tabContent">

                        <!-- Discussion Tab -->
                        <div class="tab-content active space-y-4" data-content="discussion">

                            <!-- Create Post Card -->
                            <?php require_once 'app/views/components/forum/createTopic.php'; ?>

                            <!-- Post 1 with Slider -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold flex-shrink-0">
                                                YU
                                            </div>
                                            <div>
                                                <h4 class="font-semibold">Ya Udah Iya</h4>
                                                <p class="text-xs text-gray-500">20h · 🌐</p>
                                            </div>
                                        </div>
                                        <button class="text-gray-500 hover:bg-gray-100 rounded-full p-2 transition">⋯</button>
                                    </div>

                                    <p class="text-gray-900 mb-3">WTS Akun PUBG Season 20 Conqueror, full skin legendary! Cek SS nya 🔥</p>
                                </div>

                                <!-- Swiper Slider -->
                                <div class="swiper postSwiper1" style="height: 400px;">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <img src="https://images.unsplash.com/photo-1538481199705-c710c4e965fc?w=800" alt="PUBG 1">
                                        </div>
                                        <div class="swiper-slide">
                                            <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?w=800" alt="PUBG 2">
                                        </div>
                                        <div class="swiper-slide">
                                            <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800" alt="PUBG 3">
                                        </div>
                                        <div class="swiper-slide">
                                            <img src="https://images.unsplash.com/photo-1560253023-3ec5d502959f?w=800" alt="PUBG 4">
                                        </div>
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-pagination"></div>
                                </div>

                                <div class="border-t border-gray-200 py-2 px-2 flex justify-center gap-1 select-none">
                                    <button
                                        class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition hover:bg-gray-100 cursor-pointer group w-1/2">
                                        <svg
                                            class="w-5 h-5 text-gray-500 group-hover:text-red-500 transition-all duration-300"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>

                                        <span class="text-gray-600 group-hover:text-red-500">12</span>
                                    </button>

                                    <button
                                        class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition hover:bg-gray-100 cursor-pointer group w-1/2">
                                        <svg
                                            class="w-5 h-5 text-gray-500 group-hover:text-blue-600 transition"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>

                                        <span class="text-gray-600 group-hover:text-blue-600">3</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Post 3 -->
                            <div class="bg-white rounded-lg shadow">
                                <div class="p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex gap-3">
                                            <div class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-bold flex-shrink-0">
                                                BS
                                            </div>
                                            <div>
                                                <h4 class="font-semibold">Budi Santoso</h4>
                                                <p class="text-xs text-gray-500">2d · 🌐</p>
                                            </div>
                                        </div>
                                        <button class="text-gray-500 hover:bg-gray-100 rounded-full p-2 transition">⋯</button>
                                    </div>

                                    <p class="text-gray-900 mb-3">LF akun pubg budget 200-250rb, tier minimal diamond, RP lengkap. Ada yg jual?</p>
                                </div>



                                <div class="border-t border-gray-200 py-2 px-2 flex justify-center gap-1 select-none">
                                    <button
                                        class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition hover:bg-gray-100 cursor-pointer group w-1/2">
                                        <svg
                                            class="w-5 h-5 text-gray-500 group-hover:text-red-500 transition-all duration-300"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>

                                        <span class="text-gray-600 group-hover:text-red-500">12</span>
                                    </button>

                                    <button
                                        class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition hover:bg-gray-100 cursor-pointer group w-1/2">
                                        <svg
                                            class="w-5 h-5 text-gray-500 group-hover:text-blue-600 transition"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>

                                        <span class="text-gray-600 group-hover:text-blue-600">3</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Post 4 -->
                            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">

                                <!-- 1. HEADER (User Info) -->
                                <div class="p-4 pb-2">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex gap-3">
                                            <!-- Avatar -->
                                            <div class="w-10 h-10 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold flex-shrink-0 text-sm">
                                                SN
                                            </div>
                                            <!-- Name & Time -->
                                            <div>
                                                <h4 class="font-semibold text-gray-900 leading-tight">Siti Nurhaliza</h4>
                                                <p class="text-xs text-gray-500 mt-0.5">3d · 🌐</p>
                                            </div>
                                        </div>
                                        <!-- Menu Button -->
                                        <button class="text-gray-400 hover:bg-gray-100 rounded-full p-2 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Post Text -->
                                    <p class="text-gray-900 mb-2">
                                        Berikut saya lampirkan surat perjanjian jual beli yang sudah disepakati. Silakan diunduh ya. 🙏
                                    </p>
                                </div>



                                <div class="px-4 pb-4">
                                    <a href="#" class="group block">
                                        <div class="flex items-center p-3 border border-gray-300 rounded-xl bg-gray-50 hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">

                                            <div class="flex-shrink-0 h-12 w-12 bg-blue-100 text-blue-500 rounded-lg flex items-center justify-center group-hover:scale-105 transition-transform">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>

                                            <div class="ml-4 flex-1 overflow-hidden">
                                                <h5 class="text-sm font-bold text-gray-900 group-hover:text-blue-700 truncate">Surat_Perjanjian_Jual_Beli_Sah.pdf</h5>
                                            </div>

                                            <div class="flex-shrink-0 ml-3 text-gray-400 group-hover:text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="border-t border-gray-100 px-4 py-3 flex items-center justify-between text-xs text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <span class="bg-blue-500 text-white rounded-full p-0.5">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v7.333a2 2 0 01-.826 1.57l-2.174.43z" />
                                            </svg>
                                        </span>
                                        <span>12</span>
                                    </div>
                                    <div class="flex gap-3 hover:underline cursor-pointer">
                                        <span>2 comments</span>
                                        <span>1 share</span>
                                    </div>
                                </div>



                                <div class="border-t border-gray-200 py-2 px-2 flex justify-center gap-1 select-none">
                                    <button
                                        class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition hover:bg-gray-100 cursor-pointer group w-1/2">
                                        <svg
                                            class="w-5 h-5 text-gray-500 group-hover:text-red-500 transition-all duration-300"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>

                                        <span class="text-gray-600 group-hover:text-red-500">12</span>
                                    </button>

                                    <button
                                        class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition hover:bg-gray-100 cursor-pointer group w-1/2">
                                        <svg
                                            class="w-5 h-5 text-gray-500 group-hover:text-blue-600 transition"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>

                                        <span class="text-gray-600 group-hover:text-blue-600">3</span>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <!-- People Tab -->
                        <div class="tab-content hidden" data-content="people">
                            <div class="bg-white rounded-lg shadow p-8 text-center">
                                <div class="text-6xl mb-4">👥</div>
                                <h3 class="text-xl font-bold mb-2">Group Members</h3>
                                <p class="text-gray-600">235.5K members in this group</p>
                            </div>
                        </div>

                        <!-- Media Tab -->
                        <div class="tab-content hidden" data-content="media">
                            <div class="bg-white rounded-lg shadow p-8 text-center">
                                <div class="text-6xl mb-4">🖼️</div>
                                <h3 class="text-xl font-bold mb-2">Media Gallery</h3>
                                <p class="text-gray-600">Photos and videos shared in this group</p>
                            </div>
                        </div>

                        <!-- Files Tab -->
                        <div class="tab-content hidden" data-content="files">
                            <div class="bg-white rounded-lg shadow p-8 text-center">
                                <div class="text-6xl mb-4">📁</div>
                                <h3 class="text-xl font-bold mb-2">Group Files</h3>
                                <p class="text-gray-600">Documents shared in this group</p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Initialize all Swiper instances
        document.addEventListener('DOMContentLoaded', function() {
            // Swiper 1
            const swiper1 = new Swiper('.postSwiper1', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: false,
                pagination: {
                    el: '.postSwiper1 .swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                    dynamicMainBullets: 3,
                },
                navigation: {
                    nextEl: '.postSwiper1 .swiper-button-next',
                    prevEl: '.postSwiper1 .swiper-button-prev',
                },
            });

            // Swiper 2
            const swiper2 = new Swiper('.postSwiper2', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: false,
                pagination: {
                    el: '.postSwiper2 .swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                    dynamicMainBullets: 3,
                },
                navigation: {
                    nextEl: '.postSwiper2 .swiper-button-next',
                    prevEl: '.postSwiper2 .swiper-button-prev',
                },
            });

            // Swiper 4
            const swiper4 = new Swiper('.postSwiper4', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: false,
                pagination: {
                    el: '.postSwiper4 .swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                    dynamicMainBullets: 3,
                },
                navigation: {
                    nextEl: '.postSwiper4 .swiper-button-next',
                    prevEl: '.postSwiper4 .swiper-button-prev',
                },
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            if (tabButtons.length > 0) {
                tabButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const targetTab = this.getAttribute('data-tab');

                        // Remove active class from all buttons
                        tabButtons.forEach(btn => {
                            btn.classList.remove('active', 'text-blue-600', 'border-b-4', 'border-blue-600');
                            btn.classList.add('text-gray-600');
                        });

                        // Add active class to clicked button
                        this.classList.add('active', 'text-blue-600', 'border-b-4', 'border-blue-600');
                        this.classList.remove('text-gray-600');

                        // Hide all tab contents
                        tabContents.forEach(content => {
                            content.classList.add('hidden');
                            content.classList.remove('active');
                        });

                        // Show target tab content
                        const targetContent = document.querySelector(`[data-content="${targetTab}"]`);
                        if (targetContent) {
                            targetContent.classList.remove('hidden');
                            targetContent.classList.add('active');
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>