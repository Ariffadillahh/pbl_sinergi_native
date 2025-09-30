<?php
require_once __DIR__ . '/../app/controllers/HomepageController.php';
require_once __DIR__ . '/../app/controllers/ForumsController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$route = $_GET['route'] ?? '';

if (strlen($route) > 1) {
    $route = rtrim($route, '/');
}

switch (true) {
    case $route === '':
        $controller = new landingPageController();
        $controller->index();
        break;

    case $route === 'homepage':
        $controller = new HomePageController();
        $controller->index();
        break;
        
    case preg_match('#^homepage/repaly/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $repaly = $matches[1];
        $controller = new HomePageController();
        $controller->replayPage($repaly);
        break;

    case $route === 'signin':
        $controller = new SigninController();
        $controller->index();
        break;

    case $route === 'signup':
        $controller = new SignupController();
        $controller->index();
        break;

    case $route === 'forums':
        $controller = new ForumsController();
        $controller->index();
        break;

    case preg_match('#^forums/chat/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $chatId = $matches[1];
        $controller = new ForumsController();
        $controller->chat($chatId);
        break;

    case $route === 'forums/create':
        $controller = new ForumsController();
        $controller->create();
        break;

    default:
        http_response_code(404);
        $controller = new NotFoundPageController();
        $controller->index();
        break;
}
