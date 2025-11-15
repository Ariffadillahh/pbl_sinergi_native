<div id="reason-modal" class="hidden fixed inset-0 bg-black/50 z-[99999] justify-center items-center">
    <div class="relative p-4 w-full max-w-xl max-h-[90vh] overflow-hidden">
        <div class="relative bg-white rounded-xl shadow-lg animate-fade-in">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">Reason</h3>
                <button type="button" id="reason-modal-close" class="text-gray-400 hover:text-gray-600 rounded-lg text-sm w-8 h-8 flex justify-center items-center cursor-pointer transition">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>

            <div id="reason-container" class="px-4 py-4 max-h-[70vh] overflow-y-auto space-y-4 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-100">

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
                const postId = btn.dataset.id;

                reasonContainer.innerHTML = "<p class='text-center text-gray-500'>Memuat alasan...</p>";

                const res = await fetch(`<?= BASEURL ?>/report/reasons?target_id=${postId}&type=POST`);
                const data = await res.json();

                if (data.error) {
                    reasonContainer.innerHTML = `<p class='text-red-500 text-center'>${data.error}</p>`;
                    return;
                }

                if (data.length > 0) {
                    reasonContainer.innerHTML = data.map(item => `
                    <div class="border-b border-gray-200 pb-2">
                        <p class="font-semibold text-gray-900">${item.REPORTER_NAME}</p>
                        <p class="text-gray-600 text-sm">${item.REASON}</p>
                    </div>
                `).join("");
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