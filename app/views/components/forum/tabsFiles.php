<div class="tab-content hidden" data-content="files">

    <?php
    $currentRole = $_SESSION['role'] ?? '';
    $canViewFiles = $isMember || $currentRole === 'ADMIN';
    ?>

    <?php if ($canViewFiles): ?>

        <div id="files-loading" class="bg-white rounded-lg shadow p-8 text-center">
            <div class="animate-pulse flex flex-col items-center">
                <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
                <p class="text-gray-600">Loading files...</p>
            </div>
        </div>

        <div id="files-list" class="space-y-3 hidden">
        </div>

        <div id="files-empty" class="bg-white rounded-lg shadow p-8 text-center hidden">
            <div class="text-6xl mb-4">📁</div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">No Files Found</h3>
            <p class="text-gray-600">No documents have been shared in this group yet.</p>
        </div>

    <?php else: ?>

        <div class="bg-white rounded-lg shadow p-10 text-center border border-gray-200">
            <div class="text-6xl mb-4 grayscale opacity-50">🔒</div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Files are Locked</h3>
            <p class="text-gray-600 mb-6">
                You need to be a member of this forum to download shared documents.
            </p>
            <button onclick="joinForum('<?= $forumById['ID'] ?>')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full font-bold shadow-md transition-all">
                Join Forum to Access
            </button>
        </div>

    <?php endif; ?>

</div>

<script>
    (function() {
        const FORUM_ID = "<?= $forumById['ID'] ?>";
        const BASE_URL = '<?= defined('BASEURL') ? BASEURL : '' ?>';
        const STORAGE_PATH = BASE_URL + '/storage/forums/topics';

        let allFiles = [];
        let hasLoadedFiles = false;

        function initFilesTab() {
            if (!FORUM_ID) return;

            const filesTabBtn = document.querySelector('[data-tab="files"]');

            if (filesTabBtn) {
                filesTabBtn.addEventListener('click', function() {
                    if (!hasLoadedFiles) {
                        loadFiles();
                    }
                });
            } else {
                const activeTab = document.querySelector('.tab-content[data-content="files"]:not(.hidden)');
                if (activeTab) {
                    loadFiles();
                }
            }
        }

        async function loadFiles() {
            if (hasLoadedFiles) return;

            const list = document.getElementById('files-list');

            if (!list) {
                console.log('User has no access to files list.');
                return;
            }

            const loading = document.getElementById('files-loading');
            const empty = document.getElementById('files-empty');

            if (loading) loading.classList.remove('hidden');
            if (list) list.classList.add('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                const response = await fetch(BASE_URL + '/forum/getAssets?forum_id=' + FORUM_ID);
                const result = await response.json();

                hasLoadedFiles = true;

                if (result.status === 'success' && result.data && result.data.length > 0) {

                    allFiles = result.data.filter(function(item) {
                        return item.MEDIA_TYPE === 'FILE';
                    });

                    if (allFiles.length === 0) {
                        if (loading) loading.classList.add('hidden');
                        if (empty) empty.classList.remove('hidden');
                        return;
                    }

                    const filesByTopic = {};
                    allFiles.forEach(function(file) {
                        if (!filesByTopic[file.TOPIC_ID]) {
                            filesByTopic[file.TOPIC_ID] = {
                                topic: file,
                                files: []
                            };
                        }
                        filesByTopic[file.TOPIC_ID].files.push(file);
                    });

                    displayList(filesByTopic);

                    if (loading) loading.classList.add('hidden');
                    if (list) list.classList.remove('hidden');

                } else {
                    if (loading) loading.classList.add('hidden');
                    if (empty) empty.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading files:', error);
                if (loading) {
                    loading.innerHTML = '<div class="text-red-500">Failed to load files.</div>';
                }
            }
        }

        function displayList(filesByTopic) {
            const list = document.getElementById('files-list');
            if (!list) return;

            list.innerHTML = '';

            Object.values(filesByTopic).forEach(function(group) {
                const topicDiv = document.createElement('div');
                topicDiv.className = 'bg-white rounded-lg shadow p-6 mb-4 border border-gray-100';

                const header = document.createElement('div');
                header.className = 'flex items-center mb-4 pb-4 border-b border-gray-200';

                const avatarPath = group.topic.PATH_PHOTO ? (BASE_URL + '/storage/users/photos/' + group.topic.PATH_PHOTO) : (BASE_URL + '/src/asset/image/default.png');

                const avatarImg = document.createElement('img');
                avatarImg.src = avatarPath;
                avatarImg.className = 'w-10 h-10 rounded-full mr-3 object-cover border border-blue-600';

                avatarImg.onerror = function() {
                    this.src = BASE_URL + '/src/asset/image/default.png';
                };

                const userInfo = document.createElement('div');
                userInfo.className = 'flex-1';

                const userName = document.createElement('h4');
                userName.className = 'font-bold text-gray-800';
                userName.textContent = group.topic.USERNAME || 'Unknown User';

                const date = document.createElement('p');
                date.className = 'text-sm text-gray-500';
                date.textContent = group.topic.FORMATTED_DATE || '';

                userInfo.appendChild(userName);
                userInfo.appendChild(date);
                header.appendChild(avatarImg);
                header.appendChild(userInfo);
                topicDiv.appendChild(header);

                if (group.topic.TOPIC_CONTENT) {
                    const content = document.createElement('p');
                    content.className = 'text-gray-700 mb-4 text-sm leading-relaxed whitespace-pre-wrap break-words';

                    const fullText = group.topic.TOPIC_CONTENT;
                    const maxLength = 200;

                    if (fullText.length > maxLength) {
                        content.textContent = fullText.substring(0, maxLength) + '... ';

                        // Buat element Link 'See more'
                        const seeMoreLink = document.createElement('a');
                        seeMoreLink.href = BASE_URL + '/forum/topic/' + group.topic.TOPIC_ID;
                        seeMoreLink.textContent = 'See more';
                        seeMoreLink.className = 'text-blue-600 font-medium hover:underline ml-1 cursor-pointer';

                        // Masukkan link ke dalam paragraf
                        content.appendChild(seeMoreLink);
                    } else {
                        content.textContent = fullText;
                    }

                    topicDiv.appendChild(content);
                }

                group.files.forEach(function(file) {
                    const fileDiv = createFileItem(file);
                    topicDiv.appendChild(fileDiv);
                });

                list.appendChild(topicDiv);
            });
        }

        function createFileItem(file) {
            const fileDiv = document.createElement('a');
            fileDiv.href = STORAGE_PATH + '/' + file.MEDIA_PATH;
            fileDiv.target = '_blank';

            const fileName = file.ORIGINAL_FILENAME || file.MEDIA_PATH || 'Document';
            fileDiv.download = fileName;

            fileDiv.className = 'flex items-center p-3 hover:bg-gray-50 rounded-lg border border-gray-200 hover:border-blue-500 mb-2 transition group';

            let extension = 'FILE';
            if (fileName.includes('.')) {
                extension = fileName.split('.').pop().toUpperCase();
            }

            const iconDiv = document.createElement('div');
            iconDiv.className = 'w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0';

            const iconText = document.createElement('span');
            iconText.className = 'text-blue-600 font-bold text-xs';
            iconText.textContent = extension.substring(0, 4);
            iconDiv.appendChild(iconText);

            const infoDiv = document.createElement('div');
            infoDiv.className = 'flex-1 min-w-0';

            const nameEl = document.createElement('p');
            nameEl.className = 'font-semibold text-gray-800 truncate text-sm';
            nameEl.textContent = fileName;

            const dateEl = document.createElement('p');
            dateEl.className = 'text-xs text-gray-500';
            dateEl.textContent = 'Click to download';

            infoDiv.appendChild(nameEl);
            infoDiv.appendChild(dateEl);

            const downloadIcon = document.createElement('div');
            downloadIcon.innerHTML = `<svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>`;

            fileDiv.appendChild(iconDiv);
            fileDiv.appendChild(infoDiv);
            fileDiv.appendChild(downloadIcon);

            return fileDiv;
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initFilesTab);
        } else {
            initFilesTab();
        }
    })();
</script>