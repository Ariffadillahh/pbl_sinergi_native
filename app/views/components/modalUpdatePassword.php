<div id="update-password-modal"
    class="hidden inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50 fixed top-0">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Ubah Password
                </h3>
                <button type="button" id="update-password-close"
                    class="text-gray-400 rounded-lg text-sm w-8 h-8 flex justify-center items-center cursor-pointer">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <div class="px-4 pb-6">
                <p id="update-password-error"
                    class="bg-red-600 p-2 text-white text-center rounded-lg hidden mb-4"></p>
                <p id="update-password-success"
                    class="bg-green-600 p-2 text-white text-center rounded-lg hidden mb-4"></p>

                <form id="update-password-form" action="<?php echo BASEURL ?>/profile/updatePassword" method="POST"
                    class="my-5 space-y-4">

                    <div class="relative">
                        <input type="password" id="current-password" name="current_password"
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="current-password"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                            Password Lama
                        </label>
                    </div>

                    <div class="relative">
                        <input type="password" id="new-password" name="new_password"
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="new-password"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                            Password Baru
                        </label>
                    </div>

                    <div class="relative">
                        <input type="password" id="confirm-password" name="confirm_password"
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label for="confirm-password"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                            Konfirmasi Password
                        </label>
                    </div>

                    <button type="submit" id="update-password-submit"
                        class="w-full px-6 py-3.5 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("update-password-modal");
        const openBtn = document.getElementById("btn-open-update-password");
        const closeBtn = document.getElementById("update-password-close");
        const form = document.getElementById("update-password-form");
        const errorBox = document.getElementById("update-password-error");
        const successBox = document.getElementById("update-password-success");
        const submitBtn = document.getElementById("update-password-submit");

        const openModal = () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        };

        const closeModal = () => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
            form.reset();
            errorBox.classList.add("hidden");
            successBox.classList.add("hidden");
        };

        openBtn.addEventListener("click", openModal);
        closeBtn.addEventListener("click", closeModal);
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            errorBox.classList.add("hidden");
            successBox.classList.add("hidden");
            submitBtn.disabled = true;
            submitBtn.textContent = "Updating...";

            const newPass = document.getElementById("new-password").value;
            const confirmPass = document.getElementById("confirm-password").value;

            if (newPass !== confirmPass) {
                errorBox.textContent = "Password baru dan konfirmasi tidak cocok!";
                errorBox.classList.remove("hidden");
                submitBtn.disabled = false;
                submitBtn.textContent = "Update Password";
                return; 
            }

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: new FormData(form),
                });

                const result = await response.json();

                if (result.success) {
                    successBox.textContent = result.message || "Password berhasil diupdate!";
                    successBox.classList.remove("hidden");

                    setTimeout(() => {
                        closeModal();
                    }, 2000); 

                } else {
                    errorBox.textContent = result.message || "Terjadi kesalahan.";
                    errorBox.classList.remove("hidden");
                }
            } catch (error) {
                console.error("Error:", error);
                errorBox.textContent = "Tidak dapat terhubung ke server.";
                errorBox.classList.remove("hidden");
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = "Update Password";
            }
        });
    });
</script>