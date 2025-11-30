<div id="modal-delete-forum" class="hidden fixed inset-0 z-[99999] overflow-y-auto justify-center items-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity backdrop-blur-sm"></div>

    <div class="flex min-h-full justify-center p-4 text-center sm:p-0 items-center">
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Delete Group?</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to delete this group?</p>
                        </div>
                    </div>
                </div>
            </div>

            <form id="form-delete-forum" action="<?php echo BASEURL; ?>/groups/delete" method="post" class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <input type="hidden" name="group_chat_id" value="<?php echo $groupChatId['ID']; ?>">

                <button id="btn-confirm-delete-forum" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition">
                    Yes, I'm sure
                </button>
                <button id="btn-cancel-delete-forum" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">
                    Cancel
                </button>
            </form>

            <p id="delete-forum-error" class="bg-red-100 text-red-600 text-sm mx-4 mb-4 p-3 rounded-lg hidden text-center border border-red-200"></p>
        </div>
    </div>
</div>

<script>
    const modalDeleteForum = document.getElementById("modal-delete-forum");
    const btnOpenDeleteForum = document.getElementById("btn-open-delete-forum"); 
    const btnCancelDeleteForum = document.getElementById("btn-cancel-delete-forum");
    const btnConfirmDeleteForum = document.getElementById("btn-confirm-delete-forum");
    const formDeleteForum = document.getElementById("form-delete-forum");
    const errorDeleteForum = document.getElementById("delete-forum-error");

    btnOpenDeleteForum?.addEventListener("click", () => {
        modalDeleteForum.classList.remove("hidden");
        modalDeleteForum.classList.add("flex");
    });

    const closeModalDeleteForum = () => {
        modalDeleteForum.classList.add("hidden");
        modalDeleteForum.classList.remove("flex");
        errorDeleteForum.classList.add("hidden");
    };

    btnCancelDeleteForum.addEventListener("click", closeModalDeleteForum);

    modalDeleteForum.addEventListener("click", (e) => {
        if (e.target === modalDeleteForum) closeModalDeleteForum();
    });

    btnConfirmDeleteForum.addEventListener("click", async () => {
        const formData = new FormData(formDeleteForum);
        const actionUrl = formDeleteForum.getAttribute("action");

        btnConfirmDeleteForum.disabled = true;
        btnConfirmDeleteForum.textContent = "Deleting...";

        try {
            const response = await fetch(actionUrl, {
                method: "POST",
                body: formData,
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = result.redirectUrl;
            } else {
                errorDeleteForum.textContent = result.message || "Failed to delete group.";
                errorDeleteForum.classList.remove("hidden");
            }
        } catch (err) {
            errorDeleteForum.textContent = "Network error while deleting group.";
            errorDeleteForum.classList.remove("hidden");
        } finally {
            btnConfirmDeleteForum.disabled = false;
            btnConfirmDeleteForum.textContent = "Yes, I'm sure";
        }
    });
</script>