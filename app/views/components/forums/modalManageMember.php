<div id="modal-manage-members"
    class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">

    <div class="relative p-4 w-full max-w-3xl max-h-[120vh]">
        <div class="relative bg-white rounded-xl shadow-lg flex flex-col h-full border border-gray-200">

            <!-- HEADER -->
            <div class="flex items-center justify-between px-4 py-3 border-b bg-gray-50 rounded-t">
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
                        class="w-full px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-600 outline-none">

                    <div id="search-results"
                        class="mt-3 flex flex-col gap-2 max-h-[300px] overflow-y-auto border border-gray-200 rounded-lg p-3 bg-white text-sm text-gray-600">
                        <div class="text-center text-gray-400">Start typing to search...</div>
                    </div>
                </div>

                <!-- KICK MEMBER -->
                <div id="page-kick">
                    <div class="flex flex-col gap-2 max-h-[350px] overflow-y-auto border p-3 rounded-lg">

                        <?php foreach ($membersForumFiltered as $member): ?>
                            <div class="flex items-center justify-between p-3 bg-white shadow-sm border rounded-xl hover:shadow-md transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="<?= !empty($member['PATH_PHOTO'])
                                        ? BASEURL . '/storage/users/photos/' . $member['PATH_PHOTO']
                                        : BASEURL . '/src/asset/image/default.png' ?>"
                                        class="w-10 h-10 rounded-full object-cover">

                                    <p class="font-medium truncate"><?= $member["NAME"] ?></p>
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

<!-- CONFIRM ADD MEMBER -->
<div id="modal-confirm-add"
    class="hidden fixed inset-0 bg-black/40 z-[99999] flex justify-center items-center">
    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-sm text-center">
        <h3 class="font-semibold text-gray-800 text-lg mb-3">Add this member?</h3>
        <p id="confirm-user-name" class="text-gray-600 mb-5">User name here...</p>

        <div class="flex justify-end gap-3">
            <button id="cancel-add" 
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">
                Cancel
            </button>
            <button id="confirm-add"
                class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">
                Add
            </button>
        </div>
    </div>
</div>

<script>
const FORUM_ID = "<?= $forumByid['ID'] ?>";

// TAB LOGIC
const tabAdd = document.getElementById("tab-add");
const tabKick = document.getElementById("tab-kick");
const pageAdd = document.getElementById("page-add");
const pageKick = document.getElementById("page-kick");

// RESET TAB UI
function resetTabs() {
    tabAdd.classList.remove("text-blue-600", "border-blue-600");
    tabAdd.classList.add("text-gray-500");

    tabKick.classList.remove("text-blue-600", "border-blue-600");
    tabKick.classList.add("text-gray-500");
}

// SHOW TAB ADD
function showAdd() {
    resetTabs();

    tabAdd.classList.add("text-blue-600", "border-blue-600");
    tabAdd.classList.remove("text-gray-500");

    pageAdd.classList.remove("hidden");
    pageKick.classList.add("hidden");
}

// SHOW TAB KICK
function showKick() {
    resetTabs();

    tabKick.classList.add("text-blue-600", "border-blue-600");
    tabKick.classList.remove("text-gray-500");

    pageKick.classList.remove("hidden");
    pageAdd.classList.add("hidden");
}

tabAdd.onclick = showAdd;
tabKick.onclick = showKick;

showAdd(); // default

// MODAL LOGIC
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

// AJAX SEARCH USER
const searchInput = document.getElementById("search-user");
const searchResults = document.getElementById("search-results");

let selectedUserId = null; // untuk confirm modal
let searchTimeout = null;

// EVENT LISTENER — INI WAJIB ADA
searchInput.addEventListener("input", () => {
    const keyword = searchInput.value.trim();

    if (keyword.length < 2) {
        searchResults.innerHTML = `
            <p class="text-center text-gray-400 text-sm">
                Type at least 2 characters...
            </p>`;
        return;
    }

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => searchUser(keyword), 300);
});

async function searchUser(keyword) {
    searchResults.innerHTML = `
        <p class="text-center text-gray-400 text-sm">Searching...</p>
    `;

    const res = await fetch(`${BASEURL}/forums/searchUser`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ keyword })
    });

    const users = await res.json();

    if (users.length < 1) {
        searchResults.innerHTML = `
            <p class="text-center text-gray-400 text-sm">No users found.</p>
        `;
        return;
    }

    searchResults.innerHTML = users.map(u => `
        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border hover:bg-gray-100 transition">

            <div class="flex items-center gap-3">
                <img src="${u.PATH_PHOTO 
                    ? BASEURL + '/storage/users/photos/' + u.PATH_PHOTO 
                    : BASEURL + '/src/asset/image/default.png'}"
                    class="w-10 h-10 rounded-full object-cover">

                <div>
                    <p class="font-semibold text-gray-800">${u.FULL_NAME}</p>
                    <p class="text-xs text-gray-500">@${u.USERNAME}</p>
                </div>
            </div>

            <button onclick="openAddConfirm('${u.ID}', '${u.FULL_NAME}')"
                class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Add
            </button>
        </div>
    `).join("");
}

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

    const res = await fetch(`${BASEURL}/forums/addMember`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({
            id: FORUM_ID,
            user_id: selectedUserId
        })
    });

    const result = await res.json();
    modalConfirmAdd.classList.add("hidden");

    if (result.success) {
        alert("Member added!");
        searchResults.innerHTML = "";
        searchInput.value = "";
    } else {
        alert(result.message || "Failed to add member.");
    }
};

// ADD MEMBER AJAX
async function addMember(userId) {
    const res = await fetch(`${BASEURL}/forums/addMember`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            id: FORUM_ID,
            user_id: userId
        })
    });

    const result = await res.json();

    if (result.success) {
        alert("Member added!");
        searchResults.innerHTML = "";
        searchInput.value = "";
    } else {
        alert(result.message || "Failed to add member.");
    }
}

// KICK MEMBER AJAX
async function kickMember(forumId, userId) {
    if (!confirm("Kick this member?")) return;

    const res = await fetch(`${BASEURL}/forums/kickMember`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({ forum_id: forumId, user_id: userId })
    });

    const result = await res.json();

    if (result.success) {
        alert("Member kicked.");
        location.reload();
    } else {
        alert(result.message || "Cannot kick this member.");
    }
}

</script>