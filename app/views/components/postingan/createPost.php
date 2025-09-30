<form class="bg-white rounded-2xl p-4 shadow-md" method="POST" action="" enctype="multipart/form-data">
    <div class="flex items-start gap-4">
        <div class="flex size-14 rounded-full overflow-hidden flex-shrink-0">
            <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" class="w-full h-full object-cover" alt="photo">
        </div>
        <textarea name="post_content" id="post_content" rows="3" placeholder="Apa yang sedang Anda pikirkan?" class="w-full bg-gray-100 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 resize-none"></textarea>
    </div>

    <div id="image-preview-container" class="relative mt-4 flex justify-center">
        <img id="post-preview-image" src="" alt="Image Preview" class="h-44 max-w-full rounded-lg w-auto hidden">

        <button type="button" id="post-remove-preview" class="absolute top-1 right-1 size-6 items-center justify-center bg-white hover:bg-gray-200 rounded-full transition-colors hidden">
            <img src="<?php echo BASEURL; ?>/src/asset/icons/close-circle-grey.svg" class="w-6 h-6 cursor-pointer" alt="Remove">
        </button>
    </div>

    <div class="flex justify-between items-center mt-4">
        <div>
            <label for="image-input" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 cursor-pointer">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/gallery-import.svg" class="w-6 h-6" alt="icon">
            </label>
            <input type="file" id="image-input" name="post_image" class="hidden" accept="image/*">
        </div>

        <button type="submit" class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-semibold rounded-lg text-sm px-5 py-2.5 text-center flex items-center gap-2 transition-all duration-300">
            Posting
            <img src="<?php echo BASEURL; ?>/src/asset/image/send.png" class="size-4 mt-1" alt="icon">
        </button>
    </div>
</form>