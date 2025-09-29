<?php
// Ambil URI saat ini untuk menentukan halaman aktif
$current_uri = $_SERVER['REQUEST_URI'];

$isHomepageActive = (strpos($current_uri, '/homepage') !== false);
$isForumsActive   = (strpos($current_uri, '/forums') !== false);
$isSettingsActive = (strpos($current_uri, '/settings') !== false);
?>

<div>
    <nav class="hidden lg:flex h-screen flex-col items-center justify-between w-[84px] shrink-0 bg-gray-200/70 px-5 py-[30px] z-30">
        <ul class="flex flex-col gap-5">
            <li>
                <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="flex w-11 h-9 shrink-0" alt="logo">
            </li>
            <li class="group <?php echo $isHomepageActive ? 'active' : ''; ?>">
                <a href="<?php echo BASEURL ?>/homepage" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/chart-square-grey.svg" class="size-6 group-[.active]:hidden" alt="icon" />
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/chart-square-white-fill.svg" class="size-6 hidden group-[.active]:flex" alt="icon" />
                </a>
            </li>
            <li class="group <?php echo $isForumsActive ? 'active' : ''; ?>">
                <a href="<?php echo BASEURL ?>/forums" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/messages.svg" class="size-6 group-[.active]:hidden" alt="icon">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/messages-white-fill.svg" class="size-6 hidden group-[.active]:flex" alt="icon">
                </a>
            </li>
            <li class="group <?php echo $isSettingsActive ? 'active' : ''; ?>">
                <a href="<?php echo BASEURL ?>/settings" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 group-[.active]:bg-blue-600 hover:ring-1 hover:ring-blue-600">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/setting-2.svg" class="size-6 group-[.active]:hidden" alt="icon">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/setting-2-white-fill.svg" class="size-6 hidden group-[.active]:flex" alt="icon">
                </a>
            </li>
        </ul>
        <ul class="flex flex-col gap-5">
            <li>
                <a href="#" class="size-11 flex shrink-0 items-center justify-center rounded-xl bg-white p-[10px] transition-all duration-300 hover:ring-1 hover:ring-blue-600">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/Logout.svg" class="size-6" alt="icon">
                </a>
            </li>
            <li>
                <a href="#" class="size-11 flex shrink-0 overflow-hidden rounded-full bg-white transition-all duration-300 hover:ring-1 hover:ring-blue-600">
                    <img src="<?php echo BASEURL; ?>/src/asset/image/default.png" class="h-full w-full object-cover" alt="photo">
                </a>
            </li>
        </ul>
    </nav>

    <nav class="lg:hidden fixed bottom-0 left-0 right-0 flex w-full h-[70px] items-center justify-around bg-white border-t border-gray-100 px-5 z-30">
        <a href="<?php echo BASEURL ?>/homepage" class="relative <?php echo $isHomepageActive ? 'active bg-blue-600 p-1 rounded-xl' : ''; ?> ">
            <img src="<?php echo BASEURL; ?>/src/asset/icons/<?php echo $isHomepageActive ? 'chart-square-white-fill.svg' : 'chart-square-grey.svg'; ?>" class="h-7 w-7" alt="icon">
        </a>

        <?php if ($isForumsActive): ?>
            <button id="toggleForumsBtn" class="relative active">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/menu.svg" class="h-7 w-7" alt="icon">
            </button>
        <?php else: ?>
            <a href="<?php echo BASEURL; ?>/forums">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/messages.svg" class="h-7 w-7" alt="icon">
            </a>
        <?php endif; ?>

        <a href="<?php echo BASEURL ?>/settings" class="relative <?php echo $isSettingsActive ? 'active bg-blue-600 p-1 rounded-xl' : ''; ?>">
            <img src="<?php echo BASEURL; ?>/src/asset/icons/<?php echo $isSettingsActive ? 'setting-2-white-fill.svg' : 'setting-2.svg'; ?>" class="h-7 w-7" alt="icon">
        </a>

        <a href="#">
            <img src="<?php echo BASEURL; ?>/src/asset/icons/Logout.svg" class="size-6" alt="icon">
        </a>
    </nav>
</div>