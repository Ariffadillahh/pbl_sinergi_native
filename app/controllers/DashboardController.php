<?php

require_once __DIR__ . '/../models/Auth/SignIn.php';
require_once __DIR__ . '/../models/Forum/Forum.php';
require_once __DIR__ . '/DashboardOverview.php';
require_once __DIR__ . '/ReportController.php';
require_once __DIR__ . '/../models/Notif/NotificationModel.php';


use PHPMailer\PHPMailer\PHPMailer;


class DashboardController
{
    private $userModel;
    private $loginModel;
    private $overviewCount;
    private $reportController;
    private $forumModel;
    private $notificationModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->loginModel  = new SignInModel();
        $this->overviewCount = new overviewCount();
        $this->reportController = new ReportController();
        $this->forumModel = new ForumModel();
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        $anggotaCount = $this->overviewCount->countAnggota();
        $postCount = $this->overviewCount->countPost();
        $forumCount = $this->overviewCount->countForum();
        $groupCount = $this->overviewCount->groupCount();
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

    public function laporanGroup()
    {
        $report = $this->reportController->getReportGroup();
        $contentViewDashboard =  __DIR__ . '/../views/dashboard/laporan/laporanGroup.php';
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

        $users = $this->userModel->getAllUser();
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

        $contentViewDashboard =  __DIR__ . '/../views/dashboard/anggota/allusers.php';
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

        $required_fields = ['nama-lengkap', 'personal-number', 'email', 'role', 'username'];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $fieldName = str_replace('-', ' ', $field);
                echo json_encode(['success' => false, 'message' => "Field '" . ucwords($fieldName) . "' tidak boleh kosong."]);
                exit;
            }
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

        $rawPassword = bin2hex(random_bytes(6));

        $hashedPassword = password_hash($rawPassword, PASSWORD_BCRYPT);

        $registrationData = [
            'ID'              => uniqid(),
            'USERNAME'        => htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'),
            'PERSONAL_NUMBER' => htmlspecialchars($_POST['personal-number'], ENT_QUOTES, 'UTF-8'),
            'FULL_NAME'       => htmlspecialchars($_POST['nama-lengkap'], ENT_QUOTES, 'UTF-8'),
            'EMAIL'           => $email,
            'PASSWORD'        => $hashedPassword,
            'ROLE'            => htmlspecialchars($_POST['role'], ENT_QUOTES, 'UTF-8'),
            'STATUS'          => htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8'),
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

            $resetLink = BASEURL . '/forget-password';

            $mail->Body = "Halo <b>{$registrationData['FULL_NAME']}</b>,<br><br>"
                . "Akun Anda untuk aplikasi SINERGI telah berhasil dibuat oleh administrator.<br><br>"
                . "Anda dapat login menggunakan detail berikut:<br>"
                . "<b>Email:</b> {$registrationData['EMAIL']}<br><br>"
                . "Demi keamanan, silakan segera atur password Anda dengan mengklik tombol di bawah ini:<br><br>"

                . "<a href='{$resetLink}' style='background-color: #2563eb; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-family: Arial, sans-serif;'>"
                . "Atur Password Sekarang"
                . "</a><br><br>"

                . "Jika tombol di atas tidak berfungsi, silakan klik tautan berikut:<br>"
                . "<a href='{$resetLink}'>{$resetLink}</a><br><br>"
                . "Terima kasih.";

            $mail->AltBody = "Akun Anda telah dibuat. Email: {$registrationData['EMAIL']}";

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

    public function joinByAdmin()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed.']);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;
        $ownerId = $_POST['owner_id'] ?? null;
        $userId = $_POST['user_id'] ?? null;
        $accessKey = $_POST['access_key'] ?? null;


        if (empty($forumId) || empty($userId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            exit;
        }

        $result = $this->forumModel->joinForum($forumId, $userId, $accessKey);
        if ($ownerId !== $userId) {
            $this->notificationModel->addNotification(
                $userId,
                $_SESSION['user_id'],
                $forumId,
                'ADMIN_INVITE_FORUM',
                'FORUM'
            );
            error_log("Kekirim ke user" . $userId);
        }

        if ($result['success']) {
            echo json_encode(['success' => true, 'message' => $result['message']]);
            error_log("Succsess menambahkan" . $userId);
        } else {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
        exit;
    }

    public function requestedAccounts()
    {
        $contentViewDashboard =  __DIR__ . '/../views/dashboard/anggota/requested-accounts.php';
        require_once __DIR__ . '/../views/dashboard/layout.php';
    }

    public function getPendingRequestsCount()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
            echo json_encode(['success' => false, 'count' => 0]);
            exit;
        }
        
        try {
            $count = $this->userModel->getPendingMitraRequestsCount();
            echo json_encode(['success' => true, 'count' => $count]);
        } catch (Exception $e) {
            error_log("Get Pending Count Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'count' => 0]);
        }
    }

    public function getPendingRequests()
    {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        try {
            $requests = $this->userModel->getPendingMitraRequests();
            echo json_encode(['success' => true, 'requests' => $requests]);
        } catch (Exception $e) {
            error_log("Get Pending Requests Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error']);
        }
    }

    public function approveMitraRequest()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
            exit;
        }

        $userId = $_POST['user_id'] ?? null;
        $forumId = $_POST['forum_id'] ?? null;
        $ownerId = $_POST['owner_id'] ?? null;

        if (empty($userId)) {
            echo json_encode(['success' => false, 'message' => 'User ID tidak ditemukan']);
            exit;
        }

        try {
            // Update status user menjadi APPROVED
            $updateResult = $this->userModel->approvePendingUser($userId);
            
            if (!$updateResult) {
                echo json_encode(['success' => false, 'message' => 'Gagal approve user']);
                exit;
            }
            
            // Join ke forum jika ada forum_id
            if (!empty($forumId)) {
                $joinResult = $this->forumModel->joinForum($forumId, $userId);
                if (!$joinResult['success']) {
                    error_log("Gagal join forum: " . $joinResult['message']);
                }
            }

            // Get user data untuk email
            $userData = $this->userModel->getUserById($userId);
            
            if ($userData) {
                // Send email
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
                    $mail->addAddress($userData['EMAIL'], $userData['FULL_NAME']);

                    $mail->isHTML(true);
                    $mail->Subject = 'Akun Mitra SINERGI Telah Disetujui';

                    $resetLink = BASEURL . '/forget-password';

                    $mail->Body = "Halo <b>{$registrationData['FULL_NAME']}</b>,<br><br>"
                        . "Akun Anda untuk aplikasi SINERGI telah berhasil dibuat oleh administrator.<br><br>"
                        . "Anda dapat login menggunakan detail berikut:<br>"
                        . "<b>Email:</b> {$registrationData['EMAIL']}<br><br>"
                        . "Demi keamanan, silakan segera atur password Anda dengan mengklik tombol di bawah ini:<br><br>"

                        . "<a href='{$resetLink}' style='background-color: #2563eb; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-family: Arial, sans-serif;'>"
                        . "Atur Password Sekarang"
                        . "</a><br><br>"

                        . "Jika tombol di atas tidak berfungsi, silakan klik tautan berikut:<br>"
                        . "<a href='{$resetLink}'>{$resetLink}</a><br><br>"
                        . "Terima kasih.";

                    $mail->AltBody = "Akun Anda telah dibuat. Email: {$registrationData['EMAIL']}";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("PHPMailer Error: " . $mail->ErrorInfo);
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Akun mitra berhasil disetujui dan notifikasi email telah dikirim'
            ]);

        } catch (Exception $e) {
            error_log("Approve Mitra Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
        }
    }

    public function rejectMitraRequest()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $userId = $_POST['user_id'] ?? null;

        if (empty($userId)) {
            echo json_encode(['success' => false, 'message' => 'User ID tidak ditemukan']);
            exit;
        }

        try {
            // Delete pending user
            $result = $this->userModel->deletePendingUser($userId);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Request berhasil ditolak']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menolak request']);
            }
        } catch (Exception $e) {
            error_log("Reject Mitra Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
        }
    }
}
