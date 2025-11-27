<div id="reason-modal" class="hidden fixed inset-0 bg-black/50 z-[99999] justify-center items-center">
    <div class="relative p-4 w-full max-w-xl max-h-[90vh] overflow-hidden">
        <div class="relative bg-white rounded-xl shadow-lg animate-fade-in">
            <div class=" bg-red-700 px-6 py-4 flex items-center justify-between rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Report Reason</h3>
                        <p class="text-red-100 text-xs">Content violation details</p>
                    </div>
                </div>
                <button type="button" id="reason-modal-close" class="text-white/80 hover:text-white hover:bg-white/10 rounded-lg w-9 h-9 flex justify-center items-center transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div id="reason-container" class="px-4 py-4 max-h-[70vh] overflow-y-auto space-y-4 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">
                <div class="border-b border-gray-200 pb-2">

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const reasonModal = document.getElementById("reason-modal");
        const closeBtn = document.getElementById("reason-modal-close");
        const reasonContainer = document.getElementById("reason-container");

        const openModal = () => {
            reasonModal.classList.remove("hidden");
            reasonModal.classList.add("flex");
            document.body.style.overflow = "hidden";
        };

        const closeModal = () => {
            reasonModal.classList.add("hidden");
            reasonModal.classList.remove("flex");
            document.body.style.overflow = "";
        };

        document.querySelectorAll(".reason-modal-btn").forEach(btn => {
            btn.addEventListener("click", async () => {
                const forumId = btn.dataset.id;

                reasonContainer.innerHTML = "<p class='text-center text-gray-500'>Memuat alasan...</p>";

                const res = await fetch(`<?= BASEURL ?>/report/reasons?target_id=${forumId}&type=GROUPS`);
                const data = await res.json();

                if (data.error) {
                    reasonContainer.innerHTML = `<p class='text-red-500 text-center'>${data.error}</p>`;
                    return;
                }

                if (data.length > 0) {
                    reasonContainer.innerHTML = data.map(item => {
                        const photoPath = item.PATH_PHOTO ?
                            `<?= BASEURL ?>/storage/users/photos/${item.PATH_PHOTO}` :
                            `<?= BASEURL ?>/src/asset/image/default.png`;
                        return `
                            <div class="flex gap-3 items-center">
                                <img src="${photoPath}" class="w-10 h-10 rounded-full" alt="User photo">
                                <div>
                                    <p class="font-semibold text-gray-900">${item.REPORTER_NAME}</p>
                                    <p class="text-gray-600 text-sm">${item.REASON}</p>
                                </div>
                            </div>
                        `
                    }).join("");
                } else {
                    reasonContainer.innerHTML = "<p class='text-center text-gray-500'>Tidak ada alasan ditemukan.</p>";
                }

                openModal();
            });
        });

        closeBtn.addEventListener("click", closeModal);
        reasonModal.addEventListener("click", (e) => {
            if (e.target === reasonModal) closeModal();
        });
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && !reasonModal.classList.contains("hidden")) {
                closeModal();
            }
        });
    });
</script>