<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../models/Auth/SignUp.php';
require_once __DIR__ . '/../models/Auth/SignIn.php';
require_once __DIR__ . '/../models/Forums/Forum.php';



class SigninController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index()
    {
        include __DIR__ . '/../views/sign-in/index.php';
    }

    public function setUp()
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

        if (empty($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Sesi tidak valid. Silakan login ulang.']);
            exit;
        }

        $required_fields = ['jenjang_studi', 'tahunMasuk', 'prodi'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['status' => 'error', 'message' => "Field '{$field}' tidak boleh kosong."]);
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
            $userModel = new User();
            $isUpdated = $userModel->updateProfile($userId, $dataToUpdate);

            if ($isUpdated) {
                $loginModel  = new SignInModel();
                $updatedUser = $loginModel->getUserByUsernameOrEmail($email);

                if ($updatedUser) {
                    $this->createSession($updatedUser);

                    echo json_encode([
                        'status'  => 'success',
                        'message' => 'Profil berhasil diperbarui dan sesi diperbarui!'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Data user tidak ditemukan setelah update.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Gagal menyimpan data ke database.'
                ]);
            }
        } catch (\Exception $e) {
            error_log("Error pada controller setup: " . $e->getMessage());
            echo json_encode([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan pada server.'
            ]);
        }

        exit;
    }


    public function signInAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $identifier = $_POST['username_or_email'];
            $password = $_POST['password'];

            $loginModel = new SignInModel();
            $user = $loginModel->getUserByUsernameOrEmail($identifier);

            if ($user && password_verify($password, $user['PASSWORD'])) {
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
                            $bulanLulus = 10; // Oktober, sesuai permintaan

                            $tahunSekarang = (int)date('Y');
                            $bulanSekarang = (int)date('m');

                            if ($tahunSekarang > $tahunLulus || ($tahunSekarang == $tahunLulus && $bulanSekarang >= $bulanLulus)) {

                                $loginModel->updateUserRole($userId, 'ALUMNI');

                                $user['ROLE'] = 'ALUMNI';

                                $this->createSession($user);

                                header('Location: ' . BASEURL . '/forums');
                                exit();
                            }
                        }
                    }
                }

                $this->createSession($user);
                $role = $user['ROLE'];

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
                $_SESSION['login_error'] = "Username, Email, atau Password salah!";
                header('Location: ' . BASEURL . '/sign-in');
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
        $users = $userModel->getAllUsers();

        echo json_encode(['success' => true, 'data' => $users]);
        exit;
    }
}

class SignupController
{
    public function  index()
    {
        include __DIR__ . '/../views/sign-up/index.php';
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
                echo json_encode(['success' => false, 'message' => "Field '{$field}' tidak boleh kosong."]);
                exit;
            }
        }

        if (strlen($_POST['password']) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password Harus lebih dari 6 karakter']);
            exit;
        }

        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
            exit;
        }

        $role = null;
        if (str_ends_with($email, '@stu.pnj.ac.id')) {
            $role = 'MAHASISWA';
        } elseif (str_ends_with($email, '@tik.pnj.ac.id')) {
            $role = 'DOSEN';
        } else {
            echo json_encode(['success' => false, 'message' => 'Domain email tidak valid. Gunakan email PNJ yang sesuai.']);
            exit;
        }

        $loginModel = new SignInModel();
        $username = $_POST['username'];
        $email = $_POST['email'];
        $personalNumber = $_POST['personal_number'];

        $user = $loginModel->getUserByUsernameOrEmail($username);

        if ($user) {
            echo json_encode(['success' => false, 'message' => 'Username sudah ada']);
            exit;
        }

        $user = $loginModel->getUserByUsernameOrEmail($email);

        if ($user) {
            echo json_encode(['success' => false, 'message' => 'Email sudah ada']);
            exit;
        }

        $user = $loginModel->getUserByUsernameOrEmail($personalNumber);

        if ($user) {
            echo json_encode(['success' => false, 'message' => 'NIM/NIP sudah ada']);
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
            $mail->Subject = 'Kode Verifikasi Registrasi Akun SINERGI';
            $mail->Body    = "Halo <b>{$registrationData['FULL_NAME']}</b>,<br><br>Terima kasih telah mendaftar. Gunakan kode OTP di bawah ini untuk menyelesaikan proses registrasi Anda. Kode ini hanya berlaku 5 menit.<br><br>Kode OTP Anda adalah: <h2><b>{$otp}</b></h2>";
            $mail->AltBody = "Kode OTP Anda adalah: {$otp}";

            $mail->send();

            echo json_encode([
                'success' => true,
                'message' => 'OTP berhasil dikirim. Silakan periksa email Anda.',
                'role'    => $role
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal mengirim email verifikasi. Silakan coba lagi.'
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
            echo json_encode(['success' => false, 'message' => 'Sesi registrasi Anda telah berakhir. Harap ulangi dari awal.']);
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
            $mail->Subject = 'Kode Verifikasi Registrasi Baru Anda';
            $mail->Body    = "Gunakan kode OTP baru di bawah ini untuk menyelesaikan registrasi. Kode ini hanya berlaku 5 menit.<br><br>Kode OTP Anda adalah: <h2><b>{$newOtp}</b></h2>";

            $mail->send();

            echo json_encode(['success' => true, 'message' => 'Kode OTP baru telah berhasil dikirim.']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => "Gagal mengirim email verifikasi. Mailer Error: {$mail->ErrorInfo}"]);
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
            echo json_encode(['success' => false, 'message' => 'Sesi registrasi tidak ditemukan atau sudah habis. Silakan daftar ulang.']);
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
            echo json_encode(['success' => false, 'message' => 'Kode OTP telah kadaluarsa. Silakan daftar ulang.']);
            exit;
        }

        if ($submittedOtp !== strval($sessionData['otp'])) {
            echo json_encode(['success' => false, 'message' => 'Kode OTP yang Anda masukkan salah.']);
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
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan foto profil (permission error).']);
                exit;
            }
        }

        $sessionData['PATH_PHOTO'] = $finalPhotoName;
        unset($sessionData['temp_photo_path']);
        unset($sessionData['temp_photo']);

        try {
            $userModel = new User();

            $isCreated = $userModel->create($sessionData);

            if ($isCreated) {
                unset($_SESSION['registration_data']);
                echo json_encode(['success' => true, 'message' => 'Registrasi berhasil!']);
            } else {
                if ($finalPhotoPath && file_exists($finalPhotoPath)) {
                    unlink($finalPhotoPath);
                }
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data ke database.']);
            }
        } catch (Exception $e) {
            if ($finalPhotoPath && file_exists($finalPhotoPath)) {
                unlink($finalPhotoPath);
            }
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }

        exit;
    }
}

class forgetPassword
{
    public function index()
    {
        include __DIR__ . '/../views/forget-password/index.php';
    }

    public function forgetPassword()
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

        $identifier = $_POST['username_or_email'];
        $newPassword = $_POST['password'];
        $loginModel = new SignInModel();
        $user = $loginModel->getUserByUsernameOrEmail($identifier);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Pengguna dengan username atau email tersebut tidak ditemukan.']);
            exit;
        }

        if (strlen($newPassword) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password Harus lebih dari 6 karakter']);
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
            $mail->Subject = 'Kode Verifikasi Pengaturan Ulang Kata Sandi';
            $mail->Body    = "Halo <b>{$user['FULL_NAME']}</b>,<br><br>Seseorang meminta untuk mengatur ulang kata sandi Anda. Gunakan kode OTP di bawah ini untuk melanjutkan. Kode ini hanya berlaku 5 menit.<br><br>Kode OTP Anda adalah: <h2><b>{$otp}</b></h2>";
            $mail->AltBody = "Kode OTP Anda adalah: {$otp}";

            $mail->send();

            echo json_encode([
                'success' => true,
                'message' => 'OTP berhasil dikirim. Silakan periksa email Anda.'
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => "Gagal mengirim email verifikasi. Mailer Error: {$mail->ErrorInfo}"
            ]);
        }

        exit;
    }

    public function verifyOtpForgetPassword()
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

        if (!isset($_SESSION['forget-password'])) {
            echo json_encode(['success' => false, 'message' => 'Sesi verifikasi tidak ditemukan atau sudah habis.']);
            exit;
        }

        $submittedOtp = trim($_POST['otp'] ?? '');
        $sessionData = $_SESSION['forget-password'];

        if (time() > $sessionData['otp_expiry']) {
            unset($_SESSION['forget-password']);
            echo json_encode(['success' => false, 'message' => 'Kode OTP telah kadaluarsa.']);
            exit;
        }

        if ($submittedOtp !== strval($sessionData['otp'])) {
            echo json_encode(['success' => false, 'message' => 'Kode OTP yang Anda masukkan salah.']);
            exit;
        }

        try {
            $userModel = new User();

            $userId = $sessionData['user_id'];
            $hashedPassword = $sessionData['new_password'];

            $isUpdated = $userModel->updatePassword($userId, $hashedPassword);

            if ($isUpdated) {
                unset($_SESSION['forget-password']);
                echo json_encode(['success' => true, 'message' => 'Password berhasil diubah! Silakan login.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui password di database.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }

        exit;
    }

    public function resendOtpForgetPassword()
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

        if (!isset($_SESSION['forget-password']) || !isset($_SESSION['forget-password']['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Sesi Anda telah berakhir. Harap ulangi dari awal.']);
            exit;
        }

        $sessionData = $_SESSION['forget-password'];

        $userModel = new SignInModel();
        $user = $userModel->getUserByUsernameOrEmail($sessionData['user_id']);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Pengguna tidak dapat ditemukan.']);
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
            $mail->Subject = 'Kode Verifikasi Baru Anda';
            $mail->Body    = "Gunakan kode OTP baru di bawah ini. Kode ini hanya berlaku 5 menit.<br><br>Kode OTP Anda adalah: <h2><b>{$newOtp}</b></h2>";

            $mail->send();

            echo json_encode(['success' => true, 'message' => 'Kode OTP baru telah berhasil dikirim.']);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => "Gagal mengirim email verifikasi. Mailer Error: {$mail->ErrorInfo}"
            ]);
        }

        exit;
    }
}
