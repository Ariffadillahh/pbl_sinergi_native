<?php

require_once __DIR__ . '/../models/Auth/SignIn.php';
require_once __DIR__ . '/../models/Forum/Forum.php';
require_once __DIR__ . '/DashboardOverview.php';
require_once __DIR__ . '/ReportController.php';
require_once __DIR__ . '/../models/Notif/NotificationModel.php';
require_once __DIR__ . '/../models/Groups/GroupChat.php';


use PHPMailer\PHPMailer\PHPMailer;


class DashboardController
{
    private $userModel;
    private $loginModel;
    private $overviewCount;
    private $reportController;
    private $forumModel;
    private $notificationModel;
    private $groupsModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->loginModel  = new SignInModel();
        $this->overviewCount = new overviewCount();
        $this->reportController = new ReportController();
        $this->forumModel = new ForumModel();
        $this->notificationModel = new NotificationModel();
        $this->groupsModel = new GroupChat();
    }

    public function index()
    {
        $anggotaCount = $this->overviewCount->countAnggota();
        $postCount    = $this->overviewCount->countPost();

        $forumCount     = $this->overviewCount->countForum();
        $totalForum     = $forumCount['TOTAL'];
        $forumNonActive = $forumCount['NONACTIVE'];

        $groupCount = $this->overviewCount->groupCount();

        $laporanData  = $this->overviewCount->countLaporan();
        $kasusCount   = $laporanData['CASES']; 
        $totalLaporan = $laporanData['TOTAL']; 

        $contentViewDashboard = __DIR__ . '/../views/dashboard/index.php';
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

    public function groups()
    {
        $limit = 6;

        $activeTab = $_GET['tab'] ?? 'my-groups';

        $mySearch = $_GET['my_search'] ?? '';
        $myPage = isset($_GET['my_page']) ? (int)$_GET['my_page'] : 1;
        $myOffset = ($myPage - 1) * $limit;

        $allSearch = $_GET['all_search'] ?? '';
        $allPage = isset($_GET['all_page']) ? (int)$_GET['all_page'] : 1;
        $allOffset = ($allPage - 1) * $limit;

        $myGroupsData = $this->groupsModel->getMyGroups($mySearch, $limit, $myOffset);
        $allGroupsData = $this->groupsModel->getAllGroupsPagination($allSearch, $limit, $allOffset);

        $data = [
            'activeTab' => $activeTab,

            'myGroups' => $myGroupsData['data'],
            'myTotal' => $myGroupsData['total'],
            'myPage' => $myPage,
            'mySearch' => $mySearch,
            'myTotalPages' => ceil($myGroupsData['total'] / $limit),
            'myStart' => $myGroupsData['total'] > 0 ? $myOffset + 1 : 0,
            'myEnd' => min($myOffset + $limit, $myGroupsData['total']),

            'groups' => $allGroupsData['data'],
            'allTotal' => $allGroupsData['total'],
            'allPage' => $allPage,
            'allSearch' => $allSearch,
            'allTotalPages' => ceil($allGroupsData['total'] / $limit),
            'allStart' => $allGroupsData['total'] > 0 ? $allOffset + 1 : 0,
            'allEnd' => min($allOffset + $limit, $allGroupsData['total'])
        ];

        foreach ($data as $key => $value) {
            $$key = $value;
        }

        $contentViewDashboard =  __DIR__ . '/../views/dashboard/group/index.php';
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
                echo json_encode(['success' => false, 'message' => "Field '" . ucwords($fieldName) . "' cannot be null."]);
                exit;
            }
        }

        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

        $userByEmail = $this->loginModel->getUserByUsernameOrEmail($email);
        if ($userByEmail) {
            echo json_encode(['success' => false, 'message' => 'Email already exists.']);
            exit;
        }

        $personalNumber = $_POST['personal-number'];
        $userByPN = $this->loginModel->getUserByUsernameOrEmail($personalNumber);
        if ($userByPN) {
            echo json_encode(['success' => false, 'message' => 'NIM/NIP already exists.']);
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
                    'message' => 'Failed to save data to the database. Please try again.'
                ]);
                exit;
            }
        } catch (Exception $dbException) {
            error_log("DB Create Error: " . $dbException->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred in the database while creating an account.'
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
            $mail->Subject = 'Your SINERGI account has been created.';

            $resetLink = BASEURL . '/forget-password';

            $mail->Body = "Hello <b>{$registrationData['FULL_NAME']}</b>,<br><br>"
                . "Your account for the SINERGI application has been successfully created by the administrator.<br><br>"
                . "You can log in using the following details:<br>"
                . "<b>Email:</b> {$registrationData['EMAIL']}<br><br>"
                . "For security reasons, please set your password immediately by clicking the button below:<br><br>"

                . "<a href='{$resetLink}' style='background-color: #2563eb; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-family: Arial, sans-serif;'>"
                . "Set Your Password Now"
                . "</a><br><br>"

                . "If the button above does not work, please click the following link:<br>"
                . "<a href='{$resetLink}'>{$resetLink}</a><br><br>"
                . "Thank you.";

            $mail->AltBody = "Your account has been created. Email: {$registrationData['EMAIL']}";

            $mail->send();

            echo json_encode([
                'success' => true,
                'message' => 'A new account has been successfully created and a notification email has been sent.'
            ]);
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);

            echo json_encode([
                'success' => true,
                'message' => 'Account successfully created, but notification email failed to send. Error: ' . $mail->ErrorInfo
            ]);
        }
    }

    public function accAccoutByAdmin()
    {
        if (!isset($_POST['id_user'])) {
            echo json_encode(['success' => false, 'message' => 'User ID not found.']);
            return;
        }

        $idUser = $_POST['id_user'];

        $userPending = $this->userModel->getPendingRequestsById($idUser);

        if (!$userPending) {
            echo json_encode(['success' => false, 'message' => 'User data not found or status is not pending.']);
            return;
        }

        $updateSuccess = $this->userModel->approvePendingUser($idUser);

        if (!$updateSuccess) {
            echo json_encode(['success' => false, 'message' => 'Failed to activate account in the database.']);
            return;
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

            $mail->addAddress($userPending['EMAIL'], $userPending['FULL_NAME']);

            $mail->isHTML(true);
            $mail->Subject = 'Your SINERGI account has been created.';

            $resetLink = BASEURL . '/forget-password';

            $mail->Body = "Hello <b>{$userPending['FULL_NAME']}</b>,<br><br>"
                . "Your account for the SINERGI application has been successfully created and activated by the administrator.<br><br>"
                . "You can log in using the following details:<br>"
                . "<b>Email:</b> {$userPending['EMAIL']}<br><br>"
                . "For security reasons, please set your password immediately by clicking the button below.:<br><br>"
                . "<a href='{$resetLink}' style='background-color: #2563eb; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-family: Arial, sans-serif;'>"
                . "Set Your Password Now"
                . "</a><br><br>"
                . "If the button above does not work, please click the following link:<br>"
                . "<a href='{$resetLink}'>{$resetLink}</a><br><br>"
                . "Thank you.";

            $mail->AltBody = "Your account has been activated. Email: {$userPending['EMAIL']}. Please reset your password.";

            $mail->send();

            echo json_encode([
                'success' => true,
                'message' => 'Your account has been successfully activated and a notification email has been sent.'
            ]);
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);

            echo json_encode([
                'success' => true,
                'message' => 'The account has been successfully activated, but the notification email failed to send. Please contact the user manually..'
            ]);
        }
    }

    public function updateRoleByAdmin()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'You dont have permission for this action..']);
            exit;
        }

        $userId = $_POST['user_id'] ?? null;
        $newRole = $_POST['role'] ?? null;

        if (empty($userId) || empty($newRole)) {
            echo json_encode(['success' => false, 'message' => 'Incomplete data. New User ID and Role must be filled in.']);
            exit;
        }

        $validRoles = ['ADMIN', 'MAHASISWA', 'DOSEN', 'ALUMNI', 'MITRA'];
        if (!in_array($newRole, $validRoles)) {
            echo json_encode(['success' => false, 'message' => 'The selected role is invalid.']);
            exit;
        }

        if (isset($_SESSION['user_id']) && $userId === $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'You cannot change your own account role.']);
            exit;
        }

        try {
            $isSuccess = $this->userModel->updateUserRoleById($userId, $newRole);

            if ($isSuccess) {
                echo json_encode(['success' => true, 'message' => 'User role successfully updated.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update role. Data did not change or ID not found.']);
            }
        } catch (Exception $e) {
            error_log("Update Role Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'An error occurred on the server.']);
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
            error_log("Sent to user" . $userId);
        }

        if ($result['success']) {
            echo json_encode(['success' => true, 'message' => $result['message']]);
            error_log("Successfully added" . $userId);
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

    public function approveAccountRequest()
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

        if (empty($userId)) {
            echo json_encode(['success' => false, 'message' => 'User ID not found']);
            exit;
        }

        try {
            $updateResult = $this->userModel->approvePendingUser($userId);

            if (!$updateResult) {
                echo json_encode(['success' => false, 'message' => 'Failed to approve user']);
                exit;
            }

            $userData = $this->userModel->getUserById($userId);

            if ($userData) {
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
                    $mail->Subject = 'SINERGI Partner Account Approved';

                    $resetLink = BASEURL . '/forget-password';


                    $mail->Body = "Hello <b>{$userData['FULL_NAME']}</b>,<br><br>"
                        . "Your account for the SINERGI application has been successfully created by the administrator.<br><br>"
                        . "You can log in using the following details:<br>"
                        . "<b>Email:</b> {$userData['EMAIL']}<br><br>" // Ubah di sini juga
                        . "For security reasons, please set your password immediately by clicking the button below.:<br><br>"

                        . "<a href='{$resetLink}' style='background-color: #2563eb; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold; font-family: Arial, sans-serif;'>"
                        . "Set Your Password Now"
                        . "</a><br><br>"

                        . "If the button above does not work, please click the following link:<br>"
                        . "<a href='{$resetLink}'>{$resetLink}</a><br><br>"
                        . "Thank you.";

                    $mail->AltBody = "Your account has been created. Email: {$userData['EMAIL']}";

                    $mail->send();
                } catch (Exception $e) {
                    error_log("PHPMailer Error: " . $mail->ErrorInfo);
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'The partner account has been successfully approved and an email notification has been sent.'
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
            echo json_encode(['success' => false, 'message' => 'User ID not found']);
            exit;
        }

        try {
            // Delete pending user
            $result = $this->userModel->deletePendingUser($userId);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Request successfully denied']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to decline request']);
            }
        } catch (Exception $e) {
            error_log("Reject Mitra Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'A server error has occurred']);
        }
    }

    public function reportCount()
    {
        header('Content-Type: application/json');

        $counts = $this->overviewCount->getReportCounts();

        if ($counts) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'forum'     => (int)$counts['total_forum'],
                    'postingan' => (int)$counts['total_postingan'],
                    'group'     => (int)$counts['total_group']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error fatch data'
            ]);
        }
        exit;
    }
}
