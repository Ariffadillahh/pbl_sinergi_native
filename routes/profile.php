 <?php
    require_once __DIR__ . '/../app/controllers/ProfileController.php';

    switch (true) {
        case $route === 'profile':
            requireLogin();
            $controller = new ProfileController();
            $controller->index();
            break;
    }
