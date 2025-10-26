<div id="modal-report-post" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Laporkan Post Ini
                </h3>
                <button type="button" id="btn-cancel-report-post" class="text-gray-400 rounded-lg text-sm w-8 h-8 flex justify-center items-center cursor-pointer">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <p id="report-post-message" class="text-sm mb-3 p-3 rounded-lg hidden text-center"></p>

                <div class="text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>

                <form id="form-report-post" action="<?php echo BASEURL; ?>/report" method="post">
                    <input type="hidden" name="target_id" id="input-report-post-id" value="">
                    <input type="hidden" name="target_type" value="POSTINGAN">

                    <h3 class="mb-3 font-semibold text-gray-900 text-left">Pilih Alasan</h3>

                    <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg">
                        <li><label for="reason-spam" class="flex items-center gap-3 ps-3 py-3 cursor-pointer"><input id="reason-spam" type="radio" value="Spam atau Iklan" name="reason" class="w-4 h-4 text-blue-600"> <span>Spam atau Iklan</span></label></li>
                        <li><label for="reason-harassment" class="flex items-center gap-3 ps-3 py-3 cursor-pointer"><input id="reason-harassment" type="radio" value="Ujaran Kebencian atau Pelecehan" name="reason" class="w-4 h-4 text-blue-600"> <span>Ujaran Kebencian atau Pelecehan</span></label></li>
                        <li><label for="reason-inappropriate" class="flex items-center gap-3 ps-3 py-3 cursor-pointer"><input id="reason-inappropriate" type="radio" value="Konten Tidak Pantas (SARA, Pornografi)" name="reason" class="w-4 h-4 text-blue-600"> <span>Konten Tidak Pantas (SARA, Pornografi)</span></label></li>
                        <li><label for="reason-misinformation" class="flex items-center gap-3 ps-3 py-3 cursor-pointer"><input id="reason-misinformation" type="radio" value="Informasi Palsu / Hoax" name="reason" class="w-4 h-4 text-blue-600"> <span>Informasi Palsu / Hoax</span></label></li>
                        <li><label for="reason-other" class="flex items-center gap-3 ps-3 py-3 cursor-pointer"><input id="reason-other" type="radio" value="other" name="reason" class="w-4 h-4 text-blue-600"> <span>Lainnya</span></label></li>
                    </ul>

                    <div id="other-reason-container" class="mt-4 hidden">
                        <label for="other-reason-text" class="block mb-2 text-sm font-medium text-gray-900">Jelaskan alasan Anda:</label>
                        <input id="other-reason-text" name="other_reason_text" class="block p-2.5 w-full text-sm bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis alasan spesifik Anda di sini..." />
                    </div>
                </form>

                <div class="flex justify-center gap-3 mt-6">
                    <button id="btn-confirm-report-post" type="button"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5">
                        Kirim Laporan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const modalReportPost = document.getElementById("modal-report-post");
    const btnCloseReportPost = document.getElementById("btn-cancel-report-post");
    const btnConfirmReportPost = document.getElementById("btn-confirm-report-post");
    const formReportPost = document.getElementById("form-report-post");
    const reasonRadios = document.querySelectorAll('input[name="reason"]');
    const otherReasonContainer = document.getElementById("other-reason-container");
    const otherReasonText = document.getElementById("other-reason-text");
    const messageReportPost = document.getElementById("report-post-message");
    const inputPostId = document.getElementById("input-report-post-id");
    const successClasses = ['bg-green-100', 'border', 'border-green-400', 'text-green-800'];
    const errorClasses = ['bg-red-100', 'border', 'border-red-400', 'text-red-700'];

    const showMessage = (message, type) => {
        messageReportPost.classList.remove(...successClasses, ...errorClasses);
        messageReportPost.classList.add(...(type === 'success' ? successClasses : errorClasses));
        messageReportPost.textContent = message;
        messageReportPost.classList.remove('hidden');
    };

    const openModalReportPost = (postId) => {
        inputPostId.value = postId;
        modalReportPost.classList.remove("hidden");
        modalReportPost.classList.add("flex");
    };

    const closeModalReportPost = () => {
        modalReportPost.classList.add("hidden");
        modalReportPost.classList.remove("flex");
        formReportPost.reset();
        messageReportPost.classList.add("hidden");
        otherReasonContainer.classList.add("hidden");
    };

    document.querySelectorAll('.report-btn').forEach(button => {
        button.addEventListener('click', () => {
            const postId = button.dataset.postId;
            openModalReportPost(postId);
        });
    });

    btnCloseReportPost.addEventListener("click", closeModalReportPost);
    modalReportPost.addEventListener("click", e => {
        if (e.target === modalReportPost) closeModalReportPost();
    });

    reasonRadios.forEach(radio => {
        radio.addEventListener("change", () => {
            otherReasonContainer.classList.toggle("hidden", radio.value !== "other");
        });
    });

    btnConfirmReportPost.addEventListener("click", async () => {
        const formData = new FormData(formReportPost);
        const actionUrl = formReportPost.getAttribute("action");
        const selectedReason = formData.get("reason");

        if (!selectedReason) {
            showMessage("Silakan pilih salah satu alasan laporan.", "error");
            return;
        }
        if (selectedReason === "other" && !otherReasonText.value.trim()) {
            showMessage("Silakan jelaskan alasan Anda pada kolom yang tersedia.", "error");
            return;
        }

        messageReportPost.classList.add("hidden");
        btnConfirmReportPost.disabled = true;
        btnConfirmReportPost.textContent = "Mengirim...";

        try {
            const response = await fetch(actionUrl, {
                method: "POST",
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                showMessage(result.message || "Laporan berhasil dikirim!", "success");
                setTimeout(closeModalReportPost, 1000);
            } else {
                showMessage(result.message || "Gagal mengirim laporan.", "error");
            }
        } catch (err) {
            showMessage("Terjadi kesalahan jaringan. Coba lagi.", "error");
        } finally {
            btnConfirmReportPost.disabled = false;
            btnConfirmReportPost.textContent = "Kirim Laporan";
        }
    });
</script>