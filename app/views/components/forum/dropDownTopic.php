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
     <div class="bg-green-100 border border-green-700 text-green-700 p-3 px-5 fixed right-5 top-5 rounded-md hidden z-[99999]" id="divSuccsessPin"></div>
     <div class="bg-red-100 border border-red-700 text-red-700 p-3 px-5 fixed right-5 top-5 rounded-md hidden z-[99999]" id="divErrorPin"></div>

     <div class="relative">
         <button onclick="toggleDropdown('dropdown-<?= $topic['ID'] ?>')"
             class="text-gray-400 hover:bg-gray-100 rounded-full p-2 transition focus:outline-none cursor-pointer">
             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                 <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
             </svg>
         </button>


         <div id="dropdown-<?= $topic['ID'] ?>" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200 py-1">
             <?php if ($canPin): ?>
                 <button type="button"
                     data-id="<?= $topic['ID'] ?>"
                     class="btn-pin-action flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 w-full text-left cursor-pointer">

                     <?php if ($topic['IS_PINNED'] == 1): ?>
                         <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                             <path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                         </svg>

                         <span class="font-semibold text-blue-600">Unpin Topic</span>
                     <?php else: ?>
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                         </svg>
                         <span>Pin Topic</span>
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
                     class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-left cursor-pointer">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                     </svg>
                     Edit Topic
                 </button>
             <?php endif; ?>

             <?php if ($canDelete): ?>
                 <button type="button"
                     onclick="openDeleteTopicModal('<?= $topic['ID'] ?>', '<?= $forumById['ID'] ?>')"
                     class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 text-left cursor-pointer">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                     </svg>
                     Delete
                 </button>
             <?php endif; ?>
         </div>
     </div>
 <?php endif; ?>

 <div id="deleteTopicModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
     <div class="fixed inset-0 bg-black/50 transition-opacity backdrop-blur-sm"></div>

     <div class="bg-green-100 border border-green-700 text-green-700 p-3 px-5 fixed right-5 top-5 rounded-md hidden" id="divSuccsess"></div>

     <div class="flex min-h-full justify-center p-4 text-center sm:p-0 items-start mt-20">

         <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-full sm:my-8 sm:w-full sm:max-w-2xl">

             <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                 <div class="sm:flex sm:items-start">
                     <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                         <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                         </svg>
                     </div>
                     <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                         <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Delete topic?</h3>
                         <div class="mt-2">
                             <p class="text-sm text-gray-500">Are you sure to delete this topic?</p>
                         </div>
                     </div>
                 </div>
             </div>

             <form action="<?= BASEURL ?>/topic/delete" method="POST" class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6" id="formDeleteTopic">
                 <input type="hidden" name="topic_id" id="input_delete_topic_id">
                 <input type="hidden" name="forum_id" id="input_delete_forum_id">

                 <button type="submit" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition cursor-pointer">
                     Delete
                 </button>
                 <button type="button" onclick="closeDeleteTopicModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition cursor-pointer">
                     Cancel
                 </button>
             </form>
         </div>
     </div>
 </div>

 <div id="editTopicModal" class="hidden flex fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50 p-5 md:p-0 backdrop-blur-sm">
     <div class="bg-green-100 border border-green-700 text-green-700 p-3 px-5 fixed right-5 top-5 rounded-md hidden" id="divSuccsessEdit"></div>
     <div class="bg-red-100 border border-red-700 text-red-700 p-3 px-5 fixed right-5 top-5 rounded-md hidden" id="divErrorEdit"></div>
     <div class="relative bg-white shadow-lg w-full max-w-xl drop-shadow rounded-xl">

         <form id="editTopicForm" action="<?= BASEURL ?>/forum/update-topic" method="POST" enctype="multipart/form-data">
             <input type="hidden" name="topic_id" id="input_edit_topic_id">

             <div id="deleted_media_container"></div>

             <div class=" px-4 pb-4 pt-5 sm:p-6 rounded-xl">
                 <div class="flex items-center justify-between mb-4">
                     <div class="flex items-center gap-3">
                         <div class="mx-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0">
                             <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                             </svg>
                         </div>
                         <h3 class="text-lg font-semibold leading-6 text-gray-900">Edit Post</h3>
                     </div>
                     <span id="edit_file_counter" class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-full">0/5 Media</span>
                 </div>

                 <div class="mt-2 relative">
                     <label for="input_edit_topic_content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>

                     <textarea
                         name="content"
                         id="input_edit_topic_content"
                         rows="4"
                         class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 bg-gray-50 border resize-none"
                         placeholder="What are you thinking?"></textarea>

                     <span
                         id="aboutCounter"
                         class="absolute right-3 bottom-3 text-xs text-gray-400 pointer-events-none">
                         0/1500
                     </span>
                 </div>


                 <div class="mt-4">
                     <label class="block text-sm font-medium text-gray-700 mb-2">Media</label>

                     <div id="edit_media_preview_container" class="flex gap-2 overflow-x-auto pb-2 snap-x min-h-[100px] border border-dashed border-gray-300 rounded-lg p-2 bg-gray-50 items-center">
                         <p id="edit_empty_msg" class="text-xs text-gray-400 w-full text-center">No media</p>
                     </div>

                     <div class="mt-2 flex items-center justify-end">
                         <input type="file" id="edit_image_input" name="new_media[]" multiple accept="image/*, .pdf, .doc, .docx, .xls, .xlsx, .zip" class="hidden">
                         <label for="edit_image_input" id="btn_add_more_edit" class="cursor-pointer inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                             </svg>
                             Add Media
                         </label>
                     </div>
                     <p id="edit_limit_warning" class="hidden text-xs text-red-500 mt-1 text-right">Maximum files reached.</p>
                 </div>
             </div>

             <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 rounded-b-xl">
                 <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto transition cursor-pointer">
                     Save Change
                 </button>
                 <button type="button" onclick="closeEditTopicModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition cursor-pointer">
                     Cancel
                 </button>
             </div>
         </form>
     </div>
 </div>


 <script>
     var editTextTopic = document.getElementById('input_edit_topic_content');
     var aboutCounter = document.getElementById('aboutCounter');
     const textarea = document.getElementById("input_edit_topic_content");
     var MAX_LENGTH_EDIT = 1500;


     textarea.addEventListener("input", function() {
         this.value = this.value.replace(/\n{3,}/g, "\n\n\n");
     });

     function updateCounter() {
         const textarea = document.getElementById('input_edit_topic_content');
         const counter = document.getElementById('aboutCounter');

         if (!textarea || !counter) return;

         let len = textarea.value.length;

         if (len > MAX_LENGTH_EDIT) {
             textarea.value = textarea.value.slice(0, MAX_LENGTH_EDIT);
             len = MAX_LENGTH_EDIT;
         }

         counter.textContent = `${len}/${MAX_LENGTH_EDIT}`;
     }

     if (editTextTopic) {
         editTextTopic.addEventListener('input', updateCounter);
     }

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

     window.onclick = function(event) {
         if (!event.target.closest('.relative')) {
             document.querySelectorAll('[id^="dropdown-"]').forEach(d => d.classList.add('hidden'));
         }

         const delModal = document.getElementById('deleteTopicModal');
         if (delModal && event.target === delModal.querySelector('.fixed.inset-0.bg-gray-500')) {
             closeDeleteTopicModal();
         }
     }

     {
         const formDeleteTopic = document.getElementById('formDeleteTopic');
         const divSuccess = document.getElementById('divSuccsess');

         formDeleteTopic.addEventListener('submit', async function(event) {
             event.preventDefault();

             const deleteButton = this.querySelector('button[type="submit"]');
             deleteButton.disabled = true;
             deleteButton.innerHTML = 'Deleting...';

             const formData = new FormData(this);

             try {
                 const response = await fetch(this.action, {
                     method: 'POST',
                     body: formData
                 });

                 const result = await response.json();

                 if (result.success) {
                     divSuccess.classList.remove('hidden');
                     divSuccess.innerHTML = 'Topic deleted successfully!';
                     setTimeout(() => {
                         divSuccess.classList.add('hidden');
                         location.reload();
                     }, 1500);
                 } else {
                     alert('Failed deleting topic: ' + result.message);
                     deleteButton.disabled = false;
                     deleteButton.innerHTML = 'Ya, Hapus';
                 }
             } catch (error) {
                 alert('Something wrong when deleting topic.');
                 deleteButton.disabled = false;
                 deleteButton.innerHTML = 'Delete';
             }
         });
     }

     document.addEventListener("DOMContentLoaded", function() {
         const pinButtons = document.querySelectorAll('.btn-pin-action');

         const divSuccsessPin = document.getElementById("divSuccsessPin")
         const divErrorPin = document.getElementById('divErrorPin')

         pinButtons.forEach(button => {
             button.addEventListener('click', async function(e) {
                 e.preventDefault();
                 e.stopPropagation();

                 const currentBtn = this;
                 const topicId = currentBtn.getAttribute('data-id');
                 const originalContent = currentBtn.innerHTML;

                 currentBtn.innerHTML = '<span class="text-xs text-gray-500">Processing...</span>';
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
                         divSuccsessPin.classList.remove('hidden')
                         divSuccsessPin.innerHTML = "The topic pin status successfully changed.!"

                         setTimeout(() => {
                             window.location.reload();
                         }, 1500)

                     } else {
                         divErrorPin.classList.remove('hidden')
                         divErrorPin.innerHTML = result.message

                         setTimeout(() => {
                             divErrorPin.classList.add('hidden')
                             currentBtn.innerHTML = originalContent;
                             currentBtn.disabled = false;
                         }, 1500)

                     }

                 } catch (error) {
                     console.error(error);
                     divErrorPin.classList.add('hidden')
                     divErrorPin.classList.remove('hidden')
                     divErrorPin.innerHTML = "Connection error occurred."

                     setTimeout(() => {
                         currentBtn.innerHTML = originalContent;
                         currentBtn.disabled = false;
                     }, 1500)
                 }
             });
         });
     });

     if (typeof existingMediaData === 'undefined') {
         var existingMediaData = [];
     }

     if (typeof filesToDelete === 'undefined') {
         var filesToDelete = [];
     }

     if (typeof MAX_FILES === 'undefined') var MAX_FILES = 5;


     function openEditTopicModal(topicId, content, mediaArray) {
         existingMediaData = mediaArray || [];
         filesToDelete = [];

         document.getElementById('input_edit_topic_id').value = topicId;

         const contentInput = document.getElementById('input_edit_topic_content');
         contentInput.value = content;

         updateCounter();

         document.getElementById('deleted_media_container').innerHTML = '';
         document.getElementById('edit_image_input').value = '';

         renderExistingMedia();
         updateFileCounter();

         const modal = document.getElementById('editTopicModal');
         if (modal) {
             modal.classList.remove('hidden');
             modal.classList.add('flex', 'justify-center', 'items-center');
         }
     }


     function closeEditTopicModal() {
         const modal = document.getElementById('editTopicModal');
         if (modal) {
             modal.classList.add('hidden');
             modal.classList.remove('flex', 'justify-center', 'items-center');
         }
         existingMediaData = [];
         filesToDelete = [];
     }

     function renderExistingMedia() {
         const container = document.getElementById('edit_media_preview_container');
         const emptyMsg = document.getElementById('edit_empty_msg');

         container.querySelectorAll(':scope > :not(#edit_empty_msg)').forEach(el => el.remove());


         if (existingMediaData.length === 0) {
             emptyMsg.classList.remove('hidden');
             return;
         }

         emptyMsg.classList.add('hidden');

         existingMediaData.forEach(media => {
             const mediaEl = createMediaPreviewElement(media, true);
             container.appendChild(mediaEl);
         });
     }


     function createMediaPreviewElement(media, isExisting = false) {
         const div = document.createElement('div');
         div.className = 'relative flex-shrink-0 snap-start group';

         const mediaId = isExisting ? media.ID : `new_${Date.now()}`;
         const mediaPath = isExisting ?
             `<?= BASEURL ?>/storage/forums/topics/${media.MEDIA_PATH}` :
             media.preview;

         let mediaContent = '';

         if (media.MEDIA_TYPE === 'IMAGE' || media.type?.startsWith('image/')) {
             mediaContent = `
                <img src="${mediaPath}" 
                    alt="${media.ORIGINAL_FILENAME || media.name}" 
                    class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
            `;
         } else {
             const fileName = media.ORIGINAL_FILENAME || media.name;
             const fileExt = fileName.split('.').pop().toUpperCase();

             mediaContent = `
                <div class="w-32 h-32 flex flex-col items-center justify-center bg-gray-100 rounded-lg border-2 border-gray-200">
                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-xs font-bold text-gray-600">${fileExt}</span>
                    <span class="text-xs text-gray-500 px-2 truncate w-full text-center">${fileName}</span>
                </div>
            `;
         }

         div.innerHTML = `
            ${mediaContent}
            <button type="button" 
                    onclick="removeMedia('${mediaId}', ${isExisting})"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:bg-red-600 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

         div.dataset.mediaId = mediaId;

         return div;
     }


     function removeMedia(mediaId, isExisting) {
         if (isExisting) {
             filesToDelete.push(mediaId);

             existingMediaData = existingMediaData.filter(m => m.ID !== mediaId);

             const input = document.createElement('input');
             input.type = 'hidden';
             input.name = 'deleted_media[]';
             input.value = mediaId;
             document.getElementById('deleted_media_container').appendChild(input);
         }

         const element = document.querySelector(`[data-media-id="${mediaId}"]`);
         if (element) {
             element.remove();
         }

         updateFileCounter();
         checkEmptyState();
     }


     const editImgInput = document.getElementById('edit_image_input');

     if (editImgInput) {
         // Gunakan .onchange agar fungsi lama tertimpa, jadi hanya ada 1 listener aktif
         editImgInput.onchange = function(e) {
             const files = Array.from(e.target.files);
             const container = document.getElementById('edit_media_preview_container');

             const currentTotal = existingMediaData.length +
                 container.querySelectorAll('[data-media-id^="new_"]').length;
             const newTotal = currentTotal + files.length;

             if (newTotal > MAX_FILES) {
                 alert(`Maximum ${MAX_FILES} file. You already have ${currentTotal} files.`);
                 e.target.value = '';
                 return;
             }

             document.getElementById('edit_empty_msg').classList.add('hidden');

             files.forEach(file => {
                 const allowedTypes = [
                     'image/jpeg', 'image/png', 'image/jpg', 'image/gif',
                     'application/pdf', 'application/msword',
                     'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                     'application/vnd.ms-excel',
                     'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                     'application/zip'
                 ];

                 if (!allowedTypes.includes(file.type)) {
                     alert(`File type not allowed: ${file.name}`);
                     return;
                 }

                 if (file.size > 20 * 1024 * 1024) {
                     alert(`File size too large: ${file.name} (max 20MB)`);
                     return;
                 }

                 const reader = new FileReader();
                 reader.onload = function(event) {
                     const mediaData = {
                         name: file.name,
                         type: file.type,
                         preview: file.type.startsWith('image/') ? event.target.result : null
                     };

                     const previewEl = createMediaPreviewElement(mediaData, false);
                     container.appendChild(previewEl);

                     updateFileCounter();
                 };

                 if (file.type.startsWith('image/')) {
                     reader.readAsDataURL(file);
                 } else {
                     const mediaData = {
                         name: file.name,
                         type: file.type,
                         preview: null
                     };

                     const previewEl = createMediaPreviewElement(mediaData, false);
                     container.appendChild(previewEl);

                     updateFileCounter();
                 }
             });
         };
     }


     function updateFileCounter() {
         const container = document.getElementById('edit_media_preview_container');
         const total = existingMediaData.length +
             container.querySelectorAll('[data-media-id^="new_"]').length;

         document.getElementById('edit_file_counter').textContent = `${total}/${MAX_FILES} Media`;

         const warning = document.getElementById('edit_limit_warning');
         const addButton = document.getElementById('btn_add_more_edit');

         if (total >= MAX_FILES) {
             warning.classList.remove('hidden');
             addButton.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
         } else {
             warning.classList.add('hidden');
             addButton.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
         }
     }


     function checkEmptyState() {
         const container = document.getElementById('edit_media_preview_container');
         const emptyMsg = document.getElementById('edit_empty_msg');
         const hasMedia = container.querySelectorAll('[data-media-id]').length > 0;

         if (!hasMedia) {
             emptyMsg.classList.remove('hidden');
         }
     }

     document.getElementById('editTopicForm')?.addEventListener('submit', async function(e) {
         e.preventDefault();

         const submitBtn = this.querySelector('button[type="submit"]');
         const divSuccsesEdit = document.getElementById('divSuccsessEdit')
         const divErrorEdit = document.getElementById('divErrorEdit')

         const originalText = submitBtn.textContent;
         submitBtn.disabled = true;
         submitBtn.textContent = 'Saving...';

         const formData = new FormData(this);

         if (filesToDelete.length > 0) {
             formData.append('deleted_media', JSON.stringify(filesToDelete));
         }

         try {
             const response = await fetch('<?= BASEURL ?>/topic/update-topic', {
                 method: 'POST',
                 body: formData
             });

             const result = await response.json();

             if (result.success) {
                 divSuccsesEdit.classList.remove('hidden')
                 divSuccsesEdit.innerHTML = "Update topic successfully."

                 setTimeout(() => {
                     location.reload();
                 }, 1500)

             } else {
                 divErrorEdit.classList.remove('hidden')
                 divErrorEdit.innerHTML = result.message || 'Failed updating topic.'
                 setTimeout(() => {
                     divErrorEdit.classList.add('hidden')
                 }, 3000)
                 submitBtn.disabled = false;
                 submitBtn.textContent = originalText;
             }
         } catch (error) {
             console.error('Error:', error);
             alert('An error occurred while updating the topic.');
             submitBtn.disabled = false;
             submitBtn.textContent = originalText;
         }
     });

     document.addEventListener('DOMContentLoaded', function() {
         updateFileCounter();
     });
 </script>