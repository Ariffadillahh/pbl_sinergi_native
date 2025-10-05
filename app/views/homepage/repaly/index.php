<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Home Page | <?php echo $id ?></title>
</head>

<body class="bg-gray-100 h-screen flex">

    <?php require_once 'app/views/components/sidebars.php'; ?>

    <div class="w-full flex flex-1 justify-center overflow-hidden">
        <main class="w-full h-screen overflow-y-auto border-gray-200 hide-scrollbar relative">
            <div class="sticky top-0 z-10 bg-white w-full px-5 py-3 mb-4 border-b border-gray-200">
                <button onclick="window.history.back()" class="flex items-center gap-3 text-black font-semibold cursor-pointer">
                    <img src="<?php echo BASEURL . '/src/asset/icons/left-arrow-svgrepo-com.svg'; ?>" alt="icon" class="w-6 h-6">
                    <h1 class="text-xl">Post</h1>
                </button>
            </div>
            <div class="max-w-xl mx-auto px-5 md:p-0 mb-20 md:mb-10">
                <div class="max-w-xl w-full mx-auto">
                    <?php require_once 'app/views/components/postingan/replayPost.php'; ?>
                    <div>
                        <form method="POST" action="" class="bg-white text-black border-t border-gray-200 p-4 rounded-2xl my-2">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" alt="Your Profile" class="w-12 h-12 rounded-full">
                                </div>

                                <textarea
                                    class="w-full bg-transparent text-lg text-gray-800 placeholder-gray-500 border-none focus:ring-0 focus:outline-none resize-none p-1"
                                    rows="2"
                                    placeholder="Add Comment...."></textarea>

                                <div class="mt-2 flex items-center justify-end">
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors">
                                        Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="bg-white text-black border-t border-gray-200 p-4 rounded-2xl">
                        <div class="flex items-start space-x-3 border-b border-gray-200 pb-5">
                            <div class="flex-shrink-0">
                                <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" alt="Profile" class="w-12 h-12 rounded-full">
                            </div>

                            <div class="flex-1 min-w-0">
                                <h1 class="text-black min-w-0 truncate font-semibold">Arif fadillah Wicaksono</h1>
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-500">@feraacorp</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-gray-800 text-base">
                            <p>Kata siapa ga angker?</p>
                            <p class="mt-2">Orang yg mau manjat everest uda siap kehilangan nyawa saking ga berbahayanya sama kondisi normal manusia.</p>
                            <p class="mt-2">Kedua, orang yg ke sana, rata2 berpendidikan, wajib punya pengetahuan ttg hipotermia, ciri2 dan dampaknya, bukan orang yg di pohon itu ada mbah anu</p>
                        </div>

                        <div class="mt-4 flex items-center text-gray-500 text-sm max-w-sm gap-4">
                            <p class="text-gray-400">
                                21h
                            </p>
                            <button id="reply" class="text-gray-600 hover:text-blue-600 transition duration-300 font-semibold">
                                Reply
                            </button>
                        </div>
                        <div class="hidden" id="reply_form">
                            <form method="POST" action="" class="bg-white text-black border-t border-gray-200 p-4 rounded-2xl my-2">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" alt="Your Profile" class="w-10 h-10 rounded-full">
                                    </div>
                                    <input type="text" class="w-full bg-transparent text-gray-800 ring-1 placeholder-gray-500 border-none focus:ring-1 focus:outline-blue-600 rounded-full p-1.5 ps-3" placeholder="Reply">
                                    <div class="flex items-center justify-end">
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors">
                                            Reply
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Reply comments -->
                        <div class="border-t border-gray-200 mt-4 pt-4">

                            <div class="replies-section space-y-4">

                                <div class="comment-container">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" alt="Profile" class="w-9 h-9 rounded-full">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h1 class="text-black min-w-0 truncate font-semibold">Arif fadillah Wicaksono</h1>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-gray-500">@feraacorp</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 ml-1 pl-12 text-gray-800 text-base">
                                        <p>Ini adalah contoh balasan yang sudah dirapikan.</p>
                                    </div>
                                    <div class="mt-3 ml-1 pl-12 flex items-center text-gray-500 text-sm gap-4">
                                        <p class="text-gray-400">20h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- comment ke 2 -->
                    <div class="bg-white text-black border-t border-gray-200 p-4 rounded-2xl mt-4">
                        <div class="flex items-start space-x-3 border-b border-gray-200 pb-5">
                            <div class="flex-shrink-0">
                                <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" alt="Profile" class="w-12 h-12 rounded-full">
                            </div>

                            <div class="flex-1 min-w-0">
                                <h1 class="text-black min-w-0 truncate font-semibold">Prabowo</h1>
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-500">@owocut</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-gray-800 text-base">
                            <p>Motor Apa tuchhh?</p>
                        </div>

                        <div class="mt-4 flex items-center text-gray-500 text-sm max-w-sm gap-4">
                            <p class="text-gray-400">
                                21h
                            </p>
                            <button id="reply" class="text-gray-600 hover:text-blue-600 transition duration-300 font-semibold">
                                Reply
                            </button>
                        </div>
                        <div class="hidden" id="reply_form">
                            <form method="POST" action="" class="bg-white text-black border-t border-gray-200 p-4 rounded-2xl my-2">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" alt="Your Profile" class="w-10 h-10 rounded-full">
                                    </div>
                                    <input type="text" class="w-full bg-transparent text-gray-800 ring-1 placeholder-gray-500 border-none focus:ring-1 focus:outline-blue-600 rounded-full p-1.5 ps-3" placeholder="Reply">
                                    <div class="flex items-center justify-end">
                                        <button class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors">
                                            Reply
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Reply comments -->
                        <div class="border-t border-gray-200 mt-4 pt-4">

                            <div class="replies-section space-y-4">

                                <div class="comment-container">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" alt="Profile" class="w-9 h-9 rounded-full">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h1 class="text-black min-w-0 truncate font-semibold">Arif fadillah Wicaksono</h1>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-gray-500">@feraacorp</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 ml-1 pl-12 text-gray-800 text-base">
                                        <p>Ini adalah contoh balasan yang sudah dirapikan.</p>
                                    </div>
                                    <div class="mt-3 ml-1 pl-12 flex items-center text-gray-500 text-sm gap-4">
                                        <p class="text-gray-400">20h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <aside class="hidden lg:block w-[360px] p-4 flex-shrink-0 bg-white border-l border-gray-200 ">
            <?php require_once 'app/views/components/postingan/forYouPage.php' ?>
        </aside>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const replyButton = document.getElementById("reply");
            const replyForm = document.getElementById("reply_form");

            if (replyButton && replyForm) {
                replyButton.addEventListener("click", function() {
                    replyForm.classList.toggle("hidden");

                    const inputField = replyForm.querySelector('input[type="text"]');
                    if (inputField) {
                        inputField.focus();
                    }
                });
            }
        });
    </script>
</body>

</html>