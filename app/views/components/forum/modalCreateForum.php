<div id="modalOverlay" class="fixed inset-0 bg-black/50 flex items-center justify-center z-[9999] hidden px-4 transition-opacity duration-300 opacity-0">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all duration-300 scale-95 flex flex-col max-h-[90vh]">

        <div class="relative shrink-0 bg-gray-50 border-b border-gray-200 z-10">
            <div id="bannerPreview" class="h-36 md:h-44 bg-gray-300 w-full object-cover flex items-center justify-center relative overflow-hidden group">
                <span id="defaultBannerText" class="text-gray-500 font-bold text-3xl tracking-widest select-none opacity-50">SINERGI</span>
                <img id="bannerImage" src="" alt="Banner" class="w-full h-full object-cover absolute inset-0 hidden">
            </div>

            <div class="absolute bottom-0 left-0 right-0 px-6 translate-y-1/2 flex items-end justify-between z-10">
                <div class="flex items-end gap-4">
                    <div class="relative group cursor-pointer">
                        <img id="profilePreview" src="https://ui-avatars.com/api/?name=New+Forum&&background=3B82F6&color=fff&size=128"
                            alt="Profile"
                            class="w-20 h-20 md:w-24 md:h-24 rounded-xl border-4 border-white bg-white shadow-lg object-cover">
                    </div>
                    <div class="mb-1 md:mb-2 pt-16 ">
                        <h2 id="groupNamePreview" class="text-xl md:text-2xl font-bold text-gray-900 leading-tight truncate max-w-[200px] md:max-w-xs drop-shadow-sm bg-white/50 backdrop-blur-sm px-2 rounded-lg">New Forum</h2>
                        <p class="mt-1 flex items-center gap-1 bg-white/80 backdrop-blur-md px-2 py-0.5 rounded-full w-max shadow-sm border border-gray-100">
                            <span id="privacyIcon" class="text-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <span id="privacyText">Public</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <form id="createForumForm" class="p-6 pt-20 overflow-y-auto hide-scrollbar" method="POST" action="<?php echo BASEURL ?>/forum/createForum" enctype="multipart/form-data">

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Forum Details</h2>
                <button type="button" id="closeModalBtn" class="cursor-pointer text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="alertBox" class="hidden fixed top-10 right-10 mb-4 p-4 rounded-lg text-sm"></div>

            <div class="mb-5 relative">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Forum Name</label>

                <div class="relative">
                    <input
                        type="text"
                        id="groupNameInput"
                        name="NAME"
                        placeholder="Example: Indonesian Coding Community"
                        class="w-full px-4 py-3 pr-14 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        required>
                    <span
                        id="nameCounter"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">
                        0/30
                    </span>
                </div>

            </div>


            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Privacy</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer relative">
                        <input type="radio" name="IS_PRIVATE" value="0" class="peer sr-only" checked>
                        <div class="p-3 rounded-xl border border-gray-200 hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-500 transition flex items-center gap-3">
                            <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-sm font-semibold text-gray-900">Public</span>
                                <span class="block text-xs text-gray-500 truncate">Everyone can see</span>
                            </div>
                        </div>
                    </label>

                    <label class="cursor-pointer relative">
                        <input type="radio" name="IS_PRIVATE" value="1" class="peer sr-only">
                        <div class="p-3 rounded-xl border border-gray-200 hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-500 transition flex items-center gap-3">
                            <div class="bg-gray-100 text-gray-600 p-2 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <span class="block text-sm font-semibold text-gray-900">Private</span>
                                <span class="block text-xs text-gray-500 truncate">Members only</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div id="accessKeyContainer" class="mb-5 hidden transition-all duration-300">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Access Key (Password)</label>
                <div class="relative">
                    <input type="text" id="accessKeyInput" name="ACCESS_KEY" placeholder="Create a secret key..."
                        class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-yellow-50">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Profile Photo</label>
                    <label for="profileInput" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-blue-50 hover:border-blue-300 transition group relative overflow-hidden">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 z-10">
                            <div class="bg-white p-2 rounded-full shadow-sm mb-2 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">Upload Profile</p>
                        </div>
                        <input id="profileInput" type="file" name="PATH_PHOTO" class="hidden" accept="image/*" />
                        <img id="profileInputPreview" src="" class="absolute inset-0 w-full h-full object-cover opacity-40 hidden">
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Banner / Cover</label>
                    <label for="bannerInput" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-blue-50 hover:border-blue-300 transition group relative overflow-hidden">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 z-10">
                            <div class="bg-white p-2 rounded-full shadow-sm mb-2 group-hover:scale-110 transition">
                                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">Upload Banner</p>
                        </div>
                        <input id="bannerInput" type="file" name="PATH_THUMBNAIL" class="hidden" accept="image/*" />
                        <img id="bannerInputPreview" src="" class="absolute inset-0 w-full h-full object-cover opacity-40 hidden">
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>

                <div class="relative">
                    <textarea
                        id="aboutText"
                        rows="3"
                        name="ABOUT"
                        placeholder="Explain the purpose of this forum..."
                        class="w-full px-4 py-3 pr-14 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"></textarea>

                    <span
                        id="aboutCounter"
                        class="absolute right-3 bottom-3 text-xs pt-3 text-gray-400 pointer-events-none">
                        0/150
                    </span>
                </div>
            </div>


            <div class="flex justify-end gap-3 pt-2">
                <button type="button" id="cancelBtn" class="cursor-pointer px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" id="submitBtn" class="cursor-pointer px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                    <span>Create Forum</span>
                </button>
            </div>

        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalOverlay = document.getElementById('modalOverlay');
        const modalContainer = modalOverlay.querySelector('div');
        const openBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');

        const form = document.getElementById('createForumForm');
        const submitBtn = document.getElementById('submitBtn');
        const alertBox = document.getElementById('alertBox');

        const nameInput = document.getElementById('groupNameInput');
        const profileInput = document.getElementById('profileInput');
        const bannerInput = document.getElementById('bannerInput');
        const privacyInputs = document.querySelectorAll('input[name="IS_PRIVATE"]');
        const accessKeyContainer = document.getElementById('accessKeyContainer');

        const namePreview = document.getElementById('groupNamePreview');
        const profilePreview = document.getElementById('profilePreview');
        const bannerImage = document.getElementById('bannerImage');
        const defaultBannerText = document.getElementById('defaultBannerText');
        const privacyText = document.getElementById('privacyText');
        const privacyIcon = document.getElementById('privacyIcon');
        const profileInputPreview = document.getElementById('profileInputPreview');
        const bannerInputPreview = document.getElementById('bannerInputPreview');
        const aboutText = document.getElementById('aboutText');
        const aboutCounter = document.getElementById('aboutCounter');
        const counter = document.getElementById('nameCounter');

        let isCustomProfile = false;

        const MAX_LENGTH = 30;
        const ABOUT_MAX = 150;

        aboutText.addEventListener('input', function() {
            if (this.value.length > ABOUT_MAX) {
                this.value = this.value.slice(0, ABOUT_MAX);
            }
            aboutCounter.textContent = `${this.value.length}/${ABOUT_MAX}`;
        });

        nameInput.addEventListener('input', function() {
            if (this.value.length > MAX_LENGTH) {
                this.value = this.value.slice(0, MAX_LENGTH);
            }
            counter.textContent = `${this.value.length}/${MAX_LENGTH}`;
        });

        function toggleModal(show) {
            if (show) {
                modalOverlay.classList.remove('hidden');
                setTimeout(() => {
                    modalOverlay.classList.remove('opacity-0');
                    modalContainer.classList.remove('scale-95');
                }, 10);
            } else {
                modalOverlay.classList.add('opacity-0');
                modalContainer.classList.add('scale-95');
                setTimeout(() => modalOverlay.classList.add('hidden'), 300);
            }
        }

        openBtn.onclick = () => toggleModal(true);
        closeBtn.onclick = () => toggleModal(false);
        cancelBtn.onclick = () => toggleModal(false);
        modalOverlay.onclick = (e) => {
            if (e.target === modalOverlay) toggleModal(false);
        };

        nameInput.addEventListener('input', function() {
            const name = this.value.trim() || "New Group";
            namePreview.textContent = name;
            if (!isCustomProfile) {
                profilePreview.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=3B82F6&color=fff&size=128`;
            }
        });

        privacyInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value === '0') {
                    privacyText.textContent = "Public";
                    privacyIcon.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>`;
                    accessKeyContainer.classList.add('hidden');
                } else {
                    privacyText.textContent = "Private";
                    privacyIcon.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>`;
                    accessKeyContainer.classList.remove('hidden');
                }
            });
        });


        profileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    profilePreview.src = evt.target.result;
                    profileInputPreview.src = evt.target.result;
                    profileInputPreview.classList.remove('hidden');
                    isCustomProfile = true;
                }
                reader.readAsDataURL(file);
            }
        });

        bannerInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    bannerImage.src = evt.target.result;
                    bannerImage.classList.remove('hidden');
                    defaultBannerText.classList.add('hidden');
                    bannerInputPreview.src = evt.target.result;
                    bannerInputPreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...`;

            alertBox.classList.add('hidden');

            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData
                });

                const textResult = await response.text();
                let result;
                try {
                    result = JSON.parse(textResult);
                } catch (err) {
                    throw new Error("Invalid server response: " + textResult);
                }

                if (result.status === 'success' || response.ok) {

                    showAlert('success', 'Forum created successfully!');

                    setTimeout(() => {
                        toggleModal(false);

                        if (result.id) {
                            window.location.href = `<?php echo BASEURL ?>/forum/${result.id}`;
                        }

                    }, 1500);

                    this.reset();

                } else {
                    throw new Error(result.message || "Failed to create forum.");
                }


            } catch (error) {
                console.error('Error:', error);
                showAlert('error', error.message || "A system error occurred.");
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });

        function showAlert(type, message) {
            alertBox.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
            if (type === 'success') {
                alertBox.classList.add('bg-green-100', 'text-green-700');
            } else {
                alertBox.classList.add('bg-red-100', 'text-red-700');
            }
            alertBox.textContent = message;
        }
    });
</script>