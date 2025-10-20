<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
</head>

<body>
    <button id="toggleSidebar"
        class="fixed right-3 top-3 z-50 bg-gray-800 text-white p-2 rounded-md md:hidden focus:outline-none">
        <svg id="iconOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg id="iconClose" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div class="flex flex-col md:flex-row h-screen">
        <?php require_once 'app/views/components/sidebarAdmin.php'; ?>

        <div id="overlay"
            class="fixed inset-0 bg-black opacity-50 hidden z-30 transition-opacity duration-300 ease-in-out md:hidden"></div>

        <main class="flex-1 md:ml-0 p-6 md:p-10 overflow-y-auto">
            <?php require_once $contentViewDashboard; ?>
        </main>
    </div>


    <script>
        const sidebar = document.getElementById("sidebar");
        const toggleSidebar = document.getElementById("toggleSidebar");
        const iconOpen = document.getElementById("iconOpen");
        const iconClose = document.getElementById("iconClose");
        const overlay = document.getElementById("overlay");

        toggleSidebar.addEventListener("click", () => {
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
        });

        overlay.addEventListener("click", () => {
            sidebar.classList.remove("translate-x-0");
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
            iconOpen.classList.remove("hidden");
            iconClose.classList.add("hidden");
        });
    </script>
</body>

</html>