<!-- MODAL DELETE FORUM -->
<div id="modal-delete-forum" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                <h3 class="mb-5 text-lg font-normal text-gray-600">
                    Are you sure you want to delete this forum?
                </h3>

                <form id="form-delete-forum" action="<?php echo BASEURL; ?>/forums/delete" method="post">
                    <input type="hidden" name="forum_id" value="<?php echo $forumByid['ID']; ?>">
                </form>

                <div class="flex justify-center gap-3 mt-4">
                    <button id="btn-confirm-delete-forum" type="button"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none 
                               focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center 
                               px-5 py-2.5 text-center">
                        Yes, I'm sure
                    </button>

                    <button id="btn-cancel-delete-forum" type="button"
                        class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white border border-gray-200 
                               rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 
                               focus:ring-gray-100">
                        No, cancel
                    </button>
                </div>

                <p id="delete-forum-error"
                    class="bg-red-600 text-white text-sm mt-4 p-2 rounded-lg hidden text-center"></p>
            </div>
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
                errorDeleteForum.textContent = result.message || "Failed to delete forum.";
                errorDeleteForum.classList.remove("hidden");
            }
        } catch (err) {
            errorDeleteForum.textContent = "Network error while deleting forum.";
            errorDeleteForum.classList.remove("hidden");
        } finally {
            btnConfirmDeleteForum.disabled = false;
            btnConfirmDeleteForum.textContent = "Yes, I'm sure";
        }
    });
</script>