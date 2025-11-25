<?php
$initial = strtoupper(substr($groupChatId['NAME'], 0, 2));
$placeholder = "data:image/svg+xml;utf8,"
    . rawurlencode("
        <svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'>
            <rect width='100' height='100' fill='#ec4899'/>
            <text x='50%' y='50%' font-size='36' font-family='Arial' font-weight='bold' dy='.3em' 
                  text-anchor='middle' fill='white'>$initial</text>
        </svg>
    ");
?>

<div id="modal-edit-forum" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Edit Group
                </h3>
                <button
                    type="button"
                    id="btn-close-edit-forum"
                    class="text-gray-400 rounded-lg text-sm w-8 h-8 flex justify-center items-center cursor-pointer">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4">
                <p id="edit-forum-error" class="bg-red-600 p-2 text-white text-center rounded-lg hidden mb-2"></p>

                <form id="form-edit-forum" action="<?php echo BASEURL; ?>/groups/edit" method="post" class="my-5" enctype="multipart/form-data">
                    <section class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <div class="flex-shrink-0 size-[100px] rounded-full overflow-hidden border-2 border-gray-200">
                            <img
                                id="edit-forum-photo-preview"
                                src="<?=
                                        !empty($groupChatId['PATH_PHOTO'])
                                            ? BASEURL . '/storage/groups/photos/' . $groupChatId['PATH_PHOTO']
                                            : $placeholder
                                        ?>"
                                alt="Forum photo"
                                class="object-cover w-full h-full" />
                        </div>

                        <input type="file" id="edit-forum-file-input" name="groupChatPhoto" class="hidden" accept="image/*" />
                        <button
                            type="button"
                            id="btn-edit-forum-change-photo"
                            class="flex items-center gap-2 px-6 py-3.5 rounded-full bg-gray-900 text-white font-bold hover:bg-gray-700 transition-colors">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/edit-2-white-fill.svg" alt="Edit icon" class="size-6" />
                            <span>Change Photo</span>
                        </button>
                    </section>

                    <div class="my-6 max-w-md mx-auto">
                        <input type="hidden" name="group_chat_id" value="<?php echo $groupChatId['ID'] ?>">
                        <div class="relative">
                            <input
                                type="text"
                                id="edit-forum-name"
                                name="groupChatName"
                                value="<?php echo $groupChatId['NAME'] ?>"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " required />
                            <label for="edit-forum-name"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600 peer-placeholder-shown:top-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4">
                                Group Name
                            </label>
                        </div>

                        <div class="relative my-5">
                            <input
                                type="text"
                                id="edit-forum-bio"
                                name="bio"
                                value="<?php echo $groupChatId['ABOUT'] ?>"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " required />
                            <label for="edit-forum-bio"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600 peer-placeholder-shown:top-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4">
                                About This Group
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input
                                type="checkbox"
                                name="isPrivate"
                                id="edit-forum-isPrivate"
                                <?php if (!empty($groupChatId['IS_PRIVATE']) && $groupChatId['IS_PRIVATE']) echo 'checked'; ?> />
                            <label for="edit-forum-isPrivate" class="ml-2 text-sm text-gray-600">Make this group private</label>
                        </div>

                        <div class="relative my-3 <?php echo (!empty($groupChatId['IS_PRIVATE']) && $groupChatId['IS_PRIVATE']) ? '' : 'hidden'; ?>" id="edit-forum-key-container">
                            <input
                                type="text"
                                id="edit-forum-key"
                                value="<?php echo $groupChatId['ACCESS_KEY'] ?>"
                                name="keyGroupChat"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="edit-forum-key"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600 peer-placeholder-shown:top-1/2 peer-placeholder-shown:scale-100 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4">
                                Key Group Chat
                            </label>
                        </div>

                        <button
                            type="submit"
                            name="edit"
                            id="btn-submit-edit-forum"
                            class="mt-6 w-full px-6 py-3.5 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors">
                            Edit Group
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const btnOpenEditForum = document.getElementById("btn-open-edit-forum");
    const modalEditForum = document.getElementById("modal-edit-forum");
    const btnCloseEditForum = document.getElementById("btn-close-edit-forum");

    const checkboxPrivate = document.getElementById("edit-forum-isPrivate");
    const keyForumContainer = document.getElementById("edit-forum-key-container");

    const btnChangePhoto = document.getElementById("btn-edit-forum-change-photo");
    const fileInput = document.getElementById("edit-forum-file-input");
    const photoPreview = document.getElementById("edit-forum-photo-preview");

    const originalPhotoSrc = photoPreview.src;

    btnOpenEditForum?.addEventListener('click', () => {
        modalEditForum.classList.remove("hidden");
        modalEditForum.classList.add("flex");
    });

    const closeModalEditForum = () => {
        modalEditForum.classList.add("hidden");
        modalEditForum.classList.remove("flex");
        photoPreview.src = originalPhotoSrc;
        fileInput.value = "";
    };

    btnCloseEditForum.addEventListener('click', closeModalEditForum);

    modalEditForum.addEventListener('click', (e) => {
        if (e.target === modalEditForum) {
            closeModalEditForum();
        }
    });

    checkboxPrivate.addEventListener('change', () => {
        keyForumContainer.classList.toggle("hidden", !checkboxPrivate.checked);
    });

    btnChangePhoto.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            photoPreview.src = URL.createObjectURL(file);
        }
    });

    function editForum() {
        const form = document.getElementById("form-edit-forum");
        const submitButton = document.getElementById("btn-submit-edit-forum");
        const errorMessageDiv = document.getElementById("edit-forum-error");

        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = "Editing...";
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
                    errorMessageDiv.textContent = result.message || "Something went wrong!";
                    errorMessageDiv.classList.remove("hidden");
                }
            } catch (error) {
                errorMessageDiv.textContent = "Failed to update forum.";
                errorMessageDiv.classList.remove("hidden");
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        });
    }

    editForum();
</script>