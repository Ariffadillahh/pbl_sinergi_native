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
            <div class="sticky top-0 z-10 bg-white w-full px-5 py-3 mb-4 border-b border-gray-200">

                <form class="w-full">
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only ">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="default-search" class="block w-full p-3 ps-12 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500 " placeholder="Search postingan..." required />
                        <button type="submit" class="text-white absolute end-2 bottom-1.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-4 py-2 ">Search</button>
                    </div>
                </form>

            </div>
            <div class="max-w-xl mx-auto px-5 md:p-0 mb-20 md:mb-0">
                <?php require_once 'app/views/components/postingan/createPost.php' ?>
                <?php require_once 'app/views/components/postingan/post.php' ?>
            </div>
        </main>

        <aside class="hidden lg:block w-[360px] p-4 flex-shrink-0 bg-white border-l border-gray-200">
            <?php require_once 'app/views/components/postingan/forYouPage.php' ?>
        </aside>
    </div>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>
</body>

</html>