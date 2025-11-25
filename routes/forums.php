<?php
require_once __DIR__ . '/../app/controllers/ForumController.php';

switch (true) {
    case $route === 'forums':
        requireLogin();
        $controller = new ForumController();
        $controller->index();
        break;

    case $route === 'forum/createForum':
        requireLogin();
        $controller = new ForumController();
        $controller->createForum();
        break;

    case preg_match('#^forum/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $forumId = $matches[1];
        requireLogin();
        $controller = new ForumController();
        $controller->forumById($forumId);
        break;
}
