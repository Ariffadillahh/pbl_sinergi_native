<?php
session_start();
require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/controllers/HomepageController.php';

$route = $_GET['route'] ?? '';

if (strlen($route) > 1) {
    $route = rtrim($route, '/');
}

switch (true) {
    case $route === '':
        $controller = new landingPageController();
        $controller->index();
        break;

    case $route === 'koneksi':
        $controller = new landingPageController();
        $controller->con();
        break;

    default:
        include __DIR__ . '/forums.php';
        include __DIR__ . '/auth.php';
        include __DIR__ . '/profile.php';
        include __DIR__ . '/dashboard.php';
        include __DIR__ . '/homepage.php';
        include __DIR__ . '/settings.php';
        include __DIR__ . '/post.php';
        include __DIR__ . '/profile.php';

        if (!isset($controller)) {
            http_response_code(404);
            $controller = new NotFoundPageController();
            $controller->index();
        }
        break;
}
