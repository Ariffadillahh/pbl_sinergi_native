<div id="edit-member-modal"
    class="w-full h-full fixed bg-black/50 top-0 left-0 z-[99999] justify-center items-center hidden">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Edit Members
                </h3>
                <button type="button" id="edit-modal-close-btn"
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
                <p id="edit-user-error" class="bg-red-600 p-2 text-white text-center rounded-lg hidden my-4"></p>
                <p id="edit-user-succses" class="bg-green-600 p-2 text-white text-center rounded-lg hidden my-4"></p>

                <form method="POST" action="<?= BASEURL ?>/admin/update-role" class="my-5" id="edit-member-form">

                    <input type="hidden" id="edit-user-id" name="user_id">

                    <div class="my-6 max-w-md mx-auto space-y-4">

                        <div>
                            <label for="edit-user-name" class="block mb-2 text-sm font-medium text-gray-500">Full Name</label>
                            <input type="text" id="edit-user-name" name="full_name"
                                class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed"
                                disabled>
                        </div>

                        <div>
                            <label for="edit-user-username" class="block mb-2 text-sm font-medium text-gray-500">Username</label>
                            <input type="text" id="edit-user-username" name="username"
                                class="bg-gray-200 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed"
                                disabled>
                        </div>

                        <div>
                            <label for="edit-user-role" class="block mb-2 text-sm font-medium text-gray-900">Role</label>
                            <select id="edit-user-role" name="role"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                                <option value="" disabled>-- New Role --</option>
                                <option value="ADMIN">ADMIN</option>
                                <option value="MAHASISWA">MAHASISWA</option>
                                <option value="DOSEN">DOSEN</option>
                                <option value="ALUMNI">ALUMNI</option>
                                <option value="MITRA">MITRA</option>
                            </select>
                        </div>

                        <div class="flex justify-end pt-4 gap-3">
                            <button type="button" id="edit-modal-cancel-btn"
                                class="px-6 py-2 rounded-full bg-[#D9D9D9] text-[#6B7280] font-bold transition-colors">
                                Cancle
                            </button>
                            <button type="submit" id="edit-modal-submit-btn"
                                class="px-6 py-2 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors">
                                Save
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
        const editModal = document.getElementById("edit-member-modal");
        const editModalCloseBtn = document.getElementById("edit-modal-close-btn");
        const editModalCancelBtn = document.getElementById("edit-modal-cancel-btn");
        const editForm = document.getElementById("edit-member-form");
        const editErrorDisplay = document.getElementById("edit-user-error");
        const succsesDisplay = document.getElementById("edit-user-succses");

        const editUserId = document.getElementById("edit-user-id");
        const editUserName = document.getElementById("edit-user-name");
        const editUserUsername = document.getElementById("edit-user-username");
        const editUserRole = document.getElementById("edit-user-role");

        const editButtons = document.querySelectorAll(".btn-edit-user");

        const openEditModal = () => {
            editModal.classList.remove("hidden");
            editModal.classList.add("flex");
        };

        const closeEditModal = () => {
            editModal.classList.add("hidden");
            editModal.classList.remove("flex");
            editErrorDisplay.classList.add("hidden");
            editForm.reset();
        };

        editButtons.forEach(button => {
            button.addEventListener("click", () => {
                const id = button.dataset.id;
                const name = button.dataset.name;
                const username = button.dataset.username;
                const role = button.dataset.role;

                editUserId.value = id;
                editUserName.value = name;
                editUserUsername.value = username;
                editUserRole.value = role;

                openEditModal();
            });
        });

        editModalCloseBtn.addEventListener("click", closeEditModal);
        editModalCancelBtn.addEventListener("click", closeEditModal);

        editModal.addEventListener("click", (event) => {
            if (event.target === editModal) {
                closeEditModal();
            }
        });

        editForm.addEventListener("submit", async (event) => {
            event.preventDefault();
            editErrorDisplay.classList.add("hidden");
            succsesDisplay.classList.add('hidden')

            const submitButton = document.getElementById("edit-modal-submit-btn");
            submitButton.disabled = true;
            submitButton.textContent = "Menyimpan...";

            const formData = new FormData(editForm);

            try {
                const response = await fetch(editForm.action, {
                    method: "POST",
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    succsesDisplay.textContent = result.message || "Berhasil Edit role anggota.";
                    succsesDisplay.classList.remove("hidden");

                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    editErrorDisplay.textContent = result.message || "Gagal memperbarui role.";
                    editErrorDisplay.classList.remove("hidden");
                }
            } catch (error) {
                console.error("Edit Submit Error:", error);
                editErrorDisplay.textContent = "Tidak dapat terhubung ke server.";
                editErrorDisplay.classList.remove("hidden");
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = "Simpan Perubahan";
            }
        });

    });
</script>