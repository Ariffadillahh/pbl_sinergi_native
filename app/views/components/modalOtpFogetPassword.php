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

        <form id="otp-form" action="<?php echo BASEURL ?>/forget-password/verif-otp" method="POST">
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
    let cooldownInterval = null;

    function startCooldown() {

        if (cooldownInterval) {
            clearInterval(cooldownInterval);
        }

        let cooldown = 60;
        resendBtn.disabled = true;
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
</script>