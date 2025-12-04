<div id="searchModal" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">
    <div class="lg:w-[50%] md:w-[60%] w-[90%]  bg-white h-[80%] rounded-3xl p-4">
        <div class="flex justify-between">
            <h1 class="font-semibold text-xl">Search Group</h1>
            <button class="cursor-pointer" id="closeModalBtn">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/close-circle-grey.svg" class="w-7 h-7" alt="Remove">
            </button>
        </div>

        <div class="w-full my-3">
            <label for="searchLive" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input
                    type="search"
                    id="searchLive"
                    class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 
                           focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search..."
                    required />
            </div>
        </div>

        <div class="mt-4">
            <div class="overflow-y-auto max-h-[58vh] pr-2 hide-scrollbar">
                <p class="font-medium text-gray-700 mb-2" id="forumListTitle">Joined groups</p>
                <div id="forumListContainer">
                    <?php if (!empty($joinedGroupChats)): ?>
                        <?php foreach ($joinedGroupChats as $groupChat): ?>
                            <?php
                            $isActive = ($activeChatId === $groupChat['ID'])
                                ? 'border-2 border-blue-600 bg-blue-50'
                                : 'border border-gray-200';

                            $isOwner = ($groupChat['OWNER_ID'] == $_SESSION['user_id']);
                            ?>

                            <a href="<?= BASEURL; ?>/groups/chat/<?= $groupChat['ID']; ?>"
                                class="chats-card group last:pb-8">
                                <div class="flex items-start gap-3 my-3 rounded-2xl p-3 hover:bg-gray-50 cursor-pointer <?= $isActive; ?>">

                                    <div class="w-16 h-16 rounded-xl overflow-hidden flex items-center justify-center bg-gray-200 flex-shrink-0">
                                        <?php if (!empty($groupChat['PATH_PHOTO'])): ?>
                                            <img src="<?= BASEURL . '/storage/groups/photos/' . $groupChat['PATH_PHOTO']; ?>"
                                                alt="Group Photo"
                                                class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <span class="text-white font-bold text-lg w-full h-full flex items-center justify-center"
                                                style="background-color: #EF5DA8;">
                                                <?= strtoupper(substr($groupChat['NAME'], 0, 2)); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="md:flex md:justify-between">
                                            <p class="text-black font-bold mb-1 truncate flex items-center gap-2">
                                                <?= htmlspecialchars($groupChat['NAME']); ?>
                                            </p>
                                            <?php if ($isOwner): ?>
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm border border-blue-400">
                                                    My Group
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <p class="text-gray-600 text-sm truncate">
                                            <?= htmlspecialchars($groupChat['ABOUT'] ?? 'No description yet'); ?>
                                        </p>
                                    </div>

                                </div>
                            </a>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <div class="h-full">
                            <p class="text-sm text-gray-900 text-center font-semibold h-[50vh] flex justify-center items-center">No groups found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/components/groups/modalAccsesKey.php'; ?>

<script>
    if (typeof BASEURL === 'undefined') {
        var BASEURL = '<?php echo BASEURL; ?>';
    }

    function debounce(func, delay = 300) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), delay);
        };
    }

    const modalSearch = document.getElementById('searchModal');
    const openBtn = document.getElementById('openModalBtnSearch');
    const closeBtn = document.getElementById('closeModalBtn');
    const liveSearch = document.getElementById("searchLive");
    const forumListContainer = document.getElementById('forumListContainer');
    const forumListTitle = document.getElementById('forumListTitle');
    const initialForumListHTML = forumListContainer.innerHTML;
    const initialTitle = forumListTitle.textContent;
    const accessKeyModal = document.getElementById('accessKeyModal');
    const closeKeyModalBtn = document.getElementById('closeKeyModalBtn');
    const accessKeyForm = document.getElementById('accessKeyForm');
    const forumIdToJoinInput = document.getElementById('forumIdToJoin');
    const accessKeyInput = document.getElementById('access_key');
    const keyWornNotif = document.getElementById("keyWrongNotif");

    openBtn.addEventListener('click', () => {
        modalSearch.classList.remove('hidden');
        modalSearch.classList.add("flex");
    });

    closeBtn.addEventListener('click', () => {
        modalSearch.classList.remove('flex');
        modalSearch.classList.add("hidden");
        // Reset search when closing
        liveSearch.value = '';
        forumListContainer.innerHTML = initialForumListHTML;
        forumListTitle.textContent = initialTitle;
    });

    modalSearch.addEventListener('click', (e) => {
        if (e.target === modalSearch) {
            closeBtn.click();
        }
    });

    function renderResults(data) {
        forumListContainer.innerHTML = '';

        if (data.length > 0) {
            data.forEach(groupChat => {
                const defaultPhotoHTML = `<span class="text-white font-bold text-lg w-16 h-16 flex items-center justify-center rounded-xl" style="background-color: #EF5DA8;">
                                        ${groupChat.NAME.substring(0, 2).toUpperCase()}
                                      </span>`;

                const photoContent = groupChat.PATH_PHOTO ?
                    `<img src="${BASEURL}/storage/groups/photos/${groupChat.PATH_PHOTO}" alt="Forum Photo" class="w-16 h-16 rounded-xl object-cover flex-shrink-0" />` :
                    defaultPhotoHTML;

                const isJoined = groupChat.IS_MEMBER == 1 || groupChat.IS_MEMBER === true;
                const isPrivate = groupChat.IS_PRIVATE == 1 || groupChat.IS_PRIVATE === true;

                const lockIcon = isPrivate ? `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mb-1.5 inline-block ml-1">
                    <path fill-rule="evenodd" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7V4.5A3.5 3.5 0 0 0 8 1Zm2 3.5V7h-4V4.5a2 2 0 1 1 4 0Z" clip-rule="evenodd" />
                </svg>
            ` : '';

                const cardContentHTML = `
                <div class="flex items-start gap-3">
                    ${photoContent}
                    <div class="flex-1 min-w-0">
                        <p class="text-black font-bold mb-1 truncate">${groupChat.NAME}</p>
                        <p class="text-gray-600 text-sm truncate">${groupChat.ABOUT || 'No description'}</p>
                    </div>
                    ${!isJoined ? `
                        <button 
                            class="join-forum-btn bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600 self-center flex-shrink-0 whitespace-nowrap cursor-pointer"
                            data-forum-id="${groupChat.ID}"
                            data-is-private="${isPrivate ? '1' : '0'}">
                            Join${lockIcon}
                        </button>
                    ` : `
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-1 rounded-full self-center flex-shrink-0">
                            Joined
                        </span>
                    `}
                </div>`;

                const finalElementHTML = isJoined ?
                    `<a href="${BASEURL}/groups/chat/${groupChat.ID}" class="block my-3 border border-gray-200 rounded-2xl p-3 hover:bg-gray-50 cursor-pointer">
                    ${cardContentHTML}
                </a>` :
                    `<div class="my-3 border border-gray-200 rounded-2xl p-3">
                    ${cardContentHTML}
                </div>`;

                forumListContainer.innerHTML += finalElementHTML;
            });
        } else {
            forumListContainer.innerHTML = '<p class="text-gray-500 text-center mt-8">Group tidak ditemukan.</p>';
        }
    }

    function performSearch(query) {
        if (query.trim() === "") {
            forumListContainer.innerHTML = initialForumListHTML;
            forumListTitle.textContent = initialTitle;
            return;
        }

        forumListTitle.textContent = "Search Results";
        forumListContainer.innerHTML = `
        <div id="loading-indicator" class="flex items-center justify-center h-full py-8">
            <div class="text-center">
                <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    `;

        fetch(`${BASEURL}/groups/search?q=${encodeURIComponent(query)}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                renderResults(data);
            })
            .catch(error => {
                console.error("Gagal melakukan pencarian:", error);
                forumListContainer.innerHTML = '<p class="text-red-500 text-center mt-8">Terjadi kesalahan saat mencari.</p>';
            });
    }

    const debouncedSearch = debounce((e) => {
        performSearch(e.target.value);
    }, 700);

    liveSearch.addEventListener('input', debouncedSearch);

    async function handleJoinForum(groupChatId, buttonElement, accessKey = null) {
        const submitButton = accessKeyForm.querySelector('button[type="submit"]');

        if (buttonElement) buttonElement.disabled = true;
        if (submitButton) submitButton.disabled = true;
        if (buttonElement) buttonElement.textContent = 'Joining...';

        keyWornNotif.classList.add("hidden");

        try {
            const formData = new URLSearchParams();
            formData.append('group_chat_id', groupChatId);
            if (accessKey) {
                formData.append('access_key', accessKey);
            }

            console.log('Sending join request:', {
                groupChatId,
                hasAccessKey: !!accessKey
            });

            const response = await fetch(`${BASEURL}/groups/join`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData,
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers.get('content-type'));

            // Get response text first for debugging
            const responseText = await response.text();
            console.log('Raw response:', responseText);

            // Try to parse as JSON
            let result;
            try {
                result = JSON.parse(responseText);
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                throw new Error('Server returned invalid response: ' + responseText.substring(0, 100));
            }

            console.log('Parsed result:', result);

            if (response.ok && result.success) {
                console.log('Join successful, redirecting...');

                // Close modals
                closeAccessKeyModal();
                modalSearch.classList.add('hidden');
                modalSearch.classList.remove('flex');

                // Small delay to ensure modals are closed
                setTimeout(() => {
                    // Redirect to the group chat
                    const redirectUrl = result.redirectUrl || `${BASEURL}/groups/chat/${groupChatId}`;
                    console.log('Redirecting to:', redirectUrl);
                    window.location.href = redirectUrl;
                }, 100);
            } else {
                throw new Error(result.message || 'Gagal bergabung dengan group.');
            }

        } catch (error) {
            console.error("Join Error:", error);

            keyWornNotif.textContent = error.message || 'Terjadi kesalahan';
            keyWornNotif.classList.remove("hidden");

            setTimeout(() => {
                keyWornNotif.classList.add("hidden");
            }, 3000);

            if (buttonElement) {
                buttonElement.disabled = false;
                const isPrivate = buttonElement.dataset.isPrivate === '1';

                const lockIcon = isPrivate ? `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mb-1.5 inline-block ml-1">
                    <path fill-rule="evenodd" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7V4.5A3.5 3.5 0 0 0 8 1Zm2 3.5V7h-4V4.5a2 2 0 1 1 4 0Z" clip-rule="evenodd" />
                </svg>
            ` : '';

                buttonElement.innerHTML = `Join${lockIcon}`;
            }

            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    }

    function openAccessKeyModal(groupChatId) {
        forumIdToJoinInput.value = groupChatId;
        accessKeyInput.value = '';
        keyWornNotif.classList.add("hidden");
        accessKeyModal.classList.remove('hidden');
        accessKeyModal.classList.add('flex');
    }

    function closeAccessKeyModal() {
        accessKeyModal.classList.add('hidden');
        accessKeyModal.classList.remove('flex');
        accessKeyInput.value = '';
        keyWornNotif.classList.add("hidden");
    }

    closeKeyModalBtn.addEventListener('click', closeAccessKeyModal);
    accessKeyModal.addEventListener('click', (e) => {
        if (e.target === accessKeyModal) closeAccessKeyModal();
    });

    accessKeyForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const groupChatId = forumIdToJoinInput.value;
        const accessKey = accessKeyInput.value.trim();

        if (!accessKey) {
            keyWornNotif.textContent = "Access key must not be empty";
            keyWornNotif.classList.remove("hidden");
            return;
        }

        const originalButton = forumListContainer.querySelector(`[data-forum-id="${groupChatId}"]`);
        handleJoinForum(groupChatId, originalButton, accessKey);
    });

    forumListContainer.addEventListener('click', function(event) {
        const joinButton = event.target.closest('.join-forum-btn');

        if (joinButton) {
            event.preventDefault();
            const groupChatId = joinButton.dataset.forumId;
            const isPrivate = joinButton.dataset.isPrivate;

            if (isPrivate === '1') {
                openAccessKeyModal(groupChatId);
            } else {
                handleJoinForum(groupChatId, joinButton);
            }
        }
    });
</script>