<div class="relative inline-block">
    <button id="notif-btn" class="relative flex-shrink-0 p-2.5 pt-3 md:pt-2.5 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all duration-200 group">
        <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <span id="notif-badge" class="hidden absolute top-1.5 right-1.5 h-4 w-4 items-center justify-center">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span id="notif-count" class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-red-500 text-white text-[10px] font-semibold">0</span>
        </span>
    </button>

    <div id="notif-dropdown" class="hidden absolute right-0 top-full mt-2 w-80 md:w-96 bg-white rounded-xl shadow-2xl z-50">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <h3 class="font-bold text-gray-800 text-lg">Notifikasi</h3>
            <button id="mark-all-read-btn" class="text-sm text-blue-600 hover:text-blue-700 font-medium hover:underline">
                Read ALL
            </button>
        </div>

        <div id="notif-list-container" class="max-h-96 overflow-y-auto hide-scrollbar mb-2">
            <p id="no-notif-message" class="p-8 text-center text-gray-500 text-sm">
                Tidak ada notifikasi.
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
        const notifListContainer = document.getElementById('notif-list-container');
        const noNotifMessage = document.getElementById('no-notif-message');
        const markAllReadBtn = document.getElementById('mark-all-read-btn');

        let lastTimestamp = new Date().toISOString();
        let isPolling = false;

        notifBtn.addEventListener('click', () => {
            notifDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.classList.add('hidden');
            }
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
                                <path 
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682 a4.5 4.5 0 00-6.364-6.364L12 7.636 l-1.318-1.318a4.5 4.5 0 00-6.364 0z" 
                                />
                            </svg>
                        `,
                REPLY_POST: `
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path 
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M8 12h.01M12 12h.01M16 12h.01 M21 12c0 4.418-4.03 8-9 8 a9.863 9.863 0 01-4.255-.949L3 20 l1.395-3.72C3.512 15.042 3 13.574 3 1c0-4.418 4.03-8 9-8s9 3.582 9 8z" 
                                />
                            </svg>
                        `,
                MENTION: `
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path 
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857
                                    M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.85 M7 20H2v-2a3 3 0 015.356-1.857 M7 20v-2c0-.656.126-1.283.356-1.857 m0 0a5.002 5.002 0 019.288 0
                                    M15 7a3 3 0 11-6 0 3 3 0 016 0z m6 3a2 2 0 11-4 0 2 2 0 014 0z M7 10a2 2 0 11-4 0 2 2 0 014 0z" 
                                />
                            </svg>
                        `,
                WARNING: `
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                stroke-width="2" 
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" 
                            />
                        </svg>
                    `,
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

            const borderColorClass = {
                blue: 'border-blue-500 bg-blue-50',
                green: 'border-green-500 bg-green-50',
                purple: 'border-purple-500 bg-purple-50',
                yellow: 'border-yellow-500 bg-yellow-50',
                gray: 'border-transparent'
            } [color];

            const dotColorClass = {
                blue: 'bg-blue-500',
                green: 'bg-green-500',
                purple: 'bg-purple-500',
                yellow: 'bg-yellow-500',
                gray: 'bg-gray-400'
            } [color];

            return `
                <div data-notif-id="${ID}" data-link="${link}" 
                    class="notification-item flex items-start gap-3 px-4 py-3 hover:bg-gray-200/50 
                        transition-colors cursor-pointer border-l-4 ${borderColorClass} 
                        ${isUnread ? 'is-unread' : ''}">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full ${bgColorClass} flex items-center justify-center">
                        ${iconMap[TYPE] || iconMap.DEFAULT}
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
                    const notifItems = notifListContainer.querySelectorAll('[data-notif-id]');
                    notifItems.forEach(item => {
                        item.classList.remove(
                            'border-blue-500', 'border-green-500', 'border-purple-500',
                            'bg-blue-50', 'bg-green-50', 'bg-purple-50',
                            'border-gray-500', 'bg-gray-50',
                            'border-yellow-500', 'bg-yellow-50'
                        );
                        item.classList.add('border-transparent', 'bg-gray-50');

                        const iconWrapper = item.querySelector('.rounded-full');
                        if (iconWrapper) {
                            iconWrapper.classList.remove(
                                'from-blue-500', 'to-blue-600',
                                'from-green-500', 'to-green-600',
                                'from-purple-500', 'to-purple-600',
                                'from-yellow-500', 'to-yellow-600'
                            );
                            iconWrapper.classList.add('from-gray-400', 'to-gray-500');
                        }

                        const dot = item.querySelector('.w-2.h-2');
                        if (dot) dot.remove();

                        item.classList.remove('is-unread');
                    });

                    notifCountSpan.textContent = '0';
                    notifBadge.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        });

        notifListContainer.addEventListener('click', async (event) => {
            const notificationElement = event.target.closest('[data-notif-id]');

            if (!notificationElement || !notificationElement.classList.contains('is-unread')) {
                const link = `http://localhost/sinergi/${notificationElement?.dataset.link}`;
                if (link && link !== '#') window.location.href = link;
                return;
            }

            notificationElement.classList.remove('is-unread');

            notificationElement.classList.remove(
                'border-blue-500', 'bg-blue-50',
                'border-green-500', 'bg-green-50',
                'border-purple-500', 'bg-purple-50',
                'border-yellow-500', 'bg-yellow-50'
            );

            const iconWrapper = notificationElement.querySelector('.rounded-full');
            if (iconWrapper) {
                iconWrapper.classList.remove(
                    'from-blue-500', 'to-blue-600',
                    'from-green-500', 'to-green-600',
                    'from-purple-500', 'to-purple-600',
                    'from-yellow-500', 'to-yellow-600'
                );
                iconWrapper.classList.add('from-gray-400', 'to-gray-500');
            }
            notificationElement.classList.add('border-transparent');

            const unreadIndicator = notificationElement.querySelector('.unread-indicator');
            if (unreadIndicator) {
                unreadIndicator.remove();
            }

            const currentCount = parseInt(notifCountSpan.textContent) || 0;
            updateNotificationCount(Math.max(0, currentCount - 1));

            const notifId = notificationElement.dataset.notifId;
            const link = notificationElement.dataset.link;

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
            } catch (error) {
                console.error('Gagal mengirim status "dibaca":', error);
            } finally {
                // if (link && link !== '#') {
                //     window.location.href = link;
                // }
            }
        });

        function updateNotificationCount(newUnreadCount) {
            notifCountSpan.textContent = newUnreadCount;
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

                if (notifications.length > 0) {
                    noNotifMessage.style.display = 'none';
                    notifListContainer.innerHTML = notifications.map(createNotificationHTML).join('');
                } else {
                    noNotifMessage.style.display = 'block';
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
                    noNotifMessage.style.display = 'none';

                    newNotifications.forEach(notif => {
                        notifListContainer.insertAdjacentHTML('afterbegin', createNotificationHTML(notif));
                    });

                    lastTimestamp = newNotifications[newNotifications.length - 1].CREATED_AT;

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