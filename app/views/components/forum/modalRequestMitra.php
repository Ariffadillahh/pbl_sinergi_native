<!-- Modal Request Pembuatan Akun Mitra - Blue Design -->
<div id="requestMitraModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[9999] hidden opacity-0 transition-opacity duration-300 p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl transform scale-95 transition-all duration-300 max-h-[95vh] overflow-y-auto">
        
        <!-- Header with Blue Gradient -->
        <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 p-8 rounded-t-3xl">
            <div class="absolute top-4 right-4">
                <button type="button" onclick="closeRequestMitraModal()" 
                    class="text-white/80 hover:text-white transition p-2 rounded-full hover:bg-white/10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="flex items-start gap-4">
                <div class="bg-white/20 backdrop-blur-sm p-4 rounded-2xl">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div class="flex-1 pt-2">
                    <h3 class="text-3xl font-bold text-white mb-2">Request Akun Mitra</h3>
                    <p class="text-white/90 text-sm leading-relaxed">
                        Ajukan permintaan pembuatan akun mitra industri untuk bergabung dan berkolaborasi dalam forum ini
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <div id="requestMitraAlert" class="hidden mx-8 mt-6"></div>

        <!-- Form Content -->
        <form id="requestMitraForm" class="p-8 space-y-6">
            <input type="hidden" name="forum_id" value="<?= $forumById['ID'] ?>">
            <input type="hidden" name="forum_name" value="<?= htmlspecialchars($forumById['NAME']) ?>">
            
            <!-- Info Box -->
            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm text-amber-800">
                        <p class="font-semibold mb-1">Informasi Penting</p>
                        <p class="text-amber-700">Request akan dikirim ke admin untuk diverifikasi. Setelah disetujui, akun mitra akan otomatis bergabung ke forum <strong><?= htmlspecialchars($forumById['NAME']) ?></strong></p>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="space-y-5">
                <!-- Nama Lengkap -->
                <div class="group">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-2.5">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Nama Lengkap Mitra <span class="text-red-500">*</span></span>
                    </label>
                    <input type="text" name="nama_lengkap" required
                        class="w-full my-2 px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="Contoh: PT Mitra Sejahtera Indonesia">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Username -->
                    <div class="group">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-2.5">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>Username <span class="text-red-500">*</span></span>
                        </label>
                        <input type="text" name="username" required
                            class="w-full my-2 px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            placeholder="username_mitra">
                    </div>

                    <!-- Nomor Mitra -->
                    <div class="group">
                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-2.5">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                            <span>Nomor Mitra <span class="text-red-500">*</span></span>
                        </label>
                        <input type="text" name="personal_number" required
                            class="w-full my-2 px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            placeholder="MITRA001">
                    </div>
                </div>

                <!-- Email -->
                <div class="group">
                    <label class="flex items-center gap-2 text-sm font-semibold text-gray-800 mb-2.5">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>Email Mitra <span class="text-red-500">*</span></span>
                    </label>
                    <input type="email" name="email" required
                        class="w-full my-2 px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="kontak@perusahaan.com">
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
                <button type="button" onclick="closeRequestMitraModal()"
                    class="flex-1 px-6 py-3.5 border-2 border-gray-300 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 transition-all duration-200 hover:shadow-md">
                    Batal
                </button>
                <button type="submit" id="btnSubmitRequest"
                    class="flex-1 px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    Kirim Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const requestMitraModal = document.getElementById('requestMitraModal');
const requestMitraForm = document.getElementById('requestMitraForm');
const requestMitraAlert = document.getElementById('requestMitraAlert');
const btnSubmitRequest = document.getElementById('btnSubmitRequest');

function openRequestMitraModal() {
    requestMitraModal.classList.remove('hidden');
    setTimeout(() => {
        requestMitraModal.classList.remove('opacity-0');
        requestMitraModal.querySelector('div').classList.remove('scale-95');
    }, 10);
}

function closeRequestMitraModal() {
    requestMitraModal.classList.add('opacity-0');
    requestMitraModal.querySelector('div').classList.add('scale-95');
    setTimeout(() => {
        requestMitraModal.classList.add('hidden');
        requestMitraForm.reset();
        requestMitraAlert.classList.add('hidden');
    }, 300);
}

function showAlert(message, isError = false) {
    const iconPath = isError 
        ? 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z';
    
    const bgColor = isError ? 'bg-red-50' : 'bg-green-50';
    const textColor = isError ? 'text-red-700' : 'text-green-700';
    const borderColor = isError ? 'border-red-500' : 'border-green-500';
    
    requestMitraAlert.className = `mx-8 mt-6 p-4 rounded-xl text-sm font-medium ${bgColor} ${textColor} border-l-4 ${borderColor}`;
    requestMitraAlert.innerHTML = `
        <div class="mx-2 flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}" />
            </svg>
            <span>${message}</span>
        </div>
    `;
    requestMitraAlert.classList.remove('hidden');
}

requestMitraForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    // Disable button dan tampilkan loading
    btnSubmitRequest.disabled = true;
    btnSubmitRequest.innerHTML = `
        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Mengirim...
    `;
    requestMitraAlert.classList.add('hidden');
    
    const formData = new FormData(requestMitraForm);
    
    try {
        const response = await fetch('<?= BASEURL ?>/forum/request-mitra-account', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert(result.message, false);
            setTimeout(() => {
                closeRequestMitraModal();
                // Refresh halaman untuk update UI
                location.reload();
            }, 2500);
        } else {
            showAlert(result.message, true);
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat mengirim request. Silakan coba lagi.', true);
    } finally {
        // Reset button
        btnSubmitRequest.disabled = false;
        btnSubmitRequest.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
            </svg>
            Kirim Request
        `;
    }
});

// Close on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !requestMitraModal.classList.contains('hidden')) {
        closeRequestMitraModal();
    }
});

// Close on backdrop click
requestMitraModal.addEventListener('click', (e) => {
    if (e.target === requestMitraModal) {
        closeRequestMitraModal();
    }
});
</script>