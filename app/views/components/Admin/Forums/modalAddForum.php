<div id="createForumModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div id="modal-error-message" class="hidden my-3 text-red-600 text-sm"></div>

        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Create New Forum</h2>
                <button onclick="closeCreateForumModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="create-forum-form-admin" action="<?php echo BASEURL; ?>/forums/create" method="POST">
                <div class="flex flex-col items-center mb-4">
                    <div id="photoPreview" class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden mb-3">
                        <svg class="w-12 h-12 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                        </svg>
                    </div>
                    <input type="file" id="forumPhoto" accept="image/*" name="forumPhoto" class="hidden" onchange="previewPhoto(event)">
                    <button type="button" onclick="document.getElementById('forumPhoto').click()" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-gray-800 flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Change Photo
                    </button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Forum Name</label>
                    <input type="text" id="forumName" name="forumName" placeholder="Enter forum name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">About Forum</label>
                    <textarea id="forumAbout" name="bio" placeholder="Forum description..." rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                </div>

                <div class="mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="isPrivate" name="isPrivate" onchange="togglePrivateKey()" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer" />
                        <span class="text-sm text-gray-700">Make this forum private</span>
                    </label>
                </div>

                <div id="privateKeySection" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Private Forum Key</label>
                    <div class="flex gap-2">
                        <input type="text" name="keyForum" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-700 font-mono text-sm" />
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Save this key to join the private forum</p>
                </div>

                <button id="createForumSubmit" class="w-full py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200 cursor-pointer">
                    Create Forum
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        createForum();
    });

    function createForum() {
        const form = document.getElementById("create-forum-form-admin");
        if (!form) return;

        const submitButton = document.getElementById("createForumSubmit");
        const errorMessageDiv = document.getElementById("modal-error-message");

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            // Save button HTML before it changes
            const originalButtonText = submitButton.innerHTML;

            // Change button to loading
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="inline w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;

            errorMessageDiv.classList.add("hidden");

            const formData = new FormData(form);
            const actionUrl = form.getAttribute("action");

            try {
                const response = await fetch(actionUrl, {
                    method: "POST",
                    body: formData,
                });

                const result = await response.json();

                if (result.success) {
                    window.location.reload();
                } else {
                    errorMessageDiv.textContent = result.message;
                    errorMessageDiv.classList.remove("hidden");
                }
            } catch (error) {
                console.error("Fetch Error:", error);
                errorMessageDiv.textContent = "A network error occurred. Please try again.";
                errorMessageDiv.classList.remove("hidden");
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }
</script>