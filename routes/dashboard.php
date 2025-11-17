 <?php
    require_once __DIR__ . '/../app/controllers/DashboardController.php';
    require_once __DIR__ . '/../app/controllers/DashboardOverview.php';
    require_once __DIR__ . '/../app/controllers/ReportController.php';


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

        case $route === 'getDashboardDataApi':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardOverview();
            $controller->getDashboardDataApi();
            break;

        case $route === 'report/reasons':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new ReportController();
            $controller->getReasons();
            break;

        case $route === 'report/delete':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new ReportController();
            $controller->deleteForumByAdmin();
            break;

        case $route === 'report/delete/post':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new ReportController();
            $controller->deletePostByAdmin();
            break;

        case $route === 'report/warning':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new ReportController();
            $controller->sendWarningNotification();
            break;
            
        case $route === 'admin/update-role':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->updateRoleByAdmin();
            break;

        case $route === 'join-forum-admin':
            requireLogin();
            checkRoleAccess(['ADMIN']);
            $controller = new DashboardController();
            $controller->joinByAdmin();
            break;
    }
