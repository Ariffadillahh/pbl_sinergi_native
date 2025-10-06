<?php
$tahunMasuk = $_SESSION['tahun_masuk'] ?? null;
$jenjangStudi = $_SESSION['jenjang_studi'] ?? null;
// $prodi = $_SESSION['prodi'] ?? null;

$showModal = empty($tahunMasuk) || empty($jenjangStudi)
?>

<div id="finish-setup-modal"
    class="<?php echo $showModal ? 'fixed inset-0 z-[9999] flex justify-center items-center w-full h-full bg-black/50' : 'hidden'; ?>">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Finish Your Profile Setup
                </h3>
                <button type="button" id="close-setup-modal"
                    class="text-gray-400 rounded-lg text-sm w-8 h-8 flex justify-center items-center cursor-pointer">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <div class="p-4">
                <p id="modal-error-message"
                    class="bg-red-600 p-2 text-white text-center rounded-lg hidden mb-4"></p>

                <form id="setup-form" method="post" class="my-5">
                    <div class="my-6 max-w-md mx-auto">

                        <div class="relative mb-5">
                            <select id="jenjang-studi" name="jenjang_studi"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                required>
                                <option value="" disabled selected>-- Pilih --</option>
                                <option value="D3">D3 - Diploma 3</option>
                                <option value="D4">D4 - Sarjana Terapan</option>
                            </select>
                            <label for="jenjang-studi"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">Jenjang
                                Studi</label>
                        </div>

                        <div class="relative mb-5">
                            <input type="text" id="prodi" name="prodi"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " required />
                            <label for="prodi"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">Program
                                Studi</label>
                        </div>

                        <div class="relative mb-5">
                            <input type="text" id="tahunMasuk" name="tahunMasuk"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " required />
                            <label for="tahunMasuk"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">Tahun
                                Masuk</label>
                        </div>

                        <button type="submit" name="finish_setup" id="setup-submit-button"
                            class="mt-8 w-full px-6 py-3.5 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors">Save
                            and Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const setupModal = document.getElementById('finish-setup-modal');
        const closeSetupModalButton = document.getElementById('close-setup-modal');
        const setupForm = document.getElementById('setup-form');
        const submitButton = document.getElementById('setup-submit-button');
        const errorMessage = document.getElementById('modal-error-message');

        if (closeSetupModalButton) {
            closeSetupModalButton.addEventListener('click', () => {
                setupModal.classList.add('hidden');
            });
        }

        setupForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';
            errorMessage.classList.add('hidden');

            const formData = new FormData(setupForm);
            try {
                const response = await fetch('/users/finishSetup', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    setupModal.classList.add('hidden');
                    window.location.reload();
                } else {
                    errorMessage.textContent = result.message || 'An unknown error occurred.';
                    errorMessage.classList.remove('hidden');
                }
            } catch (error) {
                errorMessage.textContent = 'Cannot connect to server. Please check your connection.';
                errorMessage.classList.remove('hidden');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = 'Save and Continue';
            }
        });
    });
</script>