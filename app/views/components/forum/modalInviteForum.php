<!-- Enhanced Forum Invite Preview Modal -->
<div id="modal-preview-forum"
    class="hidden fixed inset-0 z-[999999] bg-black/40 backdrop-blur-sm flex items-center justify-center p-4 animate-fadeIn">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300 scale-100">

        <!-- Close Button -->
        <button id="invite-forum-close" 
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors z-10"
                aria-label="Close modal">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Loading State -->
        <div id="invite-loading" class="hidden p-12 flex flex-col items-center justify-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-600">Loading forum information...</p>
        </div>

        <!-- Error State -->
        <div id="invite-error" class="hidden p-12 flex flex-col items-center justify-center text-center">
            <svg class="w-16 h-16 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Failed to Load Forum</h3>
            <p id="invite-error-message" class="text-gray-600 mb-4">Something went wrong. Please try again.</p>
            <button id="invite-error-retry" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Retry
            </button>
        </div>

        <!-- Main Content -->
        <div id="invite-content" class="hidden p-6">

            <!-- HEADER -->
            <div class="mb-6">
                <div class="w-full flex justify-center mb-4">

                    <!-- WRAPPER FOTO/INITIAL -->
                    <div id="invite-forum-photo-wrapper"
                        class="relative w-32 h-32 rounded-full overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-3xl font-bold select-none shadow-lg">

                        <img id="invite-forum-photo"
                            class="w-full h-full object-cover hidden"
                            alt="Forum photo">

                        <span id="invite-forum-initials" class="uppercase"></span>

                        <!-- Private Badge -->
                        <div id="invite-private-badge" class="hidden absolute bottom-2 right-2 bg-yellow-500 text-white rounded-full p-1.5 shadow-md">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                    </div>

                </div>

                <div class="text-center">
                    <h2 id="invite-forum-name" class="text-2xl font-bold text-gray-900 mb-2"></h2>
                    <p id="invite-forum-desc" class="text-gray-600 text-sm line-clamp-3"></p>
                </div>
            </div>

            <!-- OWNER -->
            <div id="invite-owner" class="flex items-center gap-3 p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl border border-gray-200 mb-4 hover:shadow-md transition-shadow">
                <img id="invite-owner-photo" 
                     class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm"
                     alt="Owner photo">
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Forum Owner</p>
                    <p id="invite-owner-name" class="font-semibold text-gray-900 truncate"></p>
                </div>
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>

            <!-- MEMBER COUNT & PREVIEW -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-700">
                        Members (<span id="invite-total-members" class="text-blue-600">0</span>)
                    </p>
                    <span class="text-xs text-gray-500" id="invite-member-hint">Join this community</span>
                </div>
                
                <!-- Member Avatars -->
                <div class="flex items-center">
                    <div id="invite-members" class="flex -space-x-2"></div>
                    <span id="invite-more-members" class="hidden ml-2 text-xs text-gray-500 font-medium"></span>
                </div>
                
                <!-- Empty Members State -->
                <div id="invite-no-members" class="hidden text-center py-4 text-gray-400 text-sm">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p>Be the first member!</p>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex gap-3">
                <button id="invite-forum-decline"
                    class="flex-1 px-4 py-3 text-sm font-medium rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors duration-200">
                    Decline
                </button>

                <button id="invite-forum-join"
                    class="flex-1 px-4 py-3 text-sm font-medium rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span id="invite-join-text">Join Forum</span>
                    <svg id="invite-join-spinner" class="hidden animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
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

    #modal-preview-forum:not(.hidden) {
        animation: fadeIn 0.2s ease-out;
    }

    /* Member avatar hover effect */
    #invite-members img {
        transition: transform 0.2s, z-index 0.2s;
    }

    #invite-members img:hover {
        transform: scale(1.1) translateY(-2px);
        z-index: 10;
    }
</style>

<script>
// const BASEURL = '<?php echo BASEURL; ?>';
(function() {
    'use strict';

    // Toast notification function with fallback
    function showToast(message, type = 'info') {
        if (typeof window.showToast === 'function') {
            window.showToast(message, type);
        } else {
            // Fallback: create simple toast
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-[999999] ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 
                'bg-blue-500'
            } text-white`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    }

    // Wait for DOM to be fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initModal);
    } else {
        initModal();
    }

    function initModal() {
        // DOM Elements
        const modalInviteForum = document.getElementById('modal-preview-forum');
        const inviteLoading = document.getElementById('invite-loading');
        const inviteError = document.getElementById('invite-error');
        const inviteContent = document.getElementById('invite-content');
        const inviteErrorMessage = document.getElementById('invite-error-message');
        const inviteErrorRetry = document.getElementById('invite-error-retry');
        
        const inviteForumPhoto = document.getElementById('invite-forum-photo');
        const inviteForumInitials = document.getElementById('invite-forum-initials');
        const inviteForumName = document.getElementById('invite-forum-name');
        const inviteForumDesc = document.getElementById('invite-forum-desc');
        const invitePrivateBadge = document.getElementById('invite-private-badge');
        const inviteForumJoin = document.getElementById('invite-forum-join');
        const inviteForumDecline = document.getElementById('invite-forum-decline');
        const inviteForumClose = document.getElementById('invite-forum-close');
        const inviteJoinText = document.getElementById('invite-join-text');
        const inviteJoinSpinner = document.getElementById('invite-join-spinner');

        const inviteOwnerName = document.getElementById('invite-owner-name');
        const inviteOwnerPhoto = document.getElementById('invite-owner-photo');
        const inviteMembers = document.getElementById('invite-members');
        const inviteTotalMembers = document.getElementById('invite-total-members');
        const inviteNoMembers = document.getElementById('invite-no-members');
        const inviteMoreMembers = document.getElementById('invite-more-members');

        let currentForumId = null;

        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Helper function to get initials
        function getInitials(name) {
            if (!name) return 'FM';
            const words = name.trim().split(' ');
            if (words.length === 1) {
                return words[0].substring(0, 2).toUpperCase();
            }
            return (words[0][0] + words[words.length - 1][0]).toUpperCase();
        }

        // Show different states
        function showLoading() {
            inviteLoading.classList.remove('hidden');
            inviteError.classList.add('hidden');
            inviteContent.classList.add('hidden');
        }

        function showError(message) {
            inviteLoading.classList.add('hidden');
            inviteError.classList.remove('hidden');
            inviteContent.classList.add('hidden');
            inviteErrorMessage.textContent = message;
        }

        function showContent() {
            inviteLoading.classList.add('hidden');
            inviteError.classList.add('hidden');
            inviteContent.classList.remove('hidden');
        }

        
        // Open modal with forum ID
        function openPreviewForum(forumId) {
            if (!forumId) {
                console.error('Forum ID is required');
                return;
            }

            currentForumId = forumId;
            modalInviteForum.classList.remove('hidden');
            showLoading();
            loadForumInfo(forumId);
        }

        // Load forum information
        async function loadForumInfo(forumId) {
            try {
                const response = await fetch(`${BASEURL}/forum/getForumInfo?id=${encodeURIComponent(forumId)}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Failed to load forum information');
                }

                renderForumInfo(data);
                showContent();

            } catch (error) {
                console.error('Error loading forum info:', error);
                showError(error.message || 'Failed to load forum. Please try again.');
            }
        }

        // Render forum information
        function renderForumInfo(data) {
            // Forum photo/initials
            if (data.PHOTO) {
                inviteForumPhoto.src = `${BASEURL}/storage/forums/photos/${encodeURIComponent(data.PHOTO)}`;
                inviteForumPhoto.alt = escapeHtml(data.NAME);
                inviteForumPhoto.classList.remove('hidden');
                inviteForumInitials.classList.add('hidden');
                
                // Handle image load error
                inviteForumPhoto.onerror = function() {
                    this.classList.add('hidden');
                    inviteForumInitials.textContent = getInitials(data.NAME);
                    inviteForumInitials.classList.remove('hidden');
                };
            } else {
                inviteForumPhoto.classList.add('hidden');
                inviteForumInitials.textContent = getInitials(data.NAME);
                inviteForumInitials.classList.remove('hidden');
            }

            // Forum name and description
            inviteForumName.textContent = data.NAME || 'Unknown Forum';
            inviteForumDesc.textContent = data.ABOUT || 'No description available';

            // Private badge
            if (data.IS_PRIVATE == 1) {
                invitePrivateBadge.classList.remove('hidden');
            } else {
                invitePrivateBadge.classList.add('hidden');
            }

            // Owner info
            inviteOwnerName.textContent = data.OWNER.NAME || 'Unknown';
            inviteOwnerPhoto.src = data.OWNER.PHOTO 
                ? `${BASEURL}/storage/users/photos/${encodeURIComponent(data.OWNER.PHOTO)}`
                : `${BASEURL}/src/asset/image/default.png`;
            inviteOwnerPhoto.alt = escapeHtml(data.OWNER.NAME);

            // Member count
            const totalMembers = parseInt(data.TOTAL_MEMBERS) || 0;
            inviteTotalMembers.textContent = totalMembers;

            // Render member avatars
            if (data.MEMBERS && data.MEMBERS.length > 0) {
                const maxDisplay = 7;
                const membersToShow = data.MEMBERS.slice(0, maxDisplay);
                const remainingCount = data.MEMBERS.length - maxDisplay;

                inviteMembers.innerHTML = membersToShow.map(member => {
                    const photoUrl = member.PHOTO 
                        ? `${BASEURL}/storage/users/photos/${encodeURIComponent(member.PHOTO)}`
                        : `${BASEURL}/src/asset/image/default.png`;
                    
                    return `
                        <img src="${photoUrl}"
                             alt="${escapeHtml(member.NAME)}"
                             title="${escapeHtml(member.NAME)}"
                             class="w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm hover:shadow-md cursor-pointer"
                             onerror="this.src='${BASEURL}/src/asset/image/default.png'">
                    `;
                }).join('');

                if (remainingCount > 0) {
                    inviteMoreMembers.textContent = `+${remainingCount} more`;
                    inviteMoreMembers.classList.remove('hidden');
                } else {
                    inviteMoreMembers.classList.add('hidden');
                }

                inviteNoMembers.classList.add('hidden');
            } else {
                inviteMembers.innerHTML = '';
                inviteMoreMembers.classList.add('hidden');
                inviteNoMembers.classList.remove('hidden');
            }

            // Store forum ID in join button
            inviteForumJoin.dataset.forumId = data.ID;
        }

        // Tambahkan fungsi ini di dalam IIFE modal forum (setelah fungsi showToast)

        async function deleteInviteNotification(forumId, notificationType = 'INVITE_FORUM') {
            try {
                const response = await fetch(`${BASEURL}/notifications/deleteInviteNotif`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        target_id: forumId,
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

        // UPDATE EVENT LISTENER JOIN BUTTON - Ganti yang lama:
        inviteForumJoin.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const forumId = this.dataset.forumId;
            
            console.log('Join button clicked, Forum ID:', forumId);
            
            if (!forumId) {
                showToast('Forum ID not found', 'error');
                return;
            }

            this.disabled = true;
            inviteJoinText.classList.add('hidden');
            inviteJoinSpinner.classList.remove('hidden');

            try {
                // 1. Join Forum
                const formData = new URLSearchParams();
                formData.append('forum_id', forumId);
                
                const response = await fetch(`${BASEURL}/forum/joinViaInvite`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData.toString()
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const json = await response.json();

                if (json.success) {
                    // 2. Hapus Notifikasi (Support INVITE_FORUM dan ADMIN_INVITE_FORUM)
                    await deleteInviteNotification(forumId, 'INVITE_FORUM');
                    await deleteInviteNotification(forumId, 'ADMIN_INVITE_FORUM');
                    
                    showToast('Successfully joined the forum!', 'success');
                    setTimeout(() => {
                        window.location.href = json.redirect;
                    }, 1000);
                } else {
                    throw new Error(json.message || 'Failed to join forum');
                }

            } catch (error) {
                console.error('Error joining forum:', error);
                showToast(error.message || 'Failed to join forum', 'error');
                
                this.disabled = false;
                inviteJoinText.classList.remove('hidden');
                inviteJoinSpinner.classList.add('hidden');
            }
        });

        // UPDATE EVENT LISTENER DECLINE BUTTON - Tambahkan handler khusus:
        inviteForumDecline.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Decline button clicked');
            
            // Hapus notifikasi jika user klik "Maybe Later"
            if (currentForumId) {
                await deleteInviteNotification(currentForumId, 'INVITE_FORUM');
                await deleteInviteNotification(currentForumId, 'ADMIN_INVITE_FORUM');
            }
            closeModal();
        });

        // FUNGSI closeModal - TIDAK menghapus notifikasi (hanya untuk tombol X dan klik outside)
        function closeModal() {
            modalInviteForum.classList.add('hidden');
            currentForumId = null;
            
            inviteForumJoin.disabled = false;
            inviteJoinText.classList.remove('hidden');
            inviteJoinSpinner.classList.add('hidden');
        }

        inviteForumDecline.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Decline button clicked'); // Debug log
            closeModal();
        });

        inviteForumClose.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Close button clicked'); // Debug log
            closeModal();
        });

        // Close on outside click
        modalInviteForum.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modalInviteForum.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Retry button
        inviteErrorRetry.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (currentForumId) {
                showLoading();
                loadForumInfo(currentForumId);
            }
        });

        // Make function globally available
        window.openPreviewForum = openPreviewForum;
        
        console.log('Forum invite modal initialized'); // Debug log
    }
})();
</script>