<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Forgot Password | SINERGI</title>
</head>

<body>
    <div class="flex min-h-screen bg-[#EBEDF2]">
        <?php include_once "app/views/components/communityCard.php" ?>

        <main class="flex flex-1 items-center justify-center px-4 bg-white/50 md:bg-white z-10 lg:rounded-l-4xl ">
            <div class="w-full max-w-[435px] relative mt-6 md:mt-0">

                <div id="error-notification"
                    class="absolute bottom-[calc(100%+-10px)] md:bottom-[calc(100%+15px)] w-full text-center bg-red-500/90 py-3 px-6 rounded-2xl shadow-lg hidden z-[999] text-white">

                </div>

                <section
                    class="flex flex-col gap-10 bg-white md:bg-transparent md:p-0 md:drop-shadow-none p-5 rounded-xl drop-shadow-2xl">
                    <form id="resetForm" class="flex flex-col gap-10" method="POST">
                        <div class="flex flex-col gap-8">
                            <header class="flex flex-col gap-3 text-center">
                                <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg"
                                    class="w-12 h-10 shrink-0 mx-auto" alt="logo">
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
                                            New Password
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
                                <div class="px-2 -mt-3 hidden" id="strengthPassword">
                                    <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="strength-bar"
                                            class="h-full w-0 transition-all duration-300 ease-out bg-red-500"></div>
                                    </div>
                                    <p id="strength-text"
                                        class="text-xs text-gray-500 mt-1.5 font-medium text-right">
                                        Minimum 6 characters
                                    </p>
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
                                            class="absolute right-6 top-1/2 -translate-y-1/2 cursor-pointer focus:outline-none z-20">
                                            <img src="src/asset/icons/eye-grey.svg" alt="Show password"
                                                id="showIconConfirm" class="size-6" />
                                            <img src="src/asset/icons/eye-slash-black.svg" alt="Hide password"
                                                id="hideIconConfirm" class="size-6 hidden" />
                                        </button>
                                    </div>

                                    <p id="match-text" class="text-xs mt-1.5 font-medium text-right hidden"></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6">
                            <button type="submit" id="btnForget"
                                class="w-full bg-blue-800 text-white font-bold py-4 rounded-full hover:bg-blue-700 transition-all cursor-pointer">
                                Submit
                            </button>
                            <p class="font-semibold text-center">Already Have Account? <a
                                    href="<?php echo BASEURL; ?>/sign-in"
                                    class="text-blue-500 hover:underline cursor-pointer">Sign in Now</a></p>

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
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        const strengthPassword = document.getElementById('strengthPassword');
        const confirmInput = document.getElementById('confirmPassword');
        const matchText = document.getElementById('match-text');

        function checkMatch() {
            const passVal = passwordInput.value;
            const confirmVal = confirmInput.value;

            if (confirmVal.length === 0) {
                matchText.classList.add('hidden');
                confirmInput.classList.remove('border-red-500', 'border-green-500', 'focus:border-red-500', 'focus:border-green-500');
                confirmInput.classList.add('focus:border-blue-500');
                return;
            }

            matchText.classList.remove('hidden');

            if (passVal === confirmVal) {
                matchText.innerText = "Password Match! ✅";
                matchText.className = "text-xs mt-6 font-medium text-right text-green-600 mt";

                confirmInput.classList.remove('border-red-500', 'focus:border-blue-500', 'focus:border-red-500');
                confirmInput.classList.add('border-green-500', 'focus:border-green-500');
            } else {
                matchText.innerText = "Passwords Don't Match ❌";
                matchText.className = "text-xs mt-6 font-medium text-right text-red-500";

                confirmInput.classList.remove('border-green-500', 'focus:border-blue-500', 'focus:border-green-500');
                confirmInput.classList.add('border-red-500', 'focus:border-red-500');
            }
        }

        if (confirmInput && passwordInput) {
            confirmInput.addEventListener('input', checkMatch);

            passwordInput.addEventListener('input', () => {
                if (confirmInput.value.length > 0) {
                    checkMatch();
                }
            });
        }

        if (passwordInput && strengthBar && strengthText) {
            passwordInput.addEventListener('input', () => {
                const val = passwordInput.value;
                const len = val.length;
                strengthPassword.classList.remove('hidden')

                len === 0 ? strengthPassword.classList.add('hidden') : strengthPassword.classList.remove('hidden')

                strengthBar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');

                if (len === 0) {
                    strengthBar.style.width = '0%';
                    strengthText.innerText = 'Minimum 6 characters with combination';
                    strengthText.className = 'text-xs text-gray-500 mt-1.5 font-medium text-right';
                    return;
                }

                if (len < 6) {
                    strengthBar.style.width = '20%';
                    strengthBar.classList.add('bg-red-500');
                    strengthText.innerText = `Too short (needs ${6 - len} more)`;
                    strengthText.className = 'text-xs text-red-500 mt-1.5 font-medium text-right';
                    return;
                }

                let score = 0;
                let missing = [];

                if (val.match(/[a-z]/)) score++;
                else missing.push("lowercase letter");
                if (val.match(/[A-Z]/)) score++;
                else missing.push("uppercase letter");
                if (val.match(/[0-9]/)) score++;
                else missing.push("number");
                if (val.match(/[^a-zA-Z0-9]/)) score++;
                else missing.push("symbol");
                if (len > 8) score++;

                let saran = missing.length > 0 ? missing[0] : '';

                if (missing.length > 1 && score > 2) {
                    saran = missing.slice(0, 2).join(' or ');
                }

                if (score <= 2) {
                    strengthBar.style.width = '30%';
                    strengthBar.classList.add('bg-red-500');

                    strengthText.innerText = `Weak: Try adding ${saran || 'another combination'}`;
                    strengthText.className = 'text-xs text-red-500 mt-1.5 font-medium text-right';

                } else if (score <= 4) {
                    strengthBar.style.width = '70%';
                    strengthBar.classList.add('bg-yellow-500');

                    strengthText.innerText = `Medium: Add ${saran} to make it stronger`;
                    strengthText.className = 'text-xs text-yellow-600 mt-1.5 font-medium text-right';

                } else {
                    strengthBar.style.width = '100%';
                    strengthBar.classList.add('bg-green-500');

                    strengthText.innerText = 'Very Strong & Secure! 🔒';
                    strengthText.className = 'text-xs text-green-600 mt-1.5 font-bold text-right';
                }
            });
        }

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
                    errorNotif.textContent = "Password and confirmation password do not match.";
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
                submitButton.classList.add("cursor-not-allowed")
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

                        if (emailDisplayElement && result.email) {
                            emailDisplayElement.textContent = maskEmail(result.email);
                        }

                        if (typeof startCooldown === 'function') {
                            startCooldown();
                        }

                        setTimeout(() => {
                            const firstOtpInput = document.querySelector('.otp-input');
                            if (firstOtpInput) {
                                firstOtpInput.focus();
                            }
                        }, 100);
                    } else {
                        errorNotif.textContent = result.message || "An error occurred.";
                        errorNotif.classList.remove("hidden");
                    }
                } catch (error) {
                    console.error("Fetch Error:", error);
                    errorNotif.textContent = "Could not connect to the server. Check your connection.";
                    errorNotif.classList.remove("hidden");
                } finally {
                    submitButton.textContent = "Submit";
                    submitButton.disabled = false;
                    submitButton.classList.remove("cursor-not-allowed")
                }
            });
        }

        function maskEmail(email) {
            const [username, domain] = email.split("@");
            const maskedUsername = username.substring(0, 3) + "***";
            return maskedUsername + "@" + domain;
        }


        function setupOtpModal() {
            const otpModal = document.getElementById("modal-otp");
            if (!otpModal) return;

            const otpForm = document.getElementById("otp-form");
            const otpInputs = otpModal.querySelectorAll('.otp-input');
            const hiddenOtpInput = document.getElementById('otp-full-value');
            const otpMessageDiv = document.getElementById("otpMessage");
            const verifyBtn = document.getElementById("verifyOtpBtn");
            const resendBtn = document.getElementById("resendBtn"); // Assuming resendBtn exists in the included modal

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
                resendBtn.classList.add("cursor-not-allowed"); // Ensure resend button also respects cursor pointer rules

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
                        resendBtn.disabled = false
                        resendBtn.classList.remove("cursor-not-allowed");
                    }
                } catch (error) {
                    otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                    otpMessageDiv.textContent = 'Failed to connect to the server.';
                    resendBtn.disabled = false
                    resendBtn.classList.remove("cursor-not-allowed");
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
                    otpMessageDiv.textContent = "Please fill in all OTP fields.";
                    otpMessageDiv.classList.remove('hidden');
                    otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
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
                verifyBtn.classList.add("cursor-not-allowed");
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
                    otpMessageDiv.textContent = 'Connection to the server failed.';
                    otpMessageDiv.classList.remove('hidden');
                    otpMessageDiv.className = 'w-full bg-red-500 text-white p-2 rounded-xl mb-3';
                } finally {
                    verifyBtn.textContent = "Verify Email";
                    verifyBtn.disabled = false;
                    verifyBtn.classList.remove("cursor-not-allowed");
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            handleForget();
            setupOtpModal();
            // startCooldown(); // Disabled, as the function is not provided, but it's called after successful 'action'
        });
    </script>

</body>

</html>