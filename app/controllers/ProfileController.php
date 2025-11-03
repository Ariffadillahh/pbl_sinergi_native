<?php
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
}
