<?php
require_once __DIR__ . '/../app/controllers/ForumsController.php';
require_once __DIR__ . '/../app/controllers/ChatMessagesController.php';

switch (true) {

        case ($route === 'forums/checkMembership'):
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new ForumsController();
        $controller->checkMembership();
        break;

    case $route === 'forums':
        requireLogin();
        $controller = new ForumsController();
        $controller->index();
        break;

    case preg_match('#^forums/chat/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $chatId = $matches[1];
        requireLogin();
        $controller = new ForumsController();
        $controller->chat($chatId);
        break;

    case preg_match('#^forums/getInitialMessages/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $chatId = $matches[1];
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->getInitialMessages($chatId);
        break;

    case preg_match('#^forums/getMediaPreview/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $forumId = $matches[1];
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->getMediaPreview($forumId, 8); // Get 8 most recent media
        break;

    case preg_match('#^forums/getAllMedia/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $forumId = $matches[1];
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->getAllMedia($forumId);
        break;

    case $route === 'forums/create':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new ForumsController();
        $controller->create();
        break;

    case $route === 'forums/edit':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new ForumsController();
        $controller->edit();
        break;

    case $route === 'forums/delete':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new ForumsController();
        $controller->delete();
        break;

    case $route === 'forums/exit':
        requireLogin();
        $controller = new ForumsController();
        $controller->exit();
        break;

    case $route === 'forums/search':
        requireLogin();
        $controller = new ForumsController();
        $controller->search();
        break;

    case $route === 'forums/searchUser':
        requireLogin();
        $controller = new ForumsController();
        $controller->searchUser();
        break;

    case $route === 'forums/join':
        requireLogin();
        $controller = new ForumsController();
        $controller->join();
        break;

    case $route === 'forums/joinViaInvite':
        requireLogin();
        $controller = new ForumsController();
        $controller->joinViaInvite();
        break;

    case $route === 'forums/kickMember':
        requireLogin();
        $controller = new ForumsController();
        $controller->kickMember();
        break;

    case $route === 'forums/getForumInfo':
        requireLogin();
        $controller = new ForumsController();
        $controller->getForumInfo();
        break;

    case $route === 'forums/addMember':
        requireLogin();
        $controller = new ForumsController();
        $controller->addMember();
        break;

    case $route === 'forums/send-message':
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->sendMessage();
        break;

    case $route === 'forums/get-new-messages':
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->getNewMessages();
        break;

    case $route === 'report':
        requireLogin();
        $controller = new ForumsController();
        $controller->reportForumOrPost();
        break;

    case $route === 'forums/pollCounts':
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->pollCounts();
        break;

}
