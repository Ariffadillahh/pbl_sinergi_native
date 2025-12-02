<?php
$keyword = $keyword ?? ($_GET['keyword'] ?? '');
?>
<!doctype html>
<html lang="en"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= BASEURL ?>/src/css/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <title>Search | Sinergi</title> </head>

<body class="bg-gray-50">
    <main class="w-full min-h-screen overflow-y-auto border-gray-200 hide-scrollbar relative">

        <div class="sticky top-0 z-50 bg-white/95 backdrop-blur-md w-full shadow-sm border-b border-gray-200/80">
            <div class="w-full px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex items-center justify-between gap-3 sm:gap-4">
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <h1 class="hidden sm:block text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">SINERGI</h1>
                        <img src="<?= BASEURL ?>/src/asset/icons/logo-icon.svg" class="flex w-11 h-9 shrink-0 md:hidden" alt="logo">
                    </div>

                    <form id="searchForm" class="flex-1 max-w-2xl" onsubmit="return handleSearch(event)">
                        <div class="relative group">
                            <input type="search" id="searchInput"
                                class="block w-full p-4 pr-14 text-sm text-gray-900 rounded-full bg-white shadow-md
                                     placeholder:text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 outline-none"
                                placeholder="Search for something or type @username..." value="<?= htmlspecialchars($keyword) ?>"
                                minlength="3"
                                autocomplete="off" />
                            <button type="submit"
                                class="text-white absolute right-1.5 top-1/2 -translate-y-1/2 
                                        bg-gradient-to-r from-blue-500 to-blue-600 rounded-full p-2.5 w-10 h-10 flex items-center justify-center shadow-sm cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    <div class="group relative flex justify-center">
                        <button id="quote-btn-opn" aria-label="Show Quote of the Day" class="flex items-center justify-center p-2.5 rounded-full cursor-pointer">
                            <img src="<?php echo BASEURL; ?>/src/asset/icons/quote.svg" class="h-8 w-8" alt="Quotes of the Day icon">
                        </button>

                        <div role="tooltip"
                            class="absolute top-full mt-2 left-0 -translate-x-1/2 
                                         whitespace-nowrap bg-gray-800 text-white text-sm font-medium 
                                         px-3 py-1.5 rounded-lg shadow-sm 
                                         opacity-0 invisible group-hover:opacity-100 group-hover:visible 
                                         transition-opacity duration-300">
                            Quotes of the Day
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky top-[90px] z-40 flex justify-center md:my-5 px-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 px-6 py-3 w-full max-w-xl flex justify-between">
                <button data-filter="top"
                    class="tab-btn active-tab relative px-4 py-2 text-blue-600 font-semibold transition-all duration-200 cursor-pointer">
                    Top
                </button>
                <button data-filter="latest"
                    class="tab-btn relative px-4 py-2 text-gray-500 hover:text-blue-600 transition-all duration-200 cursor-pointer">
                    Latest
                </button>
                <button data-filter="users"
                    class="tab-btn relative px-4 py-2 text-gray-500 hover:text-blue-600 transition-all duration-200 cursor-pointer">
                    Users
                </button>
            </div>
        </div>

        <div class="max-w-xl mx-auto px-5 md:px-0 mb-20 md:mb-0">
            <div id="results" class="space-y-6 mt-4">
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="text-gray-500 text-center">Type your keyword and click Search, or press Enter.</p> </div>
            </div>
        </div>
    </main>

  <script>
    const BASEURL = '<?= BASEURL ?>';
    const LOGGED_IN_USER_ID = '<?= $_SESSION['user_id'] ?? '' ?>';
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
          tabs.forEach(b => {
            b.classList.remove('text-blue-600', 'font-semibold', 'active-tab');
            b.classList.add('text-gray-500');
          });

          btn.classList.remove('text-gray-500');
          btn.classList.add('text-blue-600', 'font-semibold', 'active-tab');

          currentFilter = btn.dataset.filter;
          performSearch();
        });
      });

      const params = new URLSearchParams(window.location.search);
      const kw = params.get('keyword') || '';
      const filter = params.get('filter') || 'top';

      if (filter) {
        tabs.forEach(b => {
          b.classList.remove('text-blue-600', 'font-semibold', 'active-tab');
          b.classList.add('text-gray-500');
        });
        const activeTab = document.querySelector(`[data-filter="${filter}"]`);
        if (activeTab) {
          activeTab.classList.remove('text-gray-500');
          activeTab.classList.add('text-blue-600', 'font-semibold', 'active-tab');
          currentFilter = filter;
        }
      }

      if (kw) performSearch();
    });

    function timeAgo(dateString) {
      if (!dateString) return '';
      
      const safeDateString = dateString.replace(' ', 'T');
      const date = new Date(safeDateString);
      const now = new Date();

      if (isNaN(date.getTime())) {
        console.error("Invalid date:", dateString);
        return dateString;
      }

      const seconds = Math.floor((now - date) / 1000);

      let interval = seconds / 31536000;
      if (interval > 1) return Math.floor(interval) + "y ago"; // Translated

      interval = seconds / 2592000;
      if (interval > 1) return Math.floor(interval) + "mo ago"; // Translated

      interval = seconds / 86400;
      if (interval > 1) return Math.floor(interval) + "d ago"; // Translated

      interval = seconds / 3600;
      if (interval > 1) return Math.floor(interval) + "h ago"; // Translated

      interval = seconds / 60;
      if (interval > 1) return Math.floor(interval) + "m ago"; // Translated

      return "Just now"; // Translated
    }

    async function performSearch() {
      const keyword = document.getElementById('searchInput').value.trim();
      const resultsDiv = document.getElementById('results');

      if (!keyword) {
        resultsDiv.innerHTML = `
          <div class="flex flex-col items-center justify-center py-12">
            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-gray-500 text-center">Type something to search...</p> </div>`;
        return;
      }

      resultsDiv.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12">
          <div class="relative">
            <div class="w-10 h-10 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin"></div>
          </div>
          <p class="text-gray-600 text-center mt-4 font-medium">Searching...</p> </div>`;

      try {
        const url = `${BASEURL}/homepage/search/ajax?keyword=${encodeURIComponent(keyword)}&filter=${encodeURIComponent(currentFilter)}`;
        const res = await fetch(url, {
          credentials: 'same-origin'
        });
        if (!res.ok) throw new Error(res.status);
        const data = await res.json();

        if (data.type === 'users') {
          if (!data.data.length) {
            resultsDiv.innerHTML = `
              <div class="flex flex-col items-center justify-center py-12 text-center">
                <svg class="w-24 h-24 mb-4 text-gray-400" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true">
                  <title>Search not found</title>
                  <g stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="27" cy="27" r="14" fill="none" />
                    <path d="M38.5 38.5 L52 52" />
                    <path d="M22 22 L32 32 M32 22 L22 32" />
                  </g>
                </svg>

                <p class="text-gray-500">
                  No users matched the search for
                  <span class="font-medium text-gray-700">"${escapeHtml(keyword)}"</span>
                </p> </div>`;
            return;
          }

          resultsDiv.innerHTML = data.data.map(u => {
            const profileUrl = (LOGGED_IN_USER_ID && u.ID === LOGGED_IN_USER_ID) ?
              `${BASEURL}/profile` :
              `${BASEURL}/homepage/user/profile/${u.ID}`;

            return `<div onclick="window.location.href='${profileUrl}'" class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3 border border-gray-100 hover:shadow-md hover:border-blue-200 transition-all duration-200 cursor-pointer">
              					 <img src="${u.PATH_PHOTO ? BASEURL + '/storage/users/photos/' + u.PATH_PHOTO : BASEURL + '/src/asset/image/default.png'}" class="w-12 h-12 rounded-full object-cover">
              					 <div>
              					 	 <p class="font-semibold text-gray-800">${escapeHtml(u.FULL_NAME)}</p>
              					 	 <p class="text-sm text-gray-500">@${escapeHtml(u.USERNAME)}</p>
              					 </div>
              				 </div>`;
          }).join('');

          return;
        }

        if (!data.data.length) {
          resultsDiv.innerHTML = `
          <div class="flex flex-col items-center justify-center py-12">
            <div class="bg-gray-100 rounded-full p-6 mb-4">
              	 <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">No Posts Found</h3> <p class="text-gray-500 text-center text-sm max-w-sm">No posts matched the search for "${escapeHtml(keyword)}"</p> </div>`;
          return;
        }
        
        // Role mapping consistent with PHP template
        const roleDisplay = {
            "MAHASISWA": "STUDENT",
            "ADMIN": "ADMIN",
            "DOSEN": "LECTURER",
            "MITRA": "PARTNER",
            "ALUMNI": "ALUMNI"
        };
        
        resultsDiv.innerHTML = data.data.map(p => {
          // Role badge mapping (CSS classes remain the same for consistency)
          const role = p.ROLE || 'MAHASISWA';
          const roleText = roleDisplay[role] || role; // Get English display text
          const roleClasses = {
            "MAHASISWA": "bg-blue-100 text-blue-800",
            "ADMIN": "bg-red-100 text-red-800",
            "DOSEN": "bg-green-100 text-green-800",
            "MITRA": "bg-gray-100 text-gray-800",
            "ALUMNI": "bg-yellow-100 text-yellow-800"
          };
          const colorClass = roleClasses[role] || "bg-gray-100 text-gray-800";
          
          return `
          <div class="my-6 bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden hover:shadow-md hover:border-blue-200 transition-all duration-200 group relative cursor-pointer"
            onclick="window.location.href='${BASEURL}/homepage/reply/${p.POST_ID}'">

            <div class="p-4">
              <div class="flex items-start gap-3">
                <img src="${p.PATH_PHOTO ? BASEURL + '/storage/users/photos/' + p.PATH_PHOTO : BASEURL + '/src/asset/image/default.png'}" 
              					 class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-semibold text-gray-800">${escapeHtml(p.FULL_NAME)}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium ${colorClass}">
              					 ${escapeHtml(roleText)} </span>
                  </div>
                  <div class="flex items-center gap-1 text-sm mt-0.5">
                    <span class="text-gray-500">@${escapeHtml(p.USERNAME)}</span>
                    <span class="text-gray-400">·</span>
                    <span class="text-gray-400 time-ago" data-time="${p.CREATED_AT}">
              					 ${new Date(p.CREATED_AT).toLocaleDateString()}
                    </span>
                  </div>
                </div>
              </div>

              <p class="mt-3 text-gray-700 text-sm leading-relaxed">${p.CONTENT ?? ''}</p>
            </div>

            ${p.MEDIA && p.MEDIA.length > 0 ? `
              <div class="bg-gradient-to-b from-gray-300 to-white overflow-hidden pointer-events-auto"
              					 onclick="event.stopPropagation()">
                <swiper-container class="mySwiper w-full aspect-video" init="false">
                  ${p.MEDIA.map(path => `
                    <swiper-slide>
                      <img src="${BASEURL + '/' + path}" class="w-full h-full object-contain bg-gray-50">
                    </swiper-slide>`).join('')}
                </swiper-container>
              </div>` : ''}

            <div class="border-t border-gray-100 p-2 flex justify-center gap-2 bg-white">
              <button class="like-btn flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl transition-all hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 cursor-pointer group w-1/2 relative overflow-hidden" 
              					 data-post-id="${p.POST_ID}" 
              					 data-liked="${p.IS_LIKED ? 'true' : 'false'}" 
              					 onclick="event.stopPropagation()">
                <div class="absolute inset-0 bg-gradient-to-r from-red-500/0 to-pink-500/0 group-hover:from-red-500/5 group-hover:to-pink-500/5 transition-all duration-300"></div>
                <svg class="w-5 h-5 transition-all duration-300 relative z-10 ${p.IS_LIKED ? 'text-red-500 fill-red-500' : 'text-gray-600 group-hover:text-red-500 group-hover:scale-110'}" 
              					 fill="${p.IS_LIKED ? 'currentColor' : 'none'}" 
              					 stroke="currentColor" 
              					 viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="like-count text-gray-700 group-hover:text-red-500 text-sm font-semibold transition-colors relative z-10">${p.TOTAL_LIKES ?? 0}</span>
              </button>
              
              <a href="${BASEURL}/homepage/reply/${p.POST_ID}" 
              					 class="flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-xl transition-all hover:bg-gradient-to-r hover:from-blue-50 hover:to-cyan-50 cursor-pointer group w-1/2 relative overflow-hidden"
              					 onclick="event.stopPropagation()">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/0 to-cyan-500/0 group-hover:from-blue-500/5 group-hover:to-cyan-500/5 transition-all duration-300"></div>
                <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-600 group-hover:scale-110 transition-all duration-300 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span class="text-gray-700 group-hover:text-blue-600 text-sm font-semibold transition-colors relative z-10">${p.TOTAL_COMMENT ?? 0}</span>
              </a>
            </div>
          </div>`;
        }).join('');

        // Apply time ago
        document.querySelectorAll('.time-ago').forEach(el => {
          const rawDate = el.getAttribute('data-time');
          if (rawDate) {
            el.textContent = timeAgo(rawDate);
          }
        });

        customElements.whenDefined('swiper-container').then(() => {
          const swiperElements = document.querySelectorAll('swiper-container.mySwiper');

          const style = `
            .swiper-button-next,
            .swiper-button-prev {
              opacity: 0;
              transition: opacity 0.3s ease;
              color: #ffffff; 
              padding: 6px;
              background-color: rgba(0, 0, 0, 0.2); 
              border-radius: 50%;
              width: 15px;
              height: 15px;
              --swiper-navigation-size: 16px; 
            }

            :host(:hover) .swiper-button-next,
            :host(:hover) .swiper-button-prev {
              opacity: 1;
            }

            .swiper-button-disabled {
              opacity: 0 !important;
              pointer-events: none;
            }
          `;

          const swiperParams = {
            navigation: true,
            pagination: {
              clickable: true,
              dynamicBullets: true,
            },
            injectStyles: [style],
          };

          swiperElements.forEach(swiperEl => {
            Object.assign(swiperEl, swiperParams);
            swiperEl.initialize();
          });
        });

        document.querySelectorAll('.like-btn').forEach(btn => {
          btn.addEventListener('click', async e => {
            e.stopPropagation();
            const postId = btn.dataset.postId;
            const icon = btn.querySelector('svg');
            const countSpan = btn.querySelector('.like-count');
            
            // Disable button during request
            btn.disabled = true;
            btn.style.opacity = '0.6';
            
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
                
                // Animate count
                countSpan.classList.add('scale-110', 'text-blue-600');
                setTimeout(() => {
                  countSpan.classList.remove('scale-110', 'text-blue-600');
                }, 200);
                
                // Update icon
                if (isLiked) {
                  icon.classList.remove('text-gray-600', 'group-hover:text-red-500', 'group-hover:scale-110');
                  icon.classList.add('text-red-500', 'fill-red-500', 'scale-110');
                  icon.setAttribute('fill', 'currentColor');
                  setTimeout(() => icon.classList.remove('scale-110'), 300);
                } else {
                  icon.classList.remove('text-red-500', 'fill-red-500');
                  icon.classList.add('text-gray-600', 'group-hover:text-red-500', 'group-hover:scale-110');
                  icon.setAttribute('fill', 'none');
                }
              }
            } catch (err) {
              console.error(err);
              alert('Gagal memproses like');
            } finally {
              btn.disabled = false;
              btn.style.opacity = '1';
            }
          });
        });

      } catch (err) {
        console.error(err);
        resultsDiv.innerHTML = `
          <div class="flex flex-col items-center justify-center py-12">
              <div class="bg-red-100 rounded-full p-6 mb-4">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800 mb-2">Terjadi Kesalahan</h3>
              <p class="text-gray-500 text-center text-sm">Unable to load search results. Please try again.</p>
          </div>`;
      }
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
    /* Smooth transition untuk like button */
    .like-btn svg {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .like-btn:active svg {
      transform: scale(0.9);
    }

    .like-btn:disabled {
      cursor: not-allowed;
    }

    /* Animation untuk like count */
    .like-count {
      transition: all 0.3s ease;
    }
  </style>
</body>

</html>