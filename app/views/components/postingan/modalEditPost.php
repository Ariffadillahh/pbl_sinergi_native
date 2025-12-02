<div id="modal-edit-post" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm p-3">
  <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-fade-in">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-500">
      <h3 class="text-lg font-bold text-white">Edit Post</h3>
      <button id="btn-close-edit-post" class="text-white hover:bg-white/20 w-8 h-8 flex items-center justify-center rounded-full cursor-pointer">&times;</button>
    </div>

    <div class="p-6 space-y-4">
      <p id="edit-post-error" class="bg-red-600 p-2 text-white text-center rounded-lg hidden"></p>
      <p id="edit-post-succses" class="bg-green-600 p-2 text-white text-center rounded-lg hidden"></p>

      <form id="form-edit-post" action="<?= BASEURL ?>/post/update" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="post_id" id="edit-post-id">

        <div>
          <textarea
            name="content"
            id="edit-post-content"
            rows="4"
            maxlength="250"
            placeholder="Write Something..."
            class="w-full p-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"></textarea>

          <div class="text-right text-xs text-gray-500 mt-1">
            <span id="edit-post-char-count">0</span>/250
          </div>
        </div>

        <div id="media-preview-container" class="grid grid-cols-3 sm:grid-cols-4 gap-3"></div>

        <div class="flex justify-between items-center">
          <button type="button" id="btn-edit-post-change-photo" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Add Photos
          </button>

          <input type="file" id="edit-post-file-input" name="images[]" class="hidden" accept="image/*" multiple>
        </div>

        <button type="submit" id="btn-submit-edit-post" class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-bold rounded-full hover:opacity-90 transition cursor-pointer">
          Save Change
        </button>
      </form>
    </div>
  </div>
</div>

<div id="toast-limit" class="hidden fixed top-5 right-5 z-[99999]">
  <div class="bg-red-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in">
    <span class="font-semibold">5 Photo Maximum</span>
  </div>
</div>

<style>
  @keyframes fade-in {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
</style>

<script>
  const modalEditPost = document.getElementById("modal-edit-post");
  const btnCloseEditPost = document.getElementById("btn-close-edit-post");
  const btnChangePostPhoto = document.getElementById("btn-edit-post-change-photo");
  const fileInputPost = document.getElementById("edit-post-file-input");
  const mediaPreviewContainer = document.getElementById("media-preview-container");
  const formEditPost = document.getElementById("form-edit-post");

  // 1. Ambil elemen Textarea dan Counter
  const editPostContent = document.getElementById("edit-post-content");
  const editPostCharCount = document.getElementById("edit-post-char-count");

  let existingMedia = [];
  let deletedMedia = [];
  let newMediaFiles = [];

  // Fungsi utama saat tombol Edit diklik
  function openEditPostModal(postId, content, mediaPaths = []) {
    modalEditPost.classList.remove("hidden");
    modalEditPost.classList.add("flex");

    document.getElementById("edit-post-id").value = postId;

    // 2. Masukkan konten lama ke textarea
    editPostContent.value = content;

    // 3. PENTING: Langsung hitung panjang karakter konten lama saat modal dibuka
    // Jika content null/undefined, hitung sebagai 0
    editPostCharCount.textContent = content ? content.length : 0;

    existingMedia = [...mediaPaths];
    deletedMedia = [];
    newMediaFiles = [];
    renderMediaPreviews();
  }

  // 4. Event Listener: Update angka saat user mengetik atau menghapus teks
  editPostContent.addEventListener('input', function() {
    const currentLength = this.value.length;
    editPostCharCount.textContent = currentLength;
  });

  // --- Sisa fungsi lainnya (Preview Gambar, Close, Submit) tetap sama ---

  function renderMediaPreviews() {
    mediaPreviewContainer.innerHTML = '';

    existingMedia.forEach((path, i) => {
      const div = document.createElement('div');
      div.className = 'relative aspect-square rounded-lg overflow-hidden group shadow-sm hover:shadow-md transition';

      const img = document.createElement('img');
      // Pastikan BASEURL di PHP mencetak url yang benar
      img.src = '<?= rtrim(BASEURL, "/") ?>/' + path.replace(/^\/+/, '');
      img.className = 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-105';
      div.appendChild(img);

      const btnX = document.createElement('button');
      btnX.type = 'button';
      btnX.innerHTML = '&times;';
      btnX.className = 'absolute top-1 right-1 bg-black/60 text-white text-sm w-6 h-6 flex items-center justify-center rounded-full opacity-0 group-hover:opacity-100 transition cursor-pointer';
      btnX.onclick = () => {
        existingMedia.splice(i, 1);
        deletedMedia.push(path);
        renderMediaPreviews();
      };
      div.appendChild(btnX);

      mediaPreviewContainer.appendChild(div);
    });

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

  function showLimitToast() {
    const toast = document.getElementById("toast-limit");
    toast.classList.remove("hidden");
    setTimeout(() => {
      toast.classList.add("hidden");
    }, 2500);
  }

  btnChangePostPhoto.addEventListener('click', () => fileInputPost.click());

  fileInputPost.addEventListener('change', (e) => {
    const files = Array.from(e.target.files);
    const totalMedia = existingMedia.length + newMediaFiles.length + files.length;

    if (totalMedia > 5) {
      showLimitToast();
      fileInputPost.value = "";
      return;
    }

    newMediaFiles.push(...files);
    renderMediaPreviews();
    fileInputPost.value = "";
  });

  formEditPost.addEventListener('submit', async (e) => {
    e.preventDefault();

    const submitBtn = document.getElementById("btn-submit-edit-post");
    const errorDiv = document.getElementById("edit-post-error");
    const succsesDiv = document.getElementById("edit-post-succses");

    submitBtn.disabled = true;
    submitBtn.innerText = "Saving...";
    errorDiv.classList.add("hidden");

    const formData = new FormData();

    formData.append('post_id', document.getElementById('edit-post-id').value);
    formData.append('content', document.getElementById('edit-post-content').value);

    existingMedia.forEach(path => {
      formData.append('existing_media[]', path);
    });

    deletedMedia.forEach(path => {
      formData.append('deleted_media[]', path);
    });

    newMediaFiles.forEach(file => {
      formData.append('images[]', file, file.name);
    });

    try {
      const response = await fetch(formEditPost.action, {
        method: 'POST',
        body: formData
      });

      if (!response.ok) {
        throw new Error(`Server error: ${response.statusText}`);
      }

      const result = await response.json();

      if (result.success) {
        succsesDiv.classList.remove("hidden");
        succsesDiv.textContent = "Your post successfully updated.";

        setTimeout(() => {
          succsesDiv.classList.add("hidden");
          modalEditPost.classList.add("hidden");
          modalEditPost.classList.remove("flex");
          location.reload();
        }, 1500);
      } else {
        errorDiv.textContent = result.message || "Failed updating post.";
        errorDiv.classList.remove("hidden");
      }
    } catch (err) {
      console.error("Fetch error:", err);
      errorDiv.textContent = "Terjadi kesalahan koneksi ke server.";
      errorDiv.classList.remove("hidden");
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerText = "Save Change";
    }
  });
</script>