<?php
$keyword = $keyword ?? ($_GET['keyword'] ?? '');
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?= BASEURL ?>/src/css/output.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
  <title>Search | Sinergi</title>
</head>

<body class="bg-gray-50">
  <main class="w-full h-screen overflow-y-auto overflow-x-hidden border-gray-200 hide-scrollbar relative bg-gray-50/50">

    <div class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl w-full border-b border-gray-100">
      <div class="w-full px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center justify-between gap-3 sm:gap-4">

          <div class="flex items-center gap-2 flex-shrink-0">

            <a href="<?= BASEURL ?>/homepage" class="flex gap-2 items-center">
              <img src="<?php echo BASEURL; ?>/src/asset/icons/logo-icon.svg" class="flex w-11 h-9 shrink-0 lg:hidden" alt="logo">
              <h1 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                SINERGI
              </h1>
            </a>
          </div>

          <form id="searchFormDesktop" class="hidden lg:block flex-1 max-w-2xl mx-4" onsubmit="return handleSearch(event, 'desktop')">
            <div class="relative group">
              <input type="search" id="desktop-search-input"
                class="block w-full p-4 pr-14 text-sm text-gray-900 rounded-full bg-white shadow-md border border-gray-100 placeholder:text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 outline-none"
                placeholder="Search for something..." autocomplete="off" minlength="3" required value="<?= htmlspecialchars($keyword) ?>" />

              <button type="submit"
                class="text-white absolute right-1.5 top-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-full p-2.5 w-10 h-10 flex items-center justify-center shadow-sm hover:shadow-md transition-all duration-200 active:scale-95 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
              </button>
            </div>
          </form>

          <div class="flex items-center gap-3">
            <button type="button" onclick="openMobileSearch()"
              class="lg:hidden p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-all cursor-pointer">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
            </button>

            <div class="group relative flex justify-center">
              <button id="quote-btn-opn" aria-label="Show Quote of the Day" class="flex items-center justify-center p-2.5 rounded-full cursor-pointer hover:bg-gray-50 transition-colors">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/quote.svg" class="h-8 w-8" alt="Quotes of the Day icon">
              </button>
              <div role="tooltip" class="absolute top-full mt-2 left-1/2 -translate-x-1/2 whitespace-nowrap bg-gray-800 text-white text-sm font-medium px-3 py-1.5 rounded-lg shadow-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300 z-50">
                Daily quotes
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="max-w-xl mx-auto px-4 md:px-0 pt-6 pb-20">

      <?php if (!empty($keyword)): ?>
        <div class="mb-6 flex flex-col items-center text-center animate-fade-in-down lg:hidden">
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-100 rounded-full shadow-sm">
            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="text-sm text-gray-600">Results for:</span>
            <span class="text-sm font-bold text-blue-700">"<?= htmlspecialchars($keyword) ?>"</span>
          </div>
        </div>
      <?php endif; ?>

      <div class="sticky top-[75px] z-40 bg-gray-50/95 backdrop-blur rounded-2xl mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-1.5 flex justify-between">
          <button data-filter="top" class="tab-btn flex-1 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-blue-600 bg-blue-50 shadow-sm">
            Top
          </button>
          <button data-filter="latest" class="tab-btn flex-1 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50">
            Latest
          </button>
          <button data-filter="users" class="tab-btn flex-1 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50">
            Users
          </button>
        </div>
      </div>

      <div id="results" class="space-y-5 min-h-[300px]">
        <div class="flex flex-col items-center justify-center py-16 opacity-60">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <p class="text-gray-500 font-medium">Type a keyword to start searching</p>
        </div>
      </div>

    </div>
  </main>

  <div id="mobileSearchModal" class="fixed inset-0 z-[100000] bg-white hidden flex-col transition-all duration-300">
    <div class="flex items-center justify-between p-4 border-b border-gray-100">
      <span class="text-lg font-bold text-gray-800">Search</span>
      <button type="button" onclick="closeMobileSearch()" class="p-2 text-gray-500 hover:bg-gray-100 rounded-full cursor-pointer">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    <div class="p-4">
      <form id="searchFormMobile" onsubmit="return handleSearch(event, 'mobile')">
        <div class="relative">
          <input type="search" id="mobile-search-input"
            class="block w-full p-4 pl-12 text-sm text-gray-900 rounded-xl bg-gray-50 border-none focus:ring-2 focus:ring-blue-500 outline-none"
            placeholder="Search for something..."
            value="<?= htmlspecialchars($keyword) ?>"
            autocomplete="off" minlength="3" required />

          <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>

          <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 text-white px-4 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-700 cursor-pointer">
            Go
          </button>
        </div>
      </form>
    </div>
  </div>
  </main>

  <?php require_once 'app/views/components/quotes.php' ?>


  <script>
    const BASEURL = '<?= BASEURL ?>';
    const LOGGED_IN_USER_ID = '<?= $_SESSION['user_id'] ?? '' ?>';
    let currentFilter = 'top';


    function openMobileSearch() {
      const modal = document.getElementById('mobileSearchModal');
      const mobileInput = document.getElementById('mobile-search-input');
      const desktopInput = document.getElementById('desktop-search-input');

      if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        if (desktopInput && mobileInput) {
          mobileInput.value = desktopInput.value;
        }

        if (mobileInput) {
          setTimeout(() => {
            mobileInput.focus();
            const val = mobileInput.value;
            mobileInput.value = '';
            mobileInput.value = val;
          }, 100);
        }
      }
    }

    function closeMobileSearch() {
      const modal = document.getElementById('mobileSearchModal');
      if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
      }
    }


    function handleSearch(e, source) {
      e.preventDefault();

      let q = '';

      if (source === 'mobile') {
        const input = document.getElementById('mobile-search-input');
        q = input ? input.value.trim() : '';
      } else {
        const input = document.getElementById('desktop-search-input');
        q = input ? input.value.trim() : '';
      }

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
          tabs.forEach(b => {
            b.className = 'tab-btn flex-1 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50';
          });

          btn.className = 'tab-btn flex-1 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-blue-600 bg-blue-50 shadow-sm';

          currentFilter = btn.dataset.filter;
          performSearch();
        });
      });

      const params = new URLSearchParams(window.location.search);
      const kw = params.get('keyword') || '';
      const filter = params.get('filter') || 'top';

      if (filter) {
        tabs.forEach(b => {
          b.className = 'tab-btn flex-1 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50';
        });
        const activeTab = document.querySelector(`[data-filter="${filter}"]`);
        if (activeTab) {
          activeTab.className = 'tab-btn flex-1 py-2 text-sm font-medium rounded-lg transition-all duration-200 text-blue-600 bg-blue-50 shadow-sm';
          currentFilter = filter;
        }
      }

      if (kw) {
        performSearch();
      }
    });


    async function performSearch() {
      const params = new URLSearchParams(window.location.search);
      const keyword = params.get('keyword') || '';

      const resultsDiv = document.getElementById('results');

      if (!keyword) {
        resultsDiv.innerHTML = `
                <div class="flex flex-col items-center justify-center py-16 opacity-60">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">Type a keyword to start searching</p>
                </div>`;
        return;
      }

      resultsDiv.innerHTML = `
            <div class="flex flex-col items-center justify-center py-12">
                <div class="relative">
                    <div class="w-10 h-10 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin"></div>
                </div>
                <p class="text-gray-600 text-center mt-4 font-medium">Searching for "${escapeHtml(keyword)}"...</p>
            </div>`;

      try {
        const url = `${BASEURL}/homepage/search/ajax?keyword=${encodeURIComponent(keyword)}&filter=${encodeURIComponent(currentFilter)}`;
        const res = await fetch(url, {
          credentials: 'same-origin'
        });

        if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

        const data = await res.json();

        // --- RENDER USERS ---
        if (data.type === 'users') {
          if (!data.data.length) {
            renderNoResults(resultsDiv, keyword);
            return;
          }

          resultsDiv.innerHTML = data.data.map(u => {
            const profileUrl = (LOGGED_IN_USER_ID && u.ID === LOGGED_IN_USER_ID) ?
              `${BASEURL}/profile` :
              `${BASEURL}/homepage/user/profile/${u.ID}`;

            return `
                    <div onclick="window.location.href='${profileUrl}'" class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3 border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all duration-200 cursor-pointer">
                        <img src="${u.PATH_PHOTO ? BASEURL + '/storage/users/photos/' + u.PATH_PHOTO : BASEURL + '/src/asset/image/default.png'}" class="w-12 h-12 rounded-full object-cover border border-gray-100">
                        <div>
                            <p class="font-semibold text-gray-800">${escapeHtml(u.FULL_NAME)}</p>
                            <p class="text-sm text-gray-500">@${escapeHtml(u.USERNAME)}</p>
                        </div>
                    </div>`;
          }).join('');
          return;
        }

        // --- RENDER POSTS ---
        if (!data.data.length) {
          renderNoResults(resultsDiv, keyword);
          return;
        }

        const roleDisplay = {
          "MAHASISWA": "STUDENT",
          "ADMIN": "ADMIN",
          "DOSEN": "LECTURER",
          "MITRA": "PARTNER",
          "ALUMNI": "ALUMNI"
        };
        const roleClasses = {
          "MAHASISWA": "bg-blue-100 text-blue-800",
          "ADMIN": "bg-red-100 text-red-800",
          "DOSEN": "bg-green-100 text-green-800",
          "MITRA": "bg-gray-100 text-gray-800",
          "ALUMNI": "bg-yellow-100 text-yellow-800"
        };

        resultsDiv.innerHTML = data.data.map(p => {
          const role = p.ROLE || 'MAHASISWA';
          const roleText = roleDisplay[role] || role;
          const colorClass = roleClasses[role] || "bg-gray-100 text-gray-800";

          let mediaHtml = '';
          if (p.MEDIA && p.MEDIA.length > 0) {
            mediaHtml = `
                    <div class="bg-gradient-to-b from-gray-50 to-white overflow-hidden pointer-events-auto border-t border-b border-gray-100" onclick="event.stopPropagation()">
                        <swiper-container class="mySwiper w-full aspect-video" init="false">
                            ${p.MEDIA.map(path => `
                                <swiper-slide class="flex items-center justify-center bg-gray-50">
                                    <img src="${BASEURL + '/' + path}" class="w-full h-full object-contain max-h-[400px]">
                                </swiper-slide>`).join('')}
                        </swiper-container>
                    </div>`;
          }

          return `
                <div class="my-6 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden hover:shadow-md hover:border-blue-200 transition-all duration-200 group relative cursor-pointer"
                     onclick="window.location.href='${BASEURL}/homepage/reply/${p.POST_ID}'">
                    
                    <div class="p-4">
                        <div class="flex items-start gap-3">
                            <img src="${p.PATH_PHOTO ? BASEURL + '/storage/users/photos/' + p.PATH_PHOTO : BASEURL + '/src/asset/image/default.png'}" class="w-10 h-10 rounded-full object-cover flex-shrink-0 border border-gray-100">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-semibold text-gray-900 text-sm">${escapeHtml(p.FULL_NAME)}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase ${colorClass}">${escapeHtml(roleText)}</span>
                                </div>
                                <div class="flex items-center gap-1 text-xs mt-0.5 text-gray-500">
                                    <span>@${escapeHtml(p.USERNAME)}</span>
                                    <span>·</span>
                                    <span class="time-ago" data-time="${p.CREATED_AT}">${new Date(p.CREATED_AT).toLocaleDateString()}</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 text-gray-800 text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(p.CONTENT ?? '')}</p>
                    </div>

                    ${mediaHtml}

                    <div class="border-t border-gray-100 p-2 flex justify-center gap-2 bg-white">
                        <button class="like-btn flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition-all hover:bg-red-50 cursor-pointer group w-1/2 relative overflow-hidden" 
                                data-post-id="${p.POST_ID}" 
                                data-liked="${p.IS_LIKED ? 'true' : 'false'}" 
                                onclick="event.stopPropagation()">
                            <svg class="w-5 h-5 transition-all duration-300 ${p.IS_LIKED ? 'text-red-500 fill-red-500' : 'text-gray-400 group-hover:text-red-500 group-hover:scale-110'}" 
                                 fill="${p.IS_LIKED ? 'currentColor' : 'none'}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span class="like-count ml-2 text-gray-600 group-hover:text-red-500 text-sm font-medium transition-colors">${p.TOTAL_LIKES ?? 0}</span>
                        </button>
                        
                        <a href="${BASEURL}/homepage/reply/${p.POST_ID}" class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg transition-all hover:bg-blue-50 cursor-pointer group w-1/2 relative overflow-hidden" onclick="event.stopPropagation()">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 group-hover:scale-110 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span class="ml-2 text-gray-600 group-hover:text-blue-600 text-sm font-medium transition-colors">${p.TOTAL_COMMENT ?? 0}</span>
                        </a>
                    </div>
                </div>`;
        }).join('');

        document.querySelectorAll('.time-ago').forEach(el => {
          const rawDate = el.getAttribute('data-time');
          if (rawDate) el.textContent = timeAgo(rawDate);
        });
        initSwiper();
        attachLikeListeners();

      } catch (err) {
        console.error(err);
        renderErrorState(resultsDiv);
      }
    }

    function renderNoResults(container, keyword) {
      container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-16 opacity-80 animate-fade-in-up">
                <div class="bg-gray-100 p-4 rounded-full mb-4">
                    <svg class="w-10 h-10 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <h3 class="text-gray-900 font-semibold text-lg mb-1">No matches found</h3>
                <p class="text-gray-500 text-center max-w-xs">We couldn't find anything matching <span class="font-semibold text-gray-800">"${escapeHtml(keyword)}"</span></p>
            </div>`;
    }

    function renderErrorState(container) {
      container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-12">
                <div class="bg-red-50 rounded-full p-4 mb-4">
                     <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-1">Something went wrong</h3>
                <p class="text-gray-500 text-center text-sm">Please try searching again later.</p>
            </div>`;
    }

    function initSwiper() {
      customElements.whenDefined('swiper-container').then(() => {
        const swiperElements = document.querySelectorAll('swiper-container.mySwiper');
        const style = `
                .swiper-button-next, .swiper-button-prev { opacity: 0; transition: opacity 0.3s ease; color: #fff; padding: 10px; background-color: rgba(0,0,0,0.3); border-radius: 50%; width: 20px; height: 20px; backdrop-filter: blur(2px); }
                :host(:hover) .swiper-button-next, :host(:hover) .swiper-button-prev { opacity: 1; }
                .swiper-button-disabled { opacity: 0 !important; pointer-events: none; }
                .swiper-pagination-bullet-active { background: #3B82F6 !important; }
            `;
        const swiperParams = {
          navigation: true,
          pagination: {
            clickable: true,
            dynamicBullets: true
          },
          injectStyles: [style],
        };
        swiperElements.forEach(swiperEl => {
          Object.assign(swiperEl, swiperParams);
          swiperEl.initialize();
        });
      });
    }

    function attachLikeListeners() {
      document.querySelectorAll('.like-btn').forEach(btn => {
        btn.replaceWith(btn.cloneNode(true));
      });

      document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
          e.stopPropagation();
          const postId = btn.dataset.postId;
          const icon = btn.querySelector('svg');
          const countSpan = btn.querySelector('.like-count');

          btn.disabled = true;
          btn.style.opacity = '0.7';

          try {
            const res = await fetch(`${BASEURL}/like/toggle`, {
              method: 'POST',
              body: new URLSearchParams({
                post_id: postId
              }),
              credentials: 'same-origin'
            });
            const data = await res.json();

            if (data.success) {
              const isLiked = data.action === 'liked';
              btn.dataset.liked = isLiked ? 'true' : 'false';
              countSpan.textContent = data.total_likes ?? 0;

              countSpan.classList.add('scale-125', 'text-red-600');
              setTimeout(() => countSpan.classList.remove('scale-125', 'text-red-600'), 200);

              if (isLiked) {
                icon.classList.remove('text-gray-400', 'group-hover:text-red-500');
                icon.classList.add('text-red-500', 'fill-red-500', 'scale-110');
                icon.setAttribute('fill', 'currentColor');
              } else {
                icon.classList.remove('text-red-500', 'fill-red-500', 'scale-110');
                icon.classList.add('text-gray-400', 'group-hover:text-red-500');
                icon.setAttribute('fill', 'none');
              }
            }
          } catch (err) {
            console.error(err);
          } finally {
            btn.disabled = false;
            btn.style.opacity = '1';
          }
        });
      });
    }

    function timeAgo(dateString) {
      if (!dateString) return '';
      const safeDateString = dateString.replace(' ', 'T');
      const date = new Date(safeDateString);
      const now = new Date();
      if (isNaN(date.getTime())) return dateString;

      const seconds = Math.floor((now - date) / 1000);
      let interval = seconds / 31536000;
      if (interval > 1) return Math.floor(interval) + "y";
      interval = seconds / 2592000;
      if (interval > 1) return Math.floor(interval) + "mo";
      interval = seconds / 86400;
      if (interval > 1) return Math.floor(interval) + "d";
      interval = seconds / 3600;
      if (interval > 1) return Math.floor(interval) + "h";
      interval = seconds / 60;
      if (interval > 1) return Math.floor(interval) + "m";
      return "Just now";
    }

    function escapeHtml(str) {
      return String(str ?? '').replace(/[&<>"']/g, m => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      } [m]));
    }
  </script>

  <style>
    .like-btn svg {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .like-btn:active svg {
      transform: scale(0.9);
    }

    .like-btn:disabled {
      cursor: not-allowed;
    }

    .like-count {
      transition: all 0.3s ease;
    }
  </style>
</body>

</html>