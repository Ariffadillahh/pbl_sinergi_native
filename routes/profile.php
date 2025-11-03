<?php
require_once __DIR__ . '/../app/controllers/ProfileController.php';

switch (true) {
    case $route === 'profile':
        requireLogin();
        $controller = new ProfileController();
        $controller->index();
        break;

    case $route === 'profile/update':
        requireLogin();
        $controller = new ProfileController();
        $controller->updateProfile();
        break;

    case $route === 'profile/updatePassword':
        requireLogin();
        $controller = new ProfileController();
        $controller->updatePassword();
        break;
}
