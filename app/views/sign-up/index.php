<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Sign Up | SINERGI</title>
</head>

<body>
    <div class="flex min-h-screen bg-[#EBEDF2]">
        <?php include_once "app/views/components/communityCard.php" ?>

        <main class="flex flex-1 min-h-screen items-center justify-center p-4 bg-white z-10">

            <div class="w-full max-w-[435px]">
                <form id="registerForm" action="<?php echo BASEURL ?>/sign-up/action" method="POST" class="flex flex-col gap-10">
                    <div class="flex flex-col gap-8">
                        <header class="flex flex-col gap-3 text-center">
                            <h1 class="font-semibold text-2xl md:text-3xl text-gray-900">Hey🙌🏻, Welcome Aboard!</h1>
                            <p class="font-medium text-gray-500">Create your account to continue!</p>
                        </header>

                        <section class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <div class="flex-shrink-0 size-[100px] rounded-full overflow-hidden border-2 border-gray-200">
                                <img id="photo-container" src="src/asset/image/default.png" alt="User avatar" class="object-cover w-full h-full" />
                            </div>
                            <input type="file" id="file-input" name="photo" class="hidden" accept="image/*" />
                            <button type="button" id="add-photo" class="flex items-center gap-2 px-6 py-3.5 rounded-full bg-gray-900 text-white font-bold hover:bg-gray-700 transition-colors">
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
                                        <label for="username" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">username</label>
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

                            <div class="relative">
                                <div class="group relative">
                                    <input type="password" id="password" name="password" class="w-full h-[72px] pl-[80px] pr-14 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all" placeholder=" " required />
                                    <label for="password" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">Password</label>
                                    <img src="src/asset/icons/lock-grey.svg" alt="Password icon" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                    <div class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300"></div>
                                    <button type="button" id="show-password" class="absolute right-6 top-1/2 -translate-y-1/2 cursor-pointer">
                                        <img src="src/asset/icons/eye-grey.svg" alt="Show password" id="show-icon" class="size-6" />
                                        <img src="src/asset/icons/eye-slash-black.svg" alt="Hide password" id="hide-icon" class="size-6 hidden" />
                                    </button>
                                </div>
                            </div>
                        </section>
                    </div>

                    <section class="flex flex-col gap-6">
                        <button type="submit" id="registerBtn" class="w-full bg-blue-800 text-white font-bold py-4 rounded-full hover:bg-blue-700 transition-all">Create Account</button>
                        <p class="font-semibold text-center">Already Have Account? <a href="<?php echo BASEURL; ?>/sign-in" class="text-blue-500 hover:underline">Sign in Now</a></p>
                    </section>
                </form>
            </div>
        </main>
    </div>

    <?php include_once "app/views/components/modalOtp.php" ?>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>

    <script>
        function handleRegist() {
            const formRegist = document.getElementById("registerForm");
            const modalOtp = document.getElementById("modal-otp");

            if (!formRegist) {
                return;
            }

            const submitButton = document.getElementById("registerBtn");

            formRegist.addEventListener("submit", async (e) => {
                e.preventDefault();

                submitButton.textContent = "Loading....";
                submitButton.disabled = true;

                const formData = new FormData(formRegist);
                const actionUrl = formRegist.getAttribute("action");

                console.log("--- Data dari Form Registrasi ---");
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value);
                }
                console.log("---------------------------------");

                try {
                    const response = await fetch(actionUrl, {
                        method: "POST",
                        body: formData,
                    });

                    const result = await response.json();
                    if (result.success) {
                        modalOtp.classList.remove("hidden");
                        modalOtp.classList.add("flex");
                        const userEmail = document.getElementById("Email").value;
                        const emailDisplayElement = document.getElementById("otp-email-display");
                        emailDisplayElement.textContent = userEmail;
                    }
                } catch (error) {
                    console.error("Fetch Error:", error);
                } finally {
                    submitButton.textContent = "Create Account";
                    submitButton.disabled = false;
                }
            });
        }
    </script>
</body>

</html>