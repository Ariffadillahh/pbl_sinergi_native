<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Home Page | Sinergi</title>
</head>

<body>
    <main class="w-full h-screen overflow-y-auto border-gray-200 hide-scrollbar relative">
        <div class="sticky top-0 z-50 bg-white/95 backdrop-blur-md w-full shadow-sm border-b border-gray-200/80">
            <div class="w-full px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex items-center justify-between gap-3 sm:gap-4">

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <h1 class="hidden sm:block text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                            SINERGI
                        </h1>
                        <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="flex w-11 h-9 shrink-0 md:hidden" alt="logo">
                    </div>

                    <form id="searchForm" class="flex-1 max-w-2xl" onsubmit="return handleSearch(event)">
                        <div class="relative group">
                            <input
                                type="search"
                                id="default-search"
                                class="block w-full p-4 pr-14 text-sm text-gray-900 rounded-full bg-white shadow-md
                                        placeholder:text-gray-700
                                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                        transition-all duration-200 outline-none"
                                placeholder="Search somethings..."
                                autocomplete="off"
                                required />


                            <button type="submit"
                                class="text-white absolute right-1.5 top-1/2 -translate-y-1/2 
                                    bg-gradient-to-r from-blue-500 to-blue-600 
                                    hover:from-blue-600 hover:to-blue-700
                                    focus:ring-4 focus:outline-none focus:ring-blue-300 
                                    rounded-full p-2.5 w-10 h-10 
                                    flex items-center justify-center
                                    shadow-sm hover:shadow-md
                                    transition-all duration-200
                                    active:scale-95">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    <div class="group relative flex justify-center">
                        <button id="quote-btn-opn" aria-label="Show Quote of the Day" class="flex items-center justify-center p-2.5 rounded-full cursor-pointer">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/quote.svg" class="h-8 w-8" alt="Quotes of the Day icon">
                        </button>

                        <div role="tooltip"
                            class="absolute top-full mt-2 left-0 -translate-x-1/2 
                                    whitespace-nowrap bg-gray-800 text-white text-sm font-medium 
                                    px-3 py-1.5 rounded-lg shadow-sm 
                                    opacity-0 invisible group-hover:opacity-100 group-hover:visible 
                                    transition-opacity duration-300">
                            Quotes of the Day
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


    </main>
</body>
<script>
    const BASEURL = '<?= BASEURL ?>';

    function handleSearch(e) {
        e.preventDefault();
        const input = document.getElementById('default-search');
        const keyword = input.value.trim();
        if (!keyword) return;

        // Arahkan ke halaman pencarian
        window.location.href = `${BASEURL}/homepage/search?keyword=${encodeURIComponent(keyword)}`;
    }
</script>

</html>