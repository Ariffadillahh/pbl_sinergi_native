<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINERGI</title>
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
            <a href="<?= BASEURL ?>/sign-in" class="border-2 border-blue-500 text-blue-600 px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-500 hover:text-white transition duration-300">My Account</a>
        </nav>

        <section class="grid grid-cols-1 lg:grid-cols-2 px-6 lg:px-20 py-12 lg:py-20 gap-8 items-center" id="home">
            <div class="max-w-xl space-y-7 text-center lg:text-left mx-auto lg:mx-0">
                <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold leading-tight">
                    Sistem <span class="text-blue-600">IN</span>teraksi <br>
                    Edukasi Riset <br>
                    <span class="bg-gradient-to-r from-blue-600 to-blue-500 bg-clip-text text-transparent">Gagasan & Inovasi</span>
                </h1>
                <p class="text-gray-600 text-base lg:text-lg leading-relaxed">
                    SINERGI menghubungkan mahasiswa, dosen, admin, alumni, dan mitra industri dalam satu ekosistem digital
                    untuk mendukung aktivitas akademik dan kolaborasi.
                </p>

                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 pt-2">
                    <a href="<?= BASEURL ?>/sign-up" class="bg-gradient-to-r from-blue-600 to-blue-500 text-white px-8 py-3.5 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition duration-300 font-semibold">
                        Daftar Sekarang
                    </a>
                    <a href="#fitur" class="border-2 border-blue-500 text-blue-600 px-8 py-3.5 rounded-lg hover:bg-blue-50 transition duration-300 flex items-center justify-center gap-2 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Lihat Fitur
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
                                https://sinergi.com
                            </div>
                        </div>

                        <img
                            src="<?php echo BASEURL; ?>/src/asset/image/wow.png"
                            alt="Dashboard SINERGI"
                            class="w-full">
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="py-16 lg:py-24 px-6 lg:px-24 bg-gradient-to-b from-white to-gray-50">
            <div class="text-center mb-16">
                <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Fitur Unggulan</span>
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mt-3">Solusi Lengkap untuk Akademik Modern</h2>
                <p class="text-gray-500 mt-3 max-w-2xl mx-auto">
                    Teknologi terdepan yang dirancang khusus untuk meningkatkan produktivitas dan kolaborasi akademik
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">

                <div class="group bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-300">
                    <div class="flex justify-center mb-4">
                        <div class="p-4 rounded-full">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/icon-diskusi.svg" class="w-14 h-14 group-hover:scale-110 transition duration-300" alt="">
                        </div>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-gray-900">Diskusi Interaktif</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Mulai percakapan, tanyakan pertanyaan, dan dapatkan jawaban langsung dari komunitas akademik.
                    </p>
                </div>

                <!-- Feature Card 2 -->
                <div class="group bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-300">
                    <div class="flex justify-center mb-5">
                        <div class="p-4 rounded-full">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/icon-post.svg" class="w-14 h-14 group-hover:scale-110 transition duration-300" alt="">
                        </div>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-gray-900">Postingan Interaktif</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Berbagi pemikiran, ide, dan pengalaman antar sesama civitas akademika.
                    </p>
                </div>

                <!-- Feature Card 3 -->
                <div class="group bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-300">
                    <div class="flex justify-center mb-5">
                        <div class="p-4 rounded-full">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/icon-kolaborasi.svg" class="w-14 h-14 group-hover:scale-110 transition duration-300" alt="">
                        </div>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-gray-900">Kolaborasi Akademik</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Temukan partner untuk tugas, riset, atau proyek bersama.
                    </p>
                </div>

                <!-- Feature Card 4 -->
                <div class="group bg-white rounded-2xl p-8 text-center border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl hover:-translate-y-3 transition-all duration-300">
                    <div class="flex justify-center mb-5">
                        <div class="p-4 rounded-full">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/icon-notifikasi.svg" class="w-14 h-14 group-hover:scale-110 transition duration-300" alt="">
                        </div>
                    </div>
                    <h3 class="font-bold text-lg mb-3 text-gray-900">Notifikasi Real-time</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Dapatkan update instan setiap ada balasan atau thread terbaru.
                    </p>
                </div>

            </div>
        </section>

    </div>

    <section id="testimoni" class="py-16 lg:py-24 bg-gradient-to-b from-gray-50 to-white overflow-hidden">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-gray-900">Testimoni Pengguna</h2>
            <p class="text-gray-500 mt-2">
                Dengarkan pengalaman mereka yang telah menggunakan SINERGI
            </p>
        </div>

        <!-- Infinite Scroll Wrapper -->
        <div class="relative w-full overflow-hidden">
            <div class="flex gap-6 animate-scroll">

                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            S
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Dr. Sari Wulandari, M.Pd</h3>
                            <span class="text-blue-600 text-sm">Dosen Teknik Informatika</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "SINERGI sangat membantu saya dalam mengelola kelas dan berkomunikasi dengan mahasiswa.
                        Interface yang intuitif membuat pekerjaan administratif menjadi lebih efisien."
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            A
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Ahmad Faris</h3>
                            <span class="text-blue-600 text-sm">Mahasiswa Teknik Informatika</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "Dengan SINERGI, saya bisa mengakses semua informasi akademik dengan mudah.
                        Fitur notifikasi dan reminder sangat membantu saya tidak melewatkan deadline tugas."
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            R
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Rizky Santoso</h3>
                            <span class="text-blue-600 text-sm">Alumni Teknik Komputer</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "Platform ini membantu saya tetap terhubung dengan kampus dan mahasiswa untuk proyek kolaboratif.
                        Sangat berguna untuk networking dan berbagi pengalaman."
                    </p>
                </div>

                <!-- Duplicate cards for seamless loop -->
                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            S
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Dr. Sari Wulandari, M.Pd</h3>
                            <span class="text-blue-600 text-sm">Dosen Teknik Informatika</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "SINERGI sangat membantu saya dalam mengelola kelas dan berkomunikasi dengan mahasiswa.
                        Interface yang intuitif membuat pekerjaan administratif menjadi lebih efisien."
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            A
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Ahmad Faris</h3>
                            <span class="text-blue-600 text-sm">Mahasiswa Teknik Informatika</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "Dengan SINERGI, saya bisa mengakses semua informasi akademik dengan mudah.
                        Fitur notifikasi dan reminder sangat membantu saya tidak melewatkan deadline tugas."
                    </p>
                </div>

                <div class="bg-white rounded-2xl p-8 w-[380px] flex-shrink-0 border-2 border-gray-100 hover:border-blue-500 hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            R
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Rizky Santoso</h3>
                            <span class="text-blue-600 text-sm">Alumni Teknik Komputer</span>
                        </div>
                    </div>
                    <div class="flex text-yellow-400 mb-4 text-lg">⭐⭐⭐⭐⭐</div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        "Platform ini membantu saya tetap terhubung dengan kampus dan mahasiswa untuk proyek kolaboratif.
                        Sangat berguna untuk networking dan berbagi pengalaman."
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
                    Platform akademik terpadu yang menghubungkan mahasiswa dan dosen dalam satu ekosistem digital yang modern dan efisien.
                </p>
            </div>

            <!-- Menu -->
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
                <h3 class="text-white font-bold mb-4">Ikuti Kami</h3>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-400 transition duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-pink-600 transition duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-blue-700 transition duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                    </a>
                </div>
            </div>

        </div>

        <div class="border-t border-gray-800 mt-10 pt-6 text-center text-sm text-gray-500">
            © 2025 <span class="text-blue-400 font-semibold">SINERGI</span>. Semua hak dilindungi.
        </div>
    </footer>
</body>

</html>