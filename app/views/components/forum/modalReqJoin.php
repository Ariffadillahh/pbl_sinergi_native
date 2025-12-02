<div id="requestModal" class="hidden relative z-[9999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/60 bg-opacity-60 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex justify-center items-center h-screen px-3">

            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl
            transition-all sm:my-8 w-full max-w-lg lg:max-w-2xl border border-gray-100">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Incoming Requests</h3>
                        <p class="text-sm text-gray-500 mt-1">Review new user access requests.</p>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition cursor-pointer p-1 rounded-full hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-4 py-2 sm:px-6 h-80 overflow-y-auto hide-scrollbar relative">

                    <div id="loadingState" class="hidden absolute inset-0 bg-white z-20 flex flex-col items-center justify-center text-gray-400">
                        <svg class="animate-spin h-8 w-8 mb-3 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-sm font-medium text-gray-500">Loading requests...</p>
                    </div>

                    <div id="requestListContainer" class="space-y-1 mt-2"></div>

                    <div id="emptyState" class="hidden flex-col items-center justify-center h-full text-gray-500 py-10">

                    </div>

                    <div id="empty" class="hidden flex flex-col items-center justify-center h-full py-6 text-center px-4 hide-scrollbar">

                        <div class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 shadow-inner">
                            <svg class="w-9 h-9 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>

                        <h4 class="text-lg font-semibold text-gray-800 mb-1">
                            No Requests Found
                        </h4>

                        <p class="text-sm text-gray-500 max-w-sm leading-relaxed">
                            There are currently no pending requests to join this forum.
                            New requests will appear here automatically.
                        </p>
                    </div>


                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-2xl border-t border-gray-100">
                    <button type="button" onclick="closeModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm 
                    ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition cursor-pointer">
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

    const modal = document.getElementById('requestModal');
    const listContainer = document.getElementById('requestListContainer');
    const loadingState = document.getElementById('loadingState');
    const emptyState = document.getElementById('emptyState');
    const empty = document.getElementById('empty');

    async function openRequestModal(buttonElement) {
        const forumId = buttonElement.getAttribute('data-id');


        modal.classList.remove('hidden');
        modal.classList.add('flex');
        listContainer.innerHTML = '';
        emptyState.classList.remove('flex');
        emptyState.classList.add('hidden');
        loadingState.classList.remove('hidden');
        loadingState.classList.add('flex');


        try {
            const response = await fetch(`<?= BASEURL ?>/${API_GET_URL}?forum_id=${forumId}`);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();

            loadingState.classList.add('hidden');
            loadingState.classList.remove('flex');


            if (data.length === 0) {
                empty.classList.remove('hidden')
                console.log('kosong')
                return;
            }

            data.forEach(user => {
                const initial = user.nama ? user.nama.substring(0, 2).toUpperCase() : 'NN';
                const roleColor = user.role === 'ADMIN' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700';

                const itemHTML = `
                    <div id="req-item-${user.id}" class="request-item flex items-center justify-between py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 rounded-lg px-2 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 flex-shrink-0 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm shadow-sm">
                                ${initial}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 line-clamp-1">${user.nama}</p>
                                <p class="text-xs text-gray-500">@${user.username} • <span class="${roleColor} px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide">${user.role}</span></p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="processAction('${user.id}', 'rejected')" title="Reject" class="p-2 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition shadow-sm border border-red-100 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            
                            <button onclick="processAction('${user.id}', 'accepted')" title="Accept" class="p-2 rounded-lg bg-green-50 text-green-500 hover:bg-green-500 hover:text-white transition shadow-sm border border-green-100 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        </div>
                    </div>
                `;
                listContainer.insertAdjacentHTML('beforeend', itemHTML);
            });

        } catch (error) {
            console.error('Error fetching requests:', error);
            loadingState.classList.add('hidden');
            loadingState.classList.remove('flex');
            listContainer.innerHTML = '<div class="text-center text-red-500 py-10 text-sm">Gagal memuat data. Silakan coba lagi.</div>';
        }
    }

    async function processAction(userId, status) {
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

                if (item) {
                    item.style.transform = 'translateX(20px)';
                    item.style.opacity = '0';

                    setTimeout(() => {
                        item.remove();
                        checkIfListEmpty();
                    }, 300);
                }

                const badge = document.getElementById('badgeCount');
                if (badge) {
                    let count = parseInt(badge.innerText) || 0;
                    if (count > 0) badge.innerText = count - 1;
                    if (count - 1 <= 0) badge.classList.add('hidden');
                }

            } else {
                alert('Gagal: ' + (result.message || 'Terjadi kesalahan server'));
            }
        } catch (error) {
            console.error('Error processing action:', error);
            alert('Terjadi kesalahan koneksi.');
        }
    }

    function checkIfListEmpty() {
        const remainingItems = document.querySelectorAll('#requestListContainer .request-item');
        if (remainingItems.length === 0) {
            showEmptyState();
        }
    }

    function showEmptyState() {
        listContainer.innerHTML = '';
        emptyState.classList.remove('hidden');
        emptyState.classList.add('flex');
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>