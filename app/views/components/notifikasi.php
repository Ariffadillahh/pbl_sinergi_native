<div id="notif-dropdown" class="hidden overflow-hidden absolute w-80 md:w-96 bg-white rounded-xl shadow-2xl z-[99999] border border-gray-100">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
        <h3 class="font-bold text-gray-800 text-lg">Notifikasi</h3>
        <button id="mark-all-read-btn" class="text-sm text-blue-600 hover:text-blue-700 font-medium hover:underline">
            Read All
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
    <div id="unread-container" class="h-[300px] overflow-y-auto hide-scrollbar text-left">
        <p id="no-unread-message" class="p-8 text-center text-gray-500 text-sm">
            Tidak ada notifikasi belum dibaca.
        </p>
    </div>
    <div id="read-container" class="hidden text-left">
        <div class="h-[300px] overflow-y-auto hide-scrollbar">
            <p id="no-read-message" class="p-8 text-center text-gray-500 text-sm">
                Tidak ada notifikasi yang sudah dibaca.
            </p>
        </div>
        <div class="border-t border-gray-200 p-3 bg-gray-50">
            <button id="delete-all-read-btn" class="w-full py-2 px-4 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete All Read
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const BASEURL = '<?php echo BASEURL; ?>';

        const notifDropdown = document.getElementById('notif-dropdown');
        const unreadContainer = document.getElementById('unread-container');
        const readContainer = document.getElementById('read-container');
        const noUnreadMessage = document.getElementById('no-unread-message');
        const noReadMessage = document.getElementById('no-read-message');
        const markAllReadBtn = document.getElementById('mark-all-read-btn');
        const deleteAllReadBtn = document.getElementById('delete-all-read-btn');
        const tabUnread = document.getElementById('tab-unread');
        const tabRead = document.getElementById('tab-read');
        const unreadTabCount = document.getElementById('unread-tab-count');
        const notifBtn = document.getElementById('notif-btn');
        const notifBadge = document.getElementById('notif-badge');
        const notifCountSpan = document.getElementById('notif-count');
        const notifBtnMobile = document.getElementById('notif-btn-mobile');
        const notifBadgeMobile = document.getElementById('notif-badge-mobile');
        const notifCountMobile = document.getElementById('notif-count-mobile');

        let activeNotifButtonId = null;
        const desktopBreakpoint = 1024;
        let lastTimestamp = new Date().toISOString();
        let isPolling = false;

        const positionDropdown = () => {
            if (notifDropdown.classList.contains('hidden') || !activeNotifButtonId) {
                return;
            }
            notifDropdown.className = notifDropdown.className.replace(/(absolute|fixed|left-full|ml-8|-top-5|bottom-20|right-4|w-\[90vw\]|max-w-sm|w-80|md:w-96)/g, '');

            if (window.innerWidth < desktopBreakpoint) {
                activeNotifButtonId = 'notif-btn-mobile';
                document.body.appendChild(notifDropdown);
                notifDropdown.classList.add('fixed', 'bottom-20', 'right-4', 'w-[90vw]', 'max-w-sm');
            } else {
                activeNotifButtonId = 'notif-btn';
                notifBtn.parentElement.appendChild(notifDropdown);
                notifDropdown.classList.add('absolute', 'left-full', 'ml-8', '-top-5', 'w-80', 'md:w-96');
            }
        };

        const toggleDropdown = (event) => {
            event.stopPropagation();
            const clickedButton = event.currentTarget;
            const isOpening = notifDropdown.classList.contains('hidden');

            if (isOpening) {
                activeNotifButtonId = clickedButton.id;
                notifDropdown.classList.remove('hidden');
                positionDropdown();
            } else {
                activeNotifButtonId = null;
                notifDropdown.classList.add('hidden');
            }
            notifBtn.parentElement.classList.toggle('modal-open', activeNotifButtonId === 'notif-btn');
            notifBtnMobile.parentElement.classList.toggle('modal-open', activeNotifButtonId === 'notif-btn-mobile');
        };

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
                REPLY_COMMENT: 'green',
                MENTION: 'purple',
                WARNING: 'yellow',
                KICKED: 'red',
                DELETE: 'red',
                INVITE_GROUP: 'indigo',
                INVITE_FORUM: 'indigo',
                ADMIN_INVITE_FORUM: 'indigo',
                DEFAULT: 'gray'
            };

            const baseColor = colorMap[TYPE] || colorMap.DEFAULT;
            const color = IS_READ == 0 || IS_READ === false ? baseColor : 'gray';

            const messageMap = {
                LIKE_POST: `<strong>${DATA.sender_name || 'Someone'}</strong> menyukai postingan Anda.`,
                REPLY_POST: `<strong>${DATA.sender_name || 'Someone'}</strong> mengomentari postingan Anda.`,
                REPLY_COMMENT: `<strong>${DATA.sender_name || 'Someone'}</strong> membalas komentar Anda.`,
                MENTION: `<strong>${DATA.sender_name || 'Someone'}</strong> menyebut Anda dalam sebuah postingan.`,
                KICKED: `<strong>${DATA.sender_name || 'Someone'}</strong> mengeluarkan Anda dari forumnya.`,
                DELETE: `<strong>ADMIN</strong> menghapus ${DATA?.content_type === 'FORUM' ? 'forum' : 'postingan'}.`,
                INVITE_GROUP: `<strong>${DATA.sender_name || 'Someone'}</strong> mengundang Anda untuk bergabung ke grupnya.`,
                INVITE_FORUM: `<strong>${DATA.sender_name || 'Someone'}</strong> mengundang Anda untuk bergabung ke forumnya.`,
                ADMIN_INVITE_FORUM: `<strong>ADMIN</strong> menambahkan Anda ke dalam forum.`,
                WARNING: `<strong>ADMIN</strong> memperingatkan Anda terkait ${DATA.content_type === 'FORUM' ? 'forum' : 'postingan'}.`,
                DEFAULT: `Notifikasi baru`
            };

            const iconMap = {
                LIKE_POST: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>`,

                REPLY_POST: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>`,

                REPLY_COMMENT: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>`,

                MENTION: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>`,

                WARNING: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>`,

                KICKED: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" /></svg>`,

                DELETE: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>`,

                INVITE_GROUP: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>`,

                INVITE_FORUM: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>`,

                ADMIN_INVITE_FORUM: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>`
            };

            const isUnread = IS_READ == 0 || IS_READ === false;
            const link = DATA.link || '#';

            const bgColorClass = {
                red: 'bg-gradient-to-br from-red-400 to-red-500',
                blue: 'bg-gradient-to-br from-blue-500 to-blue-600',
                green: 'bg-gradient-to-br from-green-500 to-green-600',
                purple: 'bg-gradient-to-br from-purple-500 to-purple-600',
                yellow: 'bg-gradient-to-br from-yellow-500 to-yellow-600',
                red: 'bg-gradient-to-br from-red-500 to-red-600',
                indigo: 'bg-gradient-to-br from-indigo-500 to-indigo-600',
                gray: 'bg-gradient-to-br from-gray-400 to-gray-500'
            } [color];

            const borderColorClass = isUnread ? {
                blue: 'border-blue-500 bg-blue-50',
                green: 'border-green-500 bg-green-50',
                purple: 'border-purple-500 bg-purple-50',
                yellow: 'border-yellow-500 bg-yellow-50',
                red: 'border-red-500 bg-red-50',
                indigo: 'border-indigo-500 bg-indigo-50'
            } [color] : 'border-transparent bg-white';

            const dotColorClass = {
                blue: 'bg-blue-500',
                green: 'bg-green-500',
                purple: 'bg-purple-500',
                yellow: 'bg-yellow-500',
                red: 'bg-red-500',
                indigo: 'bg-indigo-500'
            } [color];

            return `
                <div data-notif-id="${ID}" data-link="${link}" data-is-read="${IS_READ}" data-type="${TYPE}" data-target-id="${DATA && DATA.target_id ? DATA.target_id : ''}" class="notification-item flex items-start gap-3 px-4 py-3 hover:bg-gray-100 transition-colors cursor-pointer border-l-4 ${borderColorClass}">
                    
                    <div class="flex-shrink-0 w-10 h-10 rounded-full ${bgColorClass} flex items-center justify-center">
                        ${iconMap[TYPE] || iconMap.LIKE_POST}
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 font-medium line-clamp-2">
                            ${messageMap[TYPE] || messageMap.DEFAULT}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            ${
                            (() => {
                                const date = new Date(CREATED_AT);
                                date.setHours(date.getHours() - 7);

                                const formattedDate = date.toLocaleString('id-ID', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                });

                                return formattedDate.replace('.', ':').replace(',', '') + ' WIB';
                            })()
                            }
                        </p>
                    </div>

                    ${isUnread ? `
                    <div class="unread-indicator flex-shrink-0">
                        <span class="w-2 h-2 ${dotColorClass} rounded-full block"></span>
                    </div>
                    ` : ''}
                </div>`;
        }

        function updateNotificationCount(newUnreadCount) {
            notifCountSpan.textContent = newUnreadCount;
            notifCountMobile.textContent = newUnreadCount;
            unreadTabCount.textContent = newUnreadCount;

            if (newUnreadCount > 0) {
                notifBadge.classList.remove('hidden');
                notifBadge.classList.add('flex');
                notifBadgeMobile.classList.remove('hidden');
                notifBadgeMobile.classList.add('flex');
            } else {
                notifBadge.classList.add('hidden');
                notifBadge.classList.remove('flex');
                notifBadgeMobile.classList.add('hidden');
                notifBadgeMobile.classList.remove('flex');
            }
        }

        function updateContainerVisibility() {
            const unreadItems = unreadContainer.querySelectorAll('[data-notif-id]');
            const readItems = readContainer.querySelectorAll('[data-notif-id]');

            noUnreadMessage.style.display = unreadItems.length === 0 ? 'block' : 'none';
            noReadMessage.style.display = readItems.length === 0 ? 'block' : 'none';
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

                unreadContainer.innerHTML = '';
                readContainer.querySelector('.overflow-y-auto').innerHTML = '';

                if (unreadNotifs.length > 0) {
                    unreadContainer.innerHTML = unreadNotifs.map(createNotificationHTML).join('');
                }

                if (readNotifs.length > 0) {
                    readContainer.querySelector('.overflow-y-auto').innerHTML = readNotifs.map(createNotificationHTML).join('');
                }

                updateContainerVisibility();
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
                            unreadContainer.insertAdjacentHTML('afterbegin', createNotificationHTML(notif));
                        } else {
                            readContainer.querySelector('.overflow-y-auto').insertAdjacentHTML('afterbegin', createNotificationHTML(notif));
                        }
                    });

                    updateContainerVisibility();
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

        function handleNotificationClick(container) {
            container.addEventListener('click', async (event) => {
                const notificationElement = event.target.closest('.notification-item');
                if (!notificationElement) return;

                const notifId = notificationElement.dataset.notifId;
                const isRead = notificationElement.dataset.isRead == '1';
                const link = notificationElement.dataset.link;
                const type = notificationElement.dataset.type;
                const targetId = notificationElement.dataset.targetId;

                // Jika INVITE_FORUM → JANGAN redirect!
                if (type === 'INVITE_GROUP') {
                    if (!isRead) {
                        await markNotifAsRead(notifId, notificationElement);
                    }
                    openPreviewGroupNew(targetId);
                    return;
                }

                if (type === 'INVITE_FORUM') {
                    if (!isRead) {
                        await markNotifAsRead(notifId, notificationElement);
                    }
                    openPreviewForum(targetId);
                    return;
                }

                // Bukan INVITE → mekanisme lama
                if (!isRead) {
                    await markNotifAsRead(notifId, notificationElement);
                }

                if (link && link !== '#') {
                    window.location.href = `${BASEURL}/${link}`;
                }
            });
        }

        async function markNotifAsRead(notifId, notificationElement) {
            try {
                await fetch(`${BASEURL}/notifications/markAsRead`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        notifId
                    })
                });

                const clonedItem = notificationElement.cloneNode(true);

                const allBorderClasses = [
                    'border-blue-500', 'border-green-500', 'border-purple-500', 'border-yellow-500',
                    'border-red-500', 'border-indigo-500',
                    'bg-blue-50', 'bg-green-50', 'bg-purple-50', 'bg-yellow-50',
                    'bg-red-50', 'bg-indigo-50'
                ];
                clonedItem.classList.remove(
                    'border-blue-500', 'border-green-500', 'border-purple-500', 'border-yellow-500',
                    'bg-blue-50', 'bg-green-50', 'bg-purple-50', 'bg-yellow-50'
                );
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
                readContainer.querySelector('.overflow-y-auto').insertAdjacentElement('afterbegin', clonedItem);
                notificationElement.remove();

                const currentCount = parseInt(notifCountSpan.textContent) || 0;
                updateNotificationCount(Math.max(0, currentCount - 1));
                updateContainerVisibility();
            } catch (err) {
                console.error(err);
            }
        }

        notifBtn.addEventListener('click', toggleDropdown);
        notifBtnMobile.addEventListener('click', toggleDropdown);
        window.addEventListener('resize', positionDropdown);

        document.addEventListener('click', (e) => {
            if (!notifBtn.contains(e.target) && !notifBtnMobile.contains(e.target) && !notifDropdown.contains(e.target)) {
                if (!notifDropdown.classList.contains('hidden')) {
                    activeNotifButtonId = null;
                    notifDropdown.classList.add('hidden');
                    notifBtn.parentElement.classList.remove('modal-open');
                    notifBtnMobile.parentElement.classList.remove('modal-open');
                }
            }
        });

        tabUnread.addEventListener('click', () => {
            tabUnread.classList.add('text-blue-600', 'border-b-2', 'border-blue-600', 'bg-white');
            tabUnread.classList.remove('text-gray-600', 'hover:bg-gray-100');
            tabRead.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600', 'bg-white');
            tabRead.classList.add('text-gray-600', 'hover:bg-gray-100');
            unreadContainer.classList.remove('hidden');
            readContainer.classList.add('hidden');
        });

        tabRead.addEventListener('click', () => {
            tabRead.classList.add('text-blue-600', 'border-b-2', 'border-blue-600', 'bg-white');
            tabRead.classList.remove('text-gray-600', 'hover:bg-gray-100');
            tabUnread.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600', 'bg-white');
            tabUnread.classList.add('text-gray-600', 'hover:bg-gray-100');
            readContainer.classList.remove('hidden');
            unreadContainer.classList.add('hidden');
        });

        markAllReadBtn.addEventListener('click', async () => {
            try {
                const response = await fetch(`${BASEURL}/notifications/markAllRead`, {
                    method: 'POST'
                });
                if (response.ok) {
                    const unreadItems = Array.from(unreadContainer.querySelectorAll('[data-notif-id]'));

                    unreadItems.forEach(item => {
                        const clonedItem = item.cloneNode(true);
                        clonedItem.classList.remove('border-blue-500', 'border-green-500', 'border-purple-500', 'border-yellow-500', 'bg-blue-50', 'bg-green-50', 'bg-purple-50', 'bg-yellow-50');
                        clonedItem.classList.add('border-transparent', 'bg-white');

                        const iconWrapper = clonedItem.querySelector('.rounded-full');
                        if (iconWrapper) {
                            iconWrapper.classList.remove('from-blue-500', 'to-blue-600', 'from-green-500', 'to-green-600', 'from-purple-500', 'to-purple-600', 'from-yellow-500', 'to-yellow-600');
                            iconWrapper.classList.add('from-gray-400', 'to-gray-500');
                        }

                        const dot = clonedItem.querySelector('.unread-indicator');
                        if (dot) dot.remove();

                        clonedItem.dataset.isRead = '1';
                        readContainer.querySelector('.overflow-y-auto').insertAdjacentElement('afterbegin', clonedItem);
                    });

                    unreadContainer.innerHTML = '';
                    updateNotificationCount(0);
                    updateContainerVisibility();
                }
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        });

        deleteAllReadBtn.addEventListener('click', async () => {
            try {
                const response = await fetch(`${BASEURL}/notifications/deleteAllRead`, {
                    method: 'POST'
                });

                if (response.ok) {
                    readContainer.querySelector('.overflow-y-auto').innerHTML = '';
                    updateContainerVisibility();
                }
            } catch (error) {
                console.error('Error deleting all read notifications:', error);
            }
        });

        handleNotificationClick(unreadContainer);
        handleNotificationClick(readContainer.querySelector('.overflow-y-auto'));

        loadInitialNotifications().then(() => {
            pollForNotifications();
        });
    });
</script>