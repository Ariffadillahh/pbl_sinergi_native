<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Dashboard Admin - SINERGI</title>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">

        <?php require_once 'app/views/components/sidebarAdmin.php'; ?>

        <div id="overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-30 transition-opacity duration-300 md:hidden"></div>

        <div class="flex-1 flex flex-col overflow-hidden">

            <header class="sticky top-0 z-20 bg-white border-b border-gray-200">
                <div class="h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between">

                    <button id="toggleSidebar" class="p-2 -ml-2 text-gray-600 hover:text-gray-900 md:hidden">
                        <span id="iconOpen">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </span>
                        <span id="iconClose" class="hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </span>
                    </button>

                    <h2 class="text-xl font-semibold text-gray-800 hidden md:block">
                        <?php echo htmlspecialchars($pageTitle); ?>
                    </h2>

                    <div class="flex items-center gap-4">

                        <div class="relative">

                            <button id="notif-toggle-btn" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span id="notif-badge" class="absolute hidden top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            </button>

                            <?php require_once 'app/views/components/admin/notifAdmin.php'; ?>

                        </div>


                        <div class="w-px h-8 bg-gray-200 hidden md:block"></div>

                        <button class="flex items-center gap-3">
                            <img
                                src="<?= !empty($_SESSION['path_photo'])
                                            ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                            : 'https://ui-avatars.com/api/?name=' . urlencode(substr($_SESSION['username'], 0, 1)) . '&background=random&size=100' ?>"
                                alt="Admin profile photo"
                                class="w-9 h-9 rounded-full object-cover">
                            <div class="hidden md:block">
                                <p class="text-sm font-medium text-gray-800 text-left capitalize"><?= $_SESSION['username'] ?></p>
                                <p class="text-xs text-gray-500 text-left"><?= $_SESSION['email'] ?></p>
                            </div>
                        </button>

                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="px-4 sm:px-6 lg:px-8 py-6">
                    <div class="max-w-7xl mx-auto">
                        <?php require_once $contentViewDashboard; ?>
                    </div>
                </div>
            </main>

            <footer class="bg-white border-t border-gray-200 px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex justify-between items-center gap-2">
                    <p class="text-sm text-gray-600">© 2025 SINERGI. All rights reserved.</p>
                    <p class="text-sm text-gray-500 hidden md:block">Develope by Kelompok 1</p>
                </div>
            </footer>

        </div>
    </div>

    <script>
        const sidebar = document.getElementById("sidebar");
        const toggleSidebar = document.getElementById("toggleSidebar");
        const iconOpen = document.getElementById("iconOpen");
        const iconClose = document.getElementById("iconClose");
        const overlay = document.getElementById("overlay");

        function toggleSidebarMenu() {
            const isOpen = sidebar.classList.contains("translate-x-0");

            if (isOpen) {
                sidebar.classList.remove("translate-x-0");
                sidebar.classList.add("-translate-x-full");
                overlay.classList.add("hidden");
                iconOpen.classList.remove("hidden");
                iconClose.classList.add("hidden");
            } else {
                sidebar.classList.remove("-translate-x-full");
                sidebar.classList.add("translate-x-0");
                overlay.classList.remove("hidden");
                iconOpen.classList.add("hidden");
                iconClose.classList.remove("hidden");
            }
        }

        toggleSidebar.addEventListener("click", toggleSidebarMenu);
        overlay.addEventListener("click", toggleSidebarMenu);

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && !sidebar.classList.contains("-translate-x-full")) {
                toggleSidebarMenu();
            }
        });
    </script>
</body>

</html>