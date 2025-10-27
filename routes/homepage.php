   <?php
    require_once __DIR__ . '/../app/controllers/HomepageController.php';

    switch (true) {
        case $route === 'homepage':
            requireLogin();
            checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
            $controller = new HomePageController();
            $controller->index();
            break;

        case preg_match('#^homepage/reply/([a-zA-Z0-9\-]+)$#', $route, $matches):
            $reply = $matches[1];
            requireLogin();
            checkRoleAccess(['MAHASISWA', 'DOSEN', 'ADMIN']);
            $controller = new HomePageController();
            $controller->replyPage($reply);
            break;
    }
