<div id="Chat-Navigation" class="flex items-center justify-between w-full border-b border-gray-200 p-5 gap-3 bg-white flex-shrink-0">
    <div id="Group-Title" class="flex items-center flex-1 gap-3">
        <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden items-center justify-center">

            <?php if (!empty($forumByid['PATH_PHOTO'])): ?>
                <img
                    src="<?= BASEURL . '/storage/forums/photos/' . $forumByid['PATH_PHOTO'] ?>"
                    class="w-full h-full object-cover"
                    alt="photo">
            <?php else: ?>
                <span class="w-full h-full flex items-center justify-center bg-pink-500 text-white font-bold text-lg">
                    <?= strtoupper(substr($forumByid['NAME'], 0, 2)) ?>
                </span>
            <?php endif; ?>

        </div>

        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-[6px]">
                <h1 class="font-semibold text-lg truncate overflow-hidden whitespace-nowrap">
                    <?= $forumByid["NAME"] ?>
                </h1>
            </div>
            <div class="flex items-center gap-[6px]">
                <span class="font-semibold text-sm text-gray-500"><?= count($membersForum) ?> Members</span>
            </div>
        </div>
    </div>
    <ul class="flex gap-3">
        <?php if ($forumByid['OWNER_ID'] !== $_SESSION['user_id']) : ?>
            <li class="group">
                <button id="reportForumButton" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/report.png" class="size-6" alt="icon">
                </button>
            </li>
        <?php endif ?>
        <li class="group">
            <button id="infoForum" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/more.svg" class="size-6" alt="icon">
            </button>
        </li>
    </ul>
</div>

<div class="hidden fixed top-0 left-0 w-full h-screen z-[9999] bg-black/50" id="Overlay-Info">
    <div class="fixed w-[80%] sm:w-[60%] lg:w-[40%] xl:w-[35%] top-0 right-0 h-screen bg-white shadow-lg flex flex-col" id="Info-Forum">
        <p class="font-semibold text-lg text-center mt-4">Group Info</p>
        <div class="flex items-center justify-between border-b border-gray-200 py-3 px-5 flex-shrink-0">
            <div>
                <button id="btn-open-exit-forum" class="w-full h-full flex gap-1 items-center justify-center bg-white rounded-2xl p-[10px] ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/logout-grey.svg" class="size-6 flex shrink-0" alt="icon">
                    <span class="font-medium text-sm text-heyhao-secondary">Leave Forum</span>
                </button>
            </div>
            <div class="group">
                <button id="Close-Info" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-100 hover:ring-1 hover:ring-blue-600 transition-all duration-300 cursor-pointer">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/close-circle-grey.svg" class="size-6" alt="icon">
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto hide-scrollbar">
            <div class="flex flex-col items-center py-8 px-6 gap-4 border-b border-gray-200">
                <div class="flex size-[120px] rounded-full overflow-hidden items-center justify-center bg-gray-200">

                    <?php if (!empty($forumByid['PATH_PHOTO'])): ?>
                        <img
                            src="<?= BASEURL . '/storage/forums/photos/' . $forumByid['PATH_PHOTO'] ?>"
                            class="w-full h-full object-cover"
                            alt="photo">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-pink-500 text-white text-3xl font-bold">
                            <?= strtoupper(substr($forumByid['NAME'], 0, 2)) ?>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="flex flex-col items-center gap-2">
                    <div class="flex items-center justify-center gap-[6px]">
                        <p class="font-semibold text-lg"> <?= $forumByid["NAME"] ?></p>
                    </div>
                    <?php if ($forumByid['OWNER_ID'] == $_SESSION['user_id'] && !empty($forumByid['ACCESS_KEY'])): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-violet-700 bg-violet-100 px-2 py-0.5 rounded-md flex items-center gap-1.5">
                                <span>Private Key:</span>
                                <span id="access-key-text" class="font-mono"><?= $forumByid['ACCESS_KEY'] ?></span>
                            </span>

                            <button
                                id="copy-key-btn"
                                onclick="copyAccessKey('<?= $forumByid['ACCESS_KEY'] ?>')"
                                class="group relative flex items-center justify-center p-1.5 rounded-md bg-violet-100 hover:bg-violet-200 text-violet-700 transition-all duration-200 active:scale-95"
                                title="Copy Access Key">
                                <svg id="clipboard-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <svg id="check-icon" class="w-4 h-4 hidden text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
                    <div class="flex items-center gap-[6px] font-semibold text-sm">
                        <p class="flex items-center gap-1">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/profile-2user-grey.svg" class="flex size-4 shrink-0" alt="icon">
                            <span class="text-gray-500"><?= count($membersForum) ?> Members</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div id="About" class="flex flex-col gap-1 mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-lg font-semibold text-gray-800">About Forum</p>

                    <p class="text-gray-700">
                        <?= htmlspecialchars($forumByid["ABOUT"]) ?>
                    </p>

                    <p class="text-sm text-gray-500 mt-2">
                        Created at:
                        <span class="font-medium text-gray-700">
                            <?= formatDatePretty($forumByid["CREATED_AT"]) ?>
                        </span>
                    </p>
                </div>

                <!-- MEDIA PREVIEW SECTION -->
<div id="MediaPreview" 
     class="flex flex-col gap-3 mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">

    <div class="flex items-center justify-between">
        <p class="text-lg font-semibold text-gray-800">Media, Files & Links</p>

        <button id="open-media-full"
            class="text-sm font-medium text-blue-600 hover:underline">
            Lihat lainnya →
        </button>
    </div>

    <!-- PREVIEW GRID -->
    <div class="grid grid-cols-4 gap-3">

        <?php if (!empty($mediaPreview)): ?>
            <?php foreach ($mediaPreview as $m): ?>
                <div class="relative w-full h-[65px] rounded-lg overflow-hidden bg-gray-200 flex items-center justify-center cursor-pointer group">

                    <!-- FILE TYPE HANDLING -->
                    <?php if ($m['type'] === 'image'): ?>
                        <img src="<?= BASEURL . '/' . $m['path'] ?>"
                             class="w-full h-full object-cover group-hover:scale-105 transition">

                    <?php elseif ($m['type'] === 'video'): ?>
                        <div class="flex items-center justify-center w-full h-full bg-black/40">
                            <img src="<?= BASEURL ?>/src/asset/icons/video.svg" class="w-7 h-7 opacity-90">
                        </div>

                    <?php elseif ($m['type'] === 'file'): ?>
                        <div class="flex items-center justify-center w-full h-full bg-gray-300">
                            <img src="<?= BASEURL ?>/src/asset/icons/file.svg" class="w-7 h-7 opacity-80">
                        </div>

                    <?php else: ?>
                        <div class="w-full h-full bg-gray-200"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500 text-sm col-span-4 text-center">
                Belum ada media yang dikirim.
            </p>
        <?php endif; ?>

    </div>

        </div>


                <div>
                    <div id="Owner" class="flex flex-col gap-3 mt-6">
                        <p class="font-semibold leading-5">Owner</p>
                        <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 min-w-0">
                            <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                                <img src="<?= !empty($forumByid['PATH_PHOTO_OWNER'])
                                                ? BASEURL . '/storage/users/photos/' . $forumByid['PATH_PHOTO_OWNER']
                                                : BASEURL . '/src/asset/image/default.png' ?>" class="w-full h-full object-cover" alt="photo">
                            </div>
                            <div class="flex flex-col flex-1 gap-[6px] min-w-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold truncate"><?= $forumByid["OWNER_NAME"] ?></p>
                                    </div>
                                    <?php
                                    $role = $forumByid["ROLE_OWNER"];

                                    $roleClasses = [
                                        "MAHASISWA" => "bg-blue-100 text-blue-800",
                                        "ADMIN"     => "bg-red-100 text-red-800",
                                        "DOSEN"     => "bg-green-100 text-green-800",
                                        "MITRA"     => "bg-gray-100 text-gray-800",
                                        "ALUMNI"    => "bg-yellow-100 text-yellow-800"
                                    ];

                                    $colorClass = $roleClasses[$role] ?? "bg-gray-100 text-gray-800";
                                    ?>

                                    <div class="flex-shrink-0">
                                        <span class="<?= $colorClass ?> text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                            <?= htmlspecialchars($role) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="Members" class="flex flex-col gap-3 mt-6">
                        <?php
                        $membersForumFiltered = array_filter($membersForum, function ($m) use ($forumByid) {
                            return $m['USER_ID'] !== $forumByid['OWNER_ID'];
                        }); ?>
                        <p class="font-semibold leading-5">Members (<?= count($membersForumFiltered) ?>)</p>

                        <div class="flex flex-col gap-3">
                            <?php foreach ($membersForumFiltered as $member): ?>
                                <?php if ($member['USER_ID'] == $forumByid['OWNER_ID']) continue; ?>
                                <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 min-w-0">
                                    <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                                        <img src="<?= !empty($member['PATH_PHOTO'])
                                                        ? BASEURL . '/storage/users/photos/' . $member['PATH_PHOTO']
                                                        : BASEURL . '/src/asset/image/default.png' ?>" class="w-full h-full object-cover" alt="photo">
                                    </div>
                                    <div class="flex flex-col flex-1 gap-[6px] min-w-0">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold truncate"><?= $member["NAME"] ?></p>
                                            </div>
                                            <?php
                                            $role = $member["ROLE"];

                                            $roleClasses = [
                                                "MAHASISWA" => "bg-blue-100 text-blue-800",
                                                "ADMIN"     => "bg-red-100 text-red-800",
                                                "DOSEN"     => "bg-green-100 text-green-800",
                                                "MITRA"     => "bg-gray-100 text-gray-800",
                                                "ALUMNI"    => "bg-yellow-100 text-yellow-800"
                                            ];

                                            $colorClass = $roleClasses[$role] ?? "bg-gray-100 text-gray-800";
                                            ?>

                                            <div class="flex-shrink-0">
                                                <span class="<?= $colorClass ?> text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                                    <?= htmlspecialchars($role) ?>
                                                </span>
                                            </div>

                                        </div>
                                        <div class="flex font-medium text-sm text-heyhao-secondary gap-0.5 items-center">
                                            <p>Joined: </p>
                                            <p><?= $member["JOINED_AT"] ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>


                <?php if ($forumByid['OWNER_ID'] == $_SESSION['user_id']): ?>
                    <div class="my-5 flex">
                        <button
                            type="button"
                            id="btn-open-manage-members"
                            class="w-full px-5 py-2.5 mb-2text-sm font-semibold text-gray-700 bg-white border border-gray-300rounded-xl shadow-sm hover:bg-gray-100 hover:border-gray-400 active:scale-[0.98] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-lg">
                            Manage Members
                        </button>

                    </div>
                    <div class="my-5 flex gap-3">
                        <button type="button" id="btn-open-edit-forum"
                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 w-full">
                            Edit Forum
                        </button>

                        <button type="button" id="btn-open-delete-forum"
                            class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2 w-full">
                            Delete Forum
                        </button>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openMediaBtn = document.getElementById('open-media-full');
    const mediaPreviewItems = document.querySelectorAll('#MediaPreview .grid > div.relative');
    const forumId = '<?= $forum['ID'] ?? '' ?>';
    
    if (!openMediaBtn) return;

    // Click "Lihat lainnya" button
    openMediaBtn.addEventListener('click', async function(e) {
        e.preventDefault();
        await loadAndShowAllMedia(0);
    });

    // Click on individual preview items
    mediaPreviewItems.forEach((item, index) => {
        item.addEventListener('click', async function() {
            await loadAndShowAllMedia(index);
        });
    });

    async function loadAndShowAllMedia(startIndex) {
        try {
            const response = await fetch(`<?= BASEURL ?>/forums/getAllMedia/${forumId}`);
            const result = await response.json();
            
            if (result.success && result.data && result.data.length > 0) {
                openMediaModal(result.data, startIndex);
            } else {
                alert('Tidak ada media untuk ditampilkan');
            }
        } catch (error) {
            console.error('Error fetching media:', error);
            alert('Gagal memuat media');
        }
    }

    function openMediaModal(mediaItems, startIndex) {
        let currentIndex = startIndex;
        
        // Create modal
        const modal = document.createElement('div');
        modal.id = 'media-modal';
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm';
        modal.innerHTML = `
            <div class="relative w-full max-w-5xl mx-4">
                <!-- Close Button -->
                <button id="close-modal" 
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Media Container -->
                <div class="bg-white rounded-xl overflow-hidden shadow-2xl">
                    <!-- Media Display Area -->
                    <div id="media-display" class="relative bg-black flex items-center justify-center min-h-[400px] max-h-[70vh]">
                        <!-- Content will be inserted here -->
                    </div>

                    <!-- Navigation & Info -->
                    <div class="p-4 bg-gray-50">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-600">
                                <span id="current-index">${currentIndex + 1}</span> / ${mediaItems.length}
                            </span>
                            <a id="download-btn" download 
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                                Download
                            </a>
                        </div>

                        <!-- Thumbnails -->
                        <div id="thumbnail-strip" class="flex gap-2 overflow-x-auto pb-2">
                            <!-- Thumbnails will be inserted here -->
                        </div>
                    </div>
                </div>

                <!-- Navigation Arrows -->
                ${mediaItems.length > 1 ? `
                    <button id="prev-btn" 
                        class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button id="next-btn" 
                        class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                ` : ''}
            </div>
        `;

        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';

        // Display initial media
        displayMedia(currentIndex);
        createThumbnails();

        // Event listeners
        document.getElementById('close-modal').addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        if (mediaItems.length > 1) {
            document.getElementById('prev-btn').addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + mediaItems.length) % mediaItems.length;
                displayMedia(currentIndex);
            });
            document.getElementById('next-btn').addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % mediaItems.length;
                displayMedia(currentIndex);
            });
        }

        // Keyboard navigation
        document.addEventListener('keydown', handleKeyPress);

        function handleKeyPress(e) {
            if (e.key === 'Escape') closeModal();
            if (e.key === 'ArrowLeft' && mediaItems.length > 1) {
                currentIndex = (currentIndex - 1 + mediaItems.length) % mediaItems.length;
                displayMedia(currentIndex);
            }
            if (e.key === 'ArrowRight' && mediaItems.length > 1) {
                currentIndex = (currentIndex + 1) % mediaItems.length;
                displayMedia(currentIndex);
            }
        }

        function displayMedia(index) {
            const media = mediaItems[index];
            const display = document.getElementById('media-display');
            const downloadBtn = document.getElementById('download-btn');
            const currentIndexSpan = document.getElementById('current-index');

            currentIndexSpan.textContent = index + 1;
            const mediaUrl = `<?= BASEURL ?>/${media.path}`;
            downloadBtn.href = mediaUrl;
            downloadBtn.download = media.original_name || media.file;

            if (media.type === 'image') {
                display.innerHTML = `
                    <img src="${mediaUrl}" 
                         class="max-w-full max-h-[70vh] object-contain" 
                         alt="${media.original_name || 'Media'}">
                `;
            } else if (media.type === 'video') {
                display.innerHTML = `
                    <video controls class="max-w-full max-h-[70vh]">
                        <source src="${mediaUrl}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                `;
            } else {
                display.innerHTML = `
                    <div class="text-center text-white p-8">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-lg font-medium">${media.original_name || media.file}</p>
                        <p class="text-sm opacity-70 mt-2">Klik download untuk melihat file</p>
                    </div>
                `;
            }

            updateActiveThumbnail(index);
        }

        function createThumbnails() {
            const strip = document.getElementById('thumbnail-strip');
            strip.innerHTML = mediaItems.map((media, idx) => {
                const mediaUrl = `<?= BASEURL ?>/${media.path}`;
                let thumbContent = '';
                
                if (media.type === 'image') {
                    thumbContent = `<img src="${mediaUrl}" class="w-full h-full object-cover">`;
                } else if (media.type === 'video') {
                    thumbContent = `
                        <div class="w-full h-full bg-black/40 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    `;
                } else {
                    thumbContent = `
                        <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    `;
                }

                return `
                    <div data-index="${idx}" 
                         class="thumbnail flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden cursor-pointer border-2 border-transparent hover:border-blue-400 transition ${idx === currentIndex ? 'border-blue-600' : ''}">
                        ${thumbContent}
                    </div>
                `;
            }).join('');

            strip.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const index = parseInt(this.dataset.index);
                    currentIndex = index;
                    displayMedia(currentIndex);
                });
            });
        }

        function updateActiveThumbnail(index) {
            document.querySelectorAll('.thumbnail').forEach((thumb, idx) => {
                if (idx === index) {
                    thumb.classList.add('border-blue-600');
                    thumb.classList.remove('border-transparent');
                    thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                } else {
                    thumb.classList.remove('border-blue-600');
                    thumb.classList.add('border-transparent');
                }
            });
        }

        function closeModal() {
            document.removeEventListener('keydown', handleKeyPress);
            modal.remove();
            document.body.style.overflow = '';
        }
    }
});

    function copyAccessKey(key) {
        const clipboardIcon = document.getElementById('clipboard-icon');
        const checkIcon = document.getElementById('check-icon');
        const button = document.getElementById('copy-key-btn');

        navigator.clipboard.writeText(key).then(() => {
            clipboardIcon.classList.add('hidden');
            checkIcon.classList.remove('hidden');
            button.classList.add('bg-green-100');
            button.classList.remove('bg-violet-100');

            setTimeout(() => {
                clipboardIcon.classList.remove('hidden');
                checkIcon.classList.add('hidden');
                button.classList.remove('bg-green-100');
                button.classList.add('bg-violet-100');
            }, 1500);
        }).catch(err => {
            const textArea = document.createElement('textarea');
            textArea.value = key;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.select();

            try {
                document.execCommand('copy');
                clipboardIcon.classList.add('hidden');
                checkIcon.classList.remove('hidden');
                button.classList.add('bg-green-100');
                button.classList.remove('bg-violet-100');

                setTimeout(() => {
                    clipboardIcon.classList.remove('hidden');
                    checkIcon.classList.add('hidden');
                    button.classList.remove('bg-green-100');
                    button.classList.add('bg-violet-100');
                }, 1500);
            } catch (err) {
                console.error('Failed to copy:', err);
            }

            document.body.removeChild(textArea);
        });
    }
</script>

<?php require_once 'app/views/components/forums/modalEditForum.php'; ?>
<?php require_once 'app/views/components/forums/modalDeleteForum.php'; ?>
<?php require_once 'app/views/components/forums/modalExitForum.php'; ?>
<?php require_once 'app/views/components/forums/modalReportForum.php'; ?>
<?php require_once 'app/views/components/forums/modalManageMember.php'; ?>