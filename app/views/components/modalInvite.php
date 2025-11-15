<div id="modal-preview-forum"
    class="hidden fixed inset-0 z-[9999] bg-black/40 backdrop-blur-sm flex items-center justify-center">

    <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md animate-fadeIn scale-95">
        
        <!-- HEADER -->
        <div class="mb-4">
            <div class="w-full flex justify-center">
                <img id="invite-forum-photo" class="w-17 h-17 rounded-full object-cover">
            </div>

            <h2 id="invite-forum-name" class="text-xl font-bold text-gray-900"></h2>
            <p id="invite-forum-desc" class="text-gray-600 mt-1"></p>
        </div>

        <!-- OWNER -->
        <div id="invite-owner" class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border mb-4">
            <img id="invite-owner-photo" class="w-10 h-10 rounded-full object-cover">
            <div>
                <p class="text-sm text-gray-500">Owner</p>
                <p id="invite-owner-name" class="font-medium text-gray-900"></p>
            </div>
        </div>

        <!-- MEMBER LIST -->
        <div class="mb-5">
            <p class="text-gray-700 font-medium mb-2">Members</p>
            <div id="invite-members" class="flex -space-x-3"></div>
        </div>

        <!-- BUTTONS -->
        <div class="flex gap-3 justify-end">
            <button id="invite-forum-decline"
                class="px-4 py-2 text-sm rounded-md bg-gray-200 hover:bg-gray-300 transition">
                Nanti Saja
            </button>

            <button id="invite-forum-join"
                class="px-4 py-2 text-sm rounded-md bg-blue-600 text-white hover:bg-blue-700 transition">
                Gabung Forum
            </button>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fadeIn { animation: fadeIn .2s ease-out; }
</style>

<script>
const modalInvite = document.getElementById('modal-preview-forum');
const inviteForumPhoto = document.getElementById('invite-forum-photo');
const inviteForumName = document.getElementById('invite-forum-name');
const inviteForumDesc = document.getElementById('invite-forum-desc');
const inviteForumJoin = document.getElementById('invite-forum-join');
const inviteForumDecline = document.getElementById('invite-forum-decline');

const inviteOwner = document.getElementById('invite-owner');
const inviteOwnerName = document.getElementById('invite-owner-name');
const inviteOwnerPhoto = document.getElementById('invite-owner-photo');
const inviteMembers = document.getElementById('invite-members');

function openPreviewForum(forumId) {
    fetch(`${BASEURL}/forums/getForumInfo?id=${forumId}`)
    .then(r => r.json())
    .then(data => {

        // Set forum details
        inviteForumPhoto.src = data.PHOTO 
            ? `${BASEURL}/storage/forums/photos/${data.PHOTO}`
            : `${BASEURL}/src/asset/image/default.png`;
        inviteForumName.textContent = data.NAME;
        inviteForumDesc.textContent = data.ABOUT || "Tanpa deskripsi";

        // Set owner
        inviteOwnerName.textContent = data.OWNER.NAME;
        inviteOwnerPhoto.src = data.OWNER.PHOTO 
            ? `${BASEURL}/storage/users/photos/${data.OWNER.PHOTO}`
            : `${BASEURL}/src/asset/image/default.png`;

        // Members list thumbnails
        inviteMembers.innerHTML = data.MEMBERS.map(m => `
            <img src="${
                m.PHOTO ? BASEURL + '/storage/users/photos/' + m.PHOTO
                : BASEURL + '/src/asset/image/default.png'
            }"
            class="w-10 h-10 rounded-full border-2 border-white object-cover">
        `).join('');

        // For join action later
        inviteForumJoin.dataset.forumId = forumId;

        modalInvite.classList.remove("hidden");
    })
    .catch(err => {
        console.error(err);
        alert("Gagal memuat info forum.");
    });
}

// JOIN BUTTON
inviteForumJoin.addEventListener('click', async () => {
    const id = inviteForumJoin.dataset.forumId;

    const res = await fetch(`${BASEURL}/forums/joinViaInvite`, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ forum_id: id })
    });

    const json = await res.json();

    if (json.success) {
        window.location.href = json.redirect;
    } else {
        alert(json.message || "Gagal join forum.");
    }
});

// CLOSE BUTTON
inviteForumDecline.addEventListener("click", () => {
    modalInvite.classList.add("hidden");
});

</script>