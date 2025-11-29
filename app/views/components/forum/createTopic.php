<div class="w-full relative">

    <div class="w-full relative">
        <div id="toast-limit-create" class="hidden fixed top-5 right-10 bg-red-600 text-white px-4 py-2 rounded shadow-lg z-50 text-sm">
            Maksimal hanya 5 file!
        </div>
        <div id="errorDiv" class="hidden fixed top-16 right-10 bg-red-100 text-red-700 px-4 py-2 rounded shadow-lg z-50 text-sm border border-red-400"></div>
        <div id="successDiv" class="hidden fixed top-16 right-10 bg-green-100 text-green-700 px-4 py-2 rounded shadow-lg z-50 text-sm border border-green-400"></div>

        <div class="bg-white rounded-lg shadow p-6">
            <form id="createPostForm" action="<?= BASEURL ?>/create/topics" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="forum_id" value="<?= $forumById['ID'] ?>">
                <div class="flex gap-3 items-start mb-3">
                    <div class="w-12 h-12 shrink-0 rounded-full overflow-hidden">
                        <img src="<?= !empty($_SESSION['path_photo'])
                                        ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                        : BASEURL . '/src/asset/image/default.png' ?>"
                            class="w-full h-full object-cover"
                            alt="photo">
                    </div>

                    <div class="relative w-full">
                        <textarea
                            id="content"
                            name="content"
                            placeholder="Apa yang Anda pikirkan?"
                            class="flex-1 w-full hide-scrollbar bg-gray-100 rounded-2xl px-4 py-3 pr-16 hover:bg-gray-200 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition resize-none h-24"></textarea>

                        <span
                            id="contentCounter"
                            class="absolute right-4 pt-3 bottom-3 text-xs text-gray-500 pointer-events-none">
                            0/300
                        </span>
                    </div>

                </div>

                <div id="preview-wrapper" class="hidden mb-4">
                    <p class="text-xs text-gray-500 mb-2 ml-1">Media Preview:</p>
                    <div id="image-preview-container" class="flex gap-3 overflow-x-auto no-scrollbar pb-2 snap-x">
                    </div>
                </div>

                <div class="border-t pt-3 flex justify-between items-center">

                    <div id="upload-btn-wrapper">
                        <input type="file" id="image-input" name="media[]" multiple accept="image/*, .pdf, .doc, .docx, .xls, .xlsx, .zip" class="hidden">

                        <label for="image-input" class="group flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-50 transition cursor-pointer select-none">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-600 rounded-full group-hover:bg-blue-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-gray-600 group-hover:text-blue-600 font-medium text-sm transition">
                                Foto / File
                            </span>
                        </label>
                    </div>

                    <button type="submit" id="submit-post-btn" class="bg-blue-600 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                        <span>Posting</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createPostForm');
        const submitButton = document.getElementById('submit-post-btn');
        const successDiv = document.getElementById("successDiv");
        const errorDiv = document.getElementById("errorDiv");
        const textarea = document.getElementById("content");

        const previewWrapper = document.getElementById("preview-wrapper");
        const previewContainer = document.getElementById("image-preview-container");
        const imageInput = document.getElementById("image-input");
        const uploadBtnWrapper = document.getElementById("upload-btn-wrapper");
        const content = document.getElementById('content');
        const contentCounter = document.getElementById('contentCounter');
        const CONTENT_MAX = 300;

        const MAX_FILES = 5;

        let fileBuffer = [];
        let fileIdCounter = 0;

        content.addEventListener('input', function() {
            if (this.value.length > CONTENT_MAX) {
                this.value = this.value.slice(0, CONTENT_MAX);
            }
            contentCounter.textContent = `${this.value.length}/${CONTENT_MAX}`;
        });

        function showCreateLimitToast() {
            const toast = document.getElementById("toast-limit-create");
            toast.classList.remove("hidden");
            setTimeout(() => {
                toast.classList.add("hidden");
            }, 2500);
        }

        function showEmptyPostToast() {
            errorDiv.innerHTML = "Konten atau gambar tidak boleh kosong!";
            errorDiv.classList.remove("hidden");
            setTimeout(() => errorDiv.classList.add("hidden"), 2500);
        }

        function updateInputFiles() {
            const dt = new DataTransfer();
            fileBuffer.forEach(file => dt.items.add(file));
            imageInput.files = dt.files;

            if (fileBuffer.length > 0) {
                previewWrapper.classList.remove('hidden');
            } else {
                previewWrapper.classList.add('hidden');
            }
        }

        function updateImageInputState() {
            if (fileBuffer.length >= MAX_FILES) {
                imageInput.disabled = true;
                uploadBtnWrapper.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                imageInput.disabled = false;
                uploadBtnWrapper.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }


        function removeFile(fileToRemove) {
            fileBuffer = fileBuffer.filter(f => f !== fileToRemove);

            updateInputFiles();
            renderPreviews();
            updateImageInputState();
        }

        async function renderPreviews() {
            previewContainer.innerHTML = "";

            for (const file of fileBuffer) {
                const reader = new FileReader();

                const base64 = await new Promise((resolve) => {
                    reader.onload = e => resolve(e.target.result);
                    reader.readAsDataURL(file);
                });

                const wrapper = document.createElement('div');
                wrapper.className = 'relative group flex-none rounded-lg overflow-hidden shadow-sm border border-gray-200 snap-center bg-gray-50';

                if (file.type.startsWith('image/')) {
                    wrapper.classList.add('w-40', 'h-40');
                } else {
                    wrapper.classList.add('w-64', 'p-3', 'flex', 'items-center', 'gap-3');
                }

                let contentHtml = '';
                if (file.type.startsWith('image/')) {
                    contentHtml = `<img src="${base64}" class="w-full h-full object-cover" alt="preview">`;
                } else {
                    contentHtml = `
                        <div class="bg-blue-100 p-2 rounded-lg text-blue-500 shrink-0">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                             </svg>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-bold text-gray-800 truncate">${file.name}</p>
                            <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</p>
                        </div>
                    `;
                }

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute top-1 right-1 size-6 flex items-center justify-center bg-gray-900/60 hover:bg-red-600 text-white rounded-full transition-colors shadow-sm backdrop-blur-sm z-10';
                removeBtn.innerHTML = `<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>`;

                removeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    removeFile(file);
                });

                wrapper.innerHTML = contentHtml;
                wrapper.appendChild(removeBtn);
                previewContainer.appendChild(wrapper);
            }
        }

        imageInput.addEventListener("change", function() {
            const selectedFiles = Array.from(this.files);
            const availableSlots = MAX_FILES - fileBuffer.length;

            if (availableSlots <= 0) {
                showCreateLimitToast();
                this.value = "";
                return;
            }

            const filesToAdd = selectedFiles.slice(0, availableSlots);
            const rejectedCount = selectedFiles.length - filesToAdd.length;

            const newFiles = filesToAdd.map((file, idx) => {
                file.uploadOrder = fileBuffer.length + idx;
                file.uniqueId = fileIdCounter++;
                return file;
            });

            fileBuffer.push(...newFiles);

            updateInputFiles();
            renderPreviews();
            updateImageInputState();

            if (rejectedCount > 0) {
                showCreateLimitToast();
            }

            this.value = "";
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (fileBuffer.length === 0 && textarea.value.trim() === "") {
                showEmptyPostToast();
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = 'Memposting...';

            const formData = new FormData();

            formData.append("content", textarea.value.trim());

            const forumIdValue = form.querySelector('input[name="forum_id"]').value;
            formData.append("forum_id", forumIdValue);

            fileBuffer.forEach((file, index) => {
                formData.append("images[]", file, file.name);
            });

            const imageOrder = fileBuffer.map((f, i) => ({
                index: i,
                originalName: f.name,
                size: f.size
            }));
            formData.append("image_order", JSON.stringify(imageOrder));

            console.log("Mengirim data...", {
                content: textarea.value,
                files: fileBuffer.length,
                order: imageOrder
            });

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    textarea.value = "";
                    fileBuffer = [];
                    updateInputFiles();
                    renderPreviews();

                    successDiv.innerHTML = result.message;
                    successDiv.classList.remove("hidden");
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    errorDiv.innerHTML = result.message;
                    errorDiv.classList.remove("hidden");
                    setTimeout(() => errorDiv.classList.add("hidden"), 2500);
                }
            } catch (err) {
                console.error(err);
                errorDiv.innerHTML = "Terjadi kesalahan jaringan.";
                errorDiv.classList.remove("hidden");
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = `Posting ...`;
            }
        });
    });
</script>