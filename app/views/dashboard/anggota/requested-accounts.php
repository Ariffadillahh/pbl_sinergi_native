<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requested Accounts | Sinergi</title>
</head>
<body>
    <div class="bg-white rounded-xl p-4 drop-shadow">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Requested Mitra Accounts</h2>
            <p class="text-sm text-gray-600 mt-1">Kelola permintaan pembuatan akun mitra dari owner forum</p>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="search-input" 
                    class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                    placeholder="Cari berdasarkan nama mitra, username, email, requester, atau forum...">
                <button id="clear-search" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                    <svg class="w-5 h-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <p id="request-alert" class="hidden mb-4 p-3 rounded-lg text-sm"></p>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Requested By</th>
                        <th scope="col" class="px-6 py-3">Forum</th>
                        <th scope="col" class="px-6 py-3">Nama Mitra</th>
                        <th scope="col" class="px-6 py-3">Username</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Tanggal Request</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody id="requests-table-body">
                    <!-- Data akan dimuat dengan JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="empty-state" class="hidden text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada request</h3>
            <p class="mt-1 text-sm text-gray-500" id="empty-message">Semua request telah diproses</p>
        </div>

        <!-- Results Info -->
        <div id="results-info" class="hidden mt-4 text-sm text-gray-600">
            Menampilkan <span id="results-count" class="font-semibold">0</span> hasil
        </div>
    </div>

    <!-- Modal Approve -->
    <div id="approve-modal" class="hidden fixed inset-0 bg-black/50 z-[9999] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h3 class="text-xl font-semibold text-gray-900">Review Request Akun Mitra</h3>
                <button type="button" onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <p id="modal-alert" class="hidden mb-4 p-3 rounded-lg text-sm"></p>

                <!-- Request Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-500 text-white p-2 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-1">Informasi Request</h4>
                            <p class="text-sm text-gray-600">
                                Requested by: <span id="modal-requester" class="font-medium"></span><br>
                                Forum: <span id="modal-forum" class="font-medium"></span><br>
                                Tanggal: <span id="modal-date" class="font-medium"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <form id="approve-form" class="space-y-4">
                    <input type="hidden" id="approve-user-id" name="user_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                            <input type="text" id="approve-fullname" readonly
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                            <input type="text" id="approve-username" readonly
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nomor Mitra</label>
                            <input type="text" id="approve-personal-number" readonly
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                            <input type="email" id="approve-email" readonly
                                class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 gap-3 border-t border-gray-200">
                        <button type="button" onclick="rejectRequest()" id="reject-btn"
                            class="px-6 py-2 rounded-full bg-red-100 text-red-600 font-bold hover:bg-red-200 transition-colors">
                            Tolak
                        </button>
                        <button type="submit" id="approve-btn"
                            class="px-6 py-2 rounded-full bg-blue-600 text-white font-bold hover:bg-blue-500 transition-colors">
                            Setujui & Buat Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    let currentRequestId = null;
    let allRequests = []; // Store all requests for filtering

    async function loadRequests() {
        try {
            const response = await fetch('<?= BASEURL ?>/dashboard/anggota/get-pending-requests');
            const result = await response.json();

            if (result.success) {
                allRequests = result.requests;
                displayRequests(allRequests);
            } else {
                allRequests = [];
                displayRequests([]);
            }
        } catch (error) {
            console.error('Error loading requests:', error);
            showAlert('Gagal memuat data', true);
        }
    }

    function displayRequests(requests) {
        const tbody = document.getElementById('requests-table-body');
        const emptyState = document.getElementById('empty-state');
        const resultsInfo = document.getElementById('results-info');
        const resultsCount = document.getElementById('results-count');
        const emptyMessage = document.getElementById('empty-message');
        const searchInput = document.getElementById('search-input');

        if (requests.length > 0) {
            tbody.innerHTML = requests.map(req => {
                // Format tanggal
                const tanggal = req.CREATED_AT ? 
                    new Date(req.CREATED_AT).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : 
                    '-';
                
                // Escape data untuk JSON
                const reqJson = JSON.stringify(req).replace(/'/g, "&apos;");
                
                return `
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            ${req.REQUESTER_NAME || '<span class="text-gray-400 italic">-</span>'}
                        </td>
                        <td class="px-6 py-4">
                            ${req.FORUM_NAME || '<span class="text-gray-400 italic">-</span>'}
                        </td>
                        <td class="px-6 py-4 font-medium">${req.FULL_NAME}</td>
                        <td class="px-6 py-4">${req.USERNAME}</td>
                        <td class="px-6 py-4">${req.EMAIL}</td>
                        <td class="px-6 py-4 text-gray-600">${tanggal}</td>
                        <td class="px-6 py-4">
                            <button onclick='openApproveModal(${reqJson})' 
                                class="text-blue-600 hover:text-blue-800 font-medium hover:underline transition-colors">
                                Review
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            emptyState.classList.add('hidden');
            resultsInfo.classList.remove('hidden');
            resultsCount.textContent = requests.length;
        } else {
            tbody.innerHTML = '';
            resultsInfo.classList.add('hidden');
            emptyState.classList.remove('hidden');
            
            // Update empty message based on search
            if (searchInput.value.trim()) {
                emptyMessage.textContent = 'Tidak ada hasil yang cocok dengan pencarian';
            } else {
                emptyMessage.textContent = 'Semua request telah diproses';
            }
        }
    }

    // Search functionality
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    const clearSearchBtn = document.getElementById('clear-search');

    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        
        // Show/hide clear button
        if (e.target.value.trim()) {
            clearSearchBtn.classList.remove('hidden');
        } else {
            clearSearchBtn.classList.add('hidden');
        }
        
        searchTimeout = setTimeout(() => {
            const searchTerm = e.target.value.toLowerCase().trim();
            
            if (!searchTerm) {
                displayRequests(allRequests);
                return;
            }
            
            const filtered = allRequests.filter(req => {
                return (
                    (req.FULL_NAME || '').toLowerCase().includes(searchTerm) ||
                    (req.USERNAME || '').toLowerCase().includes(searchTerm) ||
                    (req.EMAIL || '').toLowerCase().includes(searchTerm) ||
                    (req.PERSONAL_NUMBER || '').toLowerCase().includes(searchTerm) ||
                    (req.REQUESTER_NAME || '').toLowerCase().includes(searchTerm) ||
                    (req.FORUM_NAME || '').toLowerCase().includes(searchTerm)
                );
            });
            
            displayRequests(filtered);
        }, 300); // Debounce 300ms
    });

    // Clear search
    clearSearchBtn.addEventListener('click', () => {
        searchInput.value = '';
        clearSearchBtn.classList.add('hidden');
        displayRequests(allRequests);
        searchInput.focus();
    });

    function openApproveModal(request) {
        currentRequestId = request.ID;

        document.getElementById('approve-user-id').value = request.ID;
        document.getElementById('approve-fullname').value = request.FULL_NAME;
        document.getElementById('approve-username').value = request.USERNAME;
        document.getElementById('approve-personal-number').value = request.PERSONAL_NUMBER;
        document.getElementById('approve-email').value = request.EMAIL;
        
        // Set informasi request
        document.getElementById('modal-requester').textContent = request.REQUESTER_NAME || 'Unknown';
        document.getElementById('modal-forum').textContent = request.FORUM_NAME || 'Unknown';
        
        // Format tanggal untuk modal
        const tanggal = request.CREATED_AT ? 
            new Date(request.CREATED_AT).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }) : 
            '-';
        document.getElementById('modal-date').textContent = tanggal;

        document.getElementById('approve-modal').classList.remove('hidden');
    }

    function closeApproveModal() {
        document.getElementById('approve-modal').classList.add('hidden');
        document.getElementById('approve-form').reset();
        document.getElementById('modal-alert').classList.add('hidden');
    }

    function showAlert(message, isError = false) {
        const alert = document.getElementById('request-alert');
        alert.className = `mb-4 p-3 rounded-lg text-sm ${isError ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}`;
        alert.textContent = message;
        alert.classList.remove('hidden');
        
        setTimeout(() => alert.classList.add('hidden'), 5000);
    }

    function showModalAlert(message, isError = false) {
        const alert = document.getElementById('modal-alert');
        alert.className = `mb-4 p-3 rounded-lg text-sm ${isError ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}`;
        alert.textContent = message;
        alert.classList.remove('hidden');
    }

    document.getElementById('approve-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const approveBtn = document.getElementById('approve-btn');
        approveBtn.disabled = true;
        approveBtn.textContent = 'Memproses...';

        const formData = new FormData(e.target);

        try {
            const response = await fetch('<?= BASEURL ?>/dashboard/anggota/approve-mitra-request', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showModalAlert(result.message, false);
                setTimeout(() => {
                    closeApproveModal();
                    loadRequests();
                }, 1500);
            } else {
                showModalAlert(result.message, true);
            }
        } catch (error) {
            console.error(error);
            showModalAlert('Terjadi kesalahan server', true);
        } finally {
            approveBtn.disabled = false;
            approveBtn.textContent = 'Setujui & Buat Akun';
        }
    });

    async function rejectRequest() {
        if (!confirm('Apakah Anda yakin ingin menolak request ini?')) {
            return;
        }

        const rejectBtn = document.getElementById('reject-btn');
        rejectBtn.disabled = true;
        rejectBtn.textContent = 'Menolak...';

        const formData = new FormData();
        formData.append('user_id', currentRequestId);

        try {
            const response = await fetch('<?= BASEURL ?>/dashboard/anggota/reject-mitra-request', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showModalAlert(result.message, false);
                setTimeout(() => {
                    closeApproveModal();
                    loadRequests();
                }, 1500);
            } else {
                showModalAlert(result.message, true);
            }
        } catch (error) {
            console.error(error);
            showModalAlert('Terjadi kesalahan server', true);
        } finally {
            rejectBtn.disabled = false;
            rejectBtn.textContent = 'Tolak';
        }
    }

    // Close on backdrop click
    document.getElementById('approve-modal').addEventListener('click', (e) => {
        if (e.target.id === 'approve-modal') {
            closeApproveModal();
        }
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !document.getElementById('approve-modal').classList.contains('hidden')) {
            closeApproveModal();
        }
    });

    // Load requests on page load
    loadRequests();
    </script>
</body>
</html>