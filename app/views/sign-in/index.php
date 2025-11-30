<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Sign In | SINERGI</title>
</head>

<body>
    <div class="flex min-h-screen bg-[#EBEDF2]">
        <?php include_once "app/views/components/communityCard.php" ?>

        <main class="flex flex-1 items-center justify-center px-4 bg-white/50 md:bg-white z-10 lg:rounded-l-4xl ">
            <div class="w-full max-w-[435px] relative mt-6 md:mt-0">
                <?php
                if (isset($_SESSION['login_error'])):
                ?>
                    <section id="error-notification" class="bg-red-500 text-center py-3 rounded-xl my-3">

                        <p class="font-medium text-white"><?= htmlspecialchars($_SESSION['login_error']); ?></p>

                    </section>

                <?php

                    unset($_SESSION['login_error']);
                endif;
                ?>


                <section class="flex flex-col gap-10 bg-white md:bg-transparent md:p-0 md:drop-shadow-none p-5 rounded-xl drop-shadow-2xl">
                    <form class="flex flex-col gap-10" action="<?php echo BASEURL ?>/sign-in/action" method="POST">
                        <div class="flex flex-col gap-8">
                            <header class="flex flex-col gap-3 text-center">
                                <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="w-12 h-10 shrink-0 mx-auto" alt="logo">
                                <h1 class="font-light text-2xl">SINERGI</h1>
                                <p class="font-medium text-gray-500">Hop into your account to continue!</p>
                            </header>

                            <div class="flex flex-col gap-6">
                                <div class="relative">
                                    <div class="group relative">
                                        <input type="text" id="username_or_email" name="username_or_email" class="w-full h-[72px] pl-[80px] pr-6 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all" placeholder=" " required />
                                        <label for="username_or_email" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">Email Or Username</label>
                                        <img src="src/asset/icons/sms-grey.svg" alt="Email icon" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
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
                                <div>
                                    <div class="flex gap-3 items-center mb-3">
                                        <img id="captcha-img" src="<?php echo BASEURL; ?>/captcha.php" alt="captcha">
                                        <button type="button" id="refresh-captcha" style="cursor:pointer;" class="p-1 rounded-md text-gray-600 hover:bg-gray-100 hover:text-gray-900">
                                            <img src="src/asset/image/refresh.png" alt="Email icon" class="size-6" />
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <div class="group relative">
                                            <input type="text" id="captcha" name="captcha" class="w-full h-[72px] pl-[80px] pr-6 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all" placeholder=" " required />
                                            <label for="captcha" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">Captcha</label>
                                            <img src="src/asset/image/captcha.png" alt="Email icon" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                            <div class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300"></div>
                                        </div>
                                    </div>
                                    <a href="<?php echo BASEURL; ?>/forget-password" class="block text-end mt-3 font-medium text-gray-500 hover:text-blue-500 hover:underline">Forget My password</a>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6">
                            <button
                                type="submit"
                                id="login-button"
                                class="w-full bg-blue-800 text-white font-bold py-4 rounded-full hover:bg-blue-700 transition-all flex justify-center items-center gap-2 cursor-pointer">
                                Sign In Now 
                            </button>

                            <p class="font-medium text-center text-gray-700">
                                Don’t have an account?
                                <a href="<?php echo BASEURL; ?>/sign-up" class="text-blue-600 font-semibold hover:text-blue-700 hover:underline transition">
                                    Sign Up
                                </a>
                            </p>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>

    <script>
        const form = document.querySelector('form');
        const loginButton = document.getElementById('login-button');

        form.addEventListener('submit', () => {
            loginButton.innerHTML = `
                    <svg class="inline w-5 h-5 animate-spin mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                `;

            loginButton.disabled = true;
            loginButton.classList.add('opacity-70', 'cursor-not-allowed');
        });

        const refreshButton = document.getElementById('refresh-captcha');
        const captchaImage = document.getElementById('captcha-img');

        refreshButton.addEventListener('click', function() {
            fetch('<?php echo BASEURL; ?>/refresh-captcha')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        captchaImage.src = '<?php echo BASEURL; ?>/captcha.php?' + new Date().getTime();
                    }
                })
                .catch(error => {
                    console.error('Error refreshing captcha:', error);
                });
        });
    </script>

</body>

</html>