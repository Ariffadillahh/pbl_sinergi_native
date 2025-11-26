<?php
require_once __DIR__ . '/../app/controllers/GroupChatController.php';
require_once __DIR__ . '/../app/controllers/ChatMessagesController.php';

switch (true) {

    case ($route === 'groups/checkMembership'):
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new GroupChatController();
        $controller->checkMembership();
        break;

    case $route === 'groups':
        requireLogin();
        $controller = new GroupChatController();
        $controller->index();
        break;

    case preg_match('#^groups/chat/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $chatId = $matches[1];
        requireLogin();
        $controller = new GroupChatController();
        $controller->chat($chatId);
        break;

    case preg_match('#^groups/getInitialMessages/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $chatId = $matches[1];
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->getInitialMessages($chatId);
        break;

    case preg_match('#^groups/getAllMedia/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $groupChatId = $matches[1];
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->getAllMedia($groupChatId);
        break;

    case $route === 'groups/create':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new GroupChatController();
        $controller->create();
        break;

    case $route === 'groups/edit':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new GroupChatController();
        $controller->edit();
        break;

    case $route === 'groups/delete':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new GroupChatController();
        $controller->delete();
        break;

    case $route === 'groups/exit':
        requireLogin();
        $controller = new GroupChatController();
        $controller->exit();
        break;

    case $route === 'groups/search':
        requireLogin();
        $controller = new GroupChatController();
        $controller->search();
        break;

    case $route === 'groups/searchUser':
        requireLogin();
        $controller = new GroupChatController();
        $controller->searchUser();
        break;

    case $route === 'groups/join':
        requireLogin();
        $controller = new GroupChatController();
        $controller->join();
        break;

    case $route === 'groups/joinViaInvite':
        requireLogin();
        $controller = new GroupChatController();
        $controller->joinViaInvite();
        break;

    case $route === 'groups/kickMember':
        requireLogin();
        $controller = new GroupChatController();
        $controller->kickMember();
        break;

    case $route === 'groups/getGroupChatInfo':
        requireLogin();
        $controller = new GroupChatController();
        $controller->getGroupChatInfo();
        break;

    case $route === 'groups/addMember':
        requireLogin();
        $controller = new GroupChatController();
        $controller->addMember();
        break;

    case $route === 'groups/send-message':
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->sendMessage();
        break;

    case $route === 'groups/get-new-messages':
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->getNewMessages();
        break;

    case $route === 'report':
        requireLogin();
        $controller = new GroupChatController();
        $controller->reportGroupChatOrPost();
        break;

    case $route === 'groups/pollCounts':
        requireLogin();
        $controller = new ChatMessagesController();
        $controller->pollCounts();
        break;
}
