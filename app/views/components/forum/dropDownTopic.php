 <?php
    $currentUserId = $_SESSION['user_id'] ?? null;
    $currentUserRole = $_SESSION['role'] ?? '';
    $forumOwnerId = $forumById['OWNER_ID'];
    $topicOwnerId = $topic['USER_ID'];

    $canEdit = ($currentUserId === $topicOwnerId);

    $canDelete = ($currentUserId === $topicOwnerId) ||
        ($currentUserId === $forumOwnerId) ||
        ($currentUserRole === 'ADMIN');

    $canPin = ($currentUserId === $forumOwnerId) ||
        ($currentUserRole === 'ADMIN') ||
        ($currentUserRole === 'DOSEN');
    ?>

 <?php if ($canEdit || $canDelete || $canPin): ?>
     <div class="relative">
         <button onclick="toggleDropdown('dropdown-<?= $topic['ID'] ?>')"
             class="text-gray-400 hover:bg-gray-100 rounded-full p-2 transition focus:outline-none">
             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                 <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
             </svg>
         </button>

         <div id="dropdown-<?= $topic['ID'] ?>" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200 py-1">

             <?php if ($canPin): ?>
                 <button type="button"
                     data-id="<?= $topic['ID'] ?>"
                     class="btn-pin-action flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left">

                     <?php if ($topic['IS_PINNED'] == 1): ?>
                         <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                             <path d="M18.75 12.75h1.5a.75.75 0 000-1.5h-1.5a.75.75 0 000 1.5zM12 6a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 0112 6zM12 18a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 0112 18zM3.75 6.75h1.5a.75.75 0 100-1.5h-1.5a.75.75 0 000 1.5zM5.25 18.75h-1.5a.75.75 0 010-1.5h1.5a.75.75 0 010 1.5zM3 12a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 013 12zM9 3.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zM12.75 12a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0zM9 20.25a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                             <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                         </svg>
                         <span class="font-semibold text-blue-600">Unpin Topik</span>
                     <?php else: ?>
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                         </svg>
                         <span>Pin Topik</span>
                     <?php endif; ?>

                 </button>
             <?php endif; ?>

             <?php if ($canEdit): ?>
                 <?php
                    $mediaJson = htmlspecialchars(json_encode($topic['MEDIA']), ENT_QUOTES, 'UTF-8');
                    $contentEscaped = htmlspecialchars($topic['CONTENT'], ENT_QUOTES, 'UTF-8');
                    ?>
                 <button type="button"
                     onclick="openEditTopicModal('<?= $topic['ID'] ?>', `<?= $contentEscaped ?>`, <?= $mediaJson ?>)"
                     class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-left">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                     </svg>
                     Edit Postingan
                 </button>
             <?php endif; ?>

             <?php if ($canDelete): ?>
                 <button type="button"
                     onclick="openDeleteTopicModal('<?= $topic['ID'] ?>', '<?= $forumById['ID'] ?>')"
                     class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                     </svg>
                     Hapus
                 </button>
             <?php endif; ?>
         </div>
     </div>
 <?php endif; ?>

 <div id="deleteTopicModal" class="hidden fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
     <div class="fixed inset-0 bg-black/50 transition-opacity backdrop-blur-sm"></div>

     <div class="bg-green-100 border border-green-700 text-green-700 p-3 px-5 fixed right-5 top-5 rounded-md hidden" id="divSuccsess"></div>

     <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
         <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
             <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                 <div class="sm:flex sm:items-start">
                     <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                         <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                         </svg>
                     </div>
                     <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                         <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Hapus Topik?</h3>
                         <div class="mt-2">
                             <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus postingan ini?</p>
                         </div>
                     </div>
                 </div>
             </div>

             <form action="<?= BASEURL ?>/topic/delete" method="POST" class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6" id="formDeleteTopic">
                 <input type="hidden" name="topic_id" id="input_delete_topic_id">
                 <input type="hidden" name="forum_id" id="input_delete_forum_id">

                 <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition">
                     Ya, Hapus
                 </button>
                 <button type="button" onclick="closeDeleteTopicModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">
                     Batal
                 </button>
             </form>
         </div>
     </div>
 </div>

 <div id="editTopicModal" class="hidden fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
     <div class="fixed inset-0 bg-black/50 transition-opacity backdrop-blur-sm"></div>

     <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
         <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

             <form id="editTopicForm" action="<?= BASEURL ?>/topic/update" method="POST" enctype="multipart/form-data">
                 <input type="hidden" name="topic_id" id="input_edit_topic_id">

                 <div id="deleted_media_container"></div>

                 <div class="bg-white px-4 pb-4 pt-5 sm:p-6">
                     <div class="flex items-center justify-between mb-4">
                         <div class="flex items-center gap-3">
                             <div class="mx-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0">
                                 <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                 </svg>
                             </div>
                             <h3 class="text-lg font-semibold leading-6 text-gray-900">Edit Postingan</h3>
                         </div>
                         <span id="edit_file_counter" class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-full">0/5 Media</span>
                     </div>

                     <div class="mt-2">
                         <label for="input_edit_topic_content" class="block text-sm font-medium text-gray-700 mb-1">Konten</label>
                         <textarea name="content" id="input_edit_topic_content" rows="4"
                             class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 bg-gray-50 border resize-none"
                             placeholder="Apa yang Anda pikirkan?"></textarea>
                     </div>

                     <div class="mt-4">
                         <label class="block text-sm font-medium text-gray-700 mb-2">Media</label>

                         <div id="edit_media_preview_container" class="flex gap-2 overflow-x-auto pb-2 snap-x min-h-[100px] border border-dashed border-gray-300 rounded-lg p-2 bg-gray-50 items-center">
                             <p id="edit_empty_msg" class="text-xs text-gray-400 w-full text-center">Tidak ada media</p>
                         </div>

                         <div class="mt-2 flex items-center justify-end">
                             <input type="file" id="edit_image_input" name="new_media[]" multiple accept="image/*, .pdf, .doc, .docx, .xls, .xlsx, .zip" class="hidden">
                             <label for="edit_image_input" id="btn_add_more_edit" class="cursor-pointer inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                 </svg>
                                 Tambah Media
                             </label>
                         </div>
                         <p id="edit_limit_warning" class="hidden text-xs text-red-500 mt-1 text-right">Maksimal 5 file tercapai.</p>
                     </div>
                 </div>

                 <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                     <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition">
                         Simpan Perubahan
                     </button>
                     <button type="button" onclick="closeEditTopicModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">
                         Batal
                     </button>
                 </div>
             </form>
         </div>
     </div>
 </div>

 <script>
     function toggleDropdown(dropdownId) {
         const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
         allDropdowns.forEach(dd => {
             if (dd.id !== dropdownId) dd.classList.add('hidden');
         });
         const dropdown = document.getElementById(dropdownId);
         if (dropdown) dropdown.classList.toggle('hidden');
     }

     function openDeleteTopicModal(topicId, forumId) {
         document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));

         document.getElementById('input_delete_topic_id').value = topicId;
         document.getElementById('input_delete_forum_id').value = forumId;

         document.getElementById('deleteTopicModal').classList.remove('hidden');
     }

     function closeDeleteTopicModal() {
         document.getElementById('deleteTopicModal').classList.add('hidden');
     }

     function openEditTopicModal(topicId, content) {
         document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));

         document.getElementById('input_edit_topic_id').value = topicId;
         document.getElementById('input_edit_topic_content').value = content;

         document.getElementById('editTopicModal').classList.remove('hidden');
     }

     function closeEditTopicModal() {
         document.getElementById('editTopicModal').classList.add('hidden');
     }

     window.onclick = function(event) {
         if (!event.target.closest('.relative')) {
             document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
         }

         const delModal = document.getElementById('deleteTopicModal');
         if (delModal && event.target === delModal.querySelector('.fixed.inset-0.bg-gray-500')) {
             closeDeleteTopicModal();
         }

         const editModal = document.getElementById('editTopicModal');
         if (editModal && event.target === editModal.querySelector('.fixed.inset-0.bg-gray-500')) {
             closeEditTopicModal();
         }
     }

     const formDeleteTopic = document.getElementById('formDeleteTopic');
     const divSuccess = document.getElementById('divSuccsess');

     formDeleteTopic.addEventListener('submit', async function(event) {
         event.preventDefault();

         const deleteButton = this.querySelector('button[type="submit"]');
         deleteButton.disabled = true;
         deleteButton.innerHTML = 'Menghapus...';

         const formData = new FormData(this);

         try {
             const response = await fetch(this.action, {
                 method: 'POST',
                 body: formData
             });

             const result = await response.json();

             if (result.success) {
                 divSuccess.classList.remove('hidden');
                 divSuccess.innerHTML = 'Topik berhasil dihapus!';
                 setTimeout(() => {
                     divSuccess.classList.add('hidden');
                     location.reload();
                 }, 1500);
             } else {
                 alert('Gagal menghapus topik: ' + result.message);
                 deleteButton.disabled = false;
                 deleteButton.innerHTML = 'Ya, Hapus';
             }
         } catch (error) {
             alert('Terjadi kesalahan saat menghapus topik.');
             deleteButton.disabled = false;
             deleteButton.innerHTML = 'Ya, Hapus';
         }
     });

     const pinButtons = document.querySelectorAll('.btn-pin-action');

     pinButtons.forEach(button => {
         button.addEventListener('click', async function(e) {
             e.preventDefault(); 

             const currentBtn = this;
             const topicId = currentBtn.getAttribute('data-id');
             const originalContent = currentBtn.innerHTML; 

             currentBtn.innerHTML = '<span class="text-xs">Processing...</span>';
             currentBtn.disabled = true;

             const formData = new FormData();
             formData.append('topic_id', topicId);

             try {
                 const response = await fetch(`<?= BASEURL ?>/topic/pin`, {
                     method: 'POST',
                     body: formData
                 });

                 const result = await response.json();

                 if (result.success) {
                     window.location.reload(); // Reload agar posisi topik berpindah (naik/turun) & icon berubah
                 } else {
                     alert(result.message);

                     currentBtn.innerHTML = originalContent;
                     currentBtn.disabled = false;
                 }

             } catch (error) {
                 console.error(error);
                 alert("Terjadi kesalahan koneksi.");
                 currentBtn.innerHTML = originalContent;
                 currentBtn.disabled = false;
             }
         });
     });
 </script>