<?php
$current_uri = $_SERVER['REQUEST_URI'];

$isHomepageActive = preg_match('#/homepage(/|$)#', $current_uri);
$isForumsActive   = preg_match('#/forums?(/|$)#', $current_uri);
$isGroupActive    = preg_match('#/groups?(/|$)#', $current_uri);

$accsesPages = in_array($_SESSION['role'], ['MAHASISWA', 'DOSEN', 'ADMIN']);
?>


<div>
    <nav class="hidden lg:flex h-screen flex-col items-center justify-between min-w-[84px] shrink-0 bg-gray-200/70 px-5 py-[25px] z-[99999]">
        <ul class="flex flex-col gap-5">
            <li>
                <a href="<?= BASEURL ?>/homepage">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="flex w-11 h-9 shrink-0" alt="logo">
                </a>
            </li>

            <?php if ($accsesPages) : ?>
                <li class="group relative flex items-center <?php echo $isHomepageActive ? 'active' : ''; ?>">
                    <a href="<?php echo BASEURL ?>/homepage" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">

                        <svg class="size-6 text-gray-500 group-[.active]:hidden transition-colors" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 22V12H15V22M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                        <svg class="size-6 text-white hidden group-[.active]:flex" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L3 9V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H9V12H15V22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V9L12 2Z" />
                        </svg>

                    </a>

                    <div class="absolute left-full ml-3 whitespace-nowrap rounded-xl bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-2xl border border-gray-700/50 border-l-[3px] border-l-blue-500 opacity-0 scale-90 -translate-x-2 transition-all duration-300 ease-out group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0 invisible group-hover:visible pointer-events-none z-[9999] backdrop-blur-sm">
                        <span class="relative z-10">Homepage</span>
                        <div class="absolute inset-0 rounded-xl bg-blue-500/10 blur-sm"></div>
                        <div class="absolute right-full top-1/2 -translate-y-1/2 border-[6px] border-transparent border-r-gray-900"></div>
                    </div>
                </li>
            <?php endif; ?>

            <li class="group relative flex items-center <?php echo $isForumsActive ? 'active' : ''; ?>">
                <a href="<?php echo BASEURL ?>/forums"
                    class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[7px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-7 w-7 transition-all duration-300 <?= $isForumsActive ? 'text-white' : 'text-gray-500' ?>  group-[.active]:text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </a>

                <div class="absolute left-full ml-3 whitespace-nowrap rounded-xl bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-2xl border border-gray-700/50 border-l-[3px] border-l-blue-500 opacity-0 scale-90 -translate-x-2 transition-all duration-300 ease-out group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0 invisible group-hover:visible pointer-events-none z-[9999] backdrop-blur-sm">
                    <span class="relative z-10">Forums</span>
                    <div class="absolute inset-0 rounded-xl bg-blue-500/10 blur-sm"></div>
                    <div class="absolute right-full top-1/2 -translate-y-1/2 border-[6px] border-transparent border-r-gray-900"></div>
                </div>
            </li>

            <li class="group relative flex items-center <?php echo $isGroupActive ? 'active' : ''; ?>">
                <a href="<?php echo BASEURL ?>/groups" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/messages.svg" class="size-6 group-[.active]:hidden" alt="icon">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/messages-white-fill.svg" class="size-6 hidden group-[.active]:flex" alt="icon">
                </a>

                <div class="absolute left-full ml-3 whitespace-nowrap rounded-xl bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-2xl border border-gray-700/50 border-l-[3px] border-l-blue-500 opacity-0 scale-90 -translate-x-2 transition-all duration-300 ease-out group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0 invisible group-hover:visible pointer-events-none z-[9999] backdrop-blur-sm">
                    <span class="relative z-10">Chat Groups</span>
                    <div class="absolute inset-0 rounded-xl bg-blue-500/10 blur-sm"></div>
                    <div class="absolute right-full top-1/2 -translate-y-1/2 border-[6px] border-transparent border-r-gray-900"></div>
                </div>
            </li>

            <li class="group relative flex items-center">
                <button id="notif-btn" class="relative size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600 cursor-pointer">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span id="notif-badge" class="hidden absolute top-1.5 right-1.5 h-4 w-auto items-center justify-center">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>

                        <span id="notif-count" class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-red-500 text-white text-[10px] font-semibold">
                            0
                        </span>
                    </span>
                </button>

                <div class="absolute left-full ml-3 whitespace-nowrap rounded-xl bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-2xl border border-gray-700/50 border-l-[3px] border-l-blue-500 opacity-0 scale-90 -translate-x-2 transition-all duration-300 ease-out group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0 invisible group-hover:visible pointer-events-none z-[9999] backdrop-blur-sm">
                    <span class="relative z-10">Notification</span>
                    <div class="absolute inset-0 rounded-xl bg-blue-500/10 blur-sm"></div>
                    <div class="absolute right-full top-1/2 -translate-y-1/2 border-[6px] border-transparent border-r-gray-900"></div>
                </div>
                <?php include_once 'app/views/components/notifikasi.php'; ?>
            </li>
        </ul>

        <ul class="flex flex-col gap-5">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN'): ?>
                <li class="group relative flex items-center">
                    <a href="<?php echo BASEURL ?>/dashboard" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 hover:ring-1 hover:ring-blue-600">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                    </a>

                    <div class="absolute left-full ml-3 whitespace-nowrap rounded-xl bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-2xl border border-gray-700/50 border-l-[3px] border-l-blue-500 opacity-0 scale-90 -translate-x-2 transition-all duration-300 ease-out group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0 invisible group-hover:visible pointer-events-none z-[9999] backdrop-blur-sm">
                        <span class="relative z-10">Dashboard Admin</span>
                        <div class="absolute inset-0 rounded-xl bg-blue-500/10 blur-sm"></div>
                        <div class="absolute right-full top-1/2 -translate-y-1/2 border-[6px] border-transparent border-r-gray-900"></div>
                    </div>
                </li>
            <?php endif ?>

            <li class="group relative flex items-center">
                <button onclick="openLogoutModal()" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 hover:ring-1 hover:ring-blue-600 cursor-pointer">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/Logout.svg" class="size-6" alt="icon">
                </button>

                <div class="absolute left-full ml-3 whitespace-nowrap rounded-xl bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-2xl border border-gray-700/50 border-l-[3px] border-l-red-500 opacity-0 scale-90 -translate-x-2 transition-all duration-300 ease-out group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0 invisible group-hover:visible pointer-events-none z-[9999] backdrop-blur-sm">
                    <span class="relative z-10">Sign Out</span>
                    <div class="absolute inset-0 rounded-xl bg-red-500/10 blur-sm"></div>
                    <div class="absolute right-full top-1/2 -translate-y-1/2 border-[6px] border-transparent border-r-gray-900"></div>
                </div>
            </li>

            <li class="group relative flex items-center">
                <a href="<?php echo BASEURL ?>/profile" class="size-11 flex shrink-0 overflow-hidden rounded-full bg-white transition-all duration-300 hover:ring-1 hover:ring-blue-600 cursor-pointer">
                    <img src="<?= !empty($_SESSION['path_photo'])
                                    ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                    : BASEURL . '/src/asset/image/default.png' ?>" class="h-full w-full object-cover" alt="photo">
                </a>

                <div class="absolute left-full ml-3 whitespace-nowrap rounded-xl bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 px-4 py-2.5 text-sm font-semibold text-white shadow-2xl border border-gray-700/50 border-l-[3px] border-l-blue-500 opacity-0 scale-90 -translate-x-2 transition-all duration-300 ease-out group-hover:opacity-100 group-hover:scale-100 group-hover:translate-x-0 invisible group-hover:visible pointer-events-none z-[9999] backdrop-blur-sm">
                    <span class="relative z-10">Profile</span>
                    <div class="absolute inset-0 rounded-xl bg-blue-500/10 blur-sm"></div>
                    <div class="absolute right-full top-1/2 -translate-y-1/2 border-[6px] border-transparent border-r-gray-900"></div>
                </div>
            </li>
        </ul>
    </nav>

    <nav class="lg:hidden fixed bottom-0 left-0 right-0 grid 
    <?php echo $accsesPages ? 'grid-cols-5' : 'grid-cols-4'; ?> 
    h-[70px] items-center bg-white border-t border-gray-100 px-2 z-[9999]">

        <?php if ($accsesPages): ?>
            <a href="<?= BASEURL ?>/homepage" class="flex flex-col items-center justify-center gap-1 text-center">
                <img src="<?= BASEURL ?>/src/asset/icons/<?= $isHomepageActive ? 'chart-square-white-fill.svg' : 'chart-square-grey.svg' ?>"
                    class="h-7 w-7 <?= $isHomepageActive ? 'bg-blue-600 p-1 rounded-lg' : '' ?>" alt="icon">

                <span class="text-xs <?= $isHomepageActive ? 'text-blue-600 font-semibold' : 'text-gray-500' ?>">
                    HomePage
                </span>
            </a>
        <?php endif; ?>


        <?php if ($isGroupActive): ?>
            <button id="toggleForumsBtn" class="flex flex-col items-center justify-center gap-1 text-center">
                <img src="<?= BASEURL ?>/src/asset/icons/menuActive.png"
                    class="h-7 w-7 bg-blue-600 p-1 rounded-lg" alt="icon">
                <span class="text-xs text-blue-600 font-semibold">Menu</span>
            </button>
        <?php else: ?>
            <a href="<?= BASEURL ?>/groups" class="flex flex-col items-center justify-center gap-1 text-center">
                <img src="<?= BASEURL ?>/src/asset/icons/messages.svg" class="h-7 w-7" alt="icon">
                <span class="text-xs text-gray-500">Chat</span>
            </a>
        <?php endif; ?>


        <a href="<?= BASEURL ?>/forums"
            class="flex flex-col items-center justify-center gap-1 text-center">

            <div class="p-1 rounded-xl <?= $isForumsActive ? 'bg-blue-600' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 <?= $isForumsActive ? 'text-white' : 'text-gray-500' ?>"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>

            <span class="text-xs <?= $isForumsActive ? 'text-blue-600 font-semibold' : 'text-gray-500' ?>">
                Forums
            </span>
        </a>


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


        <div class="relative flex flex-col items-center justify-center gap-1 text-center">
            <button id="btn-menu" class="h-8 w-8 rounded-full overflow-hidden cursor-pointer group">
                <img src="<?= !empty($_SESSION['path_photo'])
                                ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                : BASEURL . '/src/asset/image/default.png' ?>"
                    class="h-full w-full object-cover" alt="photo">
            </button>

            <span class="text-xs text-gray-500">Profile</span>

            <div id="menu-profile-signout"
                class="absolute bottom-full right-0.5 mb-4 hidden">
                <ul class="bg-white shadow-lg rounded-lg py-2 w-32 text-sm text-gray-700 border border-gray-200">

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN'): ?>
                        <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            <a href="<?= BASEURL ?>/dashboard" class="flex gap-2 items-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z
                                       M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z
                                       M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z
                                       M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        <a href="<?= BASEURL ?>/profile" class="flex gap-2 items-center">
                            <img src="<?= BASEURL ?>/src/asset/icons/profile-2user-grey.svg" class="size-5" alt="icon">
                            Profile
                        </a>
                    </li>

                    <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer" onclick="openLogoutModal()">
                        <button class="flex gap-2 items-center w-full">
                            <img src="<?= BASEURL ?>/src/asset/icons/Logout.svg" class="size-5" alt="icon">
                            Sign Out
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>

<div id="logoutModal" class="fixed inset-0 z-[9999999] hidden items-center justify-center bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white rounded-2xl shadow-2xl w-[90%] max-w-sm p-6 transform scale-95 transition-transform duration-300" id="logoutModalContent">
        <div class="flex flex-col items-center text-center mb-5">
            <div class="bg-red-50 p-3 rounded-full mb-3">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Sign Out</h3>
            <p class="text-gray-500 text-sm mt-1">Are you sure you want to end your session?</p>
        </div>

        <div class="flex gap-3">
            <button onclick="closeLogoutModal()" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors">
                Cancel
            </button>
            <a href="<?= BASEURL ?>/logout" class="flex-1 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl text-center transition-colors shadow-lg shadow-red-500/30">
                Yes, Sign Out
            </a>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('logoutModal');
    const modalContent = document.getElementById('logoutModalContent');

    function openLogoutModal() {
        modal.classList.remove('hidden');
        // Sedikit delay untuk animasi fade-in
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('flex'); // Pastikan flex aktif
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }, 10);
    }

    function closeLogoutModal() {
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');

        // Tunggu animasi selesai baru hidden
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // Close modal jika klik di luar area putih (backdrop)
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeLogoutModal();
        }
    });
</script>

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