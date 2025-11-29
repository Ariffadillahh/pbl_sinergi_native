<?php
require_once __DIR__ . '/../app/controllers/ForumController.php';
require_once __DIR__ . '/../app/controllers/TopicsController.php';
require_once __DIR__ . '/../app/controllers/CommentController.php';

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

    case $route ===  'req-join':
        requireLogin();
        $controller = new ForumController();
        $controller->reqJoin();

    case $route === 'forum/joinViaInvite':
        requireLogin();
        $controller = new ForumController();
        $controller->joinViaInvite();
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

    case $route === 'forum/getForumInfo':
        requireLogin();
        $controller = new ForumController();
        $controller->getForumInfo();
        break;

    case $route ===  'forum/searchAvailableUsers':
        requireLogin();
        $controller = new ForumController();
        $controller->searchAvailableUsers();
        break;

    case $route ===  'forum/addMemberByOwner':
        requireLogin();
        $controller = new ForumController();
        $controller->addMemberByOwner();
        break;

    case $route ===  'forum/removeMemberByOwner':
        requireLogin();
        $controller = new ForumController();
        $controller->removeMemberByOwner();
        break;

    case $route ===  'forum/getAssets':
        requireLogin();
        $controller = new ForumController();
        $controller->getAssetsJson();
        break;

    case $route ===  'get-req-forum':
        requireLogin();
        $controller = new ForumController();
        $controller->getReqForum();
        break;

    case $route ===  'update-req-forum':
        requireLogin();
        $controller = new ForumController();
        $controller->updateReqForum();
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

    case $route === 'comment/add-topic':
        requireLogin();
        $commentController = new CommentController();
        $commentController->addCommentTopic();
        break;

    case $route === 'comment/reply-topic':
        requireLogin();
        $commentController = new CommentController();
        $commentController->addReplyTopic();
        break;

    case preg_match('#^forum/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $forumId = $matches[1];
        requireLogin();
        $controller = new ForumController();
        $controller->forumById($forumId);
        break;

    case preg_match('#^forum/topic/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $topicId = $matches[1];
        requireLogin();
        $controller = new TopicsController();
        $controller->index($topicId);
        break;
}
