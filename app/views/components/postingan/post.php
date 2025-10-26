<?php if (empty($posts)): ?>
    <div class="bg-white p-6 rounded-xl mt-6 flex flex-col items-center justify-center shadow-sm">
        <img src="<?= BASEURL ?>/src/asset/image/empty-folder.png" alt="icon" width="100" class="mb-2">
        <h1 class="text-gray-600 text-center text-sm">Saat ini belum ada postingan.</h1>
    </div>
<?php else: ?>
    <?php
    $currentUserId = $_SESSION['user_id'] ?? null;
    foreach ($posts as $post):
        $isOwner = ($currentUserId !== null && $currentUserId === $post['USER_ID']);
    ?>
        <div class="my-5">
            <div class="bg-white text-gray-900 border border-gray-200 rounded-2xl shadow-sm p-4">
                <div class="flex items-start space-x-3">
                    <img src="<?= !empty($post['PATH_PHOTO'])
                                    ? BASEURL . '/storage/users/photos/' . $post['PATH_PHOTO']
                                    : BASEURL . '/src/asset/image/default.png' ?>"
                        alt="Profile" class="w-12 h-12 rounded-full object-cover flex-shrink-0">

                    <div class="flex-1">
                        <div class="text-lg">
                            <span class="font-semibold text-gray-700"><?= htmlspecialchars($post['FULL_NAME']) ?></span>
                        </div>
                        <div class="text-sm">
                            <span class="text-gray-500">@<?= htmlspecialchars($post['USERNAME']) ?></span>
                            <span class="text-gray-400">· <?= date('d M Y', strtotime($post['CREATED_AT'])) ?></span>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="relative inline-block text-left">
                            <button
                                class="p-2 rounded-full hover:bg-gray-100 transition-colors"
                                onclick="toggleDropdown('dropdown-<?= $post['POST_ID'] ?>')">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                    <circle cx="5" cy="12" r="2" />
                                    <circle cx="12" cy="12" r="2" />
                                    <circle cx="19" cy="12" r="2" />
                                </svg>
                            </button>

                            <div
                                id="dropdown-<?= $post['POST_ID'] ?>"
                                class="hidden absolute right-0 mt-2 w-36 bg-white border border-gray-200 rounded-lg shadow-lg z-20 overflow-hidden">
                                <?php if ($isOwner): ?>
                                    <button
                                        type="button"
                                        class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100"
                                        onclick="openDeletePostModal('<?= $post['POST_ID'] ?>')">
                                        Hapus
                                    </button>
                                <?php else: ?>
                                    <button
                                        type="button"
                                        class="report-btn w-full text-left px-4 py-2 text-sm text-black hover:bg-gray-100"
                                        data-post-id="<?= $post['POST_ID']; ?>">
                                        Report
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <?php
                    $content = $post['CONTENT'];
                    if ($content instanceof OCILob) {
                        $content = $content->load();
                    }
                    ?>
                    <p class="mt-2 text-black text-[15px leading-relaxed"><?= nl2br(htmlspecialchars($content ?? '')) ?></p>
                </div>

                <?php if (!empty($post['MEDIA'])): ?>
                    <div class="mt-4 rounded-2xl overflow-hidden border border-gray-100 ">
                        <swiper-container class="mySwiper aspect-video w-full min-h-[250px] md:min-h-[400px]" init="false">
                            <?php foreach ($post['MEDIA'] as $mediaPath): ?>
                                <swiper-slide>
                                    <img src="<?= BASEURL . '/' . $mediaPath ?>" class="w-full h-full object-contain bg-gray-50">
                                </swiper-slide>
                            <?php endforeach; ?>
                        </swiper-container>
                    </div>
                <?php endif; ?>

                <div class="mt-3 flex items-center justify-between text-gray-500 text-sm border-t border-gray-100 pt-3">
                    <div class="flex items-center space-x-6">
                        <button class="flex items-center hover:text-red-500 transition-colors group cursor-pointer">
                            <div class="p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span>0</span>
                        </button>

                        <a href="<?= BASEURL ?>/homepage/reply/<?= $post['POST_ID'] ?>" class="flex items-center hover:text-blue-600 transition-colors group cursor-pointer">
                            <div class="p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <span>0</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php include __DIR__ . '/modalDeletePost.php'; ?>
<?php include __DIR__ . '/modalEditPost.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
<script>
    customElements.whenDefined('swiper-container').then(() => {
        const swiperElements = document.querySelectorAll('swiper-container.mySwiper');

        const style = `
            .swiper-button-next,
            .swiper-button-prev {
                opacity: 0;
                transition: opacity 0.3s ease;
                color: #ffffff; 
                padding: 6px;
                background-color: rgba(0, 0, 0, 0.2); 
                border-radius: 50%;
                width: 15px;
                height: 15px;
                --swiper-navigation-size: 16px; 
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

    function toggleDropdown(id) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        const dropdown = document.getElementById(id);
        dropdown.classList.toggle('hidden');
    }

    function openEditPostModal(postId, content, mediaPaths = []) {
        const container = document.getElementById("media-preview-container");
        if (!container) {
            console.error("Elemen #media-preview-container tidak ditemukan!");
            return;
        }
        modalEditPost.classList.remove("hidden");
        modalEditPost.classList.add("flex");

        document.getElementById("edit-post-id").value = postId;
        document.getElementById("edit-post-content").value = content || "";

        existingMedia = [...mediaPaths];
        deletedMedia = [];
        newMediaFiles = [];
        renderMediaPreviews();
    }

    document.querySelectorAll('.edit-post-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const json = this.getAttribute('data-post');
            if (!json) return console.error('data-post kosong pada tombol edit');
            let data;
            try {
                data = JSON.parse(json);
            } catch (e) {
                console.error('Gagal parse data-post JSON', e, json);
                return;
            }
            openEditPostModal(data.id, data.content, data.media || []);
        });
    });

    function openDeletePostModal(postId) {
        const modal = document.getElementById("modal-delete-post");
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        document.getElementById("delete-post-id").value = postId;
    }

    function reportPost(postId) {
        alert("Report post: " + postId);
    }
</script>
