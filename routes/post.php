<?php
require_once __DIR__ . '/../app/controllers/PostController.php';

$postController = new PostController();

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

// Route untuk delete post
if ($_SERVER['REQUEST_URI'] === '/sinergi/post/delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController->delete();
    exit;
}

// Route untuk update post (jika mau)
if ($_SERVER['REQUEST_URI'] === '/sinergi/post/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $postController->update();
    exit;
}

?>
