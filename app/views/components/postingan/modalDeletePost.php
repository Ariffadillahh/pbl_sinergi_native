<div id="modal-delete-post" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="p-4 md:p-5 text-center">
                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <h3 class="mb-5 text-lg font-normal text-gray-600">Are you sure you want to delete this post?</h3>
                <form id="form-delete-post" action="<?php echo BASEURL ?>/post/delete" method="post">
                    <input type="hidden" name="post_id" id="delete-post-id">
                </form>
                <div class="flex justify-center gap-3 mt-4">
                    <button id="btn-confirm-delete-post" type="button"
                        class="text-white bg-red-600 hover:bg-red-800 font-medium rounded-lg text-sm px-5 py-2.5">
                        Yes, I'm sure
                    </button>
                    <button id="btn-cancel-delete-post" type="button"
                        class="py-2.5 px-5 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100">
                        No, cancel
                    </button>
                </div>
                <p id="delete-post-error" class="bg-red-600 text-white text-sm mt-4 p-2 rounded-lg hidden text-center"></p>
            </div>
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
        const response = await fetch(formDeletePost.action, { method: "POST", body: formData });
        const result = await response.json();
        if (result.success) window.location.reload();
        else { errorDeletePost.textContent = result.message || "Failed to delete post."; errorDeletePost.classList.remove("hidden"); }
    } catch(err) {
        console.error(err);
        errorDeletePost.textContent = "Network error while deleting post."; errorDeletePost.classList.remove("hidden");
    } finally {
        btnConfirmDeletePost.disabled = false;
        btnConfirmDeletePost.textContent = "Yes, I'm sure";
    }
});
</script>
