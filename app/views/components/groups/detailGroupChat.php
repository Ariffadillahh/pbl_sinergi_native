<div id="Chat-Navigation" class="flex items-center justify-between w-full border-b border-gray-200 p-5 gap-3 bg-white flex-shrink-0">
    <div id="Group-Title" class="flex items-center flex-1 gap-3">
        <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden items-center justify-center">
            <?php if (!empty($groupChatId['PATH_PHOTO'])): ?>
                <img
                    src="<?= BASEURL . '/storage/groups/photos/' . $groupChatId['PATH_PHOTO'] ?>"
                    class="w-full h-full object-cover"
                    alt="photo">
            <?php else: ?>
                <span class="w-full h-full flex items-center justify-center bg-pink-500 text-white font-bold text-lg">
                    <?= strtoupper(substr($groupChatId['NAME'], 0, 2)) ?>
                </span>
            <?php endif; ?>
        </div>

        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-[6px]">
                <h1 class="font-semibold text-lg truncate overflow-hidden whitespace-nowrap">
                    <?= $groupChatId["NAME"] ?>
                </h1>
            </div>
            <div class="flex items-center gap-[6px]">
                <span class="font-semibold text-sm text-gray-500"><?= count($membersGroupChat) ?> Members</span>
            </div>
        </div>
    </div>
    <ul class="flex gap-3">
        <?php if ($groupChatId['OWNER_ID'] !== $_SESSION['user_id']) : ?>
            <li class="group">
                <button id="reportForumButton" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 cursor-pointer">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/report.png" class="size-6" alt="icon">
                </button>
            </li>
        <?php endif ?>
        <li class="group">
            <button id="infoForum" class="size-11 flex shrink-0 bg-white rounded-xl p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 cursor-pointer">
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
                <button id="btn-open-exit-forum" class="w-full h-full flex gap-1 items-center justify-center bg-white rounded-2xl p-[10px] ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 cursor-pointer">
                    <img src="<?php echo BASEURL; ?>/src/asset/icons/logout-grey.svg" class="size-6 flex shrink-0" alt="icon">
                    <span class="font-medium text-sm">Leave Group</span>
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
                    <?php if (!empty($groupChatId['PATH_PHOTO'])): ?>
                        <img
                            src="<?= BASEURL . '/storage/groups/photos/' . $groupChatId['PATH_PHOTO'] ?>"
                            class="w-full h-full object-cover"
                            alt="photo">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-pink-500 text-white text-3xl font-bold">
                            <?= strtoupper(substr($groupChatId['NAME'], 0, 2)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <div class="flex items-center justify-center gap-[6px]">
                        <p class="font-semibold text-lg"> <?= $groupChatId["NAME"] ?></p>
                    </div>
                    <?php if ($groupChatId['OWNER_ID'] == $_SESSION['user_id'] && !empty($groupChatId['ACCESS_KEY'])): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-violet-700 bg-violet-100 px-2 py-0.5 rounded-md flex items-center gap-1.5">
                                <span>Private Key:</span>
                                <span id="access-key-text" class="font-mono"><?= $groupChatId['ACCESS_KEY'] ?></span>
                            </span>

                            <button
                                id="copy-key-btn"
                                onclick="copyAccessKey('<?= $groupChatId['ACCESS_KEY'] ?>')"
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
                            <span class="text-gray-500"><?= count($membersGroupChat) ?> Members</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div id="About" class="flex flex-col gap-1 mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-lg font-semibold text-gray-800">About Group</p>

                    <p class="text-gray-700">
                        <?= htmlspecialchars($groupChatId["ABOUT"]) ?>
                    </p>

                    <p class="text-sm text-gray-500 mt-2">
                        Created at:
                        <span class="font-medium text-gray-700">
                            <?= formatDatePretty($groupChatId["CREATED_AT"]) ?>
                        </span>
                    </p>
                </div>

                <div id="MediaPreview"
                    class="flex flex-col gap-3 mt-6 p-4 bg-gray-50 rounded-xl border border-gray-200">

                    <div class="flex items-center justify-between">
                        <p class="text-lg font-semibold text-gray-800">Media, Files & Links</p>

                        <?php if (!empty($mediaPreview)) : ?>
                            <button id="open-media-full"
                                class="text-sm font-medium text-blue-600 hover:underline cursor-pointer">
                                See more →
                            </button>
                        <?php endif ?>
                    </div>

                    <div class="grid grid-cols-4 gap-3">
                        <?php if (!empty($mediaPreview)): ?>
                            <?php foreach ($mediaPreview as $media): ?>
                                <div class="relative w-full h-[65px] rounded-lg overflow-hidden bg-gray-200 flex items-center justify-center cursor-pointer group media-item">

                                    <?php if ($media['type'] === 'image'): ?>
                                        <img src="<?= BASEURL . '/' . $media['path'] ?>"
                                            class="w-full h-full object-cover group-hover:scale-105 transition"
                                            alt="Image Preview">

                                    <?php elseif ($media['type'] === 'video'): ?>
                                        <div class="flex items-center justify-center w-full h-full bg-black/40 relative">

                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white opacity-80 group-hover:opacity-100 transition duration-300"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M6 4.5l14 7.5L6 19V4.5z" />
                                                </svg>
                                            </div>
                                        </div>

                                    <?php elseif ($media['type'] === 'file'): ?>
                                        <div class="flex flex-col items-center justify-center w-full h-full bg-gray-300">
                                            <img src="<?= BASEURL ?>/src/asset/image/file.png" class="w-7 h-7 opacity-80" alt="File Icon">
                                        </div>

                                    <?php else: ?>
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                            Media
                                        </div>
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-gray-500 text-sm col-span-4 text-center my-3">
                                No media sent yet.
                            </p>
                        <?php endif; ?>
                    </div>

                </div>

                <div>
                    <div id="Owner" class="flex flex-col gap-3 mt-6">
                        <p class="font-semibold leading-5">Owner</p>
                        <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 min-w-0">
                            <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                                <img src="<?= !empty($groupChatId['PATH_PHOTO_OWNER'])
                                                ? BASEURL . '/storage/users/photos/' . $groupChatId['PATH_PHOTO_OWNER']
                                                : BASEURL . '/src/asset/image/default.png' ?>" class="w-full h-full object-cover" alt="photo">
                            </div>
                            <div class="flex flex-col flex-1 gap-[6px] min-w-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold truncate"><?= $groupChatId["OWNER_NAME"] ?></p>
                                    </div>
                                    <?php
                                    $role = $groupChatId["ROLE_OWNER"];

                                    $roleTranslations = [
                                        "MAHASISWA" => "STUDENT",
                                        "ADMIN"     => "ADMIN",
                                        "DOSEN"     => "LECTURER",
                                        "MITRA"     => "PARTNER",
                                        "ALUMNI"    => "ALUMNI"
                                    ];

                                    $roleClasses = [
                                        "MAHASISWA" => "bg-blue-100 text-blue-800",
                                        "ADMIN"     => "bg-red-100 text-red-800",
                                        "DOSEN"     => "bg-green-100 text-green-800",
                                        "MITRA"     => "bg-gray-100 text-gray-800",
                                        "ALUMNI"    => "bg-yellow-100 text-yellow-800"
                                    ];

                                    $displayRole = $roleTranslations[$role] ?? htmlspecialchars($role);
                                    $colorClass = $roleClasses[$role] ?? "bg-gray-100 text-gray-800";
                                    ?>

                                    <div class="flex-shrink-0">
                                        <span class="<?= $colorClass ?> text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                            <?= $displayRole ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="Members" class="flex flex-col gap-3 mt-6">
                        <?php
                        $membersGroupChatFiltered = array_filter($membersGroupChat, function ($m) use ($groupChatId) {
                            return $m['USER_ID'] !== $groupChatId['OWNER_ID'];
                        }); ?>
                        <p class="font-semibold leading-5">Members (<?= count($membersGroupChatFiltered) ?>)</p>

                        <div class="flex flex-col gap-3">
                            <?php foreach ($membersGroupChatFiltered as $member): ?>
                                <?php if ($member['USER_ID'] == $groupChatId['OWNER_ID']) continue; ?>
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

                                            $roleTranslations = [
                                                "MAHASISWA" => "STUDENT",
                                                "ADMIN"     => "ADMIN",
                                                "DOSEN"     => "LECTURER",
                                                "MITRA"     => "PARTNER",
                                                "ALUMNI"    => "ALUMNI"
                                            ];

                                            $roleClasses = [
                                                "MAHASISWA" => "bg-blue-100 text-blue-800",
                                                "ADMIN"     => "bg-red-100 text-red-800",
                                                "DOSEN"     => "bg-green-100 text-green-800",
                                                "MITRA"     => "bg-gray-100 text-gray-800",
                                                "ALUMNI"    => "bg-yellow-100 text-yellow-800"
                                            ];

                                            $displayRole = $roleTranslations[$role] ?? htmlspecialchars($role);
                                            $colorClass = $roleClasses[$role] ?? "bg-gray-100 text-gray-800";
                                            ?>

                                            <div class="flex-shrink-0">
                                                <span class="<?= $colorClass ?> text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                                    <?= $displayRole ?>
                                                </span>
                                            </div>

                                        </div>
                                        <div class="flex font-medium text-sm gap-0.5 items-center">
                                            <p>Joined: </p>
                                            <p><?= $member["JOINED_AT"] ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>


                <?php if ($groupChatId['OWNER_ID'] == $_SESSION['user_id']): ?>
                    <div class="my-6 flex flex-col gap-3 sm:grid sm:grid-cols-2">
                        <button type="button" id="btn-open-manage-members"
                            class="group flex w-full sm:col-span-2 items-center justify-center gap-2 rounded-xl bg-slate-800 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-slate-500/20 transition-all duration-200 hover:bg-slate-900 hover:shadow-lg hover:shadow-slate-500/30 focus:outline-none focus:ring-4 focus:ring-slate-500/20 active:scale-95">

                            <svg class="h-5 w-5 text-slate-300 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            Manage Members
                        </button>

                        <button type="button" id="btn-open-edit-forum"
                            class="group flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 active:scale-[0.98]">
                            <svg class="h-4 w-4 text-blue-100 transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Group
                        </button>

                        <button type="button" id="btn-open-delete-forum"
                            class="group flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-700 transition-all duration-200 hover:bg-red-100 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-200 active:scale-[0.98]">
                            <svg class="h-4 w-4 text-red-500 transition-colors group-hover:text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Group
                        </button>

                    </div>
                <?php endif; ?>

            </div>

        </div>
    </div>
</div>

<script>
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

<?php require_once 'app/views/components/groups/modalEditGroup.php'; ?>
<?php require_once 'app/views/components/groups/modalDeleteGroup.php'; ?>
<?php require_once 'app/views/components/groups/modalExitGroup.php'; ?>
<?php require_once 'app/views/components/groups/modalReportGroup.php'; ?>
<?php require_once 'app/views/components/groups/modalManageMember.php'; ?>
<?php require_once 'app/views/components/groups/modalAllMedia.php'; ?>