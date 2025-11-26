<?php
// Helper untuk membaca CLOB Oracle
function loadLobSafe($data)
{
    return ($data instanceof OCILob) ? $data->load() : $data;
}

function organizeReplies($replies)
{
    $repliesById = [];
    $roots = [];

    // 1. Petakan semua reply berdasarkan ID agar mudah dicari
    foreach ($replies as $reply) {
        $reply['children'] = []; // Siapkan array children kosong
        $repliesById[$reply['REPLY_ID']] = $reply;
    }

    // 2. Pisahkan mana Bapak Utama (Root), mana Anak/Cucu
    foreach ($repliesById as $id => $reply) {
        if (empty($reply['PARENT_ID'])) {
            $roots[$id] = &$repliesById[$id];
        }
    }

    // 3. Masukkan Anak, Cucu, Cicit ke dalam children milik Bapak Utama
    foreach ($repliesById as $id => $reply) {
        if (!empty($reply['PARENT_ID'])) {
            $currentParentId = $reply['PARENT_ID'];
            $ultimateRootId = null;

            $safety = 0;
            while ($safety < 100) {
                if (isset($repliesById[$currentParentId])) {
                    if (empty($repliesById[$currentParentId]['PARENT_ID'])) {
                        $ultimateRootId = $currentParentId;
                        break;
                    } else {
                        $currentParentId = $repliesById[$currentParentId]['PARENT_ID'];
                    }
                } else {
                    break;
                }
                $safety++;
            }

            if ($ultimateRootId && isset($roots[$ultimateRootId])) {
                $roots[$ultimateRootId]['children'][] = $reply;
            }
        }
    }

    return $roots;
}
?>

<div class="comments-wrapper">
    <?php if (empty($comments)): ?>
        <div class="bg-white text-center text-gray-500 border border-gray-200 p-6 rounded-2xl mt-4">
            Belum ada komentar. Jadilah yang pertama berkomentar!
        </div>
    <?php else: ?>
        <?php foreach ($comments as $comment): ?>
            <div class="bg-white text-black border border-gray-200 p-4 rounded-2xl my-4 comment-block" id="comment-block-<?= $comment['COMMENT_ID'] ?>">

                <div class="flex items-start space-x-3 border-b border-gray-200 pb-5">
                    <div class="flex-shrink-0">
                        <img src="<?= !empty($comment['PATH_PHOTO'])
                                        ? BASEURL . '/storage/users/photos/' . $comment['PATH_PHOTO']
                                        : BASEURL . '/src/asset/image/default.png' ?>"
                            alt="Profile" class="w-12 h-12 rounded-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-black min-w-0 truncate font-semibold">
                            <?= htmlspecialchars($comment['FULL_NAME']) ?>
                        </h1>
                        <?php
                        $profileUrl = ($comment['USER_ID'] === $_SESSION['user_id'] ?? '')
                            ? BASEURL . "/profile"
                            : BASEURL . "/homepage/user/profile/" . htmlspecialchars($comment['USER_ID']);
                        ?>
                        <a href="<?= $profileUrl ?>">
                            <span class="text-gray-500 text-sm">@<?= htmlspecialchars($comment['USERNAME']) ?></span>
                        </a>
                    </div>
                </div>

                <div class="mt-4 text-gray-800 text-sm sm:text-base break-words">
                    <p><?= nl2br(htmlspecialchars(loadLobSafe($comment['MESSAGE']) ?? '')) ?></p>
                </div>

                <div class="mt-4 flex items-center justify-between text-gray-500 text-xs sm:text-sm gap-3">
                    <div class="flex gap-3 sm:gap-4 items-center">
                        <p class="text-gray-400 time-ago" data-time="<?= htmlspecialchars($comment['CREATED_AT']) ?>">
                            <?= htmlspecialchars($comment['CREATED_AT']) ?>
                        </p>

                        <button class="toggle-reply text-gray-600 hover:text-blue-600 transition duration-300 font-semibold flex items-center gap-1">
                            Reply
                        </button>
                    </div>

                    <?php
                    $canDelete = (
                        ($_SESSION['user_id'] ?? '') == $comment['USER_ID'] ||
                        ($_SESSION['user_id'] ?? '') == ($topic['USER_ID'] ?? '') ||
                        ($_SESSION['role'] ?? '') == 'ADMIN'
                    );
                    if ($canDelete):
                    ?>
                        <button class="text-red-500 hover:text-red-600 font-semibold transition cursor-pointer"
                            onclick="openDeleteModal('comment', '<?= $comment['COMMENT_ID'] ?>')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>

                <div class="hidden reply-form mt-3 transition-all duration-300 ease-in-out">
                    <form method="POST" class="reply-form-data mt-5 border-t border-gray-200 rounded-2xl pt-5">
                        <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['COMMENT_ID']) ?>">
                        <input type="hidden" name="topic_id" value="<?= htmlspecialchars($topic['ID'] ?? '') ?>">

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

                <?php if (!empty($comment['REPLIES'])): ?>
                    <?php
                    $totalReplies = count($comment['REPLIES']);
                    $organizedReplies = organizeReplies($comment['REPLIES']);
                    ?>

                    <div class="border-t border-gray-200 mt-3 pt-4 replies-section">
                        <button class="show-more-replies text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center gap-2 mb-3 transition-colors"
                            data-comment-id="<?= htmlspecialchars($comment['COMMENT_ID']) ?>"
                            data-count="<?= $totalReplies ?>"
                            data-text="<?= $totalReplies === 1 ? 'reply' : 'replies' ?>">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            Show <?= $totalReplies ?> <?= $totalReplies === 1 ? 'reply' : 'replies' ?>
                        </button>

                        <div class="hidden-replies hidden">
                            <?php foreach ($organizedReplies as $parentReply): ?>
                                <?php
                                $reply = $parentReply;
                                $isChild = false;

                                $rootReplyId = $parentReply['REPLY_ID'];

                                include __DIR__ . '/../../forum/detail/topic/reply-item.php';
                                ?>

                                <?php if (!empty($parentReply['children'])): ?>
                                    <?php foreach ($parentReply['children'] as $childReply): ?>
                                        <?php
                                        $reply = $childReply;
                                        $isChild = true;
                                        include __DIR__ . '/../../forum/detail/topic/reply-item.php';
                                        ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div id="deleteModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <div class="fixed inset-0 bg-black/50" aria-hidden="true" onclick="closeDeleteModal()"></div>

        <div class="relative z-10 inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Removed Confirmation</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="deleteMessage">Are you sure you want to removed this?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="deleteConfirmBtn" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Delete</button>
                <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
            </div>
        </div>
    </div>
</div>


<div id="toastSuccess" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-3 rounded shadow-lg opacity-0 transition-opacity duration-300 pointer-events-none z-50">
    Successfully Removed.
</div>

<script>
    function timeAgo(dateString) {
        const date = new Date(dateString.replace(/-/g, "/"));
        const now = new Date();

        if (isNaN(date.getTime())) return dateString;

        const seconds = Math.floor((now - date) / 1000);

        let interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + "y ago";

        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + "mo ago";

        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + "d ago";

        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + "h ago";

        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + "m ago";

        return "Just now";
    }

    function updateAllTimeAgo() {
        document.querySelectorAll('.time-ago').forEach(el => {
            const originalTime = el.getAttribute('data-time');
            if (originalTime) {
                el.textContent = timeAgo(originalTime);
            }
        });
    }

    let deleteTargetId = null;
    let deleteType = null;

    function openDeleteModal(type, id) {
        deleteTargetId = id;
        deleteType = type;
        const msg = type === "comment" ?
            "Delete this comment and all its replies?" :
            "Delete this reply?";
        document.getElementById("deleteMessage").textContent = msg;
        document.getElementById("deleteModal").classList.remove("hidden");
    }

    function closeDeleteModal() {
        deleteTargetId = null;
        deleteType = null;
        document.getElementById("deleteModal").classList.add("hidden");
    }

    function showToastSuccess(message = "Successfully Removed.") {
        const toast = document.getElementById("toastSuccess");
        toast.textContent = message;
        toast.classList.remove("opacity-0");
        setTimeout(() => toast.classList.add("opacity-0"), 2000);
    }

    document.addEventListener('DOMContentLoaded', function() {

        updateAllTimeAgo();

        document.body.addEventListener('click', function(e) {

            if (e.target.closest('.toggle-reply')) {
                const btn = e.target.closest('.toggle-reply');
                let form = null;

                const replyContainer = btn.closest('.reply-container');

                if (replyContainer) {
                    form = replyContainer.querySelector('.reply-form');
                } else {
                    const commentBlock = btn.closest('.comment-block');
                    if (commentBlock) {
                        form = commentBlock.querySelector('.reply-form');
                    }
                }

                if (form) {
                    form.classList.toggle('hidden');
                    if (!form.classList.contains('hidden')) {
                        const textarea = form.querySelector('textarea');
                        if (textarea) textarea.focus();
                    }
                }
            }

            if (e.target.closest('.show-more-replies')) {
                const btn = e.target.closest('.show-more-replies');
                const section = btn.closest('.replies-section');
                const hiddenDiv = section.querySelector('.hidden-replies');
                const count = btn.dataset.count;

                if (hiddenDiv) {
                    hiddenDiv.classList.toggle('hidden');
                    const isHidden = hiddenDiv.classList.contains('hidden');

                    if (isHidden) {
                        btn.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg> Lihat ${count} balasan`;
                    } else {
                        btn.innerHTML = `<svg class="w-3 h-3 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg> Sembunyikan`;
                    }
                }
            }
        });



        document.querySelectorAll('.reply-form-data').forEach(form => {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);

                const endpoint = '<?= BASEURL ?>/comment/reply-topic';

                try {
                    const res = await fetch(endpoint, {
                        method: 'POST',
                        body: formData
                    });
                    const text = await res.text();
                    try {
                        const data = JSON.parse(text);
                        if (data.success) location.reload();
                        else alert(data.message || "Gagal mengirim balasan");
                    } catch (e) {
                        location.reload();
                    }
                } catch (err) {
                    console.error(err);
                    alert("Terjadi kesalahan saat mengirim balasan.");
                }
            });
        });

        const deleteBtn = document.getElementById("deleteConfirmBtn");
        if (deleteBtn) {
            deleteBtn.onclick = async function() {
                if (!deleteTargetId || !deleteType) return;

                const endpoint = deleteType === "comment" ?
                    "<?= BASEURL ?>/comment/deleteComment-topic" :
                    "<?= BASEURL ?>/comment/deleteReply-topic";

                const formData = new URLSearchParams();
                if (deleteType === "comment") formData.append("comment_id", deleteTargetId);
                else formData.append("reply_id", deleteTargetId);

                try {
                    const res = await fetch(endpoint, {
                        method: "POST",
                        body: formData
                    });
                    const result = await res.json();

                    if (result.success) {
                        closeDeleteModal();
                        showToastSuccess();
                        setTimeout(() => location.reload(), 800);
                    } else {
                        alert(result.message || "Gagal menghapus");
                    }
                } catch (err) {
                    console.error(err);
                    alert("Terjadi kesalahan server");
                }
            };
        }
    });
</script>