<?php
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
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Homepage | Post @<?= $post['USERNAME'] ?></title>
</head>

<body>
    <main class="w-full h-screen overflow-y-auto border-gray-200 hide-scrollbar relative z-[999]">
        <div class="sticky top-0 z-[999] bg-white w-full px-5 py-3 mb-4 border-b border-gray-200">
            <button onclick="window.history.back()" class="flex items-center gap-3 text-black font-semibold cursor-pointer">
                <img src="<?php echo BASEURL . '/src/asset/icons/left-arrow-svgrepo-com.svg'; ?>" alt="icon" class="w-6 h-6">
                <h1 class="text-xl">Back</h1>
            </button>
        </div>
        <div class="max-w-xl mx-auto px-5 md:p-0 mb-20 lg:mb-10">
            <div class="max-w-xl w-full mx-auto">
                <?php require_once 'app/views/components/postingan/replyPost.php'; ?>

                <div class="sticky top-16 max-w-xl z-10">
                    <form id="comment-form" method="POST" class="bg-white/60 backdrop-blur border text-black border-gray-200 p-4 rounded-2xl my-2">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['POST_ID']) ?>">
                        <div class="flex flex-col sm:flex-row items-start space-y-3 sm:space-y-0 sm:space-x-3 relative">
                            <div class="flex-shrink-0">
                                <img src="<?= !empty($_SESSION['path_photo'])
                                                ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                                : BASEURL . '/src/asset/image/default.png' ?>" alt="Your Profile" class="w-12 h-12 rounded-full">
                            </div>
                            <div class="flex-1 w-full relative">
                                <textarea name="message" rows="2"
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

                <?php if (empty($comments)): ?>
                    <div class="bg-white text-center text-gray-500 border border-gray-200 p-6 rounded-2xl mt-4">
                        No comments yet.
                    </div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="bg-white text-black border border-gray-200 p-4 rounded-2xl my-4 comment-block">

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
                                    <?php
                                    $profileUrl = ($comment['USER_ID'] === $_SESSION['user_id'])
                                        ? BASEURL . "/profile"
                                        : BASEURL . "/homepage/user/profile/" . htmlspecialchars($comment['USER_ID']);
                                    ?>

                                    <a href="<?= $profileUrl ?>">
                                        <span class="text-gray-500">@<?= htmlspecialchars($comment['USERNAME']) ?></span>
                                    </a>
                                </div>
                            </div>

                            <div class="mt-4 text-gray-800 text-sm sm:text-base break-words">
                                <p><?= nl2br(htmlspecialchars(loadLobSafe($comment['MESSAGE']) ?? '')) ?></p>
                            </div>

                            <div class="mt-4 flex items-center justify-between text-gray-500 text-xs sm:text-sm gap-3 ">

                                <div class="flex gap-3 sm:gap-4 items-center">
                                    <p class="text-gray-400 time-ago" data-time="<?= htmlspecialchars($comment['CREATED_AT']) ?>"></p>
                                    <button class="toggle-reply text-gray-600 hover:text-blue-600 transition duration-300 font-semibold cursor-pointer" data-username="<?= htmlspecialchars($comment['USERNAME']) ?>">
                                        Reply
                                    </button>
                                </div>

                                <?php
                                $canDelete = (
                                    $_SESSION['user_id'] == $comment['USER_ID'] ||
                                    $_SESSION['user_id'] == $post['USER_ID'] ||
                                    $_SESSION['role'] == 'ADMIN'
                                );

                                if ($canDelete):
                                ?>
                                    <button class="text-red-500 hover:text-red-600 font-semibold transition cursor-pointer"
                                        onclick="openDeleteModal('comment', '<?= $comment['COMMENT_ID'] ?>')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="hidden reply-form">
                                <form method="POST" class="reply-form-data bg-white text-black border-t border-gray-200 p-3 sm:p-4 rounded-2xl my-2">
                                    <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['COMMENT_ID']) ?>">
                                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['POST_ID']) ?>">

                                    <div class="flex flex-col sm:flex-row sm:items-start space-y-3 sm:space-y-0 sm:space-x-3 relative">
                                        <div class="flex items-start space-x-3 w-full sm:flex-1">
                                            <div class="flex-shrink-0">
                                                <img src="<?= !empty($_SESSION['path_photo'])
                                                                ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                                                : BASEURL . '/src/asset/image/default.png' ?>" alt="Your Profile" class="w-10 h-10 rounded-full">
                                            </div>
                                            <div class="flex-1 w-full relative">
                                                <textarea name="message" rows="1" maxlength="150"
                                                    class="reply-textarea w-full hide-scrollbar bg-transparent text-sm sm:text-base text-gray-800 ring-1 placeholder-gray-500 border-none focus:ring-1 focus:outline-blue-600 rounded-2xl p-2 sm:p-2.5 ps-3"></textarea>
                                                <div class="mention-dropdown hidden absolute bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto z-50 w-full mt-1"></div>
                                            </div>
                                        </div>

                                        <div class="w-full sm:w-auto flex items-center justify-end">
                                            <button type="submit"
                                                class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors">
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
                                    <button class="show-more-replies text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center gap-2 mb-3 transition-colors cursor-pointer"
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

                                            include __DIR__ . '/reply-item.php';
                                            ?>

                                            <?php if (!empty($parentReply['children'])): ?>
                                                <?php foreach ($parentReply['children'] as $childReply): ?>
                                                    <?php
                                                    $reply = $childReply;
                                                    $isChild = true;

                                                    include __DIR__ . '/reply-item.php';
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
        </div>
    </main>

    <div id="deleteModal"
        class="fixed inset-0 backdrop-blur-sm bg-black/50 z-[99999] flex items-center justify-center hidden ">

        <div class="bg-white rounded-xl shadow-lg w-80 p-6">
            <h2 class="text-lg font-semibold mb-2">Deletion Confirmation</h2>
            <p id="deleteMessage" class="text-sm text-gray-600 mb-4"></p>

            <div class="flex justify-end gap-3">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 cursor-pointer">
                    Cancel
                </button>

                <button id="deleteConfirmBtn"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 cursor-pointer">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <div id="toastSuccess"
        class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition-all duration-300 z-50">
        Delete successful.
    </div>

    <script>
        let deleteTargetId = null;
        let deleteType = null;

        function openDeleteModal(type, id) {
            deleteTargetId = id;
            deleteType = type;

            const msg = type === "comment" ?
                "Delete this comment and all replies to it?" :
                "Delete this reply?";

            document.getElementById("deleteMessage").textContent = msg;

            document.getElementById("deleteModal").classList.remove("hidden");
        }

        function closeDeleteModal() {
            deleteTargetId = null;
            deleteType = null;
            document.getElementById("deleteModal").classList.add("hidden");
        }

        function showToastSuccess(message = "Delete Successful") {
            const toast = document.getElementById("toastSuccess");
            toast.textContent = message;

            toast.classList.remove("opacity-0");
            toast.classList.add("opacity-100");

            setTimeout(() => {
                toast.classList.add("opacity-0");
                toast.classList.remove("opacity-100");
            }, 1500);
        }

        console.log('Comments data:', <?= json_encode($comments) ?>);
        let users = [];

        async function fetchUsers() {
            try {
                const response = await fetch('<?= BASEURL ?>/get-all-user');
                if (!response.ok) throw new Error(`Server responded with ${response.status}`);

                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("Response bukan JSON. Periksa URL endpoint.");
                }

                const data = await response.json();
                if (data.success && Array.isArray(data.data)) {
                    users = data.data;
                } else {
                    console.error('Failed to get user data from response:', data);
                }
            } catch (err) {
                console.error('Failed to fetch users for mentions:', err);
            }
        }

        document.getElementById("deleteConfirmBtn").onclick = async function() {
            if (!deleteTargetId || !deleteType) return;

            const endpoint = deleteType === "comment" ?
                "<?= BASEURL ?>/comment/deleteComment" :
                "<?= BASEURL ?>/comment/deleteReply";

            const formData = new URLSearchParams();

            if (deleteType === "comment") {
                formData.append("comment_id", deleteTargetId);
            } else {
                formData.append("reply_id", deleteTargetId);
            }

            const res = await fetch(endpoint, {
                method: "POST",
                body: formData
            });

            const result = await res.json();

            if (result.success) {
                closeDeleteModal();
                showToastSuccess();

                setTimeout(() => {
                    location.reload();
                }, 800);
            } else {
                alert(result.message);
            }
        };

        fetchUsers();

        function initMentionFeature(textarea) {
            const mentionDropdown = textarea.closest('.flex, .flex-1').querySelector('.mention-dropdown');
            if (!mentionDropdown) return;

            let mentionStartPos = -1;
            let mentionQuery = '';

            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                const maxHeight = 192;
                this.style.height = Math.min(this.scrollHeight, maxHeight) + 'px';
                this.style.overflowY = this.scrollHeight > maxHeight ? 'auto' : 'hidden';

                handleMention();
            });

            function handleMention() {
                const cursorPos = textarea.selectionStart;
                const textBefore = textarea.value.substring(0, cursorPos);
                const lastAt = textBefore.lastIndexOf('@');

                if (lastAt !== -1) {
                    const query = textBefore.substring(lastAt + 1);
                    if (!query.includes(' ') && !query.includes('\n')) {
                        mentionStartPos = lastAt;
                        mentionQuery = query.toLowerCase();
                        showMentionSuggestions(mentionQuery);
                        return;
                    }
                }
                hideMentionDropdown();
            }

            function showMentionSuggestions(query) {
                if (!users || users.length === 0) return hideMentionDropdown();

                const filtered = users.filter(u =>
                    u.FULL_NAME.toLowerCase().includes(query) ||
                    u.USERNAME.toLowerCase().includes(query)
                );

                if (!filtered.length) return hideMentionDropdown();

                mentionDropdown.innerHTML = filtered.map(u => {
                    const roleBadges = {
                        'ADMIN': 'bg-red-100 text-red-700 border-red-200',
                        'MAHASISWA': 'bg-blue-100 text-blue-700 border-blue-200',
                        'DOSEN': 'bg-green-100 text-green-700 border-green-200',
                    };

                    const role = u.ROLE;
                    const badgeColor = roleBadges[role] || 'bg-gray-100 text-gray-700 border-gray-200';

                    return `
                    <div class="mention-item group flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2 sm:py-3 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 cursor-pointer transition-all duration-200 border-b border-gray-100 last:border-b-0"
                        data-username="${u.USERNAME}" data-name="${u.FULL_NAME}">
                        <div class="relative">
                            <div class="size-9 sm:size-11 rounded-full overflow-hidden flex-shrink-0 ring-2 ring-gray-200 group-hover:ring-blue-400 transition-all duration-200">
                                <img src="${u.PATH_PHOTO ? `<?= BASEURL; ?>/storage/users/photos/${u.PATH_PHOTO}` : `<?= BASEURL; ?>/src/asset/image/default.png`}"
                                    class="w-full h-full object-cover" 
                                    alt="${u.FULL_NAME}">
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <div class="font-semibold text-sm sm:text-base text-gray-900 truncate group-hover:text-blue-600 transition-colors">
                                    ${u.FULL_NAME}
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs sm:text-sm text-gray-500 truncate">@${u.USERNAME}</span>
                                <span class="px-1.5 sm:px-2 py-0.5 text-xs font-medium rounded-full border ${badgeColor} flex-shrink-0">
                                    ${role.charAt(0).toUpperCase() + role.slice(1)}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
                }).join('');

                mentionDropdown.classList.remove('hidden');

                mentionDropdown.querySelectorAll('.mention-item').forEach(item => {
                    item.addEventListener('click', () => {
                        insertMention(item.dataset.username);
                    });
                });
            }

            function insertMention(username) {
                const before = textarea.value.substring(0, mentionStartPos);
                const after = textarea.value.substring(textarea.selectionStart);
                textarea.value = before + `@${username} ` + after;
                const newPos = mentionStartPos + username.length + 2;
                textarea.setSelectionRange(newPos, newPos);
                hideMentionDropdown();
                textarea.focus();
            }

            function hideMentionDropdown() {
                mentionDropdown.classList.add('hidden');
                mentionStartPos = -1;
                mentionQuery = '';
            }

            document.addEventListener('click', e => {
                if (!mentionDropdown.contains(e.target) && e.target !== textarea) {
                    hideMentionDropdown();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const mainTextarea = document.querySelector('.comment-textarea');
            if (mainTextarea) {
                initMentionFeature(mainTextarea);
            }

            document.querySelectorAll('.reply-textarea').forEach(textarea => {
                initMentionFeature(textarea);
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.show-more-replies')) {
                    const button = e.target.closest('.show-more-replies');
                    const repliesSection = button.closest('.replies-section');
                    const hiddenReplies = repliesSection.querySelector('.hidden-replies');

                    if (hiddenReplies) {
                        hiddenReplies.classList.toggle('hidden');

                        const count = button.dataset.count;
                        const text = button.dataset.text;

                        if (hiddenReplies.classList.contains('hidden')) {
                            button.innerHTML = `
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                Show ${count} more ${text}
                            `;
                        } else {
                            button.innerHTML = `
                                <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                                Show less
                            `;
                        }
                    }
                }

                if (e.target.classList.contains('toggle-reply')) {
                    const targetUsername = e.target.dataset.username;

                    const commentBlock = e.target.closest('.comment-block, .comment-container, .reply-container');
                    const replyForm = commentBlock.querySelector('.reply-form');

                    if (replyForm) {
                        replyForm.classList.toggle('hidden');
                        const textarea = replyForm.querySelector('textarea');

                        if (textarea) {
                            if (targetUsername) {
                                textarea.placeholder = `Reply to @${targetUsername}`;
                                textarea.dataset.replyTo = targetUsername;
                            }

                            if (!textarea.dataset.mentionInitialized) {
                                initMentionFeature(textarea);
                                textarea.dataset.mentionInitialized = 'true';
                            }

                            textarea.focus();
                        }
                    }
                }
            });

            const commentForm = document.querySelector('#comment-form');
            commentForm?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(commentForm);
                const res = await fetch('<?= BASEURL ?>/comment/add', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success) location.reload();
                else alert(data.message);
            });

            document.querySelectorAll('.reply-form-data').forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();

                    const textarea = form.querySelector('textarea[name="message"]');
                    const replyTo = textarea.dataset.replyTo;

                    if (replyTo && !textarea.placeholder.startsWith(`@${replyTo}`)) {
                        textarea.placeholder = `@${replyTo} ${textarea.value}`;
                    }

                    const formData = new FormData(form);

                    const res = await fetch('<?= BASEURL ?>/comment/reply', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await res.json();
                    if (data.success) location.reload();
                    else alert(data.message);
                });
            });
        });

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

        document.querySelectorAll('.time-ago').forEach(el => {
            const t = el.dataset.time;
            el.textContent = timeAgo(t);
        });
    </script>

</body>

</html>