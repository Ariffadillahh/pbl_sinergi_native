<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinergi</title>
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
</head>

<body class="bg-white text-gray-800 font-sans">

    <div class="max-w-[1400px] mx-auto">
        <nav class="flex justify-between items-center py-6 px-6 lg:px-10">
            <div class="flex items-center space-x-2">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="w-12 h-10 shrink-0 mx-auto" alt="logo">
                <span class="font-semibold text-xl tracking-wide">SINERGI</span>
            </div>
            <ul class="hidden md:flex space-x-8 text-gray-600 font-medium">
                <li><a href="#home" class="hover:text-blue-600 transition">Home</a></li>
                <li><a href="#fitur" class="hover:text-blue-600 transition">Features</a></li>
                <li><a href="#testimoni" class="hover:text-blue-600 transition">Review</a></li>
            </ul>
            <a href="<?= BASEURL ?>/sign-in" class="border-2 border-blue-500 text-blue-600 px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-500 hover:text-white transition duration-300">Sign In</a>
        </nav>

        <section class="grid grid-cols-1 lg:grid-cols-2 px-6 lg:px-20 py-12 lg:py-20 gap-8 items-center" id="home">
            <div class="max-w-xl space-y-7 text-center lg:text-left mx-auto lg:mx-0">
                <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight">
                    Sistem <span class="text-blue-600">IN</span>teraksi <br>
                    Edukasi Riset <br>
                    <span class="bg-gradient-to-r from-blue-600 to-blue-500 bg-clip-text text-transparent">Gagasan & Inovasi</span>
                </h1>
                <p class="text-gray-600 text-base lg:text-lg leading-relaxed">
                    SINERGI connects students, lecturers, admins, alumni, and industry partners in one digital ecosystem
                    to support academic activities and collaboration.
                </p>

                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 pt-2">
                    <a href="<?= BASEURL ?>/sign-up" class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-8 py-3.5 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition duration-300 font-semibold">
                        Register Now
                    </a>
                    <a href="#fitur" class="border-2 border-blue-500 text-blue-600 px-8 py-3.5 rounded-lg hover:bg-blue-50 transition duration-300 flex items-center justify-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        See Features
                    </a>
                </div>
            </div>

            <div class="mt-10 lg:mt-0 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-2xl">
                    <div class="absolute top-1/4 -left-12 w-64 h-64 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
                    <div class="absolute bottom-1/4 -right-12 w-64 h-64 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>

                    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl shadow-2xl overflow-hidden border border-gray-200">
                        <div class="bg-white border-b border-gray-200 px-4 py-3 flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="flex-1 bg-gray-100 rounded px-3 py-1 text-xs text-gray-500 mx-4">
                                <?= BASEURL ?>
                            </div>
                        </div>

                        <img
                            src="<?php echo BASEURL; ?>/src/asset/image/wow.png"
                            alt="SINERGI Dashboard"
                            class="w-full">
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="py-16 lg:py-24 px-6 lg:px-24 bg-gradient-to-b from-white to-gray-50">
            <div class="text-center mb-16">
                <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Featured Features</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-3">Complete Solution for Modern Academics</h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">
                    Leading technology specifically designed to enhance academic productivity and collaboration.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">

                <div class="group bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-300">
                    <div class="flex justify-center mb-4">
                        <div class="p-4 rounded-full">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/icon-diskusi.svg" class="w-14 h-14 group-hover:scale-110 transition duration-300" alt="">
                        </div>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-gray-900">Interactive Discussion</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Start conversations, ask questions, and get answers from the academic community.
                    </p>
                </div>

                <div class="group bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-300">
                    <div class="flex justify-center mb-5">
                        <div class="p-4 rounded-full">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/icon-post.svg" class="w-14 h-14 group-hover:scale-110 transition duration-300" alt="">
                        </div>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-gray-900">Interactive Posts</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Share thoughts, ideas, and experiences among fellow academic members.
                    </p>
                </div>

                <div class="group bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-300">
                    <div class="flex justify-center mb-5">
                        <div class="p-4 rounded-full">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/icon-kolaborasi.svg" class="w-14 h-14 group-hover:scale-110 transition duration-300" alt="">
                        </div>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-gray-900">Academic Collaboration</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Find partners for assignments, research, or collaborative projects.
                    </p>
                </div>

                <div class="group bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-300">
                    <div class="flex justify-center mb-5">
                        <div class="p-4 rounded-full">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/icon-notifikasi.svg" class="w-14 h-14 group-hover:scale-110 transition duration-300" alt="">
                        </div>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-gray-900">Real-time Notifications</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Get instant updates whenever there is a reply or a new thread.
                    </p>
                </div>

            </div>
        </section>

    </div>

    <section id="testimoni" class="py-16 lg:py-24 bg-gradient-to-b from-gray-50 to-white overflow-hidden">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-gray-900">User Testimonials</h2>
            <p class="text-gray-500 mt-2">
                Hear the experiences of those who have used SINERGI
            </p>
        </div>

        <div class="relative w-full overflow-hidden">
            <div class="flex gap-6 animate-scroll">

                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            S
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Dr. Sari Wulandari, M.Pd</h3>
                            <span class="text-blue-600 text-sm">Informatics Engineering Lecturer</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "SINERGI greatly helps me in managing classes and communicating with students.
                        The intuitive interface makes administrative work more efficient."
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            A
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Ahmad Faris</h3>
                            <span class="text-blue-600 text-sm">Informatics Engineering Student</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "With SINERGI, I can access all academic information easily.
                        The notification and reminder features really help me not to miss assignment deadlines."
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            R
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Rizky Santoso</h3>
                            <span class="text-blue-600 text-sm">Computer Engineering Alumnus</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "This platform helps me stay connected with the campus and students for collaborative projects.
                        Very useful for networking and sharing experiences."
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            S
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Dr. Sari Wulandari, M.Pd</h3>
                            <span class="text-blue-600 text-sm">Informatics Engineering Lecturer</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "SINERGI greatly helps me in managing classes and communicating with students.
                        The intuitive interface makes administrative work more efficient."
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            A
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Ahmad Faris</h3>
                            <span class="text-blue-600 text-sm">Informatics Engineering Student</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "With SINERGI, I can access all academic information easily.
                        The notification and reminder features really help me not to miss assignment deadlines."
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            R
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Rizky Santoso</h3>
                            <span class="text-blue-600 text-sm">Computer Engineering Alumnus</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "This platform helps me stay connected with the campus and students for collaborative projects.
                        Very useful for networking and sharing experiences."
                    </p>
                </div>

            </div>
        </div>
    </section>

    <footer class="bg-gradient-to-b from-[#0b1320] to-[#050a14] text-gray-300 py-12 px-6 lg:px-16">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-[1.3fr_0.8fr_1fr] gap-12 items-start">

            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="flex items-center justify-center">
                        <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="w-12 h-10 shrink-0 mx-auto" alt="logo">
                    </div>
                    <span class="text-white font-bold text-xl">SINERGI</span>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed max-w-sm">
                    An integrated academic platform that connects students and lecturers in a modern and efficient digital ecosystem.
                </p>
            </div>

            <div class="pl-0 md:pl-12">
                <h3 class="text-white font-bold mb-4">Menu</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="#home" class="hover:text-blue-400 transition flex items-center gap-2">
                            <span class="text-blue-500">›</span> Home
                        </a></li>
                    <li><a href="#fitur" class="hover:text-blue-400 transition flex items-center gap-2">
                            <span class="text-blue-500">›</span> Features
                        </a></li>
                    <li><a href="#testimoni" class="hover:text-blue-400 transition flex items-center gap-2">
                            <span class="text-blue-500">›</span> Review
                        </a></li>
                    <li>
                </ul>
            </div>

            <div>
                <h3 class="text-white font-bold mb-4">Team</h3>
                <div class="space-y-2 text-gray-300">

                    <div class="px-4 py-2 bg-gray-800 rounded-lg hover:bg-blue-600 transition duration-300">
                        Arif Fadillah Wicaksono
                    </div>

                    <div class="px-4 py-2 bg-gray-800 rounded-lg hover:bg-blue-600 transition duration-300">
                        Haikal Benyamin Prabowo
                    </div>

                    <div class="px-4 py-2 bg-gray-800 rounded-lg hover:bg-blue-600 transition duration-300">
                        Meiza Zafira Angraini
                    </div>

                    <div class="px-4 py-2 bg-gray-800 rounded-lg hover:bg-blue-600 transition duration-300">
                        Raditya Meyka Harry Sandhiva
                    </div>

                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-10 pt-6 text-center text-sm text-gray-500">
            © 2025 <span class="text-blue-400 font-semibold">SINERGI</span>. All rights reserved.
        </div>
    </footer>
</body>

</html>