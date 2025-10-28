<?php
$keyword = $keyword ?? ($_GET['keyword'] ?? '');
?>

<!-- Header bagian atas seperti homepage -->
<div class="sticky top-0 z-50 bg-white/95 backdrop-blur-md w-full shadow-sm border-b border-gray-200/80">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center justify-between gap-3 sm:gap-4">
            <div class="flex items-center gap-2 flex-shrink-0">
                <h1 class="hidden sm:block text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                    SINERGI
                </h1>
                <img src="<?= BASEURL ?>/src/asset/icons/logo-icon.svg" class="flex w-11 h-9 shrink-0 md:hidden" alt="logo">
            </div>

            <!-- search form -->
            <form id="searchForm" class="flex-1 max-w-2xl" onsubmit="return handleSearch(event)">
                <div class="relative group">
                    <input type="search"
                        id="searchInput"
                        class="block w-full p-4 pr-14 text-sm text-gray-900 rounded-full bg-white shadow-md border border-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Cari sesuatu atau ketik @username..."
                        autocomplete="off"
                        value="<?= htmlspecialchars($keyword) ?>"
                    />
                    <button type="submit"
                        class="text-white absolute right-1.5 top-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full p-2.5 w-10 h-10 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- notifikasi component -->
            <?php require_once __DIR__ . '/../components/notifikasi.php'; ?>
        </div>
    </div>
</div>

<!-- layout utama -->
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- sidebar kiri -->
    <aside class="hidden md:block md:col-span-1">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="font-semibold mb-2">Menu</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><a href="<?= BASEURL ?>/homepage" class="hover:underline">Home</a></li>
                <li><a href="<?= BASEURL ?>/profile" class="hover:underline">Profile</a></li>
            </ul>
        </div>
    </aside>

    <!-- hasil pencarian -->
    <main class="md:col-span-2">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex gap-6 border-b border-gray-200 pb-3">
                <button data-filter="top" class="tab-btn px-4 py-2 font-semibold text-blue-600 border-b-2 border-blue-600">Top</button>
                <button data-filter="latest" class="tab-btn px-4 py-2 text-gray-500 hover:text-blue-600">Latest</button>
                <button data-filter="users" class="tab-btn px-4 py-2 text-gray-500 hover:text-blue-600">Users</button>
            </div>

            <div id="results" class="mt-4 min-h-[200px]">
                <p class="text-center text-gray-400">Ketik keyword lalu klik Cari, atau tekan Enter.</p>
            </div>
        </div>
    </main>

    <!-- sidebar kanan -->
    <aside class="hidden md:block md:col-span-1">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="font-semibold mb-2">Trends</h3>
            <p class="text-sm text-gray-500">Placeholder untuk trending topics</p>
        </div>
    </aside>
</div>

<script>
const BASEURL = '<?= BASEURL ?>';
let currentFilter = 'top';

function handleSearch(e){
    e.preventDefault();
    const q = document.getElementById('searchInput').value.trim();
    if(!q) return;
    window.location.href = `${BASEURL}/homepage/search?keyword=${encodeURIComponent(q)}&filter=${currentFilter}`;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-b-2','border-blue-600','text-blue-600');
                b.classList.add('text-gray-500');
            });
            btn.classList.add('border-b-2','border-blue-600','text-blue-600');
            btn.classList.remove('text-gray-500');
            currentFilter = btn.dataset.filter;
            performSearch();
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    const kw = urlParams.get('keyword') || '';
    if (kw) {
        const qfilter = urlParams.get('filter') || 'top';
        currentFilter = qfilter;
        const activeBtn = document.querySelector(`.tab-btn[data-filter="${currentFilter}"]`);
        if (activeBtn) activeBtn.click();
        performSearch();
    }
});

async function performSearch(){
    const keyword = document.getElementById('searchInput').value.trim();
    const resultsDiv = document.getElementById('results');
    if (!keyword) {
        resultsDiv.innerHTML = '<p class="text-gray-500 text-center text-sm">Ketik sesuatu untuk mencari...</p>';
        return;
    }
    resultsDiv.innerHTML = '<p class="text-center text-gray-400">Mencari...</p>';

    try {
        const url = `${BASEURL}/homepage/search/ajax?keyword=${encodeURIComponent(keyword)}&filter=${encodeURIComponent(currentFilter)}`;
        const res = await fetch(url, { credentials: 'same-origin' });
        if (!res.ok) throw new Error(res.status);
        const data = await res.json();

        if (data.type === 'users') {
            if (!data.data.length) {
                resultsDiv.innerHTML = '<p class="text-center text-gray-500 text-sm">Tidak ada pengguna ditemukan.</p>';
                return;
            }
            resultsDiv.innerHTML = data.data.map(u => `
                <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3 border border-gray-100">
                    <img src="${u.PATH_PHOTO ? BASEURL + '/storage/users/photos/' + u.PATH_PHOTO : BASEURL + '/src/asset/image/default.png'}" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <p class="font-semibold text-gray-800">${escapeHtml(u.FULL_NAME)}</p>
                        <p class="text-sm text-gray-500">@${escapeHtml(u.USERNAME)}</p>
                    </div>
                </div>
            `).join('');
        } else {
            if (!data.data.length) {
                resultsDiv.innerHTML = '<p class="text-center text-gray-500 text-sm">Tidak ada postingan ditemukan.</p>';
                return;
            }
            resultsDiv.innerHTML = data.data.map(p => `
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-3">
                        <img src="${p.PATH_PHOTO ? BASEURL + '/storage/users/photos/' + p.PATH_PHOTO : BASEURL + '/src/asset/image/default.png'}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="font-semibold text-gray-800">${escapeHtml(p.FULL_NAME)}</p>
                            <p class="text-sm text-gray-500">@${escapeHtml(p.USERNAME)}</p>
                        </div>
                    </div>
                    <p class="mt-2 text-gray-700 text-sm">${escapeHtml(p.CONTENT?.slice(0,300) ?? '')}</p>
                    <div class="mt-1 text-xs text-gray-400">${p.TOTAL_LIKES ?? 0} likes • ${p.TOTAL_COMMENT ?? 0} comments</div>
                </div>
            `).join('');
        }
    } catch (err) {
        console.error(err);
        resultsDiv.innerHTML = `<p class="text-center text-red-500">Terjadi kesalahan saat mencari.</p>`;
    }
}

function escapeHtml(unsafe) {
    return String(unsafe ?? '')
      .replace(/&/g,"&amp;").replace(/</g,"&lt;")
      .replace(/>/g,"&gt;").replace(/"/g,"&quot;")
      .replace(/'/g,"&#039;");
}
</script>
