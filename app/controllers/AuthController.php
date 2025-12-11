<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../models/Auth/SignUp.php';
require_once __DIR__ . '/../models/Auth/SignIn.php';
require_once __DIR__ . '/../models/Groups/GroupChat.php';

class AuthController
{
    private $userModel;
    private $loginModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->loginModel  = new SignInModel();
    }

    public function signIn()
    {
        $this->generateAndStoreCaptcha();
        include __DIR__ . '/../views/sign-in/index.php';
    }

    public function refreshCaptcha()
    {
        $this->generateAndStoreCaptcha();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
    }

    private function generateAndStoreCaptcha()
    {
        $length = 6;
        $charset = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghizklmnopqrstuvwxyz123456789_#!";
        $captchaText = '';
        for ($i = 0, $n = strlen($charset); $i < $length; ++$i) {
            $captchaText .= $charset[random_int(0, $n - 1)];
        }
        $_SESSION['captcha'] = $captchaText;
    }

    public function signUp()
    {
        include __DIR__ . '/../views/sign-up/index.php';
    }

    public function forgetPassword()
    {
        include __DIR__ . '/../views/forget-password/index.php';
    }

    public function setUp()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        if (empty($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Session is invalid. Please log in again.']);
            exit;
        }

        $required_fields = ['jenjang_studi', 'tahunMasuk', 'prodi'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['status' => 'error', 'message' => "The '{$field}' field cannot be empty."]);
                exit;
            }
        }

        $dataToUpdate = [
            'PRODI'         => $_POST['prodi'],
            'JENJANG_STUDI' => $_POST['jenjang_studi'],
            'TAHUN_MASUK'   => $_POST['tahunMasuk']
        ];

        $userId = $_SESSION['user_id'];
        $email  = $_SESSION['email'];

        try {
            $isUpdated = $this->userModel->updateProfile($userId, $dataToUpdate);

            if ($isUpdated) {
                $updatedUser = $this->loginModel->getUserByUsernameOrEmail($email);

                if ($updatedUser) {
                    $this->createSession($updatedUser);
                    echo json_encode([
                        'status'  => 'success',
                        'message' => 'Profile successfully updated and session refreshed!'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'User data not found after update.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Failed to save data to the database.'
                ]);
            }
        } catch (\Exception $e) {
            error_log("Error at controller setup: " . $e->getMessage());
            echo json_encode([
                'status'  => 'error',
                'message' => 'An error occurred on the server.'
            ]);
        }
        exit;
    }

    public function signInAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $identifier = $_POST['username_or_email'] ?? '';
            $password = $_POST['password'] ?? '';
            $captchaInput = $_POST['captcha'] ?? '';

            // Validasi field kosong
            if (empty($identifier) || empty($password) || empty($captchaInput)) {
                $_SESSION['login_error'] = "All field must be filled!";
                $_SESSION['login_error_type'] = 'all'; // Reset semua field
                header('Location: ' . BASEURL . '/sign-in');
                exit();
            }

            $sessionCaptcha = $_SESSION['captcha'] ?? '';

            // Validasi Captcha
            if (empty($sessionCaptcha) || $captchaInput !== $sessionCaptcha) {
                $_SESSION['login_error'] = "Captcha you entered is incorrect!";
                $_SESSION['login_error_type'] = 'captcha'; // Hanya captcha yang salah
                $_SESSION['login_username'] = $identifier; // Simpan username untuk diisi ulang

                unset($_SESSION['captcha']);
                header('Location: ' . BASEURL . '/sign-in');
                exit();
            }

            unset($_SESSION['captcha']);

            // Cek apakah user ada
            $user = $this->loginModel->getUserByUsernameOrEmail($identifier);

            // Jika user tidak ditemukan (username/email salah)
            if (!$user) {
                $_SESSION['login_error'] = "Incorrect username, email, or password!";
                $_SESSION['login_error_type'] = 'username'; // Username salah - reset semua
                header('Location: ' . BASEURL . '/sign-in');
                exit();
            }

            // Jika user belum approved
            if ($user['STATUS'] !== 'APPROVED') {
                $_SESSION['login_error'] = "Your account is not approved yet!";
                $_SESSION['login_error_type'] = 'username'; // Reset semua
                header('Location: ' . BASEURL . '/sign-in');
                exit();
            }

            // Validasi password
            if (!password_verify($password, $user['PASSWORD'])) {
                $_SESSION['login_error'] = "Incorrect password!";
                $_SESSION['login_error_type'] = 'password'; // Hanya password yang salah
                $_SESSION['login_username'] = $identifier; // Simpan username untuk diisi ulang
                header('Location: ' . BASEURL . '/sign-in');
                exit();
            }

            // Login berhasil - proses role checking
            if ($user['ROLE'] == 'MAHASISWA') {
                $tahunMasuk = (int)$user['TAHUN_MASUK'];
                $jenjangStudi = $user['JENJANG_STUDI'];
                $userId = $user['ID'];

                if ($tahunMasuk > 0 && !empty($jenjangStudi)) {
                    $durasiStudi = 0;
                    if ($jenjangStudi == 'D4') {
                        $durasiStudi = 4;
                    } elseif ($jenjangStudi == 'D3') {
                        $durasiStudi = 3;
                    }

                    if ($durasiStudi > 0) {
                        $tahunLulus = $tahunMasuk + $durasiStudi;
                        $bulanLulus = 10;
                        $tahunSekarang = (int)date('Y');
                        $bulanSekarang = (int)date('m');

                        if ($tahunSekarang > $tahunLulus || ($tahunSekarang == $tahunLulus && $bulanSekarang >= $bulanLulus)) {
                            $this->loginModel->updateUserRole($userId, 'ALUMNI');
                            $user['ROLE'] = 'ALUMNI';
                            $this->createSession($user);
                            header('Location: ' . BASEURL . '/forums');
                            exit();
                        }
                    }
                }
            }

            // Bersihkan session error jika ada
            unset($_SESSION['login_error']);
            unset($_SESSION['login_error_type']);
            unset($_SESSION['login_username']);

            $this->createSession($user);
            $role = $user['ROLE'];

            // Redirect berdasarkan role
            if ($role == 'MAHASISWA' || $role == 'DOSEN') {
                header('Location: ' . BASEURL . '/homepage');
                exit();
            } elseif ($role == 'ALUMNI' || $role == 'MITRA') {
                header('Location: ' . BASEURL . '/forums');
                exit();
            } else {
                header('Location: ' . BASEURL . '/dashboard');
                exit();
            }
        } else {
            header('Location: ' . BASEURL . '/sign-in');
            exit();
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASEURL . '/sign-in');
        exit();
    }

    private function createSession($user)
    {
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['username'] = $user['USERNAME'];
        $_SESSION['personal_number'] = $user['PERSONAL_NUMBER'];
        $_SESSION['full_name'] = $user['FULL_NAME'];
        $_SESSION['email'] = $user['EMAIL'];
        $_SESSION['role'] = $user['ROLE'];
        $_SESSION['prodi'] = $user['PRODI'];
        $_SESSION['jenjang_studi'] = $user['JENJANG_STUDI'];
        $_SESSION['tahun_masuk'] = $user['TAHUN_MASUK'];
        $_SESSION['path_photo'] = $user['PATH_PHOTO'];
        $_SESSION['logged_in'] = true;
    }

    public function getAllUsers()
    {
        header('Content-Type: application/json');
        $userModel = new User();
        $users = $userModel->getRegularUsers();
        echo json_encode(['success' => true, 'data' => $users]);
        exit;
    }

    public function register()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $required_fields = ['FullName', 'username', 'personal_number', 'email', 'password'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "The '{$field}' field cannot be empty."]);
                exit;
            }
        }

        if (strlen($_POST['password']) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password must be more than 6 characters']);
            exit;
        }

        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
            exit;
        }

        $role = null;

        if (preg_match('/\.tik\d+@stu\.pnj\.ac\.id$/', $email)) {
            $role = 'MAHASISWA';
        } elseif (str_ends_with($email, '@tik.pnj.ac.id')) {
            $role = 'DOSEN';
        } elseif (str_ends_with($email, '@lecturer.pnj.ac.id')) {
            $role = 'DOSEN';
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'The email domain is invalid. Please use a valid PNJ email address.'
            ]);
            exit;
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $personalNumber = $_POST['personal_number'];
        $user = $this->loginModel->getUserByUsernameOrEmail($username);

        if ($user) {
            echo json_encode(['success' => false, 'message' => 'Username already exists.']);
            exit;
        }
        $user = $this->loginModel->getUserByUsernameOrEmail($email);
        if ($user) {
            echo json_encode(['success' => false, 'message' => 'Email already exists.']);
            exit;
        }
        $user = $this->loginModel->getUserByUsernameOrEmail($personalNumber);
        if ($user) {
            echo json_encode(['success' => false, 'message' => 'NIM/NIP already exists.']);
            exit;
        }

        $tempPhotoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $tempUploadDir = __DIR__ . '/../../storage/users/temp/';
            if (!is_dir($tempUploadDir)) {
                mkdir($tempUploadDir, 0777, true);
            }
            $tempFileName = uniqid('temp_', true) . '-' . basename($_FILES['photo']['name']);
            $tempPhotoPath = $tempUploadDir . $tempFileName;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $tempPhotoPath)) {
                echo json_encode(['success' => false, 'message' => 'Gagal memproses file upload.']);
                exit;
            }
        }

        $otp = rand(1000, 9999);
        $registrationData = [
            'ID'              => uniqid(),
            'USERNAME'        => htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'),
            'PERSONAL_NUMBER' => htmlspecialchars($_POST['personal_number'], ENT_QUOTES, 'UTF-8'),
            'FULL_NAME'       => htmlspecialchars($_POST['FullName'], ENT_QUOTES, 'UTF-8'),
            'TAHUN_MASUK'     => null,
            'EMAIL'           => $email,
            'PASSWORD'        => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'PATH_PHOTO'      => null,
            'JENJANG_STUDI'   => null,
            'ROLE'            => $role,
            'PRODI'           => null,
            'otp'             => $otp,
            'otp_expiry'      => time() + 300,
            'temp_photo_path' => $tempPhotoPath
        ];
        $_SESSION['registration_data'] = $registrationData;

        try {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $mailConfig = require __DIR__ . '/../../config/mail.php';
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $mailConfig['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailConfig['username'];
            $mail->Password   = $mailConfig['password'];
            $mail->SMTPSecure = $mailConfig['encryption'];
            $mail->Port       = $mailConfig['port'];
            $mail->setFrom($mailConfig['from_address'], $mailConfig['from_name']);
            $mail->addAddress($email, $registrationData['FULL_NAME']);
            $mail->isHTML(true);
            $mail->Subject = 'SINERGI Account Registration Verification Code';
            $mail->Body    = "Hello <b>{$registrationData['FULL_NAME']}</b>,<br><br>Thank you for registering. Use the OTP code below to complete your registration process. This code is only valid for 5 minutes.<br><br>Your OTP code is: <h2><b>{$otp}</b></h2>";
            $mail->AltBody = "Your OTP code is: {$otp}";
            $mail->send();
            echo json_encode([
                'success' => true,
                'message' => 'The OTP has been successfully sent. Please check your email.',
                'role'    => $role,
                'OTP'     => $otp
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send verification email. Please try again.'
            ]);
        }
        exit;
    }

    public function resendRegistrationOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        header('Content-Type: application/json');

        if (!isset($_SESSION['registration_data'])) {
            echo json_encode(['success' => false, 'message' => 'Your registration session has ended. Please start over.']);
            exit;
        }

        $sessionData = $_SESSION['registration_data'];
        $newOtp = rand(1000, 9999);
        $newExpiry = time() + (5 * 60);
        $_SESSION['registration_data']['otp'] = $newOtp;
        $_SESSION['registration_data']['otp_expiry'] = $newExpiry;
        $userEmail = $sessionData['EMAIL'];
        $userName = $sessionData['FULL_NAME'];

        try {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $mailConfig = require __DIR__ . '/../../config/mail.php';
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $mailConfig['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailConfig['username'];
            $mail->Password   = $mailConfig['password'];
            $mail->SMTPSecure = $mailConfig['encryption'];
            $mail->Port       = $mailConfig['port'];
            $mail->setFrom($mailConfig['from_address'], $mailConfig['from_name']);
            $mail->addAddress($userEmail, $userName);
            $mail->isHTML(true);
            $mail->Subject = 'Your New Registration Verification Code';
            $mail->Body    = "Use the new OTP code below to complete your registration. This code is only valid for 5 minutes.<br><br>Your OTP code is: <h2><b>{$newOtp}</b></h2>";
            $mail->send();
            echo json_encode(['success' => true, 'message' => 'A new OTP code has been successfully sent.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Failed to send verification email. Mailer Error: {$mail->ErrorInfo}"]);
        }
        exit;
    }

    public function verifyOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['registration_data'])) {
            echo json_encode(['success' => false, 'message' => 'The registration session was not found or has expired. Please re-register.']);
            exit;
        }

        $finalPhotoName = null;
        $finalPhotoPath = null;
        $submittedOtp = trim($_POST['otp'] ?? '');
        $sessionData = $_SESSION['registration_data'];

        error_log("Submitted OTP: " . $submittedOtp);
        error_log("Session Data: " . print_r($sessionData, true));

        if (time() > $sessionData['otp_expiry']) {
            unset($_SESSION['registration_data']);
            echo json_encode(['success' => false, 'message' => 'The OTP code has expired. Please register again.']);
            exit;
        }

        if ($submittedOtp !== strval($sessionData['otp'])) {
            echo json_encode(['success' => false, 'message' => 'The OTP code you entered is incorrect.']);
            exit;
        }

        if (!empty($sessionData['temp_photo_path']) && file_exists($sessionData['temp_photo_path'])) {
            $tempPath = $sessionData['temp_photo_path'];
            $uploadDir = __DIR__ . '/../../storage/users/photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $finalPhotoName = basename($tempPath);
            if (strpos($finalPhotoName, 'temp_') === 0) {
                $finalPhotoName = substr($finalPhotoName, strlen('temp_'));
            }
            $finalPhotoPath = $uploadDir . $finalPhotoName;
            if (!rename($tempPath, $finalPhotoPath)) {
                echo json_encode(['success' => false, 'message' => 'Failed to save profile photo (permission error).']);
                exit;
            }
        }

        $sessionData['PATH_PHOTO'] = $finalPhotoName;
        $sessionData['STATUS'] = 'APPROVED';
        unset($sessionData['temp_photo_path']);
        unset($sessionData['temp_photo']);

        try {
            $isCreated = $this->userModel->create($sessionData);
            if ($isCreated) {
                unset($_SESSION['registration_data']);
                echo json_encode(['success' => true, 'message' => 'Registration successful!']);
            } else {
                if ($finalPhotoPath && file_exists($finalPhotoPath)) {
                    unlink($finalPhotoPath);
                }
                echo json_encode(['success' => false, 'message' => 'Failed to save data to the database.']);
            }
        } catch (Exception $e) {
            if ($finalPhotoPath && file_exists($finalPhotoPath)) {
                unlink($finalPhotoPath);
            }
            echo json_encode(['success' => false, 'message' => 'A system error has occurred: ' . $e->getMessage()]);
        }
        exit;
    }

    public function sendPasswordResetOtp()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $identifier = $_POST['username_or_email'];
        $newPassword = $_POST['password'];
        $user = $this->loginModel->getUserByUsernameOrEmail($identifier);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'The user with that username or email address was not found.']);
            exit;
        }

        if (strlen($newPassword) < 6) {
            echo json_encode(['success' => false, 'message' => 'Passwords must be more than 6 characters long.']);
            exit;
        }

        $otp = rand(1000, 9999);
        $_SESSION['forget-password'] = [
            'action'       => 'reset_password',
            'user_id'      => $user['ID'],
            'new_password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'otp'          => $otp,
            'otp_expiry'   => time() + (5 * 60)
        ];

        try {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $mailConfig = require __DIR__ . '/../../config/mail.php';
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $mailConfig['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailConfig['username'];
            $mail->Password   = $mailConfig['password'];
            $mail->SMTPSecure = $mailConfig['encryption'];
            $mail->Port       = $mailConfig['port'];
            $mail->setFrom($mailConfig['from_address'], $mailConfig['from_name']);
            $mail->addAddress($user['EMAIL'], $user['FULL_NAME']);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';
            $mail->Body    = "Ha\ello <b>{$user['FULL_NAME']}</b>,<br><br>Someone has requested to reset your password. Use the OTP code below to continue. This code is only valid for 5 minutes.: <h2><b>{$otp}</b></h2>";
            $mail->AltBody = "Your OTP code is: {$otp}";
            $mail->send();
            echo json_encode([
                'success' => true,
                'message' => 'The OTP has been successfully sent. Please check your email.',
                'email'   => $user['EMAIL']
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => "Failed to send verification email. Mailer Error: {$mail->ErrorInfo}"
            ]);
        }
        exit;
    }

    public function verifyOtpForgetPassword()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        if (!isset($_SESSION['forget-password'])) {
            echo json_encode(['success' => false, 'message' => 'The verification session was not found or has expired.']);
            exit;
        }

        $submittedOtp = trim($_POST['otp'] ?? '');
        $sessionData = $_SESSION['forget-password'];

        if (time() > $sessionData['otp_expiry']) {
            unset($_SESSION['forget-password']);
            echo json_encode(['success' => false, 'message' => 'The OTP code has expired.']);
            exit;
        }

        if ($submittedOtp !== strval($sessionData['otp'])) {
            echo json_encode(['success' => false, 'message' => 'The OTP code you entered is incorrect.']);
            exit;
        }

        try {
            $userId = $sessionData['user_id'];
            $hashedPassword = $sessionData['new_password'];
            $isUpdated = $this->userModel->updatePassword($userId, $hashedPassword);
            if ($isUpdated) {
                unset($_SESSION['forget-password']);
                echo json_encode(['success' => true, 'message' => 'Your password has been successfully changed! Please log in.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update the password in the database.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'A system error has occurred: ' . $e->getMessage()]);
        }
        exit;
    }

    public function resendOtpForgetPassword()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        if (!isset($_SESSION['forget-password']) || !isset($_SESSION['forget-password']['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Your session has ended. Please start over.']);
            exit;
        }

        $sessionData = $_SESSION['forget-password'];
        $user = $this->loginModel->getUserByUsernameOrEmail($sessionData['user_id']);
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'The user cannot be found.']);
            exit;
        }

        $newOtp = rand(1000, 9999);
        $newExpiry = time() + (5 * 60);
        $_SESSION['forget-password']['otp'] = $newOtp;
        $_SESSION['forget-password']['otp_expiry'] = $newExpiry;

        try {
            require_once __DIR__ . '/../../vendor/autoload.php';
            $mailConfig = require __DIR__ . '/../../config/mail.php';
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $mailConfig['host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailConfig['username'];
            $mail->Password   = $mailConfig['password'];
            $mail->SMTPSecure = $mailConfig['encryption'];
            $mail->Port       = $mailConfig['port'];
            $mail->setFrom($mailConfig['from_address'], $mailConfig['from_name']);
            $mail->addAddress($user['EMAIL'], $user['FULL_NAME']);
            $mail->isHTML(true);
            $mail->Subject = 'Your New Verification Code';
            $mail->Body    = "Use the new OTP code below. This code is only valid for 5 minutes.<br><br>Your OTP code is: <h2><b>{$newOtp}</b></h2>";
            $mail->send();
            echo json_encode(['success' => true, 'message' => 'A new OTP code has been successfully sent.']);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => "Failed to send verification email. Mailer Error: {$mail->ErrorInfo}"
            ]);
        }
        exit;
    }
}
