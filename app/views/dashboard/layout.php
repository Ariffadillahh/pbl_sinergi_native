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

            <header class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-20">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex justify-between items-center">

                        <div class="flex items-center gap-4">
                            <button id="toggleSidebar" class="md:hidden p-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg id="iconOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg id="iconClose" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <div>
                                <h1 class="md:text-2xl text-base font-bold text-gray-900">Dashboard Admin</h1>
                                <p class="md:text-sm text-xs text-gray-500 mt-0.5">POLITEKNIK NEGRII JAKARTA</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-3 px-3 py-2 rounded-lg group">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-semibold text-gray-900 capitalize"><?= $_SESSION['full_name'] ?? 'Admin' ?></p>
                                    <p class="text-xs text-gray-500"><?= $_SESSION['role'] ?? 'Administrator' ?></p>
                                </div>
                                <div class="w-10 h-10 rounded-full overflow-hidden ring-2 ring-gray-200 group-hover:ring-blue-500 transition-all duration-200">
                                    <img src="<?= !empty($_SESSION['path_photo'])
                                                    ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                                    : BASEURL . '/src/asset/image/default.png' ?>"
                                        class="h-full w-full object-cover"
                                        alt="Profile Photo">
                                </div>
                            </div>
                        </div>
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