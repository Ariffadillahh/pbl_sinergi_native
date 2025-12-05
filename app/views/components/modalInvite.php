<div id="modal-preview-group-new"
    class="hidden fixed inset-0 z-[999999] bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 animate-fadeIn">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-100">

        <button id="invite-group-close" 
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10 cursor-pointer"
                aria-label="Close modal">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div id="invite-group-loading" class="hidden p-12 flex flex-col items-center justify-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Loading group information...</p>
        </div>

        <div id="invite-group-error" class="hidden p-12 flex flex-col items-center justify-center text-center">
            <svg class="w-16 h-16 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to Load Group</h3>
            <p id="invite-group-error-message" class="text-gray-600 mb-4">Something went wrong. Please try again.</p>
            <button id="invite-group-error-retry" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                Retry
            </button>
        </div>

        <div id="invite-group-content" class="hidden p-6">

            <div class="mb-6">
                <div class="w-full flex justify-center mb-4">

                    <div id="invite-group-photo-wrapper"
                        class="relative w-32 h-32 rounded-full overflow-hidden bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center text-white text-3xl font-bold select-none shadow-lg">

                        <img id="invite-group-photo"
                            class="w-full h-full object-cover hidden"
                            alt="Group photo">

                        <span id="invite-group-initials" class="uppercase"></span>

                        <div id="invite-group-private-badge" class="hidden absolute bottom-2 right-2 bg-yellow-500 text-white rounded-full p-1.5 shadow-md">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                    </div>

                </div>

                <div class="text-center">
                    <h2 id="invite-group-name" class="text-2xl font-bold text-gray-900 mb-2"></h2>
                    <p id="invite-group-desc" class="text-gray-600 text-sm line-clamp-3"></p>
                </div>
            </div>

            <div id="invite-group-owner" class="flex items-center gap-3 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 mb-4 hover:shadow-md transition-shadow">
                <img id="invite-group-owner-photo" 
                     class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm"
                     alt="Owner photo">
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Group Owner</p>
                    <p id="invite-group-owner-name" class="font-semibold text-gray-900 truncate"></p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-700">
                        Members (<span id="invite-group-total-members" class="text-blue-600">0</span>)
                    </p>
                    <span class="text-xs text-gray-500" id="invite-group-member-hint">Join this community</span>
                </div>
                
                <div class="flex items-center">
                    <div id="invite-group-members" class="flex -space-x-2"></div>
                    <span id="invite-group-more-members" class="hidden ml-2 text-xs text-gray-500 font-medium"></span>
                </div>
                
                <div id="invite-group-no-members" class="hidden text-center py-4 text-gray-400 text-sm">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p>Be the first member!</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button id="invite-group-decline"
                    class="flex-1 px-4 py-3 text-sm font-medium rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors duration-200 cursor-pointer">
                    Decline
                </button>

                <button id="invite-group-join"
                    class="flex-1 px-4 py-3 text-sm font-medium rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span id="invite-group-join-text">Join Group</span>
                    <svg id="invite-group-join-spinner" class="hidden animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div id="toast-container" 
     class="fixed top-5 right-5 z-[9999] space-y-3 pointer-events-none">
    </div>
</div>

<style>
    /* Transisi masuk (dari kanan ke dalam) */
    .toast-enter {
        transform: translateX(100%);
        opacity: 0;
    }
    .toast-enter-active {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Bounce effect */
    }
    /* Transisi keluar (dari dalam ke kanan) */
    .toast-exit {
        opacity: 1;
        transform: translateX(0);
    }
    .toast-exit-active {
        transition: all 0.5s ease-in;
        transform: translateX(100%);
        opacity: 0;
    }
</style>

<style>
    /* Add this style block if you removed the original one, or ensure it's present */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    #modal-preview-group-new:not(.hidden) {
        animation: fadeIn 0.2s ease-out;
    }

    /* Member avatar hover effect */
    #invite-group-members img {
        transition: transform 0.2s, z-index 0.2s;
    }

    #invite-group-members img:hover {
        transform: scale(1.1) translateY(-2px);
        z-index: 10;
    }
</style>

<script>
    // Pastikan BASEURL terdefinisi di scope global
    // const BASEURL = 'https://domainanda.com'; // Contoh jika BASEURL belum ada

    // DOM Elements for NEW Group Modal
    const modalInviteGroup = document.getElementById('modal-preview-group-new');
    const inviteGroupLoading = document.getElementById('invite-group-loading');
    const inviteGroupError = document.getElementById('invite-group-error');
    const inviteGroupContent = document.getElementById('invite-group-content');
    const inviteGroupErrorMessage = document.getElementById('invite-group-error-message');
    const inviteGroupErrorRetry = document.getElementById('invite-group-error-retry');
    
    const inviteGroupPhotoWrapper = document.getElementById('invite-group-photo-wrapper'); 
    const inviteGroupPhoto = document.getElementById('invite-group-photo');
    const inviteGroupInitials = document.getElementById('invite-group-initials');
    const inviteGroupName = document.getElementById('invite-group-name');
    const inviteGroupDesc = document.getElementById('invite-group-desc');
    const inviteGroupPrivateBadge = document.getElementById('invite-group-private-badge');
    const inviteGroupJoin = document.getElementById('invite-group-join');
    const inviteGroupDecline = document.getElementById('invite-group-decline');
    const inviteGroupClose = document.getElementById('invite-group-close');
    const inviteGroupJoinText = document.getElementById('invite-group-join-text');
    const inviteGroupJoinSpinner = document.getElementById('invite-group-join-spinner');

    const inviteGroupOwnerName = document.getElementById('invite-group-owner-name');
    const inviteGroupOwnerPhoto = document.getElementById('invite-group-owner-photo');
    const inviteGroupMembers = document.getElementById('invite-group-members');
    const inviteGroupTotalMembers = document.getElementById('invite-group-total-members');
    const inviteGroupNoMembers = document.getElementById('invite-group-no-members');
    const inviteGroupMoreMembers = document.getElementById('invite-group-more-members');

    let currentGroupId = null;

    // --- Helper Functions ---
    function escapeHtmlGroup(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function getInitialsGroup(name) {
        if (!name) return 'GM'; 
        const words = name.trim().split(' ');
        if (words.length === 1) {
            return words[0].substring(0, 2).toUpperCase();
        }
        return (words[0][0] + words[words.length - 1][0]).toUpperCase();
    }
    // -------------------------------------------------

    // Show different states
    function showGroupLoading() {
        inviteGroupLoading.classList.remove('hidden');
        inviteGroupError.classList.add('hidden');
        inviteGroupContent.classList.add('hidden');
    }

    function showGroupError(message) {
        inviteGroupLoading.classList.add('hidden');
        inviteGroupError.classList.remove('hidden');
        inviteGroupContent.classList.add('hidden');
        inviteGroupErrorMessage.textContent = message;
    }

    function showGroupContent() {
        inviteGroupLoading.classList.add('hidden');
        inviteGroupError.classList.add('hidden');
        inviteGroupContent.classList.remove('hidden');
    }

    window.openPreviewGroupNew = function(groupId) {
        if (!groupId) {
            console.error('Group ID is required');
            return;
        }

        currentGroupId = groupId;
        modalInviteGroup.classList.remove('hidden');
        showGroupLoading();
        loadGroupInfo(groupId);
    }

    async function loadGroupInfo(groupId) {
        try {
            if (typeof BASEURL === 'undefined') {
                 throw new Error('BASEURL is undefined. Cannot load group info.');
            }
            const response = await fetch(`${BASEURL}/groups/getGroupChatInfo?id=${encodeURIComponent(groupId)}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();

            if (data.success === false) { 
                throw new Error(data.message || 'Failed to load group information from server.');
            }

            renderGroupInfo(data);
            showGroupContent();

        } catch (error) {
            console.error('Error loading group info:', error);
            showGroupError(error.message || 'Failed to load group. Please check network and BASEURL.');
        }
    }

    // Render group information (Perbaikan Final PP Grup/Inisial)
    function renderGroupInfo(data) {
        // Reset wrapper background ke default group (pink gradient)
        inviteGroupPhotoWrapper.classList.remove('bg-gradient-to-br', 'from-blue-500', 'to-blue-600');
        inviteGroupPhotoWrapper.classList.add('bg-gradient-to-br', 'from-pink-500', 'to-pink-600');
        
        // Group photo/initials
        if (data.PHOTO) {
            inviteGroupPhoto.src = `${BASEURL}/storage/groups/photos/${encodeURIComponent(data.PHOTO)}`; 
            inviteGroupPhoto.alt = escapeHtmlGroup(data.NAME);
            inviteGroupPhoto.classList.remove('hidden');
            inviteGroupInitials.classList.add('hidden');
            
            // Handle image load error: revert to initials if photo fails
            inviteGroupPhoto.onerror = function() {
                this.classList.add('hidden');
                inviteGroupInitials.textContent = getInitialsGroup(data.NAME);
                inviteGroupInitials.classList.remove('hidden');
            };
        } else {
            // JIKA TIDAK ADA FOTO (PP Kosong), tampilkan inisial
            inviteGroupPhoto.classList.add('hidden');
            inviteGroupInitials.textContent = getInitialsGroup(data.NAME);
            inviteGroupInitials.classList.remove('hidden');
        }

        // Group name and description
        inviteGroupName.textContent = data.NAME || 'Unknown Group';
        inviteGroupDesc.textContent = data.ABOUT || 'No description available';

        if (data.IS_PRIVATE == 1) { 
            inviteGroupPrivateBadge.classList.remove('hidden');
        } else {
            inviteGroupPrivateBadge.classList.add('hidden');
        }

        // Owner info (Memastikan path foto owner)
        inviteGroupOwnerName.textContent = data.OWNER.NAME || 'Unknown';
        inviteGroupOwnerPhoto.src = data.OWNER.PHOTO 
            ? `${BASEURL}/storage/users/photos/${encodeURIComponent(data.OWNER.PHOTO)}`
            : `${BASEURL}/src/asset/image/default.png`;
        inviteGroupOwnerPhoto.alt = escapeHtmlGroup(data.OWNER.NAME);
        inviteGroupOwnerPhoto.onerror = function() { 
            this.src = `${BASEURL}/src/asset/image/default.png`; 
        }; // Tambah error handler untuk owner photo

        const totalMembers = parseInt(data.TOTAL_MEMBERS) || (data.MEMBERS ? data.MEMBERS.length : 0);
        inviteGroupTotalMembers.textContent = totalMembers;

        // Render member avatars
        if (data.MEMBERS && data.MEMBERS.length > 0) {
            const maxDisplay = 7;
            const membersToShow = data.MEMBERS.slice(0, maxDisplay);
            const remainingCount = data.MEMBERS.length - maxDisplay;

            inviteGroupMembers.innerHTML = membersToShow.map(member => {
                const photoUrl = member.PHOTO 
                    ? `${BASEURL}/storage/users/photos/${encodeURIComponent(member.PHOTO)}`
                    : `${BASEURL}/src/asset/image/default.png`;
                
                return `
                    <img src="${photoUrl}"
                        alt="${escapeHtmlGroup(member.NAME)}"
                        title="${escapeHtmlGroup(member.NAME)}"
                        class="w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm hover:shadow-md cursor-pointer"
                        onerror="this.src='${BASEURL}/src/asset/image/default.png'">
                `;
            }).join('');

            if (remainingCount > 0) {
                inviteGroupMoreMembers.textContent = `+${remainingCount} more`;
                inviteGroupMoreMembers.classList.remove('hidden');
            } else {
                inviteGroupMoreMembers.classList.add('hidden');
            }

            inviteGroupNoMembers.classList.add('hidden');
        } else {
            inviteGroupMembers.innerHTML = '';
            inviteGroupMoreMembers.classList.add('hidden');
            inviteGroupNoMembers.classList.remove('hidden');
        }

        inviteGroupJoin.dataset.groupId = data.ID; 
    }

    // Tambahkan fungsi ini di dalam <script> modal group (setelah fungsi showToastGroup)

    async function deleteInviteNotification(groupId, notificationType = 'INVITE_GROUP') {
        try {
            const response = await fetch(`${BASEURL}/notifications/deleteInviteNotif`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    target_id: groupId,
                    type: notificationType
                })
            });

            if (!response.ok) {
                console.error('Failed to delete notification');
            }
        } catch (error) {
            console.error('Error deleting notification:', error);
        }
    }

    // UPDATE EVENT LISTENER JOIN BUTTON - Ganti yang lama dengan ini:
    inviteGroupJoin.addEventListener('click', async function() {
        const groupId = this.dataset.groupId;
        
        if (!groupId) {
            showToastGroup('Group ID not found', 'error'); 
            return;
        }

        if (typeof BASEURL === 'undefined') {
            showToastGroup('BASEURL is undefined. Cannot join group.', 'error');
            return;
        }

        this.disabled = true;
        inviteGroupJoinText.classList.add('hidden');
        inviteGroupJoinSpinner.classList.remove('hidden');

        try {
            // 1. Join Group
            const response = await fetch(`${BASEURL}/groups/joinViaInvite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    group_chat_id: groupId
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const json = await response.json();

            if (json.success) {
                // 2. Hapus Notifikasi
                await deleteInviteNotification(groupId, 'INVITE_GROUP');
                
                showToastGroup('Successfully joined the group!', 'success');
                setTimeout(() => {
                    const redirectUrl = json.redirect 
                        ? (json.redirect.startsWith('http') ? json.redirect : BASEURL + json.redirect)
                        : BASEURL; 
                        
                    window.location.href = redirectUrl;
                }, 1000);
            } else {
                throw new Error(json.message || 'Failed to join group due to server response.');
            }

        } catch (error) {
            console.error('Error joining group:', error);
            showToastGroup(error.message || 'Failed to join group. Please contact support.', 'error');
            
            this.disabled = false;
            inviteGroupJoinText.classList.remove('hidden');
            inviteGroupJoinSpinner.classList.add('hidden');
        }
    });

    // UPDATE EVENT LISTENER DECLINE BUTTON - Tambahkan handler khusus untuk decline:
    inviteGroupDecline.addEventListener('click', async function() {
        // Hapus notifikasi jika user klik "Maybe Later"
        if (currentGroupId) {
            await deleteInviteNotification(currentGroupId, 'INVITE_GROUP');
        }
        closeGroupModal();
    });

    // FUNGSI closeGroupModal - TIDAK menghapus notifikasi (hanya untuk tombol X dan klik outside)
    function closeGroupModal() {
        modalInviteGroup.classList.add('hidden');
        currentGroupId = null;
        
        inviteGroupJoin.disabled = false;
        inviteGroupJoinText.classList.remove('hidden');
        inviteGroupJoinSpinner.classList.add('hidden');
    }

    inviteGroupDecline.addEventListener('click', closeGroupModal);
    inviteGroupClose.addEventListener('click', closeGroupModal);

    modalInviteGroup.addEventListener('click', function(e) {
        if (e.target === this) {
            closeGroupModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modalInviteGroup.classList.contains('hidden')) {
            closeGroupModal();
        }
    });

    inviteGroupErrorRetry.addEventListener('click', function() {
        if (currentGroupId) {
            showGroupLoading();
            loadGroupInfo(currentGroupId);
        }
    });

function showToastGroup(message, type = 'info', duration = 3000) {
    const container = document.getElementById('toast-container');
    if (!container) {
        console.error('Toast container not found!');
        alert(`[${type.toUpperCase()}] ${message}`); // Fallback
        return;
    }

    let bgColorClass = '';
    let icon = '';

    // Menentukan warna dan ikon berdasarkan tipe
    switch (type) {
        case 'success':
            bgColorClass = 'bg-green-500';
            icon = '✅';
            break;
        case 'error':
            bgColorClass = 'bg-red-500';
            icon = '❌';
            break;
        case 'info':
        default:
            bgColorClass = 'bg-blue-500';
            icon = 'ℹ️';
            break;
    }

    // Membuat elemen Toast
    const toast = document.createElement('div');
    toast.className = `toast-enter-active toast-enter w-full max-w-xs p-4 text-white rounded-lg shadow-xl ${bgColorClass} flex items-center pointer-events-auto`;
    toast.innerHTML = `
        <div class="text-xl mr-3">${icon}</div>
        <div>${message}</div>
    `;

    container.appendChild(toast);
    
    // Animate In (Setelah ditambahkan ke DOM)
    setTimeout(() => {
        toast.classList.remove('toast-enter', 'toast-enter-active');
    }, 50); 
    
    // Animate Out dan Hapus
    setTimeout(() => {
        toast.classList.add('toast-exit-active', 'toast-exit');
        
        // Hapus elemen setelah transisi selesai
        setTimeout(() => {
            container.removeChild(toast);
        }, 500); // 500ms sesuai durasi transisi
    }, duration);
}

</script>