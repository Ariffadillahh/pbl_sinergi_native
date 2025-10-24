 <?php
    require_once __DIR__ . '/../app/controllers/ProfileController.php';
    require_once __DIR__ . '/../app/controllers/ProfileController.php';
    $controller = new ProfileController();

    if ($_SERVER['REQUEST_URI'] === '/sinergi/profile') {
        $controller->index();
        exit;
    }

    if (strpos($_SERVER['REQUEST_URI'], '/sinergi/profile?id=') !== false) {
        $id = $_GET['id'] ?? null;
        $controller->index($id);
        exit;
    }

    switch (true) {
        case $route === 'profile':
            requireLogin();
            $controller = new ProfileController();
            $controller->index();
            break;
    }
