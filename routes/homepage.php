<?php
require_once __DIR__ . '/../app/controllers/HomepageController.php';

switch (true) {
    case $route === 'homepage':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new HomepageController();
        $controller->home();
        break;

    case preg_match('#^homepage/reply/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $replyId = $matches[1];
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN', 'ALUMNI']);
        $controller = new HomepageController();
        $controller->replyPage($replyId);
        break;

    case preg_match('#^homepage/user/profile/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $id = $matches[1];
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new HomepageController();
        $controller->profile($id);
        break;

    case $route === 'homepage/search':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new HomepageController();
        $controller->searchPage();
        break;

    case $route === 'homepage/search/ajax':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
        $controller = new HomepageController(); 
        $controller->searchAjax();
        break;
}
