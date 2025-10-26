<?php
require_once __DIR__ . '../../../../controllers/CommentController.php';
$commentController = new CommentController();

$postId = $id ?? $_GET['id'] ?? null;
$comments = $commentController->getModel()->getCommentsByPostId($postId);
?>

<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Home Page | <?php echo $id ?></title>
</head>

<body>
    <main class="w-full h-screen overflow-y-auto border-gray-200 hide-scrollbar relative z-[9999]">
        <div class="sticky top-0 z-10 bg-white w-full px-5 py-3 mb-4 border-b border-gray-200">
            <button onclick="window.history.back()" class="flex items-center gap-3 text-black font-semibold cursor-pointer">
                <img src="<?php echo BASEURL . '/src/asset/icons/left-arrow-svgrepo-com.svg'; ?>" alt="icon" class="w-6 h-6">
                <h1 class="text-xl">Post</h1>
            </button>
        </div>
        <div class="max-w-xl mx-auto px-5 md:p-0 mb-20 md:mb-10">
            <div class="max-w-xl w-full mx-auto">
                <?php require_once 'app/views/components/postingan/replyPost.php'; ?>

<div class="sticky top-16 max-w-xl">
    <form id="comment-form" method="POST" class="bg-white/60 backdrop-blur border text-black border-gray-200 p-4 rounded-2xl my-2">
        <input type="hidden" name="post_id" value="<?= htmlspecialchars($postId) ?>">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <img src="<?= BASEURL ?>/src/asset/image/default.png" alt="Your Profile" class="w-12 h-12 rounded-full">
            </div>
            <textarea name="message" rows="2"
                class="w-full hide-scrollbar bg-transparent text-lg text-gray-800 placeholder-gray-500 border-none focus:ring-0 focus:outline-none resize-none p-1"
                placeholder="Add Comment...."></textarea>
            <div class="mt-2 flex items-center justify-end">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors" required>
                    Comment
                </button>
            </div>
        </div>
    </form>
</div>

<?php if (empty($comments)): ?>
    <div class="bg-white text-center text-gray-500 border border-gray-200 p-6 rounded-2xl mt-4">
        Belum ada komentar.
    </div>
<?php else: ?>
    <?php foreach ($comments as $comment): ?>
        <div class="bg-white text-black border border-gray-200 p-4 rounded-2xl my-4 comment-block">
            <!-- HEADER KOMENTAR -->
            <div class="flex items-start space-x-3 border-b border-gray-200 pb-5">
                <div class="flex-shrink-0">
                    <img src="<?= !empty($comment['PATH_PHOTO'])
                        ? BASEURL . '/storage/users/photos/' . $comment['PATH_PHOTO']
                        : BASEURL . '/src/asset/image/default.png' ?>"
                        alt="Profile" class="w-12 h-12 rounded-full">
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-black min-w-0 truncate font-semibold">
                        <?= htmlspecialchars($comment['FULL_NAME']) ?>
                    </h1>
                    <span class="text-gray-500">@<?= htmlspecialchars($comment['USERNAME']) ?></span>
                </div>
            </div>

            <div class="mt-4 text-gray-800 text-base">
                <?php
                $message = $comment['MESSAGE'];
                if ($message instanceof OCILob) {
                    $message = $message->load();
                }
                ?>
                <p><?= nl2br(htmlspecialchars($message ?? '')) ?></p>
            </div>

            <div class="mt-4 flex items-center text-gray-500 text-sm max-w-sm gap-4">
                <p class="text-gray-400"><?= date('d M H:i', strtotime($comment['CREATED_AT'])) ?></p>
                <button class="toggle-reply text-gray-600 hover:text-blue-600 transition duration-300 font-semibold" required>
                    Reply
                </button>
            </div>

            <div class="hidden reply-form">
                <form method="POST" class="reply-form-data bg-white text-black border-t border-gray-200 p-4 rounded-2xl my-2">
                    <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['COMMENT_ID']) ?>">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <img src="<?= BASEURL ?>/src/asset/image/default.png" alt="Your Profile" class="w-10 h-10 rounded-full">
                        </div>
                        <input type="text" name="message"
                            class="w-full bg-transparent text-gray-800 ring-1 placeholder-gray-500 border-none focus:ring-1 focus:outline-blue-600 rounded-full p-1.5 ps-3"
                            placeholder="Reply...">
                        <div class="flex items-center justify-end">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors" required>
                                Reply
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if (!empty($comment['REPLIES'])): ?>
                <div class="border-t border-gray-200 mt-4 pt-4 replies-section space-y-4">
                    <?php foreach ($comment['REPLIES'] as $reply): ?>
                        <div class="comment-container">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <img src="<?= !empty($reply['PATH_PHOTO'])
                                        ? BASEURL . '/storage/users/photos/' . $reply['PATH_PHOTO']
                                        : BASEURL . '/src/asset/image/default.png' ?>"
                                        alt="Profile" class="w-9 h-9 rounded-full">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h1 class="text-black min-w-0 truncate font-semibold">
                                        <?= htmlspecialchars($reply['FULL_NAME']) ?>
                                    </h1>
                                    <span class="text-gray-500">@<?= htmlspecialchars($reply['USERNAME']) ?></span>
                                </div>
                            </div>

                            <div class="mt-2 ml-1 pl-12 text-gray-800 text-base">
                                <?php
                                $replyMsg = $reply['MESSAGE'];
                                if ($replyMsg instanceof OCILob) {
                                    $replyMsg = $replyMsg->load();
                                }
                                ?>
                                <p><?= nl2br(htmlspecialchars($replyMsg ?? '')) ?></p>
                            </div>

                            <div class="mt-3 ml-1 pl-12 flex items-center text-gray-500 text-sm gap-4">
                                <p class="text-gray-400"><?= date('d M H:i', strtotime($reply['CREATED_AT'])) ?></p>
                                <button class="toggle-reply text-gray-600 hover:text-blue-600 transition duration-300 font-semibold">
                                    Reply
                                </button>
                            </div>

                            <div class="hidden reply-form ml-1 pl-12">
                                <form method="POST" class="reply-form-data bg-white text-black border-t border-gray-200 p-4 rounded-2xl my-2">
                                    <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['COMMENT_ID']) ?>">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <img src="<?= BASEURL ?>/src/asset/image/default.png" alt="Your Profile" class="w-10 h-10 rounded-full">
                                        </div>
                                        <input type="text" name="message"
                                            class="w-full bg-transparent text-gray-800 ring-1 placeholder-gray-500 border-none focus:ring-1 focus:outline-blue-600 rounded-full p-1.5 ps-3"
                                            placeholder="Reply...">
                                        <div class="flex items-center justify-end">
                                            <button type="submit"
                                                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors">
                                                Reply
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
    </main>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('toggle-reply')) {
            const commentBlock = e.target.closest('.comment-block, .comment-container');
            const replyForm = commentBlock.querySelector('.reply-form');
            if (replyForm) {
                replyForm.classList.toggle('hidden');
                const input = replyForm.querySelector('input');
                if (input) input.focus();
            }
        }
    });

    const commentForm = document.querySelector('#comment-form');
    commentForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(commentForm);
        const res = await fetch('<?= BASEURL ?>/comment/add', {method: 'POST', body: formData});
        const data = await res.json();
        if (data.success) location.reload();
        else alert(data.message);
    });

    document.querySelectorAll('.reply-form-data').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const res = await fetch('<?= BASEURL ?>/comment/reply', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) location.reload();
            else alert(data.message);
        });
    });
});
</script>

</body>

</html>