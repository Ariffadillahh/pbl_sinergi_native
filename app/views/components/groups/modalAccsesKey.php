<div id="accessKeyModal" class="hidden fixed inset-0 z-[99999] backdrop-blur justify-center items-center w-full h-full bg-black/60">
    <div class="bg-white rounded-2xl p-6 w-full max-w-sm">
        <div class="bg-red-600 p-2 text-center hidden text-white rounded-lg mb-3" id="keyWrongNotif"></div>
        <h2 class="text-xl font-bold mb-4">Private Group Chat</h2>
        <p class="text-gray-600 mb-4">This group chat requires an access key to join. Please enter it below.</p>

        <form id="accessKeyForm">
            <input type="hidden" id="forumIdToJoin" name="groupChat_id">

            <div>
                <label for="access_key" class="block text-sm font-medium text-gray-700">Access Key</label>
                <input type="password" id="access_key" name="access_key" autocomplete="off"
                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="closeKeyModalBtn" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 cursor-pointer">
                    Join
                </button>
            </div>
        </form>
    </div>
</div>
