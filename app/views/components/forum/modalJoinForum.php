<div id="requestJoinModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50 backdrop-blur-sm">
    <div class="bg-white rounded-xl p-6 w-96 shadow-xl relative">

        <div id="errorDiv" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden text-sm"></div>
        <div id="successDiv" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 hidden text-sm"></div>

        <h2 class="text-lg font-bold text-gray-800 mb-4">Input Access Key</h2>

        <form id="formJoin" method="post" onsubmit="return false;"> <input id="joinKeyInput"
                type="password"
                name="key"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 mb-4 focus:ring-2 focus:ring-blue-500 outline-none"
                placeholder="Type Access Key..."
                autocomplete="off">

            <input type="hidden" id="joinForumId" name="forum_id">

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRequestJoinModal()" class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 font-medium cursor-pointer">
                    Cancel
                </button>

                <button type="submit" id="btnSubmitKey" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 font-medium cursor-pointer">
                    Join
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function requestJoin(forumId) {
        document.getElementById('joinForumId').value = forumId;
        document.getElementById('joinKeyInput').value = '';
        document.getElementById('errorDiv').classList.add('hidden');
        document.getElementById('successDiv').classList.add('hidden');
        document.getElementById('requestJoinModal').classList.remove('hidden');

        setTimeout(() => document.getElementById('joinKeyInput').focus(), 100);
    }

    function closeRequestJoinModal() {
        document.getElementById('requestJoinModal').classList.add('hidden');
    }

    const formJoin = document.getElementById('formJoin');
    const successDiv = document.getElementById('successDiv');
    const errorDiv = document.getElementById('errorDiv');
    const btnSubmit = document.getElementById('btnSubmitKey');

    formJoin.addEventListener('submit', async function(event) {
        event.preventDefault();

        const key = document.getElementById('joinKeyInput').value.trim();

        if (!key) {
            errorDiv.textContent = "Key cannot be null";
            errorDiv.classList.remove('hidden');
            return;
        }

        errorDiv.classList.add('hidden');
        successDiv.classList.add('hidden');
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = "Loading...";

        const formData = new FormData(formJoin);

        try {
            const response = await fetch('<?= BASEURL ?>/forum/join', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                successDiv.textContent = result.message;
                successDiv.classList.remove('hidden');

                setTimeout(() => {
                    const forumId = document.getElementById('joinForumId').value;
                    location.href = '<?= BASEURL ?>/forum/' + forumId;
                }, 1000);
            } else {
                errorDiv.textContent = result.message;
                errorDiv.classList.remove('hidden');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = "Join";
            }

        } catch (error) {
            console.error(error);
            errorDiv.textContent = 'Terjadi kesalahan sistem (Cek Console).';
            errorDiv.classList.remove('hidden');
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = "Join";
        }
    });
</script>