<div id="create-forum-modal" class="hidden fixed inset-0 z-[9999]  justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Crate New Group
                </h3>
                <button type="button" id="close-modal-forum" class="text-gray-400 rounded-lg text-sm w-8 h-8 flex justify-center items-center cursor-pointer" data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4">
                <p id="modal-error-message" class="bg-red-600 p-2 text-white text-center rounded-lg hidden mb-2"></p>
                <form id="create-forum-form" action="<?php echo BASEURL; ?>/forums/create" method="post" class="my-5" enctype="multipart/form-data">
                    <section class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <div class="flex-shrink-0 size-[100px] rounded-full overflow-hidden border-2 border-gray-200">
                            <img id="photo-container" src="<?php echo BASEURL; ?>/src/asset/image/default.png" alt="User avatar" class="object-cover w-full h-full" />
                        </div>
                        <input type="file" id="file-input" name="forumPhoto" class="hidden" accept="image/*" />
                        <button type="button" id="add-photo" class="flex items-center gap-2 px-6 py-3.5 rounded-full bg-gray-900 text-white font-bold hover:bg-gray-700 transition-colors">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/edit-2-white-fill.svg" alt="Edit icon" class="size-6" />
                            <span>Change Photo</span>
                        </button>
                    </section>
                    <div class="my-6 max-w-md mx-auto">
                        <div class="relative">
                            <input type="text" id="forumName" name="forumName" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                            <label for="forumName" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4">Group Name</label>
                        </div>
                        <div class="relative my-5">
                            <input type="text" id="bio" name="bio" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                            <label for="bio" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4">About This Group</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="isPrivate" id="isPrivate" />
                            <label for="isPrivate" class="ml-2 text-sm text-gray-600">Make this group private</label>
                        </div>
                        <div class="relative my-3 hidden" id="keyForumContainer">
                            <input type="text" id="keyForums" name="keyForum" class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                            <label for="keyForums" class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4">Key Group</label>
                        </div>
                        <button type="submit" name="create" id="createForm" class="mt-6 w-full px-6 py-3.5 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors">Create Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        createForum();
    });

    function createForum() {
        const form = document.getElementById("create-forum-form");
        if (!form) return;

        const submitButton = document.getElementById("createForm");
        const errorMessageDiv = document.getElementById("modal-error-message");

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const originalButtonText = submitButton.innerHTML;
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
                    window.location.href = result.redirectUrl;
                } else {
                    errorMessageDiv.textContent = result.message;
                    errorMessageDiv.classList.remove("hidden");
                }
            } catch (error) {
                console.error("Fetch Error:", error);
                errorMessageDiv.textContent = "Terjadi kesalahan jaringan. Silakan coba lagi.";
                errorMessageDiv.classList.remove("hidden");
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }
</script>