<?php
session_start();
require_once __DIR__ . '/../app/controllers/HomepageController.php';
require_once __DIR__ . '/../app/controllers/ForumsController.php';
require_once __DIR__ . '/../app/controllers/ProfileController.php';
require_once __DIR__ . '/../app/controllers/SettingsController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/helpers/auth.php';

$route = $_GET['route'] ?? '';

if (strlen($route) > 1) {
    $route = rtrim($route, '/');
}

switch (true) {
    case $route === '':
        $controller = new landingPageController();
        $controller->index();
        break;

    case $route === 'koneksi':
        $controller = new landingPageController();
        $controller->con();
        break;

    case $route === 'homepage':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN']);
        $controller = new HomePageController();
        $controller->index();
        break;

    case preg_match('#^homepage/reply/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $repaly = $matches[1];
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN']);
        $controller = new HomePageController();
        $controller->replayPage($repaly);
        break;

    case $route === 'sign-in':
        guestOnly();
        $controller = new SigninController();
        $controller->index();
        break;

    case $route === 'sign-in/action':
        guestOnly();
        $controller = new SigninController();
        $controller->signInAction();
        break;

    case $route === 'profile/setup':
        $controller = new SigninController();
        $controller->setUp();
        break;

    case $route === 'forget-password/action':
        guestOnly();
        $controller = new forgetPassword();
        $controller->forgetPassword();
        break;

    case $route === 'forget-password':
        guestOnly();
        $controller = new forgetPassword();
        $controller->index();
        break;

    case $route === 'forget-password/verif-otp':
        guestOnly();
        $controller = new forgetPassword();
        $controller->verifyOtpForgetPassword();
        break;

    case $route === 'forget-password/resend-otp':
        guestOnly();
        $controller = new forgetPassword();
        $controller->resendOtpForgetPassword();
        break;

    case $route === 'logout':
        requireLogin();
        $controller = new SigninController();
        $controller->logout();
        break;

    case $route === 'sign-up':
        guestOnly();
        $controller = new SignupController();
        $controller->index();
        break;

    case $route === 'sign-up/action':
        guestOnly();
        $controller = new SignupController();
        $controller->register();
        break;

    case $route === 'sign-up/verif-otp':
        guestOnly();
        $controller = new SignupController();
        $controller->verifyOtp();
        break;

    case $route === 'sign-up/resend-otp':
        guestOnly();
        $controller = new SignupController();
        $controller->resendRegistrationOtp();
        break;

    case $route === 'forums':
        requireLogin();
        $controller = new ForumsController();
        $controller->index();
        break;

    case preg_match('#^forums/chat/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $chatId = $matches[1];
        requireLogin();
        $controller = new ForumsController();
        $controller->chat($chatId);
        break;

    case preg_match('#^forums/getInitialMessages/([a-zA-Z0-9\-]+)$#', $route, $matches):
        $chatId = $matches[1];
        requireLogin();
        $controller = new ChatMessages();
        $controller->getInitialMessages($chatId);
        break;


    case $route === 'forums/create':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN']);
        $controller = new ForumsController();
        $controller->create();
        break;

    case $route === 'forums/edit':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN']);
        $controller = new ForumsController();
        $controller->edit();
        break;

    case $route === 'forums/delete':
        requireLogin();
        checkRoleAccess(['MAHASISWA', 'DOSEN']);
        $controller = new ForumsController();
        $controller->delete();
        break;

    case $route === 'forums/exit':
        requireLogin();
        $controller = new ForumsController();
        $controller->exit();
        break;
    case $route === 'forums/search':
        requireLogin();
        $controller = new ForumsController();
        $controller->search();
        break;

    case $route === 'forums/join':
        requireLogin();
        $controller = new ForumsController();
        $controller->join();
        break;

    case $route === 'forums/send-message':
        requireLogin();
        $controller = new ChatMessages();
        $controller->sendMessage();
        break;

    case $route === 'forums/get-new-messages':
        requireLogin();
        $controller = new ChatMessages();
        $controller->getNewMessages();
        break;

    case $route === 'forums/report':
        requireLogin();
        $controller = new ForumsController();
        $controller->reportForum();
        break;

    case $route === 'settings':
        requireLogin();
        $controller = new SettingsController();
        $controller->index();
        break;

    case $route === 'profile':
        requireLogin();
        $controller = new ProfileController();
        $controller->index();
        break;

    default:
        http_response_code(404);
        $controller = new NotFoundPageController();
        $controller->index();
        break;
}
