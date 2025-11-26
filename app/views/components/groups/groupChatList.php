<aside id="forumsListSidebar" class="fixed lg:relative flex flex-col w-full max-w-[360px] lg:w-[360px] shrink-0 h-full border-r border-gray-100 bg-white overflow-hidden transition-all duration-300 ease-in-out z-20 -translate-x-full lg:translate-x-0 pb-[70px] lg:pb-0">
    <div id="Top-Bar" class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-6">
        <p class="text-2xl font-semibold">Your Groups</p>
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
                    <p class="text-sm text-gray-500 pb-2 border-b-[1px] border-gray-200">All Groups (<?php echo count($joinedGroupChats); ?>)</p>
                    <div id="Message-container" class="hide-scrollbar h-full w-full overflow-y-scroll">
                        <div class="flex w-full flex-col gap-1">
                            <?php if (!empty($joinedGroupChats)): ?>
                                <?php foreach ($joinedGroupChats as $groupChat): ?>
                                    <?php
                                    $isActive = ($activeChatId === $groupChat['ID']) ? 'active' : '';
                                    $cleanId = trim($groupChat['ID']);
                                    ?>
                                    <a href="<?php echo BASEURL; ?>/groups/chat/<?php echo $groupChat['ID']; ?>" class="chats-card group <?php echo $isActive; ?> last:pb-8">
                                        <div class="flex items-center rounded-2xl p-4 gap-3 group-[.active]:bg-gray-100 hover:bg-gray-100 transition-all duration-300">

                                            <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden items-center justify-center bg-gray-200">

                                                <?php if (!empty($groupChat['PATH_PHOTO'])): ?>
                                                    <img
                                                        src="<?= BASEURL . '/storage/groups/photos/' . $groupChat['PATH_PHOTO'] ?>"
                                                        class="w-full h-full object-cover"
                                                        alt="photo">
                                                <?php else: ?>
                                                    <span class="text-white font-bold text-lg bg-pink-500 w-full h-full flex items-center justify-center">
                                                        <?= strtoupper(substr($groupChat['NAME'], 0, 2)) ?>
                                                    </span>
                                                <?php endif; ?>

                                            </div>


                                            <div class="flex flex-col w-full gap-1">

                                                <div id="forum-skeleton-<?php echo $cleanId; ?>" class="w-full animate-pulse space-y-1.5">
                                                    <div class="flex items-center justify-between gap-[6px]">
                                                        <p class="font-medium leading-5 max-w-[182px] truncate">
                                                            <?php echo htmlspecialchars($groupChat['NAME']); ?>
                                                        </p>
                                                        <div class="bg-gray-200 rounded w-10 h-3"></div>
                                                    </div>
                                                    <div class="flex items-center gap-1 justify-between">
                                                        <div class="bg-gray-200 rounded w-10/12 h-4"></div>
                                                        <div class="bg-gray-200 rounded-full size-5"></div>
                                                    </div>
                                                </div>

                                                <div id="forum-data-<?php echo $cleanId; ?>" class="w-full hidden">
                                                    <div class="flex items-center justify-between gap-[6px]">
                                                        <p class="font-medium leading-5 max-w-[182px] truncate">
                                                            <?php echo htmlspecialchars($groupChat['NAME']); ?>
                                                        </p>
                                                        <span class="text-xs text-gray-500" id="forum-time-<?php echo $groupChat['ID']; ?>"></span>
                                                    </div>
                                                    <div class="flex items-center gap-1 justify-between">
                                                        <div class="w-full max-w-[178px] text-sm text-gray-500 line-clamp-1">
                                                            <p class="flex items-center gap-1">
                                                                <span class="truncate" id="forum-last-msg-<?php echo $groupChat['ID']; ?>"></span>
                                                            </p>
                                                        </div>
                                                        <span id="forum-count-<?php echo $groupChat['ID']; ?>"
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
                                    <p class="text-sm text-gray-900 text-center font-semibold h-[50vh] flex justify-center items-center">No groups found.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>

<?php require_once 'app/views/components/groups/modalSearchGroup.php'; ?>


<script>
    window.lastCountHash = '';

    const activeChatId = '<?php echo $activeChatId ?? ''; ?>';
    const baseUrl = '<?php echo BASEURL; ?>';

    function formatTime(timestamp) {
        if (!timestamp) {
            console.warn('Empty timestamp received');
            return '';
        }
        
        try {
            let dateString = timestamp;
            
            if (dateString.includes(' ')) {
                dateString = dateString.replace(' ', 'T');
            }
            
            const date = new Date(dateString);

            if (isNaN(date.getTime())) {
                console.error('Invalid date:', timestamp);
                return timestamp.substring(0, 10);
            }

            const now = new Date();
            
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const messageDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            
            const diffTime = today - messageDate;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays === 0) {
                let hours = date.getHours();
                const minutes = date.getMinutes().toString().padStart(2, '0');
                const ampm = hours >= 12 ? 'PM' : 'AM';
                
                hours = hours % 12;
                hours = hours ? hours : 12;
                
                return `${hours}:${minutes} ${ampm}`;
            }

            if (diffDays === 1) {
                return 'Yesterday';
            }

            if (diffDays >= 2 && diffDays <= 6) {
                const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                return days[date.getDay()];
            }

            if (now.getFullYear() === date.getFullYear()) {
                const day = date.getDate().toString().padStart(2, '0');
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const month = months[date.getMonth()];
                return `${day} ${month}`;
            }

            const day = date.getDate().toString().padStart(2, '0');
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            return `${day} ${month} ${year}`;

        } catch (e) {
            console.error("Error formatting date:", timestamp, e);
            return '';
        }
    }

    function updateSidebarUI(data) {
        console.log('Updating sidebar with data:', data);

        // Validate data
        if (!data || !Array.isArray(data)) {
            console.error('Invalid data received:', data);
            return;
        }

        // Sort data by lastTime (newest first)
        data.sort((a, b) => {
            const timeA = a.lastTime ? new Date(a.lastTime.replace(' ', 'T')).getTime() : 0;
            const timeB = b.lastTime ? new Date(b.lastTime.replace(' ', 'T')).getTime() : 0;
            return timeB - timeA; // Descending order (newest first)
        });

        const container = document.querySelector('#Message-container > div');
        if (!container) {
            console.error('Message container not found');
            return;
        }

        // Update data for each group
        for (const item of data) {
            // Handle both groupChatId and group_chat_id
            const groupChatId = (item.groupChatId || item.group_chat_id || '').toString().trim();
            if (!groupChatId) {
                console.warn('Missing group chat ID in item:', item);
                continue;
            }
            
            const dataWrapper = document.getElementById(`forum-data-${groupChatId}`);
            const skeletonWrapper = document.getElementById(`forum-skeleton-${groupChatId}`);
            const badgeElement = document.getElementById(`forum-count-${groupChatId}`);
            const msgElement = document.getElementById(`forum-last-msg-${groupChatId}`);
            const timeElement = document.getElementById(`forum-time-${groupChatId}`);
            const chatCard = document.querySelector(`a[href*="/groups/chat/${groupChatId}"]`);

            if (!dataWrapper || !skeletonWrapper || !badgeElement || !msgElement || !timeElement) {
                console.warn(`Missing elements for group ${groupChatId}`);
                continue;
            }

            const isChatActive = (activeChatId !== '' && groupChatId === activeChatId);

            // Update badge
            if (item.count > 0 && !isChatActive) {
                badgeElement.innerText = item.count;
                badgeElement.classList.add('bg-blue-600');
            } else {
                badgeElement.innerText = '';
                badgeElement.classList.remove('bg-blue-600');
            }

            // Update last message
            msgElement.innerText = item.lastMessage || 'No messages yet';

            // Update time
            timeElement.innerText = formatTime(item.lastTime);

            // Show data, hide skeleton
            skeletonWrapper.classList.add('hidden');
            dataWrapper.classList.remove('hidden');

            // Store timestamp for sorting
            if (chatCard) {
                chatCard.dataset.lastMessageTime = item.lastTime || '0';
            }
        }

        // Reorder DOM elements - insert in correct order from top to bottom
        console.log('Starting reorder process...');
        
        for (let i = 0; i < data.length; i++) {
            const item = data[i];
            const groupId = (item.groupChatId || item.group_chat_id || '').toString().trim();
            
            if (!groupId) continue;
            
            // Find the card for this group
            const card = container.querySelector(`a[href*="/groups/chat/${groupId}"]`)?.closest('.chats-card');
            
            if (!card) {
                console.warn(`Card not found for group ${groupId}`);
                continue;
            }
            
            // Get the card that should be at position i
            const cardAtPosition = container.children[i];
            
            // If the card is not already in the correct position, move it
            if (cardAtPosition !== card) {
                console.log(`Moving group ${groupId} to position ${i}`);
                container.insertBefore(card, cardAtPosition);
            }
        }

        console.log('Sidebar reordered successfully');
    }

    async function startCountPolling() {
        const hash = window.lastCountHash;
        const url = `${baseUrl}/groups/pollCounts?lastHash=${encodeURIComponent(hash)}`;

        try {
            const response = await fetch(url);

            if (response.status === 200) {
                const result = await response.json();
                console.log('Poll response:', result);

                window.lastCountHash = result.hash;
                updateSidebarUI(result.data);

            } else if (response.status === 204) {
                console.log('No changes detected');
            } else if (response.status === 403) {
                console.error('Unauthorized');
                return;
            }

        } catch (error) {
            console.error('Count polling error:', error);
            await new Promise(resolve => setTimeout(resolve, 5000));
        }

        startCountPolling();
    }

    document.addEventListener('DOMContentLoaded', () => {
        console.log('Starting count polling...');
        startCountPolling();
    });
</script>