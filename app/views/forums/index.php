<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Forums | Sinergi</title>
</head>

<body>
    <div class="relative flex h-screen overflow-hidden bg-gray-50">

        <?php require_once 'app/views/components/sidebars.php'; ?>
        <?php require_once 'app/views/components/forums/forumsList.php'; ?>
        <?php require_once 'app/views/components/forums/modalSearchForum.php'; ?>

        <div id="Main-Content-Container" class="flex-1 overflow-y-auto pb-[70px] lg:pb-0 relative">
            <div class="flex h-[100vh] items-center justify-center text-center">
                <div class="ornaments absolute inset-0 overflow-hidden">
                    <img src="<?php echo BASEURL; ?>/src/asset/image/ornament.png" class="absolute top-0 h-[320px] -right-[249px] rotate-180" alt="ornament" />
                    <img src="<?php echo BASEURL; ?>/src/asset/image/ornament.png" class="absolute bottom-0 h-[300px] -left-[270px]" alt="ornament" />
                </div>
                <div class="flex flex-col items-center gap-6 p-4">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/message-text-blue-transparent-bg.svg" class="w-[120px] h-[120px]" alt="icon" />
                    <div>
                        <p class="text-xl font-semibold leading-[25px]">No chat to display.</p>
                        <p class="mt-2 font-medium leading-5 text-gray-500">
                            Tap on a forum to view the chat.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
        const params = new URLSearchParams(window.location.search);
        const keyword = params.get("keyword");

        if (keyword) {
            const modal = document.getElementById("searchModal");
            const input = document.getElementById("searchLive");

            setTimeout(() => {
            if (modal && input) {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
                input.value = keyword;

                if (typeof performSearch === "function") {
                performSearch(keyword);
                } else {
                console.warn("performSearch() belum terdefinisi");
                }
            } else {
                console.warn("Modal search forum belum ditemukan di halaman /forums");
            }
            }, 300);
        }
        });
    </script>
</body>
</html>

