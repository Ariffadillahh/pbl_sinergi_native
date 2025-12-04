<div id="add-member-modal"
    class="w-full h-full fixed bg-black/50 top-0 left-0 z-[99999] justify-center items-center hidden">
    <div class="relative p-4 w-full max-w-xl max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Add New Member
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

                <form action="<?= BASEURL ?>/admin/create-account" method="POST" class="my-5" id="add-member-form">
                    <div class="my-6 max-w-md mx-auto space-y-4">
                        <div>
                            <label for="nama-lengkap" class="block mb-2 text-sm font-medium text-gray-900">Full Name</label>
                            <input type="text" id="nama-lengkap" name="nama-lengkap"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                required>
                        </div>

                        <input type="hidden" name="status" value="APPROVED">

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
                                    <option value="" disabled selected>-- Select --</option>
                                    <option value="ALUMNI">ALUMNI</option>
                                    <option value="MITRA">PARTNER</option>
                                    <option value="ADMIN">ADMIN</option>
                                </select>
                            </div>
                        </div>


                        <div class="flex justify-end pt-4 gap-3">
                            <button type="button" id="cancel-modal-btn"
                                class="px-6 py-2 rounded-full bg-[#D9D9D9] text-[#6B7280] font-bold transition-colors">
                                Cancel
                            </button>
                            <button type="submit" id="submit-form-btn"
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

        const modal = document.getElementById("add-member-modal");
        const openModalBtn = document.getElementById("open-modal-btn");
        const closeModalBtn = document.getElementById("close-modal-btn");
        const cancelModalBtn = document.getElementById("cancel-modal-btn");

        const passwordInput = document.getElementById("password");
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


        addMemberForm.addEventListener("submit", async (event) => {
            event.preventDefault(); 

            errorDisplay.classList.add("hidden");
            succsesDisplay.classList.add("hidden");

            submitButton.disabled = true;
            submitButton.textContent = "Saving...";

            const actionUrl = addMemberForm.getAttribute("action");

            try {
                const formData = new FormData(addMemberForm);
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    succsesDisplay.textContent = result.message || "Member created successfully.";
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
                errorDisplay.textContent = "Unable to connect to server. Please try again.";
                errorDisplay.classList.remove("hidden");
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = "Save";
            }
        });
    });
</script>