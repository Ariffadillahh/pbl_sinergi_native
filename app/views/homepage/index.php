<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Home Page | Sinergi</title>
</head>

<body class="bg-gray-100 h-screen flex">

    <?php require_once 'app/views/components/sidebars.php'; ?>

    <div class="w-full flex flex-1 justify-center overflow-hidden">
        <main class="w-full h-screen overflow-y-auto border-gray-200 hide-scrollbar relative">
            <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md w-full px-5 py-3 mb-4 border-b border-gray-200">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input
                        type="search"
                        name="search"
                        id="search"
                        placeholder="Search..."
                        class="block w-full bg-white border border-gray-300 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="max-w-xl mx-auto px-5 md:p-0 mb-20 md:mb-0">
                <?php require_once 'app/views/components/postingan/createPost.php' ?>
                <?php require_once 'app/views/components/postingan/post.php' ?>
            </div>
        </main>

        <aside class="hidden lg:block max-w-[360px] p-4 flex-shrink-0 bg-white border-l border-gray-200 ">
            <?php require_once 'app/views/components/postingan/forYouPage.php' ?>
        </aside>
    </div>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>
</body>

</html>