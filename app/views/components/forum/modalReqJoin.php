<div id="requestModal" class="hidden relative z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <div class="fixed inset-0 bg-black/50 bg-opacity-40 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Incoming Requests</h3>
                        <p class="text-sm text-gray-500 mt-1">Review new user access requests.</p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-4 py-2 sm:px-6 h-80 overflow-y-auto custom-scrollbar">

                    <div id="loadingState" class="hidden flex-col items-center justify-center h-full text-gray-400">
                        <svg class="animate-spin h-8 w-8 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm">Loading...</p>
                    </div>

                    <div id="requestListContainer">
                    </div>

                    <div id="emptyState" class="hidden flex-col items-center justify-center h-full text-gray-700 ">
                        <p class="text-sm">No new requests available.</p>
                    </div>

                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                    <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const API_GET_URL = '/get-req-forum';
    const API_ACTION_URL = '/update-req-forum';

    async function openRequestModal(buttonElement) {
        const modal = document.getElementById('requestModal');
        const listContainer = document.getElementById('requestListContainer');
        const loadingState = document.getElementById('loadingState');
        const emptyState = document.getElementById('emptyState');

        const forumId = buttonElement.getAttribute('data-id');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        listContainer.innerHTML = '';
        loadingState.classList.remove('hidden');
        emptyState.classList.add('hidden');

        try {
            const response = await fetch(`<?= BASEURL ?>/${API_GET_URL}?forum_id=${forumId}`);
            const data = await response.json();

            loadingState.classList.add('hidden');

            if (data.length === 0) {
                emptyState.classList.remove('hidden');
                return;
            }

            data.forEach(user => {
                const itemHTML = `
                    <div id="req-item-${user.id}" class="flex items-center justify-between py-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded-lg px-2 transition">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                ${user.nama ? user.nama.substring(0, 2).toUpperCase() : 'NN'}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">${user.nama}</p>
                                <p class="text-xs text-gray-500">@${user.username} • <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">${user.role}</span></p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="processAction('${user.id}', 'rejected')" title="Reject" class="p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition shadow-sm border border-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            
                            <button onclick="processAction('${user.id}', 'accepted')" title="Accept" class="p-2 rounded-lg bg-green-50 text-green-500 hover:bg-green-500 hover:text-white transition shadow-sm border border-green-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        </div>
                    </div>
                    `;
                listContainer.insertAdjacentHTML('beforeend', itemHTML);
            });

        } catch (error) {
            console.error('Error:', error);
            loadingState.innerHTML = '<p class="text-red-500 text-sm">Gagal memuat data.</p>';
        }
    }

    async function processAction(userId, status) {
        if (!confirm(`Yakin ingin mengubah status menjadi ${status}?`)) return;

        try {
            const response = await fetch(`<?= BASEURL ?>${API_ACTION_URL}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: userId,
                    status: status
                })
            });

            const result = await response.json();

            if (result.success) {
                const item = document.getElementById(`req-item-${userId}`);
                item.style.opacity = '0';
                setTimeout(() => item.remove(), 300);

                const badge = document.getElementById('badgeCount');
                let count = parseInt(badge.innerText);
                badge.innerText = Math.max(0, count - 1);
            } else {
                alert('Gagal: ' + result.message);
            }
        } catch (error) {
            console.error('Error action:', error);
        }
    }

    function closeModal() {
        const modal = document.getElementById('requestModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>