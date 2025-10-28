<form class="bg-white rounded-2xl p-4 shadow-md" method="POST" action="<?php echo BASEURL; ?>/post/create" enctype="multipart/form-data" id="createPostForm">
    <div class="flex items-start gap-4">
        <div class="flex size-10 md:size-14 rounded-full overflow-hidden flex-shrink-0">
            <img src="<?= !empty($_SESSION['path_photo'])
                            ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                            : BASEURL . '/src/asset/image/default.png' ?>"
                class="w-full h-full object-cover"
                alt="photo">
        </div>
        <textarea name="content" id="content" rows="3" placeholder="Apa yang sedang Anda pikirkan?" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 resize-none "></textarea>
    </div>

    <div id="image-preview-container" class="mt-4 flex flex-nowrap gap-3 overflow-x-auto pb-2">
    </div>

    <div class="flex justify-between items-center mt-4">
        <div>
            <label for="image-input" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 cursor-pointer">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/gallery-import.svg" class="w-6 h-6" alt="icon">
            </label>
            <input type="file" id="image-input" name="images[]" class="hidden" accept="image/*" multiple
                data-icon-url="<?php echo BASEURL; ?>/src/asset/icons/close-circle-grey.svg">
        </div>

        <button type="submit" id="submit-post-btn" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center flex items-center gap-2 transition-all duration-300">
            Posting
            <img src="<?php echo BASEURL; ?>/src/asset/image/send.png" class="size-4 mt-1" alt="icon">
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById("image-input");
        const previewContainer = document.getElementById("image-preview-container");
        const MAX_FILES = 5;
        const removeIconUrl = imageInput.dataset.iconUrl;

        let fileBuffer = [];

        function updateInputFiles() {
            const dataTransfer = new DataTransfer();
            fileBuffer.forEach(f => dataTransfer.items.add(f));
            imageInput.files = dataTransfer.files;
        }

        function removeFile(fileToRemove) {
            const indexToRemove = fileBuffer.findIndex(f => f === fileToRemove);
            if (indexToRemove > -1) {
                fileBuffer.splice(indexToRemove, 1);
                updateInputFiles();
                renderPreviews(); // Cukup panggil renderPreviews yang sudah diperbaiki
            }
        }

        // ===================================================================
        // INI ADALAH FUNGSI YANG DIPERBAIKI MENGGUNAKAN ASYNC/AWAIT DAN PROMISE.ALL
        // ===================================================================
        async function renderPreviews() {
            previewContainer.innerHTML = ""; // Kosongkan container

            // 1. Buat sebuah Promise untuk setiap file yang sedang dibaca
            const filePromises = fileBuffer.map(file => {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        // Jika berhasil, kirim hasil (URL gambar) dan file aslinya
                        resolve({
                            result: e.target.result,
                            originalFile: file
                        });
                    };
                    reader.onerror = reject;
                    reader.readAsDataURL(file);
                });
            });

            try {
                // 2. Tunggu SEMUA promise selesai. Promise.all menjamin urutannya tetap sama.
                const imageSources = await Promise.all(filePromises);

                // 3. Sekarang 'imageSources' adalah array berisi hasil yang sudah PASTI BERURUTAN
                imageSources.forEach(imageData => {
                    const {
                        result,
                        originalFile
                    } = imageData;

                    const wrapper = document.createElement('div');
                    wrapper.classList.add('relative', 'flex-shrink-0', 'h-44', 'w-auto');

                    const img = document.createElement("img");
                    img.src = result;
                    img.classList.add("h-full", "w-full", "rounded-lg", "object-cover");

                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.classList.add('absolute', 'top-1', 'right-1', 'size-6', 'flex', 'items-center', 'justify-center', 'bg-white', 'hover:bg-gray-200', 'rounded-full', 'transition-colors');
                    removeBtn.innerHTML = `<img src="${removeIconUrl}" class="w-6 h-6" alt="Remove">`;

                    // Event listener tetap menunjuk ke file asli yang unik
                    removeBtn.addEventListener('click', function(event) {
                        event.preventDefault();
                        removeFile(originalFile);
                    });

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    previewContainer.appendChild(wrapper);
                });
            } catch (error) {
                console.error("Gagal membaca salah satu file:", error);
            }
        }

        imageInput.addEventListener("change", function() {
            const selectedFiles = Array.from(this.files);

            if ((fileBuffer.length + selectedFiles.length) > MAX_FILES) {
                alert(`Maksimal ${MAX_FILES} gambar.`);
                this.value = "";
                return;
            }

            fileBuffer.push(...selectedFiles);
            updateInputFiles();
            renderPreviews(); // Panggil fungsi render yang baru
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createPostForm');
        const submitButton = document.getElementById('submit-post-btn');

        form.addEventListener('submit', async function(e) {
            e.preventDefault(); // Mencegah form dari submit bawaan browser

            // Nonaktifkan tombol untuk mencegah double-click
            submitButton.disabled = true;
            submitButton.textContent = 'Memposting...';

            const formData = new FormData(form);

            try {
                // Kirim data menggunakan Fetch API
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });

                // Cek apakah respons dari server adalah JSON yang valid
                if (!response.ok) {
                    // Jika server mengembalikan status error (4xx, 5xx), lempar error
                    throw new Error(`Server responded with ${response.status}: ${response.statusText}`);
                }

                const result = await response.json();

                if (result.success) {
                    alert('✅ ' + result.message);
                    window.location.reload(); // Refresh halaman jika berhasil
                } else {
                    alert('❌ ' + result.message);
                }
            } catch (err) {
                // Tangani error jaringan atau error parsing JSON
                alert('⚠️ Gagal terhubung ke server. Silakan coba lagi.');
                console.error('Fetch Error:', err);
            } finally {
                // Aktifkan kembali tombol setelah proses selesai (baik berhasil maupun gagal)
                submitButton.disabled = false;
                submitButton.innerHTML = `Posting <img src="<?php echo BASEURL; ?>/src/asset/image/send.png" class="size-4 mt-1" alt="icon">`;
            }
        });
    });
</script>