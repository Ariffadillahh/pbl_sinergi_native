<div id="modal-report-forum" class="hidden fixed inset-0 z-[9999] justify-center items-center w-full backdrop-blur-sm h-full bg-black/50 ">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Report this Group
                </h3>
                <button type="button" id="btn-cancel-report-forum" class="text-gray-400 rounded-lg text-sm w-8 h-8 flex justify-center items-center cursor-pointer">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <p id="report-forum-message" class="text-sm mb-3 p-3 rounded-lg hidden text-center"></p>

                <div class="text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="text-sm text-gray-600 mb-5">
                        Why are you reporting this group "<?php echo htmlspecialchars($groupChatId['NAME'], ENT_QUOTES, 'UTF-8'); ?>"?
                    </p>
                </div>

                <form id="form-report-forum" action="<?php echo BASEURL; ?>/report" method="post">
                    <input type="hidden" name="target_id" value="<?php echo $groupChatId['ID']; ?>">
                    <input type="hidden" name="target_type" value="GROUP">

                    <h3 class="mb-3 font-semibold text-gray-900 text-left">Reason</h3>

                    <ul class="w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg">
                        <li class="w-full border-b border-gray-200 rounded-t-lg">
                            <label for="reason-spam" class="flex items-center gap-3 ps-3 py-3 w-full cursor-pointer">
                                <input id="reason-spam" type="radio" value="Spam or Advertising" name="reason" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                <span class="ms-2 text-sm font-medium text-gray-900">Spam or Advertising</span>
                            </label>
                        </li>
                        <li class="w-full border-b border-gray-200">
                            <label for="reason-harassment" class="flex items-center gap-3 ps-3 py-3 w-full cursor-pointer">
                                <input id="reason-harassment" type="radio" value="Hate Speech or Harassment" name="reason" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                <span class="ms-2 text-sm font-medium text-gray-900">Hate Speech or Harassment</span>
                            </label>
                        </li>
                        <li class="w-full border-b border-gray-200">
                            <label for="reason-inappropriate" class="flex items-center gap-3 ps-3 py-3 w-full cursor-pointer">
                                <input id="reason-inappropriate" type="radio" value="Inappropriate Content (Sensitive, Sexual, etc.)" name="reason" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                <span class="ms-2 text-sm font-medium text-gray-900">Inappropriate Content (Sensitive, Sexual, etc.)</span>
                            </label>
                        </li>
                        <li class="w-full border-b border-gray-200">
                            <label for="reason-misinformation" class="flex items-center gap-3 ps-3 py-3 w-full cursor-pointer">
                                <input id="reason-misinformation" type="radio" value="Misinformation / Hoax" name="reason" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                <span class="ms-2 text-sm font-medium text-gray-900">Misinformation / Hoax</span>
                            </label>
                        </li>
                        <li class="w-full rounded-b-lg">
                            <label for="reason-other" class="flex items-center gap-3 ps-3 py-3 w-full cursor-pointer">
                                <input id="reason-other" type="radio" value="other" name="reason" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                <span class="ms-2 text-sm font-medium text-gray-900">Other</span>
                            </label>
                        </li>
                    </ul>

                    <div id="other-reason-container" class="mt-4 hidden">
                        <label for="other-reason-text" class="block mb-2 text-sm font-medium text-gray-900">Please explain your reason:</label>
                        <input id="other-reason-text" name="other_reason_text" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 cursor-pointer" placeholder="Write your specific reason here..." maxlength="80" />
                    </div>

                </form>

                <div class="flex justify-center gap-3 mt-6">
                    <button id="btn-confirm-report-forum" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center cursor-pointer">
                        Send Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const modalReportForum = document.getElementById("modal-report-forum");
    const btnOpenReportModal = document.getElementById("reportForumButton");
    const btnCloseReportForum = document.getElementById("btn-cancel-report-forum");
    const btnConfirmReportForum = document.getElementById("btn-confirm-report-forum");
    const formReportForum = document.getElementById("form-report-forum");
    const reasonRadios = document.querySelectorAll('input[name="reason"]');
    const otherReasonContainer = document.getElementById("other-reason-container");
    const otherReasonText = document.getElementById("other-reason-text");
    const messageReportForum = document.getElementById("report-forum-message");
    const successClasses = ['bg-green-100', 'border', 'border-green-400', 'text-green-800'];
    const errorClasses = ['bg-red-100', 'border', 'border-red-400', 'text-red-700'];

    otherReasonText.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
        }
    });

    const showMessage = (message, type) => {
        messageReportForum.classList.remove(...successClasses, ...errorClasses);

        if (type === 'success') {
            messageReportForum.classList.add(...successClasses);
        } else {
            messageReportForum.classList.add(...errorClasses);
        }
        messageReportForum.textContent = message;
        messageReportForum.classList.remove('hidden');
    };

    const openModalReportForum = () => {
        modalReportForum.classList.remove("hidden");
        modalReportForum.classList.add("flex");
    };

    const closeModalReportForum = () => {
        modalReportForum.classList.add("hidden");
        modalReportForum.classList.remove("flex");
        formReportForum.reset();
        messageReportForum.classList.add("hidden");
        otherReasonContainer.classList.add("hidden");
    };

    btnOpenReportModal?.addEventListener("click", openModalReportForum);
    btnCloseReportForum.addEventListener("click", closeModalReportForum);
    modalReportForum.addEventListener("click", (e) => {
        if (e.target === modalReportForum) closeModalReportForum();
    });
    reasonRadios.forEach(radio => {
        radio.addEventListener("change", () => {
            otherReasonContainer.classList.toggle("hidden", radio.value !== "other");
        });
    });

    btnConfirmReportForum.addEventListener("click", async () => {

        const formData = new FormData(formReportForum);
        const actionUrl = formReportForum.getAttribute("action");
        const selectedReason = formData.get("reason");

        if (!selectedReason) {
            showMessage("Please select one reason for the report.", "error");
            return;
        }
        if (selectedReason === "other" && !otherReasonText.value.trim()) {
            showMessage("Please explain your reasons in the space provided..", "error");
            return;
        }

        messageReportForum.classList.add("hidden");
        btnConfirmReportForum.disabled = true;
        btnConfirmReportForum.textContent = "Mengirim...";

        try {
            const response = await fetch(actionUrl, {
                method: "POST",
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                showMessage(result.message || "Report successfully sent!", "success");

                setTimeout(() => {
                    closeModalReportForum();
                }, 1500);
            } else {
                showMessage(result.message || "Failed to send report.", "error");
            }
        } catch (err) {
            showMessage("Terjadi kesalahan jaringan. Coba lagi.", "error");
        } finally {
            btnConfirmReportForum.disabled = false;
            btnConfirmReportForum.textContent = "Sent Report";
        }
    });
</script>