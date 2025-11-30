<div id="studentConfirmModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-black/50 transition-opacity" aria-hidden="true" onclick="closeStudentModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[10000]">

            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirm Student Status
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                System detected that you are using a PNJ institutional email. Would you like to update your status back to Active Student?
                            </p>
                            <div class="mt-3 p-3 bg-gray-50 rounded-md border border-gray-200 text-left">
                                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Detected Email:</p>
                                <p class="text-sm font-mono text-gray-800 break-all">
                                    <?= htmlspecialchars($userEmail) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="confirmBtn" class="w-full sm:w-auto sm:ml-3 inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm cursor-pointer">
                    Confirm
                </button>

                <button type="button" onclick="closeStudentModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/modalOtpProfile.php'; ?>

<script>
    function openStudentModal() {
        document.getElementById('studentConfirmModal').classList.remove('hidden');
    }

    function closeStudentModal() {
        document.getElementById('studentConfirmModal').classList.add('hidden');
    }

    const confirmBtn = document.getElementById("confirmBtn");

    if (confirmBtn) {
        confirmBtn.addEventListener('click', async (e) => {
            e.preventDefault();

            const originalText = confirmBtn.innerText;

            confirmBtn.disabled = true;
            confirmBtn.innerHTML = 'Loading...';

            try {
                const response = await fetch('<?= BASEURL ?>/account/confirm-student-status', {
                    method: 'POST',
                });

                const result = await response.json();

                if (result.success) {
                    console.log(result.message)
                    closeStudentModal();

                    const modalOtp = document.getElementById("modal-otp");

                    if (modalOtp) {
                        modalOtp.classList.remove('hidden');

                    } else {
                        console.warn("Element with ID 'modalOtp' was not found on this page.");
                        alert("Request successful, please check your email for the OTP.");
                    }

                    if (typeof startCooldown === 'function') {
                        startCooldown();
                    }

                } else {
                    alert(result.message || "An error occurred while processing the request.");
                }

            } catch (error) {
                console.error("Error:", error);
                alert("Failed to connect to the server. Please try again.");
            } finally {
                confirmBtn.disabled = false;
                confirmBtn.innerText = originalText;
            }
        });
    }
</script>