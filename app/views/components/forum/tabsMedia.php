<div class="tab-content hidden" data-content="media">

    <?php
    $currentRole = $_SESSION['role'] ?? '';
    $canViewMedia = $isMember || $currentRole === 'ADMIN';
    ?>

    <?php if ($canViewMedia): ?>

        <div id="media-loading" class="bg-white rounded-lg shadow p-8 text-center">
            <div class="animate-pulse flex flex-col items-center">
                <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
                <p class="text-gray-600">Loading Media...</p>
            </div>
        </div>

        <div id="media-grid" class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-1 hidden bg-white p-5 rounded-lg drop-shadow">
        </div>

        <div id="media-empty" class="bg-white rounded-lg shadow p-8 text-center hidden">
            <div class="text-6xl mb-4">📷</div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">No Media</h3>
            <p class="text-gray-600">No photos have been shared yet.</p>
        </div>

    <?php else: ?>

        <div class="bg-white rounded-lg shadow p-10 text-center border border-gray-200">
            <div class="text-6xl mb-4 grayscale opacity-50">🔒</div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Media is Locked</h3>
            <p class="text-gray-600 mb-6">
                You need to be a member of this forum to view shared photos.
            </p>
            <button onclick="joinForum('<?= $forumById['ID'] ?>')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-bold shadow-md transition-all">
                Join Forum to View
            </button>
        </div>

    <?php endif; ?>

</div>

<div id="galleryModal" class="fixed inset-0 z-[99999] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center font-sans md:p-5">

    <button onclick="GalleryManager.close()" class="absolute top-4 right-4 z-50 text-white hover:text-gray-300 p-2">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <div class="flex flex-col md:flex-row w-full h-full md:max-w-6xl md:h-[85vh] bg-black md:bg-gray-900 md:rounded-lg overflow-hidden shadow-2xl">

        <div class="flex-1 bg-black flex items-center justify-center relative overflow-hidden bg-checkerboard">
            <img id="modal-img-display" src="" class="max-w-full max-h-full object-contain">
        </div>

        <div class="w-full md:w-[350px] bg-white flex flex-col h-[30vh] md:h-full border-l border-gray-700">

            <div class="p-4 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
                <img id="modal-user-pic" src="" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <h4 id="modal-user-name" class="font-bold text-gray-900 text-sm">Username</h4>
                        <span id="modal-user-role" class="text-xs font-medium px-2 py-0.5 rounded-sm"></span>
                    </div>
                    <span id="modal-date" class="text-xs text-gray-500 block">Date</span>
                </div>
            </div>

            <div class="p-4 flex-1 overflow-y-auto">
                <p id="modal-caption" class="text-sm text-gray-800 whitespace-pre-wrap leading-relaxed">
                </p>
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50 flex flex-col gap-2">
                <a id="modal-link" href="#" target="_blank" class="flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded transition shadow-sm">
                    View Original Post
                </a>

                <a id="modal-download" href="#" download class="flex items-center justify-center w-full bg-white hover:bg-gray-100 text-gray-700 border border-gray-300 text-sm font-medium py-2 rounded transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download Image
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    const GalleryManager = {
        forumId: "<?= isset($forumById['ID']) ? $forumById['ID'] : '' ?>",
        baseUrl: '<?= defined('BASEURL') ? BASEURL : '' ?>',
        storagePath: '<?= defined('BASEURL') ? BASEURL : '' ?>/storage/forums/topics',
        userPhotoPath: '<?= defined('BASEURL') ? BASEURL : '' ?>/storage/users/photos',

        data: [],
        loaded: false,

        init: function() {
            if (!this.forumId) return;

            const mediaTabBtn = document.querySelector('[data-tab="media"]');
            if (mediaTabBtn) {
                mediaTabBtn.addEventListener('click', () => {
                    if (!this.loaded) this.loadData();
                });
            } else {
                const activeTab = document.querySelector('.tab-content:not(.hidden)');
                if (activeTab && activeTab.dataset.content === 'media') {
                    this.loadData();
                }
            }
        },

        loadData: async function() {
            const loading = document.getElementById('media-loading');
            const grid = document.getElementById('media-grid');
            const empty = document.getElementById('media-empty');

            if (!grid) {
                console.log('User has no access to media grid. Gallery script stopped.');
                return;
            }

            if (loading) loading.classList.remove('hidden');
            if (grid) grid.classList.add('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await fetch(`${this.baseUrl}/forum/getAssets?forum_id=${this.forumId}`);
                const result = await response.json();

                this.loaded = true;

                if (result.status === 'success' && result.data && result.data.length > 0) {
                    this.data = result.data.filter(item => item.MEDIA_TYPE === 'IMAGE');

                    if (this.data.length === 0) {
                        if (loading) loading.classList.add('hidden');
                        if (empty) empty.classList.remove('hidden');
                        return;
                    }

                    this.renderGrid();
                    if (loading) loading.classList.add('hidden');
                    if (grid) grid.classList.remove('hidden');
                } else {
                    if (loading) loading.classList.add('hidden');
                    if (empty) empty.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Gallery Error:', error);
                if (loading) loading.innerHTML = '<p class="text-red-500">Failed to load gallery.</p>';
            }
        },

        renderGrid: function() {
            const grid = document.getElementById('media-grid');
            if (!grid) return;

            grid.innerHTML = '';

            this.data.forEach((item, index) => {
                const imgSrc = `${this.storagePath}/${item.MEDIA_PATH}`;

                const div = document.createElement('div');
                div.className = 'group relative aspect-square bg-gray-200 cursor-pointer overflow-hidden border border-gray-300 rounded';
                div.onclick = () => this.openModal(index);

                const img = document.createElement('img');
                img.src = imgSrc;
                img.alt = "Media";
                img.className = "w-full h-full object-cover transition-transform duration-300 group-hover:scale-110";
                img.loading = "lazy";

                img.onerror = function() {
                    console.error("Failed to load grid image:", imgSrc);
                    this.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2'%3E%3Crect x='3' y='3' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='3' y1='3' x2='21' y2='21'/%3E%3C/svg%3E";
                    this.className = "w-full h-full object-contain p-4 opacity-50";
                };

                const overlay = document.createElement('div');
                overlay.className = "absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300";

                div.appendChild(img);
                div.appendChild(overlay);
                grid.appendChild(div);
            });
        },

        openModal: function(index) {
            const item = this.data[index];
            const modal = document.getElementById('galleryModal');
            if (!modal) return;

            const fullImgPath = `${this.storagePath}/${item.MEDIA_PATH}`;

            const mainImg = document.getElementById('modal-img-display');
            if (mainImg) mainImg.src = fullImgPath;

            const userPic = document.getElementById('modal-user-pic');
            if (userPic) {
                userPic.src = item.PATH_PHOTO ? `${this.userPhotoPath}/${item.PATH_PHOTO}` : `${this.baseUrl}/src/asset/image/default.png`;
            }

            const userName = document.getElementById('modal-user-name');
            if (userName) userName.textContent = item.USERNAME || 'Unknown';

            const modalDate = document.getElementById('modal-date');
            if (modalDate) modalDate.textContent = item.FORMATTED_DATE || '';

            const modalCaption = document.getElementById('modal-caption');
            if (modalCaption) {
                modalCaption.innerHTML = '';

                const fullText = item.TOPIC_CONTENT || '';
                const maxLength = 200;

                if (fullText.length > maxLength) {
                    const textNode = document.createTextNode(fullText.substring(0, maxLength) + '... ');
                    modalCaption.appendChild(textNode);

                    const seeMoreLink = document.createElement('a');
                    seeMoreLink.href = `${this.baseUrl}/forum/topic/${item.TOPIC_ID}`;
                    seeMoreLink.textContent = 'See more';
                    seeMoreLink.className = 'text-blue-600 font-medium hover:underline ml-1 cursor-pointer';

                    modalCaption.appendChild(seeMoreLink);
                } else {
                    modalCaption.textContent = fullText;
                }
            }

            this.setupRoleBadge(item);

            const linkBtn = document.getElementById('modal-link');
            if (linkBtn) linkBtn.href = `${this.baseUrl}/forum/topic/${item.TOPIC_ID}`;

            const downloadBtn = document.getElementById('modal-download');
            if (downloadBtn) {
                downloadBtn.href = fullImgPath;
                downloadBtn.setAttribute('download', item.ORIGINAL_FILENAME || `image_${item.MEDIA_ID}.jpg`);
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        },

        setupRoleBadge: function(item) {
            const roleElement = document.getElementById('modal-user-role');
            if (!roleElement) return;

            const roleClasses = {
                'MAHASISWA': 'bg-blue-100 text-blue-800',
                'ADMIN': 'bg-red-100 text-red-800',
                'DOSEN': 'bg-green-100 text-green-800',
                'MITRA': 'bg-gray-100 text-gray-800',
                'ALUMNI': 'bg-yellow-100 text-yellow-800'
            };

            const userRole = item.ROLE || item.USER_ROLE || item.ROLE_NAME || '';

            if (userRole) {
                const roleClass = roleClasses[userRole] || 'bg-gray-100 text-gray-800';
                roleElement.className = `text-xs font-medium px-2 py-0.5 rounded-sm ${roleClass}`;
                roleElement.textContent = userRole;
                roleElement.style.display = 'inline-block';
            } else {
                roleElement.style.display = 'none';
            }
        },

        close: function() {
            const modal = document.getElementById('galleryModal');
            if (modal) modal.classList.add('hidden');

            const mainImg = document.getElementById('modal-img-display');
            if (mainImg) mainImg.src = '';

            document.body.style.overflow = '';
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => GalleryManager.init());
    } else {
        GalleryManager.init();
    }
</script>