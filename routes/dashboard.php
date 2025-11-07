 <?php
    require_once __DIR__ . '/../app/controllers/DashboardController.php';


    switch (true) {
        case $route === 'dashboard':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->index();
            break;

        case $route === 'dashboard/anggota':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->anggota();
            break;

        case $route === 'dashboard/forums':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->forums();
            break;

        case $route === 'dashboard/laporan/postingan':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->laporanPost();
            break;

        case $route === 'dashboard/laporan/forum':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->laporanForum();
            break;

        case $route === 'admin/create-accout':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->createAccountByAdmin();
            break;
            
        case $route === 'admin/update-role':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->updateRoleByAdmin();
            break;
    }
