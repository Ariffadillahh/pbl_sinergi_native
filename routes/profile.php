<?php
require_once __DIR__ . '/../app/controllers/ProfileController.php';

switch (true) {
    case $route === 'profile':
        requireLogin();
        $controller = new ProfileController();
        $controller->index();
        break;

    case $route === 'profile/update':
        requireLogin();
        $controller = new ProfileController();
        $controller->updateProfile();
        break;

    case $route === 'profile/updatePassword':
        requireLogin();
        $controller = new ProfileController();
        $controller->updatePassword();
        break;

    case $route === 'account/confirm-student-status':
        requireLogin();
        $controller = new ProfileController();
        $controller->updateRoleMahasiswa();
        break;

    case $route === 'account/confirm-student-status/resend':
        requireLogin();
        $controller = new ProfileController();
        $controller->resendStudentOtp();
        break;

    case $route === 'account/confirm-student-status/otp':
        requireLogin();
        $controller = new ProfileController();
        $controller->verifyStudentOtp();
        break;
}
