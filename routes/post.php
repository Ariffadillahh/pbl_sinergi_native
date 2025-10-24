<?php
require_once __DIR__ . '/../app/controllers/PostController.php';
// require_once __DIR__ . '/../app/controllers/LikeController.php';
require_once __DIR__ . '/../app/controllers/CommentController.php';

$postController = new PostController();
// $likeController = new LikeController();
$commentController = new CommentController();

// ==================== POST ==================== //

// Route untuk menampilkan semua post (feed)
if ($_SERVER['REQUEST_URI'] === '/sinergi/post' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $postController->index();
    exit;
}

// Route untuk membuat post baru
if ($_SERVER['REQUEST_URI'] === '/sinergi/post/create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController->create();
    exit;
}

// Route untuk menghapus post
if ($_SERVER['REQUEST_URI'] === '/sinergi/post/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController->delete();
    exit;
}

// Route untuk update post
if ($_SERVER['REQUEST_URI'] === '/sinergi/post/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController->update();
    exit;
}


// ==================== LIKE ==================== //

// Toggle like/unlike post
// if ($_SERVER['REQUEST_URI'] === '/sinergi/like/toggle' && $_SERVER['REQUEST_METHOD'] === 'POST') {
//     $likeController->toggleLike();
//     exit;
// }


// ==================== COMMENT ==================== //

// Tambah komentar
if ($_SERVER['REQUEST_URI'] === '/sinergi/comment/add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentController->addComment();
    exit;
}

// Tambah balasan (reply)
if ($_SERVER['REQUEST_URI'] === '/sinergi/comment/reply' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentController->addReply();
    exit;
}

// Ambil semua komentar per post (AJAX misalnya)
if ($_SERVER['REQUEST_URI'] === '/sinergi/comment/get' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $commentController->getComments($commentId);
    exit;
}
?>
