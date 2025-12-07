<div id="update-password-modal"
    class="hidden inset-0 z-[99999] justify-center items-center w-full h-full backdrop-blur-sm bg-black/50 fixed top-0">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Change Password
                </h3>
                <button type="button" id="update-password-close"
                    class="text-gray-400 rounded-lg text-sm w-8 h-8 flex justify-center items-center cursor-pointer">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <div class="px-4 pb-6">
                <p id="update-password-error"
                    class="bg-red-600 p-2 text-white text-center rounded-lg hidden mb-4"></p>
                <p id="update-password-success"
                    class="bg-green-600 p-2 text-white text-center rounded-lg hidden mb-4"></p>

                <form id="update-password-form" action="<?php echo BASEURL ?>/profile/updatePassword"
                    class="my-5 space-y-4">

                    <div class="relative mb-6">
                        <div class="relative">
                            <input type="password" id="current-password" name="current_password"
                                class="block px-2.5 pb-2.5 pt-4 w-full pr-10 text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " required />

                            <label for="current-password"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Current Password
                            </label>

                            <button type="button" id="toggleCurrent" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600 cursor-pointer focus:outline-none">
                                <svg id="iconShowCurrent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg id="iconHideCurrent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="relative mb-6">
                        <div class="relative">
                            <input type="password" id="new-password" name="new_password"
                                class="block px-2.5 pb-2.5 pt-4 w-full pr-10 text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " required />

                            <label for="new-password"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                New Password
                            </label>

                            <button type="button" id="toggleNewPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600 cursor-pointer focus:outline-none">
                                <svg id="iconShowNew" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg id="iconHideNew" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>

                        <div class="mt-2 px-1 hidden" id="strengthPassword">
                            <div class="w-full h-1 bg-gray-200 rounded-full overflow-hidden">
                                <div id="new-strength-bar" class="h-full w-0 transition-all duration-300 ease-out bg-red-500"></div>
                            </div>
                            <p id="new-strength-text" class="text-xs text-gray-500 mt-1 font-medium text-right">Minimum 6 characters</p>
                        </div>
                    </div>

                    <div class="relative mb-6">
                        <div class="relative">
                            <input type="password" id="confirm-password" name="confirm_password"
                                class="block px-2.5 pb-2.5 pt-4 w-full pr-10 text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " required />

                            <label for="confirm-password"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Confirm Password
                            </label>

                            <button type="button" id="toggleConfirm" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-blue-600 cursor-pointer focus:outline-none">
                                <svg id="iconShowConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg id="iconHideConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>

                        <p id="match-text" class="text-xs mt-1.5 font-medium text-right hidden"></p>
                    </div>

                    <button type="submit" id="update-password-submit"
                        class="w-full px-6 py-3.5 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors cursor-pointer">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("update-password-modal");
        const openBtn = document.getElementById("btn-open-update-password");
        const closeBtn = document.getElementById("update-password-close");
        const form = document.getElementById("update-password-form");
        const errorBox = document.getElementById("update-password-error");
        const successBox = document.getElementById("update-password-success");
        const submitBtn = document.getElementById("update-password-submit");

        const newPassInput = document.getElementById('new-password');
        const confirmPassInput = document.getElementById('confirm-password');

        const strengthBar = document.getElementById('new-strength-bar');
        const strengthText = document.getElementById('new-strength-text');

        const matchText = document.getElementById('match-text');

        const toggleNewBtn = document.getElementById('toggleNewPass');
        const iconShowNew = document.getElementById('iconShowNew');
        const iconHideNew = document.getElementById('iconHideNew');
        const strengthPassword = document.getElementById('strengthPassword');

        const toggleConfirmBtn = document.getElementById('toggleConfirm');
        const iconShowConfirm = document.getElementById('iconShowConfirm');
        const iconHideConfirm = document.getElementById('iconHideConfirm');

        const currentPassInput = document.getElementById('current-password');
        const toggleCurrentBtn = document.getElementById('toggleCurrent');
        const iconShowCurrent = document.getElementById('iconShowCurrent');
        const iconHideCurrent = document.getElementById('iconHideCurrent');

        if (typeof setupToggle === 'function') {
            setupToggle(toggleCurrentBtn, currentPassInput, iconShowCurrent, iconHideCurrent);
        } else {
            if (toggleCurrentBtn && currentPassInput) {
                toggleCurrentBtn.addEventListener('click', () => {
                    if (currentPassInput.type === 'password') {
                        currentPassInput.type = 'text';
                        iconShowCurrent.classList.add('hidden');
                        iconHideCurrent.classList.remove('hidden');
                    } else {
                        currentPassInput.type = 'password';
                        iconShowCurrent.classList.remove('hidden');
                        iconHideCurrent.classList.add('hidden');
                    }
                });
            }
        }

        function setupToggle(btn, input, iconShow, iconHide) {
            if (btn && input) {
                btn.addEventListener('click', () => {
                    if (input.type === 'password') {
                        input.type = 'text';
                        iconShow.classList.add('hidden');
                        iconHide.classList.remove('hidden');
                    } else {
                        input.type = 'password';
                        iconShow.classList.remove('hidden');
                        iconHide.classList.add('hidden');
                    }
                });
            }
        }

        if (newPassInput && strengthBar && strengthText) {
            newPassInput.addEventListener('input', () => {
                const val = newPassInput.value;
                const len = val.length;

                len === 0 ? strengthPassword.classList.add('hidden') : strengthPassword.classList.remove('hidden')

                strengthBar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');

                if (len === 0) {
                    strengthBar.style.width = '0%';
                    strengthText.innerText = 'Minimum 6 characters'; // Translated
                    strengthText.className = 'text-xs text-gray-500 mt-1 font-medium text-right';
                    if (matchText) matchText.classList.add('hidden');
                    return;
                }

                if (len < 6) {
                    strengthBar.style.width = '20%';
                    strengthBar.classList.add('bg-red-500');
                    strengthText.innerText = `Too short (needs ${6 - len} more)`; // Translated
                    strengthText.className = 'text-xs text-red-500 mt-1 font-medium text-right';
                    checkMatch();
                    return;
                }

                let score = 0;
                let missing = [];

                if (val.match(/[a-z]/)) score++;
                else missing.push("lowercase letter"); // Translated
                if (val.match(/[A-Z]/)) score++;
                else missing.push("uppercase letter"); // Translated
                if (val.match(/[0-9]/)) score++;
                else missing.push("number"); // Translated
                if (val.match(/[^a-zA-Z0-9]/)) score++;
                else missing.push("symbol"); // Translated
                if (len > 8) score++;

                let saran = missing.length > 0 ? missing[0] : '';
                if (missing.length > 1 && score > 2) {
                    saran = missing.slice(0, 2).join(' or '); // Translated
                }

                if (score <= 2) {
                    strengthBar.style.width = '40%';
                    strengthBar.classList.add('bg-red-500');
                    strengthText.innerText = `Weak: Add ${saran || 'combination'}`; // Translated
                    strengthText.className = 'text-xs text-red-500 mt-1 font-medium text-right';
                } else if (score <= 4) {
                    strengthBar.style.width = '70%';
                    strengthBar.classList.add('bg-yellow-500');
                    strengthText.innerText = `Medium: Add ${saran}`; // Translated
                    strengthText.className = 'text-xs text-yellow-600 mt-1 font-medium text-right';
                } else {
                    strengthBar.style.width = '100%';
                    strengthBar.classList.add('bg-green-500');
                    strengthText.innerText = 'Very Strong! 🔒'; // Translated
                    strengthText.className = 'text-xs text-green-600 mt-1 font-bold text-right';
                }

                checkMatch();
            });
        }

        function checkMatch() {
            if (!confirmPassInput || !newPassInput || !matchText) return;

            const valNew = newPassInput.value;
            const valConfirm = confirmPassInput.value;

            if (valConfirm.length === 0) {
                matchText.classList.add('hidden');
                confirmPassInput.classList.remove('border-red-500', 'border-green-500');
                confirmPassInput.classList.add('border-gray-300');
                return;
            }

            matchText.classList.remove('hidden');

            if (valNew === valConfirm) {
                matchText.innerText = "Passwords Match ✅"; // Translated
                matchText.className = "text-xs mt-1.5 font-medium text-right text-green-600";
                confirmPassInput.classList.remove('border-gray-300', 'border-red-500');
                confirmPassInput.classList.add('border-green-500');
            } else {
                matchText.innerText = "Passwords Don't Match ❌"; // Translated
                matchText.className = "text-xs mt-1.5 font-medium text-right text-red-500";
                confirmPassInput.classList.remove('border-gray-300', 'border-green-500');
                confirmPassInput.classList.add('border-red-500');
            }
        }

        setupToggle(toggleNewBtn, newPassInput, iconShowNew, iconHideNew);
        setupToggle(toggleConfirmBtn, confirmPassInput, iconShowConfirm, iconHideConfirm);

        if (confirmPassInput) {
            confirmPassInput.addEventListener('input', checkMatch);
        }

        const openModal = () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        };

        const closeModal = () => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
            form.reset();
            errorBox.classList.add("hidden");
            successBox.classList.add("hidden");
        };

        openBtn.addEventListener("click", openModal);
        closeBtn.addEventListener("click", closeModal);
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            errorBox.classList.add("hidden");
            successBox.classList.add("hidden");
            submitBtn.disabled = true;
            submitBtn.textContent = "Updating..."; // Translated
            submitBtn.classList.add("cursor-not-allowed"); // Added for consistency

            const newPass = document.getElementById("new-password").value;
            const confirmPass = document.getElementById("confirm-password").value;

            if (newPass !== confirmPass) {
                errorBox.textContent = "New password and confirmation do not match!"; // Translated
                errorBox.classList.remove("hidden");
                submitBtn.disabled = false;
                submitBtn.textContent = "Update Password"; // Translated
                submitBtn.classList.remove("cursor-not-allowed"); // Added for consistency
                return;
            }

            try {
                const response = await fetch(form.action, {
                    method: "POST",
                    body: new FormData(form),
                });

                const result = await response.json();

                if (result.success) {
                    successBox.textContent = result.message || "Password updated successfully!"; // Translated
                    successBox.classList.remove("hidden");

                    setTimeout(() => {
                        closeModal();
                    }, 1500);

                } else {
                    errorBox.textContent = result.message || "An error occurred."; // Translated
                    errorBox.classList.remove("hidden");
                }
            } catch (error) {
                console.error("Error:", error);
                errorBox.textContent = "Could not connect to the server."; // Translated
                errorBox.classList.remove("hidden");
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = "Update Password"; // Translated
                submitBtn.classList.remove("cursor-not-allowed"); // Added for consistency
            }
        });
    });
</script>