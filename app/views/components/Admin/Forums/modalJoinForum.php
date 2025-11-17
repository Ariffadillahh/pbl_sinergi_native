<div id="joinForumModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Tambahkan Anggota ke Forum</h2>
                <button onclick="closeJoinForumModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="bg-red-600 w-full text-white text-center rounded-lg mb-3 p-2 hidden" id="errorDiv"></div>
            <div class="bg-green-600 w-full text-white text-center rounded-lg mb-3 p-2 hidden" id="successDiv"></div>

            <form id="form-join-forum">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Anggota</label>
                    <select id="memberSelect" name="user_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['ID']; ?>">
                                <?= htmlspecialchars($user['FULL_NAME']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Forum</label>
                    <select id="forumSelect" name="forum_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <?php foreach ($allForums as $forums): ?>
                            <option
                                value="<?= $forums['ID']; ?>"
                                data-private="<?= $forums['IS_PRIVATE']; ?>"
                                data-owner="<?= $forums['OWNER_ID']; ?>">
                                <?= htmlspecialchars($forums['NAME']); ?>
                                <?= $forums['IS_PRIVATE'] == 1 ? '🔒' : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="hidden" id="ownerIdInput" name="owner_id">


                <div id="privateKeyContainer" class="mb-6 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Masukkan Private Key</label>
                    <input type="text" id="privateKeyInput"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg"
                        placeholder="Masukkan key forum..."
                        name="access_key">
                </div>

                <button id="btn-inv"
                    class="w-full py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition duration-200">
                    Tambahkan Anggota
                </button>
            </form>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        $('#memberSelect').select2({
            placeholder: "Cari Anggota...",
            allowClear: true,
            width: '100%'
        });

        $('#forumSelect').select2({
            placeholder: "Cari Forums...",
            allowClear: true,
            width: '100%'
        });

        function updateOwnerAndPrivateStatus() {
            const data = $('#forumSelect').select2('data')[0];

            if (!data) return;

            const isPrivate = Number($(data.element).data('private'));
            const ownerId = $(data.element).data('owner');

            $('#ownerIdInput').val(ownerId);

            if (isPrivate === 1) {
                $('#privateKeyContainer').removeClass('hidden');
            } else {
                $('#privateKeyContainer').addClass('hidden');
                $('#privateKeyInput').val('');
            }
        }

        $('#forumSelect').on('change', updateOwnerAndPrivateStatus);

        updateOwnerAndPrivateStatus();
    });



    const form = document.getElementById("form-join-forum");
    const errorDiv = document.getElementById("errorDiv");
    const successDiv = document.getElementById("successDiv");
    const btnInvite = document.getElementById("btn-inv");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        btnInvite.disabled = true;
        btnInvite.innerHTML = "Memproses...";

        const formData = new FormData(form);

        try {
            const response = await fetch(`<?= BASEURL ?>/join-forum-admin`, {
                method: "POST",
                body: formData,
            });

            const result = await response.json();

            if (result.success) {
                ShowSuccsess("Berhasil menambahkan anggota!")
                window.location.reload();
            } else {
                showError(result.message);
            }

        } catch (error) {
            showError("Terjadi kesalahan jaringan!");
            console.error(error);
        } finally {
            btnInvite.disabled = false;
            btnInvite.innerHTML = "Tambahkan Anggota";
        }
    });

    function showError(message) {
        errorDiv.classList.remove("hidden");
        errorDiv.innerHTML = message;

        setTimeout(() => {
            errorDiv.classList.add("hidden");
        }, 2000);
    }

    function ShowSuccsess(message) {
        successDiv.classList.remove("hidden");
        successDiv.innerHTML = message;

        setTimeout(() => {
            successDiv.classList.add("hidden");
        }, 2000);
    }
</script>