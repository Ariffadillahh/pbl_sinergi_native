<?php

require_once __DIR__ . '/../models/Auth/SignIn.php';
require_once __DIR__ . '/../models/Forums/Forum.php';
require_once __DIR__ . '/DashboardOverview.php';
require_once __DIR__ . '/ReportController.php';


use PHPMailer\PHPMailer\PHPMailer;


class DashboardController
{
    private $userModel;
    private $loginModel;
    private $overviewCount;
    private $reportController;
    private $forumModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->loginModel  = new SignInModel();
        $this->overviewCount = new overviewCount();
        $this->reportController = new ReportController();
        $this->forumModel = new Forum();
    }

    public function index()
    {
        $anggotaCount = $this->overviewCount->countAnggota();
        $postCount = $this->overviewCount->countPost();
        $forumCount = $this->overviewCount->countForum();
        $laporanCount = $this->overviewCount->countLaporan();
        $contentViewDashboard =  __DIR__ . '/../views/dashboard/index.php';
        require_once __DIR__ . '/../views/dashboard/layout.php';
    }

    public function laporanForum()
    {
        $report = $this->reportController->getReportForums();
        $contentViewDashboard =  __DIR__ . '/../views/dashboard/laporan/laporanForum.php';
        require_once __DIR__ . '/../views/dashboard/layout.php';
    }

    public function laporanPost()
    {
        $report = $this->reportController->getReportPost();
        $contentViewDashboard =  __DIR__ . '/../views/dashboard/laporan/laporanPost.php';
        require_once __DIR__ . '/../views/dashboard/layout.php';
    }

    public function forums()
    {
        $limit = 6;

        $activeTab = $_GET['tab'] ?? 'my-forums';

        $mySearch = $_GET['my_search'] ?? '';
        $myPage = isset($_GET['my_page']) ? (int)$_GET['my_page'] : 1;
        $myOffset = ($myPage - 1) * $limit;

        $allSearch = $_GET['all_search'] ?? '';
        $allPage = isset($_GET['all_page']) ? (int)$_GET['all_page'] : 1;
        $allOffset = ($allPage - 1) * $limit;

        $myForumsData = $this->forumModel->getMyForum($mySearch, $limit, $myOffset);
        $allForumsData = $this->forumModel->getAllForumsPagination($allSearch, $limit, $allOffset);

        $users = $this->userModel->getAllUsers();
        $allForums = $this->forumModel->allForums();

        $data = [
            'activeTab' => $activeTab,

            'myForums' => $myForumsData['data'],
            'myTotal' => $myForumsData['total'],
            'myPage' => $myPage,
            'mySearch' => $mySearch,
            'myTotalPages' => ceil($myForumsData['total'] / $limit),
            'myStart' => $myForumsData['total'] > 0 ? $myOffset + 1 : 0,
            'myEnd' => min($myOffset + $limit, $myForumsData['total']),
            'forums' => $allForumsData['data'],
            'allTotal' => $allForumsData['total'],
            'allPage' => $allPage,
            'allSearch' => $allSearch,
            'allTotalPages' => ceil($allForumsData['total'] / $limit),
            'allStart' => $allForumsData['total'] > 0 ? $allOffset + 1 : 0,
            'allEnd' => min($allOffset + $limit, $allForumsData['total'])
        ];

        foreach ($data as $key => $value) {
            $$key = $value;
        }

        $contentViewDashboard =  __DIR__ . '/../views/dashboard/forum/index.php';
        require_once __DIR__ . '/../views/dashboard/layout.php';
    }

    public function anggota()
    {
        $keyword = $_GET['q'] ?? '';
        $currentPage = (int)($_GET['page'] ?? 1);
        $limit = 5;
        $offset = ($currentPage - 1) * $limit;

        $totalUsers = $this->userModel->getTotalUsers($keyword);
        $totalPages = ceil($totalUsers / $limit);

        $users = $this->userModel->getPaginatedUsers($keyword, $limit, $offset);

        $data = [
            'users' => $users,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'keyword' => $keyword,
            'totalResults' => $totalUsers,
            'limit' => $limit
        ];

        $contentViewDashboard =  __DIR__ . '/../views/dashboard/anggota/index.php';
        require_once __DIR__ . '/../views/dashboard/layout.php';
    }

    public function createAccountByAdmin()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $required_fields = ['nama-lengkap', 'personal-number', 'email', 'password', 'role', 'username'];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $fieldName = str_replace('-', ' ', $field);
                echo json_encode(['success' => false, 'message' => "Field '" . ucwords($fieldName) . "' tidak boleh kosong."]);
                exit;
            }
        }

        if (strlen($_POST['password']) < 6) {
            echo json_encode(['success' => false, 'message' => 'Password harus minimal 6 karakter.']);
            exit;
        }

        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        $userByEmail = $this->loginModel->getUserByUsernameOrEmail($email);
        if ($userByEmail) {
            echo json_encode(['success' => false, 'message' => 'Email sudah ada']);
            exit;
        }

        $personalNumber = $_POST['personal-number'];
        $userByPN = $this->loginModel->getUserByUsernameOrEmail($personalNumber);
        if ($userByPN) {
            echo json_encode(['success' => false, 'message' => 'NIM/NIP sudah ada']);
            exit;
        }

        $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $registrationData = [
            'ID'              => uniqid(),
            'USERNAME'        => htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'),
            'PERSONAL_NUMBER' => htmlspecialchars($_POST['personal-number'], ENT_QUOTES, 'UTF-8'),
            'FULL_NAME'       => htmlspecialchars($_POST['nama-lengkap'], ENT_QUOTES, 'UTF-8'),
            'EMAIL'           => $email,
            'PASSWORD'        => $hashedPassword,
            'ROLE'            => htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8'),
        ];

        try {
            $isCreated = $this->userModel->create($registrationData);

            if (!$isCreated) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menyimpan data ke database. Silakan coba lagi.'
                ]);
                exit;
            }
        } catch (Exception $dbException) {
            error_log("DB Create Error: " . $dbException->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database saat membuat akun.'
            ]);
            exit;
        }

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
            $mail->Subject = 'Akun Anda di SINERGI Telah Dibuat';

            $plainPassword = $_POST['password'];

            $mail->Body = "Halo <b>{$registrationData['FULL_NAME']}</b>,<br><br>"
                . "Akun Anda untuk aplikasi SINERGI telah berhasil dibuat oleh administrator.<br><br>"
                . "Anda dapat login menggunakan detail berikut:<br>"
                . "<b>Email:</b> {$registrationData['EMAIL']}<br>"
                . "<b>Password:</b> <b>{$plainPassword}</b><br><br>"
                . "Silakan segera ganti password Anda.<br>"
                . "Terima kasih.";

            $mail->AltBody = "Akun Anda telah dibuat. Email: {$registrationData['EMAIL']}, Password: {$plainPassword}";

            $mail->send();

            echo json_encode([
                'success' => true,
                'message' => 'Akun baru berhasil dibuat dan email notifikasi telah dikirim.'
            ]);
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);

            echo json_encode([
                'success' => true,
                'message' => 'Akun berhasil dibuat, namun email notifikasi gagal dikirim. Error: ' . $mail->ErrorInfo
            ]);
        }
    }

    public function updateRoleByAdmin()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan.']);
            exit;
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki hak akses untuk aksi ini.']);
            exit;
        }

        $userId = $_POST['user_id'] ?? null;
        $newRole = $_POST['role'] ?? null;

        if (empty($userId) || empty($newRole)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap. ID Pengguna dan Role baru wajib diisi.']);
            exit;
        }

        $validRoles = ['ADMIN', 'MAHASISWA', 'DOSEN', 'ALUMNI', 'MITRA'];
        if (!in_array($newRole, $validRoles)) {
            echo json_encode(['success' => false, 'message' => 'Role yang dipilih tidak valid.']);
            exit;
        }

        if (isset($_SESSION['user_id']) && $userId === $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Anda tidak dapat mengubah role akun Anda sendiri.']);
            exit;
        }

        try {
            $isSuccess = $this->userModel->updateUserRoleById($userId, $newRole);

            if ($isSuccess) {
                echo json_encode(['success' => true, 'message' => 'Role pengguna berhasil diperbarui.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal memperbarui role. Data tidak berubah atau ID tidak ditemukan.']);
            }
        } catch (Exception $e) {
            error_log("Update Role Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan pada server.']);
        }
    }
}
