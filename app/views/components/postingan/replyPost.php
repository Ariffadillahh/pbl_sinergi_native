<div class="bg-white text-gray-900 border border-gray-200 rounded-2xl shadow-sm overflow-hidden" id="post-<?= $post['POST_ID'] ?>">
    <!-- Header Section -->
    <div class="p-4">
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
                    <?php
                    $profileUrl = ($post['USER_ID'] === $_SESSION['user_id'])
                        ? BASEURL . "/profile"
                        : BASEURL . "/homepage/user/profile/" . htmlspecialchars($post['USER_ID']);
                    ?>

                    <a href="<?= $profileUrl ?>">
                        <span class="text-gray-500">@<?= htmlspecialchars($post['USERNAME']) ?></span>
                    </a>
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
                        <?php $isOwner = $post['USER_ID'] === $_SESSION['user_id'];
                        if ($isOwner): ?>
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

        <!-- Content -->
        <div class="mt-3">
            <p class="text-black text-[15px] leading-relaxed"><?= $post['CONTENT_FORMATTED'] ?? '' ?></p>
        </div>
    </div>

    <!-- Media Section -->
    <?php if (!empty($post['MEDIA'])): ?>
        <div class="bg-gradient-to-b from-gray-900 to-black overflow-hidden">
            <swiper-container class="mySwiper aspect-video w-full min-h-[250px] md:min-h-[400px]" init="false">
                <?php foreach ($post['MEDIA'] as $mediaPath): ?>
                    <swiper-slide>
                        <img src="<?= BASEURL . '/' . $mediaPath ?>" class="w-full h-full object-contain bg-gray-50">
                    </swiper-slide>
                <?php endforeach; ?>
            </swiper-container>
        </div>
    <?php endif; ?>

    <!-- Stats Bar -->
    <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
        <div class="flex items-center justify-between text-sm text-gray-600">
            <div class="flex items-center gap-2 hover:text-blue-600 transition-colors cursor-pointer">
                <div class="flex -space-x-1">
                    <span class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full p-1 ring-2 ring-white">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v7.333a2 2 0 01-.826 1.57l-2.174.43z" />
                        </svg>
                    </span>
                </div>
                <span class="like-count-display font-semibold"><?= htmlspecialchars($post['TOTAL_LIKES'] ?? 0) ?> Likes</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="border-t border-gray-100 p-2 flex justify-center gap-2 bg-white">
        <button class="like-btn flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl transition-all hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 cursor-pointer group w-full relative overflow-hidden"
            data-post-id="<?= $post['POST_ID'] ?>"
            data-liked="<?= ($post['IS_LIKED'] ?? false) ? 'true' : 'false' ?>">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/0 to-pink-500/0 group-hover:from-red-500/5 group-hover:to-pink-500/5 transition-all duration-300"></div>
            <svg class="w-5 h-5 transition-all duration-300 relative z-10 <?= ($post['IS_LIKED'] ?? false) ? 'text-red-500 fill-red-500' : 'text-gray-600 group-hover:text-red-500 group-hover:scale-110' ?>"
                fill="<?= ($post['IS_LIKED'] ?? false) ? 'currentColor' : 'none' ?>"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span class="text-gray-700 group-hover:text-red-500 text-sm font-semibold transition-colors relative z-10">Like</span>
        </button>
    </div>
</div>

<?php include __DIR__ . '/modalDeletePost.php'; ?>
<?php include __DIR__ . '/modalReportPost.php'; ?>

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
        // Close all other dropdowns first
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        // Toggle the clicked dropdown
        const dropdown = document.getElementById(id);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const isDropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');
        const isDropdownContent = event.target.closest('[id^="dropdown-"]');
        
        if (!isDropdownButton && !isDropdownContent) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                d.classList.add('hidden');
            });
        }
    });

    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const postId = this.getAttribute('data-post-id');
            const card = this.closest('[id^="post-"]');
            const countSpan = card ? card.querySelector('.like-count-display') : null;
            const icon = this.querySelector('svg');

            if (!card) {
                console.error('Error: Tidak dapat menemukan card post');
                return;
            }

            if (!countSpan) {
                console.error('Error: Tidak dapat menemukan elemen .like-count-display');
                return;
            }

            console.log('Toggling like for post:', postId);

            try {
                const res = await fetch('<?= BASEURL ?>/like/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        post_id: postId
                    })
                });

                const data = await res.json();
                console.log('Response:', data);

                if (data.success) {
                    const isLiked = data.action === 'liked';
                    this.setAttribute('data-liked', isLiked ? 'true' : 'false');
                    
                    // Update count dengan animasi
                    countSpan.textContent = data.total_likes + ' Likes';
                    countSpan.classList.add('scale-110', 'text-blue-600');
                    setTimeout(() => {
                        countSpan.classList.remove('scale-110', 'text-blue-600');
                    }, 200);

                    // Update icon dengan animasi
                    if (isLiked) {
                        icon.classList.remove('text-gray-600', 'group-hover:text-red-500');
                        icon.classList.add('text-red-500', 'fill-red-500', 'animate-pulse');
                        icon.setAttribute('fill', 'currentColor');
                        setTimeout(() => icon.classList.remove('animate-pulse'), 600);
                    } else {
                        icon.classList.remove('text-red-500', 'fill-red-500', 'animate-pulse');
                        icon.classList.add('text-gray-600', 'group-hover:text-red-500');
                        icon.setAttribute('fill', 'none');
                    }
                } else {
                    console.error('Server Error:', data.message);
                    alert(data.message || 'Gagal update like.');
                }
            } catch (err) {
                console.error('Fetch Error:', err);
                alert('Terjadi kesalahan saat memproses like.');
            }
        });
    });

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