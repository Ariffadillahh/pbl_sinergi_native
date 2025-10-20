 <?php
    require_once __DIR__ . '/../app/controllers/DashboardController.php';


    switch (true) {
        case $route === 'dashboard':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->index();
            break;
    }
