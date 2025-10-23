<?php
$current_uri = $_SERVER['REQUEST_URI'];

$isHomepageActive = (strpos($current_uri, '/homepage') !== false);
$isForumsActive   = (strpos($current_uri, '/forums') !== false);
$isSettingsActive = (strpos($current_uri, '/settings') !== false);
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
            <li class="group relative flex items-center <?php echo $isSettingsActive ? 'active' : ''; ?>">
                <a href="<?php echo BASEURL ?>/settings" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/setting-2.svg" class="size-6 group-[.active]:hidden" alt="icon">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/setting-2-white-fill.svg" class="size-6 hidden group-[.active]:flex" alt="icon">
                </a>
                <h1 class="absolute left-full ml-4 whitespace-nowrap rounded bg-gray-900 px-3 py-1.5 text-sm font-medium text-white border border-gray-200 border-l-4 border-l-blue-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100 invisible group-hover:visible pointer-events-none z-[9999]">
                    Settings
                </h1>
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

    <nav class="lg:hidden fixed bottom-0 left-0 right-0 grid <?php echo $accsesPages ? 'grid-cols-5' : 'grid-cols-4'; ?> h-[70px] items-center bg-white border-t border-gray-100 px-2 z-30">

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

        <a href="<?php echo BASEURL ?>/settings" class="flex flex-col items-center justify-center gap-1 text-center">
            <img src="<?php echo BASEURL; ?>/src/asset/icons/<?php echo $isSettingsActive ? 'settingActive.png' : 'setting-2.svg'; ?>"
                class="h-7 w-7 <?php echo $isSettingsActive ? 'bg-blue-600 p-1 rounded-lg' : ''; ?>" alt="icon">
            <span class="text-xs <?php echo $isSettingsActive ? 'text-blue-600 font-semibold' : 'text-semibold'; ?>">
                Settings
            </span>
        </a>

        <a href="<?php echo BASEURL ?>/logout" class="flex flex-col items-center justify-center gap-1 text-center">
            <img src="<?php echo BASEURL; ?>/src/asset/icons/Logout.svg" class="h-7 w-7" alt="icon">
            <span class="text-xs text-gray-500">Logout</span>
        </a>

        <a href="<?php echo BASEURL ?>/profile" class="flex flex-col items-center justify-center gap-1 text-center">
            <div class="h-8 w-8 rounded-full overflow-hidden">
                <img src="<?= !empty($_SESSION['path_photo'])
                                ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                : BASEURL . '/src/asset/image/default.png' ?>" class="h-full w-full object-cover" alt="photo">
            </div>
            <span class="text-xs text-gray-500">Profile</span>
        </a>
    </nav>
</div>