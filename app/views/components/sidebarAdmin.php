<?php
$current_uri = $_SERVER['REQUEST_URI'];

$isHomeActive = (strpos($current_uri, '/dashboard/home') !== false || strpos($current_uri, '/dashboard') !== false);
$isBukuActive = (strpos($current_uri, '/dashboard/buku') !== false);
$isAnggotaActive = (strpos($current_uri, '/dashboard/anggota') !== false);
$isPeminjamanActive = (strpos($current_uri, '/dashboard/peminjaman') !== false);
?>

<aside id="sidebar" class="fixed md:sticky top-0 left-0 h-screen w-64 bg-gradient-to-b from-blue-600 via-blue-700 to-blue-800 shadow-2xl transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40 flex flex-col">

    <div class="px-6 py-8 border-b border-blue-500/30">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-lg">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="w-8 h-8 shrink-0" alt="logo">

            </div>
            <h2 class="text-2xl font-bold text-white tracking-wide">SINERGI</h2>
        </div>
    </div>

    <nav class="flex-1 px-4 py-6 overflow-y-auto">
        <ul class="space-y-2">
            <li>
                <a href="<?php echo BASEURL ?>/admin/home"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?php echo $isHomeActive ? 'bg-white text-blue-600 shadow-lg' : 'text-white hover:bg-blue-500/30'; ?>">
                    <svg class="w-5 h-5 <?php echo $isHomeActive ? 'text-blue-600' : 'text-blue-200 group-hover:text-white'; ?>"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="font-medium">Home</span>
                    <?php if ($isHomeActive): ?>
                        <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
                    <?php endif; ?>
                </a>
            </li>

            <li>
                <a href="<?php echo BASEURL ?>/admin/buku"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?php echo $isBukuActive ? 'bg-white text-blue-600 shadow-lg' : 'text-white hover:bg-blue-500/30'; ?>">
                    <svg class="w-5 h-5 <?php echo $isBukuActive ? 'text-blue-600' : 'text-blue-200 group-hover:text-white'; ?>"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="font-medium">Buku</span>
                    <?php if ($isBukuActive): ?>
                        <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
                    <?php endif; ?>
                </a>
            </li>

            <li>
                <a href="<?php echo BASEURL ?>/admin/anggota"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?php echo $isAnggotaActive ? 'bg-white text-blue-600 shadow-lg' : 'text-white hover:bg-blue-500/30'; ?>">
                    <svg class="w-5 h-5 <?php echo $isAnggotaActive ? 'text-blue-600' : 'text-blue-200 group-hover:text-white'; ?>"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">Anggota</span>
                    <?php if ($isAnggotaActive): ?>
                        <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
                    <?php endif; ?>
                </a>
            </li>

            <li>
                <a href="<?php echo BASEURL ?>/admin/peminjaman"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group <?php echo $isPeminjamanActive ? 'bg-white text-blue-600 shadow-lg' : 'text-white hover:bg-blue-500/30'; ?>">
                    <svg class="w-5 h-5 <?php echo $isPeminjamanActive ? 'text-blue-600' : 'text-blue-200 group-hover:text-white'; ?>"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-medium">Peminjaman Buku</span>
                    <?php if ($isPeminjamanActive): ?>
                        <div class="ml-auto w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
                    <?php endif; ?>
                </a>
            </li>
        </ul>
    </nav>

    <div class="px-4 py-6 border-t border-blue-500/50">
        <a href="<?php echo BASEURL ?>/homepage"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:bg-blue-500/30 transition-all duration-300 group">
            <svg class="w-5 h-5 text-white group-hover:text-white"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="font-medium">Home Page</span>
        </a>
        <a href="<?php echo BASEURL ?>/logout"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-white hover:bg-blue-500/30 transition-all duration-300 group">
            <svg class="w-5 h-5 text-white group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="font-medium">Sign Out</span>
        </a>
    </div>
</aside>