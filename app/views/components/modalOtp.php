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

            <button type="submit" id="verifyOtpBtn" class="w-full py-4 rounded-xl bg-blue-600 text-white font-bold text-lg hover:bg-blue-700 transition-colors">Verify email</button>
        </form>

        <p class="text-sm text-gray-500 mt-8">
            Didn't get a code?
            <a href="#" class="font-semibold text-blue-600 hover:underline">Resend</a>
        </p>
    </div>
</div>

<script>
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
                console.log(`Input ${index}:`, input.value);

                // Update OTP setiap kali ada input
                console.log("OTP sekarang:", getOtpValue());

                if (input.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                    console.log(`Fokus pindah ke input ${index + 1}`);
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                    console.log(`Backspace di input ${index}, pindah ke ${index - 1}`);
                    otpInputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = e.clipboardData.getData('text');
                const digits = pasteData.replace(/[^0-9]/g, '');
                console.log("Data di-paste:", digits);

                for (let i = 0; i < otpInputs.length; i++) {
                    if (i < digits.length) {
                        otpInputs[i].value = digits[i];
                        console.log(`Input ${i} terisi:`, digits[i]);
                    }
                }

                console.log("OTP setelah paste:", getOtpValue());

                const lastFilledIndex = Math.min(digits.length, otpInputs.length - 1);
                if (lastFilledIndex >= 0) {
                    otpInputs[lastFilledIndex].focus();
                    if (lastFilledIndex === otpInputs.length - 1) {
                        verifyBtn.focus();
                    }
                }
            });
        });

        otpForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const otp = getOtpValue();
            if (otp.length < otpInputs.length) {
                otpMessageDiv.classList.remove("hidden");
                otpMessageDiv.textContent = 'Harap isi semua 4 digit OTP.';
                return;
            }

            hiddenOtpInput.value = otp;
            console.log("OTP yang dikirim:", hiddenOtpInput.value); 

            const formData = new FormData(otpForm);
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }

            verifyBtn.disabled = true;
            verifyBtn.textContent = 'Memverifikasi...';

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
                }
            } catch (error) {
                otpMessageDiv.classList.remove("hidden");
                otpMessageDiv.textContent = error.message;
            } finally {
                verifyBtn.disabled = false;
                verifyBtn.textContent = 'Verifikasi';
            }
        });

    }

    document.addEventListener('DOMContentLoaded', function() {
        handleRegist();
        setupOtpModal();
    });
</script>