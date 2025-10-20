   <?php
    require_once __DIR__ . '/../app/controllers/SettingsController.php';


    switch (true) {
        case $route === 'settings':
            requireLogin();
            $controller = new SettingsController();
            $controller->index();
            break;
    }
