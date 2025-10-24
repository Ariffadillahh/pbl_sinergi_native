<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?= BASEURL ?>/src/css/output.css" rel="stylesheet">
    <title>Post | <?= htmlspecialchars($id) ?></title>
</head>

<body>
    <main class="w-full h-screen overflow-y-auto border-gray-200 hide-scrollbar relative">
        <!-- Header -->
        <div class="sticky top-0 z-10 bg-white w-full px-5 py-3 mb-4 border-b border-gray-200 flex items-center gap-3">
            <button onclick="window.history.back()" class="flex items-center gap-2 text-black font-semibold">
                <img src="<?= BASEURL ?>/src/asset/icons/left-arrow-svgrepo-com.svg" class="w-6 h-6" alt="icon">
                <h1 class="text-xl">Post</h1>
            </button>
        </div>

        <div class="max-w-xl mx-auto px-5 mb-20">
            <!-- Komponen post utama -->
            <?php require_once 'app/views/components/postingan/replyPost.php'; ?>

            <!-- Form tambah komentar -->
            <form id="form-comment" method="POST" action="<?= BASEURL ?>/comment/add"
                class="bg-white border-t border-gray-200 p-4 rounded-2xl mt-4">
                <input type="hidden" name="post_id" value="<?= $id ?>">
                <div class="flex items-start space-x-3">
                    <img src="<?= BASEURL ?>/src/asset/image/default.png" alt="Your Profile" class="w-12 h-12 rounded-full">
                    <textarea name="message" id="comment-message"
                        class="w-full bg-gray-50 border rounded-lg px-4 py-2 resize-none focus:ring-2 focus:ring-blue-500"
                        rows="2" placeholder="Tulis komentar..."></textarea>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg transition">
                        Kirim
                    </button>
                </div>
            </form>

            <!-- Daftar komentar -->
            <div id="comment-section" class="mt-6 space-y-4"></div>
        </div>
    </main>

    <script>
        const BASEURL = "<?= BASEURL ?>";
        const postId = "<?= $id ?>";
        const commentSection = document.getElementById("comment-section");

        // === Ambil komentar dari server ===
        async function loadComments() {
            const res = await fetch(`${BASEURL}/comment/get?id=${postId}`);
            const data = await res.json();

            if (data.success) renderComments(data.comments);
            else commentSection.innerHTML = "<p class='text-center text-gray-500'>Gagal memuat komentar.</p>";
        }

        // === Render komentar dan reply ===
        function renderComments(comments) {
            commentSection.innerHTML = comments.map(c => `
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <img src="${BASEURL}/src/asset/image/default.png" class="w-10 h-10 rounded-full" alt="pfp">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold">${c.USERNAME}</span>
                                <span class="text-xs text-gray-400">${c.CREATED_AT}</span>
                            </div>
                            <p class="mt-1 text-gray-700">${c.MESSAGE}</p>
                        </div>
                    </div>

                    <!-- Reply Section -->
                    <div class="ml-12 mt-3 space-y-2">
                        ${c.REPLIES.map(r => `
                            <div class="flex items-start gap-3">
                                <img src="${BASEURL}/src/asset/image/default.png" class="w-8 h-8 rounded-full" alt="pfp">
                                <div>
                                    <span class="font-semibold text-sm">${r.USERNAME}</span>
                                    <p class="text-gray-700 text-sm">${r.MESSAGE}</p>
                                </div>
                            </div>
                        `).join('')}

                        <!-- Form balasan -->
                        <form class="reply-form flex gap-2 mt-2" data-comment="${c.COMMENT_ID}">
                            <input type="text" name="message" class="flex-1 bg-gray-50 border rounded-full px-3 py-1 text-sm" placeholder="Balas komentar...">
                            <button class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm hover:bg-blue-600">Balas</button>
                        </form>
                    </div>
                </div>
            `).join('');
        }

        // === Tambah komentar utama ===
        document.getElementById("form-comment").addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            const res = await fetch(e.target.action, { method: "POST", body: formData });
            const data = await res.json();

            if (data.success) {
                e.target.reset();
                loadComments();
            } else alert(data.message || "Gagal mengirim komentar");
        });

        // === Balasan komentar ===
        commentSection.addEventListener("submit", async (e) => {
            if (e.target.classList.contains("reply-form")) {
                e.preventDefault();
                const commentId = e.target.dataset.comment;
                const message = e.target.querySelector("input").value;

                const formData = new FormData();
                formData.append("comment_id", commentId);
                formData.append("message", message);

                const res = await fetch(`${BASEURL}/comment/reply`, { method: "POST", body: formData });
                const data = await res.json();

                if (data.success) {
                    e.target.reset();
                    loadComments();
                } else alert("Gagal menambahkan balasan");
            }
        });

        loadComments();
    </script>
</body>
</html>
