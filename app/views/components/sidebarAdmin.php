<?php
$current_route = $_GET['route'] ?? 'dashboard';

if (strlen($current_route) > 1) {
    $current_route = rtrim($current_route, '/');
}

$isDashboardActive = ($current_route === 'dashboard');

// Members parent dan submenu
$isMembersParentActive = (strpos($current_route, 'dashboard/anggota') === 0 || strpos($current_route, 'dashboard/anggota/requested-accounts') === 0);
$isAnggotaActive = ($current_route === 'dashboard/anggota/allusers');
$isRequestedActive = (strpos($current_route, 'dashboard/anggota/requested-accounts') === 0);

$isForumsActive = (strpos($current_route, 'dashboard/forums') === 0);
$isLaporanParentActive = (strpos($current_route, 'dashboard/laporan') === 0);
$isLaporanActiveForum = ($current_route === 'dashboard/laporan/forum');
$isLaporanActiveGroup = ($current_route === 'dashboard/laporan/group');
$isLaporanActivePostingan = ($current_route === 'dashboard/laporan/postingan');

$pageTitle = "Dashboard Overview";
if ($isAnggotaActive) $pageTitle = "Manajemen Anggota";
if ($isRequestedActive) $pageTitle = "Requested Accounts";
if ($isForumsActive) $pageTitle = "Manajemen Forums";
if ($isLaporanActiveForum) $pageTitle = "Laporan Forums";
if ($isLaporanActivePostingan) $pageTitle = "Laporan Postingan";

?>

<aside id="sidebar" class="fixed md:sticky top-0 left-0 h-screen w-64 bg-white border-r border-gray-200 flex flex-col z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">

    <div class="h-16 flex items-center gap-3 px-6 border-b border-gray-200">
        <div class="w-8 h-8 flex items-center justify-center font-bold text-xl">
            <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="w-8 h-8 shrink-0" alt="logo">
        </div>
        <div>
            <h1 class="text-sm font-bold text-gray-800">FORUM SINERGI</h1>
            <p class="text-xs text-gray-500">Admin Panel</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto p-4">
        <ul class="space-y-1.5">
            <?php
            function getLinkClass($isActive)
            {
                return $isActive
                    ? 'bg-blue-50 text-blue-600 font-semibold'
                    : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900';
            }
            ?>

            <li>
                <a href="<?php echo BASEURL; ?>/dashboard"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200 <?php echo getLinkClass($isDashboardActive); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Members Parent Menu dengan Submenu -->
            <li class="relative">
                <button
                    type="button"
                    class="flex w-full items-center justify-between px-4 py-2.5 rounded-lg transition-all duration-200 group <?php echo getLinkClass($isMembersParentActive); ?>"
                    onclick="toggleSubmenu(this)">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 <?php echo $isMembersParentActive ? 'text-blue-600' : 'text-gray-500 group-hover:text-gray-800' ?> transition-colors duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">Members</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 <?php echo $isMembersParentActive ? 'rotate-90' : ''; ?>"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <div class="submenu ml-8 mt-1 overflow-hidden transition-all duration-300 <?php echo $isMembersParentActive ? 'max-h-40' : 'max-h-0'; ?>">
                    <a href="<?php echo BASEURL; ?>/dashboard/anggota/allusers"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200 hover:bg-gray-100 <?php echo getLinkClass($isAnggotaActive); ?>">
                        <span>All Members</span>
                    </a>

                    <a href="<?php echo BASEURL; ?>/dashboard/anggota/requested-accounts"
                        class="flex items-center justify-between px-4 py-2.5 rounded-lg transition-colors duration-200 hover:bg-gray-100 <?php echo getLinkClass($isRequestedActive); ?>">
                        <span>Requested Accounts</span>
                        <!-- Badge Notifikasi -->
                        <span id="requested-account-badge" class="hidden inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-red-600 rounded-full">
                            0
                        </span>
                    </a>
                </div>
            </li>

            <li>
                <a href="<?php echo BASEURL; ?>/dashboard/forums"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200 <?php echo getLinkClass($isForumsActive); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>Forums</span>
                </a>
            </li>

            <li class="relative">
                <button
                    type="button"
                    class="flex w-full items-center justify-between px-4 py-2.5 rounded-lg transition-all duration-200 group <?php echo getLinkClass($isLaporanParentActive); ?>"
                    onclick="toggleSubmenu(this)">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 <?php echo $isLaporanParentActive ? 'text-blue-600' : 'text-gray-500 group-hover:text-gray-800' ?> transition-colors duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9">
                            </path>
                        </svg>
                        <span class="font-medium">Reports</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 <?php echo $isLaporanParentActive ? 'rotate-90' : ''; ?>"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <div class="submenu ml-8 mt-1 overflow-hidden transition-all duration-300 <?php echo $isLaporanParentActive ? 'max-h-40' : 'max-h-0'; ?>">
                    <a href="<?php echo BASEURL; ?>/dashboard/laporan/forum"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200 hover:bg-gray-100 <?php echo getLinkClass($isLaporanActiveForum); ?>">
                        <span>Forum Reports</span>
                    </a>

                    <a href="<?php echo BASEURL; ?>/dashboard/laporan/postingan"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200 hover:bg-gray-100 <?php echo getLinkClass($isLaporanActivePostingan); ?>">
                        <span>Post Reports</span>
                    </a>

                    <a href="<?php echo BASEURL; ?>/dashboard/laporan/group"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200 hover:bg-gray-100 <?php echo getLinkClass($isLaporanActiveGroup); ?>">
                        <span>Group Reports</span>
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <div class="p-4 border-t border-gray-200">
        <ul class="space-y-1.5">
            <li>
                <a href="<?php echo BASEURL; ?>/homepage" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-gray-600 hover:bg-gray-200/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="<?php echo BASEURL; ?>/logout"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200 text-gray-600 hover:bg-gray-100 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<script>
    function toggleSubmenu(button) {
        const submenu = button.nextElementSibling;
        const icon = button.querySelector('svg:last-child');

        submenu.classList.toggle('max-h-0');
        submenu.classList.toggle('max-h-40');
        icon.classList.toggle('rotate-90');
    }

    // Fetch jumlah pending requests
    async function fetchPendingRequestsCount() {
        try {
            const response = await fetch('<?= BASEURL ?>/dashboard/anggota/get-pending-requests-count');
            const result = await response.json();
            
            if (result.success && result.count > 0) {
                const badge = document.getElementById('requested-account-badge');
                badge.textContent = result.count;
                badge.classList.remove('hidden');
            } else {
                const badge = document.getElementById('requested-account-badge');
                badge.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error fetching pending requests:', error);
        }
    }

    // Check setiap 30 detik
    fetchPendingRequestsCount();
    setInterval(fetchPendingRequestsCount, 30000);
</script>