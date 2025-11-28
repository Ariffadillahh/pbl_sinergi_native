<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../models/Users/UserModel.php';
require_once __DIR__ . '/../models/Posts/PostModel.php';
require_once __DIR__ . '/../helpers/mentionHelper.php';

class ProfileController
{
    private $postModel;
    private $userModel;
    private $signInModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->userModel = new UserModel();
        $this->signInModel = new SignInModel();
    }


    public function index()
    {
        $userId = $_SESSION['user_id'];

        if (!$userId) {
            header('Location: ' . BASEURL . '/sign-in');
            exit;
        }

        $posts = $this->postModel->getPostsByUser($userId);
        foreach ($posts as &$post) {
            $post['CONTENT_FORMATTED'] = mentionHelper::formatMentions($post['CONTENT']);
        }
        unset($post);

        $contentViewProfile = __DIR__ . '/../views/profile/index.php';
        require_once __DIR__ . '/../views/profile/layout.php';
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $fullName = $_POST['full_name'] ?? '';
            $personalNumber = $_POST['personal_number'] ?? '';
            $programStudi = $_POST['prodi'] ?? '';
            $jenjangStudi = $_POST['jenjang_studi'] ?? '';
            $tahunMasuk = $_POST['tahun_masuk'] ?? '';

            $oldUserData = $this->userModel->getUserById($userId);

            $user = $this->signInModel->getUserByUsernameOrEmail($personalNumber);

            if ($_SESSION['personal_number'] != $personalNumber) {
                if ($user) {
                    echo json_encode(['success' => false, 'message' => 'NIM/NIP sudah ada']);
                    exit;
                }
            }

            $photoPath = $oldUserData['PATH_PHOTO'] ?? '';

            if (!empty($_FILES['profileFoto']['name'])) {
                $targetDir = __DIR__ . '/../../storage/users/photos/';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $fileName = uniqid() . "_" . basename($_FILES['profileFoto']['name']);
                $targetFile = $targetDir . $fileName;

                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                if (!in_array($_FILES['profileFoto']['type'], $allowedTypes)) {
                    echo json_encode(['success' => false, 'message' => 'Format foto tidak didukung.']);
                    exit;
                }

                if (move_uploaded_file($_FILES['profileFoto']['tmp_name'], $targetFile)) {
                    if (!empty($oldUserData['PATH_PHOTO']) && $oldUserData['PATH_PHOTO'] !== 'default.png') {
                        $oldFile = $targetDir . $oldUserData['PATH_PHOTO'];
                        if (file_exists($oldFile)) {
                            unlink($oldFile);
                        }
                    }
                    $photoPath = $fileName;
                }
            }

            $result = $this->userModel->updateProfile(
                $userId,
                $fullName,
                $personalNumber,
                $programStudi,
                $jenjangStudi,
                $tahunMasuk,
                $photoPath
            );

            if ($result['success']) {
                $_SESSION['full_name'] = $fullName;
                $_SESSION['personal_number'] = $personalNumber;
                $_SESSION['prodi'] = $programStudi;
                $_SESSION['jenjang_studi'] = $jenjangStudi;
                $_SESSION['tahun_masuk'] = $tahunMasuk;
                $_SESSION['path_photo'] = $photoPath;

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => $result['message']]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
        exit;
    }

    public function updatePassword()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';

            if (empty($currentPassword) || empty($newPassword)) {
                echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi.']);
                exit;
            }

            if (strlen($newPassword) < 6) {
                echo json_encode(['success' => false, 'message' => 'Password baru minimal harus 6 karakter.']);
                exit;
            }

            $userData = $this->userModel->getUserById($userId);

            if (!$userData) {
                echo json_encode(['success' => false, 'message' => 'User tidak ditemukan.']);
                exit;
            }

            $hashedPasswordFromDb = $userData['PASSWORD'];

            if (!password_verify($currentPassword, $hashedPasswordFromDb)) {
                echo json_encode(['success' => false, 'message' => 'Password Anda saat ini salah.']);
                exit;
            }

            if (password_verify($newPassword, $hashedPasswordFromDb)) {
                echo json_encode(['success' => false, 'message' => 'Password baru tidak boleh sama dengan password lama.']);
                exit;
            }

            $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $result = $this->userModel->updatePassword($userId, $newHashedPassword);

            if ($result['success']) {
                echo json_encode(['success' => true, 'message' => 'Password berhasil diubah.']);
            } else {
                echo json_encode(['success' => false, 'message' => $result['message']]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
        exit;
    }

    public function updateRoleMahasiswa()
    {
        if (ob_get_length()) ob_clean();

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $userId   = $_SESSION['user_id'] ?? null;
        $email    = $_SESSION['email'] ?? null;
        $fullName = $_SESSION['full_name'] ?? 'User';

        if (!$userId || !$email) {
            echo json_encode(['success' => false, 'message' => 'Sesi habis. Silakan login ulang.']);
            exit;
        }

        $otp = rand(1000, 9999);
        $_SESSION['otp_code'] = (string)$otp;
        $_SESSION['otp_expiry'] = time() + 300;

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
            $mail->addAddress($email, $fullName);
            $mail->isHTML(true);
            $mail->Subject = 'Kode Verifikasi Perubahan Status Mahasiswa';
            $mail->Body    = "Halo <b>{$fullName}</b>,<br>Kode OTP Anda: <b>{$otp}</b>";
            $mail->send();

            if (ob_get_length()) ob_clean();

            echo json_encode([
                'success' => true,
                'message' => 'OTP berhasil dikirim ke email Anda.'
            ]);

            exit;
        } catch (Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['success' => false, 'message' => 'Gagal kirim email.']);
            exit;
        }
    }

    public function verifyStudentOtp()
    {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $inputOtp = $_POST['otp'] ?? '';
        $userId   = $_SESSION['user_id'] ?? null;

        if (!isset($_SESSION['otp_code']) || !isset($_SESSION['otp_expiry'])) {
            echo json_encode(['success' => false, 'message' => 'Permintaan OTP tidak ditemukan. Silakan minta kode ulang.']);
            exit;
        }

        if (time() > $_SESSION['otp_expiry']) {
            unset($_SESSION['otp_code'], $_SESSION['otp_expiry']);
            echo json_encode(['success' => false, 'message' => 'Kode OTP kadaluarsa. Silakan minta kode ulang.']);
            exit;
        }

        if ((string)$inputOtp !== (string)$_SESSION['otp_code']) {
            echo json_encode(['success' => false, 'message' => 'Kode OTP salah.']);
            exit;
        }

        try {
            $isUpdated = $this->userModel->updateToMahasiswa($userId);

            if ($isUpdated) {
                unset($_SESSION['otp_code'], $_SESSION['otp_expiry'], $_SESSION['otp_action']);

                $_SESSION['role'] = 'MAHASISWA';

                echo json_encode([
                    'success' => true,
                    'message' => 'Verifikasi berhasil. Status Anda telah kembali menjadi Mahasiswa.'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal mengupdate data ke database.']);
            }
            exit;
        } catch (Exception $e) {
            error_log("Update Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem saat memperbarui status.']);
            exit;
        }
    }

    public function resendStudentOtp()
    {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $userId   = $_SESSION['user_id'] ?? null;
        $email    = $_SESSION['email'] ?? null;
        $fullName = $_SESSION['full_name'] ?? 'User';

        if (!$userId || !$email) {
            echo json_encode(['success' => false, 'message' => 'Sesi habis.']);
            exit;
        }

        $otp = rand(1000, 9999);
        $_SESSION['otp_code'] = (string)$otp;
        $_SESSION['otp_expiry'] = time() + 300;

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
            $mail->addAddress($email, $fullName);
            $mail->isHTML(true);

            $mail->Subject = 'Kirim Ulang: Kode Verifikasi Status Mahasiswa';
            $mail->Body    = "Halo <b>{$fullName}</b>,<br>Kode OTP Baru Anda: <b>{$otp}</b><br>Kode ini berlaku 5 menit.";
            $mail->send();

            if (ob_get_length()) ob_clean();
            echo json_encode([
                'success' => true,
                'message' => 'Kode OTP baru berhasil dikirim.'
            ]);
            exit;
        } catch (Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['success' => false, 'message' => 'Gagal mengirim ulang email.']);
            exit;
        }
    }
}
