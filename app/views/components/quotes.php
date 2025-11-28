<div id="quote-modal" class="hidden fixed inset-0 z-[99999] justify-center items-center w-full h-full bg-black/50 p-5 md:p-0 backdrop-blur-sm">
    <div class="relative bg-gradient-to-br from-blue-500 via-indigo-700 to-blue-900 rounded-2xl shadow-lg p-8 w-full max-w-md">
        <div class="absolute -top-16 -right-10">
            <button id="close-quote-btn"
                class="absolute top-4 right-6 md:right-4 text-white focus:outline-none bg-gradient-to-br from-blue-500 via-indigo-700 to-blue-900 p-2 rounded-full shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="justify-end flex">
            <img src="<?php echo BASEURL; ?>/src/asset/icons/quotes.png" class="h-8 w-8 mb-4" alt="Quotes of the Day icon">
        </div>

        <div class="text-white max-full">
            <div id="quote-spinner" class="hidden justify-center items-center h-24">
                <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </div>

            <h1 id="quote-text" class="text-2xl font-bold mb-4 text-justify">The only way to do great work is to love what you do.</h1>
            <p id="quote-author" class="text-md font-medium pt-6">Steve Jobs</p>
        </div>

        <div class="absolute -bottom-16 left-1/2 -translate-x-1/2 w-full px-8 flex justify-center">
            <button id="generate-quote-btn" type="button"
                class="flex items-center justify-center gap-2 w-full max-w-xs sm:max-w-none sm:w-auto py-3 px-6 text-sm font-semibold
                   bg-white rounded-full border border-gray-200 
                   hover:bg-gray-100 text-blue-700 
                   focus:z-10  
                   shadow-lg hover:shadow-xl transform hover:-translate-y-0.5
                   transition-all duration-200 ease-in-out">
                <img src="<?php echo BASEURL; ?>/src/asset/icons/star.png" class="h-4 w-4" alt="icon">
                Generate New Quote
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const quoteModal = document.getElementById("quote-modal");
        const openQuoteBtn = document.getElementById("quote-btn-opn");
        const quoteText = document.getElementById("quote-text");
        const quoteAuthor = document.getElementById("quote-author");
        const spinner = document.getElementById("quote-spinner");
        const generateBtn = document.getElementById("generate-quote-btn");
        const closeBtn = document.getElementById("close-quote-btn");

        closeBtn.addEventListener("click", () => {
            quoteModal.classList.add("hidden");
            quoteModal.classList.remove("flex");
        });

        function loadStoredQuote() {
            const storedQuote = localStorage.getItem("quoteText");
            const storedAuthor = localStorage.getItem("quoteAuthor");

            if (storedQuote && storedAuthor) {
                quoteText.textContent = storedQuote;
                quoteAuthor.textContent = storedAuthor;
                return true;
            }
            return false;
        }

        async function generateQuote() {
            try {
                spinner.classList.remove("hidden");
                spinner.classList.add("flex");
                quoteText.classList.add("hidden");
                quoteAuthor.classList.add("hidden");

                const response = await fetch("http://api.quotable.io/random");
                if (!response.ok) throw new Error("Gagal mengambil quote");

                const data = await response.json();

                quoteText.textContent = data.content;
                quoteAuthor.textContent = data.author;

                localStorage.setItem("quoteText", data.content);
                localStorage.setItem("quoteAuthor", data.author);

            } catch (error) {
                console.error("Terjadi kesalahan:", error);
                quoteText.textContent = "Gagal memuat quote. Coba lagi nanti.";
                quoteAuthor.textContent = "";
            } finally {
                spinner.classList.add("hidden");
                spinner.classList.remove("flex");
                quoteText.classList.remove("hidden");
                quoteAuthor.classList.remove("hidden");
            }
        }

        openQuoteBtn.addEventListener("click", async () => {
            quoteModal.classList.remove("hidden");
            quoteModal.classList.add("flex");

            const hasStoredQuote = loadStoredQuote();
            if (!hasStoredQuote) {
                await generateQuote();
            }
        });

        generateBtn.addEventListener("click", generateQuote);

        loadStoredQuote();
    });
</script>