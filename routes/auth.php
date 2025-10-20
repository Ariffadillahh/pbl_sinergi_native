 <?php
    require_once __DIR__ . '/../app/controllers/AuthController.php';


    switch (true) {
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
    }
