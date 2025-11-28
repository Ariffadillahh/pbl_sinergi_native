<form class="bg-white rounded-2xl p-4 shadow-md"
    method="POST"
    action="<?= BASEURL; ?>/post/create"
    enctype="multipart/form-data"
    id="createPostForm">

    <div class="flex items-start gap-4 relative">
        <div class="flex size-10 md:size-14 rounded-full overflow-hidden flex-shrink-0">
            <img src="<?= !empty($_SESSION['path_photo'])
                            ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                            : BASEURL . '/src/asset/image/default.png' ?>"
                class="w-full h-full object-cover"
                alt="photo">
        </div>

        <div class="flex-1 relative">
            <textarea id="content"
                name="content"
                rows="1"
                placeholder="Apa yang sedang Anda pikirkan?"
                class="w-full bg-gray-100 rounded-lg px-4 py-3 pr-14 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 resize-none min-h-[3.5rem] max-h-48 overflow-y-auto"></textarea>

            <span id="contentCounter"
                class="absolute bottom-2 right-4 text-xs text-gray-500 pointer-events-none">
                0/250
            </span>

            <div id="mention"
                class="hidden absolute bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto z-50 w-full mt-1">
            </div>
        </div>

    </div>

    <div id="image-preview-container" class="mt-4 flex flex-nowrap gap-3 overflow-x-auto pb-2"></div>

    <div class="flex justify-between items-center mt-4">
        <div>
            <label for="image-input"
                class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-blue-600 transition-all duration-300 cursor-pointer">
                <img src="<?= BASEURL; ?>/src/asset/icons/gallery-import.svg" class="w-6 h-6" alt="icon">
            </label>
            <input type="file"
                id="image-input"
                name="images[]"
                class="hidden"
                accept="image/*"
                multiple
                data-icon-url="<?= BASEURL; ?>/src/asset/icons/close-circle-grey.svg">
        </div>

        <button type="submit"
            id="submit-post-btn"
            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center flex items-center gap-2 transition-all duration-300">
            Posting
            <img src="<?= BASEURL; ?>/src/asset/image/send.png" class="size-4 mt-1" alt="icon">
        </button>
    </div>
</form>

<?php
$moodUrl = '';
if (isset($_SESSION['img_mood']) && !empty($_SESSION['img_mood'])) {
    $moodUrl = BASEURL . $_SESSION['img_mood'];
}
?>
<div id="mood-data" data-url="<?= $moodUrl ?>" class="hidden"></div>

<div id="toast-limit-create"
    class="hidden fixed top-5 right-5 z-[99999]">
    <div class="bg-red-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 animate-fade-in">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <span class="font-semibold">Maksimal 5 gambar.</span>
    </div>
</div>

<div id="successDiv" class="hidden fixed top-5 right-5 z-[99999] bg-green-500 text-white px-4 py-3 rounded-xl shadow-lg animate-fade-in"></div>
<div id="errorDiv" class="hidden fixed top-5 right-5 z-[99999] bg-red-500 text-white px-4 py-3 rounded-xl shadow-lg animate-fade-in"></div>


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
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createPostForm');
        const submitButton = document.getElementById('submit-post-btn');
        const sendIcon = submitButton.querySelector('img');
        const successDiv = document.getElementById("successDiv");
        const errorDiv = document.getElementById("errorDiv");
        const textarea = document.getElementById("content");

        const previewContainer = document.getElementById("image-preview-container");
        const imageInput = document.getElementById("image-input");
        const removeIconUrl = imageInput.dataset.iconUrl;
        const MAX_FILES = 5;

        let fileBuffer = [];
        let fileIdCounter = 0;

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
        }

        function updateImageInputState() {
            if (fileBuffer.length >= MAX_FILES) {
                imageInput.disabled = true;
                imageInput.parentElement.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                imageInput.disabled = false;
                imageInput.parentElement.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        function removeFile(fileToRemove) {
            if (fileToRemove.serverFilename) {
                fetch('<?= BASEURL ?>/mood/delete-preview', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            filename: fileToRemove.serverFilename
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log('File di server dihapus:', data);
                    })
                    .catch(err => console.error('Gagal hapus server:', err));
            }

            fileBuffer = fileBuffer.filter(f => f !== fileToRemove);

            renderPreviews();
        }

        async function renderPreviews() {
            previewContainer.innerHTML = "";

            for (const file of fileBuffer) {
                const reader = new FileReader();
                const base64 = await new Promise((resolve, reject) => {
                    reader.onload = e => resolve(e.target.result);
                    reader.onerror = reject;
                    reader.readAsDataURL(file);
                });

                const wrapper = document.createElement('div');
                wrapper.className = 'relative flex-shrink-0 h-44 w-auto';

                const img = document.createElement("img");
                img.src = base64;
                img.className = "h-full w-full rounded-lg object-cover";

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute top-1 right-1 size-6 flex items-center justify-center bg-white hover:bg-gray-200 rounded-full transition-colors shadow-sm';
                removeBtn.innerHTML = `<img src="${removeIconUrl}" class="w-5 h-5" alt="Remove">`;

                removeBtn.addEventListener('click', e => {
                    e.preventDefault();
                    removeFile(file);
                });

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                previewContainer.appendChild(wrapper);
            }
        }

        async function initMoodImage() {
            const moodElement = document.getElementById('mood-data');
            const moodUrl = moodElement ? moodElement.dataset.url : '';

            if (!moodUrl) return;

            try {
                const response = await fetch(moodUrl);
                const blob = await response.blob();

                const urlParts = moodUrl.split('/');
                const realFilename = urlParts[urlParts.length - 1];

                const file = new File([blob], realFilename, {
                    type: blob.type
                });

                file.serverFilename = realFilename;

                file.uploadOrder = 0;
                file.uniqueId = fileIdCounter++;

                fileBuffer.push(file);
                updateInputFiles();
                renderPreviews();
                updateImageInputState();

                console.log("Mood image loaded:", realFilename);
            } catch (error) {
                console.error("Gagal memuat gambar mood:", error);
            }
        }

        initMoodImage();


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

            fileBuffer.forEach((file, index) => {
                const orderedFilename = `${String(index).padStart(3, '0')}_${file.name}`;
                formData.append("images[]", file, orderedFilename);
            });

            const imageOrder = fileBuffer.map((f, i) => ({
                index: i,
                originalName: f.name,
                size: f.size
            }));
            formData.append("image_order", JSON.stringify(imageOrder));

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
                    previewContainer.innerHTML = "";

                    successDiv.innerHTML = `
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            ${result.message}
                        </div>
                    `;
                    successDiv.classList.remove("hidden");

                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);

                } else {
                    errorDiv.innerHTML = `
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            ${result.message}
                        </div>
                    `;
                    errorDiv.classList.remove("hidden");

                    setTimeout(() => {
                        errorDiv.classList.add("hidden");
                    }, 2500);
                }

            } catch (err) {
                errorDiv.innerHTML = `
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Terjadi kesalahan jaringan.
                    </div>
                `;
                errorDiv.classList.remove("hidden");
                setTimeout(() => errorDiv.classList.add("hidden"), 2500);
                console.error(err);

            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = `Posting <img src="<?= BASEURL; ?>/src/asset/image/send.png" class="size-4 mt-1" alt="icon">`;
            }
        });
    });

    const contentInput = document.getElementById('content');
    const contentCounter = document.getElementById('contentCounter');
    const CONTENT_MAX = 250;

    contentInput.addEventListener('input', function() {
        if (this.value.length > CONTENT_MAX) {
            this.value = this.value.slice(0, CONTENT_MAX);
        }

        contentCounter.textContent = `${this.value.length}/${CONTENT_MAX}`;
    });


    const textarea = document.getElementById('content');
    const mentionDropdown = document.getElementById('mention');
    let users = [];

    async function fetchUsers() {
        try {
            const response = await fetch('<?= BASEURL ?>/get-all-user');
            if (!response.ok) throw new Error(`Server responded with ${response.status}`);

            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                return;
            }
            const data = await response.json();
            if (data.success && Array.isArray(data.data)) {
                users = data.data;
            }
        } catch (err) {
            console.error('Failed to fetch users for mentions:', err);
        }
    }

    fetchUsers();

    let mentionStartPos = -1;
    let mentionQuery = '';

    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        const maxHeight = 192;
        this.style.height = Math.min(this.scrollHeight, maxHeight) + 'px';
        this.style.overflowY = this.scrollHeight > maxHeight ? 'auto' : 'hidden';
        handleMention();
    });

    function handleMention() {
        const cursorPos = textarea.selectionStart;
        const textBefore = textarea.value.substring(0, cursorPos);
        const lastAt = textBefore.lastIndexOf('@');

        if (lastAt !== -1) {
            const query = textBefore.substring(lastAt + 1);
            if (!query.includes(' ') && !query.includes('\n')) {
                mentionStartPos = lastAt;
                mentionQuery = query.toLowerCase();
                showMentionSuggestions(mentionQuery);
                return;
            }
        }
        hideMentionDropdown();
    }

    function showMentionSuggestions(query) {
        if (!users || users.length === 0) return hideMentionDropdown();

        const filtered = users.filter(u =>
            u.FULL_NAME.toLowerCase().includes(query) ||
            u.USERNAME.toLowerCase().includes(query)
        );

        if (!filtered.length) return hideMentionDropdown();

        mentionDropdown.innerHTML = filtered.map(u => {
            const roleBadges = {
                'ADMIN': 'bg-red-100 text-red-700 border-red-200',
                'MAHASISWA': 'bg-blue-100 text-blue-700 border-blue-200',
                'DOSEN': 'bg-green-100 text-green-700 border-green-200',
            };
            const role = u.ROLE;
            const badgeColor = roleBadges[role] || 'bg-gray-100 text-gray-700 border-gray-200';

            return `
                <div class="mention-item group flex items-center gap-3 px-4 py-3 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 cursor-pointer transition-all duration-200 border-b border-gray-100 last:border-b-0"
                    data-username="${u.USERNAME}" data-name="${u.FULL_NAME}">
                    <div class="relative">
                        <div class="size-11 rounded-full overflow-hidden flex-shrink-0 ring-2 ring-gray-200 group-hover:ring-blue-400 transition-all duration-200">
                            <img src="${u.PATH_PHOTO ? `<?= BASEURL; ?>/storage/users/photos/${u.PATH_PHOTO}` : `<?= BASEURL; ?>/src/asset/image/default.png`}"
                                class="w-full h-full object-cover" 
                                alt="${u.FULL_NAME}">
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <div class="font-semibold text-gray-900 truncate group-hover:text-blue-600 transition-colors">
                                ${u.FULL_NAME}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500 truncate">@${u.USERNAME}</span>
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full border ${badgeColor} flex-shrink-0">
                                ${role.charAt(0).toUpperCase() + role.slice(1)}
                            </span>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        mentionDropdown.classList.remove('hidden');

        mentionDropdown.querySelectorAll('.mention-item').forEach(item => {
            item.addEventListener('click', () => {
                insertMention(item.dataset.username);
            });
        });
    }

    function insertMention(username) {
        const before = textarea.value.substring(0, mentionStartPos);
        const after = textarea.value.substring(textarea.selectionStart);
        textarea.value = before + `@${username} ` + after;
        const newPos = mentionStartPos + username.length + 2;
        textarea.setSelectionRange(newPos, newPos);
        hideMentionDropdown();
        textarea.focus();
    }

    function hideMentionDropdown() {
        mentionDropdown.classList.add('hidden');
        mentionStartPos = -1;
        mentionQuery = '';
    }

    document.addEventListener('click', e => {
        if (!mentionDropdown.contains(e.target) && e.target !== textarea) hideMentionDropdown();
    });
</script>