<?php
$keyword = $keyword ?? ($_GET['keyword'] ?? '');
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?= BASEURL ?>/src/css/output.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
  <title>Pencarian | Sinergi</title>
</head>

<body class="bg-gray-50">
  <main class="w-full min-h-screen overflow-y-auto border-gray-200 hide-scrollbar relative">

    <!-- 🔹 HEADER -->
    <div class="sticky top-0 z-50 bg-white/95 backdrop-blur-md w-full shadow-sm border-b border-gray-200/80">
      <div class="w-full px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center justify-between gap-3 sm:gap-4">
          <div class="flex items-center gap-2 flex-shrink-0">
            <h1 class="hidden sm:block text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">SINERGI</h1>
            <img src="<?= BASEURL ?>/src/asset/icons/logo-icon.svg" class="flex w-11 h-9 shrink-0 md:hidden" alt="logo">
          </div>

          <!-- 🔍 Search -->
          <form id="searchForm" class="flex-1 max-w-2xl" onsubmit="return handleSearch(event)">
            <div class="relative group">
              <input type="search" id="searchInput"
                class="block w-full p-4 pr-14 text-sm text-gray-900 rounded-full bg-white shadow-md 
                       border border-gray-200 placeholder:text-gray-400 
                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Cari sesuatu atau ketik @username..."
                value="<?= htmlspecialchars($keyword) ?>"
                autocomplete="off" />
              <button type="submit"
                class="text-white absolute right-1.5 top-1/2 -translate-y-1/2 
                       bg-gradient-to-r from-blue-500 to-blue-600 rounded-full p-2.5 w-10 h-10 flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
              </button>
            </div>
          </form>

          <?php require_once 'app/views/components/notifikasi.php' ?>
        </div>
      </div>
    </div>

    <div class="sticky top-[70px] z-40 flex justify-center my-5">
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-6 py-3 w-full max-w-xl flex justify-between">
        <button data-filter="top"
          class="tab-btn relative px-4 py-2 text-blue-600 font-semibold after:absolute after:left-0 after:bottom-0 after:h-[2px] after:w-full after:bg-blue-600">
          Top
        </button>
        <button data-filter="latest"
          class="tab-btn relative px-4 py-2 text-gray-500 hover:text-blue-600 after:absolute after:left-0 after:bottom-0 after:h-[2px] after:w-full after:bg-transparent hover:after:bg-blue-100">
          Latest
        </button>
        <button data-filter="users"
          class="tab-btn relative px-4 py-2 text-gray-500 hover:text-blue-600 after:absolute after:left-0 after:bottom-0 after:h-[2px] after:w-full after:bg-transparent hover:after:bg-blue-100">
          Users
        </button>
      </div>
    </div>

    <!-- 🔹 HASIL -->
    <div class="max-w-xl mx-auto px-5 md:px-0 py-5 mb-20 md:mb-0">
      <div id="results" class="space-y-6 mt-4">
        <p class="my-4 text-center text-gray-400">Ketik keyword lalu klik Cari, atau tekan Enter.</p>
      </div>
    </div>
  </main>

<script>
const BASEURL = '<?= BASEURL ?>';
let currentFilter = 'top';

function handleSearch(e) {
  e.preventDefault();
  const q = document.getElementById('searchInput').value.trim();
  if (!q) {
    window.location.href = `${BASEURL}/homepage/search`;
    return false;
  }
  window.location.href = `${BASEURL}/homepage/search?keyword=${encodeURIComponent(q)}&filter=${currentFilter}`;
  return false;
}

document.addEventListener('DOMContentLoaded', () => {
  const tabs = document.querySelectorAll('.tab-btn');

  tabs.forEach(btn => {
    btn.addEventListener('click', () => {
      // reset semua tab
      tabs.forEach(b => {
        b.classList.remove('text-blue-600', 'font-semibold');
        b.classList.add('text-gray-500');
        b.querySelector('::after');
        b.style.setProperty('--underline-color', 'transparent');
        b.style.setProperty('color', '#6b7280');
        b.style.setProperty('--underline', 'transparent');
        b.style.removeProperty('border-bottom');
        b.style.removeProperty('after:bg-blue-600');
        b.style.removeProperty('after:bg-transparent');
        b.classList.remove('active-tab');
        b.style.setProperty('--after-bg', 'transparent');
        b.style.setProperty('--after-color', 'transparent');
        b.style.removeProperty('after:content');
        b.style.setProperty('after-bg', 'transparent');
        b.style.setProperty('after-color', 'transparent');
        b.style.removeProperty('after-bg');
      });

      // aktifkan tab diklik
      btn.classList.add('text-blue-600', 'font-semibold');
      btn.style.setProperty('--underline', '#2563eb');
      btn.style.borderBottom = '2px solid #2563eb';
      currentFilter = btn.dataset.filter;

      performSearch();
    });
  });

  const params = new URLSearchParams(window.location.search);
  const kw = params.get('keyword') || '';
  if (kw) performSearch();
});

async function performSearch() {
  const keyword = document.getElementById('searchInput').value.trim();
  const resultsDiv = document.getElementById('results');
  if (!keyword) {
    resultsDiv.innerHTML = '<p class="my-4 text-gray-500 text-center text-sm">Ketik sesuatu untuk mencari...</p>';
    return;
  }
  resultsDiv.innerHTML = '<p class="text-center my-4 text-gray-500">Mencari...</p>';

  try {
    const url = `${BASEURL}/homepage/search/ajax?keyword=${encodeURIComponent(keyword)}&filter=${encodeURIComponent(currentFilter)}`;
    const res = await fetch(url, { credentials: 'same-origin' });
    if (!res.ok) throw new Error(res.status);
    const data = await res.json();

    // === USERS ===
    if (data.type === 'users') {
      resultsDiv.innerHTML = data.data.length
        ? data.data.map(u => `
          <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3 border border-gray-100 hover:bg-gray-50 transition">
            <img src="${u.PATH_PHOTO ? BASEURL + '/storage/users/photos/' + u.PATH_PHOTO : BASEURL + '/src/asset/image/default.png'}" class="w-12 h-12 rounded-full object-cover">
            <div>
              <p class="font-semibold text-gray-800">${escapeHtml(u.FULL_NAME)}</p>
              <p class="text-sm text-gray-500">@${escapeHtml(u.USERNAME)}</p>
            </div>
          </div>
        `).join('')
        : '<p class="my-4 text-center text-gray-500 text-sm">Tidak ada pengguna ditemukan.</p>';
      return;
    }

    // === POSTS ===
    if (!data.data.length) {
      resultsDiv.innerHTML = '<p class="my-4 text-center text-gray-500 text-sm">Tidak ada postingan ditemukan.</p>';
      return;
    }

    resultsDiv.innerHTML = data.data.map(p => `
      <div class="my-6 bg-white border border-gray-200 rounded-2xl shadow-sm p-4 hover:bg-gray-50 transition group relative overflow-hidden"
          onclick="window.location.href='${BASEURL}/homepage/reply/${p.POST_ID}'">

        <div class="flex items-center gap-3">
          <img src="${p.PATH_PHOTO ? BASEURL + '/storage/users/photos/' + p.PATH_PHOTO : BASEURL + '/src/asset/image/default.png'}" class="w-10 h-10 rounded-full object-cover">
          <div>
            <p class="font-semibold text-gray-800">${escapeHtml(p.FULL_NAME)}</p>
            <p class="text-sm text-gray-500">@${escapeHtml(p.USERNAME)}</p>
          </div>
        </div>

        <p class="mt-2 text-gray-700 text-sm leading-relaxed">${escapeHtml(p.CONTENT?.slice(0,300) ?? '')}</p>

        ${p.MEDIA && p.MEDIA.length > 0 ? `
          <div class="mt-3 rounded-2xl overflow-hidden border border-gray-100 pointer-events-auto"
              onclick="event.stopPropagation()">
            <swiper-container class="mySwiper w-full aspect-video" navigation="true" pagination="true" loop="false">
              ${p.MEDIA.map(path => `
                <swiper-slide>
                  <img src="${BASEURL + '/' + path}" class="w-full h-full object-contain bg-gray-50">
                </swiper-slide>`).join('')}
            </swiper-container>
          </div>` : ''}

        <div class="mt-4 flex items-center justify-between text-gray-500 text-sm border-t border-gray-100 pt-3">
          <div class="flex items-center space-x-6">
            <button class="like-btn flex items-center hover:text-red-500 transition-colors group cursor-pointer"
              data-post-id="${p.POST_ID}" data-liked="${p.IS_LIKED ? 'true' : 'false'}"
              onclick="event.stopPropagation()">
              <div class="p-2">
                <svg class="w-5 h-5 ${p.IS_LIKED ? 'text-red-500 fill-red-500' : ''}"
                  fill="${p.IS_LIKED ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
              </div>
              <span class="like-count">${p.TOTAL_LIKES ?? 0}</span>
            </button>

            <a href="${BASEURL}/homepage/reply/${p.POST_ID}" class="flex items-center hover:text-blue-600 transition-colors group cursor-pointer" onclick="event.stopPropagation()">
              <div class="p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
              </div>
              <span>${p.TOTAL_COMMENT ?? 0}</span>
            </a>
          </div>
        </div>
      </div>`).join('');

    // === Tombol like ===
    document.querySelectorAll('.like-btn').forEach(btn => {
      btn.addEventListener('click', async e => {
        e.stopPropagation();
        const postId = btn.dataset.postId;
        const icon = btn.querySelector('svg');
        const countSpan = btn.querySelector('.like-count');
        try {
          const res = await fetch(`${BASEURL}/like/toggle`, {
            method: 'POST',
            body: new URLSearchParams({ post_id: postId }),
            credentials: 'same-origin'
          });
          const data = await res.json();
          if (data.success) {
            const isLiked = data.action === 'liked';
            countSpan.textContent = data.total_likes ?? 0;
            icon.classList.toggle('text-red-500', isLiked);
            icon.classList.toggle('fill-red-500', isLiked);
            icon.setAttribute('fill', isLiked ? 'currentColor' : 'none');
          }
        } catch (err) { console.error(err); }
      });
    });

  } catch (err) {
    console.error(err);
    resultsDiv.innerHTML = `<p class="my-4 text-center text-red-500">Terjadi kesalahan saat mencari.</p>`;
  }
}

function escapeHtml(str) {
  return String(str ?? '').replace(/[&<>"']/g, m => (
    {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]
  ));
}
</script>
</body>
</html>
