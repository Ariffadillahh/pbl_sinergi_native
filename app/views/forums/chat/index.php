<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo BASEURL; ?>/src/css/output.css" rel="stylesheet">
    <title>Forums - <?= $forumByid["NAME"] ?></title>
</head>

<body>

    <div class="relative flex h-screen overflow-hidden bg-gray-50">

        <?php require_once 'app/views/components/sidebars.php'; ?>
        <?php require_once 'app/views/components/forums/forumsList.php'; ?>

        <main id="Main-Content-Container" class="relative flex flex-1">
            <div id="Chat-Container" class="flex flex-col flex-1 h-full overflow-hidden">

                <?php require_once 'app/views/components/forums/detailForum.php'; ?>

                <div id="Chat-Messages" class="relative flex-1 overflow-y-auto">
                    <div id="loading-indicator" class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <p class="mt-2 text-gray-500">Memuat pesan...</p>
                        </div>
                    </div>

                    <article class="relative flex flex-col gap-5 p-5 z-0 mb-[30px] md:mb-0">
                    </article>
                </div>


                <div class="relative flex w-full z-10 bottom-14 lg:bottom-0">
                    <form id="chat-form" class="w-full p-5 gap-[10px] z-20">
                        <div id="preview-container" class="hidden relative w-full p-3 mb-2 bg-gray-100 rounded-lg pr-11 md:pr-0">
                            <div class="flex items-start sm:items-center gap-3 flex-wrap sm:flex-nowrap">
                                <div class="shrink-0">
                                    <img id="preview-image" src="" class="w-16 h-16 sm:w-12 sm:h-12 object-cover rounded" alt="File Preview">
                                </div>

                                <div class="flex-1 min-w-0">
                                    <span id="preview-filename" class="text-sm text-gray-700 break-words line-clamp-2 sm:truncate block"></span>
                                </div>
                            </div>

                            <button type="button" id="remove-preview"
                                class="absolute top-2 right-2 size-7 sm:size-6 flex items-center justify-center hover:bg-gray-300 bg-gray-200 rounded-full transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>

                        <div class="relative">
                            <div id="Chat-Input" contenteditable="true" spellcheck="false"
                                class="appearance-none outline-none w-full min-h-[60px] max-h-[200px] overflow-y-auto rounded-2xl p-5 pl-4 pr-[112px] sm:pr-[120px] bg-white break-all font-medium leading-5 hide-scrollbar focus:ring-2 focus:ring-blue-600 transition-all duration-300 text-gray-900 shadow-sm">
                            </div>

                            <div id="placeholder" class="absolute top-5 left-4 text-gray-500 pointer-events-none select-none text-sm sm:text-base">
                                Type a message...
                            </div>

                            <div class="absolute flex right-2 bottom-2 gap-1.5 sm:gap-2">
                                <button type="button" id="Upload-Image"
                                    class="size-10 sm:size-11 flex shrink-0 bg-white rounded-xl p-2 sm:p-[10px] items-center justify-center ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300">
                                    <img src="<?php echo BASEURL; ?>/src/asset/icons/gallery-import.svg" class="w-5 h-5 sm:w-6 sm:h-6" alt="icon">
                                </button>
                                <button type="submit" id="kirim" class="flex shrink-0 w-10 sm:w-11">
                                    <img src="<?php echo BASEURL; ?>/src/asset/icons/Send-Button-blue-bg.svg" class="object-contain" alt="icon">
                                </button>
                            </div>
                        </div>
                    </form>
                    <input type="file" id="imageInput" class="hidden" accept="image/*,.pdf,.doc,.docx,video/*" />
                    <input type="hidden" id="message" name="message">
                </div>

            </div>
        </main>
        <?php require_once 'app/views/components/modalInvite.php'; ?>
    </div>

    <div id="imageModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center p-4 z-[9999] backdrop-blur-sm">
        <div class="relative w-full h-full flex items-center justify-center">

            <div class="absolute top-4 right-4 flex gap-2 z-10">
                <a id="downloadButton" href="" download
                    class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full font-bold transition-all w-10 h-10 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </a>

                <button onclick="closeImageModal()"
                    class="bg-white hover:bg-gray-100 text-black p-2 rounded-full font-bold transition-all w-10 h-10 flex items-center justify-center shadow-lg">
                    ✕
                </button>
            </div>

            <div class="relative max-w-[95vw] max-h-[95vh] flex items-center justify-center">
                <img id="modalImage"
                    class="rounded-lg max-w-full max-h-[95vh] w-auto h-auto object-contain shadow-2xl"
                    src=""
                    alt="Full size image">
            </div>
        </div>
    </div>




    <script>
        function openImageModal(src) {
            document.getElementById("modalImage").src = src;
            document.getElementById("downloadButton").href = src; 

            const modal = document.getElementById("imageModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
            document.body.style.overflow = "hidden";
        }

        function closeImageModal() {
            const modal = document.getElementById("imageModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
            document.body.style.overflow = "auto";
        }

        document.addEventListener("DOMContentLoaded", () => {
            const BASEURL = '<?php echo BASEURL; ?>';
            const FORUM_ID = '<?= $forumByid['ID'] ?? '1' ?>';
            const CURRENT_USER_ID = '<?= $_SESSION['user_id'] ?? '123' ?>';
            const CURRENT_USER_NAME = "<?= $_SESSION['full_name'] ?? 'You' ?>";
            const CURRENT_USER_PHOTO = "<?= !empty($_SESSION['path_photo']) ? BASEURL . '/storage/users/photos/' . $_SESSION['path_photo'] : BASEURL . '/src/asset/image/default.png' ?>";
            const fileIconUrl = `${BASEURL}/src/asset/image/file.png`;

            const chatForm = document.getElementById("chat-form");
            const chatInput = document.getElementById("Chat-Input");
            const placeholder = document.getElementById("placeholder");
            const hiddenInput = document.getElementById("message");
            const uploadButton = document.getElementById("Upload-Image");
            const fileInput = document.getElementById("imageInput");
            const previewContainer = document.getElementById("preview-container");
            const previewImage = document.getElementById("preview-image");
            const previewFilename = document.getElementById("preview-filename");
            const removePreviewButton = document.getElementById("remove-preview");

            let lastSenderId = null;
            let lastMessageTime = null;
            let lastTimestamp = "<?= (new DateTime())->format('Y-m-d H:i:s.u') ?>";
            let lastRenderedDate = null;

            const statusIcons = {
                pending: `<svg class="size-3 sm:size-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`,
                sent: `<svg class="size-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`,
                failed: `<svg class="size-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.414L11 9.586V6z" clip-rule="evenodd"></path></svg>`,
            };

            function renderMessage(msg) {
                const chatMessagesContainer = document.querySelector("#Chat-Messages article");

                if (msg.CREATED_AT) {
                    const currentMessageDate = msg.CREATED_AT.substring(0, 10);
                    if (currentMessageDate !== lastRenderedDate) {
                        const dateHeader = document.createElement('p');
                        dateHeader.className = "sticky w-[100px] md:w-[150px] text-center top-4 mt-5 mx-auto rounded-xl py-2 px-4 bg-white text-xs md:text-sm z-30 border border-gray-200 whitespace-nowrap";
                        dateHeader.textContent = formatDateHeader(currentMessageDate);
                        chatMessagesContainer.appendChild(dateHeader);
                        lastRenderedDate = currentMessageDate;
                        lastSenderId = null;
                    }
                }

                const messageId = msg.ID || msg.temp_id || `temp-${Date.now()}`;
                const isOutgoing = msg.SENDER_ID == CURRENT_USER_ID;

                let showHeader = !(lastSenderId === msg.SENDER_ID);
                lastSenderId = msg.SENDER_ID;

                let senderHtml = '';
                if (showHeader) {
                    let avatarSrc = isOutgoing ? CURRENT_USER_PHOTO :
                        (msg.SENDER_PHOTO ? `${BASEURL}/storage/users/photos/${msg.SENDER_PHOTO}` : `${BASEURL}/src/asset/image/default.png`);

                    const senderName = isOutgoing ? 'You' : (msg.SENDER_NAME || 'User');
                    let roleHtml = '';

                    if (!isOutgoing && msg.ROLE) {
                        const roleStyles = {
                            'ADMIN': 'bg-red-100 text-red-800',
                            'DOSEN': 'bg-green-100 text-green-800',
                            'MAHASISWA': 'bg-blue-100 text-blue-800',
                            'ALUMNI': 'bg-yellow-100 text-yellow-800',
                            'default': 'bg-gray-100 text-gray-800'
                        };

                        const styles = roleStyles[msg.ROLE] || roleStyles['default'];

                        roleHtml = `<span class="text-xs font-semibold px-2 py-0.5 rounded-full ${styles}">${msg.ROLE}</span>`;
                    }


                    senderHtml = `
                        <div class="flex items-center gap-2 sm:gap-3 ${isOutgoing ? 'flex-row-reverse' : ''}">
                            <div class="flex size-8 sm:size-10 shrink-0 overflow-hidden rounded-full">
                                <img src="${avatarSrc}" class="w-full h-full object-cover" alt="photo">
                            </div>
                            <div>
                                <div class="block text-xs sm:text-sm ${isOutgoing ? 'flex-row-reverse' : ''}">
                                    <span class="font-bold text-black truncate max-w-[120px] sm:max-w-none">${senderName} </span>
                                    ${roleHtml} 
                                </div>
                            </div>
                        </div>
                    `;
                }

                let contentHtml = '';
                if (msg.PATH_MEDIA) {
                    const mediaSrc = msg.status === 'pending' ? msg.PATH_MEDIA : `${BASEURL}/${msg.PATH_MEDIA}`;
                    if (msg.TYPE === 'IMAGE') {
                        contentHtml += `
                            <div class="relative group w-full md:max-w-xl cursor-pointer" onclick="openImageModal('${mediaSrc}')">
                                <img src="${mediaSrc}" 
                                    class="rounded-lg w-full h-auto object-cover" 
                                    alt="Image" loading="lazy">

                                <!-- Hover Button -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 
                                        flex items-center justify-center text-white font-semibold 
                                        transition-all rounded-lg pointer-events-none">
                                    Lihat
                                </div>
                            </div>
                        `;
                    } else if (msg.TYPE === 'VIDEO') {
                        contentHtml += `<video controls class="rounded-lg w-full md:max-w-xl h-auto"><source src="${mediaSrc}"></video>`;
                    } else if (msg.TYPE === 'FILE') {
                        const fileName = msg.ORIGINAL_FILENAME || 'Download File';
                        const cardBgColor = isOutgoing ? 'bg-white' : 'bg-gray-200';
                        const textColor = isOutgoing ? 'text-blue-700' : 'text-gray-800';
                        const buttonBgColor = isOutgoing ? 'bg-blue-700 hover:bg-blue-600' : 'bg-white hover:bg-gray-100';
                        const buttonTextColor = isOutgoing ? 'text-white' : 'text-gray-900';
                        contentHtml += `
                            <div class="w-full max-w-[280px] sm:max-w-sm ${cardBgColor} rounded-lg p-3 flex flex-col gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <img src="${fileIconUrl}" class="w-7 h-7" >
                                    <span class="font-medium ${textColor} truncate text-sm sm:text-base">${fileName}</span>
                                </div>
                                <a href="${mediaSrc}" download="${fileName}" target="_blank" class="w-full ${buttonBgColor} ${buttonTextColor} font-semibold py-2 px-4 rounded-lg transition-all active:scale-95 text-center text-sm sm:text-base">
                                    Download
                                </a>
                            </div>
                        `;
                    }
                }

                const textContent = msg.CONTENT;
                if (textContent) {
                    const textMargin = msg.PATH_MEDIA ? 'mt-2' : '';
                    contentHtml += `<p class="whitespace-pre-wrap break-words text-sm sm:text-base ${textMargin}">${textContent}</p>`;
                }

                const alignClass = isOutgoing ? 'items-end' : 'items-start';
                const roundedClass = isOutgoing ? 'rounded-br-none bg-blue-600 text-white' : 'rounded-tl-none bg-white text-gray-900';
                const marginClass = showHeader ? 'mt-3 sm:mt-4' : 'mt-1';

                function getStatusIcon(status) {
                    if (!isOutgoing) return '';
                    const iconSvg = statusIcons[status] || '';
                    const sentIconColor = (status === 'sent' && isOutgoing) ? 'text-white' : '';
                    const finalIconSvg = iconSvg.replace('<svg class="', `<svg class="${sentIconColor} `);
                    return `<div class="message-status-indicator flex items-center justify-end h-4 mt-1">${finalIconSvg}</div>`;
                }

                const time = msg.CREATED_AT ? new Date(msg.CREATED_AT.replace(' ', 'T')).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                }) : new Date().toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const messageCardHtml = `
                    <div class="message-card relative ${isOutgoing ? 'mr-8 sm:mr-12' : 'ml-8 sm:ml-12'}">
                        <div class="w-fit max-w-[280px] sm:max-w-sm md:max-w-md rounded-2xl py-2 px-3 sm:py-2.5 sm:px-3.5 leading-relaxed shadow ${roundedClass}">
                            
                            <div>${contentHtml}</div>

                            <div class="flex items-center justify-end gap-1.5 mt-1 text-xs ${isOutgoing ? 'text-blue-100 opacity-80' : 'text-gray-500'}">
                                <span class="message-timestamp">${time}</span>
                                <span class="message-status-indicator">${getStatusIcon(msg.status)}</span>
                            </div>

                        </div>
                    </div>
                `;

                const messageRow = document.createElement('div');
                messageRow.id = `message-${messageId}`;
                messageRow.className = `chat-row flex flex-col px-2 sm:px-4 ${alignClass} ${marginClass}`;
                messageRow.innerHTML = senderHtml + messageCardHtml;
                chatMessagesContainer.appendChild(messageRow);

                requestAnimationFrame(() => {
                    const container = chatMessagesContainer.parentElement;
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                });
            }

            function updateMessageStatus(tempId, newStatus, serverResult = null) {
                const messageElement = document.querySelector(`#message-${tempId}`);
                if (!messageElement) return;

                const statusIndicator = messageElement.querySelector('.message-status-indicator');
                if (!statusIndicator) return;

                statusIndicator.innerHTML = statusIcons[newStatus] || '';

                if (newStatus === 'sent' && serverResult && serverResult.message_id) {
                    messageElement.id = `message-${serverResult.message_id}`;
                }

                if (newStatus === 'failed') {
                    const messageCard = messageElement.querySelector('.message-card');
                    messageCard.title = "Pesan gagal terkirim.";
                }
            }

            async function handleSendMessage(event, elements) {
                event.preventDefault();
                const {
                    hiddenInput,
                    fileInput,
                    chatInput,
                    removePreview,
                    updateTextInputState
                } = elements;
                const messageText = hiddenInput.value.trim();
                const file = fileInput.files[0];

                if (!messageText && !file) return;

                const tempId = crypto.randomUUID();
                const tempMessage = {
                    temp_id: tempId,
                    SENDER_ID: CURRENT_USER_ID,
                    SENDER_NAME: CURRENT_USER_NAME,
                    SENDER_PHOTO: CURRENT_USER_PHOTO,
                    CONTENT: messageText,
                    PATH_MEDIA: file ? URL.createObjectURL(file) : null,
                    ORIGINAL_FILENAME: file ? file.name : null,
                    TYPE: file ? (file.type.startsWith("image/") ? 'IMAGE' : (file.type.startsWith("video/") ? 'VIDEO' : 'FILE')) : 'TEXT',
                    CREATED_AT: new Date().toISOString(),
                    status: 'pending'
                };

                renderMessage(tempMessage);
                chatInput.innerHTML = "";
                removePreview();
                updateTextInputState();

                const formData = new FormData();
                formData.append("forum_id", FORUM_ID);
                formData.append("message", messageText);
                if (file) {
                    formData.append("attachment", file, file.name);
                }

                try {
                    const response = await fetch(`${BASEURL}/forums/send-message`, {
                        method: "POST",
                        body: formData,
                    });
                    const result = await response.json();
                    if (!response.ok) {
                        throw new Error(result.error || 'Server mengalami masalah.');
                    }
                    updateMessageStatus(tempId, 'sent', result);
                } catch (error) {
                    console.error("Gagal mengirim pesan:", error);
                    updateMessageStatus(tempId, 'failed');
                }
            }

            function setupChatForm() {
                if (!chatInput) return;
                const elements = {
                    hiddenInput,
                    fileInput,
                    chatInput,
                    removePreview: () => {
                        previewContainer.classList.add("hidden");
                        fileInput.value = "";
                        previewImage.src = "";
                    },
                    updateTextInputState: () => {
                        const textContent = chatInput.innerText;
                        placeholder.style.display = textContent.trim() === "" ? "block" : "none";
                        hiddenInput.value = textContent;
                    },
                };
                chatInput.addEventListener("input", elements.updateTextInputState);
                uploadButton.addEventListener("click", () => fileInput.click());
                fileInput.addEventListener("change", (event) => {
                    const file = event.target.files[0];
                    if (!file) return;
                    previewFilename.textContent = file.name;
                    previewImage.src = file.type.startsWith("image/") ? URL.createObjectURL(file) : fileIconUrl;
                    previewContainer.classList.remove("hidden");
                });
                removePreviewButton.addEventListener("click", elements.removePreview);
                chatForm.addEventListener("submit", (event) => handleSendMessage(event, elements));
                elements.updateTextInputState();
            }

            async function longPoll() {
                try {
                    const response = await fetch(`${BASEURL}/forums/get-new-messages?forum_id=${FORUM_ID}&since=${encodeURIComponent(lastTimestamp)}`);
                    if (!response.ok) {
                        console.error(`Polling request failed with status: ${response.status}`);
                        await new Promise(resolve => setTimeout(resolve, 10000));
                        return;
                    }
                    const messages = await response.json();
                    if (messages.length > 0) {
                        messages.forEach(msg => {
                            if (msg.SENDER_ID == CURRENT_USER_ID) {
                                return;
                            }
                            const existingMessage = document.getElementById(`message-${msg.ID}`);
                            if (!existingMessage) {
                                msg.status = 'sent';
                                renderMessage(msg);
                            }
                        });
                        lastTimestamp = messages[messages.length - 1].CREATED_AT;
                    }
                } catch (error) {
                    console.error('Long polling error:', error);
                    await new Promise(resolve => setTimeout(resolve, 5000));
                } finally {
                    longPoll();
                }
            }

            async function loadInitialMessages() {
                const chatMessagesContainer = document.querySelector("#Chat-Messages article");
                const loadingIndicator = document.getElementById("loading-indicator");
                chatMessagesContainer.innerHTML = '';
                loadingIndicator.style.display = 'flex';
                try {
                    const response = await fetch(`${BASEURL}/forums/getInitialMessages/${FORUM_ID}`);
                    if (!response.ok) {
                        throw new Error('Gagal mengambil data pesan.');
                    }
                    const messages = await response.json();
                    loadingIndicator.style.display = 'none';
                    chatMessagesContainer.innerHTML = '';
                    if (messages && messages.length > 0) {
                        messages.forEach(msg => {
                            msg.status = 'sent';
                            renderMessage(msg);
                        });
                        lastTimestamp = messages[messages.length - 1].CREATED_AT;
                    }
                } catch (error) {
                    console.error('Gagal memuat pesan awal:', error);
                    loadingIndicator.innerHTML = '<p class="text-red-500">Gagal memuat pesan. Coba muat ulang halaman.</p>';
                } finally {
                    longPoll();
                }
            }

            function formatDateHeader(dateString) {
                const today = new Date();
                const yesterday = new Date();
                yesterday.setDate(yesterday.getDate() - 1);
                const msgDate = new Date(dateString);
                today.setHours(0, 0, 0, 0);
                yesterday.setHours(0, 0, 0, 0);
                msgDate.setHours(0, 0, 0, 0);
                if (today.getTime() === msgDate.getTime()) {
                    return 'Today';
                }
                if (yesterday.getTime() === msgDate.getTime()) {
                    return 'Yesterday';
                }
                return msgDate.toLocaleDateString('en-GB', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }

            document.getElementById("imageModal").addEventListener("click", function(e) {
                if (e.target === this || e.target.tagName === 'BUTTON') {
                    closeImageModal();
                }
            });

            setupChatForm();
            loadInitialMessages();
        });
    </script>
</body>

</html>