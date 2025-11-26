<div class="tab-content hidden" data-content="people">
    <?php
    $roleClasses = [
        "MAHASISWA" => "bg-blue-100 text-blue-800",
        "ADMIN"     => "bg-red-100 text-red-800",
        "DOSEN"     => "bg-green-100 text-green-800",
        "MITRA"     => "bg-gray-100 text-gray-800",
        "ALUMNI"    => "bg-yellow-100 text-yellow-800"
    ];

    $membersForumFiltered = array_filter($membersForum ?? [], function ($m) use ($forumById) {
        return $m['USER_ID'] !== $forumById['OWNER_ID'];
    });
    ?>

    <div class="bg-white rounded-lg drop-shadow p-5">
        <div id="Owner" class="flex flex-col gap-3 ">
            <p class="font-semibold leading-5">Owner</p>
            <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 min-w-0">
                <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                    <img src="<?= !empty($forumById['PATH_PHOTO_OWNER'])
                                    ? BASEURL . '/storage/users/photos/' . $forumById['PATH_PHOTO_OWNER']
                                    : BASEURL . '/src/asset/image/default.png' ?>"
                        class="w-full h-full object-cover border border-gray-200" alt="Owner Photo">
                </div>

                <div class="flex flex-col flex-1 gap-[6px] min-w-0">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold truncate"><?= htmlspecialchars($forumById["OWNER_NAME"] ?? 'Unknown') ?></p>
                        </div>
                        <?php
                        $roleOwner = $forumById["ROLE_OWNER"] ?? '';
                        $colorClassOwner = $roleClasses[$roleOwner] ?? "bg-gray-100 text-gray-800";
                        ?>
                        <div class="flex-shrink-0">
                            <span class="<?= $colorClassOwner ?> text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                <?= htmlspecialchars($roleOwner) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="Members" class="flex flex-col gap-3 mt-6">
            <p class="font-semibold leading-5">Members (<?= count($membersForumFiltered) ?>)</p>

            <div class="flex flex-col gap-3">
                <?php if (empty($membersForumFiltered)) : ?>
                    <div class="text-center py-4 text-gray-500 text-sm">
                        Belum ada member lain di forum ini.
                    </div>
                <?php else : ?>
                    <?php foreach ($membersForumFiltered as $member): ?>
                        <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 min-w-0">
                            <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
                                <img src="<?= !empty($member['PATH_PHOTO'])
                                                ? BASEURL . '/storage/users/photos/' . $member['PATH_PHOTO']
                                                : BASEURL . '/src/asset/image/default.png' ?>"
                                    class="w-full h-full object-cover" alt="Member Photo">
                            </div>

                            <div class="flex flex-col flex-1 gap-[6px] min-w-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold truncate"><?= htmlspecialchars($member["FULL_NAME"]) ?></p>
                                    </div>
                                    <?php
                                    $roleMember = $member["ROLE"] ?? '';
                                    $colorClassMember = $roleClasses[$roleMember] ?? "bg-gray-100 text-gray-800";
                                    ?>
                                    <div class="flex-shrink-0">
                                        <span class="<?= $colorClassMember ?> text-xs font-medium px-2.5 py-0.5 rounded-sm">
                                            <?= htmlspecialchars($roleMember) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex font-medium text-sm text-heyhao-secondary gap-0.5 items-center">
                                    <p class="text-gray-500">Joined:</p>
                                    <p class="text-gray-600"><?= $member["JOINED_AT"] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>