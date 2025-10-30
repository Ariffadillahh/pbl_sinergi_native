<div class="relative inline-block">
    <div id="notif-dropdown" class="hidden overflow-hidden absolute left-full ml-8 -top-5 w-80 md:w-96 bg-white rounded-xl shadow-2xl z-[99999] border border-gray-100">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
            <h3 class="font-bold text-gray-800 text-lg">Notifikasi</h3>
            <button id="mark-all-read-btn" class="text-sm text-blue-600 hover:text-blue-700 font-medium hover:underline">
                Read ALL
            </button>
        </div>

        <div class="flex border-b border-gray-200 bg-gray-50">
            <button id="tab-unread" class="flex-1 px-4 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600 bg-white transition-colors">
                Belum Dibaca <span id="unread-tab-count" class="ml-1 px-2 py-0.5 text-xs bg-blue-100 text-blue-600 rounded-full">0</span>
            </button>
            <button id="tab-read" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors">
                Sudah Dibaca
            </button>
        </div>

        <div id="unread-container" class="h-[300px] overflow-y-auto hide-scrollbar">
            <p id="no-unread-message" class="p-8 text-center text-gray-500 text-sm">
                Tidak ada notifikasi belum dibaca.
            </p>
        </div>

        <div id="read-container" class="hidden h-[300px] overflow-y-auto hide-scrollbar">
            <p id="no-read-message" class="p-8 text-center text-gray-500 text-sm">
                Tidak ada notifikasi yang sudah dibaca.
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const BASEURL = '<?php echo BASEURL; ?>';
        const notifBtn = document.getElementById('notif-btn');
        const notifDropdown = document.getElementById('notif-dropdown');
        const notifBadge = document.getElementById('notif-badge');
        const notifCountSpan = document.getElementById('notif-count');
        const unreadContainer = document.getElementById('unread-container');
        const readContainer = document.getElementById('read-container');
        const noUnreadMessage = document.getElementById('no-unread-message');
        const noReadMessage = document.getElementById('no-read-message');
        const markAllReadBtn = document.getElementById('mark-all-read-btn');
        const tabUnread = document.getElementById('tab-unread');
        const tabRead = document.getElementById('tab-read');
        const unreadTabCount = document.getElementById('unread-tab-count');

        let lastTimestamp = new Date().toISOString();
        let isPolling = false;
        let currentTab = 'unread';

        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
            notifBtn.parentElement.classList.toggle('modal-open');
        });

        document.addEventListener('click', (e) => {
            if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                if (!notifDropdown.classList.contains('hidden')) {
                    notifDropdown.classList.add('hidden');
                    notifBtn.parentElement.classList.remove('modal-open');
                }
            }
        });

        tabUnread.addEventListener('click', () => {
            currentTab = 'unread';
            tabUnread.classList.add('text-blue-600', 'border-b-2', 'border-blue-600', 'bg-white');
            tabUnread.classList.remove('text-gray-600', 'hover:bg-gray-100');
            tabRead.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600', 'bg-white');
            tabRead.classList.add('text-gray-600', 'hover:bg-gray-100');

            unreadContainer.classList.remove('hidden');
            readContainer.classList.add('hidden');
        });

        tabRead.addEventListener('click', () => {
            currentTab = 'read';
            tabRead.classList.add('text-blue-600', 'border-b-2', 'border-blue-600', 'bg-white');
            tabRead.classList.remove('text-gray-600', 'hover:bg-gray-100');
            tabUnread.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600', 'bg-white');
            tabUnread.classList.add('text-gray-600', 'hover:bg-gray-100');

            readContainer.classList.remove('hidden');
            unreadContainer.classList.add('hidden');
        });

        function createNotificationHTML(notif) {
            const {
                ID,
                TYPE,
                DATA,
                CREATED_AT,
                IS_READ
            } = notif;

            const colorMap = {
                LIKE_POST: 'blue',
                REPLY_POST: 'green',
                MENTION: 'purple',
                WARNING: 'yellow',
                DEFAULT: 'gray'
            };

            const baseColor = colorMap[TYPE] || colorMap.DEFAULT;
            const color = IS_READ == 0 || IS_READ === false ? baseColor : 'gray';

            const messageMap = {
                LIKE_POST: `<strong>${DATA.sender_name || 'Someone'}</strong> menyukai postingan Anda.`,
                REPLY_POST: `<strong>${DATA.sender_name || 'Someone'}</strong> mengomentari postingan Anda.`,
                MENTION: `<strong>${DATA.sender_name || 'Someone'}</strong> menyebut Anda dalam sebuah postingan.`,
                WARNING: `<strong>Admin</strong> memperingatkan Anda terkait ${DATA.content_type === 'forum' ? 'forum' : 'postingan'} Anda${DATA.reason ? ': ' + DATA.reason : '.'}`,
                DEFAULT: `Notifikasi baru`
            };

            const iconMap = {
                LIKE_POST: `
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682 a4.5 4.5 0 00-6.364-6.364L12 7.636 l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>`,
                REPLY_POST: `
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>`,
                MENTION: `
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.85 M7 20H2v-2a3 3 0 015.356-1.857 M7 20v-2c0-.656.126-1.283.356-1.857 m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z m6 3a2 2 0 11-4 0 2 2 0 014 0z M7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>`,
                WARNING: `
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>`
            };

            const isUnread = IS_READ == 0 || IS_READ === false;
            const link = DATA.link || '#';

            const bgColorClass = {
                blue: 'bg-gradient-to-br from-blue-500 to-blue-600',
                green: 'bg-gradient-to-br from-green-500 to-green-600',
                purple: 'bg-gradient-to-br from-purple-500 to-purple-600',
                yellow: 'bg-gradient-to-br from-yellow-500 to-yellow-600',
                gray: 'bg-gradient-to-br from-gray-400 to-gray-500'
            } [color];

            const borderColorClass = isUnread ? {
                blue: 'border-blue-500 bg-blue-50',
                green: 'border-green-500 bg-green-50',
                purple: 'border-purple-500 bg-purple-50',
                yellow: 'border-yellow-500 bg-yellow-50'
            } [color] : 'border-transparent bg-white';

            const dotColorClass = {
                blue: 'bg-blue-500',
                green: 'bg-green-500',
                purple: 'bg-purple-500',
                yellow: 'bg-yellow-500'
            } [color];

            return `
                <div data-notif-id="${ID}" data-link="${link}" data-is-read="${IS_READ}"
                    class="notification-item flex items-start gap-3 px-4 py-3 hover:bg-gray-100 
                        transition-colors cursor-pointer border-l-4 ${borderColorClass}">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full ${bgColorClass} flex items-center justify-center">
                        ${iconMap[TYPE] || iconMap.LIKE_POST}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 font-medium line-clamp-2">${messageMap[TYPE] || messageMap.DEFAULT}</p>
                        <p class="text-xs text-gray-500 mt-1">${new Date(CREATED_AT).toLocaleString('id-ID')}</p>
                    </div>
                    ${isUnread ? `
                    <div class="unread-indicator flex-shrink-0"> 
                        <span class="w-2 h-2 ${dotColorClass} rounded-full block"></span>
                    </div>` : ''}
                </div>
            `;
        }

        markAllReadBtn.addEventListener('click', async () => {
            try {
                const response = await fetch(`${BASEURL}/notifications/markAllRead`, {
                    method: 'POST'
                });

                if (response.ok) {
                    const unreadItems = unreadContainer.querySelectorAll('[data-notif-id]');
                    unreadItems.forEach(item => {
                        const notifId = item.dataset.notifId;
                        const clonedItem = item.cloneNode(true);

                        clonedItem.classList.remove('border-blue-500', 'border-green-500', 'border-purple-500', 'border-yellow-500');
                        clonedItem.classList.remove('bg-blue-50', 'bg-green-50', 'bg-purple-50', 'bg-yellow-50');
                        clonedItem.classList.add('border-transparent', 'bg-white');

                        const iconWrapper = clonedItem.querySelector('.rounded-full');
                        if (iconWrapper) {
                            iconWrapper.classList.remove(
                                'from-blue-500', 'to-blue-600',
                                'from-green-500', 'to-green-600',
                                'from-purple-500', 'to-purple-600',
                                'from-yellow-500', 'to-yellow-600'
                            );
                            iconWrapper.classList.add('from-gray-400', 'to-gray-500');
                        }

                        const dot = clonedItem.querySelector('.unread-indicator');
                        if (dot) dot.remove();

                        clonedItem.dataset.isRead = '1';

                        noReadMessage.style.display = 'none';
                        readContainer.insertAdjacentElement('afterbegin', clonedItem);

                        item.remove();
                    });

                    updateNotificationCount(0);
                    noUnreadMessage.style.display = 'block';
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        });

        function handleNotificationClick(container) {
            container.addEventListener('click', async (event) => {
                const notificationElement = event.target.closest('.notification-item');
                if (!notificationElement) return;

                const isRead = notificationElement.dataset.isRead == '1';
                const link = notificationElement.dataset.link;
                const notifId = notificationElement.dataset.notifId;

                if (isRead) {
                    if (link && link !== '#') {
                        window.location.href = `http://localhost/sinergi/${link}`;
                    }
                    return;
                }

                try {
                    await fetch(`${BASEURL}/notifications/markAsRead`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            notifId: notifId
                        })
                    });

                    const clonedItem = notificationElement.cloneNode(true);
                    clonedItem.classList.remove('border-blue-500', 'border-green-500', 'border-purple-500', 'border-yellow-500');
                    clonedItem.classList.remove('bg-blue-50', 'bg-green-50', 'bg-purple-50', 'bg-yellow-50');
                    clonedItem.classList.add('border-transparent', 'bg-white');

                    const iconWrapper = clonedItem.querySelector('.rounded-full');
                    if (iconWrapper) {
                        iconWrapper.classList.remove(
                            'from-blue-500', 'to-blue-600',
                            'from-green-500', 'to-green-600',
                            'from-purple-500', 'to-purple-600',
                            'from-yellow-500', 'to-yellow-600'
                        );
                        iconWrapper.classList.add('from-gray-400', 'to-gray-500');
                    }

                    const dot = clonedItem.querySelector('.unread-indicator');
                    if (dot) dot.remove();

                    clonedItem.dataset.isRead = '1';

                    noReadMessage.style.display = 'none';
                    readContainer.insertAdjacentElement('afterbegin', clonedItem);

                    notificationElement.remove();

                    const currentCount = parseInt(notifCountSpan.textContent) || 0;
                    updateNotificationCount(Math.max(0, currentCount - 1));

                    if (unreadContainer.querySelectorAll('[data-notif-id]').length === 0) {
                        noUnreadMessage.style.display = 'block';
                    }

                    if (link && link !== '#') {
                        window.location.href = `http://localhost/sinergi/${link}`;
                    }
                } catch (error) {
                    console.error('Error marking as read:', error);
                }
            });
        }

        handleNotificationClick(unreadContainer);
        handleNotificationClick(readContainer);

        function updateNotificationCount(newUnreadCount) {
            notifCountSpan.textContent = newUnreadCount;
            unreadTabCount.textContent = newUnreadCount;

            if (newUnreadCount > 0) {
                notifBadge.classList.remove('hidden');
                notifBadge.classList.add('flex');
            } else {
                notifBadge.classList.add('hidden');
                notifBadge.classList.remove('flex');
            }
        }

        async function loadInitialNotifications() {
            try {
                const response = await fetch(`${BASEURL}/notifications/getRecent`);
                if (!response.ok) throw new Error('Failed to load notifications');

                const data = await response.json();
                const notifications = data.notifications || [];
                const unreadCount = data.unread_count || 0;

                const unreadNotifs = notifications.filter(n => n.IS_READ == 0);
                const readNotifs = notifications.filter(n => n.IS_READ == 1);

                if (unreadNotifs.length > 0) {
                    noUnreadMessage.style.display = 'none';
                    unreadContainer.innerHTML = unreadNotifs.map(createNotificationHTML).join('');
                } else {
                    noUnreadMessage.style.display = 'block';
                }

                if (readNotifs.length > 0) {
                    noReadMessage.style.display = 'none';
                    readContainer.innerHTML = readNotifs.map(createNotificationHTML).join('');
                } else {
                    noReadMessage.style.display = 'block';
                }

                updateNotificationCount(unreadCount);

                if (notifications.length > 0) {
                    lastTimestamp = notifications[0].CREATED_AT;
                }
            } catch (error) {
                console.error('Error loading initial notifications:', error);
            }
        }

        async function pollForNotifications() {
            if (isPolling) return;
            isPolling = true;

            try {
                const response = await fetch(`${BASEURL}/notifications/checkForUpdates?last_timestamp=${encodeURIComponent(lastTimestamp)}`);
                if (!response.ok) throw new Error('Network response was not ok.');

                const newNotifications = await response.json();

                if (newNotifications.length > 0) {
                    newNotifications.forEach(notif => {
                        if (notif.IS_READ == 0) {
                            noUnreadMessage.style.display = 'none';
                            unreadContainer.insertAdjacentHTML('afterbegin', createNotificationHTML(notif));
                        } else {
                            noReadMessage.style.display = 'none';
                            readContainer.insertAdjacentHTML('afterbegin', createNotificationHTML(notif));
                        }
                    });

                    lastTimestamp = newNotifications[0].CREATED_AT;

                    const currentCount = parseInt(notifCountSpan.textContent) || 0;
                    const newUnreadCount = currentCount + newNotifications.filter(n => n.IS_READ == 0).length;
                    updateNotificationCount(newUnreadCount);
                }
            } catch (error) {
                console.error("Long polling error:", error);
                await new Promise(resolve => setTimeout(resolve, 5000));
            } finally {
                isPolling = false;
                pollForNotifications();
            }
        }

        loadInitialNotifications().then(() => {
            pollForNotifications();
        });
    });
</script>