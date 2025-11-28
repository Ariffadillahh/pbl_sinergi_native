<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sinergi</title>
</head>

<body>
    <div class="bg-white rounded-xl p-4 drop-shadow">
        <div class="md:flex md:justify-between md:items-center">
            <form class="md:w-md w-full" action="<?= BASEURL ?>/dashboard/anggota" method="GET">
                <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="search" id="default-search" name="q"
                        class="block w-full p-4 ps-10 text-sm text-gray-900 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Search by name or NIM...." autocomplete="off"
                        value="<?= htmlspecialchars($data['keyword']) ?>" />

                    <button type="submit" class="hidden">Search</button>
                </div>
            </form>

            <button id="open-modal-btn"
                class="text-white mt-2 md:mt-0 w-full md:w-fit bg-blue-900 focus:ring-4 focus:outline-none cursor-pointer font-medium rounded-lg text-sm px-4 py-2 text-center h-fit">
                Add Member
            </button>
        </div>

        <div class="relative overflow-x-auto mt-5">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">NIM/NIP/PERSONAL NUMBER</th>
                        <th scope="col" class="px-6 py-3">Prodi</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['users'])) : ?>
                        <tr class="bg-white border-b border-gray-200">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No member data found.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($data['users'] as $user) : ?>
                            <tr class="bg-white border-b border-gray-200">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    <?= htmlspecialchars($user['FULL_NAME']) ?>
                                </th>
                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($user['PERSONAL_NUMBER'])
                                    ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($user['PRODI'])
                                    ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $role = htmlspecialchars($user['ROLE']);
                                    $badgeClass = '';

                                    switch ($role) {
                                        case 'MAHASISWA':
                                            $badgeClass = 'bg-blue-100 text-blue-800';
                                            break;
                                        case 'DOSEN':
                                            $badgeClass = 'bg-green-100 text-green-800';
                                            break;
                                        case 'ADMIN':
                                            $badgeClass = 'bg-red-100 text-red-800';
                                            break;
                                        case 'ALUMNI':
                                            $badgeClass = 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'MITRA':
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            break;
                                        default:
                                            $badgeClass = 'bg-slate-100 text-slate-800';
                                            break;
                                    }
                                    ?>

                                    <span class="px-3 py-1 text-sm font-semibold rounded-full <?= $badgeClass ?>">
                                        <?= $role ?>
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <button type="button"
                                        class="btn-edit-user"
                                        data-id="<?= htmlspecialchars($user['ID']) ?>"
                                        data-name="<?= htmlspecialchars($user['FULL_NAME']) ?>"
                                        data-username="<?= htmlspecialchars($user['USERNAME']) ?>"
                                        data-role="<?= htmlspecialchars($user['ROLE']) ?>">

                                        <svg class="w-5 h-5 text-blue-500 hover:text-blue-700" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center md:flex-row md:justify-between md:items-center mt-6 p-3">
            <?php
            $startItem = ($data['currentPage'] - 1) * $data['limit'] + 1;
            $endItem = min($startItem + $data['limit'] - 1, $data['totalResults']);
            ?>

            <p class="text-sm text-gray-600 font-sans text-center md:text-left mb-3 md:mb-0">
                <?php if ($data['totalResults'] > 0) : ?>
                    Showing <?= $startItem ?> to <?= $endItem ?> of <?= $data['totalResults'] ?> results
                <?php else : ?>
                    Showing 0 results
                <?php endif; ?>
            </p>

            <?php if ($data['totalPages'] > 1) : ?>
                <div class="flex flex-wrap justify-center gap-1">
                    <?php
                    $currentPage = $data['currentPage'];
                    $totalPages = $data['totalPages'];
                    $keyword = $data['keyword'];

                    function page_url($page, $keyword)
                    {
                        $params = [];
                        if (!empty($keyword)) {
                            $params['q'] = $keyword;
                        }
                        if ($page > 1) {
                            $params['page'] = $page;
                        }
                        return BASEURL . '/dashboard/anggota' . (empty($params) ? '' : '?' . http_build_query($params));
                    }

                    $baseClass = "w-10 h-10 flex items-center justify-center rounded-full border border-gray-200";
                    $activeClass = "bg-blue-900 text-white";
                    $disabledClass = "bg-gray-100 text-gray-400 cursor-not-allowed";
                    ?>

                    <a href="<?= ($currentPage > 1) ? page_url($currentPage - 1, $keyword) : '#' ?>"
                        class="<?= $baseClass ?> <?= ($currentPage <= 1) ? $disabledClass : '' ?>">
                        <span class="sr-only">Previous</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </a>

                    <?php
                    $maxPages = 4;
                    $startPage = 1;
                    $endPage = $totalPages;

                    if ($totalPages > $maxPages) {
                        $startPage = max(1, $currentPage - floor($maxPages / 2));
                        $endPage = $startPage + $maxPages - 1;

                        if ($endPage > $totalPages) {
                            $endPage = $totalPages;
                            $startPage = $endPage - $maxPages + 1;
                        }
                    }

                    for ($i = $startPage; $i <= $endPage; $i++) :
                    ?>
                        <a href="<?= page_url($i, $keyword) ?>"
                            class="<?= $baseClass ?> <?= ($i == $currentPage) ? $activeClass : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <a href="<?= ($currentPage < $totalPages) ? page_url($currentPage + 1, $keyword) : '#' ?>"
                        class="<?= $baseClass ?> <?= ($currentPage >= $totalPages) ? $disabledClass : '' ?>">
                        <span class="sr-only">Next</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require_once 'app/views/components/Admin/modalAddUser.php'; ?>
    <?php require_once 'app/views/components/Admin/modalEditUser.php'; ?>

</body>

</html>