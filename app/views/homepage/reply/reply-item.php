<div class="flex items-start space-x-3">
    <div class="flex-shrink-0">
        <img src="<?= !empty($reply['PATH_PHOTO'])
                        ? BASEURL . '/storage/users/photos/' . $reply['PATH_PHOTO']
                        : BASEURL . '/src/asset/image/default.png' ?>"
            alt="Profile" class="w-9 h-9 rounded-full">
    </div>
    <div class="flex-1 min-w-0">
        <h1 class="text-black truncate font-semibold text-sm sm:text-base">
            <?= htmlspecialchars($reply['FULL_NAME']) ?>
        </h1>
        <div class="flex gap-1 items-center">
            <span class="text-gray-400 text-xs sm:text-sm">@<?= htmlspecialchars($reply['USERNAME']) ?></span>
            <?php if (!empty($reply['REPLY_TO_USERNAME'])): ?>
                <div class="flex items-center gap-1 text-xs sm:text-sm mt-0.5">
                    <svg class="w-3 h-3 text-blue-700 rotate-180 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                    <?php
                    $replyToUsername = htmlspecialchars($reply['REPLY_TO_USERNAME']);
                    $replyToID = htmlspecialchars($reply['REPLY_TO_ID']);
                    $currentUsername = $_SESSION['username'];

                    if ($replyToUsername === $currentUsername) {
                        $profileUrl = BASEURL . '/profile';
                    } else {
                        $profileUrl = BASEURL . '/homepage/user/profile/' . urlencode($replyToID);
                    }
                    ?>
                    <span class="text-blue-600">
                        <a href="<?= $profileUrl ?>"
                            class="hover:underline hover:text-blue-700 transition-colors">
                            @<?= $replyToUsername ?>
                        </a>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$replyMsg = $reply['MESSAGE_FORMATTED'];
if ($replyMsg instanceof OCILob) {
    $replyMsg = $replyMsg->load();
}

if (preg_match('/^@(\w+)/', trim($replyMsg), $matches)) {
    $cleanMsg = preg_replace('/^@\w+\s*/', '', $replyMsg);
} else {
    $cleanMsg = $replyMsg;
}
?>

<div class="ml-3.5 md:ml-0">
    <div class="mt-2 ml-1 pl-8 sm:pl-12 text-gray-800 text-sm sm:text-base break-words">
        <p><?= nl2br($cleanMsg ?? '') ?></p>
    </div>

    <div class="mt-3 ml-1 pl-8 sm:pl-12 flex items-center text-gray-500 text-xs sm:text-sm gap-3 sm:gap-4">
        <p class="text-gray-400 time-ago" data-time="<?= htmlspecialchars($reply['CREATED_AT']) ?>"></p>
        <button class="toggle-reply text-gray-600 hover:text-blue-600 transition duration-300 font-semibold">
            Reply
        </button>
    </div>

</div>
<div class="hidden reply-form ml-1 pl-8 sm:pl-12">
    <form method="POST" class="reply-form-data bg-white text-black border-t border-gray-200 p-3 sm:p-4 rounded-2xl my-2">
        <input type="hidden" name="comment_id" value="<?= trim(htmlspecialchars($comment['COMMENT_ID'])) ?>">
        <input type="hidden" name="parent_id" value="<?= isset($reply['REPLY_ID']) ? trim(htmlspecialchars($reply['REPLY_ID'])) : '' ?>">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['POST_ID']) ?>">

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
                        placeholder="Reply..."></textarea>
                    <div class="mention-dropdown hidden absolute bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto z-50 w-full mt-1"></div>
                </div>
            </div>

            <div class="flex justify-end w-full md:w-auto">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors w-full md:w-auto">
                    Reply
                </button>
            </div>

        </div>

    </form>
</div>