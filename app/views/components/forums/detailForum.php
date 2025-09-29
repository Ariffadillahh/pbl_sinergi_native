<style>
   
</style>

<div id="Chat-Navigation" class="flex items-center justify-between w-full border-b border-gray-200 p-5 gap-3 bg-white flex-shrink-0">
    <div id="Group-Title" class="flex items-center flex-1 gap-3">
        <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
            <img src="<?php echo !empty($forumByid['photo'])
                            ? BASEURL . '/storage/forums/photos/' . $forumByid['photo']
                            : BASEURL . '/src/asset/image/default.png'; ?>"
                class="w-full h-full object-cover" alt="photo">
        </div>
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-[6px]">
                <h1 class="font-semibold text-lg truncate overflow-hidden whitespace-nowrap">
                    <?= $forumByid["name"] ?>
                </h1>
            </div>
            <div class="flex items-center gap-[6px]">
                <span class="font-semibold text-sm text-gray-500"><?= count($membersForum) ?> Members</span>
            </div>
        </div>
    </div>
    <ul class="flex gap-3">
        <li class="group">
            <button id="infoForum" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/more.svg" class="size-6" alt="icon">
            </button>
        </li>
    </ul>
</div>

<div class="hidden fixed top-0 left-0 w-full h-screen z-[9999] bg-black/50" id="Overlay-Info">
    <div class="fixed w-[80%] sm:w-[60%] lg:w-[40%] xl:w-[35%] top-0 right-0 h-screen bg-white shadow-lg flex flex-col" id="Info-Forum">
        <p class="font-semibold text-lg text-center mt-4">Group Info</p>
        <div class="flex items-center justify-between border-b border-gray-200 py-3 px-5 flex-shrink-0">
            <div>
                <a href="#" class="w-full h-full flex gap-1 items-center justify-center bg-white rounded-2xl p-[10px] ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/logout-grey.svg" class="size-6 flex shrink-0" alt="icon">
                    <span class="font-medium text-sm text-heyhao-secondary">Leave Forum</span>
                </a>
            </div>
            <div class="group">
                <button id="Close-Info" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-100 hover:ring-1 hover:ring-blue-600 transition-all duration-300 cursor-pointer">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/close-circle-grey.svg" class="size-6" alt="icon">
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto hide-scrollbar">
            <div class="flex flex-col items-center py-8 px-6 gap-4 border-b border-gray-200">
                <div class="flex size-[120px] rounded-full overflow-hidden">
                    <img src="<?php echo !empty($forumByid['photo'])
                                    ? BASEURL . '/storage/forums/photos/' . $forumByid['photo']
                                    : BASEURL . '/src/asset/image/default.png'; ?>" class="w-full h-full object-cover" alt="photo">
                </div>
                <div class="flex flex-col items-center gap-2">
                    <div class="flex items-center justify-center gap-[6px]">
                        <p class="font-semibold text-lg"> <?= $forumByid["name"] ?></p>
                    </div>
                    <div class="flex items-center gap-[6px] font-semibold text-sm">
                        <p class="flex items-center gap-1">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/profile-2user-grey.svg" class="flex size-4 shrink-0" alt="icon">
                            <span class="text-gray-500"><?= count($membersForum) ?> Members</span>
                        </p>
                    </div>

                </div>
            </div>
            <div class="p-6">
                <div id="About" class="flex  gap-3 flex-col">
                    <p class="font-semibold leading-5">About Forum</p>
                    <p class="font-semibold leading-8"><?= $forumByid["about"] ?></p>
                </div>
                <div>
                    <div id="Members" class="flex flex-col gap-3 mt-6">
                        <p class="font-semibold leading-5">Members (<?= count($membersForum) ?>)</p>
                        <div class="flex flex-col gap-3">
                            <?php foreach ($membersForum as $member): ?>
                                <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3">
                                    <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                                        <img src="<?php echo BASEURL; ?>/src/asset/image/default.png" class="w-full h-full object-cover" alt="photo">
                                    </div>
                                    <div class="flex flex-col flex-1 gap-[6px]">
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center gap-1">
                                                <p class="font-semibold truncate"><?= $member["nama"] ?></p>
                                            </div>
                                            <div class="flex items-center gap-0.5">
                                                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm">Role</span>
                                            </div>
                                        </div>
                                        <div class="flex font-medium text-sm text-heyhao-secondary gap-0.5 items-center">
                                            <p>Joined: </p>
                                            <p>21 Dec 2024</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>