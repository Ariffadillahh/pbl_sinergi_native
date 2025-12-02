<?php
function getPaginationLink($page, $tabName, $currentParams)
{
    $params = $currentParams;
    $params['tab'] = $tabName;

    if ($tabName == 'my-forums') {
        $params['my_page'] = $page;
    } else {
        $params['all_page'] = $page;
    }
    return '?' . http_build_query($params);
}

function getTabLink($tabName, $currentParams)
{
    $params = $currentParams;
    $params['tab'] = $tabName;
    return '?' . http_build_query($params);
}

$currentParams = [
    'my_search' => $mySearch,
    'my_page' => $myPage,
    'all_search' => $allSearch,
    'all_page' => $allPage
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum Management</title>
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="">
        <div class="bg-white rounded-xl shadow">
            <div class="border-b border-gray-200">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center px-4 lg:px-6 py-4 gap-4">

                    <div class="flex gap-2 w-full lg:w-auto">
                        <a href="<?= getTabLink('my-forums', $currentParams) ?>" id="tab-my-forums"
                            class="flex-1 lg:flex-none px-6 py-2 font-semibold rounded-lg transition-colors 
                            <?= $activeTab == 'my-forums' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
                            My Forum
                        </a>

                        <a href="<?= getTabLink('all-forums', $currentParams) ?>" id="tab-all-forums"
                            class="flex-1 lg:flex-none px-6 py-2 font-semibold rounded-lg transition-colors 
                            <?= $activeTab == 'all-forums' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
                            Users Forum
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                        <button onclick="openCreateForumModal()"
                            class="flex-1 sm:flex-none text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 whitespace-nowrap cursor-pointer">
                            Add Forum
                        </button>

                        <button onclick="openJoinForumModal()"
                            class="flex-1 sm:flex-none text-white bg-green-700 hover:bg-green-600 font-medium rounded-lg text-sm px-4 py-2 whitespace-nowrap cursor-pointer">
                            Add User to forum
                        </button>
                    </div>

                </div>
            </div>

            <!-- Tab Content: Forum Saya -->
            <?php require_once 'app/views/components/admin/forums/tabsMyForums.php'; ?>


            <!-- Tab Content: All Forums -->
            <?php require_once 'app/views/components/admin/forums/tabsAllForums.php'; ?>

        </div>
    </div>

    <?php require_once 'app/views/components/admin/forums/modalAddForum.php'; ?>
    <?php require_once 'app/views/components/admin/forums/modalJoinForum.php'; ?>

    <script>
        function handleSearch(inputId, tabName, searchParamName, pageParamName) {
            document.getElementById(inputId).addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const searchTerm = e.target.value;
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabName);
                    url.searchParams.set(searchParamName, searchTerm);
                    url.searchParams.set(pageParamName, '1');
                    if (tabName === 'my-forums') {
                        url.searchParams.delete('all_search');
                        url.searchParams.delete('all_page');
                    } else {
                        url.searchParams.delete('my_search');
                        url.searchParams.delete('my_page');
                    }
                    window.location = url.toString();
                }
            });
        }
        handleSearch('search-my-forum', 'my-forums', 'my_search', 'my_page');
        handleSearch('search-all-forum', 'all-forums', 'all_search', 'all_page');

        function generateKey() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let key = '';
            for (let i = 0; i < 16; i++) {
                if (i > 0 && i % 4 === 0) key += '-';
                key += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return key;
        }

        function togglePrivateKey() {
            const isPrivate = document.getElementById('isPrivate').checked;
            const keySection = document.getElementById('privateKeySection');
            if (isPrivate) {
                keySection.classList.remove('hidden');
                document.getElementById('privateKey').value = generateKey();
            } else {
                keySection.classList.add('hidden');
                document.getElementById('privateKey').value = '';
            }
        }

        function copyKey() {
            const keyInput = document.getElementById('privateKey');
            keyInput.select();
            document.execCommand('copy');
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML =
                '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
            setTimeout(() => {
                btn.innerHTML = originalHTML;
            }, 2000);
        }

        function copyKeyAdmin(key) {
            const textarea = document.createElement('textarea');
            textarea.value = key;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML =
                '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
            setTimeout(() => {
                btn.innerHTML = originalHTML;
            }, 1500);
        }

        function openCreateForumModal() {
            document.getElementById('createForumModal').classList.remove('hidden');
            document.getElementById('createForumModal').classList.add('flex');
        }

        function closeCreateForumModal() {
            document.getElementById('createForumModal').classList.add('hidden');
            document.getElementById('createForumModal').classList.remove('flex');
            document.getElementById('forumName').value = '';
            document.getElementById('forumAbout').value = '';
            document.getElementById('isPrivate').checked = false;
            document.getElementById('privateKeySection').classList.add('hidden');
            document.getElementById('photoPreview').innerHTML = `
                <svg class="w-12 h-12 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                </svg>
            `;
        }

        function openJoinForumModal() {
            document.getElementById('joinForumModal').classList.remove('hidden');
            document.getElementById('joinForumModal').classList.add('flex');
        }

        function closeJoinForumModal() {
            document.getElementById('joinForumModal').classList.add('hidden');
            document.getElementById('joinForumModal').classList.remove('flex');
            document.getElementById('memberSelect').value = '';
            document.getElementById('forumSelect').value = '';
        }

        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                    `;
                };
                reader.readAsDataURL(file);
            }
        }

        window.onclick = function(event) {
            const createModal = document.getElementById('createForumModal');
            const joinModal = document.getElementById('joinForumModal');
            if (event.target === createModal) {
                closeCreateForumModal();
            }
            if (event.target === joinModal) {
                closeJoinForumModal();
            }
        }
    </script>
</body>

</html>