<?php
function getPaginationLink($page, $tabName, $currentParams)
{
    $params = $currentParams;
    $params['tab'] = $tabName;

    if ($tabName == 'my-groups') {
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
    <title>Group Management</title>
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="">
        <div class="bg-white rounded-xl shadow">
            <div class="border-b border-gray-200">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center px-4 lg:px-6 py-4 gap-4">

                    <div class="flex gap-2 w-full lg:w-auto">
                        <a href="<?= getTabLink('my-groups', $currentParams) ?>" id="tab-my-groups"
                            class="flex-1 lg:flex-none px-6 py-2 font-semibold rounded-lg transition-colors 
                           <?= $activeTab == 'my-groups' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
                            My Groups
                        </a>

                        <a href="<?= getTabLink('all-groups', $currentParams) ?>" id="tab-all-groups"
                            class="flex-1 lg:flex-none px-6 py-2 font-semibold rounded-lg transition-colors 
                           <?= $activeTab == 'all-groups' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' ?>">
                            Users Group
                        </a>
                    </div>
                </div>
            </div>

            <?php require_once 'app/views/components/Admin/groups/tabsMyGroup.php'; ?>
            <?php require_once 'app/views/components/Admin/groups/tabsUserGroups.php'; ?>

        </div>
    </div>

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

                    // Reset search parameter tab sebelah agar bersih
                    if (tabName === 'my-groups') {
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

        // Perhatikan ID input dan nama Tab disesuaikan
        handleSearch('search-my-group', 'my-groups', 'my_search', 'my_page');
        handleSearch('search-all-group', 'all-groups', 'all_search', 'all_page');
    </script>
</body>

</html>