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
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            exit;
        }

        $userId = $_SESSION['user_id'];

        $fullName       = trim($_POST['full_name'] ?? '');
        $personalNumber = trim($_POST['personal_number'] ?? '');
        $programStudi   = trim($_POST['prodi'] ?? '');
        $jenjangStudi   = trim($_POST['jenjang_studi'] ?? '');
        $tahunMasuk     = trim($_POST['tahun_masuk'] ?? '');

        $oldUserData = $this->userModel->getUserById($userId);

        if ($_SESSION['personal_number'] != $personalNumber) {
            $user = $this->signInModel->getUserByUsernameOrEmail($personalNumber);
            if ($user) {
                echo json_encode(['success' => false, 'message' => 'NIM/NIP already exists']);
                exit;
            }
        }

        $photoPath = $oldUserData['PATH_PHOTO'] ?? '';

        if (!empty($_FILES['profileFoto']['name'])) {
            $targetDir = __DIR__ . '/../../storage/users/photos/';

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileExtension = strtolower(pathinfo($_FILES['profileFoto']['name'], PATHINFO_EXTENSION));
            $fileName = uniqid() . "_" . bin2hex(random_bytes(5)) . "." . $fileExtension;
            $targetFile = $targetDir . $fileName;

            $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($fileExtension, $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => 'Unsupported photo format. Allowed: jpg, jpeg, png, webp']);
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

            $durasiStudi = 0;
            $jenjangCek = strtoupper($jenjangStudi);

            if ($jenjangCek === 'D4') {
                $durasiStudi = 4;
            } elseif ($jenjangCek === 'D3') {
                $durasiStudi = 3;
            }

            if ($durasiStudi > 0 && is_numeric($tahunMasuk)) {
                $tahunLulus = (int)$tahunMasuk + $durasiStudi;
                $bulanLulus = 10;

                $tahunSekarang = (int)date('Y');
                $bulanSekarang = (int)date('m');

                if ($tahunSekarang > $tahunLulus || ($tahunSekarang == $tahunLulus && $bulanSekarang >= $bulanLulus)) {
                    $this->userModel->updateUserRole($userId, 'ALUMNI');

                    $_SESSION['role'] = 'ALUMNI';
                }
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
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
                echo json_encode(['success' => false, 'message' => 'All fields are required.']); // Changed
                exit;
            }

            if (strlen($newPassword) < 6) {
                echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters long.']); // Changed
                exit;
            }

            $userData = $this->userModel->getUserById($userId);

            if (!$userData) {
                echo json_encode(['success' => false, 'message' => 'User not found.']); // Changed
                exit;
            }

            $hashedPasswordFromDb = $userData['PASSWORD'];

            if (!password_verify($currentPassword, $hashedPasswordFromDb)) {
                echo json_encode(['success' => false, 'message' => 'Your current password is incorrect.']); // Changed
                exit;
            }

            if (password_verify($newPassword, $hashedPasswordFromDb)) {
                echo json_encode(['success' => false, 'message' => 'New password cannot be the same as the old password.']); // Changed
                exit;
            }

            $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

            $result = $this->userModel->updatePassword($userId, $newHashedPassword);

            if ($result['success']) {
                echo json_encode(['success' => true, 'message' => 'Password updated successfully.']); // Changed
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
            echo json_encode(['success' => false, 'message' => 'Session expired. Please log in again.']); // Changed
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
            $mail->Subject = 'Student Status Change Verification Code'; // Changed
            $mail->Body    = "Hello <b>{$fullName}</b>,<br>Your OTP code is: <b>{$otp}</b>";

            $mail->send();

            if (ob_get_length()) ob_clean();

            echo json_encode([
                'success' => true,
                'message' => 'OTP sent successfully to your email.',
                'email' => $email
            ]);

            exit;
        } catch (Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['success' => false, 'message' => 'Failed to send email.']); // Changed
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
        $batch = isset($_SESSION['tahun_masuk']) ? (int)$_SESSION['tahun_masuk'] : null;

        if (!isset($_SESSION['otp_code']) || !isset($_SESSION['otp_expiry'])) {
            echo json_encode(['success' => false, 'message' => 'OTP request not found. Please request the code again.']); // Changed
            exit;
        }

        if (time() > $_SESSION['otp_expiry']) {
            unset($_SESSION['otp_code'], $_SESSION['otp_expiry']);
            echo json_encode(['success' => false, 'message' => 'OTP code expired. Please request the code again.']); // Changed
            exit;
        }

        if ((string)$inputOtp !== (string)$_SESSION['otp_code']) {
            echo json_encode(['success' => false, 'message' => 'Incorrect OTP code.']); // Changed
            exit;
        }

        try {
            $result = $this->userModel->updateToMahasiswa($userId);

            if ($result['success']) {
                unset($_SESSION['otp_code'], $_SESSION['otp_expiry'], $_SESSION['otp_action']);

                $_SESSION['role'] = 'MAHASISWA';
                $_SESSION['tahun_masuk'] = $batch + 1;

                echo json_encode([
                    'success' => true,
                    'message' => $result['message']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
            exit;
        } catch (Exception $e) {
            error_log("Update Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'A system error occurred.']);
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
            echo json_encode(['success' => false, 'message' => 'Session expired.']); // Changed
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

            $mail->Subject = 'Resend: Student Status Verification Code'; // Changed
            $mail->Body    = "Hello <b>{$fullName}</b>,<br>Your New OTP Code is: <b>{$otp}</b><br>This code is valid for 5 minutes."; // Changed

            $mail->send();

            if (ob_get_length()) ob_clean();
            echo json_encode([
                'success' => true,
                'message' => 'New OTP code sent successfully.' // Changed
            ]);
            exit;
        } catch (Exception $e) {
            if (ob_get_length()) ob_clean();
            echo json_encode(['success' => false, 'message' => 'Failed to resend email.']); // Changed
            exit;
        }
    }
}
