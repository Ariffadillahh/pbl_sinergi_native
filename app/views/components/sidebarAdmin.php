<aside id="sidebar"
    class="fixed md:static top-0 left-0 z-40 w-64 h-full md:h-screen bg-blue-950 shadow-lg transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="h-full px-3 py-5 overflow-y-auto">
        <h2 class="text-gray-100 text-xl font-semibold px-2 mb-6">Dashboard</h2>

        <ul class="space-y-2 font-medium">
            <li>
                <a href="<?php echo BASEURL ?>/dashboard"
                    class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 text-white group-hover:text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 22 21">
                        <path
                            d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                        <path
                            d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                    </svg>
                    <span class="ms-3">Home</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASEURL ?>/dashboard/buku"
                    class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 pt-1 text-white group-hover:text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 18 18">
                        <path
                            d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Z" />
                    </svg>
                    <span class="ms-3">Buku</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASEURL ?>/dashboard/anggota"
                    class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 text-white group-hover:text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 18">
                        <path
                            d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                    </svg>
                    <span class="ms-3">Anggota</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASEURL ?>/dashboard/peminjaman-buku"
                    class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 19h16M4 5h16M6 7v10a2 2 0 002 2h8a2 2 0 002-2V7M8 11h8" />
                    </svg>


                    <span class="ms-2">Peminjaman Buku</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASEURL ?>/logout"
                    class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5 text-white group-hover:text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 18 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                    </svg>
                    <span class="ms-3">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>