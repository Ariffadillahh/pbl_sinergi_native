<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Sign Up | Sinergi</title>
</head>

<body>
    <div class="flex min-h-screen bg-[#EBEDF2]">
        <?php include_once "app/views/components/communityCard.php" ?>

        <main class="flex flex-1 min-h-screen items-center justify-center p-4 bg-white z-10">


            <div class="w-full max-w-[435px] relative">
                <div id="error-notification" class="fixed top-5 left-1/2 z-50 -translate-x-1/2 w-11/12 max-w-md bg-red-500 text-white p-4 rounded-lg shadow-lg flex items-center justify-center space-x-3 hidden">

                </div>

                <form id="registerForm" action="<?php echo BASEURL ?>/sign-up/action" method="POST" class="flex flex-col gap-10">
                    <div class="flex flex-col gap-8">
                        <header class="flex flex-col gap-3 text-center">
                            <h1 class="font-semibold text-2xl md:text-3xl text-gray-900">Hey, Welcome Aboard!</h1>
                            <p class="font-medium text-gray-500">Create your account to continue!</p>
                        </header>

                        <section class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <div class="flex-shrink-0 size-[100px] rounded-full overflow-hidden border-2 border-gray-200">
                                <img id="photo-container" src="src/asset/image/default.png" alt="User avatar" class="object-cover w-full h-full" />
                            </div>
                            <input type="file" id="file-input" name="photo" class="hidden" accept="image/*" />
                            <button type="button" id="add-photo" class="flex items-center gap-2 px-6 py-3.5 rounded-full bg-gray-900 text-white font-bold hover:bg-gray-700 transition-colors cursor-pointer">
                                <img src="src/asset/icons/edit-2-white-fill.svg" alt="Edit icon" class="size-6" />
                                <span>Change Avatar</span>
                            </button>
                        </section>

                        <section class="flex flex-col gap-6">
                            <div class="relative">
                                <div class="group relative">
                                    <input type="text" id="FullName" name="FullName" class="w-full h-[72px] pl-[80px] pr-6 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all" placeholder=" " required />
                                    <label for="FullName" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">Full Name</label>
                                    <img src="<?php echo BASEURL; ?>/src/asset/icons/user-square-grey.svg" alt="User icon" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                    <div class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300"></div>
                                </div>
                            </div>

                            <div class="md:flex gap-3">
                                <div class="relative w-full  mb-6 md:mb-0">
                                    <div class="group relative">
                                        <input type="text" id="username" name="username" class="w-full h-[72px] pl-[80px] pr-6 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all" placeholder=" " required />
                                        <label for="username" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">Username</label>
                                        <img src="<?php echo BASEURL; ?>/src/asset/image/@.png" alt="User icon" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                        <div class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300"></div>
                                    </div>
                                </div>

                                <div class="relative w-full">
                                    <div class="group relative">
                                        <input type="text" id="personal_number" name="personal_number" class="w-full h-[72px] pl-[80px] pr-6 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all" placeholder=" " oninput="this.value=this.value.replace(/[^0-9]/g,'')" required />
                                        <label for="personal_number" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">NIM/NIP</label>
                                        <img src="<?php echo BASEURL; ?>/src/asset/image/icons8-id-verified-96.png" alt="User icon" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                        <div class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="group relative">
                                    <input type="email" id="Email" name="email" class="w-full h-[72px] pl-[80px] pr-10 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all" placeholder=" " required />
                                    <label for="Email" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">Email address</label>
                                    <img src="<?php echo BASEURL; ?>/src/asset/icons/sms-grey.svg" alt="Email icon" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                    <div class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300"></div>
                                </div>
                            </div>

                            <div class="relative w-full max-w-md">
                                <div class="group relative">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        minlength="6"
                                        class="w-full h-[72px] pl-[80px] pr-14 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all"
                                        placeholder=" "
                                        maxlength="6"
                                        required />

                                    <label for="password" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">Password</label>

                                    <img src="src/asset/icons/lock-grey.svg" alt="" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                    <div class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300"></div>

                                    <button type="button" id="show-password" class="absolute right-6 top-1/2 -translate-y-1/2 cursor-pointer focus:outline-none">
                                        <svg id="show-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-500">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <svg id="hide-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-500 hidden">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="mt-3 px-2 hidden" id="strengthPassword">
                                    <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div id="strength-bar" class="h-full w-0 transition-all duration-300 ease-out bg-red-500"></div>
                                    </div>
                                    <p id="strength-text" class="text-xs text-gray-500 mt-1.5 font-medium text-right">
                                        Minimum 6 characters
                                    </p>
                                </div>
                            </div>
                        </section>
                    </div>

                    <section class="flex flex-col gap-6">
                        <button type="submit" id="registerBtn" class="w-full bg-blue-800 text-white font-bold py-4 rounded-full hover:bg-blue-700 transition-all cursor-pointer">Create Account</button>
                        <p class="font-semibold text-center">Already Have Account? <a href="<?php echo BASEURL; ?>/sign-in" class="text-blue-500 hover:underline cursor-pointer">Sign in Now</a></p>
                    </section>
                </form>
            </div>
        </main>
    </div>

    <?php include_once "app/views/components/modalOtp.php" ?>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>

    <script>
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        const passwordInput = document.getElementById('password');
        const strengthPassword = document.getElementById('strengthPassword');

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

                if (len == 6) {
                    strengthBar.style.width = "20%";
                    strengthBar.classList.add("bg-red-500");

                    strengthText.innerText = `MAX-MIN 6`;
                    strengthText.className =
                        "text-xs text-red-500 mt-1.5 font-medium text-right";

                    return;
                }

                if (len < 6) {
                    strengthBar.style.width = "20%";
                    strengthBar.classList.add("bg-red-500");

                    strengthText.innerText = `Too short (needs ${6 - len} more characters)`;
                    strengthText.className =
                        "text-xs text-red-500 mt-1.5 font-medium text-right";

                    return;
                }

                let score = 0;
                let missing = [];

                if (/[a-z]/.test(val)) score++;
                else missing.push("lowercase letter");

                if (/[A-Z]/.test(val)) score++;
                else missing.push("uppercase letter");

                if (/[0-9]/.test(val)) score++;
                else missing.push("number");

                if (/[^a-zA-Z0-9]/.test(val)) score++;
                else missing.push("symbol");

                if (len > 8) score++;

                let saran = missing.length > 0 ? missing[0] : "";

                if (missing.length > 1 && score > 2) {
                    saran = missing.slice(0, 2).join(" or ");
                }

                if (score <= 2) {
                    strengthBar.style.width = "30%";
                    strengthBar.classList.add("bg-red-500");

                    strengthText.innerText =
                        `Weak: Try adding ${saran || "another combination"}`;
                    strengthText.className =
                        "text-xs text-red-500 mt-1.5 font-medium text-right";

                } else if (score <= 4) {
                    strengthBar.style.width = "70%";
                    strengthBar.classList.add("bg-yellow-500");

                    strengthText.innerText = `Medium: Add ${saran} to make it stronger`;
                    strengthText.className =
                        "text-xs text-yellow-600 mt-1.5 font-medium text-right";

                } else {
                    strengthBar.style.width = "100%";
                    strengthBar.classList.add("bg-green-500");

                    strengthText.innerText = "Very Strong & Secure!";
                    strengthText.className = "text-xs text-green-600 mt-1.5 font-bold text-right";
                }
            });
        }

        // Fungsi untuk menangani registrasi
        function handleRegist() {
            const formRegist = document.getElementById("registerForm");
            const modalOtp = document.getElementById("modal-otp");
            const errorNotif = document.getElementById("error-notification");

            if (!formRegist) return;

            const submitButton = document.getElementById("registerBtn");
            const originalButtonText = submitButton.textContent;

            formRegist.addEventListener("submit", async (e) => {
                e.preventDefault();

                submitButton.innerHTML = `
                    <svg class="inline w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                `;
                submitButton.disabled = true;
                submitButton.classList.add("cursor-not-allowed")
                errorNotif.classList.add("hidden");

                const formData = new FormData(formRegist);
                const actionUrl = formRegist.getAttribute("action");

                try {
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
                        document.body.classList.add("overflow-hidden");

                        const userEmail = document.getElementById("Email").value;
                        const emailDisplayElement = document.getElementById("otp-email-display");

                        if (emailDisplayElement) {
                            emailDisplayElement.textContent = maskEmail(userEmail);
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
                        errorNotif.classList.remove('hidden');

                        setTimeout(() => {
                            errorNotif.classList.add('hidden');
                        }, 2000);
                    }
                } catch (error) {
                    console.error("Fetch Error:", error);
                    errorNotif.textContent = "Could not connect to the server. Check your connection.";
                    errorNotif.classList.remove("hidden");

                    setTimeout(() => {
                        errorNotif.classList.add('hidden');
                    }, 2000);
                } finally {
                    submitButton.innerHTML = originalButtonText;
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

        document.addEventListener('DOMContentLoaded', function() {
            handleRegist();
            maskEmail()
        });
    </script>
</body>

</html>