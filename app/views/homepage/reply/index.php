
<?php
function loadLobSafe($data)
{
    return ($data instanceof OCILob) ? $data->load() : $data;
}
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Home Page | Positingan @<?= $post['USERNAME'] ?></title>
</head>

<body>
    <main class="w-full h-screen overflow-y-auto border-gray-200 hide-scrollbar relative z-[999]">
        <div class="sticky top-0 z-10 bg-white w-full px-5 py-3 mb-4 border-b border-gray-200">
            <button onclick="window.history.back()" class="flex items-center gap-3 text-black font-semibold cursor-pointer">
                <img src="<?php echo BASEURL . '/src/asset/icons/left-arrow-svgrepo-com.svg'; ?>" alt="icon" class="w-6 h-6">
                <h1 class="text-xl">Post</h1>
            </button>
        </div>
        <div class="max-w-xl mx-auto px-5 md:p-0 mb-20 md:mb-10">
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
                                    placeholder="Add Comment...."></textarea>
                                <div class="mention-dropdown hidden absolute bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto z-50 w-full mt-1"></div>
                            </div>
                            <div class="w-full sm:w-auto flex items-center justify-end sm:mt-2">
                                <button type="submit"
                                    class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2 rounded-full transition-colors">
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

                            <div class="mt-4 text-gray-800 text-sm sm:text-base break-words">
                                <p><?= nl2br($comment['MESSAGE_FORMATTED'] ?? '') ?></p>
                            </div>

                            <div class="mt-4 flex items-center text-gray-500 text-xs sm:text-sm gap-3 sm:gap-4">
                                <p class="text-gray-400 time-ago" data-time="<?= htmlspecialchars($comment['CREATED_AT']) ?>"></p>
                                <button class="toggle-reply text-gray-600 hover:text-blue-600 transition duration-300 font-semibold">
                                    Replys
                                </button>
                            </div>

                            <div class="hidden reply-form">
                                <form method="POST" class="reply-form-data bg-white text-black border-t border-gray-200 p-3 sm:p-4 rounded-2xl my-2">
                                    <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['ID'] ?? $comment['COMMENT_ID']) ?>">
                                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['POST_ID']) ?>">
                                    <div class="flex flex-col sm:flex-row items-start space-y-3 sm:space-y-0 sm:space-x-3 relative">
                                        <div class="flex-shrink-0">
                                            <img src="<?= !empty($_SESSION['path_photo'])
                                                            ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                                            : BASEURL . '/src/asset/image/default.png' ?>" alt="Your Profile" class="w-10 h-10 rounded-full">
                                        </div>
                                        <div class="flex-1 w-full relative">
                                            <textarea name="message" rows="1"
                                                class="reply-textarea w-full hide-scrollbar bg-transparent text-sm sm:text-base text-gray-800 ring-1 placeholder-gray-500 border-none focus:ring-1 focus:outline-blue-600 rounded-2xl p-2 sm:p-2.5 ps-3"></textarea>
                                            <div class="mention-dropdown hidden absolute bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto z-50 w-full mt-1"></div>
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

                                            <div class="mt-2 ml-1 pl-8 sm:pl-12 text-gray-800 text-sm sm:text-base break-words">
                                                <p><?= nl2br($cleanMsg ?? '') ?></p>
                                            </div>
                                            <div class="mt-3 ml-1 pl-8 sm:pl-12 flex items-center text-gray-500 text-xs sm:text-sm gap-3 sm:gap-4">
                                                <p class="text-gray-400 time-ago" data-time="<?= htmlspecialchars($reply['CREATED_AT']) ?>"></p>
                                                <button class="toggle-reply text-gray-600 hover:text-blue-600 transition duration-300 font-semibold">
                                                    Reply
                                                </button>
                                            </div>

                                            <div class="hidden reply-form ml-1 pl-8 sm:pl-12">
                                                <form method="POST" class="reply-form-data bg-white text-black border-t border-gray-200 p-3 sm:p-4 rounded-2xl my-2">
                                                    <input type="hidden" name="comment_id" value="<?= trim(htmlspecialchars($comment['COMMENT_ID'])) ?>">
                                                    <input type="hidden" name="parent_id" value="<?= isset($reply['REPLY_ID']) ? trim(htmlspecialchars($reply['REPLY_ID'])) : '' ?>">
                                                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['POST_ID']) ?>">


                                                    <div class="flex flex-col sm:flex-row items-start space-y-3 sm:space-y-0 sm:space-x-3 relative">
                                                        <div class="flex-shrink-0">
                                                            <img src="<?= !empty($_SESSION['path_photo'])
                                                                            ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                                                            : BASEURL . '/src/asset/image/default.png' ?>" alt="Your Profile" class="w-10 h-10 rounded-full">
                                                        </div>
                                                        <div class="flex-1 w-full relative">
                                                            <textarea name="message" rows="1"
                                                                class="reply-textarea w-full hide-scrollbar bg-transparent text-sm sm:text-base text-gray-800 ring-1 placeholder-gray-500 border-none focus:ring-1 focus:outline-blue-600 rounded-2xl p-2 sm:p-2.5 ps-3"
                                                                placeholder="Reply..."></textarea>
                                                            <div class="mention-dropdown hidden absolute bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto z-50 w-full mt-1"></div>
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
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
    </main>

    <script>
        console.log(<?= json_encode($comments) ?>);
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

        fetchUsers();

        function initMentionFeature(textarea) {
            const mentionDropdown = textarea.closest('.flex').querySelector('.mention-dropdown');
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
                if (e.target.classList.contains('toggle-reply')) {
                    const commentBlock = e.target.closest('.comment-block, .comment-container');
                    const replyForm = commentBlock.querySelector('.reply-form');
                    if (replyForm) {
                        replyForm.classList.toggle('hidden');
                        const textarea = replyForm.querySelector('textarea');
                        if (textarea) {
                            const usernameTag = e.target.closest('.comment-block, .comment-container')
                                ?.querySelector('span.text-gray-500')?.textContent?.trim().replace('@', '');

                            if (usernameTag) {
                                textarea.placeholder = `Reply to @${usernameTag}`;
                                textarea.dataset.replyTo = usernameTag;
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
            const now = new Date();
            const past = new Date(dateString);
            const diff = Math.floor((now - past) / 1000);

            if (diff < 60) return `${diff}s ago`;
            if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
            if (diff < 2592000) return `${Math.floor(diff / 86400)}d ago`;
            return past.toLocaleDateString();
        }

        document.querySelectorAll('.time-ago').forEach(el => {
            const t = el.dataset.time;
            el.textContent = timeAgo(t);
        });
    </script>

</body>

</html>
