    <div class="tab-content hidden" data-content="settings">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-md p-8 flex flex-col items-center text-center w-full">
                <div class="flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>

                <h3 class="text-2xl font-bold mb-2">Pengaturan Grup</h3>
                <p class="text-gray-600 text-sm mb-6 max-w-xs">
                    Ubah nama, deskripsi, foto, dan pengaturan privasi forum ini.
                </p>

                <button onclick="openEditModal()" class="bg-blue-100 border-blue-600 border text-blue-600 px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2 shadow transition">
                    Edit Informasi Forum
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-md p-8 flex flex-col items-center text-center w-full">
                <div class="flex items-center justify-center w-20 h-20 rounded-full bg-green-50 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>

                <h3 class="text-2xl font-bold mb-2">Buat Akun Mitra</h3>
                <p class="text-gray-600 text-sm mb-6 max-w-xs">
                    Buat akun mitra industri untuk berkolaborasi dalam forum ini dan mengembangkan jaringan profesional.
                </p>

                <button onclick="openRequestMitraModal()" class="bg-green-100 border-green-600 border text-green-600 px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2 shadow transition hover:bg-green-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Request Akun Mitra
                </button>
            </div>

            <div class="bg-white rounded-xl shadow-md p-8 flex flex-col items-center text-center w-full">
                <div class="flex items-center justify-center w-20 h-20 rounded-full bg-red-50 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M12 3a9 9 0 110 18 9 9 0 010-18z" />
                    </svg>
                </div>

                <h4 class="text-xl font-bold text-red-600 mb-2">Danger Zone</h4>
                <p class="text-gray-500 text-sm mb-6 max-w-xs">
                    Tindakan ini tidak dapat dibatalkan. Seluruh postingan dan anggota akan ikut terhapus.
                </p>

                <button onclick="openDeleteModal()" class="w-full md:w-auto border border-red-300 bg-red-50 hover:bg-red-100 text-red-600 px-6 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
                    </svg>
                    Hapus Forum Ini
                </button>
            </div>
        <?php require_once 'app/views/components/forum/modalRequestMitra.php'; ?>
        </div>
    </div>



    <?php
    $currentBanner = !empty($forumById['PATH_THUMBNAIL']) ? BASEURL . '/storage/forums/thumbnail/' . $forumById['PATH_THUMBNAIL'] : '';
    $currentPhoto  = !empty($forumById['PATH_PHOTO']) ? BASEURL . '/storage/forums/photos/' . $forumById['PATH_PHOTO'] : 'https://ui-avatars.com/api/?name=' . urlencode($forumById['NAME']) . '&background=random&size=128';
    $isPrivate     = $forumById['IS_PRIVATE'] == 1;
    ?>

    <div id="editModalOverlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999] hidden opacity-0 transition-opacity duration-300">


        <div id="editAlertBox" class="fixed right-5 top-5  mb-4 p-4 rounded-lg text-sm hidden"></div>


        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-all duration-300 flex flex-col max-h-[90vh]">

            <div class="relative shrink-0 bg-gray-50 border-b border-gray-200 z-10">
                <div class="h-36 md:h-44 bg-gray-300 w-full object-cover flex items-center justify-center relative overflow-hidden group">
                    <span id="editDefaultBannerText" class="text-gray-500 font-bold text-3xl tracking-widest select-none opacity-50 <?= $currentBanner ? 'hidden' : '' ?>">SINERGI</span>

                    <img id="editBannerImage" src="<?= $currentBanner ?>" alt="Banner" class="w-full h-full object-cover absolute inset-0 <?= $currentBanner ? '' : 'hidden' ?>">
                    <div class="absolute inset-0 bg-black/10"></div>
                </div>

                <div class="absolute bottom-0 left-0 right-0 px-6 translate-y-1/2 flex items-end justify-between z-20">
                    <div class="flex items-end gap-4">
                        <div class="relative group">
                            <img id="editProfilePreview"
                                src="<?= $currentPhoto ?>"
                                alt="Profile"
                                class="w-20 h-20 md:w-24 md:h-24 rounded-xl border-4 border-white bg-white shadow-lg object-cover">
                        </div>
                        <div class="mb-1 md:mb-2 pt-16">
                            <h2 id="editGroupNamePreview" class="text-xl md:text-2xl font-bold text-gray-900 leading-tight truncate max-w-[200px] md:max-w-xs drop-shadow-sm bg-white/50 backdrop-blur-sm px-2 rounded-lg">
                                <?= htmlspecialchars($forumById['NAME']) ?>
                            </h2>
                            <div class="mt-1 flex items-center gap-1 bg-white/80 backdrop-blur-md px-2 py-0.5 rounded-full w-max shadow-sm border border-gray-100">
                                <span id="editPrivacyIcon" class="<?= $isPrivate ? 'text-gray-600' : 'text-blue-600' ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $isPrivate ? 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' : 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z' ?>"></path>
                                    </svg>
                                </span>
                                <span id="editPrivacyText" class="text-xs font-semibold text-gray-700">
                                    <?= $isPrivate ? 'Private' : 'Public' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form id="updateForumForm" class="p-6 pt-16 overflow-y-auto hide-scrollbar" method="POST" action="<?php echo BASEURL ?>/forum/update" enctype="multipart/form-data">

                <input type="hidden" name="ID" value="<?= $forumById['ID'] ?>">

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Edit Forum</h2>
                    <button type="button" onclick="closeEditModal()" class="p-2 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>


                <div class="mb-5 relative">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Forum</label>

                    <div class="relative">
                        <input
                            type="text"
                            id="editGroupNameInput"
                            name="NAME"
                            placeholder="Contoh: Komunitas Koding Indonesia"
                            class="w-full px-4 py-3 pr-14 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            required>
                        <span
                            id="nameCounter"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">
                            0/15
                        </span>
                    </div>

                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Privasi</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer relative">
                            <input type="radio" name="IS_PRIVATE" value="0" class="peer sr-only" <?= !$isPrivate ? 'checked' : '' ?>>
                            <div class="p-3 rounded-xl border border-gray-200 hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-500 transition flex items-center gap-3">
                                <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                                    🌐
                                </div>
                                <div>
                                    <span class="block text-sm font-semibold text-gray-900">Public</span>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer relative">
                            <input type="radio" name="IS_PRIVATE" value="1" class="peer sr-only" <?= $isPrivate ? 'checked' : '' ?>>
                            <div class="p-3 rounded-xl border border-gray-200 hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-500 transition flex items-center gap-3">
                                <div class="bg-gray-100 text-gray-600 p-2 rounded-lg">
                                    🔒
                                </div>
                                <div>
                                    <span class="block text-sm font-semibold text-gray-900">Private</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="editAccessKeyContainer" class="mb-5 transition-all duration-300 <?= $isPrivate ? '' : 'hidden' ?>">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kunci Akses</label>
                    <input type="text" id="editAccessKeyInput" name="ACCESS_KEY"
                        value="<?= htmlspecialchars($forumById['ACCESS_KEY'] ?? '') ?>"
                        placeholder="Masukkan kunci akses baru..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 bg-yellow-50 outline-none transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Profil</label>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-blue-50 transition relative overflow-hidden">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <p class="text-xs text-gray-500 font-medium">Klik untuk ganti</p>
                            </div>
                            <input id="editProfileInput" type="file" name="PATH_PHOTO" class="hidden" accept="image/*" />
                            <img id="editProfileInputPreview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Banner</label>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-blue-50 transition relative overflow-hidden">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <p class="text-xs text-gray-500 font-medium">Klik untuk ganti</p>
                            </div>
                            <input id="editBannerInput" type="file" name="PATH_THUMBNAIL" class="hidden" accept="image/*" />
                            <img id="editBannerInputPreview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>

                    <div class="relative">
                        <textarea
                            id="aboutText"
                            rows="3"
                            name="ABOUT"
                            placeholder="Jelaskan tujuan forum ini..."
                            class="w-full px-4 py-3 pr-14 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"></textarea>

                        <span
                            id="aboutCounter"
                            class="absolute right-3 bottom-3 text-xs pt-3 text-gray-400 pointer-events-none">
                            0/150
                        </span>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 mt-4">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" id="updateBtn" class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
        <div id="globalAlert" class="hidden fixed top-4 left-1/2 -translate-x-1/2 
            bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-[999999]">
        </div>
    </div>

    <div id="deleteModalOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[9999] hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm transform scale-95 transition-all duration-300 text-center">

            <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Forum?</h3>
            <p class="text-gray-500 text-sm mb-6">
                Apakah Anda yakin ingin menghapus <strong><?= htmlspecialchars($forumById['NAME']) ?></strong>? Tindakan ini permanen.
            </p>

            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-5 py-2.5 border border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50">
                    Batal
                </button>
                <button onclick="confirmDeleteForum('<?= $forumById['ID'] ?>')" id="btnConfirmDelete" class="px-5 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 shadow-md">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>


    <script>
        const editModalOverlay = document.getElementById('editModalOverlay');
        const editModalContainer = editModalOverlay ? editModalOverlay.querySelector('div') : null;

        const editNameInput = document.getElementById('editGroupNameInput');
        const editNamePreview = document.getElementById('editGroupNamePreview');
        const editProfileInput = document.getElementById('editProfileInput');
        const editProfilePreview = document.getElementById('editProfilePreview');
        const editProfileInputPreview = document.getElementById('editProfileInputPreview');

        const editBannerInput = document.getElementById('editBannerInput');
        const editBannerImage = document.getElementById('editBannerImage');
        const editDefaultBannerText = document.getElementById('editDefaultBannerText');
        const editBannerInputPreview = document.getElementById('editBannerInputPreview');

        const editPrivacyInputs = document.querySelectorAll('input[name="IS_PRIVATE"]');
        const editAccessKeyContainer = document.getElementById('editAccessKeyContainer');
        const editPrivacyText = document.getElementById('editPrivacyText');
        const editPrivacyIcon = document.getElementById('editPrivacyIcon');

        const deleteModal = document.getElementById('deleteModalOverlay');
        const deleteModalContent = deleteModal ? deleteModal.querySelector('div') : null;
        const btnConfirmDelete = document.getElementById('btnConfirmDelete');
        const aboutText = document.getElementById('aboutText');
        const counter = document.getElementById('nameCounter');

        const MAX_LENGTH = 15;
        const ABOUT_MAX = 150;

        aboutText.addEventListener('input', function() {
            if (this.value.length > ABOUT_MAX) {
                this.value = this.value.slice(0, ABOUT_MAX);
            }
            aboutCounter.textContent = `${this.value.length}/${ABOUT_MAX}`;
        });

        editNameInput.addEventListener('input', function() {
            if (this.value.length > MAX_LENGTH) {
                this.value = this.value.slice(0, MAX_LENGTH);
            }
            counter.textContent = `${this.value.length}/${MAX_LENGTH}`;
        });

        function showGlobalAlert(message, refresh = true) {
            const alertBox = document.getElementById('globalAlert');
            alertBox.textContent = message;
            alertBox.classList.remove('hidden', 'opacity-0');

            setTimeout(() => {
                alertBox.classList.add('opacity-0');
                setTimeout(() => {
                    alertBox.classList.add('hidden');
                    if (refresh) location.reload();
                }, 300);
            }, 1500);
        }

        function openDeleteModal() {
            deleteModal.classList.remove('hidden');
            setTimeout(() => {
                deleteModal.classList.remove('opacity-0');
                deleteModalContent.classList.remove('scale-95');
            }, 10);
        }

        function closeDeleteModal() {
            deleteModal.classList.add('opacity-0');
            deleteModalContent.classList.add('scale-95');
            setTimeout(() => {
                deleteModal.classList.add('hidden');
            }, 300);
        }

        async function confirmDeleteForum(forumId) {
            btnConfirmDelete.disabled = true;
            btnConfirmDelete.innerHTML = "Menghapus...";

            const formData = new FormData();
            formData.append('ID', forumId);

            try {
                const response = await fetch('<?= BASEURL ?>/forum/delete', {
                    method: 'POST',
                    body: formData
                });

                const text = await response.text();
                try {
                    var result = JSON.parse(text);
                } catch (e) {
                    throw new Error("Respon server error: " + text);
                }

                if (result.success) {
                    window.location.href = '<?= BASEURL ?>/forums';
                } else {
                    throw new Error(result.message);
                }

            } catch (error) {
                console.error(error);
                alert("Gagal menghapus: " + error.message);

                btnConfirmDelete.disabled = false;
                btnConfirmDelete.innerHTML = "Ya, Hapus";
                closeDeleteModal();
            }
        }

        function openEditModal() {
            editModalOverlay.classList.remove('hidden');
            setTimeout(() => {
                editModalOverlay.classList.remove('opacity-0');
                editModalContainer.classList.remove('scale-95');
            }, 10);
        }

        function closeEditModal() {
            editModalOverlay.classList.add('opacity-0');
            editModalContainer.classList.add('scale-95');
            setTimeout(() => {
                editModalOverlay.classList.add('hidden');
            }, 300);
        }

        if (editNameInput) {
            editNameInput.addEventListener('input', function() {
                editNamePreview.textContent = this.value || "Tanpa Nama";
            });
        }

        if (editPrivacyInputs) {
            editPrivacyInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value === '1') {
                        editAccessKeyContainer.classList.remove('hidden');
                        editPrivacyText.textContent = "Private";
                        editPrivacyIcon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>';
                        editPrivacyIcon.className = "text-gray-600";
                    } else {
                        editAccessKeyContainer.classList.add('hidden');
                        editPrivacyText.textContent = "Public";
                        editPrivacyIcon.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
                        editPrivacyIcon.className = "text-blue-600";
                    }
                });
            });
        }

        function setupImagePreview(input, mainPreview, thumbPreview, isBanner = false) {
            if (!input) return;
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        mainPreview.src = evt.target.result;
                        mainPreview.classList.remove('hidden');

                        if (isBanner) editDefaultBannerText.classList.add('hidden');

                        thumbPreview.src = evt.target.result;
                        thumbPreview.classList.remove('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        setupImagePreview(editProfileInput, editProfilePreview, editProfileInputPreview);
        setupImagePreview(editBannerInput, editBannerImage, editBannerInputPreview, true);

        const updateForm = document.getElementById('updateForumForm');
        const updateBtn = document.getElementById('updateBtn');
        const editAlertBox = document.getElementById('editAlertBox');

        if (updateForm) {
            updateForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                updateBtn.disabled = true;
                updateBtn.innerHTML = "Menyimpan...";
                editAlertBox.classList.add('hidden');

                const formData = new FormData(this);

                try {
                    const response = await fetch('<?= BASEURL ?>/forum/update', {
                        method: 'POST',
                        body: formData
                    });

                    const text = await response.text();
                    try {
                        var result = JSON.parse(text);
                    } catch (err) {
                        throw new Error("Respon server error: " + text);
                    }

                    if (result.success) {
                        editAlertBox.className = "bg-green-100 text-green-700 fixed right-5 top-5 mb-4 p-4 rounded-lg text-sm hidden";
                        editAlertBox.textContent = result.message;
                        editAlertBox.classList.remove('hidden');

                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        throw new Error(result.message);
                    }

                } catch (error) {
                    editAlertBox.className = "bg-red-100 text-red-700 fixed right-5 top-5 mb-4 p-4 rounded-lg text-sm hidden";
                    editAlertBox.textContent = error.message;
                    editAlertBox.classList.remove('hidden');
                    updateBtn.disabled = false;
                    updateBtn.innerHTML = "Simpan Perubahan";
                }
            });
        }
    </script>