<?php
require_once __DIR__ . '/../app/controllers/ProfileController.php';

switch (true) {
    case $route === 'profile':
        requireLogin();
        $controller = new ProfileController();
        $controller->index();
        $routeFound = true;
        break;

    case $route === 'profile/update':
        requireLogin();
        $controller = new ProfileController();
        $controller->updateProfile();
        $routeFound = true;
        break;

    case $route === 'profile/updatePassword':
        requireLogin();
        $controller = new ProfileController();
        $controller->updatePassword();
        $routeFound = true;
        break;
}
