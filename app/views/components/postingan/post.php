<?php if (empty($users)): ?>
    <tr>
        <td colspan="3" class="text-center border p-2">Tidak ada data pengguna untuk ditampilkan.</td>
    </tr>
<?php else: ?>
    <?php foreach ($users as $user): ?>
        <div class="my-4">
            <div class="max-w-xl w-full mx-auto">
                <div class="bg-white text-black border border-gray-200 rounded-2xl p-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <img src="<?php echo BASEURL . '/src/asset/image/default.png'; ?>" alt="Profile" class="w-12 h-12 rounded-full">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-1">
                                <span class="text-gray-500">@<?php echo htmlspecialchars($user['NAME']); ?> · Sep 28</span>
                            </div>

                            <div class="mt-1 text-gray-600 text-base">
                                Hmmmmm
                            </div>

                            <div class="mt-3 rounded-2xl overflow-hidden">
                                <div class="relative bg-gray-100">
                                    <div class="relative">
                                        <img src="data:image/jpeg;base64,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"
                                            alt="Motor dengan catatan harga"
                                            class="w-full" />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-between text-gray-500 text-sm border-t border-gray-100 pt-2">
                                <div class="flex items-center space-x-6">
                                    <button class="flex items-center hover:text-red-500 transition-colors group cursor-pointer">
                                        <div class="p-2 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </div>
                                        <span>14K</span>
                                    </button>

                                    <button class="flex items-center hover:text-blue-600 transition-colors group cursor-pointer">
                                        <div class="p-2 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                        </div>
                                        <span>366</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- More Options -->
                        <button class="p-2 -mt-2 rounded-full hover:bg-gray-200/50 transition-colors">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="5" cy="12" r="2" />
                                <circle cx="12" cy="12" r="2" />
                                <circle cx="19" cy="12" r="2" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>