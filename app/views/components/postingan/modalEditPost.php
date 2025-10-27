<div id="modal-edit-post" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm">
  <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-500">
      <h3 class="text-lg font-bold text-white">Edit Postingan</h3>
      <button id="btn-close-edit-post" class="text-white hover:bg-white/20 w-8 h-8 flex items-center justify-center rounded-full">&times;</button>
    </div>

    <!-- Body -->
    <div class="p-6 space-y-4">
      <p id="edit-post-error" class="bg-red-600 p-2 text-white text-center rounded-lg hidden"></p>

      <form id="form-edit-post" action="<?= BASEURL ?>/post/update" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="post_id" id="edit-post-id">

        <textarea
          name="content"
          id="edit-post-content"
          rows="4"
          placeholder="Tulis sesuatu..."
          class="w-full p-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
        ></textarea>

        <!-- Gambar Preview -->
        <div id="media-preview-container" class="grid grid-cols-3 sm:grid-cols-4 gap-3"></div>

        <!-- Upload Gambar -->
        <div class="flex justify-between items-center">
          <button type="button" id="btn-edit-post-change-photo" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Gambar
          </button>

          <input type="file" id="edit-post-file-input" name="images[]" class="hidden" accept="image/*" multiple>
        </div>

        <!-- Tombol Simpan -->
        <button type="submit" id="btn-submit-edit-post" class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-bold rounded-full hover:opacity-90 transition">
          Simpan Perubahan
        </button>
      </form>
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
    div.className = 'relative aspect-square rounded-lg overflow-hidden group shadow-sm hover:shadow-md transition';

    const img = document.createElement('img');
    img.src = '<?= rtrim(BASEURL, "/") ?>/' + path.replace(/^\/+/, '');
    img.className = 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105';
    div.appendChild(img);

    const btnX = document.createElement('button');
    btnX.type = 'button';
    btnX.innerHTML = '&times;';
    btnX.className = 'absolute top-1 right-1 bg-black/60 text-white text-sm w-6 h-6 flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition';
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
    div.className = 'relative aspect-square rounded-lg overflow-hidden group shadow-sm hover:shadow-md transition';

    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.className = 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105';
    div.appendChild(img);

    const btnX = document.createElement('button');
    btnX.type = 'button';
    btnX.innerHTML = '&times;';
    btnX.className = 'absolute top-1 right-1 bg-black/60 text-white text-sm w-6 h-6 flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition';
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

    if (!response.ok) throw new Error(`Server returned ${response.status}`);

    let result;
    try {
        result = await response.json();
    } catch (err) {
        console.error("Response bukan JSON:", await response.text());
        throw new Error("Response bukan JSON");
    }

    if (result.success) {
        window.location.reload();
    } else {
        errorDiv.textContent = result.message || "Gagal memperbarui postingan.";
        errorDiv.classList.remove("hidden");
    }
    } catch (err) {
    console.error(err);
    errorDiv.textContent = "Terjadi kesalahan saat memproses update.";
    errorDiv.classList.remove("hidden");
    } finally {
    submitBtn.disabled = false;
    submitBtn.innerText = "Simpan Perubahan";
    }

});
</script>
