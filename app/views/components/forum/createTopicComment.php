<?php if ($isMember) : ?>

    <div class="sticky top-16 w-full mb-5 z-10">
        <form id="comment-form" method="POST" class="bg-white/60 backdrop-blur border text-black border-gray-200 p-4 rounded-2xl my-2">
            <input type="hidden" name="topic_id" value="<?= $topic['ID'] ?? '' ?>">
            <div class="flex flex-col sm:flex-row items-start space-y-3 sm:space-y-0 sm:space-x-3 relative">
                <div class="flex-shrink-0">
                    <img src="<?= !empty($_SESSION['path_photo'])
                                    ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                    : BASEURL . '/src/asset/image/default.png' ?>" alt="Your Profile" class="w-12 h-12 rounded-full">
                </div>
                <div class="flex-1 w-full relative">
                    <textarea name="message" rows="2" id="commentForm"
                        class="comment-textarea w-full hide-scrollbar bg-transparent text-base sm:text-lg text-gray-800 placeholder-gray-500 border-none focus:ring-0 focus:outline-none resize-none p-1"
                        placeholder="Add Comment...." maxlength="150"></textarea>
                    <div class="mention-dropdown hidden absolute bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto z-50 w-full mt-1"></div>
                </div>
                <div class="w-full sm:w-auto flex items-center justify-end sm:mt-2">
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors cursor-pointer">
                        Comment
                    </button>
                </div>
            </div>
        </form>
    </div>

<?php else: ?>

    <?php if ($_SESSION['role'] === 'ADMIN'): ?>
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl mb-5 text-center text-sm">
            <span>
                You are viewing this topic as <b>Admin</b>.

                <button type="button"
                    onclick="requestJoin('<?= $forumById['ID'] ?>')"
                    class="font-bold underline cursor-pointer hover:text-yellow-600 transition-colors">
                    Join the forum
                </button>

                to post a comment.
            </span>
        </div>
    <?php endif; ?>

<?php endif; ?>

<?php require_once 'app/views/components/forum/modalJoinForum.php'; ?>


<div id="succsesDivComment" class="fixed top-5 right-5 bg-green-100 border border-green-600 text-green-600 py-2 rounded-md px-4 hidden"></div>

<script>
    const commentForm = document.querySelector('#comment-form');
    const successDivComment = document.querySelector('#succsesDivComment');

    commentForm?.addEventListener('submit', async (e) => {
        e.preventDefault();

        console.log("DIE")

        successDivComment.classList.add('hidden');
        const formData = new FormData(commentForm);
        const res = await fetch('<?= BASEURL ?>/comment/add-topic', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            successDivComment.classList.remove('hidden');
            successDivComment.textContent = 'Comment added successfully!';
            setTimeout(() => {
                location.reload();
            }), 1500;
        } else alert(data.message);

    });
</script>