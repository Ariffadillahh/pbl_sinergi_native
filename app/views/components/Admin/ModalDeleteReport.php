<div id="modalDelete" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-[90%] max-w-md p-8 relative">

        <div class="bg-green-500 text-white rounded-lg text-center my-3 py-2 hidden" id="divSucc"></div>

        <div class="flex justify-center mb-6">
            <div class="bg-red-100 rounded-full p-4">
                <svg class="w-12 h-12 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>

        <h2 class="text-xl font-bold text-center text-gray-800 mb-2">Delete this Forum?</h2>

        <p class="text-gray-500 text-center mb-6">This file will be permanently deleted from your system and cannot be recovered.</p>

        <form method="post" id="form">
            <input type="hidden" id="deleteId" name="forum_id">
            <input type="hidden" id="reportIds" name="report_ids">
            <input type="hidden" id="ownerId" name="owner_id">

            <div class="flex gap-3">
                <button type="button" id="cancelDelete" class="flex-1 px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="button" id="confirmDelete" class="flex-1 px-6 py-3 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600 transition">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const modalDelete = document.getElementById("modalDelete");
        const deleteId = document.getElementById("deleteId");
        const ownerId = document.getElementById("ownerId");
        const reportIds = document.getElementById("reportIds");
        const deleteName = document.getElementById("deleteName");
        const title = document.getElementById("title");
        const cancelDelete = document.getElementById("cancelDelete");
        const confirmDelete = document.getElementById("confirmDelete");
        const divSucc = document.getElementById("divSucc");
        const form = document.getElementById("form");

        document.querySelectorAll(".btn-delete").forEach(btn => {
            btn.addEventListener("click", () => {
                deleteId.value = btn.dataset.id;
                reportIds.value = btn.dataset.reportId || "";
                ownerId.value = btn.dataset.ownerId || "";
                modalDelete.classList.remove("hidden");
                modalDelete.classList.add("flex");
                document.body.style.overflow = "hidden";
            });
        });

        const closeDelete = () => {
            modalDelete.classList.add("hidden");
            modalDelete.classList.remove("flex");
            document.body.style.overflow = "";
        };

        cancelDelete.addEventListener("click", (e) => {
            e.preventDefault();
            closeDelete();
        });

        modalDelete.addEventListener("click", (e) => {
            if (e.target === modalDelete) closeDelete();
        });

        confirmDelete.addEventListener("click", async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            try {
                const response = await fetch("<?= BASEURL ?>/report/delete", {
                    method: "POST",
                    body: formData
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const result = await response.json();

                if (result.success) {
                    divSucc.classList.remove("hidden");
                    divSucc.textContent = "Forum successfully deleted";

                    setTimeout(() => {
                        closeDelete();
                        location.reload();
                    }, 1000);
                } else {
                    console.error("Error deleting forum:", result.message);
                    alert("❌ Failed deleting forum: " + (result.message || "Something wrong"));
                }
            } catch (error) {
                console.error("Error deleting forum:", error);
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && !modalDelete.classList.contains("hidden")) {
                closeDelete();
            }
        });
    });
</script>