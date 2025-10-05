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

    public function signInAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $identifier = $_POST['username_or_email'];
            $password = $_POST['password'];

            $loginModel = new SignInModel();
            $user = $loginModel->getUserByUsernameOrEmail($identifier);

            if ($user && password_verify($password, $user['PASSWORD'])) {

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
        $_SESSION['full_name'] = $user['FULL_NAME'];
        $_SESSION['email'] = $user['EMAIL'];
        $_SESSION['role'] = $user['ROLE'];
        $_SESSION['prodi'] = $user['PRODI'];
        $_SESSION['jenjang_studi'] = $user['JENJANG_STUDI'];
        $_SESSION['tahun_masuk'] = $user['TAHUN_MASUK'];
        $_SESSION['path_photo'] = $user['PATH_PHOTO'];
        $_SESSION['logged_in'] = true;
    }
}

class SignupController
{
    public function  StudentPage()
    {
        include __DIR__ . '/../views/sign-up/index.php';
    }

    public function lecturerPage()
    {
        include __DIR__ . '/../views/sign-up/lecturer/index.php';
    }

    public function register()
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

        $required_fields = ['FullName', 'username', 'personal_number', 'tahunMasuk', 'jenjangStudi', 'email', 'password'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "Field '{$field}' tidak boleh kosong."]);
                exit;
            }
        }

        $tempPhotoPath = null;

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $tempUploadDir = __DIR__ . '/../../storage/users/temp/';
            if (!is_dir($tempUploadDir)) {
                mkdir($tempUploadDir, 0777, true);
            }

            $tempFileName = uniqid('temp_') . '-' . basename($_FILES['photo']['name']);
            $tempPhotoPath = $tempUploadDir . $tempFileName;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $tempPhotoPath)) {
                echo json_encode(['success' => false, 'message' => 'Gagal memproses file upload.']);
                exit;
            }
        }


        $otp = rand(1000, 9999);
        $registrationData = [
            'ID'              => uniqid(),
            'USERNAME'        => htmlspecialchars($_POST['username']),
            'PERSONAL_NUMBER' => htmlspecialchars($_POST['personal_number']),
            'FULL_NAME'       => htmlspecialchars($_POST['FullName']),
            'TAHUN_MASUK'     => (int) $_POST['tahunMasuk'],
            'EMAIL'           => filter_var($_POST['email'], FILTER_VALIDATE_EMAIL),
            'PASSWORD'        => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'PATH_PHOTO'      => null,
            'JENJANG_STUDI'   => $_POST['jenjangStudi'],
            'ROLE'            => 'MAHASISWA',
            'otp'             => $otp,
            'otp_expiry'      => time() + (10 * 60),
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
            $mail->addAddress($registrationData['EMAIL'], $registrationData['FULL_NAME']);

            $mail->isHTML(true);

            $mail->Subject = 'Kode Verifikasi Registrasi Akun SINERGI';
            $mail->Body    = "Halo <b>{$registrationData['FULL_NAME']}</b>,<br><br>Terima kasih telah mendaftar. Gunakan kode OTP di bawah ini untuk menyelesaikan proses registrasi Anda. Kode ini hanya berlaku 5 menit.<br><br>Kode OTP Anda adalah: <h2><b>{$otp}</b></h2>";
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
