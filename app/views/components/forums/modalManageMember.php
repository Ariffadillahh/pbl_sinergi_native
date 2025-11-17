<div id="modal-manage-members"
    class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">

    <div class="relative p-4 w-full max-w-lg max-h-[90vh]">
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
                    class="flex-1 py-3 text-center font-medium text-sm border-b-2 border-blue-600 text-blue-600">
                    Add Member
                </button>
                <button id="tab-kick"
                    class="flex-1 py-3 text-center font-medium text-sm border-b-2 border-transparent hover:border-gray-300 text-gray-500">
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
                <div id="page-kick" class="hidden text-sm">
                    <div class="flex flex-col gap-2 max-h-[350px] overflow-y-auto border p-3 rounded-lg">

                        <?php foreach ($membersForumFiltered as $member): ?>
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg border">

                                <div class="flex items-center gap-2 min-w-0">
                                    <img src="<?= !empty($member['PATH_PHOTO'])
                                        ? BASEURL . '/storage/users/photos/' . $member['PATH_PHOTO']
                                        : BASEURL . '/src/asset/image/default.png' ?>"
                                        class="w-9 h-9 rounded-full object-cover" alt="">
                                    <p class="font-medium truncate"><?= $member["NAME"] ?></p>
                                </div>

                                <button
                                    onclick="kickMember('<?= $forumByid['ID'] ?>','<?= $member['USER_ID'] ?>')"
                                    class="text-red-600 hover:text-white hover:bg-red-600 px-3 py-1 rounded-md text-xs font-medium border border-red-600 transition">
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

<script>
const FORUM_ID = "<?= $forumByid['ID'] ?>";

// TAB LOGIC
const tabAdd = document.getElementById("tab-add");
const tabKick = document.getElementById("tab-kick");
const pageAdd = document.getElementById("page-add");
const pageKick = document.getElementById("page-kick");

function showAdd() {
    tabAdd.classList.add("border-blue-600", "text-blue-600");
    tabKick.classList.remove("border-blue-600", "text-blue-600", "text-blue-600");
    tabKick.classList.add("text-gray-500");
    pageAdd.classList.remove("hidden");
    pageKick.classList.add("hidden");
}

function showKick() {
    tabKick.classList.add("border-blue-600", "text-blue-600");
    tabAdd.classList.remove("border-blue-600", "text-blue-600");
    tabAdd.classList.add("text-gray-500");
    pageKick.classList.remove("hidden");
    pageAdd.classList.add("hidden");
}

tabAdd.onclick = showAdd;
tabKick.onclick = showKick;

// DEFAULT TAB
showAdd();

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

let searchTimeout = null;

searchInput.addEventListener("input", () => {
    const keyword = searchInput.value.trim();

    if (keyword.length < 2) {
        searchResults.innerHTML = `<p class="text-gray-400 text-center">Type at least 2 characters...</p>`;
        return;
    }

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => searchUser(keyword), 300);
});

async function searchUser(keyword) {
    searchResults.innerHTML = `<p class="text-center text-sm text-gray-400">Searching...</p>`;

    const res = await fetch(`${BASEURL}/forums/searchUser`, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({ keyword })
    });

    const users = await res.json();

    if (users.length < 1) {
        searchResults.innerHTML = `<p class="text-center text-gray-400">No users found.</p>`;
        return;
    }

    searchResults.innerHTML = users.map(u => `
        <div onclick="addMember('${u.ID}')"
            class="flex gap-3 items-center p-2 rounded-lg hover:bg-gray-100 cursor-pointer transition">
            
            <img src="${u.PATH_PHOTO 
                ? BASEURL + '/storage/users/photos/' + u.PATH_PHOTO 
                : BASEURL + '/src/asset/image/default.png'}"
                class="w-9 h-9 rounded-full object-cover"
            >
            
            <div>
                <p class="font-medium text-gray-800 truncate">${u.FULL_NAME}</p>
                <p class="text-gray-500 text-xs">@${u.USERNAME}</p>
            </div>
        </div>
    `).join("");
}

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