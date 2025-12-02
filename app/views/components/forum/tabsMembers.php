<div class="tab-content hidden" data-content="people">
    <?php
    $roleClasses = [
        "MAHASISWA" => "bg-blue-100 text-blue-800",
        "ADMIN"     => "bg-red-100 text-red-800",
        "DOSEN"     => "bg-green-100 text-green-800",
        "MITRA"     => "bg-gray-100 text-gray-800",
        "ALUMNI"    => "bg-yellow-100 text-yellow-800"
    ];
    
    $roleTranslations = [
        "MAHASISWA" => "STUDENT",
        "ADMIN"     => "ADMIN",
        "DOSEN"     => "LECTURER",
        "MITRA"     => "PARTNER",
        "ALUMNI"    => "ALUMNI"
    ];
    
    // Function to format date
    function formatJoinedDate($dateString) {
        if (empty($dateString)) return '';
        $timestamp = strtotime($dateString);
        return date('d F Y', $timestamp);
    }

    $membersForumFiltered = array_filter($membersForum ?? [], function ($m) use ($forumById) {
        return $m['USER_ID'] !== $forumById['OWNER_ID'];
    });

    // Check if current user is owner
    $isOwner = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $forumById['OWNER_ID'];
    ?>

    <div class="bg-white rounded-lg drop-shadow p-5">
        <!-- Owner Section -->
        <div id="Owner" class="flex flex-col gap-3">
            <p class="font-semibold leading-5">Owner</p>
            <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 min-w-0">
                <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                    <img src="<?= !empty($forumById['PATH_PHOTO_OWNER'])
                                    ? BASEURL . '/storage/users/photos/' . $forumById['PATH_PHOTO_OWNER']
                                    : BASEURL . '/src/asset/image/default.png' ?>"
                        class="w-full h-full object-cover border border-gray-200" alt="Owner Photo">
                </div>

                <div class="flex flex-col flex-1 gap-[6px] min-w-0">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold truncate"><?= htmlspecialchars($forumById["OWNER_NAME"] ?? 'Unknown') ?></p>
                        </div>
                        <?php
                        $roleOwner = $forumById["ROLE_OWNER"] ?? '';
                        $colorClassOwner = $roleClasses[$roleOwner] ?? "bg-gray-100 text-gray-800";
                        $translatedRoleOwner = $roleTranslations[$roleOwner] ?? $roleOwner;
                        ?>
                        <div class="flex-shrink-0">
                            <span class="<?= $colorClassOwner ?> text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                <?= htmlspecialchars($translatedRoleOwner) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Section -->
        <div id="Members" class="flex flex-col gap-3 mt-6">
            <div class="flex items-center justify-between">
                <p class="font-semibold leading-5">Members (<?= count($membersForumFiltered) ?>)</p>
                
                <?php if ($isOwner): ?>
                    <button onclick="openAddMemberModal()" 
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="font-medium">Add Member</span>
                    </button>
                <?php endif; ?>
            </div>

            <div class="flex flex-col gap-3">
                <?php if (empty($membersForumFiltered)) : ?>
                    <div class="text-center py-4 text-gray-500 text-sm">
                        No other members in this forum yet.
                    </div>
                <?php else : ?>
                    <?php foreach ($membersForumFiltered as $member): ?>
                        <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 min-w-0">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                                    <img src="<?= !empty($member['PATH_PHOTO'])
                                                    ? BASEURL . '/storage/users/photos/' . $member['PATH_PHOTO']
                                                    : BASEURL . '/src/asset/image/default.png' ?>"
                                        class="w-full h-full object-cover" alt="Member Photo">
                                </div>

                                <div class="flex flex-col flex-1 gap-[6px] min-w-0">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold truncate"><?= htmlspecialchars($member["FULL_NAME"]) ?></p>
                                        </div>
                                        <?php
                                        $roleMember = $member["ROLE"] ?? '';
                                        $colorClassMember = $roleClasses[$roleMember] ?? "bg-gray-100 text-gray-800";
                                        $translatedRoleMember = $roleTranslations[$roleMember] ?? $roleMember;
                                        ?>
                                        <div class="flex-shrink-0">
                                            <span class="<?= $colorClassMember ?> text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                                <?= htmlspecialchars($translatedRoleMember) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex font-medium text-sm text-heyhao-secondary gap-0.5 items-center">
                                        <p class="text-gray-500">Joined:</p>
                                        <p class="text-gray-600"><?= formatJoinedDate($member["JOINED_AT"]) ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php if ($isOwner): ?>
                                <button onclick="removeMember('<?= $member['USER_ID'] ?>', '<?= htmlspecialchars($member['FULL_NAME']) ?>')"
                                        class="flex-shrink-0 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                                        title="Remove member">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Member Modal -->
<div id="addMemberModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-md bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-xl font-semibold">Add Member to Forum</h3>
            <button onclick="closeAddMemberModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="flex-1 overflow-y-auto p-6">
            <!-- Search Box -->
            <div class="mb-4">
                <div class="relative">
                    <input type="text" 
                           id="searchUserInput" 
                           placeholder="Search users by name or username..."
                           class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           oninput="handleSearchInput()">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loadingUsers" class="text-center py-8 hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Loading users...</p>
            </div>

            <!-- User List -->
            <div id="userList" class="space-y-2">
                <!-- Users will be loaded here dynamically -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-8 text-gray-500 hidden">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p>No users found</p>
            </div>
        </div>
    </div>
</div>

<!-- Add Member Confirmation Modal -->
<div id="modal-add-member"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden justify-center items-center">
    <div class="bg-white rounded-2xl p-6 w-[90%] max-w-md shadow-lg animate-fadeIn">
        <!-- Icon -->
        <div class="flex justify-center mb-4">
            <svg class="w-14 h-14 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
        </div>

        <!-- Title -->
        <h2 class="text-lg font-semibold text-center text-gray-900">Add Member</h2>

        <!-- Message -->
        <p class="text-center text-sm text-gray-600 mt-2" id="add-member-message">
            Are you sure you want to add this member to the forum?
        </p>

        <!-- Buttons -->
        <div class="flex mt-6 gap-3">
            <button id="btn-cancel-add-member"
                class="flex-1 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition">
                Cancel
            </button>

            <button id="btn-confirm-add-member"
                class="flex-1 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium transition">
                Yes, Add
            </button>
        </div>
    </div>
</div>

<!-- Remove Member Confirmation Modal -->
<div id="modal-remove-member"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden justify-center items-center">
    <div class="bg-white rounded-2xl p-6 w-[90%] max-w-md shadow-lg animate-fadeIn">
        <!-- Icon -->
        <div class="flex justify-center mb-4">
            <svg class="w-14 h-14 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
        </div>

        <!-- Title -->
        <h2 class="text-lg font-semibold text-center text-gray-900">Remove Member</h2>

        <!-- Message -->
        <p class="text-center text-sm text-gray-600 mt-2" id="remove-member-message">
            Are you sure you want to remove this member from the forum?
        </p>

        <!-- Buttons -->
        <div class="flex mt-6 gap-3">
            <button id="btn-cancel-remove-member"
                class="flex-1 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition">
                Cancel
            </button>

            <button id="btn-confirm-remove-member"
                class="flex-1 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition">
                Yes, Remove
            </button>
        </div>
    </div>
</div>

<!-- Success/Error Toast Modal -->
<div id="toast-notification"
    class="fixed top-4 right-4 bg-white rounded-xl shadow-lg p-4 w-[90%] max-w-sm z-50 hidden transform transition-all duration-300">
    <div class="flex items-center gap-3">
        <div id="toast-icon" class="flex-shrink-0">
            <!-- Icon will be inserted here -->
        </div>
        <div class="flex-1">
            <p id="toast-message" class="text-sm font-medium text-gray-900"></p>
        </div>
        <button onclick="hideToast()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
const forumId = '<?= $forumById['ID'] ?>';
let searchTimeout = null;
let pendingAddMember = null;
let pendingRemoveMember = null;

// Toast notification functions
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast-notification');
    const toastMessage = document.getElementById('toast-message');
    const toastIcon = document.getElementById('toast-icon');
    
    toastMessage.textContent = message;
    
    if (type === 'success') {
        toastIcon.innerHTML = `
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        `;
    } else {
        toastIcon.innerHTML = `
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        `;
    }
    
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        hideToast();
    }, 3000);
}

function hideToast() {
    document.getElementById('toast-notification').classList.add('hidden');
}

// Modal functions
function showModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('flex');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
}

// Open modal and load initial users
function openAddMemberModal() {
    document.getElementById('addMemberModal').classList.remove('hidden');
    document.getElementById('searchUserInput').value = '';
    searchUsers('');
}

// Close modal
function closeAddMemberModal() {
    document.getElementById('addMemberModal').classList.add('hidden');
    document.getElementById('searchUserInput').value = '';
    document.getElementById('userList').innerHTML = '';
}

// Handle search input with debounce
function handleSearchInput() {
    clearTimeout(searchTimeout);
    const searchTerm = document.getElementById('searchUserInput').value;
    
    searchTimeout = setTimeout(() => {
        searchUsers(searchTerm);
    }, 300);
}

// Search users with live search
async function searchUsers(searchTerm) {
    const loadingEl = document.getElementById('loadingUsers');
    const userListEl = document.getElementById('userList');
    const emptyStateEl = document.getElementById('emptyState');
    
    loadingEl.classList.remove('hidden');
    userListEl.innerHTML = '';
    emptyStateEl.classList.add('hidden');
    
    try {
        const formData = new FormData();
        formData.append('forum_id', forumId);
        formData.append('search', searchTerm);
        
        const response = await fetch('<?= BASEURL ?>/forum/searchAvailableUsers', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        loadingEl.classList.add('hidden');
        
        if (data.success && data.users && data.users.length > 0) {
            renderUsers(data.users);
        } else {
            emptyStateEl.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error searching users:', error);
        loadingEl.classList.add('hidden');
        showToast('Error loading users. Please try again.', 'error');
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function escapeAttribute(text) {
    return text.replace(/[&<>"']/g, function(char) {
        const entities = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        };
        return entities[char];
    });
}

// Render users
function renderUsers(users) {
    const userListEl = document.getElementById('userList');
    const emptyStateEl = document.getElementById('emptyState');
    
    if (!users || users.length === 0) {
        userListEl.innerHTML = '';
        emptyStateEl.classList.remove('hidden');
        return;
    }
    
    emptyStateEl.classList.add('hidden');
    
    const roleClasses = {
        'MAHASISWA': 'bg-blue-100 text-blue-800',
        'ADMIN': 'bg-red-100 text-red-800',
        'DOSEN': 'bg-green-100 text-green-800',
        'MITRA': 'bg-gray-100 text-gray-800',
        'ALUMNI': 'bg-yellow-100 text-yellow-800'
    };
    
    const roleTranslations = {
        'MAHASISWA': 'STUDENT',
        'ADMIN': 'ADMIN',
        'DOSEN': 'LECTURER',
        'MITRA': 'PARTNER',
        'ALUMNI': 'ALUMNI'
    };
    
    userListEl.innerHTML = '';
    
    users.forEach(user => {
        const photoUrl = user.PATH_PHOTO 
            ? `<?= BASEURL ?>/storage/users/photos/${escapeAttribute(user.PATH_PHOTO)}` 
            : `<?= BASEURL ?>/src/asset/image/default.png`;
        
        const roleClass = roleClasses[user.ROLE] || 'bg-gray-100 text-gray-800';
        const translatedRole = roleTranslations[user.ROLE] || user.ROLE;
        
        const userDiv = document.createElement('div');
        userDiv.className = 'flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors';
        
        userDiv.innerHTML = `
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <img src="${escapeAttribute(photoUrl)}" 
                     class="w-12 h-12 rounded-full object-cover"
                     alt="${escapeAttribute(user.FULL_NAME)}"
                     onerror="this.src='<?= BASEURL ?>/src/asset/image/default.png'">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold truncate">${escapeHtml(user.FULL_NAME)}</p>
                    <p class="text-sm text-gray-500 truncate">@${escapeHtml(user.USERNAME)}</p>
                </div>
                <span class="${roleClass} text-xs font-medium px-2.5 py-0.5 rounded-sm whitespace-nowrap">
                    ${escapeHtml(translatedRole)}
                </span>
            </div>
        `;
        
        const addButton = document.createElement('button');
        addButton.className = 'ml-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors whitespace-nowrap';
        addButton.textContent = 'Add';
        addButton.addEventListener('click', () => showAddMemberConfirmation(user.ID, user.FULL_NAME));
        
        userDiv.appendChild(addButton);
        userListEl.appendChild(userDiv);
    });
}

// Show add member confirmation
function showAddMemberConfirmation(userId, userName) {
    pendingAddMember = { userId, userName };
    document.getElementById('add-member-message').textContent = 
        `Are you sure you want to add ${userName} to this forum?`;
    showModal('modal-add-member');
}

async function addMemberToForum() {
    if (!pendingAddMember) return;
    
    const { userId, userName } = pendingAddMember;
    
    try {
        const formData = new FormData();
        formData.append('forum_id', forumId);
        formData.append('user_id', userId);
        
        const response = await fetch('<?= BASEURL ?>/forum/addMemberByOwner', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        hideModal('modal-add-member');
        
        if (data.success) {
            showToast(`Invitation sent to ${userName}`, 'success');
            closeAddMemberModal();
            // Don't reload page since user hasn't joined yet
        } else {
            showToast(data.message || 'Failed to invite member', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        hideModal('modal-add-member');
        showToast('An error occurred while inviting member', 'error');
    }
    
    pendingAddMember = null;
}

// Show remove member confirmation
function removeMember(userId, userName) {
    pendingRemoveMember = { userId, userName };
    document.getElementById('remove-member-message').textContent = 
        `Are you sure you want to remove ${userName} from this forum?`;
    showModal('modal-remove-member');
}

// Remove member from forum
async function confirmRemoveMember() {
    if (!pendingRemoveMember) return;
    
    const { userId, userName } = pendingRemoveMember;
    
    try {
        const formData = new FormData();
        formData.append('forum_id', forumId);
        formData.append('user_id', userId);
        
        const response = await fetch('<?= BASEURL ?>/forum/removeMemberByOwner', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        hideModal('modal-remove-member');
        
        if (data.success) {
            showToast(data.message || `${userName} has been removed successfully`, 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(data.message || 'Failed to remove member', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        hideModal('modal-remove-member');
        showToast('An error occurred while removing member', 'error');
    }
    
    pendingRemoveMember = null;
}

// Event listeners for modal buttons
document.getElementById('btn-cancel-add-member')?.addEventListener('click', () => {
    hideModal('modal-add-member');
    pendingAddMember = null;
});

document.getElementById('btn-confirm-add-member')?.addEventListener('click', addMemberToForum);

document.getElementById('btn-cancel-remove-member')?.addEventListener('click', () => {
    hideModal('modal-remove-member');
    pendingRemoveMember = null;
});

document.getElementById('btn-confirm-remove-member')?.addEventListener('click', confirmRemoveMember);

['modal-add-member', 'modal-remove-member'].forEach(modalId => {
    document.getElementById(modalId)?.addEventListener('click', function(e) {
        if (e.target === this) {
            hideModal(modalId);
            if (modalId === 'modal-add-member') pendingAddMember = null;
            if (modalId === 'modal-remove-member') pendingRemoveMember = null;
        }
    });
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        if (!document.getElementById('modal-add-member').classList.contains('hidden')) {
            hideModal('modal-add-member');
            pendingAddMember = null;
        }
        if (!document.getElementById('modal-remove-member').classList.contains('hidden')) {
            hideModal('modal-remove-member');
            pendingRemoveMember = null;
        }
        if (!document.getElementById('addMemberModal').classList.contains('hidden')) {
            closeAddMemberModal();
        }
    }
});

document.getElementById('addMemberModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddMemberModal();
    }
});
</script>