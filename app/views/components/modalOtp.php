<div id="modal-otp" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50 p-5 md:p-0">
    <div class="relative bg-white rounded-2xl shadow-lg p-8 w-full max-w-sm text-center">
        <div id="otpMessage" class="w-full bg-red-500 text-white p-2 rounded-xl mb-3 hidden">
        </div>

        <div class="mx-auto flex items-center justify-center h-16 w-16 bg-blue-100 rounded-full mb-6">
            <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Check your email</h1>
        <p class="text-sm text-gray-500 mb-8">
            Enter the verification code sent to <br> <span class="font-medium text-gray-700" id="otp-email-display"></span>
        </p>

        <form id="otp-form" action="<?php echo BASEURL ?>/sign-up/verif-otp" method="POST">
            <div id="otp-container" class="flex justify-center gap-3 mb-8">
                <input type="text" pattern="\d*" maxlength="1" class="otp-input h-16 w-14 text-center text-3xl font-bold text-gray-800 bg-white border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 transition" required>
                <input type="text" pattern="\d*" maxlength="1" class="otp-input h-16 w-14 text-center text-3xl font-bold text-gray-800 bg-white border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 transition" required>
                <input type="text" pattern="\d*" maxlength="1" class="otp-input h-16 w-14 text-center text-3xl font-bold text-gray-800 bg-white border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 transition" required>
                <input type="text" pattern="\d*" maxlength="1" class="otp-input h-16 w-14 text-center text-3xl font-bold text-gray-800 bg-white border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-0 transition" required>
            </div>

            <input type="hidden" name="otp" id="otp-full-value">

            <button type="submit" id="verifyOtpBtn" class="w-full py-4 rounded-xl bg-blue-600 text-white font-bold text-lg hover:bg-blue-700 transition-colors cursor-pointer">Verify email</button>
        </form>

        <p class="text-sm text-gray-500 mt-8">
            Didn't get a code?
            <button type="button" id="resend-otp-btn" class="font-semibold text-blue-600 hover:underline disabled:text-gray-400 disabled:no-underline cursor-pointer" disabled>
                Resend
            </button>
            <span id="resend-timer" class="text-gray-500 font-medium"></span>
        </p>
    </div>
</div>

<script>
    const resendBtn = document.getElementById("resend-otp-btn");
    const timerSpan = document.getElementById("resend-timer");
    let cooldownInterval = null; 

    function startCooldown() {

        if (cooldownInterval) {
            clearInterval(cooldownInterval);
        }

        let cooldown = 60;
        resendBtn.disabled = true;
        resendBtn.textContent = 'Resend'; 
        timerSpan.textContent = `in ${cooldown}s`;

        cooldownInterval = setInterval(() => {
            cooldown--;
            timerSpan.textContent = `in ${cooldown}s`;

            if (cooldown <= 0) {
                clearInterval(cooldownInterval);
                cooldownInterval = null;
                timerSpan.textContent = "";
                resendBtn.disabled = false;
            }
        }, 1000);
    }

    function setupOtpModal() {
        const otpModal = document.getElementById("modal-otp");
        if (!otpModal) return;

        const otpForm = document.getElementById("otp-form");
        const otpInputs = document.querySelectorAll('.otp-input');
        const hiddenOtpInput = document.getElementById('otp-full-value');
        const otpMessageDiv = document.getElementById("otpMessage");
        const verifyBtn = document.getElementById("verifyOtpBtn");

        function getOtpValue() {
            let otp = "";
            otpInputs.forEach(input => otp += input.value);
            return otp;
        }

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/[^0-9]/g, '');

                if (input.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text');
                const digits = pasteData.replace(/[^0-9]/g, '');

                for (let i = 0; i < otpInputs.length; i++) {
                    if (i < digits.length) {
                        otpInputs[i].value = digits[i];
                    }
                }

                const lastFilledIndex = Math.min(digits.length, otpInputs.length - 1);
                if (lastFilledIndex >= 0) {
                    otpInputs[lastFilledIndex].focus();
                }
            });
        });

        resendBtn.addEventListener('click', async () => {
            otpMessageDiv.textContent = 'Sending a new code...';
            otpMessageDiv.className = 'w-full bg-blue-500 text-white p-2 rounded-xl mb-3';
            otpMessageDiv.classList.remove('hidden');
            resendBtn.disabled = true;
            resendBtn.textContent = 'Sending...';

            try {
                const response = await fetch('<?php echo BASEURL; ?>/sign-up/resend-otp', {
                    method: 'POST'
                });
                const result = await response.json();

                if (result.success) {
                    otpMessageDiv.textContent = result.message;
                    otpMessageDiv.className = 'w-full bg-green-500 text-white p-2 rounded-xl mb-3';
                    startCooldown(); 
                } else {
                    otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                    otpMessageDiv.textContent = result.message;
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Resend';
                }
            } catch (error) {
                otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                otpMessageDiv.textContent = 'Failed to connect to the server.';
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend';
            }
        });

        otpForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const otp = getOtpValue();
            if (otp.length < otpInputs.length) {
                otpMessageDiv.classList.remove("hidden");
                otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                otpMessageDiv.textContent = 'Please fill in all 4 OTP digits.';
                return;
            }

            hiddenOtpInput.value = otp;

            verifyBtn.disabled = true;
            verifyBtn.innerHTML = `
                <svg class="inline w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-2">Verifying...</span>
            `;

            const formData = new FormData(otpForm);
            const actionUrl = otpForm.getAttribute("action");

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    window.location.href = '<?php echo BASEURL ?>/sign-in';
                } else {
                    otpMessageDiv.classList.remove("hidden");
                    otpMessageDiv.textContent = result.message;
                    otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                }
            } catch (error) {
                otpMessageDiv.classList.remove("hidden");
                otpMessageDiv.textContent = 'Failed to connect to the server.';
                otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
            } finally {
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = 'Verify email';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        setupOtpModal();
    });
</script>