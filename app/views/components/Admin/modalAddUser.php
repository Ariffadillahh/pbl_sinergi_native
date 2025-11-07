<div id="add-member-modal"
    class="w-full h-full fixed bg-black/50 top-0 left-0 z-[99999] justify-center items-center hidden">
    <div class="relative p-4 w-full max-w-xl max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Anggota Baru
                </h3>
                <button type="button" id="close-modal-btn"
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
                <p id="add-user-error" class="bg-red-600 p-2 text-white text-center rounded-lg hidden mb-4"></p>
                <p id="add-user-succses" class="bg-green-600 p-2 text-white text-center rounded-lg hidden mb-4"></p>

                <form action="<?= BASEURL ?>/admin/create-accout" method="POST" class="my-5" id="add-member-form">
                    <div class="my-6 max-w-md mx-auto space-y-4">
                        <div>
                            <label for="nama-lengkap" class="block mb-2 text-sm font-medium text-gray-900">Nama
                                Lengkap</label>
                            <input type="text" id="nama-lengkap" name="nama-lengkap"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-full">
                                <label for="email"
                                    class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                <input type="email" id="email" name="email"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required>
                            </div>
                            <div class="w-full">
                                <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                                <input type="text" id="username" name="username"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-full">
                                <label for="personal-number"
                                    class="block mb-2 text-sm font-medium text-gray-900">Personal Number</label>
                                <input type="text" id="personal-number" name="personal-number"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required>
                            </div>
                            <div class="w-full">
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                                <select id="role" name="role"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="ALUMNI">ALUMNI</option>
                                    <option value="MITRA">MITRA INDUSTRI</option>
                                </select>
                            </div>

                        </div>

                        <div class="w-full relative">
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                            <input type="password" id="password" name="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-32"
                                required>

                            <div class="absolute end-2.5 bottom-1 flex items-center gap-1">
                                <button type="button" id="toggle-password-btn"
                                    class="text-gray-500 hover:text-gray-700 focus:outline-none p-1.5">
                                    <svg id="icon-show" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <svg id="icon-hide" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                        class="w-5 h-5 hidden">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.243 4.243L15 12m-3-3L6.228 6.228" />
                                    </svg>
                                </button>
                                <button type="button" id="generate-password-btn"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1.5">
                                    Generate
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 gap-3">
                            <button type="button" id="cancel-modal-btn"
                                class="px-6 py-2 rounded-full bg-[#D9D9D9] text-[#6B7280] font-bold transition-colors">
                                Batal
                            </button>
                            <button type="submit" id="submit-form-btn"
                                class="px-6 py-2 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const modal = document.getElementById("add-member-modal");
        const openModalBtn = document.getElementById("open-modal-btn");
        const closeModalBtn = document.getElementById("close-modal-btn");
        const cancelModalBtn = document.getElementById("cancel-modal-btn");

        const passwordInput = document.getElementById("password");
        const generatePassBtn = document.getElementById("generate-password-btn");
        const togglePasswordBtn = document.getElementById("toggle-password-btn");
        const iconShow = document.getElementById("icon-show");
        const iconHide = document.getElementById("icon-hide");

        const addMemberForm = document.getElementById("add-member-form");
        const errorDisplay = document.getElementById("add-user-error");
        const succsesDisplay = document.getElementById("add-user-succses");
        const submitButton = document.getElementById("submit-form-btn");


        const openModal = () => {
            modal.classList.remove("hidden");
            modal.classList.add('flex')
        };

        const closeModal = () => {
            modal.classList.add("hidden");
            modal.classList.remove('flex')
            addMemberForm.reset();
            errorDisplay.classList.add("hidden");
        };

        openModalBtn.addEventListener("click", openModal);

        closeModalBtn.addEventListener("click", closeModal);

        cancelModalBtn.addEventListener("click", closeModal);

        modal.addEventListener("click", (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });


        generatePassBtn.addEventListener("click", () => {
            const length = 8;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*_";
            let newPassword = "";

            for (let i = 0, n = charset.length; i < length; ++i) {
                newPassword += charset.charAt(Math.floor(Math.random() * n));
            }

            passwordInput.value = newPassword;

            passwordInput.type = "text";
            iconShow.classList.add("hidden");
            iconHide.classList.remove("hidden");
        });

        togglePasswordBtn.addEventListener("click", () => {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                iconShow.classList.add("hidden");
                iconHide.classList.remove("hidden");
            } else {
                passwordInput.type = "password";
                iconShow.classList.remove("hidden");
                iconHide.classList.add("hidden");
            }
        });

        addMemberForm.addEventListener("submit", async (event) => {
            event.preventDefault(); 

            errorDisplay.classList.add("hidden");
            succsesDisplay.classList.add("hidden");

            submitButton.disabled = true;
            submitButton.textContent = "Menyimpan...";

            const actionUrl = addMemberForm.getAttribute("action");

            try {
                const formData = new FormData(addMemberForm);
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    succsesDisplay.textContent = result.message || "Berhasil membuat anggota.";
                    succsesDisplay.classList.remove("hidden");

                    setTimeout(() => {
                        location.reload();
                    }, 1500);

                } else {
                    errorDisplay.textContent = result.message;
                    errorDisplay.classList.remove("hidden");
                }

            } catch (error) {
                console.error(error);
                errorDisplay.textContent = "Tidak dapat terhubung ke server. Coba lagi.";
                errorDisplay.classList.remove("hidden");
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = "Simpan";
            }
        });
    });
</script>