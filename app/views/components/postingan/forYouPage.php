<?php
if (!isset($trending)) $trending = [];
if (!isset($forums)) $forums = [];
if (!isset($groups)) $groups = [];
?>

<div class="border-b border-gray-200 mb-4">
  <h2 class="text-2xl mb-5 pt-2 text-center font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
    For You
  </h2>
  <div class="w-[50px] h-[2px] bg-blue-600 mx-auto"></div>
</div>

<div class="h-full overflow-y-auto hide-scrollbar px-5 pb-10">
  <div class="mb-16">
    <!-- What's Trending -->
    <div>
      <h1 class="font-semibold font-sans">What's Trending</h1>
      <?php if (!empty($trending)): ?>
        <?php foreach ($trending as $post): ?>
          <div class="flex items-start gap-3 my-3 border border-gray-200 rounded-2xl p-3 hover:bg-gray-50 cursor-pointer"
               onclick="window.location.href='<?= BASEURL ?>/homepage/reply/<?= $post['POST_ID'] ?>'">
            <img src="<?= $post['PATH_PHOTO'] ? BASEURL.'/storage/users/photos/'.$post['PATH_PHOTO'] : BASEURL.'/src/asset/image/default.png' ?>"
                 class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
              <p class="text-gray-600">@<?= htmlspecialchars($post['USERNAME']) ?></p>
              <p class="text-black font-bold truncate mt-3">
                <?= htmlspecialchars(mb_substr($post['CONTENT'], 0, 60)) ?>...
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-sm text-gray-500 text-center py-4">No trending posts yet</p>
      <?php endif; ?>
    </div>

    <!-- Your Forums -->
    <div class="mt-8">
      <h1 class="font-semibold font-sans">Your Forums</h1>
      <?php if (!empty($forums)): ?>
        <?php foreach ($forums as $forum): ?>
          <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 mt-2 cursor-pointer" 
               onclick="window.location.href='<?= BASEURL ?>/forum/<?= $forum['ID'] ?>'">
            <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden bg-gray-200">
              <?php 
                // Use PATH_PHOTO with proper fallback
                if (!empty($forum['PATH_PHOTO'])) {
                  $forumPhoto = BASEURL . '/storage/forums/photos/' . $forum['PATH_PHOTO'];
                  echo '<img src="' . $forumPhoto . '" class="w-full h-full object-cover">';
                } else {
                  // Fallback with forum name initials
                  $initials = strtoupper(substr($forum['NAME'], 0, 2));
                  echo '<span class="text-white font-bold text-lg bg-blue-500 w-full h-full flex items-center justify-center">' . $initials . '</span>';
                }
              ?>
            </div>
            <div class="flex flex-col flex-1 min-w-0 gap-[6px]">
              <div class="flex items-center gap-2">
                <p class="font-semibold truncate"><?= htmlspecialchars($forum['NAME']) ?></p>
                <?php if (!empty($forum['LAST_ACTIVITY'])): ?>
                  <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Active</span>
                <?php endif; ?>
                <?php if ($forum['IS_PRIVATE'] == 1): ?>
                  <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                <?php endif; ?>
              </div>
              <p class="text-sm text-gray-500"><?= $forum['MEMBER_COUNT'] ?? 0 ?> Members</p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-sm text-gray-500 text-center py-4">You haven't joined any forums yet</p>
      <?php endif; ?>
    </div>

    <!-- Your Groups -->
    <div class="mt-8">
      <h1 class="font-semibold font-sans">Your Groups</h1>
      <?php if (!empty($groups)): ?>
        <?php foreach ($groups as $group): ?>
          <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 mt-2 cursor-pointer" 
               onclick="window.location.href='<?= BASEURL ?>/groups/chat/<?= $group['ID'] ?>'">
            <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden bg-gray-200">
              <?php if (!empty($group['PATH_PHOTO'])): ?>
                <img src="<?= BASEURL . '/storage/groups/photos/' . $group['PATH_PHOTO'] ?>" class="w-full h-full object-cover">
              <?php else: ?>
                <span class="text-white font-bold text-lg bg-pink-500 w-full h-full flex items-center justify-center">
                  <?= strtoupper(substr($group['NAME'], 0, 2)) ?>
                </span>
              <?php endif; ?>
            </div>
            <div class="flex flex-col flex-1 min-w-0 gap-[6px]">
              <div class="flex items-center gap-2">
                <p class="font-semibold truncate"><?= htmlspecialchars($group['NAME']) ?></p>
                <?php if (!empty($group['LAST_MESSAGE_TIME'])): ?>
                  <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Active</span>
                <?php endif; ?>
              </div>
              <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($group['ABOUT'] ?? 'No description') ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-sm text-gray-500 text-center py-4">You haven't joined any groups yet</p>
      <?php endif; ?>
    </div>

  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  window.BASEURL = '<?= BASEURL ?>';

  // Handle forum click
  window.handleForumClick = async function(forumId, forumName) {
    try {
      const res = await fetch(`${BASEURL}/forum/checkMembership?forum_id=${forumId}`, {
        credentials: 'same-origin'
      });
      const data = await res.json();

      if (data.is_member) {
        // User is member, go to forum
        window.location.href = `${BASEURL}/forum/${forumId}`;
      } else {
        // User is not member, go to forums list with search
        window.location.href = `${BASEURL}/forums?search=${encodeURIComponent(forumName)}`;
      }
    } catch (err) {
      console.error('Failed to check forum membership:', err);
      // Fallback: go to forum directly
      window.location.href = `${BASEURL}/forum/${forumId}`;
    }
  };

  // Handle group click
  window.handleGroupClick = async function(groupId, groupName) {
    try {
      const res = await fetch(`${BASEURL}/groups/checkMembership?group_id=${groupId}`, {
        credentials: 'same-origin'
      });
      const data = await res.json();

      if (data.is_member) {
        // User is member, go to group chat
        window.location.href = `${BASEURL}/groups/chat/${groupId}`;
      } else {
        // User is not member, go to groups list
        window.location.href = `${BASEURL}/groups?search=${encodeURIComponent(groupName)}`;
      }
    } catch (err) {
      console.error('Failed to check group membership:', err);
      // Fallback: go to group directly
      window.location.href = `${BASEURL}/groups/chat/${groupId}`;
    }
  };
});
</script>