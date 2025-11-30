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
        <div class="my-5" id="post-<?= $post['POST_ID'] ?>">
            <div class="bg-white text-gray-900 border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <!-- Header Section -->
                <div class="p-4">
                    <div class="flex items-start space-x-3">
                        <img src="<?= !empty($post['PATH_PHOTO'])
                                        ? BASEURL . '/storage/users/photos/' . $post['PATH_PHOTO']
                                        : BASEURL . '/src/asset/image/default.png' ?>"
                            alt="Profile" class="w-12 h-12 rounded-full object-cover flex-shrink-0">

                        <div class="flex-1">
                            <!-- FIX: Tambah badge role -->
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-gray-700"><?= htmlspecialchars($post['FULL_NAME']) ?></span>
                                
                                <?php
                                $role = $post['ROLE'] ?? 'MAHASISWA';
                                $roleClasses = [
                                    "MAHASISWA" => "bg-blue-100 text-blue-800",
                                    "ADMIN"     => "bg-red-100 text-red-800",
                                    "DOSEN"     => "bg-green-100 text-green-800",
                                    "MITRA"     => "bg-gray-100 text-gray-800",
                                    "ALUMNI"    => "bg-yellow-100 text-yellow-800"
                                ];
                                $colorClass = $roleClasses[$role] ?? "bg-gray-100 text-gray-800";
                                ?>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $colorClass ?>">
                                    <?= htmlspecialchars($role) ?>
                                </span>
                            </div>
                            
                            <div class="text-sm mt-0.5">
                                <?php
                                $profileUrl = ($post['USER_ID'] === $_SESSION['user_id'])
                                    ? BASEURL . "/profile"
                                    : BASEURL . "/homepage/user/profile/" . htmlspecialchars($post['USER_ID']);
                                ?>

                                <a href="<?= $profileUrl ?>">
                                    <span class="text-gray-500 hover:underline">@<?= htmlspecialchars($post['USERNAME']) ?></span>
                                </a>

                                <!-- FIX: Ubah ke time ago format -->
                                <span class="text-gray-400">· </span>
                                <span class="text-gray-400 time-ago" data-time="<?= $post['CREATED_AT'] ?>">
                                    <?= date('d M Y', strtotime($post['CREATED_AT'])) ?>
                                </span>
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
                        <div class="font-semibold hover:text-blue-600 transition-colors cursor-pointer">
                            <?= htmlspecialchars($post['TOTAL_COMMENT'] ?? 0) ?> Komentar
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-gray-100 p-2 flex justify-center gap-2 bg-white">
                    <button class="like-btn flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl transition-all hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 cursor-pointer group w-1/2 relative overflow-hidden"
                        data-post-id="<?= $post['POST_ID'] ?>"
                        data-liked="<?= $post['IS_LIKED'] ? 'true' : 'false' ?>">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-500/0 to-pink-500/0 group-hover:from-red-500/5 group-hover:to-pink-500/5 transition-all duration-300"></div>
                        <svg class="w-5 h-5 transition-all duration-300 relative z-10 <?= $post['IS_LIKED'] ? 'text-red-500 fill-red-500' : 'text-gray-600 group-hover:text-red-500 group-hover:scale-110' ?>"
                            fill="<?= $post['IS_LIKED'] ? 'currentColor' : 'none' ?>"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        <span class="text-gray-700 group-hover:text-red-500 text-sm font-semibold transition-colors relative z-10">Like</span>
                    </button>

                    <a href="<?= BASEURL ?>/homepage/reply/<?= $post['POST_ID'] ?>"
                        class="flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl transition-all hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50 cursor-pointer group w-1/2 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/0 to-cyan-500/0 group-hover:from-blue-500/5 group-hover:to-cyan-500/5 transition-all duration-300"></div>
                        <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600 group-hover:scale-110 transition-all duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="text-gray-700 group-hover:text-blue-600 text-sm font-semibold transition-colors relative z-10">Comment</span>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php include __DIR__ . '/modalDeletePost.php'; ?>
<?php include __DIR__ . '/modalEditPost.php'; ?>
<?php include __DIR__ . '/modalReportPost.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
<script>
    // FIX: Time ago function
    function timeAgo(dateString) {
        if (!dateString) return '';
        
        // Handle different date formats
        const safeDateString = dateString.replace(' ', 'T');
        const date = new Date(safeDateString);
        const now = new Date();

        if (isNaN(date.getTime())) {
            console.error("Invalid date:", dateString);
            return dateString;
        }

        const seconds = Math.floor((now - date) / 1000);

        let interval = seconds / 31536000;
        if (interval > 1) return Math.floor(interval) + "y ago";

        interval = seconds / 2592000;
        if (interval > 1) return Math.floor(interval) + "mo ago";

        interval = seconds / 86400;
        if (interval > 1) return Math.floor(interval) + "d ago";

        interval = seconds / 3600;
        if (interval > 1) return Math.floor(interval) + "h ago";

        interval = seconds / 60;
        if (interval > 1) return Math.floor(interval) + "m ago";

        return "Just now";
    }

    // Apply time ago to all elements
    const timeElements = document.querySelectorAll('.time-ago');
    timeElements.forEach(function(el) {
        const rawDate = el.getAttribute('data-time');
        if (rawDate) {
            el.textContent = timeAgo(rawDate);
        }
    });

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

    // Initialize like button states
    function initializeLikeButtons() {
        const likeButtons = document.querySelectorAll('.like-btn');
        
        likeButtons.forEach(button => {
            const isLiked = button.getAttribute('data-liked') === 'true';
            const icon = button.querySelector('svg');
            
            if (isLiked) {
                icon.classList.remove('text-gray-600', 'group-hover:text-red-500', 'group-hover:scale-110');
                icon.classList.add('text-red-500', 'fill-red-500');
                icon.setAttribute('fill', 'currentColor');
            } else {
                icon.classList.remove('text-red-500', 'fill-red-500');
                icon.classList.add('text-gray-600', 'group-hover:text-red-500', 'group-hover:scale-110');
                icon.setAttribute('fill', 'none');
            }
        });
    }

    // Initialize on load
    initializeLikeButtons();

    // Like button handler
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            const postId = this.getAttribute('data-post-id');
            const postContainer = document.getElementById('post-' + postId);
            
            if (!postContainer) {
                console.error('Error: Post container tidak ditemukan untuk ID:', postId);
                return;
            }
            
            const countSpan = postContainer.querySelector('.like-count-display');
            const icon = this.querySelector('svg');

            if (!countSpan) {
                console.error('Error: Element .like-count-display tidak ditemukan');
                return;
            }

            // Disable button
            this.disabled = true;
            this.style.opacity = '0.6';

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

                if (data.success) {
                    const isLiked = data.action === 'liked';
                    this.setAttribute('data-liked', isLiked ? 'true' : 'false');
                    
                    // Update count
                    countSpan.textContent = data.total_likes + ' Likes';
                    countSpan.classList.add('scale-110', 'text-blue-600');
                    setTimeout(() => {
                        countSpan.classList.remove('scale-110', 'text-blue-600');
                    }, 300);

                    // Update icon
                    icon.style.transition = 'all 0.3s ease';
                    
                    if (isLiked) {
                        icon.classList.remove('text-gray-600', 'group-hover:text-red-500', 'group-hover:scale-110');
                        icon.classList.add('text-red-500', 'fill-red-500', 'scale-110');
                        icon.setAttribute('fill', 'currentColor');
                        setTimeout(() => icon.classList.remove('scale-110'), 300);
                    } else {
                        icon.classList.remove('text-red-500', 'fill-red-500');
                        icon.classList.add('text-gray-600', 'group-hover:text-red-500', 'group-hover:scale-110');
                        icon.setAttribute('fill', 'none');
                    }
                } else {
                    console.error('Server error:', data.message);
                    alert(data.message || 'Gagal update like.');
                }
            } catch (err) {
                console.error('Fetch error:', err);
                alert('Terjadi kesalahan saat memproses like.');
            } finally {
                this.disabled = false;
                this.style.opacity = '1';
            }
        });
    });

    function toggleDropdown(id) {
        document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
            if (d.id !== id) d.classList.add('hidden');
        });
        const dropdown = document.getElementById(id);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    }

    document.addEventListener('click', function(event) {
        const isDropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');
        const isDropdownContent = event.target.closest('[id^="dropdown-"]');
        
        if (!isDropdownButton && !isDropdownContent) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(d => {
                d.classList.add('hidden');
            });
        }
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
</script>

<style>
/* Smooth transition untuk like button */
.like-btn svg {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.like-btn:active svg {
    transform: scale(0.9);
}

.like-btn:disabled {
    cursor: not-allowed;
}

/* Animation untuk like count */
.like-count-display {
    transition: all 0.3s ease;
}
</style>