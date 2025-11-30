<div id="previewModal" class="fixed inset-0 z-[999999] bg-black/50 backdrop-blur-sm hidden">

    <div id="modalBackdrop" class="absolute inset-0"></div>

    <div class="w-full h-full flex justify-center items-center p-4 relative">
        <div id="modalContentBox" class="relative bg-white rounded-xl shadow-xl max-w-3xl w-full p-4">

            <div class="flex justify-between items-center mb-4">
                <h1 class="font-semibold text-xl">Media and Files</h1>

                <button id="clsModalPreview" class="hover:bg-gray-100 rounded-lg p-1 transition-colors cursor-pointer">
                    <img src="<?= BASEURL ?>/src/asset/icons/close-circle-grey.svg" class="w-7 h-7">
                </button>
            </div>

            <div id="mediaContainer" class="max-h-[70vh] overflow-y-auto pr-2 space-y-3">
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modal = document.getElementById("previewModal");
        const backdrop = document.getElementById("modalBackdrop");
        const openBtn = document.getElementById("open-media-full");
        const closeBtn = document.getElementById("clsModalPreview");
        const modalContentBox = document.getElementById("modalContentBox");

        const mediaContainer = document.getElementById("mediaContainer");
        const groupChatId = '<?php echo $groupChatId['ID'] ?? ''; ?>';
        const BASEURL = '<?php echo BASEURL; ?>';

        async function getMedia() {
            if (!groupChatId) {
                mediaContainer.innerHTML = '<p class="text-red-500">Forum ID tidak tersedia.</p>';
                return;
            }

            mediaContainer.innerHTML = '<div class="text-center py-5">Loading media...</div>';

            try {
                const url = `${BASEURL}/groups/getAllMedia/${groupChatId}`;
                const response = await fetch(url);
                const data = await response.json();

                if (response.ok && data.success) {
                    renderFullMedia(data.data);
                } else {
                    throw new Error(data.error || 'Gagal mengambil data media.');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                mediaContainer.innerHTML = `<p class="text-red-500 p-4">Error: ${error.message}</p>`;
            }
        }


        function renderFullMedia(mediaItems) {
            const mediaContainer = document.getElementById("mediaContainer");

            if (mediaItems.length === 0) {
                mediaContainer.innerHTML = '<p class="text-gray-500 p-4 text-center">Media not found.</p>';
                return;
            }

            let html = '';

            mediaItems.forEach(item => {
                const path = item.path;
                const fullPath = `<?= BASEURL ?>/${path}`;

                const fileName = item.original_name || 'Untitled File';
                const fileType = item.type?.toUpperCase() || 'FILE';
                const senderName = item.sender_name || 'Unknown Sender';
                const createdAt = item.created_at || 'N/A';


                let previewContent;
                if (fileType === 'IMAGE') {
                    previewContent = `<img src="${fullPath}" class="w-20 h-20 object-cover rounded-lg" alt="${fileName}">`;
                } else if (fileType === 'VIDEO') {
                    previewContent = `<div class="w-20 h-20 bg-black/70 flex items-center justify-center rounded-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 4.5l14 7.5L6 19V4.5z"/></svg>
                                </div>`;
                } else {
                    previewContent = `<div class="w-20 h-20 bg-gray-200 flex items-center justify-center rounded-lg text-gray-600">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>`;
                }

                html += `
                    <div class="p-3 border border-gray-300 rounded-xl flex items-center gap-4 bg-white hover:bg-gray-50 transition group">
                        <div class="flex-shrink-0 relative">
                            ${previewContent}
                            <span class="absolute bottom-0 right-0 text-xs font-semibold text-white bg-blue-600 px-1 rounded-tl-lg">${fileType}</span>
                        </div>
                        
                        <div class="flex flex-col flex-1 min-w-0">
                            <p class="font-semibold text-base text-gray-800 group-hover:text-blue-600 truncate">
                                ${fileName}
                            </p>
                            
                            <p class="text-sm text-gray-600 mt-1">
                                Send by: <span class="font-medium text-gray-800">${senderName}</span>
                            </p>
                            <p class="text-xs text-gray-500">
                                at: ${createdAt}
                            </p>
                        </div>
                        
                        <a href="${fullPath}" target="_blank" download="${fileName}" 
                        class="flex-shrink-0 text-blue-600 hover:text-blue-700 p-2 rounded-full hover:bg-blue-50 transition duration-150"
                        title="Download File">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </a>
                    </div>
                `;
            });

            mediaContainer.innerHTML = html;
        }

        function openModal(e) {
            if (e) e.stopPropagation();
            modal?.classList.remove("hidden");
            document.body.style.overflow = 'hidden';

            getMedia()
        }

        function closeModal(e) {
            if (e) e.stopPropagation();
            modal?.classList.add("hidden");
            document.body.style.overflow = '';
        }

        openBtn?.addEventListener("click", openModal);

        document.querySelectorAll(".media-item").forEach(item => {
            item.addEventListener("click", openModal);
        });

        backdrop?.addEventListener("click", closeModal);

        modal?.addEventListener("click", (e) => {
            if (e.target === modal || e.target === backdrop) {
                closeModal(e);
            }
        });

        closeBtn.addEventListener("click", () => {
            closeModal()
        });


        modalContentBox?.addEventListener("click", (e) => {
            e.stopPropagation();
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && !modal?.classList.contains("hidden")) {
                closeModal();
            }
        });
    });
</script>