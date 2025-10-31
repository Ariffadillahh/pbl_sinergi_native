<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Forget Password | SINERGI</title>
</head>

<body>
    <div class="flex min-h-screen bg-[#EBEDF2]">
        <?php include_once "app/views/components/communityCard.php" ?>

        <main class="flex flex-1 items-center justify-center px-4 bg-white/50 md:bg-white z-10 lg:rounded-l-4xl ">
            <div class="w-full max-w-[435px] relative mt-6 md:mt-0">

                <div id="error-notification" class="absolute bottom-[calc(100%+-10px)] md:bottom-[calc(100%+15px)] w-full text-center bg-red-500/90 py-3 px-6 rounded-2xl shadow-lg hidden z-[999] text-white">

                </div>

                <section class="flex flex-col gap-10 bg-white md:bg-transparent md:p-0 md:drop-shadow-none p-5 rounded-xl drop-shadow-2xl">
                    <form id="resetForm" class="flex flex-col gap-10" method="POST">
                        <div class="flex flex-col gap-8">
                            <header class="flex flex-col gap-3 text-center">
                                <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="w-12 h-10 shrink-0 mx-auto" alt="logo">
                                <h1 class="font-light text-2xl">SINERGI</h1>
                                <p class="font-medium text-gray-500">Change Your Password Now</p>
                            </header>

                            <div class="flex flex-col gap-6">
                                <div class="relative">
                                    <div class="group relative">
                                        <input type="text" id="username_or_email" name="username_or_email"
                                            class="w-full h-[72px] pl-[80px] pr-6 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all"
                                            placeholder=" " required />
                                        <label for="username_or_email"
                                            class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">
                                            Email Or Username
                                        </label>
                                        <img src="src/asset/icons/sms-grey.svg" alt="Email icon"
                                            class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                        <div
                                            class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300">
                                        </div>
                                    </div>
                                </div>

                                <div class="relative">
                                    <div class="group relative">
                                        <input type="password" id="password" name="password"
                                            class="w-full h-[72px] pl-[80px] pr-14 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all"
                                            placeholder=" " required />
                                        <label for="password"
                                            class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">
                                            Password
                                        </label>
                                        <img src="src/asset/icons/lock-grey.svg" alt="Password icon"
                                            class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                        <div
                                            class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300">
                                        </div>
                                        <button type="button" id="togglePassword"
                                            class="absolute right-6 top-1/2 -translate-y-1/2 cursor-pointer">
                                            <img src="src/asset/icons/eye-grey.svg" alt="Show password"
                                                id="showIconPassword" class="size-6" />
                                            <img src="src/asset/icons/eye-slash-black.svg" alt="Hide password"
                                                id="hideIconPassword" class="size-6 hidden" />
                                        </button>
                                    </div>
                                </div>

                                <div class="relative">
                                    <div class="group relative">
                                        <input type="password" id="confirmPassword" name="confirm_password"
                                            class="w-full h-[72px] pl-[80px] pr-14 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all"
                                            placeholder=" " required />
                                        <label for="confirmPassword"
                                            class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">
                                            Confirm Password
                                        </label>
                                        <img src="src/asset/icons/lock-grey.svg" alt="Password icon"
                                            class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                        <div
                                            class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300">
                                        </div>
                                        <button type="button" id="toggleConfirm"
                                            class="absolute right-6 top-1/2 -translate-y-1/2 cursor-pointer">
                                            <img src="src/asset/icons/eye-grey.svg" alt="Show password"
                                                id="showIconConfirm" class="size-6" />
                                            <img src="src/asset/icons/eye-slash-black.svg" alt="Hide password"
                                                id="hideIconConfirm" class="size-6 hidden" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6">
                            <button type="submit" id="btnForget"
                                class="w-full bg-blue-800 text-white font-bold py-4 rounded-full hover:bg-blue-700 transition-all">
                                Submit
                            </button>
                            <p class="font-semibold text-center">Already Have Account? <a href="<?php echo BASEURL; ?>/sign-in" class="text-blue-500 hover:underline">Sign in Now</a></p>

                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>

    <?php include_once "app/views/components/modalOtpFogetPassword.php" ?>

    <script>
        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("password");
        const showIconPassword = document.getElementById("showIconPassword");
        const hideIconPassword = document.getElementById("hideIconPassword");

        if (togglePassword) {
            togglePassword.addEventListener("click", () => {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    showIconPassword.classList.add("hidden");
                    hideIconPassword.classList.remove("hidden");
                } else {
                    passwordInput.type = "password";
                    showIconPassword.classList.remove("hidden");
                    hideIconPassword.classList.add("hidden");
                }
            });
        }

        const toggleConfirm = document.getElementById("toggleConfirm");
        const confirmPasswordInput = document.getElementById("confirmPassword");
        const showIconConfirm = document.getElementById("showIconConfirm");
        const hideIconConfirm = document.getElementById("hideIconConfirm");

        if (toggleConfirm) {
            toggleConfirm.addEventListener("click", () => {
                if (confirmPasswordInput.type === "password") {
                    confirmPasswordInput.type = "text";
                    showIconConfirm.classList.add("hidden");
                    hideIconConfirm.classList.remove("hidden");
                } else {
                    confirmPasswordInput.type = "password";
                    showIconConfirm.classList.remove("hidden");
                    hideIconConfirm.classList.add("hidden");
                }
            });
        }


        function handleForget() {
            const form = document.getElementById("resetForm");
            const modalOtp = document.getElementById("modal-otp");
            const submitButton = document.getElementById("btnForget");
            const errorNotif = document.getElementById("error-notification");

            if (!form) return;

            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                errorNotif.classList.add("hidden");

                if (passwordInput.value !== confirmPasswordInput.value) {
                    errorNotif.textContent = "Password dan konfirmasi password tidak sama.";
                    errorNotif.classList.remove("hidden");
                    return;
                }

                submitButton.innerHTML = `
                    <svg class="inline w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                `;

                submitButton.disabled = true;

                try {
                    const formData = new FormData(form);
                    const actionUrl = "<?php echo BASEURL; ?>/forget-password/action";

                    const response = await fetch(actionUrl, {
                        method: "POST",
                        body: formData,
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();

                    if (result.success) {
                        modalOtp.classList.remove("hidden");
                        modalOtp.classList.add("flex");
                        const userEmail = document.getElementById("username_or_email").value;
                        const emailDisplayElement = document.getElementById("otp-email-display");
                        if (emailDisplayElement) {
                            emailDisplayElement.textContent = userEmail;
                        }

                        if (typeof startCooldown === 'function') {
                            startCooldown();
                        }
                    } else {
                        errorNotif.textContent = result.message || "Terjadi kesalahan.";
                        errorNotif.classList.remove("hidden");
                    }
                } catch (error) {
                    console.error("Fetch Error:", error);
                    errorNotif.textContent = "Tidak dapat terhubung ke server. Periksa koneksi Anda.";
                    errorNotif.classList.remove("hidden");
                } finally {
                    submitButton.textContent = "Submit";
                    submitButton.disabled = false;
                }
            });
        }

        function setupOtpModal() {
            const otpModal = document.getElementById("modal-otp");
            if (!otpModal) return;

            const otpForm = document.getElementById("otp-form");
            const otpInputs = otpModal.querySelectorAll('.otp-input');
            const hiddenOtpInput = document.getElementById('otp-full-value');
            const otpMessageDiv = document.getElementById("otpMessage");
            const verifyBtn = document.getElementById("verifyOtpBtn");

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
                    const pasteData = e.clipboardData.getData('text').replace(/[^0-9]/g, '');
                    for (let i = 0; i < otpInputs.length && i < pasteData.length; i++) {
                        otpInputs[i].value = pasteData[i];
                    }
                    const lastFilledIndex = Math.min(pasteData.length, otpInputs.length);
                    if (lastFilledIndex < otpInputs.length) {
                        otpInputs[lastFilledIndex].focus();
                    } else {
                        otpInputs[otpInputs.length - 1].focus();
                    }
                });
            });

            resendBtn.addEventListener('click', async () => {
                otpMessageDiv.textContent = 'Sending a new code...';
                otpMessageDiv.className = 'w-full bg-blue-500 text-white p-2 rounded-xl mb-3';
                otpMessageDiv.classList.remove('hidden');
                resendBtn.disabled = true

                try {
                    const response = await fetch('<?php echo BASEURL; ?>/forget-password/resend-otp', {
                        method: 'POST'
                    });
                    const result = await response.json();

                    if (result.success) {
                        startCooldown();
                        otpMessageDiv.textContent = result.message;
                    } else {
                        otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                        otpMessageDiv.textContent = result.message;
                    }
                } catch (error) {
                    otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                    otpMessageDiv.textContent = 'Failed to connect to the server.';
                }
            });

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
                    return;
                }

                const formData = new FormData(otpForm);
                const actionUrl = otpForm.getAttribute('action');

                verifyBtn.innerHTML = `
                    <svg class="inline w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-2">Verifying...</span>
                `;

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
                    console.error("OTP Verify Error:", error);
                    otpMessageDiv.textContent = 'Koneksi ke server gagal.';
                    otpMessageDiv.classList.remove('hidden');
                    otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                } finally {
                    verifyBtn.textContent = "Verify email";
                    verifyBtn.disabled = false;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            handleForget();
            setupOtpModal();
            startCooldown();
        });
    </script>

</body>

</html>