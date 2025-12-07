<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Homepage | Sinergi</title>
</head>

<body class="h-screen overflow-hidden">
    <main class="w-full h-screen overflow-y-auto overflow-x-hidden border-gray-200 hide-scrollbar relative" id="main-container">

        <div class="sticky top-0 z-[999] bg-white/95 backdrop-blur-md w-full shadow-sm border-b border-gray-200/80">
            <div class="w-full px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex items-center justify-between gap-3 sm:gap-4">

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="<?= BASEURL ?>/homepage" class="flex gap-2 items-center">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="flex w-11 h-9 shrink-0 lg:hidden" alt="logo">
                            <h1 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                                SINERGI
                            </h1>
                        </a>
                    </div>

                    <form id="searchFormDesktop" class="hidden lg:block flex-1 max-w-2xl mx-4" onsubmit="return handleSearch(event)">
                        <div class="relative group">
                            <input type="search" id="desktop-search-input"
                                class="block w-full p-4 pr-14 text-sm text-gray-900 rounded-full bg-white shadow-md border border-gray-100 placeholder:text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 outline-none"
                                placeholder="Search for something..." autocomplete="off" minlength="3" required />

                            <button type="submit"
                                class="text-white absolute right-1.5 top-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-full p-2.5 w-10 h-10 flex items-center justify-center shadow-sm hover:shadow-md transition-all duration-200 active:scale-95 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    <div class="flex items-center gap-2">

                        <button type="button" onclick="openMobileSearch()"
                            class="lg:hidden flex items-center justify-center p-2.5 text-gray-600 hover:bg-gray-100 rounded-full transition-colors cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>

                        <div class="group relative flex justify-center">
                            <button id="quote-btn-opn" aria-label="Show Quote of the Day" class="flex items-center justify-center p-2.5 rounded-full cursor-pointer hover:bg-gray-50 transition-colors">
                                <img src="<?php echo BASEURL; ?>/src/asset/icons/quote.svg" class="h-8 w-8" alt="Quotes of the Day icon">
                            </button>
                            <div role="tooltip" class="absolute top-full mt-2 left-1/2 -translate-x-1/2 whitespace-nowrap bg-gray-800 text-white text-sm font-medium px-3 py-1.5 rounded-lg shadow-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300 z-50">
                                Daily quotes
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-xl mx-auto px-5 md:px-0 py-4 mb-20 md:mb-0">
            <?php require_once 'app/views/components/postingan/createPost.php' ?>
            <?php require_once 'app/views/components/postingan/post.php' ?>
        </div>

        <?php require_once 'app/views/components/quotes.php' ?>

        <div class="fixed right-5 top-24 z-[9999] space-y-3">
            <div id="successDiv" class="hidden bg-green-500 text-white text-center drop-shadow-lg rounded-lg px-4 py-3 min-w-[200px] animate-slide-in-right"></div>
            <div id="errorDiv" class="hidden bg-red-500 text-white text-center drop-shadow-lg rounded-lg px-4 py-3 min-w-[200px] animate-slide-in-right"></div>
        </div>

    </main>

    <div id="mobileSearchModal" class="fixed inset-0 z-[100000] bg-white hidden flex-col transition-all duration-300">
        <div class="flex items-center justify-between p-4 border-b border-gray-100">
            <span class="text-lg font-bold text-gray-800">Search</span>
            <button type="button" onclick="closeMobileSearch()" class="p-2 text-gray-500 hover:bg-gray-100 rounded-full cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-4">
            <form id="searchFormMobile" onsubmit="return handleSearch(event)">
                <div class="relative">
                    <input type="search" id="mobile-search-input"
                        class="block w-full p-4 pl-12 text-sm text-gray-900 rounded-xl bg-gray-50 border-none focus:ring-2 focus:ring-blue-500 outline-none"
                        placeholder="Search for something..." autocomplete="off" minlength="3" required />

                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-700 cursor-pointer">
                        Go
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

<script>
    const BASEURL = '<?= BASEURL ?>';

    function openMobileSearch() {
        const modal = document.getElementById('mobileSearchModal');
        const input = document.getElementById('mobile-search-input');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            input.focus();
        }, 100);

        document.body.style.overflow = 'hidden';
    }

    function closeMobileSearch() {
        const modal = document.getElementById('mobileSearchModal');

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.style.overflow = 'hidden';
    }

    function handleSearch(e) {
        e.preventDefault();

        const desktopInput = document.getElementById('desktop-search-input');
        const mobileInput = document.getElementById('mobile-search-input');

        let keyword = '';

        if (mobileInput && mobileInput.value.trim() !== '') {
            keyword = mobileInput.value.trim();
        } else if (desktopInput && desktopInput.value.trim() !== '') {
            keyword = desktopInput.value.trim();
        }

        if (!keyword) return;

        window.location.href = `${BASEURL}/homepage/search?keyword=${encodeURIComponent(keyword)}`;
    }
</script>

</html>