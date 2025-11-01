<?php
require_once __DIR__ . '/../app/controllers/PostController.php';
require_once __DIR__ . '/../app/controllers/CommentController.php';
require_once __DIR__ . '/../app/controllers/LikeController.php';

switch (true) {
    case $route === 'post':
        requireLogin();
        $postController = new PostController();
        $postController->index();
        break;

    case $route === 'post/delete':
        requireLogin();
        $postController = new PostController();
        $postController->delete();
        break;

    case $route === 'post/create':
        requireLogin();
        $postController = new PostController();
        $postController->create();
        break;

    case $route === 'post/update':
        requireLogin();
        $postController = new PostController();
        $postController->update();
        break;

    case $route === 'comment/add':
        requireLogin();
        header('Content-Type: application/json');
        $commentController = new CommentController();
        $commentController->addComment();
        exit;
        break;

    case $route === 'comment/reply':
        requireLogin();
        header('Content-Type: application/json');
        $commentController = new CommentController();
        $commentController->addReply();
        exit;
        break;

    case $route === 'comment/get':
        requireLogin();
        header('Content-Type: application/json');
        $commentController = new CommentController();
        $commentController->getComments($commentId);
        exit;
        break;

    case $route === 'like/toggle':
        requireLogin();
        header('Content-Type: application/json');
        $likeController = new LikeController();
        $likeController->toggleLike();
        exit;
        break;

    // case $route === 'like/get':
    //     requireLogin();
    //     header('Content-Type: application/json');
    //     $likeController = new LikeController();
    //     $likeController->getLikes();
    //     exit;
    //     break;
}
