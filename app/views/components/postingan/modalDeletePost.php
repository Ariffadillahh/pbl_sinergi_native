<div id="modal-delete-post" class="hidden fixed inset-0 z-[99999] overflow-y-auto justify-center items-center backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity"></div>

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
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Delete Post?</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Are you sure you want to delete this post?</p>
                        </div>
                    </div>
                </div>
            </div>

            <form id="form-delete-post" action="<?php echo BASEURL ?>/post/delete" method="post" class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <input type="hidden" name="post_id" id="delete-post-id">

                <button id="btn-confirm-delete-post" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition cursor-pointer">
                    Yes, I'm sure
                </button>
                <button id="btn-cancel-delete-post" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition cursor-pointer">
                    Cancel
                </button>
            </form>

            <p id="delete-post-error" class="bg-red-100 text-red-600 text-sm mx-4 mb-4 p-3 rounded-lg hidden text-center border border-red-200"></p>
            <p id="delete-post-succsess" class="bg-green-100 text-green-600 text-sm mx-4 mb-4 p-3 rounded-lg hidden text-center border border-green-200"></p>
        </div>
    </div>
</div>

<script>
    const modalDeletePost = document.getElementById("modal-delete-post");
    const btnCancelDeletePost = document.getElementById("btn-cancel-delete-post");
    const btnConfirmDeletePost = document.getElementById("btn-confirm-delete-post");
    const formDeletePost = document.getElementById("form-delete-post");
    const inputDeletePostId = document.getElementById("delete-post-id");
    const errorDeletePost = document.getElementById("delete-post-error");
    const succsessDeletePost = document.getElementById("delete-post-succsess");

    btnCancelDeletePost.addEventListener("click", () => {
        modalDeletePost.classList.add("hidden");
        modalDeletePost.classList.remove("flex");
        errorDeletePost.classList.add("hidden");
    });

    modalDeletePost.addEventListener("click", e => {
        if (e.target === modalDeletePost) {
            modalDeletePost.classList.add("hidden");
            modalDeletePost.classList.remove("flex");
            errorDeletePost.classList.add("hidden");
        }
    });

    btnConfirmDeletePost.addEventListener("click", async () => {
        const formData = new FormData(formDeletePost);
        btnConfirmDeletePost.disabled = true;
        btnConfirmDeletePost.textContent = "Deleting...";
        try {
            const response = await fetch(formDeletePost.action, {
                method: "POST",
                body: formData
            });
            const result = await response.json();
            if (result.success) {
                succsessDeletePost.classList.remove("hidden")
                succsessDeletePost.textContent = "Successfully Deleted";
                setTimeout(() => {
                    window.location.reload();
                }, 1500)
            } else {
                errorDeletePost.textContent = result.message || "Failed to delete post.";
                errorDeletePost.classList.remove("hidden");
            }
        } catch (err) {
            console.error(err);
            errorDeletePost.textContent = "Network error while deleting post.";
            errorDeletePost.classList.remove("hidden");
        } finally {
            btnConfirmDeletePost.disabled = false;
            btnConfirmDeletePost.textContent = "Yes, I'm sure";
        }
    });
</script>