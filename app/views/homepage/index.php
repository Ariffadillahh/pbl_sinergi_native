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

                    <form class="flex-1 max-w-2xl">
                        <div class="relative group">
                            <input type="search"
                                id="default-search"
                                class="block w-full p-4 pr-14 text-sm text-gray-900 rounded-full bg-white shadow-md 
                                      border border-gray-200
                                      placeholder:text-gray-400
                                      focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                      hover:border-gray-300
                                      transition-all duration-200"
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


                    <?php require_once 'app/views/components/notifikasi.php' ?>

                </div>
            </div>
        </div>

        <div class="max-w-xl mx-auto px-5 md:px-0 py-4 mb-20 md:mb-0">
            <?php require_once 'app/views/components/postingan/createPost.php' ?>
            <?php require_once 'app/views/components/postingan/post.php' ?>
        </div>
    </main>
</body>

</html>