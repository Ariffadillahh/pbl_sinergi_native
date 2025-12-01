<?php
$posts = $posts ?? [];

$role = $_SESSION['role'] ?? '';
$hiddenRoles = ['ADMIN', 'DOSEN', 'ALUMNI', 'MITRA'];

function translateRole($role) {
    switch (strtoupper($role)) {
        case 'MAHASISWA':
            return 'Student';
        case 'DOSEN':
            return 'Lecturer';
        case 'ALUMNI':
            return 'Alumni';
        case 'MITRA':
            return 'Partner';
        case 'ADMIN':
            return 'Admin';
        default:
            return ucfirst(strtolower($role)); // Default fallback
    }
}
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= BASEURL ?>/src/css/output.css" rel="stylesheet">
    <title><?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?> | Sinergi</title>
</head>

<body class="">

    <div class="relative w-full h-40 bg-gradient-to-r from-blue-500 via-indigo-600 to-purple-600 shadow-lg rounded-2xl">
        <div class="absolute -bottom-20 left-1/2 transform -translate-x-1/2">
            <div class="relative">
                <img src="<?= !empty($_SESSION['path_photo'])
                                ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                : BASEURL . '/src/asset/image/default.png' ?>"
                    alt="<?= htmlspecialchars($_SESSION['full_name'] ?? '-') ?>"
                    class="w-40 h-40 rounded-full border-4 border-white shadow-2xl object-cover bg-white">

                <span class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 border-4 border-white rounded-full"></span>
            </div>
        </div>
    </div>

    <div class="w-full px-4 sm:px-6 lg:px-8 mt-24 pb-12">
        <div class="flex flex-col mx-auto gap-6 mb-8 ">
            <div class="flex-1 text-center">
                <h1 class="md:text-4xl text-2xl font-bold text-gray-900 mb-2">
                    <?= htmlspecialchars($_SESSION['full_name'] ?? '-') ?>
                </h1>
                <p class="md:text-lg text-base text-gray-500 mb-4">
                    @<?= htmlspecialchars($_SESSION['username'] ?? '-') ?>
                </p>
            </div>

            <div class="max-w-xl w-full mx-auto">
                <div class="bg-white shadow-lg rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                        <div class="flex justify-between">
                            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Account Information
                            </h2>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 font-medium mb-1">Email</p>
                                <p class="text-sm text-gray-900 font-medium truncate">
                                    <?= htmlspecialchars($_SESSION['email'] ?? '') ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-red-500 pl-0.5 pt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="none">
                                    <rect x="25" y="15" width="40" height="60" rx="3" stroke="currentColor" stroke-width="4" stroke-linejoin="round" />
                                    <circle cx="45" cy="35" r="7" fill="currentColor" />
                                    <path d="M 32 55 Q 32 45 45 45 Q 58 45 58 55 L 58 60 L 32 60 Z" fill="currentColor" />
                                    <circle cx="65" cy="65" r="15" fill="currentColor" />
                                    <path d="M 58 65 L 62 70 L 72 58" stroke="white" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500 font-medium mb-0.5">
                                    <?php
                                    if (isset($_SESSION['role'])) {
                                        if ($_SESSION['role'] === 'MAHASISWA') {
                                            echo 'Student ID (NIM)';
                                        } elseif ($_SESSION['role'] === 'DOSEN') {
                                            echo 'Lecturer ID (NIP)';
                                        } else {
                                            echo 'Personal Number';
                                        }
                                    } else {
                                        echo 'Personal Number';
                                    }
                                    ?>
                                </p>
                                <p class="text-sm text-gray-900 font-semibold truncate">
                                    <?= htmlspecialchars($_SESSION['personal_number'] ?? '-') ?>
                                </p>
                            </div>

                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-medium mb-1">Role</p>
                                <p class="text-sm text-gray-900 font-medium capitalize">
                                    <?= htmlspecialchars(translateRole($_SESSION['role'] ?? '')) ?>
                                </p>
                            </div>
                        </div>


                        <?php if (!in_array($role, $hiddenRoles)): ?>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium mb-1">Study Level</p>
                                    <?php if (!isset($_SESSION['jenjang_studi']) || empty($_SESSION['jenjang_studi'])): ?>
                                        <p class="text-sm font-medium text-blue-600">
                                            Setup Your Study Level
                                        </p>
                                    <?php else: ?>
                                        <p class="text-xs text-gray-500 font-medium mb-1">
                                            <?= htmlspecialchars($_SESSION['jenjang_studi']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-gray-500 font-medium mb-1">Study Program</p>
                                    <?php if (!isset($_SESSION['prodi']) || empty($_SESSION['prodi'])): ?>
                                        <p class="text-sm font-medium text-blue-600">
                                            Setup Your Study Program
                                        </p>
                                    <?php else: ?>
                                        <p class="text-xs text-gray-500 font-medium mb-1">
                                            <?= htmlspecialchars($_SESSION['prodi'] ?? '-') ?> - Batch <?= htmlspecialchars($_SESSION['tahun_masuk'] ?? '-') ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>

                    <div class="flex gap-3 px-6">
                        <button type="button" id="openModalEditProfile" class="cursor-pointer text-white w-full bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                            Edit Profile
                        </button>
                            <button id="btn-open-update-password" class="cursor-pointer relative inline-flex items-center justify-center p-0.5 w-full mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 ">
                                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white w-full rounded-md group-hover:bg-transparent ">
                                    Update Password
                                </span>
                            </button>

                        </div>
                        <?php
                        $userRole  = $_SESSION['role'] ?? '';
                        $userEmail = $_SESSION['email'] ?? '';

                        $isAlumni = ($userRole === 'ALUMNI');


                        $isTikStudent = preg_match('/\.tik\d+@stu\.pnj\.ac\.id$/', $userEmail);
                        ?>

                        <?php
                        if ($isAlumni && $isTikStudent):
                        ?>
                            <div class="mt-2 text-center text-blue-500 underline cursor-pointer mb-4">
                                <p onclick="openStudentModal()" class="text-sm"> Still a student?</p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

            <?php if ($_SESSION['role'] !== 'MITRA' && $_SESSION['role'] !== 'ALUMNI') : ?>
                <div class="max-w-xl mx-auto mt-12 mb-5 md:mb-0">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            Posts
                        </h2>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">
                            <?= count($posts) ?> posts
                        </span>
                    </div>

                    <?php if (empty($posts)): ?>
                        <div class="bg-white rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No posts yet</h3>
                            <p class="text-gray-500">Start sharing your thoughts and ideas with the community!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($posts as $post): ?>
                            <div class="my-5" id="post-<?= $post['POST_ID'] ?>">
                                <div class="bg-white text-gray-900 border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                                    <div class="p-4">
                                        <div class="flex items-start space-x-3">
                                            <img src="<?= !empty($_SESSION['path_photo'])
                                                            ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                                            : BASEURL . '/src/asset/image/default.png' ?>"
                                                alt="Profile" class="w-12 h-12 rounded-full object-cover flex-shrink-0">

                                            <div class="flex-1">
                                                <div class="text-lg">
                                                    <span class="font-semibold text-gray-700"><?= htmlspecialchars($_SESSION['full_name']) ?></span>
                                                </div>
                                                <div class="text-sm">
                                                    <span class="text-gray-500">@<?= htmlspecialchars($_SESSION['username']) ?></span>
                                                    <span class="text-gray-400">·</span>
                                                    <span class="text-gray-400 time-ago" data-time="<?= $post['CREATED_AT'] ?>">
                                                        <?= date('d M Y', strtotime($post['CREATED_AT'])) ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="relative">
                                                <div class="relative inline-block text-left">
                                                    <button
                                                        class="cursor-pointer p-2 rounded-full hover:bg-gray-100 transition-colors"
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
                                                        <?php
                                                        $content = $post['CONTENT'];
                                                        if ($content instanceof OCILob) {
                                                            $content = $content->load();
                                                        }

                                                        $media = $post['MEDIA'] ?? [];
                                                        $postJson = htmlspecialchars(json_encode([
                                                            'id' => $post['POST_ID'],
                                                            'content' => $content ?? '',
                                                            'media' => $media,
                                                        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES);
                                                        ?>
                                                        <button
                                                            type="button"
                                                            class="edit-post-btn cursor-pointer w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-gray-100"
                                                            data-post="<?= $postJson ?>">
                                                            Edit
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="cursor-pointer w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100"
                                                            onclick="openDeletePostModal('<?= $post['POST_ID'] ?>')">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <p class="text-black text-[15px] leading-relaxed"><?= $post['CONTENT_FORMATTED'] ?? '' ?></p>
                                        </div>
                                    </div>

                                    <?php if (!empty($post['MEDIA'])): ?>
                                        <div class="bg-gradient-to-b from-gray-900 to-black overflow-hidden">
                                            <swiper-container class="mySwiper aspect-video w-full min-h-[250px] md:min-h-[400px]" init="false">
                                                <?php foreach ($post['MEDIA'] as $mediaPath): ?>
                                                    <swiper-slide>
                                                        <img src="<?= BASEURL . '/' . $mediaPath ?>" class="w-full h-full object-contain bg-gray-50">
                                                    </swiper-slide>
                                                <?php endforeach; ?>
                                            </swiper-container>
                                        </div>
                                    <?php endif; ?>

                                    <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                                        <div class="flex items-center justify-between text-sm text-gray-600">
                                            <div class="flex items-center gap-2 hover:text-blue-600 transition-colors cursor-pointer">
                                                <div class="flex -space-x-1">
                                                    <span class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full p-1 ring-2 ring-white">
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v7.333a2 2 0 01-.826 1.57l-2.174.43z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <span class="like-count-display font-semibold"><?= htmlspecialchars($post['TOTAL_LIKES'] ?? 0) ?> Likes</span>
                                            </div>
                                            <div class="font-semibold hover:text-blue-600 transition-colors cursor-pointer">
                                                <?= htmlspecialchars($post['TOTAL_COMMENT'] ?? 0) ?> Comments
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-100 p-2 flex justify-center gap-2 bg-white">
                                        <button class="like-btn flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl transition-all hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 cursor-pointer group w-1/2 relative overflow-hidden"
                                            data-post-id="<?= $post['POST_ID'] ?>"
                                            data-liked="<?= $post['IS_LIKED'] ? 'true' : 'false' ?>">
                                            <div class="absolute inset-0 bg-gradient-to-r from-red-500/0 to-pink-500/0 group-hover:from-red-500/5 group-hover:to-pink-500/5 transition-all duration-300"></div>
                                            <svg class="w-5 h-5 transition-all duration-300 relative z-10 <?= $post['IS_LIKED'] ? 'text-red-500 fill-red-500' : 'text-gray-600 group-hover:text-red-500 group-hover:scale-110' ?>"
                                                fill="<?= $post['IS_LIKED'] ? 'currentColor' : 'none' ?>"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            <span class="text-gray-700 group-hover:text-red-500 text-sm font-semibold transition-colors relative z-10">Like</span>
                                        </button>

                                        <a href="<?= BASEURL ?>/homepage/reply/<?= $post['POST_ID'] ?>"
                                            class="flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl transition-all hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50 cursor-pointer group w-1/2 relative overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/0 to-cyan-500/0 group-hover:from-blue-500/5 group-hover:to-cyan-500/5 transition-all duration-300"></div>
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600 group-hover:scale-110 transition-all duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <span class="text-gray-700 group-hover:text-blue-600 text-sm font-semibold transition-colors relative z-10">Comment</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>


        <?php require_once 'app/views/components/modalReqRole.php'; ?>
        <?php require_once 'app/views/components/modalInvite.php'; ?>
        <?php require_once 'app/views/components/forum/modalInviteForum.php'; ?>
        <?php include_once 'app/views/components/modalEditProfile.php'; ?>
        <?php include_once 'app/views/components/modalUpdatePassword.php'; ?>
        <?php include_once 'app/views/components/postingan/modalDeletePost.php'; ?>
        <?php include_once 'app/views/components/postingan/modalEditPost.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
        <script>
            customElements.whenDefined('swiper-container').then(() => {
                const swiperElements = document.querySelectorAll('swiper-container.mySwiper');

                const style = `
                .swiper-button-next,
                .swiper-button-prev {
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    color: #ffffff; /* Ubah warna panah menjadi putih agar lebih terlihat */
                    padding: 6px;
                    background-color: rgba(0, 0, 0, 0.2); /* Latar belakang semi-transparan */
                    border-radius: 50%;
                    width: 15px;
                    height: 15px;
                    --swiper-navigation-size: 16px; /* Ukuran ikon panah */
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

            document.querySelectorAll('.like-btn').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                
                const postId = this.getAttribute('data-post-id');
                
                // Cari parent post container dengan ID
                const postContainer = document.getElementById('post-' + postId);
                if (!postContainer) {
                    console.error('Error: Post container tidak ditemukan untuk ID:', postId);
                    return;
                }
                
                // Cari elemen like count di dalam post container
                const countSpan = postContainer.querySelector('.like-count-display');
                const icon = this.querySelector('svg');

                if (!countSpan) {
                    console.error('Error: Element .like-count-display tidak ditemukan');
                    return;
                }

                console.log('Toggling like for post:', postId);

                try {
                    const res = await fetch('<?= BASEURL ?>/like/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            post_id: postId
                        })
                    });

                    const data = await res.json();
                    console.log('Response:', data);

                    if (data.success) {
                        const isLiked = data.action === 'liked';
                        this.setAttribute('data-liked', isLiked ? 'true' : 'false');
                        
                        // Update count dengan animasi
                        countSpan.textContent = data.total_likes + ' Likes';
                        countSpan.classList.add('scale-110', 'text-blue-600');
                        setTimeout(() => {
                            countSpan.classList.remove('scale-110', 'text-blue-600');
                        }, 200);

                        // Update icon dengan animasi
                        if (isLiked) {
                            icon.classList.remove('text-gray-600', 'group-hover:text-red-500');
                            icon.classList.add('text-red-500', 'fill-red-500', 'animate-pulse');
                            icon.setAttribute('fill', 'currentColor');
                            setTimeout(() => icon.classList.remove('animate-pulse'), 600);
                        } else {
                            icon.classList.remove('text-red-500', 'fill-red-500', 'animate-pulse');
                            icon.classList.add('text-gray-600', 'group-hover:text-red-500');
                            icon.setAttribute('fill', 'none');
                        }
                    } else {
                        console.error('Server error:', data.message);
                        alert(data.message || 'Gagal update like.');
                    }
                } catch (err) {
                    console.error('Fetch error:', err);
                    alert('Terjadi kesalahan saat memproses like.');
                }
            });
        });

            function toggleDropdown(id) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                    if (d.id !== id) d.classList.add('hidden');
                });
                const dropdown = document.getElementById(id);
                dropdown.classList.toggle('hidden');
            }

            function openEditPostModal(postId, content, mediaPaths = []) {
                const container = document.getElementById("media-preview-container");
                if (!container) {
                    console.error("Elemen #media-preview-container tidak ditemukan!");
                    return;
                }
                modalEditPost.classList.remove("hidden");
                modalEditPost.classList.add("flex");

                document.getElementById("edit-post-id").value = postId;
                document.getElementById("edit-post-content").value = content || "";

                existingMedia = [...mediaPaths];
                deletedMedia = [];
                newMediaFiles = [];
                renderMediaPreviews();
            }

            document.querySelectorAll('.edit-post-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const json = this.getAttribute('data-post');
                    if (!json) return console.error('data-post kosong pada tombol edit');
                    let data;
                    try {
                        data = JSON.parse(json);
                    } catch (e) {
                        console.error('Gagal parse data-post JSON', e, json);
                        return;
                    }
                    openEditPostModal(data.id, data.content, data.media || []);
                });
            });

            function openDeletePostModal(postId) {
                const modal = document.getElementById("modal-delete-post");
                modal.classList.remove("hidden");
                modal.classList.add("flex");
                document.getElementById("delete-post-id").value = postId;
            }

            function reportPost(postId) {
                alert("Report post: " + postId);
            }
        </script>
    </body>

    </html>