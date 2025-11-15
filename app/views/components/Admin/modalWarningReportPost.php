<div id="modalWarning" class="fixed hidden inset-0 z-50 items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-[90%] max-w-md p-8 relative">
        <div class="bg-green-500 text-white rounded-lg text-center my-3 py-2 hidden" id="warningDivSucc"></div>

        <div class="flex justify-center mb-6">
            <div class="bg-yellow-100 rounded-full p-4">
                <svg class="w-12 h-12 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-xl font-bold text-center text-gray-800 mb-2">Beri Peringatan?</h2>

        <p class="text-gray-500 text-center mb-6">Postingan ini akan diberi peringatan dan pemiliknya akan dinotifikasi.</p>

        <form method="post" id="formWarning">
            <input type="hidden" id="warningId" name="target_id">
            <input type="hidden" id="owner-Id" name="owner_id">

            <div class="flex gap-3">
                <button type="button" id="cancelWarning" class="flex-1 px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" id="confirmWarning" class="flex-1 px-6 py-3 rounded-lg bg-yellow-500 text-white font-medium hover:bg-yellow-600 transition">
                    Warning
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const modalWarning = document.getElementById("modalWarning");
        const warningId = document.getElementById("warningId");
        const ownerId = document.getElementById("owner-Id");
        const warningDivSucc = document.getElementById("warningDivSucc");
        const warningName = document.getElementById("warningName");
        const cancelWarning = document.getElementById("cancelWarning");
        const warningForm = document.getElementById("formWarning");
        const confirmWarning = document.getElementById("confirmWarning");

        document.querySelectorAll(".btn-warning").forEach(btn => {
            btn.addEventListener("click", () => {
                warningId.value = btn.dataset.id;
                ownerId.value = btn.dataset.ownerId || "";
                // warningName.textContent = btn.dataset.name || "";

                modalWarning.classList.remove("hidden");
                modalWarning.classList.add("flex");
                document.body.style.overflow = "hidden";
            });
        });

        const closeWarningModal = () => {
            modalWarning.classList.add("hidden");
            modalWarning.classList.remove("flex");
            document.body.style.overflow = "";
        };

        cancelWarning.addEventListener("click", closeWarningModal);

        modalWarning.addEventListener("click", e => {
            if (e.target === modalWarning) {
                closeWarningModal();
            }
        });

        confirmWarning.addEventListener("click", async (e) => {
            e.preventDefault();

            const formData = new FormData(warningForm);

            console.log("=== FormData Warning ===");
            for (const [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }

            try {
                const response = await fetch("<?= BASEURL ?>/report/warning", {
                    method: "POST",
                    body: formData
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const result = await response.json();

                if (result.success) {
                    warningDivSucc.classList.remove("hidden");
                    warningDivSucc.textContent = "Peringatan berhasil dikirim!";

                    setTimeout(() => {
                        closeWarningModal();
                        location.reload();
                    }, 1000);
                } else {
                    alert("Gagal mengirim peringatan: " + (result.message || "Terjadi kesalahan."));
                }
            } catch (error) {
                console.error("Error sending warning:", error);
                alert("Terjadi kesalahan koneksi saat mengirim peringatan.");
            }
        });

        document.addEventListener("keydown", e => {
            if (e.key === "Escape" && !modalWarning.classList.contains("hidden")) {
                closeWarningModal();
            }
        });

    });
</script>