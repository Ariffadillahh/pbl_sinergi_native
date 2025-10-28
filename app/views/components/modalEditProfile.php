<div id="edit-profile-modal"
    class="hidden inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50 fixed top-0">
    <div class="relative p-4 w-full max-w-xl max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Edit Profile
                </h3>
                <button type="button" id="edit-profile-close"
                    class="text-gray-400 rounded-lg text-sm w-8 h-8 flex justify-center items-center cursor-pointer">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <div class="px-4 pb-4">
                <p id="edit-profile-error"
                    class="bg-red-600 p-2 text-white text-center rounded-lg hidden mb-4"></p>

                <form id="edit-profile-form" action="<?php echo BASEURL ?>/profile/update" method="POST" class="my-5">

                    <div class="my-6 max-w-3xl mx-auto">
                        <section class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-4">
                            <div class="flex-shrink-0 size-[100px] rounded-full overflow-hidden border-2 border-gray-200">
                                <img id="edit-forum-photo-preview"
                                    src="<?php echo !empty($_SESSION['path_photo'])
                                                ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo']
                                                : BASEURL . '/src/asset/image/default.png'; ?>"
                                    alt="Forum photo"
                                    class="object-cover w-full h-full" />
                            </div>
                            <input type="file" id="edit-forum-file-input" name="profileFoto" class="hidden" accept="image/*" />
                            <button
                                type="button"
                                id="btn-edit-forum-change-photo"
                                class="flex items-center gap-2 px-6 py-3.5 rounded-full bg-gray-900 text-white font-bold hover:bg-gray-700 transition-colors">
                                <img src="<?php echo BASEURL; ?>/src/asset/icons/edit-2-white-fill.svg" alt="Edit icon" class="size-6" />
                                <span>Change Photo</span>
                            </button>
                        </section>

                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <div class="relative">
                                <input type="text" id="edit-profile-username" name="username"
                                    value="<?= htmlspecialchars($_SESSION['username'] ?? '') ?>"
                                    class="cursor-not-allowed block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " disabled required />
                                <label for="edit-profile-username"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                                    Username
                                </label>
                            </div>

                            <div class="relative">
                                <input type="email" id="edit-profile-email" name="email"
                                    value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>"
                                    class="cursor-not-allowed block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " disabled required />
                                <label for="edit-profile-email"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                                    Email
                                </label>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="relative">
                                <input type="text" id="edit-profile-fullname" name="full_name"
                                    value="<?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="edit-profile-fullname"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                                    Full Name
                                </label>
                            </div>
                            <div class="relative mb-5">
                                <input type="text" id="edit-profile-number" name="personal_number"
                                    value="<?= htmlspecialchars($_SESSION['personal_number'] ?? '') ?>"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="edit-profile-number"
                                    class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                                    <?php
                                    if (isset($_SESSION['role'])) {
                                        if ($_SESSION['role'] === 'MAHASISWA') {
                                            echo 'NIM';
                                        } elseif ($_SESSION['role'] === 'DOSEN') {
                                            echo 'NIP';
                                        } else {
                                            echo 'Personal Number';
                                        }
                                    } else {
                                        echo 'Personal Number';
                                    }
                                    ?>
                                </label>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mb-5">
                            <div class="relative">
                                <input type="text" id="edit-profile-prodi" name="prodi"
                                    value="<?= htmlspecialchars($_SESSION['prodi'] ?? '') ?>"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="edit-profile-prodi"
                                    class="absolute text-xs md:text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                                    Program Studi
                                </label>
                            </div>

                            <div class="relative">
                                <select id="edit-profile-jenjang" name="jenjang_studi"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="D3" <?= (($_SESSION['jenjang_studi'] ?? '') === 'D3') ? 'selected' : '' ?>>D3</option>
                                    <option value="D4" <?= (($_SESSION['jenjang_studi'] ?? '') === 'D4') ? 'selected' : '' ?>>D4</option>
                                </select>
                                <label for="edit-profile-jenjang"
                                    class="absolute text-xs md:text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                                    Jenjang Studi
                                </label>
                            </div>

                            <div class="relative">
                                <input type="text" id="edit-profile-tahun" name="tahun_masuk"
                                    value="<?= htmlspecialchars($_SESSION['tahun_masuk'] ?? '') ?>"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required />
                                <label for="edit-profile-tahun"
                                    class="absolute text-xs md:text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                                    Tahun Masuk
                                </label>
                            </div>
                        </div>
                        <button type="submit" id="edit-profile-submit"
                            class="w-full px-6 py-3.5 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('edit-profile-modal');
        const btnOpen = document.getElementById('openModalEditProfile');
        const btnClose = document.getElementById('edit-profile-close');
        const form = document.getElementById('edit-profile-form');
        const btnSubmit = document.getElementById('edit-profile-submit');
        const errorBox = document.getElementById('edit-profile-error');

        const fileInput = document.getElementById("edit-forum-file-input");
        const changePhotoBtn = document.getElementById("btn-edit-forum-change-photo");
        const photoPreview = document.getElementById("edit-forum-photo-preview");

        changePhotoBtn.addEventListener("click", function() {
            fileInput.click();
        });

        fileInput.addEventListener("change", function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        if (btnClose) {
            btnClose.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        }

        btnOpen.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Saving...';
            errorBox.classList.add('hidden');

            const formData = new FormData(form);
            const actionUrl = form.getAttribute("action");

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    modal.classList.add('hidden');
                    window.location.reload(); 
                } else {
                    errorBox.textContent = result.message || 'An unknown error occurred.';
                    errorBox.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Fetch Error:', error); 
                errorBox.textContent = 'Cannot connect to server. Please check your connection.';
                errorBox.classList.remove('hidden');
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Save Changes';
            }
        });
    });
</script>