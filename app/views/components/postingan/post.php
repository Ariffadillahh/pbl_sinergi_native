<?php if (empty($users)): ?>
    <div class="bg-white p-4 rounded-xl mt-4 flex justify-center items-center">
        <div>
            <img src="<?php echo BASEURL . '/src/asset/image/empty-folder.png'; ?>" alt="icon" width="100" class="mx-auto">
            <h1 class="text-center">
                Saat ini belum ada postingan.</h1>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($users as $user): ?>
        <div class="my-4">
            <div class="max-w-xl w-full mx-auto">
                <div class="bg-white text-black border border-gray-200 rounded-2xl p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" alt="Profile" class="w-12 h-12 rounded-full">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-1">
                                <span class="text-gray-500">@<?php echo htmlspecialchars($user['ID']); ?> · Sep 28</span>
                            </div>

                            <div class="mt-1 text-gray-600 text-base">
                                Hmmmmm
                            </div>
                        </div>



                        <button class="p-2 -mt-2 rounded-full hover:bg-gray-200/50 transition-colors">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="5" cy="12" r="2" />
                                <circle cx="12" cy="12" r="2" />
                                <circle cx="19" cy="12" r="2" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-3 w-full rounded-2xl overflow-hidden">
                        <swiper-container class="mySwiper aspect-video w-full min-h-[300px] lg:min-h-[400px]" init="false">
                            <swiper-slide>
                                <img src="https://images.tokopedia.net/img/JFrBQq/2022/6/15/5fbd53e1-4cb6-4bde-af42-d085219cca69.jpg" alt="Motor 1"
                                    class="w-full h-full object-contain">
                            </swiper-slide>
                            <swiper-slide>
                                <img src="https://images.tokopedia.net/img/JFrBQq/2022/6/15/5fbd53e1-4cb6-4bde-af42-d085219cca69.jpg" alt="Motor 1"
                                    class="w-full h-full object-contain">
                            </swiper-slide>
                            <swiper-slide>
                                <img src="https://images.tokopedia.net/img/JFrBQq/2022/6/15/5fbd53e1-4cb6-4bde-af42-d085219cca69.jpg" alt="Motor 1"
                                    class="w-full h-full object-contain">
                            </swiper-slide>
                        </swiper-container>
                    </div>

                    <div class="mt-2 flex items-center justify-between text-gray-500 text-sm border-t border-gray-100 pt-2">
                        <div class="flex items-center space-x-6">
                            <button class="flex items-center hover:text-red-500 transition-colors group cursor-pointer">
                                <div class="p-2 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                <span>14K</span>
                            </button>
                            <a href="<?php echo BASEURL ?>/homepage/reply/<?php echo $user['ID']; ?>" class="flex items-center hover:text-blue-600 transition-colors group cursor-pointer">
                                <div class="p-2 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <span>366</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
<script>
    customElements.whenDefined('swiper-container').then(() => {
        const swiperElements = document.querySelectorAll('swiper-container.mySwiper');

        const style = `
            .swiper-button-next,
            .swiper-button-prev {
                opacity: 0;
                transition: opacity 0.3s ease;
                color: #ffffff; /* Ubah warna panah menjadi putih agar lebih terlihat */
                padding: 6px;
                background-color: rgba(0, 0, 0, 0.2); /* Latar belakang semi-transparan */
                border-radius: 50%;
                width: 15px;
                height: 15px;
                --swiper-navigation-size: 16px; /* Ukuran ikon panah */
            }

            :host(:hover) .swiper-button-next,
            :host(:hover) .swiper-button-prev {
                opacity: 1;
            }

            .swiper-button-disabled {
                opacity: 0 !important;
                pointer-events: none;
            }
        `;

        const swiperParams = {
            navigation: true,
            pagination: {
                clickable: true,
                dynamicBullets: true,
            },
            injectStyles: [style],
        };

        swiperElements.forEach(swiperEl => {
            Object.assign(swiperEl, swiperParams);
            swiperEl.initialize();
        });
    });
</script>