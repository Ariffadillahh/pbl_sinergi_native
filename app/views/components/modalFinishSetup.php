<?php
$showModal = (isset($_SESSION['role']) && $_SESSION['role'] === 'MAHASISWA') &&
    (empty($_SESSION['tahun_masuk']) || empty($_SESSION['jenjang_studi']) || empty($_SESSION['prodi']));
?>

<div id="finish-setup-modal"
    class="<?php echo $showModal ? 'fixed inset-0 z-[99999] flex justify-center items-center w-full h-full bg-black/50' : 'hidden'; ?>">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Finish Your Profile Setup
                </h3>
                <button type="button" id="close-setup-modal"
                    class="text-gray-400 rounded-lg text-sm w-8 h-8 flex justify-center items-center hover:bg-gray-100 transition-colors cursor-pointer"
                    aria-label="Close modal">
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

                <p id="edit-profile-succses"
                    class="bg-green-600 p-2 text-white text-center rounded-lg hidden mb-4"></p>

                <form id="setup-form" action="<?php echo BASEURL ?>/user-setup" method="POST" class="my-5">
                    <div class="my-6 max-w-md mx-auto">
                        <div class="relative mb-5">
                            <select id="jenjang-studi" name="jenjang_studi"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer cursor-pointer"
                                required>
                                <option value="" disabled selected>-- Choose --</option>
                                <option value="D3">D3 - Diploma 3</option>
                                <option value="D4">D4 - Sarjana Terapan</option>
                            </select>
                            <label for="jenjang-studi"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">Study Level</label>
                        </div>

                        <div class="relative mb-5">
                            <select id="prodi" name="prodi"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer cursor-pointer"
                                required>
                                <option value="" disabled selected>--- Study Program ---</option>
                                <option value="TI">TI</option>
                                <option value="TMD">TMD</option>
                                <option value="TMJ">TMJ</option>
                            </select>

                            <label for="prodi"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">
                                Study Program
                            </label>
                        </div>

                        <div class="relative mb-5">
                            <input type="text" id="tahunMasuk" name="tahunMasuk"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " required pattern="\d{4}" title="Please enter a 4-digit year" maxlength="4"/>
                            <label for="tahunMasuk"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:text-blue-600">Batch/Entry Year</label>
                        </div>

                        <button type="submit" name="finish_setup" id="setup-submit-button"
                            class=" w-full px-6 py-3.5 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors cursor-pointer flex items-center justify-center">
                            Save and Continue
                        </button>
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
        const successBox = document.getElementById('edit-profile-succses');
        const originalButtonText = 'Save and Continue';

        // Add visual feedback to input for Tahun Masuk (Year of Entry)
        const tahunMasukInput = document.getElementById('tahunMasuk');
        if (tahunMasukInput) {
            tahunMasukInput.addEventListener('input', function() {
                // Remove non-digit characters
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);
            });
        }
        
        if (closeSetupModalButton) {
            closeSetupModalButton.addEventListener('click', () => {
                setupModal.classList.add('hidden');
            });
        }

        setupForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Client-side validation for Tahun Masuk (basic check)
            if (tahunMasukInput.value.length !== 4 || isNaN(parseInt(tahunMasukInput.value))) {
                errorMessage.textContent = 'Tahun Masuk must be a valid 4-digit year.';
                errorMessage.classList.remove('hidden');
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            `;
            errorMessage.classList.add('hidden');
            successBox.classList.add('hidden');

            const formData = new FormData(setupForm);
            const actionUrl = setupForm.getAttribute("action");

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                });
                
                // Check if response is JSON first
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                     throw new Error('Server did not return a valid JSON response.');
                }
                
                const result = await response.json();

                if (result.status === 'success') {
                    successBox.classList.remove('hidden');
                    successBox.innerHTML = result.message || "Data berhasil diperbarui.";
                    
                    // Clear inputs after success for clean state, though a full reload handles this
                    setupForm.reset(); 
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    errorMessage.textContent = result.message || 'An unknown error occurred.';
                    errorMessage.classList.remove('hidden');
                }
            } catch (error) {
                console.error("Fetch Error:", error);
                errorMessage.textContent = error.message || 'Cannot connect to server. Please check your connection.';
                errorMessage.classList.remove('hidden');
            } finally {
                if (!successBox.classList.contains('hidden')) {
                    // If success, wait for reload
                } else {
                    submitButton.disabled = false;
                    submitButton.textContent = originalButtonText;
                    submitButton.innerHTML = originalButtonText; // Reset to text only
                }
            }
        });
    });
</script>