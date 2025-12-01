<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sinergi</title>
</head>

<body>
    <div class="bg-white p-4 rounded-xl drop-shadow">
        <div class="relative overflow-x-auto mt-5">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Report Count</th>
                        <th scope="col" class="px-6 py-3">Post Owner</th>
                        <th scope="col" class="px-6 py-3">Post Link</th>
                        <th scope="col" class="px-6 py-3">Reason</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($report)) : ?>
                        <tr class="bg-white border-b border-gray-200">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Report Not Found
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($report as $data) : ?>
                            <tr class="bg-white border-b border-gray-200">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    <?= htmlspecialchars($data['TOTAL_REPORTS']) ?>
                                </th>
                                <td class="px-6 py-4">
                                    <?= htmlspecialchars($data['POST_OWNER_NAME']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="<?= BASEURL ?>/homepage/reply/<?= htmlspecialchars($data['POST_ID']) ?>" class="text-blue-600 underline font-semibold">POST LINK</a>
                                </td>
                                <td class="px-6 py-4">
                                    <button
                                        class="reason-modal-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm transition"
                                        data-id="<?= htmlspecialchars($data['POST_ID']) ?>">
                                        See Reason
                                    </button>
                                </td>

                                <td class="px-6 py-4 flex items-center space-x-3">
                                    <button type="button"
                                        class="btn-warning flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 hover:bg-yellow-200 transition"
                                        title="Peringati"
                                        data-id="<?= htmlspecialchars($data['POST_ID']) ?>"
                                        data-name="<?= htmlspecialchars($data['POST_OWNER_NAME']) ?>"
                                        data-owner-id="<?= htmlspecialchars($data['USER_ID']) ?>"
                                        data-title="Peringati Postingan">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="orange"
                                            class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v2m0 4h.01M10.29 3.86l-7.05 12.2A1 1 0 004.1 18h15.8a1 1 0 00.86-1.94l-7.05-12.2a1 1 0 00-1.72 0z" />
                                        </svg>
                                    </button>

                                    <button type="button"
                                        class="btn-delete flex items-center justify-center w-8 h-8 rounded-full bg-red-100 hover:bg-red-200 transition"
                                        title="Hapus Postingan"
                                        data-id="<?= htmlspecialchars($data['POST_ID']) ?>"
                                        data-name="<?= htmlspecialchars($data['POST_OWNER_NAME']) ?>"
                                        data-report-id="<?= htmlspecialchars($data['REPORT_IDS']) ?>"
                                        data-owner-id="<?= htmlspecialchars($data['USER_ID']) ?>"
                                        data-title="Delete Forum">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="red"
                                            class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php require_once 'app/views/components/admin/modalReasonLaporanPost.php' ?>
    <?php require_once 'app/views/components/admin/modalWarningReportPost.php' ?>
    <?php require_once 'app/views/components/admin/modalDeleteRepotPost.php' ?>
</body>

</html>