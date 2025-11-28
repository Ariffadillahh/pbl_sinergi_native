<div id="modal-otp" class="hidden flex fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50 p-5 md:p-0">
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

        <form id="otp-form" action="<?php echo BASEURL ?>/account/confirm-student-status/otp" method="POST">
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
            <button type="button" id="resend-otp-btn" class="font-semibold text-blue-600 hover:underline disabled:text-gray-400 disabled:no-underline" disabled>
                Resend
            </button>
            <span id="resend-timer" class="text-gray-500 font-medium"></span>
        </p>
    </div>
</div>

<script>
    const resendBtn = document.getElementById("resend-otp-btn");
    const timerSpan = document.getElementById("resend-timer");
    const otpForm = document.getElementById("otp-form");

    const otpInputs = document.querySelectorAll('.otp-input');
    const hiddenOtpInput = document.getElementById('otp-full-value');
    const verifyBtn = document.getElementById('verifyOtpBtn');
    const otpMessageDiv = document.getElementById('otpMessage');

    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            if (e.target.value.length > 1) e.target.value = e.target.value.slice(0, 1);
            if (e.target.value.length === 1) {
                if (index < otpInputs.length - 1) otpInputs[index + 1].focus();
            }
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });

    let cooldownInterval = null;

    function startCooldown() {
        if (cooldownInterval) clearInterval(cooldownInterval);

        let cooldown = 60;
        if (resendBtn) resendBtn.disabled = true;
        if (timerSpan) timerSpan.textContent = `in ${cooldown}s`;

        cooldownInterval = setInterval(() => {
            cooldown--;
            if (timerSpan) timerSpan.textContent = `in ${cooldown}s`;

            if (cooldown <= 0) {
                clearInterval(cooldownInterval);
                cooldownInterval = null;
                if (timerSpan) timerSpan.textContent = "";
                if (resendBtn) resendBtn.disabled = false;
            }
        }, 1000);
    }

    if (resendBtn) {
        resendBtn.addEventListener('click', async (e) => {
            e.preventDefault();

            resendBtn.disabled = true;
            resendBtn.textContent = "Sending...";

            try {
                const response = await fetch('<?php echo BASEURL; ?>/account/confirm-student-status/resend', {
                    method: 'POST'
                });

                const result = await response.json();

                if (result.success) {
                    otpMessageDiv.textContent = result.message;
                    otpMessageDiv.classList.remove('hidden');
                    otpMessageDiv.className = 'w-full bg-green-500 text-white p-2 rounded-xl mb-3';

                    startCooldown();
                } else {
                    alert(result.message);
                    resendBtn.disabled = false;
                }

            } catch (error) {
                console.error("Resend Error:", error);
                alert("Gagal mengirim ulang kode. Periksa koneksi internet.");
                resendBtn.disabled = false;
            } finally {
                if (!resendBtn.disabled) resendBtn.textContent = "Resend";
                else resendBtn.textContent = "Resend";
            }
        });
    }

    if (otpForm) {
        otpForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            let otpValue = "";
            otpInputs.forEach(input => {
                otpValue += input.value;
            });
            hiddenOtpInput.value = otpValue;

            if (otpValue.length < 4) {
                otpMessageDiv.textContent = "Harap isi semua kolom OTP.";
                otpMessageDiv.classList.remove('hidden');
                otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                return;
            }

            const formData = new FormData(otpForm);
            const actionUrl = otpForm.getAttribute('action');
            const originalBtnText = verifyBtn.innerHTML;

            verifyBtn.innerHTML = `<span class="ml-2">Verifying...</span>`;
            verifyBtn.disabled = true;
            otpMessageDiv.classList.add("hidden");

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    window.location.href = '<?php echo BASEURL; ?>/sign-in';
                } else {
                    otpMessageDiv.textContent = result.message;
                    otpMessageDiv.classList.remove('hidden');
                    otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                }
            } catch (error) {
                otpMessageDiv.textContent = 'Koneksi ke server gagal.';
                otpMessageDiv.classList.remove('hidden');
                otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
            } finally {
                verifyBtn.innerHTML = originalBtnText || "Verify email";
                verifyBtn.disabled = false;
            }
        });
    }
</script>