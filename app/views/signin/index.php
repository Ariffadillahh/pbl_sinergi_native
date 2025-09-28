<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Sign In | SINERGI</title>
    <style>
        @keyframes slide-top {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-50%);
            }
        }

        @keyframes slide-bottom {
            from {
                transform: translateY(-50%);
            }

            to {
                transform: translateY(0);
            }
        }

        .slide-top {
            animation: slide-top 30s linear infinite;
        }

        .slide-bottom {
            animation: slide-bottom 30s linear infinite;
        }
    </style>
</head>

<body>
    <div class="flex min-h-screen bg-[#EBEDF2]">
        <section class="absolute lg:flex w-full max-w-[685px] md:relative ">
            <span class="fixed w-full max-w-[685px] top-0 left-0 h-[160px] bg-gradient-to-b from-[#EBEDF2] to-transparent z-10"></span>
            <span class="fixed w-full max-w-[685px] bottom-0 left-0 h-[160px] bg-gradient-to-t from-[#EBEDF2] to-transparent z-10"></span>

            <div class="fixed top-0 h-screen w-full max-w-[685px] overflow-hidden">
                <div class="flex justify-center gap-[10px]">
                    <div class="flex flex-col w-[380px] gap-[10px]">
                        <div class="w-full">
                            <div class="slide-top flex flex-col gap-[10px]">
                                <img src="src/asset/image/auth-1.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-2.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-3.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-1.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-2.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-3.png" alt="Auth image" class="rounded-[24px]" />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-[275px] gap-[10px] mt-[-100px]">
                        <div class="w-full">
                            <div class="slide-bottom flex flex-col gap-[10px]">
                                <img src="src/asset/image/auth-4.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-5.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-6.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-4.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-5.png" alt="Auth image" class="rounded-[24px]" />
                                <img src="src/asset/image/auth-6.png" alt="Auth image" class="rounded-[24px]" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <main class="flex flex-1 items-center justify-center px-4 bg-white/50 md:bg-white z-10 lg:rounded-l-4xl ">
            <div class="w-full max-w-[435px] relative mt-6 md:mt-0">

                <!-- <section id="error-notification" class="absolute bottom-[calc(100%+24px)] w-full text-center bg-red-500/90 py-3 px-6 rounded-2xl shadow-lg">
                    <p class="font-medium text-white">Oops, wrong Email or Password. Try again!</p>
                </section> -->

                <section class="flex flex-col gap-10 bg-white md:bg-transparent md:p-0 md:drop-shadow-none p-5 rounded-xl drop-shadow-2xl">
                    <form class="flex flex-col gap-10">
                        <div class="flex flex-col gap-8">
                            <header class="flex flex-col gap-3 text-center">
                                <h1 class="font-light text-2xl">SINERGI</h1>
                                <p class="font-medium text-gray-500">Hop into your account to continue!</p>
                            </header>

                            <div class="flex flex-col gap-6">
                                <div class="relative">
                                    <div class="group relative">
                                        <input type="email" id="email" class="w-full h-[72px] pl-[80px] pr-6 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all" placeholder=" " required />
                                        <label for="email" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">Email Address</label>
                                        <img src="src/asset/icons/sms-grey.svg" alt="Email icon" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                        <div class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300"></div>
                                    </div>
                                </div>
                                <div class="relative">
                                    <div class="group relative">
                                        <input type="password" id="password" class="w-full h-[72px] pl-[80px] pr-14 pt-6 pb-2 font-semibold text-gray-900 border-[1.5px] border-gray-300 rounded-[24px] focus:outline-none focus:border-blue-500 peer transition-all" placeholder=" " required />
                                        <label for="password" class="absolute left-[80px] top-1/2 -translate-y-1/2 text-gray-500 font-medium peer-focus:top-4 peer-focus:text-sm peer-placeholder-shown:top-1/2 peer-[&:not(:placeholder-shown)]:top-4 peer-[&:not(:placeholder-shown)]:text-sm transition-all">Password</label>
                                        <img src="src/asset/icons/lock-grey.svg" alt="Password icon" class="absolute left-6 top-1/2 -translate-y-1/2 size-6" />
                                        <div class="absolute left-[64px] top-1/2 -translate-y-1/2 w-[1.5px] h-6 bg-gray-300"></div>
                                        <button type="button" id="show-password" class="absolute right-6 top-1/2 -translate-y-1/2 cursor-pointer">
                                            <img src="src/asset/icons/eye-grey.svg" alt="Show password" id="show-icon" class="size-6" />
                                            <img src="src/asset/icons/eye-slash-black.svg" alt="Hide password" id="hide-icon" class="size-6 hidden" />
                                        </button>
                                    </div>
                                    <a href="<?php echo BASEURL; ?>/forget-password" class="block text-end mt-3 font-medium text-gray-500 hover:text-blue-500 hover:underline">Forget My password</a>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-6">
                            <button type="submit" class="w-full bg-blue-800 text-white font-bold py-4 rounded-full hover:bg-blue-700 transition-all">Sign In Now</button>
                            <p class="font-semibold text-center">Don’t Have One? <a href="<?php echo BASEURL; ?>/signup" class="text-blue-500 hover:underline">Create Account Now</a></p>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </div>

    <script src="<?php echo BASEURL; ?>/src/js/main.js"></script>
</body>

</html>