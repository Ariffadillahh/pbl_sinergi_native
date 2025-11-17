<div id="modal-manage-members"
    class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">

    <div class="relative p-4 w-full max-w-3xl max-h-[120vh]">
        <div class="relative bg-white rounded-xl shadow-lg flex flex-col h-full border border-gray-200">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 bg-gray-50 rounded-t">
                <h3 class="text-lg font-semibold text-gray-800">Manage Members</h3>
                <button id="btn-close-manage-members"
                    class="text-gray-500 hover:text-black transition text-xl">
                    ✕
                </button>
            </div>

            <!-- TAB BUTTONS -->
            <div class="flex border-b border-gray-200">
                <button id="tab-add"
                    class="flex-1 py-3 text-center font-medium text-sm border-b-2 text-gray-500">
                    Add Member
                </button>
                <button id="tab-kick"
                    class="flex-1 py-3 text-center font-medium text-sm border-b-2 text-gray-500">
                    Kick Member
                </button>
            </div>

            <!-- CONTENT -->
            <div class="p-4 flex-1 overflow-y-auto">

                <!-- ADD MEMBER -->
                <div id="page-add">
                    <input type="text" id="search-user" placeholder="Type name or username..."
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-600 outline-none">

                    <div id="search-results"
                        class="mt-3 flex flex-col gap-2 max-h-[300px] overflow-y-auto border border-gray-200 rounded-lg p-3 bg-white text-sm text-gray-600">
                        <div class="text-center text-gray-400">Start typing to search...</div>
                    </div>
                </div>

                <!-- KICK MEMBER -->
                <div id="page-kick">
                    <div class="flex flex-col gap-2 max-h-[350px] overflow-y-auto rounded-lg">

                        <?php foreach ($membersForumFiltered as $member): ?>
                            <div class="flex items-center justify-between p-3 bg-white shadow-sm border border-gray-200 hover:border-blue-500 rounded-xl hover:shadow-md transition duration-300">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="<?= !empty($member['PATH_PHOTO'])
                                                    ? BASEURL . '/storage/users/photos/' . $member['PATH_PHOTO']
                                                    : BASEURL . '/src/asset/image/default.png' ?>"
                                        class="w-10 h-10 rounded-full object-cover">

                                    <div class="min-w-0">
                                        <p class="font-medium truncate"><?= $member["NAME"] ?></p>
                                        <p class="text-sm truncate">@<?= $member["USERNAME"] ?></p>
                                    </div>
                                </div>

                                <button
                                    onclick="kickMember('<?= $forumByid['ID'] ?>','<?= $member['USER_ID'] ?>')"
                                    class="px-4 py-2 text-sm rounded-lg border bg-red-600 text-white hover:bg-red-800">
                                    Kick
                                </button>
                            </div>
                        <?php endforeach; ?>

                        <?php if (count($membersForumFiltered) < 1): ?>
                            <p class="text-gray-500 text-center">No members found.</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="globalSuccessDiv"
    class="hidden fixed top-10 right-4 md:right-1/2 md:translate-x-1/2 sm:translate-x-0 sm:right-10 z-[100000] bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 transition-all duration-300 transform scale-100">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
    </svg>
    <span id="globalSuccessText" class="font-medium">Success!</span>
</div>

<div id="globalErrorDiv"
    class="hidden fixed top-10 right-4 md:right-1/2 md:translate-x-1/2 sm:translate-x-0 sm:right-10 z-[100000] bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 transition-all duration-300 transform scale-100">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span id="globalErrorText" class="font-medium">Error!</span>
</div>

<!-- CONFIRM ADD MEMBER -->
<div id="modal-confirm-add"
    class="hidden fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-100 transition-all">

        <div class="p-6 text-center">
            <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-blue-100">
                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-2">Tambahkan Anggota?</h3>
            <p class="text-slate-500 text-sm leading-relaxed">
                Apakah Anda yakin ingin menambahkan <span id="confirm-user-name" class="font-semibold text-slate-700">User Name</span> ke dalam tim? Tindakan ini akan memberikan akses segera.
            </p>
        </div>

        <div class="flex justify-center mb-5 gap-4">
            <button id="confirm-add"
                class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 text-white font-medium rounded-lg transition-colors duration-200">
                Ya, Tambahkan
            </button>
            <button id="cancel-add"
                class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                Batal
            </button>
        </div>
    </div>
</div>

<div id="modal-confirm-kick"
    class="hidden fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 transition-all duration-300">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform scale-100 transition-all">
        <div class="p-6 text-center">
            <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-red-100">
                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-2">Keluarkan Anggota?</h3>
            <p class="text-slate-500 text-sm leading-relaxed">
                Apakah Anda yakin ingin mengeluarkan <span class="font-semibold text-slate-700">anggota ini</span>? <br>
            </p>
        </div>

        <div class="flex justify-center mb-5 gap-4">
            <button id="confirm-kick"
                class="w-auto inline-flex justify-center items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-200 text-white font-medium rounded-lg transition-colors duration-200">
                Ya, Keluarkan
            </button>

            <button id="cancel-kick"
                class="w-auto inline-flex justify-center items-center px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium rounded-lg transition-colors duration-200">
                Batal
            </button>
        </div>
    </div>
</div>

<script>
    const FORUM_ID = "<?= $forumByid['ID'] ?>";

    // --- DEFINISI VARIABEL BARU (GLOBAL NOTIF) ---
    const globalSuccessDiv = document.getElementById("globalSuccessDiv");
    const globalSuccessText = document.getElementById("globalSuccessText");
    const globalErrorDiv = document.getElementById("globalErrorDiv");
    const globalErrorText = document.getElementById("globalErrorText");

    // Helper Function untuk menampilkan notif
    function showToast(type, message) {
        if (type === 'success') {
            globalSuccessText.innerText = message;
            globalSuccessDiv.classList.remove("hidden");
            // Auto hide setelah 3 detik
            setTimeout(() => {
                globalSuccessDiv.classList.add("hidden");
            }, 3000);
        } else {
            globalErrorText.innerText = message;
            globalErrorDiv.classList.remove("hidden");
            setTimeout(() => {
                globalErrorDiv.classList.add("hidden");
            }, 3000);
        }
    }

    // ... (Kode Tab Add/Kick Anda tetap sama) ...
    const tabAdd = document.getElementById("tab-add");
    const tabKick = document.getElementById("tab-kick");
    const pageAdd = document.getElementById("page-add");
    const pageKick = document.getElementById("page-kick");

    function resetTabs() {
        tabAdd.classList.remove("text-blue-600", "border-blue-600");
        tabAdd.classList.add("text-gray-500");
        tabKick.classList.remove("text-blue-600", "border-blue-600");
        tabKick.classList.add("text-gray-500");
    }

    function showAdd() {
        resetTabs();
        tabAdd.classList.add("text-blue-600", "border-blue-600");
        tabAdd.classList.remove("text-gray-500");
        pageAdd.classList.remove("hidden");
        pageKick.classList.add("hidden");
    }

    function showKick() {
        resetTabs();
        tabKick.classList.add("text-blue-600", "border-blue-600");
        tabKick.classList.remove("text-gray-500");
        pageKick.classList.remove("hidden");
        pageAdd.classList.add("hidden");
    }

    tabAdd.onclick = showAdd;
    tabKick.onclick = showKick;
    showAdd();

    // ... (Kode Modal Manage Members Anda tetap sama) ...
    const modalManageMembers = document.getElementById("modal-manage-members");
    const btnOpenManageMembers = document.getElementById("btn-open-manage-members");
    const btnCloseManageMembers = document.getElementById("btn-close-manage-members");

    btnOpenManageMembers?.addEventListener("click", () => {
        modalManageMembers.classList.remove("hidden");
        modalManageMembers.classList.add("flex");
    });

    const closeManageModal = () => {
        modalManageMembers.classList.add("hidden");
        modalManageMembers.classList.remove("flex");
    };

    btnCloseManageMembers.addEventListener("click", closeManageModal);
    modalManageMembers.addEventListener("click", e => {
        if (e.target === modalManageMembers) closeManageModal();
    });

    // ... (Search Logic tetap sama) ...
    const searchInput = document.getElementById("search-user");
    const searchResults = document.getElementById("search-results");
    let searchTimeout = null;

    searchInput.addEventListener("input", () => {
        const keyword = searchInput.value.trim();
        if (keyword.length < 2) {
            searchResults.innerHTML = `<p class="text-center text-gray-400 text-sm">Type at least 2 characters...</p>`;
            return;
        }
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => searchUser(keyword), 300);
    });

    async function searchUser(keyword) {
        searchResults.innerHTML = `<p class="text-center text-gray-400 text-sm">Searching...</p>`;
        const res = await fetch(`${BASEURL}/forums/searchUser`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                keyword
            })
        });
        const users = await res.json();

        if (users.length < 1) {
            searchResults.innerHTML = `<p class="text-center text-gray-400 text-sm">No users found.</p>`;
            return;
        }

        searchResults.innerHTML = users.map(u => `
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-gray-100 transition">
                <div class="flex items-center gap-3">
                    <img src="${u.PATH_PHOTO ? BASEURL + '/storage/users/photos/' + u.PATH_PHOTO : BASEURL + '/src/asset/image/default.png'}" class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <p class="font-semibold text-gray-800">${u.FULL_NAME}</p>
                        <p class="text-xs text-gray-500">@${u.USERNAME}</p>
                    </div>
                </div>
                <button onclick="openAddConfirm('${u.ID}', '${u.FULL_NAME}')" class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Add</button>
            </div>
        `).join("");
    }

    // --- LOGIKA ADD MEMBER (UPDATED) ---
    let selectedUserId = null;
    const modalConfirmAdd = document.getElementById("modal-confirm-add");
    const confirmUserName = document.getElementById("confirm-user-name");
    const cancelAdd = document.getElementById("cancel-add");
    const confirmAdd = document.getElementById("confirm-add");

    function openAddConfirm(userId, name) {
        selectedUserId = userId;
        confirmUserName.textContent = name;
        modalConfirmAdd.classList.remove("hidden");
    }

    cancelAdd.onclick = () => {
        selectedUserId = null;
        modalConfirmAdd.classList.add("hidden");
    };

    confirmAdd.onclick = async () => {
        if (!selectedUserId) return;

        // Button Loading
        const originalText = confirmAdd.innerText;
        confirmAdd.innerText = "Adding...";
        confirmAdd.disabled = true;

        try {
            const res = await fetch(`${BASEURL}/forums/addMember`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    id: FORUM_ID,
                    user_id: selectedUserId
                })
            });
            const result = await res.json();

            if (result.success) {
                // GANTI DISINI: Pakai Helper Function
                showToast('success', 'Member successfully added!');

                modalConfirmAdd.classList.add("hidden"); // Tutup modal confirm

                // Bersihkan search
                searchResults.innerHTML = "";
                searchInput.value = "";
            } else {
                showToast('error', result.message || "Failed to add.");
            }
        } catch (err) {
            showToast('error', "Connection Error");
        } finally {
            confirmAdd.innerText = originalText;
            confirmAdd.disabled = false;
        }
    };

    // --- LOGIKA KICK MEMBER (UPDATED) ---
    const kickModal = document.getElementById("modal-confirm-kick");
    const cancelKickBtn = document.getElementById("cancel-kick");
    const confirmKickBtn = document.getElementById("confirm-kick");
    let targetForumId = null;
    let targetUserId = null;

    function kickMember(forumId, userId) {
        targetForumId = forumId;
        targetUserId = userId;
        kickModal.classList.remove("hidden");
    }

    function closeKickModal() {
        kickModal.classList.add("hidden");
        targetForumId = null;
        targetUserId = null;
    }

    cancelKickBtn.addEventListener("click", closeKickModal);

    confirmKickBtn.addEventListener("click", async function() {
        if (!targetForumId || !targetUserId) return;

        const originalText = confirmKickBtn.innerText;
        confirmKickBtn.innerText = "Processing...";
        confirmKickBtn.disabled = true;

        try {
            const res = await fetch(`${BASEURL}/forums/kickMember`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    forum_id: targetForumId,
                    user_id: targetUserId
                })
            });
            const result = await res.json();

            if (result.success) {
                closeKickModal();

                showToast('success', 'Member kicked successfully!');

                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast('error', result.message || "Cannot kick member.");
            }
        } catch (error) {
            console.error("Error:", error);
            showToast('error', "Network Error");
        } finally {
            confirmKickBtn.innerText = originalText;
            confirmKickBtn.disabled = false;
            closeKickModal()
        }
    });
</script>