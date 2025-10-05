<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Forums - <?= $forumByid["NAME"] ?></title>
</head>

<body>
    <div class="relative flex h-screen overflow-hidden bg-gray-50">

        <?php require_once 'app/views/components/sidebars.php'; ?>
        <?php require_once 'app/views/components/forums/forumsList.php'; ?>

        <main id="Main-Content-Container" class="relative flex flex-1">
            <div id="Chat-Container" class="flex flex-col flex-1 h-full overflow-hidden">

                <?php require_once 'app/views/components/forums/detailForum.php'; ?>

                <div id="Chat-Messages" class="relative flex-1 overflow-y-auto">
                    <article class="relative flex flex-col gap-5 p-5 z-0 h-[200vh]">
                        <p class="date sticky w-[150px] text-center top-5 mt-[21px] mx-auto rounded-xl py-[10px] px-3 bg-white font-medium text-sm z-30">
                            Yesterday, 18 Dec
                        </p>
                        <div class="chat-row">
                            <div class="message-in group flex flex-col gap-3 [&.message-out]:items-end [&.message-in]:items-start">
                                <!-- change message-(in/out) class to swicth the card position to left (in) or right (out) -->
                                <div class="time sender flex items-center gap-3 group-[&.message-in]:flex-row-reverse">
                                    <div class="flex items-center gap-[6px] group-[&.message-in]:flex-row-reverse">
                                        <img src="assets/images/icons/Send.svg" class="flex size-6 shrink-0 group-[&.message-in]:hidden" alt="icon">
                                        <p class="flex gap-[6px] group-[&.message-in]:flex-row-reverse text-heyhao-secondary">
                                            <span>12:06 AM</span>
                                            <span> • </span>
                                            <span class="text-heyhao-black">Neb</span>
                                        </p>
                                    </div>
                                    <div class="flex size-8 shrink-0 overflow-hidden rounded-full">
                                        <img src="<?php echo BASEURL; ?>/src/asset/image/default.png" class="w-full h-full object-cover" alt="photo">
                                    </div>
                                </div>
                                <div class="message-card relative max-w-[584px]">
                                    <div class="w-fit rounded-3xl group-[&.message-out]:rounded-tr-none group-[&.message-in]:rounded-tl-none py-5 px-4 gap-2 bg-heyhao-card-meesage group-[&.message-in]:bg-white leading-[28px]">
                                        <p>Halo, semuanya! buat baru join. Yuk, kenalan dulu biar diskusinya nanti tambah seru dan santai!😎</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="date sticky w-[150px] text-center top-5 mt-[21px] mx-auto rounded-xl py-[10px] px-3 bg-white font-medium text-sm z-30">
                            Yesterday, 19 Dec
                        </p>
                        <div class="chat-row">
                            <div class="message-in group flex flex-col gap-3 [&.message-out]:items-end [&.message-in]:items-start">
                                <!-- change message-(in/out) class to swicth the card position to left (in) or right (out) -->
                                <div class="time sender flex items-center gap-3 group-[&.message-in]:flex-row-reverse">
                                    <div class="flex items-center gap-[6px] group-[&.message-in]:flex-row-reverse">
                                        <img src="assets/images/icons/Send.svg" class="flex size-6 shrink-0 group-[&.message-in]:hidden" alt="icon">
                                        <p class="flex gap-[6px] group-[&.message-in]:flex-row-reverse text-heyhao-secondary">
                                            <span>12:06 AM</span>
                                            <span> • </span>
                                            <span class="text-heyhao-black">Neb</span>
                                        </p>
                                    </div>
                                    <div class="flex size-8 shrink-0 overflow-hidden rounded-full">
                                        <img src="<?php echo BASEURL; ?>/src/asset/image/default.png" class="w-full h-full object-cover" alt="photo">
                                    </div>
                                </div>
                                <div class="message-card relative max-w-[584px]">
                                    <div class="w-fit rounded-3xl group-[&.message-out]:rounded-tr-none group-[&.message-in]:rounded-tl-none py-5 px-4 gap-2 bg-heyhao-card-meesage group-[&.message-in]:bg-white leading-[28px]">
                                        <p>Halo, semuanya! buat baru join. Yuk, kenalan dulu biar diskusinya nanti tambah seru dan santai!😎</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="date sticky w-[150px] text-center top-5 mt-[21px] mx-auto rounded-xl py-[10px] px-3 bg-white font-medium text-sm z-30">
                            Yesterday, 20 Dec
                        </p>
                        <div class="chat-row">
                            <div class="message-in group flex flex-col gap-3 [&.message-out]:items-end [&.message-in]:items-start">
                                <!-- change message-(in/out) class to swicth the card position to left (in) or right (out) -->
                                <div class="time sender flex items-center gap-3 group-[&.message-in]:flex-row-reverse">
                                    <div class="flex items-center gap-[6px] group-[&.message-in]:flex-row-reverse">
                                        <img src="assets/images/icons/Send.svg" class="flex size-6 shrink-0 group-[&.message-in]:hidden" alt="icon">
                                        <p class="flex gap-[6px] group-[&.message-in]:flex-row-reverse text-heyhao-secondary">
                                            <span>12:06 AM</span>
                                            <span> • </span>
                                            <span class="text-heyhao-black">Neb</span>
                                        </p>
                                    </div>
                                    <div class="flex size-8 shrink-0 overflow-hidden rounded-full">
                                        <img src="<?php echo BASEURL; ?>/src/asset/image/default.png" class="w-full h-full object-cover" alt="photo">
                                    </div>
                                </div>
                                <div class="message-card relative max-w-[584px]">
                                    <div class="w-fit rounded-3xl group-[&.message-out]:rounded-tr-none group-[&.message-in]:rounded-tl-none py-5 px-4 gap-2 bg-heyhao-card-meesage group-[&.message-in]:bg-white leading-[28px]">
                                        <p>Halo, semuanya! buat baru join. Yuk, kenalan dulu biar diskusinya nanti tambah seru dan santai!😎</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>


                <div class="relative flex w-full z-10 bottom-14 lg:bottom-0">
                    <form id="chat-form" class="w-full p-5 gap-[10px] z-20">
                        <div id="preview-container" class="hidden relative w-full p-2 mb-2 bg-gray-100 rounded-lg">
                            <div class="flex items-center gap-3">
                                <img id="preview-image" src="" class="w-12 h-12 object-cover rounded" alt="File Preview">
                                <span id="preview-filename" class="text-sm text-gray-700 truncate"></span>
                            </div>
                            <button type="button" id="remove-preview" class="absolute top-1 right-1 size-6 flex items-center justify-center  hover:bg-gray-400 rounded-full">
                                <img src="<?php echo BASEURL; ?>/src/asset/icons/close-circle-grey.svg" class="w-4 h-4" alt="Remove">
                            </button>
                        </div>
                        <div class="relative">
                            <div id="Chat-Input" contenteditable="true" spellcheck="false"
                                class="appearance-none outline-none w-full min-h-[60px] max-h-[200px] overflow-y-auto rounded-2xl p-5 pl-4 pr-[112px] bg-white break-all font-medium leading-5 hide-scrollbar focus:ring-2 focus:ring-blue-600 transition-all duration-300 text-gray-900 shadow-sm">
                            </div>

                            <div id="placeholder" class="absolute top-5 left-4 text-gray-500 pointer-events-none select-none">
                                Type a message...
                            </div>

                            <div class="absolute flex right-2 bottom-2 gap-2">
                                <button type="button" id="Upload-Image"
                                    class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300">
                                    <img src="<?php echo BASEURL; ?>/src/asset/icons/gallery-import.svg" class="w-6 h-6" alt="icon">
                                </button>
                                <button type="submit" id="kirim" class="flex shrink-0 w-11">
                                    <img src="<?php echo BASEURL; ?>/src/asset/icons/Send-Button-blue-bg.svg" class="object-contain" alt="icon">
                                </button>
                            </div>
                        </div>
                    </form>
                    <input type="file" id="imageInput" class="hidden" />
                    <input type="hidden" id="message" name="message">
                </div>

            </div>
        </main>
    </div>
</body>

</html>