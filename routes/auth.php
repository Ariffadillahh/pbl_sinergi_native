<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';


switch (true) {
    case $route === 'sign-in':
        guestOnly();
        $controller = new AuthController();
        $controller->signIn();
        break;

    case $route === 'sign-in/action':
        guestOnly();
        $controller = new AuthController();
        $controller->signInAction();
        break;

    case $route === 'forget-password':
        guestOnly();
        $controller = new AuthController();
        $controller->forgetPassword();
        break;

    case $route === 'forget-password/action':
        guestOnly();
        $controller = new AuthController();
        $controller->sendPasswordResetOtp();
        break;

    case $route === 'forget-password/verif-otp':
        guestOnly();
        $controller = new AuthController();
        $controller->verifyOtpForgetPassword();
        break;

    case $route === 'forget-password/resend-otp':
        guestOnly();
        $controller = new AuthController();
        $controller->resendOtpForgetPassword();
        break;

    case $route === 'logout':
        requireLogin();
        $controller = new AuthController();
        $controller->logout();
        break;

    case $route === 'sign-up':
        guestOnly();
        $controller = new AuthController();
        $controller->signUp();
        break;

    case $route === 'sign-up/action':
        guestOnly();
        $controller = new AuthController();
        $controller->register();
        break;

    case $route === 'sign-up/verif-otp':
        guestOnly();
        $controller = new AuthController();
        $controller->verifyOtp();
        break;

    case $route === 'sign-up/resend-otp':
        guestOnly();
        $controller = new AuthController();
        $controller->resendRegistrationOtp();
        break;

    case $route === 'user-setup':
        requireLogin();
        $controller = new AuthController();
        $controller->setUp();
        break;

    case $route === 'get-all-user':
        requireLogin();
        $controller = new AuthController();
        $controller->getAllUsers();
        break;
}
