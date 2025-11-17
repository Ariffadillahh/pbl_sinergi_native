 <div id="content-all-forums" class="<?= $activeTab == 'all-forums' ? '' : 'hidden' ?> p-6">
     <div class="mb-4">
         <div class="relative max-w-md">
             <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                 <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 20 20">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                         stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                 </svg>
             </div>
             <input type="search" id="search-all-forum"
                 class="block w-full p-2.5 ps-10 text-sm text-gray-900 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                 placeholder="Cari forum..." autocomplete="off" value="<?= htmlspecialchars($allSearch) ?>" />
         </div>
     </div>

     <div class="mb-6">
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
             <?php foreach ($forums as $forum): ?>
                 <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                     <div class="flex items-start gap-3">

                         <?php if (!empty($forum['PATH_PHOTO'])): ?>
                             <img src="<?= BASEURL . '/storage/forums/photos/' . $forum['PATH_PHOTO'] ?>"
                                 class="w-12 h-12 rounded-full object-cover flex-shrink-0 border border-gray-200">
                         <?php else: ?>
                             <div
                                 class="w-12 h-12 rounded-full bg-pink-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                 <?= strtoupper(substr($forum['NAME'], 0, 2)) ?>
                             </div>
                         <?php endif; ?>

                         <div class="flex-1 min-w-0">
                             <div class="flex justify-between items-start mb-2">
                                 <div>
                                     <h3 class="font-semibold text-gray-900">
                                         <?= htmlspecialchars($forum['NAME']) ?>
                                     </h3>
                                     <p class="text-sm text-gray-500">
                                         <?= $forum['TOTAL_MEMBERS'] ?> Anggota
                                     </p>
                                 </div>
                                 <span class="px-2 py-1 <?= $forum['IS_PRIVATE'] == 1 ? 'bg-gray-100 text-gray-700' : 'bg-blue-100 text-blue-700' ?> text-xs rounded-full whitespace-nowrap">
                                     <?= $forum['IS_PRIVATE'] == 1 ? 'Privat' : 'Publik' ?>
                                 </span>
                             </div>
                             <p class="text-sm text-gray-600 italic">
                                 Dibuat oleh <?= htmlspecialchars($forum['OWNER_NAME']) ?>
                             </p>
                         </div>
                     </div>
                 </div>
             <?php endforeach; ?>

             <?php if (empty($forums)): ?>
                 <p class="text-gray-500 col-span-full">Tidak ada forum yang ditemukan.</p>
             <?php endif; ?>

         </div>
     </div>

     <!-- Pagination -->
     <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-gray-200 pt-4">
         <div class="text-sm text-gray-700">
             Showing <span class="font-medium"><?= $allStart ?></span> to <span class="font-medium"><?= $allEnd ?></span> of <span class="font-medium"><?= $allTotal ?></span> results
         </div>

         <?php if ($allTotalPages > 1): ?>
             <div class="flex flex-wrap gap-2">
                 <?php if ($allPage <= 1): ?>
                     <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 opacity-50 cursor-not-allowed">
                         <span class="sr-only">Previous</span>
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                         </svg>
                     </span>
                 <?php else: ?>
                     <a href="<?= getPaginationLink($allPage - 1, 'all-forums', $currentParams) ?>"
                         class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200">
                         <span class="sr-only">Previous</span>
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                         </svg>
                     </a>
                 <?php endif; ?>

                 <?php for ($i = 1; $i <= $allTotalPages; $i++): ?>
                     <a href="<?= getPaginationLink($i, 'all-forums', $currentParams) ?>"
                         class="w-10 h-10 flex items-center justify-center rounded-full border
                                            <?= $i == $allPage ? 'text-white bg-blue-900 border-blue-600' : 'text-gray-700 bg-white border-gray-300 hover:bg-gray-100' ?>">
                         <?= $i ?>
                     </a>
                 <?php endfor; ?>

                 <?php if ($allPage >= $allTotalPages): ?>
                     <span class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 opacity-50 cursor-not-allowed">
                         <span class="sr-only">Next</span>
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                             <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                         </svg>
                     </span>
                 <?php else: ?>
                     <a href="<?= getPaginationLink($allPage + 1, 'all-forums', $currentParams) ?>"
                         class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200">
                         <span class="sr-only">Next</span>
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                             <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                         </svg>
                     </a>
                 <?php endif; ?>
             </div>
         <?php endif; ?>
     </div>
 </div>