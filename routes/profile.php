<?php
    require_once __DIR__ . '/../app/controllers/ProfileController.php';

    switch (true) {
        case $route === 'profile':
            requireLogin();
            $controller = new ProfileController();
            $controller->index();
            break;
        case preg_match('#^profile/([a-zA-Z0-9\-]+)$#', $route, $matches):
            $profileId = $matches[1];
            requireLogin();
            $profileController = new ProfileController();
            $profileController->index($profileId);
            break;
    }
