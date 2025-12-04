<?php
$isChild = isset($isChild) ? $isChild : false;
$rootReplyId = isset($rootReplyId) ? $rootReplyId : null;

$currentRootIdString = trim((string)$rootReplyId);

$currentParentIdString = isset($reply['PARENT_ID']) ? trim((string)$reply['PARENT_ID']) : '';

$showArrow = ($isChild
    && !empty($reply['REPLY_TO_USERNAME'])
    && ($currentParentIdString !== '')
    && ($currentParentIdString !== $currentRootIdString)
);
?>

<div class="reply-container relative mb-2 my-5 <?= $isChild ? 'ml-8 sm:ml-14 border-l-2 border-gray-100 pl-3' : '' ?>">

    <div class="flex items-start space-x-3">
        <div class="flex-shrink-0">
            <img src="<?= !empty($reply['PATH_PHOTO'])
                            ? BASEURL . '/storage/users/photos/' . $reply['PATH_PHOTO']
                            : BASEURL . '/src/asset/image/default.png' ?>"
                alt="Profile" class="w-9 h-9 rounded-full object-cover">
        </div>

        <div class="flex-1 min-w-0">
            <h1 class="text-black truncate font-semibold text-sm sm:text-base">
                <?= htmlspecialchars($reply['FULL_NAME']) ?>
            </h1>

            <div class="flex gap-1 items-center flex-wrap">
                <?php
                $profileUrl = ($reply['USER_ID'] === $_SESSION['user_id'])
                    ? BASEURL . "/profile"
                    : BASEURL . "/homepage/user/profile/" . htmlspecialchars($reply['USER_ID']);

                $currentRole = $_SESSION['role'] ?? '';
                $allowedRoles = ['MAHASISWA', 'DOSEN', 'ADMIN'];
                $isLinkActive = in_array($currentRole, $allowedRoles);
                ?>

                <?php if ($isLinkActive): ?>
                    <a href="<?= $profileUrl ?>" class="hover:underline hover:text-blue-500 transition-colors cursor-pointer">
                        <span class="text-gray-400 text-xs sm:text-sm">@<?= htmlspecialchars($reply['USERNAME']) ?></span>
                    </a>
                <?php else: ?>
                    <span class="text-gray-400 text-xs sm:text-sm cursor-default">@<?= htmlspecialchars($reply['USERNAME']) ?></span>
                <?php endif; ?>

                <?php if ($showArrow): ?>
                    <div class="flex items-center gap-1 text-xs sm:text-sm mt-0.5">
                        <svg class="w-3 h-3 text-blue-700 rotate-180 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>

                        <?php
                        $replyToUsername = htmlspecialchars($reply['REPLY_TO_USERNAME']);
                        $replyToID = htmlspecialchars($reply['REPLY_TO_ID']);
                        $currentUsername = $_SESSION['username'] ?? ''; 

                        $targetUrl = ($replyToUsername === $currentUsername)
                            ? BASEURL . '/profile'
                            : BASEURL . '/homepage/user/profile/' . urlencode($replyToID);

                        $currentRole = $_SESSION['role'] ?? '';
                        $allowedRoles = ['MAHASISWA', 'DOSEN', 'ADMIN'];
                        $isLinkActive = in_array($currentRole, $allowedRoles);
                        ?>

                        <span class="text-blue-600">
                            <?php if ($isLinkActive): ?>
                                <a href="<?= $targetUrl ?>" class="hover:underline hover:text-blue-700 transition-colors cursor-pointer">
                                    @<?= $replyToUsername ?>
                                </a>
                            <?php else: ?>
                                <span class="cursor-default">
                                    @<?= $replyToUsername ?>
                                </span>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
    $rawMsg = $reply['MESSAGE_FORMATTED'] ?? $reply['MESSAGE'] ?? '';

    if ($rawMsg instanceof OCILob) {
        $replyMsg = $rawMsg->load();
    } else {
        $replyMsg = $rawMsg;
    }
    ?>

    <div class="ml-3.5 md:ml-0">
        <div class="ml-1 pl-8 sm:pl-12 text-gray-800 text-sm sm:text-base break-words">
            <p><?= nl2br($replyMsg ?? '') ?></p>
        </div>

        <div class="mt-3 ml-1 pl-8 sm:pl-12 flex items-center justify-between text-gray-500 text-xs sm:text-sm">
            <div class="flex gap-3 sm:gap-4 items-center">
                <p class="text-gray-400 time-ago" data-time="<?= htmlspecialchars($reply['CREATED_AT']) ?>"></p>
                <button class="toggle-reply text-gray-600 hover:text-blue-600 transition duration-300 font-semibold cursor-pointer"
                    data-username="<?= htmlspecialchars($reply['USERNAME']) ?>"> Reply
                </button>
            </div>

            <?php if (
                $_SESSION['user_id'] == $reply['USER_ID'] ||
                $_SESSION['user_id'] == $topic['USER_ID'] ||
                $_SESSION['role'] == 'ADMIN'
            ): ?>
                <button onclick="openDeleteModal('reply', '<?= $reply['REPLY_ID'] ?>')"
                    class="text-red-500 hover:text-red-600 font-semibold transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="hidden reply-form ml-1 pl-8 sm:pl-12">
        <form method="POST" class="reply-form-data bg-white text-black border-t border-gray-200 p-3 sm:p-4 rounded-2xl my-2">
            <input type="hidden" name="comment_id" value="<?= trim(htmlspecialchars($comment['COMMENT_ID'])) ?>">

            <input type="hidden" name="parent_id" value="<?= isset($reply['REPLY_ID']) ? trim(htmlspecialchars($reply['REPLY_ID'])) : '' ?>">

            <input type="hidden" name="reply_to_id" value="<?= htmlspecialchars($reply['REPLY_ID']) ?>">

            <input type="hidden" name="topik_id" value="<?= htmlspecialchars($topic['ID']) ?>">

            <div class="flex flex-col md:flex-row md:items-center md:space-x-3 w-full space-y-3 md:space-y-0 relative">
                <div class="flex items-center space-x-3 w-full md:flex-1">
                    <div class="flex-shrink-0">
                        <img src="<?= !empty($_SESSION['path_photo'])
                                        ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                        : BASEURL . '/src/asset/image/default.png' ?>"
                            alt="Your Profile"
                            class="w-10 h-10 rounded-full object-cover">
                    </div>
                    <div class="relative flex-1 w-full">
                        <textarea name="message" rows="1"
                            class="reply-textarea w-full hide-scrollbar bg-transparent text-sm md:text-base text-gray-800 ring-1 ring-gray-300 placeholder-gray-500 border-none focus:ring-blue-600 focus:outline-none rounded-2xl p-2 md:p-2.5 ps-3 resize-none"
                            placeholder="Reply..." maxlength="150"></textarea>
                        <div class="mention-dropdown hidden absolute bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto z-50 w-full mt-1"></div>
                    </div>
                </div>
                <div class="flex justify-end w-full md:w-auto">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors w-full md:w-auto cursor-pointer">
                        Reply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>