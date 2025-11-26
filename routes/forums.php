<?php
require_once __DIR__ . '/../app/controllers/ForumController.php';
require_once __DIR__ . '/../app/controllers/TopicsController.php';

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

    case $route ===  'forum/join':
        requireLogin();
        $controller = new ForumController();
        $controller->joinForum();
        break;

    case $route ===  'forum/leave':
        requireLogin();
        $controller = new ForumController();
        $controller->leaveForum();
        break;

    case $route ===  'forum/delete':
        requireLogin();
        $controller = new ForumController();
        $controller->delete();
        break;

    case $route ===  'forum/update':
        requireLogin();
        $controller = new ForumController();
        $controller->update();
        break;

    case $route ===  'create/topics':
        requireLogin();
        $controller = new TopicsController();
        $controller->create();
        break;

    case $route ===  'topic/delete':
        requireLogin();
        $controller = new TopicsController();
        $controller->delete();
        break;

    case $route ===  'topic/pin':
        requireLogin();
        $controller = new TopicsController();
        $controller->pinTOpic();
        break;

    case preg_match('#^forum/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $forumId = $matches[1];
        requireLogin();
        $controller = new ForumController();
        $controller->forumById($forumId);
        break;
}