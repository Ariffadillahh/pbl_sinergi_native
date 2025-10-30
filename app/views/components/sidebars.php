<?php
$current_uri = $_SERVER['REQUEST_URI'];

$isHomepageActive = (strpos($current_uri, '/homepage') !== false);
$isForumsActive   = (strpos($current_uri, '/forums') !== false);
$accsesPages = (in_array($_SESSION['role'], ['MAHASISWA', 'DOSEN', 'ADMIN']));
?>

<div>
    <nav class="hidden lg:flex h-screen flex-col items-center justify-between min-w-[84px] shrink-0 bg-gray-200/70 px-5 py-[25px] z-[99999]">
        <ul class="flex flex-col gap-5">
            <li>
                <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="flex w-11 h-9 shrink-0" alt="logo">
            </li>

            <?php if ($accsesPages) : ?>
                <li class="group relative flex items-center <?php echo $isHomepageActive ? 'active' : ''; ?>">
                    <a href="<?php echo BASEURL ?>/homepage" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">
                        <img src="<?php echo BASEURL; ?>/src/asset/icons/chart-square-grey.svg" class="size-6 group-[.active]:hidden" alt="icon" />
                        <img src="<?php echo BASEURL; ?>/src/asset/icons/chart-square-white-fill.svg" class="size-6 hidden group-[.active]:flex" alt="icon" />
                    </a>
                    <h1 class="absolute left-full ml-4 whitespace-nowrap rounded bg-gray-900 px-3 py-1.5 text-sm font-medium text-white border border-gray-200 border-l-4 border-l-blue-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100 invisible group-hover:visible pointer-events-none z-[9999]">
                        Homepage
                    </h1>
                </li>
            <?php endif; ?>

            <li class="group relative flex items-center <?php echo $isForumsActive ? 'active' : ''; ?>">
                <a href="<?php echo BASEURL ?>/forums" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/messages.svg" class="size-6 group-[.active]:hidden" alt="icon">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/messages-white-fill.svg" class="size-6 hidden group-[.active]:flex" alt="icon">
                </a>
                <h1 class="absolute left-full ml-4 whitespace-nowrap rounded bg-gray-900 px-3 py-1.5 text-sm font-medium text-white border border-gray-200 border-l-4 border-l-blue-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100 invisible group-hover:visible pointer-events-none z-[9999]">
                    Forums
                </h1>
            </li>
            <li class="group relative flex items-center">
                <button id="notif-btn" class="relative size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>

                    <span id="notif-badge" class="hidden absolute top-1.5 right-1.5 h-4 w-4 items-center justify-center">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span id="notif-count" class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-red-500 text-white text-[10px] font-semibold">0</span>
                    </span>
                </button>
                <?php include_once 'app/views/components/notifikasi.php'; ?>
            </li>
        </ul>
        <ul class="flex flex-col gap-5">
            <li class="group relative flex items-center">
                <a href="<?php echo BASEURL ?>/logout" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 hover:ring-1 hover:ring-blue-600">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/Logout.svg" class="size-6" alt="icon">
                </a>
                <h1 class="absolute left-full ml-4 whitespace-nowrap rounded bg-gray-900 px-3 py-1.5 text-sm font-medium text-white border border-gray-200 border-l-4 border-l-blue-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100 invisible group-hover:visible pointer-events-none z-[9999]">
                    Sign Out
                </h1>
            </li>
            <li class="group relative flex items-center">
                <a href="<?php echo BASEURL ?>/profile" class="size-11 flex shrink-0 overflow-hidden rounded-full bg-white transition-all duration-300 hover:ring-1 hover:ring-blue-600 cursor-pointer">
                    <img src="<?= !empty($_SESSION['path_photo'])
                                    ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                    : BASEURL . '/src/asset/image/default.png' ?>" class="h-full w-full object-cover" alt="photo">
                </a>
                <h1 class="absolute left-full ml-4 whitespace-nowrap rounded bg-gray-900 px-3 py-1.5 text-sm font-medium text-white border border-gray-200 border-l-4 border-l-blue-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100 invisible group-hover:visible pointer-events-none z-[9999]">
                    Profile
                </h1>
            </li>
        </ul>
    </nav>

    <nav class="lg:hidden fixed bottom-0 left-0 right-0 grid <?php echo $accsesPages ? 'grid-cols-4' : 'grid-cols-3'; ?> h-[70px] items-center bg-white border-t border-gray-100 px-2 z-[9999]">

        <?php if ($accsesPages) : ?>
            <a href="<?php echo BASEURL ?>/homepage" class="flex flex-col items-center justify-center gap-1 text-center">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/<?php echo $isHomepageActive ? 'chart-square-white-fill.svg' : 'chart-square-grey.svg'; ?>"
                    class="h-7 w-7 <?php echo $isHomepageActive ? 'bg-blue-600 p-1 rounded-lg' : ''; ?>" alt="icon">
                <span class="text-xs <?php echo $isHomepageActive ? 'text-blue-600 font-semibold' : 'text-gray-500'; ?>">
                    HomePage
                </span>
            </a>
        <?php endif; ?>

        <?php if ($isForumsActive): ?>
            <button id="toggleForumsBtn" class="flex flex-col items-center justify-center gap-1 text-center">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/menuActive.png" class="h-7 w-7 bg-blue-600 p-1 rounded-lg" alt="icon">
                <span class="text-xs text-blue-600 font-semibold">Menu</span>
            </button>
        <?php else: ?>
            <a href="<?php echo BASEURL; ?>/forums" class="flex flex-col items-center justify-center gap-1 text-center">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/messages.svg" class="h-7 w-7" alt="icon">
                <span class="text-xs text-gray-500">Forums</span>
            </a>
        <?php endif; ?>

        <div class="relative flex flex-col items-center justify-center gap-1 text-center">
            <button id="notif-btn-mobile" class="relative group">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                <span id="notif-badge-mobile" class="hidden absolute -top-1 right-0 h-4 w-4 items-center justify-center">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span id="notif-count-mobile"
                        class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-red-500 text-white text-[10px] font-semibold">0</span>
                </span>
            </button>

            <div id="notif-dropdown-mobile"
                class="absolute bottom-full mb-2 right-0 hidden bg-white border border-gray-200 rounded-xl shadow-lg w-64 max-h-80 overflow-y-auto z-50">
                <?php include_once 'app/views/components/notifikasi.php'; ?>
            </div>

            <span class="text-xs text-gray-500">Notification</span>
        </div>


        <div class="relative flex flex-col items-center justify-center gap-1">
            <button id="btn-menu" class="h-8 w-8 rounded-full overflow-hidden cursor-pointer group">
                <img src="<?= !empty($_SESSION['path_photo'])
                                ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                : BASEURL . '/src/asset/image/default.png' ?>"
                    class="h-full w-full object-cover"
                    alt="photo">
            </button>
            <span class="text-xs text-gray-500">Profile</span>

            <div class="absolute bottom-full right-0.5 mb-4 hidden" id="menu-profile-signout">
                <ul class="bg-white shadow-lg rounded-lg py-2 w-32 text-sm text-gray-700 border border-gray-200">
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <a href="<?php echo BASEURL ?>/profile" class="flex gap-2 items-center">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/profile-2user-grey.svg" class="size-5" alt="icon">
                            Profile
                        </a>
                    </li>
                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <a href="<?php echo BASEURL ?>/logout" class="flex gap-2 items-center">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/Logout.svg" class="size-5" alt="icon">
                            Sign Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<script>
    const btnMenu = document.getElementById('btn-menu');
    const menu = document.getElementById('menu-profile-signout');

    btnMenu.addEventListener('click', (e) => {
        e.stopPropagation(); 
        menu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (!menu.classList.contains('hidden') &&
            !menu.contains(e.target) &&
            !btnMenu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>