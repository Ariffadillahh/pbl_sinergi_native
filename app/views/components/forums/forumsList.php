<aside id="forumsListSidebar" class="fixed lg:relative flex flex-col w-full max-w-[360px] lg:w-[360px] shrink-0 h-full border-r border-gray-100 bg-white overflow-hidden transition-all duration-300 ease-in-out z-20 -translate-x-full lg:translate-x-0 pb-[70px] lg:pb-0">
    <div id="Top-Bar" class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-6">
        <p class="text-2xl font-semibold">Your Forums</p>
        <?php

        if ($_SESSION['role'] === 'MAHASISWA' || $_SESSION['role'] === 'DOSEN' || $_SESSION['role'] === 'ADMIN') {
        ?>
            <ul class="flex gap-3">
                <li>
                    <button id="openModalBtn" class="size-11 flex shrink-0 cursor-pointer items-center justify-center rounded-xl bg-white p-[10px] ring-1 ring-gray-100 transition-all duration-300 hover:ring-1 hover:ring-blue-600">
                        <img src="<?php echo BASEURL; ?>/src/asset/icons/search-normal.svg" class="size-6" alt="icon">
                    </button>
                </li>
                <li>
                    <button id="AddForum" class="size-11 flex shrink-0 cursor-pointer items-center justify-center rounded-xl bg-white p-[10px] ring-1 ring-gray-100 transition-all duration-300 hover:ring-1 hover:ring-blue-600">
                        <img src="<?php echo BASEURL; ?>/src/asset/icons/icons8-plus.svg" class="size-6" alt="icon">
                    </button>
                </li>
            </ul>
        <?php
        }
        ?>
    </div>

    <div id="Menu" class="flex flex-1 flex-col gap-5 overflow-hidden p-5 pb-0">
        <div id="tabs-content-container" class="flex h-full flex-1 overflow-hidden">
            <div id="All" class="relative h-full w-full">
                <div class="flex h-full flex-col gap-1">
                    <p class="text-sm text-gray-500 pb-2 border-b-[1px] border-gray-200">All Forums (<?php echo count($joinedForums); ?>)</p>
                    <div id="Message-container" class="hide-scrollbar h-full w-full overflow-y-scroll">
                        <div class="flex w-full flex-col gap-1">
                            <?php if (!empty($joinedForums)): ?>
                                <?php foreach ($joinedForums as $forum): ?>
                                    <?php
                                    $isActive = ($activeChatId === $forum['ID']) ? 'active' : '';
                                    ?>
                                    <a href="<?php echo BASEURL; ?>/forums/chat/<?php echo $forum['ID']; ?>" class="chats-card group <?php echo $isActive; ?> last:pb-8">
                                        <div class="flex items-center rounded-2xl p-4 gap-3 group-[.active]:bg-gray-100 hover:bg-gray-100 transition-all duration-300">

                                            <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                                                <img
                                                    src="<?php echo !empty($forum['PATH_PHOTO'])
                                                                ? BASEURL . '/storage/forums/photos/' . $forum['PATH_PHOTO']
                                                                : BASEURL . '/src/asset/image/default.png'; ?>"
                                                    class="w-full h-full object-cover"
                                                    alt="photo">
                                            </div>

                                            <div class="flex flex-col w-full gap-1">

                                                <div id="forum-skeleton-<?php echo $forum['ID']; ?>" class="w-full animate-pulse space-y-1.5">
                                                    <div class="flex items-center justify-between gap-[6px]">
                                                        <p class="font-medium leading-5 max-w-[182px] truncate">
                                                            <?php echo htmlspecialchars($forum['NAME']); ?>
                                                        </p>
                                                        <div class="bg-gray-200 rounded w-10 h-3"></div>
                                                    </div>
                                                    <div class="flex items-center gap-1 justify-between">
                                                        <div class="bg-gray-200 rounded w-10/12 h-4"></div>
                                                        <div class="bg-gray-200 rounded-full size-5"></div>
                                                    </div>
                                                </div>

                                                <div id="forum-data-<?php echo $forum['ID']; ?>" class="w-full hidden">
                                                    <div class="flex items-center justify-between gap-[6px]">
                                                        <p class="font-medium leading-5 max-w-[182px] truncate">
                                                            <?php echo htmlspecialchars($forum['NAME']); ?>
                                                        </p>
                                                        <span class="text-xs text-gray-500" id="forum-time-<?php echo $forum['ID']; ?>"></span>
                                                    </div>
                                                    <div class="flex items-center gap-1 justify-between">
                                                        <div class="w-full max-w-[178px] text-sm text-gray-500 line-clamp-1">
                                                            <p class="flex items-center gap-1">
                                                                <span class="truncate" id="forum-last-msg-<?php echo $forum['ID']; ?>"></span>
                                                            </p>
                                                        </div>
                                                        <span id="forum-count-<?php echo $forum['ID']; ?>"
                                                            class="flex items-center justify-center shrink-0 size-5 text-xs font-medium text-white rounded-full">
                                                        </span>
                                                    </div>
                                                </div>
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
    </div>
</aside>

<?php require_once 'app/views/components/forums/modalSearchForum.php'; ?>


<script>
    window.lastCountHash = '';

    const activeChatId = '<?php echo $activeChatId ?? ''; ?>';
    const baseUrl = '<?php echo BASEURL; ?>';

    function formatTime(timestamp) {
        if (!timestamp) return '';
        try {
            const date = new Date(timestamp);

            if (isNaN(date.getTime())) {
                return timestamp.substring(0, 6).replace('-', ' '); // Misal: "04 Nov"
            }

            const day = date.getDate().toString().padStart(2, '0');
            const month = date.toLocaleString('id-ID', {
                month: 'short'
            });
            return `${day} ${month}`;

        } catch (e) {
            console.error("Error formatting date:", timestamp, e);
            return '';
        }
    }


    function updateSidebarUI(data) {

        for (const item of data) {

            const dataWrapper = document.getElementById(`forum-data-${item.forumId}`);
            const skeletonWrapper = document.getElementById(`forum-skeleton-${item.forumId}`);

            const badgeElement = document.getElementById(`forum-count-${item.forumId}`);
            const msgElement = document.getElementById(`forum-last-msg-${item.forumId}`);
            const timeElement = document.getElementById(`forum-time-${item.forumId}`);

            if (!dataWrapper || !skeletonWrapper || !badgeElement || !msgElement || !timeElement) {
                continue;
            }

            const isChatActive = (activeChatId !== '' && item.forumId === activeChatId);


            if (item.count > 0 && !isChatActive) {
                badgeElement.innerText = item.count;
                badgeElement.classList.add('bg-blue-600');
            } else {
                badgeElement.innerText = '';
                badgeElement.classList.remove('bg-blue-600');
            }

            if (item.lastMessage) {
                msgElement.innerText = item.lastMessage;
            } else {
                msgElement.innerText = 'No messages yet';
            }

            timeElement.innerText = formatTime(item.lastTime);

            skeletonWrapper.classList.add('hidden');
            dataWrapper.classList.remove('hidden');
        }
    }


    async function startCountPolling() {

        const hash = window.lastCountHash;
        const url = `${baseUrl}/forums/pollCounts?lastHash=${hash}`;

        try {
            const response = await fetch(url);

            if (response.status === 200) {
                const data = await response.json();

                window.lastCountHash = data.hash;

                updateSidebarUI(data.data);

            }

        } catch (error) {
            console.error('Count polling error:', error);
            await new Promise(resolve => setTimeout(resolve, 5000));
        }

        startCountPolling();
    }

    document.addEventListener('DOMContentLoaded', startCountPolling);
</script>