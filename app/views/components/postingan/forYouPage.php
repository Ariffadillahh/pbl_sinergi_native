<div class="border-b border-gray-200 mb-4">
    <h2 class="text-2xl mb-4 text-center font-bold bg-gradient-to-r from-blue-600 to-[#30A9EE] bg-clip-text text-transparent">
        For You
    </h2>
    <div class="w-[50px] h-[2px] bg-blue-600 mx-auto"></div>
</div>
<div class="h-full overflow-y-auto hide-scrollbar px-5">
    <div class="mb-16">
        <div>
            <h1 class="font-semibold font-sans">What's Trending</h1>

            <div class="flex items-start gap-3 my-3 border border-gray-200 rounded-2xl p-3 hover:bg-gray-50 cursor-pointer">
                <img src="https://www.bmwgroup.com/en/company/_jcr_content/main/layoutcontainer_1988/columncontrol/columncontrolparsys/globalimage.coreimg.jpeg/1758537295862/720x720-i5er.jpeg" alt="" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">

                <div class="flex-1 min-w-0">
                    <p class="text-gray-600">@username</p>
                    <p class="text-black font-bold truncate mt-3">Mobil terbaru tahun 2026sssssssssssssssssssssssssssss</p>
                </div>
            </div>
            <div class="flex items-start gap-3 my-3 border border-gray-200 rounded-2xl p-3 hover:bg-gray-50 cursor-pointer">
                <img src="https://www.bmwgroup.com/en/company/_jcr_content/main/layoutcontainer_1988/columncontrol/columncontrolparsys/globalimage.coreimg.jpeg/1758537295862/720x720-i5er.jpeg" alt="" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">

                <div class="flex-1 min-w-0">
                    <p class="text-gray-600">@username</p>
                    <p class="text-black font-bold truncate mt-3">Mobil terbaru tahun 2026sssssssssssssssssssssssssssss</p>
                </div>
            </div>
            <div class="flex items-start gap-3 my-3 border border-gray-200 rounded-2xl p-3 hover:bg-gray-50 cursor-pointer">
                <img src="https://www.bmwgroup.com/en/company/_jcr_content/main/layoutcontainer_1988/columncontrol/columncontrolparsys/globalimage.coreimg.jpeg/1758537295862/720x720-i5er.jpeg" alt="" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">

                <div class="flex-1 min-w-0">
                    <p class="text-gray-600">@username</p>
                    <p class="text-black font-bold truncate mt-3">Mobil terbaru tahun 2026sssssssssssssssssssssssssssss</p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h1 class="font-semibold font-sans">Hot Forums 🔥</h1>
            <?php
            $forums = [
                ['name' => 'PHP Keren Banget Sampai Ujung Dunia', 'members' => 12, 'is_new' => true],
                ['name' => 'PNJ', 'members' => 12, 'is_new' => true],
                ['name' => 'Laravel', 'members' => 12, 'is_new' => true],
            ];
            foreach ($forums as $forum):
            ?>

                <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 mt-2">
                    <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                        <img src="<?php echo BASEURL; ?>/src/asset/image/default.png" class="w-full h-full object-cover" alt="photo">
                    </div>
                    <div class="flex flex-col flex-1 min-w-0 gap-[6px]">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1 min-w-0">
                                <p class="font-semibold truncate"><?php echo htmlspecialchars($forum['name']); ?></p>
                            </div>
                            <div class="flex items-center gap-0.5">
                                <?php if ($forum['is_new']): ?>
                                    <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Tranding</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex font-medium text-sm text-gray-500 gap-0.5 items-center">
                            <?php echo htmlspecialchars($forum['members']); ?> Members
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-8">
            <h1 class="font-semibold font-sans">New Forums</h1>
            <?php
            $forums = [
                ['name' => 'PHP Keren Banget Sampai Ujung Dunia', 'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam iaculis nulla sed commodo volutpat. Ut porta mollis urna. Vestibulum porta sollicitudin condimentum. ", 'is_new' => true],
                ['name' => 'PNJ', 'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam iaculis nulla sed commodo volutpat. Ut porta mollis urna. Vestibulum porta sollicitudin condimentum. ", 'is_new' => true],
                ['name' => 'Laravel', 'bio' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam iaculis nulla sed commodo volutpat. Ut porta mollis urna. Vestibulum porta sollicitudin condimentum. ", 'is_new' => true],
            ];
            foreach ($forums as $forum):
            ?>

                <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 mt-2">
                    <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                        <img src="<?php echo BASEURL; ?>/src/asset/image/default.png" class="w-full h-full object-cover" alt="photo">
                    </div>
                    <div class="flex flex-col flex-1 min-w-0 gap-[6px]">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1 min-w-0">
                                <p class="font-semibold truncate"><?php echo htmlspecialchars($forum['name']); ?></p>
                            </div>
                            <div class="flex items-center gap-0.5">
                                <?php if ($forum['is_new']): ?>
                                    <span class="bg-indigo-100 text-indigo-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">New</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex font-medium text-sm text-gray-500 gap-0.5 items-center min-w-0 ">
                            <p class="truncate">
                                <?php echo htmlspecialchars($forum['bio']); ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>