<div id="Chat-Navigation" class="flex items-center justify-between w-full border-b border-gray-200 p-5 gap-3 bg-white flex-shrink-0">
    <div id="Group-Title" class="flex items-center flex-1 gap-3">
        <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden items-center justify-center">

            <?php if (!empty($forumByid['PATH_PHOTO'])): ?>
                <img
                    src="<?= BASEURL . '/storage/forums/photos/' . $forumByid['PATH_PHOTO'] ?>"
                    class="w-full h-full object-cover"
                    alt="photo">
            <?php else: ?>
                <span class="w-full h-full flex items-center justify-center bg-pink-500 text-white font-bold text-lg">
                    <?= strtoupper(substr($forumByid['NAME'], 0, 2)) ?>
                </span>
            <?php endif; ?>

        </div>

        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-[6px]">
                <h1 class="font-semibold text-lg truncate overflow-hidden whitespace-nowrap">
                    <?= $forumByid["NAME"] ?>
                </h1>
            </div>
            <div class="flex items-center gap-[6px]">
                <span class="font-semibold text-sm text-gray-500"><?= count($membersForum) ?> Members</span>
            </div>
        </div>
    </div>
    <ul class="flex gap-3">
        <li class="group">
            <button id="reportForumButton" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/report.png" class="size-6" alt="icon">
            </button>
        </li>
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
                <button id="btn-open-exit-forum" class="w-full h-full flex gap-1 items-center justify-center bg-white rounded-2xl p-[10px] ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/logout-grey.svg" class="size-6 flex shrink-0" alt="icon">
                    <span class="font-medium text-sm text-heyhao-secondary">Leave Forum</span>
                </button>
            </div>
            <div class="group">
                <button id="Close-Info" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-100 hover:ring-1 hover:ring-blue-600 transition-all duration-300 cursor-pointer">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/close-circle-grey.svg" class="size-6" alt="icon">
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto hide-scrollbar">
            <div class="flex flex-col items-center py-8 px-6 gap-4 border-b border-gray-200">
                <div class="flex size-[120px] rounded-full overflow-hidden items-center justify-center bg-gray-200">

                    <?php if (!empty($forumByid['PATH_PHOTO'])): ?>
                        <img
                            src="<?= BASEURL . '/storage/forums/photos/' . $forumByid['PATH_PHOTO'] ?>"
                            class="w-full h-full object-cover"
                            alt="photo">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-pink-500 text-white text-3xl font-bold">
                            <?= strtoupper(substr($forumByid['NAME'], 0, 2)) ?>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="flex flex-col items-center gap-2">
                    <div class="flex items-center justify-center gap-[6px]">
                        <p class="font-semibold text-lg"> <?= $forumByid["NAME"] ?></p>
                    </div>
                    <?php if ($forumByid['OWNER_ID'] == $_SESSION['user_id'] && !empty($forumByid['ACCESS_KEY'])): ?>
                        <div class="flex items-center gap-[6px]">
                            <span class="text-xs font-medium text-violet-700 bg-violet-100 px-2 py-0.5 rounded-md">
                                Private Key: <?= $forumByid['ACCESS_KEY'] ?>
                            </span>
                        </div>
                    <?php endif; ?>
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
                    <p class="leading-8"><?= $forumByid["ABOUT"] ?></p>
                </div>
                <div>
                    <div id="Owner" class="flex flex-col gap-3 mt-6">
                        <p class="font-semibold leading-5">Owner</p>
                        <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 min-w-0">
                            <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                                <img src="<?= !empty($forumByid['PATH_PHOTO_OWNER'])
                                                ? BASEURL . '/storage/users/photos/' . $forumByid['PATH_PHOTO_OWNER']
                                                : BASEURL . '/src/asset/image/default.png' ?>" class="w-full h-full object-cover" alt="photo">
                            </div>
                            <div class="flex flex-col flex-1 gap-[6px] min-w-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold truncate"><?= $forumByid["OWNER_NAME"] ?></p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm"><?= $member["ROLE"] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="Members" class="flex flex-col gap-3 mt-6">
                        <?php
                        $membersForumFiltered = array_filter($membersForum, function($m) use ($forumByid) {return $m['USER_ID'] !== $forumByid['OWNER_ID'];});?>
                        <p class="font-semibold leading-5">Members (<?= count($membersForumFiltered) ?>)</p>

                        <div class="flex flex-col gap-3">
                            <?php foreach ($membersForumFiltered as $member): ?>
                                <?php if ($member['USER_ID'] == $forumByid['OWNER_ID']) continue; ?>
                                <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 min-w-0">
                                    <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                                        <img src="<?= !empty($member['PATH_PHOTO'])
                                                        ? BASEURL . '/storage/users/photos/' . $member['PATH_PHOTO']
                                                        : BASEURL . '/src/asset/image/default.png' ?>" class="w-full h-full object-cover" alt="photo">
                                    </div>
                                    <div class="flex flex-col flex-1 gap-[6px] min-w-0">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold truncate"><?= $member["NAME"] ?></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm"><?= $member["ROLE"] ?></span>
                                            </div>
                                        </div>
                                        <div class="flex font-medium text-sm text-heyhao-secondary gap-0.5 items-center">
                                            <p>Joined: </p>
                                            <p><?= $member["JOINED_AT"] ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                
                <?php if ($forumByid['OWNER_ID'] == $_SESSION['user_id']): ?>
                    <div class="my-5 flex">
                        <button type="button" id="btn-open-manage-members"
                            class="text-gray-700 border border-gray-400 hover:bg-gray-300
                            focus:ring-2 focus:outline-none focus:ring-gray-300
                            font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 w-full">
                            Manage Members
                        </button>
                    </div>
                    <div class="my-5 flex gap-3">
                        <button type="button" id="btn-open-edit-forum"
                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                   hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 
                   font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 w-full">
                            Edit Forum
                        </button>

                        <button type="button" id="btn-open-delete-forum"
                            class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 
                   focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg 
                   text-sm px-5 py-2.5 text-center me-2 mb-2 w-full">
                            Delete Forum
                        </button>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
</div>

<?php require_once 'app/views/components/forums/modalEditForum.php'; ?>
<?php require_once 'app/views/components/forums/modalDeleteForum.php'; ?>
<?php require_once 'app/views/components/forums/modalExitForum.php'; ?>
<?php require_once 'app/views/components/forums/modalReportForum.php'; ?>
<?php require_once 'app/views/components/forums/modalManageMember.php'; ?>
