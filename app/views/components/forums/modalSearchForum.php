<div id="searchModal" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">
    <div class="lg:w-[50%] md:w-[60%] w-[80%]  bg-white h-[80%] rounded-3xl p-4">
        <div class="flex justify-between">
            <h1 class="font-semibold text-xl">Search Forum</h1>
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
                <p class="font-medium text-gray-700 mb-2" id="forumListTitle">Joined forums</p>
                <div id="forumListContainer">
                    <?php if (!empty($joinedForums)): ?>
                        <?php foreach ($joinedForums as $forum): ?>
                            <?php
                            $isActive = ($activeChatId === $forum['ID']) ? 'active' : '';
                            ?>
                            <a href="<?php echo BASEURL; ?>/forums/chat/<?php echo $forum['ID']; ?>" class="chats-card group <?php echo $isActive; ?> last:pb-8">
                                <div class="flex items-start gap-3 my-3 border border-gray-200 rounded-2xl p-3 hover:bg-gray-50 cursor-pointer">
                                    <img src="<?php echo !empty($forum['PATH_PHOTO'])
                                                    ? BASEURL . '/storage/forums/photos/' . $forum['PATH_PHOTO']
                                                    : BASEURL . '/src/asset/image/default.png'; ?>"
                                        alt="" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-black font-bold mb-1 truncate"><?php echo htmlspecialchars($forum['NAME']); ?></p>
                                        <p class="text-gray-600 text-sm truncate"> <?php echo htmlspecialchars($forum['ABOUT'] ?? 'No description yet'); ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="h-full">
                            <p class="text-sm text-gray-900 text-center font-semibold h-[50vh] flex justify-center items-center">No forums found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/components/forums/modalAccsesKey.php'; ?>


<script>
    const BASEURL = '<?php echo BASEURL; ?>'

    function debounce(func, delay = 300) {
        let timeout;
        return (...args) => {
            clearTimeout(timeout);
            timeout = setTimeout(() => func(...args), delay);
        };
    }

    const modal = document.getElementById('searchModal');
    const openBtn = document.getElementById('openModalBtn');
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
    const keyWornNotif = document.getElementById("keyWrongNotif")

    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add("flex");
    });

    closeBtn.addEventListener('click', () => {
        modal.classList.remove('flex');
        modal.classList.add("hidden");
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    function renderResults(data) {
        forumListContainer.innerHTML = '';

        if (data.length > 0) {
            data.forEach(forum => {
                const defaultPhoto = `${BASEURL}/src/asset/image/default.png`;
                const photoUrl = forum.PATH_PHOTO ? `${BASEURL}/storage/forums/photos/${forum.PATH_PHOTO}` : defaultPhoto;
                const isJoined = forum.IS_MEMBER;

                const cardContentHTML = `
                <div class="flex items-start gap-3">
                    <img src="${photoUrl}" alt="Forum Photo" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-black font-bold mb-1 truncate">${forum.NAME}</p>
                        <p class="text-gray-600 text-sm truncate">${forum.ABOUT || ''}</p>
                    </div>
                    ${!isJoined ? `
                        <button 
                            class="join-forum-btn bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600 self-center flex-shrink-0"
                            data-forum-id="${forum.ID}"
                            data-is-private="${forum.IS_PRIVATE}">
                            Join
                            ${forum.IS_PRIVATE  ? `
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mb-1.5 inline-block ml-1">
                                    <path fill-rule="evenodd" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7V4.5A3.5 3.5 0 0 0 8 1Zm2 3.5V7h-4V4.5a2 2 0 1 1 4 0Z" clip-rule="evenodd" />
                                </svg>
                            ` : ''}
                        </button>
                    ` : ''}
                </div>
            `;

                let finalElementHTML = '';
                if (isJoined) {
                    finalElementHTML = `
                    <a href="${BASEURL}/forums/chat/${forum.ID}" class="block my-3 border border-gray-200 rounded-2xl p-3 hover:bg-gray-50 cursor-pointer">
                        ${cardContentHTML}
                    </a>
                `;
                } else {
                    finalElementHTML = `
                    <div class="my-3 border border-gray-200 rounded-2xl p-3">
                        ${cardContentHTML}
                    </div>
                `;
                }

                forumListContainer.innerHTML += finalElementHTML;
            });
        } else {
            forumListContainer.innerHTML = '<p class="text-gray-500 text-center mt-8">Forum tidak ditemukan.</p>';
        }
    }

    function performSearch(query) {
        if (query.trim() === "") {
            console.log("Kosong, jangan cari dulu");
            forumListContainer.innerHTML = initialForumListHTML;
            forumListTitle.textContent = "Joined forums";
            return;
        }

        forumListTitle.textContent = "Search Results";
        forumListContainer.innerHTML = `
                <div id="loading-indicator" class="flex items-center justify-center h-full">
                    <div class="text-center">
                    <svg
                        class="animate-spin h-8 w-8 text-blue-600 mx-auto"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                        ></circle>
                        <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 
                            5.291A7.962 7.962 0 014 12H0c0 3.042 
                            1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    </div>
                </div>
                `;

        console.log("Mencari forum dengan kata kunci:", query);

        fetch(`${BASEURL}/forums/search?q=${encodeURIComponent(query)}`)
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

    async function handleJoinForum(forumId, buttonElement, accessKey = null) {
        const submitButton = accessKeyForm.querySelector('button[type="submit"]');

        if (buttonElement) buttonElement.disabled = true;
        if (submitButton) submitButton.disabled = true;
        if (buttonElement) buttonElement.textContent = 'Joining...';

        keyWrongNotif.classList.add("hidden");

        try {
            const formData = new URLSearchParams();
            formData.append('forum_id', forumId);
            if (accessKey) {
                formData.append('access_key', accessKey);
            }

            const response = await fetch(`${BASEURL}/forums/join`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData,
            });

            const result = await response.json();

            if (response.ok && result.success) {
                if (result.redirectUrl) {
                    closeAccessKeyModal();
                    window.location.href = result.redirectUrl;
                }
            } else {
                throw new Error(result.message || 'Gagal bergabung dengan forum.');
            }

        } catch (error) {
            console.error("Join Error:", error.message);

            keyWrongNotif.textContent = error.message;
            keyWrongNotif.classList.remove("hidden");

            setTimeout(() => {
                keyWrongNotif.classList.add("hidden");
            }, 3000);

            if (buttonElement) {
                buttonElement.disabled = false;

                const isPrivate = buttonElement.dataset.isPrivate === '1';
                let buttonContent = 'Join';

                if (isPrivate) {
                    const lockIconSVG = `
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4 mb-1.5 inline-block ml-1">
                            <path fill-rule="evenodd" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V7A1.5 1.5 0 0 0 3 8.5v5A1.5 1.5 0 0 0 4.5 15h7a1.5 1.5 0 0 0 1.5-1.5v-5A1.5 1.5 0 0 0 11.5 7V4.5A3.5 3.5 0 0 0 8 1Zm2 3.5V7h-4V4.5a2 2 0 1 1 4 0Z" clip-rule="evenodd" />
                        </svg>
                    `;
                    buttonContent += lockIconSVG;
                }

                buttonElement.innerHTML = buttonContent;
            }

            if (submitButton) {
                submitButton.disabled = false;
            }
        }
    }

    function openAccessKeyModal(forumId) {
        forumIdToJoinInput.value = forumId;
        accessKeyModal.classList.remove('hidden');
        accessKeyModal.classList.add('flex');
    }

    function closeAccessKeyModal() {
        accessKeyModal.classList.add('hidden');
        accessKeyModal.classList.remove('flex');
        accessKeyInput.value = '';
    }

    closeKeyModalBtn.addEventListener('click', closeAccessKeyModal);
    accessKeyModal.addEventListener('click', (e) => {
        if (e.target === accessKeyModal) closeAccessKeyModal();
    });

    accessKeyForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const forumId = forumIdToJoinInput.value;
        const accessKey = accessKeyInput.value;

        const originalButton = forumListContainer.querySelector(`[data-forum-id="${forumId}"]`);

        handleJoinForum(forumId, originalButton, accessKey);
    });


    forumListContainer.addEventListener('click', function(event) {
        const joinButton = event.target.closest('.join-forum-btn');

        if (joinButton) {
            const forumId = joinButton.dataset.forumId;
            const isPrivate = joinButton.dataset.isPrivate;
            if (isPrivate === '1') {
                openAccessKeyModal(forumId);
            } else {
                handleJoinForum(forumId, joinButton);
            }
        }
    });
</script>