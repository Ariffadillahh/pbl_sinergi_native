<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINERGI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
</head>

<body class="bg-black h-screen w-screen flex items-center justify-center">

    <swiper-container class="mySwiper w-full h-full" init="false">
        <swiper-slide class="flex items-center justify-center bg-gray-700">Slide 1</swiper-slide>
        <swiper-slide class="flex items-center justify-center bg-gray-600">Slide 2</swiper-slide>
        <swiper-slide class="flex items-center justify-center bg-gray-500">Slide 3</swiper-slide>
        <swiper-slide class="flex items-center justify-center bg-gray-400">Slide 4</swiper-slide>
        <swiper-slide class="flex items-center justify-center bg-gray-300">Slide 5</swiper-slide>
        <swiper-slide class="flex items-center justify-center bg-gray-200 text-black">Slide 6</swiper-slide>
    </swiper-container>

    <script>
        customElements.whenDefined('swiper-container').then(() => {
            const swiperEl = document.querySelector('swiper-container');

            const style = `
                .swiper-button-next,
                .swiper-button-prev {
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    color: #007aff;
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

            Object.assign(swiperEl, swiperParams);
            swiperEl.initialize();
        });
    </script>

</body>

</html>