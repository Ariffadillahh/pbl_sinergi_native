<?php
if (!isset($trending)) $trending = [];
if (!isset($hot)) $hot = [];
if (!isset($new)) $new = [];
?>

<div class="border-b border-gray-200 mb-4">
  <h2 class="text-2xl mb-5 pt-2 text-center font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
    For You
  </h2>
  <div class="w-[50px] h-[2px] bg-blue-600 mx-auto"></div>
</div>

<div class="h-full overflow-y-auto hide-scrollbar px-5 pb-10">
  <div class="mb-16">
    <div>
      <h1 class="font-semibold font-sans">What's Trending</h1>
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
    </div>

    <div class="mt-8">
      <h1 class="font-semibold font-sans">Hot Forums 🔥</h1>
      <?php foreach ($hot as $forum): ?>
        <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 mt-2 cursor-pointer"
             onclick="window.location.href='<?= BASEURL ?>/forums/chat/<?= $forum['ID'] ?>'">
          <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
            <img src="<?= $forum['PATH_PHOTO'] ? BASEURL.'/storage/forums/photos/'.$forum['PATH_PHOTO'] : BASEURL.'/src/asset/image/default.png' ?>" class="w-full h-full object-cover">
          </div>
          <div class="flex flex-col flex-1 min-w-0 gap-[6px]">
            <div class="flex items-center gap-2">
              <p class="font-semibold truncate"><?= htmlspecialchars($forum['NAME']) ?></p>
              <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Trending</span>
            </div>
            <p class="text-sm text-gray-500"><?= $forum['MEMBER_COUNT'] ?> Members</p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-8">
      <h1 class="font-semibold font-sans">New Forums</h1>
      <?php foreach ($new as $forum): ?>
        <div class="flex items-center justify-between rounded-2xl ring-1 ring-gray-200 hover:ring-1 hover:ring-blue-600 transition-all duration-300 p-4 gap-3 mt-2 cursor-pointer"
             onclick="window.location.href='<?= BASEURL ?>/forums/chat/<?= $forum['ID'] ?>'">
          <div class="flex size-[50px] shrink-0 rounded-full overflow-hidden">
            <img src="<?= $forum['PATH_PHOTO'] ? BASEURL . '/storage/forums/photos/'. $forum['PATH_PHOTO'] : BASEURL .'/src/asset/image/default.png' ?>" class="w-full h-full object-cover">
          </div>
          <div class="flex flex-col flex-1 min-w-0 gap-[6px]">
            <div class="flex items-center gap-2">
              <p class="font-semibold truncate"><?= htmlspecialchars($forum['NAME']) ?></p>
              <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">New</span>
            </div>
            <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($forum['ABOUT']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>
