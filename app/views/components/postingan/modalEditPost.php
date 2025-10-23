<div id="modal-edit-post" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">Edit Post</h3>
                <button type="button" id="btn-close-edit-post" class="text-gray-400 rounded-lg w-8 h-8 flex justify-center items-center">&times;</button>
            </div>
            <div class="p-4">
                <p id="edit-post-error" class="bg-red-600 p-2 text-white text-center rounded-lg hidden mb-2"></p>
                <form id="form-edit-post" action="<?= BASEURL ?>/post/update" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="post_id" id="edit-post-id">
                    <textarea name="content" id="edit-post-content" rows="4" class="w-full p-2 border rounded-lg mb-4"></textarea>
                    
                    <!-- Container preview gambar -->
                    <div id="media-preview-container" class="flex gap-2 flex-wrap mb-4"></div>

                    <input type="file" id="edit-post-file-input" name="images[]" class="hidden" accept="image/*" multiple>
                    <button type="button" id="btn-edit-post-change-photo" class="px-4 py-2 bg-gray-900 text-white rounded-full hover:bg-gray-700 mb-4">
                        Tambah Gambar
                    </button>

                    <button type="submit" id="btn-submit-edit-post" class="w-full py-3 bg-blue-600 text-white font-bold rounded-full hover:bg-blue-500 transition-colors">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const modalEditPost = document.getElementById("modal-edit-post");
const btnCloseEditPost = document.getElementById("btn-close-edit-post");
const btnChangePostPhoto = document.getElementById("btn-edit-post-change-photo");
const fileInputPost = document.getElementById("edit-post-file-input");
const mediaPreviewContainer = document.getElementById("media-preview-container");

let existingMedia = []; // path gambar lama yang tetap
let deletedMedia = []; // gambar lama yang dihapus
let newMediaFiles = []; // file baru

function openEditPostModal(postId, content, mediaPaths = []) {
    modalEditPost.classList.remove("hidden");
    modalEditPost.classList.add("flex");

    document.getElementById("edit-post-id").value = postId;
    document.getElementById("edit-post-content").value = content;

    existingMedia = [...mediaPaths];
    deletedMedia = [];
    newMediaFiles = [];
    renderMediaPreviews();
}

function renderMediaPreviews() {
    mediaPreviewContainer.innerHTML = '';

    // render gambar lama
    existingMedia.forEach((path, i) => {
        const div = document.createElement('div');
        div.className = 'relative w-20 h-20 rounded-lg overflow-hidden border';

        const img = document.createElement('img');
        img.src = '<?= BASEURL ?>' + path; // pastikan mediaPaths relatif ke BASEURL
        img.className = 'w-full h-full object-cover';
        div.appendChild(img);

        const btnX = document.createElement('button');
        btnX.type = 'button';
        btnX.innerText = '×';
        btnX.className = 'absolute top-0 right-0 bg-black/50 text-white w-6 h-6 rounded-full';
        btnX.onclick = () => {
            existingMedia.splice(i, 1);
            deletedMedia.push(path);
            renderMediaPreviews();
        };
        div.appendChild(btnX);

        mediaPreviewContainer.appendChild(div);
    });

    // render gambar baru
    newMediaFiles.forEach((file, i) => {
        const div = document.createElement('div');
        div.className = 'relative w-20 h-20 rounded-lg overflow-hidden border';

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.className = 'w-full h-full object-cover';
        div.appendChild(img);

        const btnX = document.createElement('button');
        btnX.type = 'button';
        btnX.innerText = '×';
        btnX.className = 'absolute top-0 right-0 bg-black/50 text-white w-6 h-6 rounded-full';
        btnX.onclick = () => {
            newMediaFiles.splice(i, 1);
            renderMediaPreviews();
        };
        div.appendChild(btnX);

        mediaPreviewContainer.appendChild(div);
    });
}

btnCloseEditPost.addEventListener('click', () => {
    modalEditPost.classList.add("hidden");
    modalEditPost.classList.remove("flex");
});

modalEditPost.addEventListener('click', (e) => {
    if (e.target === modalEditPost) {
        modalEditPost.classList.add("hidden");
        modalEditPost.classList.remove("flex");
    }
});

btnChangePostPhoto.addEventListener('click', () => fileInputPost.click());

fileInputPost.addEventListener('change', (e) => {
    const files = Array.from(e.target.files);
    newMediaFiles.push(...files);
    renderMediaPreviews();
    fileInputPost.value = ''; 
});

// submit form
const formEditPost = document.getElementById("form-edit-post");
formEditPost.addEventListener('submit', async (e) => {
    e.preventDefault();
    const submitBtn = document.getElementById("btn-submit-edit-post");
    const errorDiv = document.getElementById("edit-post-error");
    submitBtn.disabled = true;
    submitBtn.innerText = "Saving...";
    errorDiv.classList.add("hidden");

    const formData = new FormData(formEditPost);

    // existing media yang masih dipertahankan
    existingMedia.forEach(path => formData.append('existing_media[]', path));

    // gambar baru
    newMediaFiles.forEach(file => formData.append('images[]', file));

    // gambar lama yang dihapus
    deletedMedia.forEach(path => formData.append('deleted_media[]', path));

    try {
        const response = await fetch(formEditPost.action, { method: 'POST', body: formData });
        const result = await response.json();
        if(result.success) window.location.reload();
        else { 
            errorDiv.textContent = result.message || "Failed to update post."; 
            errorDiv.classList.remove("hidden"); 
        }
    } catch(err) {
        errorDiv.textContent = "Network error while updating post."; 
        errorDiv.classList.remove("hidden");
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerText = "Save Changes";
    }
});
</script>
