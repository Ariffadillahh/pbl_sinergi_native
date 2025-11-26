<?php
require_once __DIR__ . '/../models/Groups/GroupChat.php';
require_once __DIR__ . '/../models/Groups/GroupChatMember.php';
require_once __DIR__ . '/../models/Groups/ChatMessage.php';
require_once __DIR__ . '/../models/Notif/NotificationModel.php';
require_once __DIR__ . '/../models/Posts/PostModel.php';
require_once __DIR__ . '/../models/Users/UserModel.php';

class GroupChatController
{
    private $groupChatModel;
    private $groupChatMemberModel;
    private $notificationModel;
    private $postModel;
    private $userModel;
    private $chatMessageModel;

    public function __construct()
    {
        $this->groupChatModel = new GroupChat();
        $this->groupChatMemberModel = new GroupChatMember();
        $this->notificationModel = new NotificationModel();
        $this->postModel = new PostModel();
        $this->userModel = new UserModel();
        $this->chatMessageModel = new ChatMessage();
    }

    public function index()
    {
        $joinedGroupChats = $this->groupChatModel->getGroupChatsByUserId($_SESSION['user_id']);
        $activeChatId = null;

        $contentView = __DIR__ . '/../views/groups/index.php';
        require_once __DIR__ . '/../views/groups/layout.php';
    }

    public function chat($id)
    {
        $groupChatId = $this->groupChatModel->findById($id);

        if (!$groupChatId) {
            header("Location: " . BASEURL . "/groups");
            exit;
        }

        $membersGroupChat = $this->groupChatMemberModel->findByGroupChatId($id);

        $isMember = false;
        foreach ($membersGroupChat as $member) {
            if ($member['USER_ID'] == $_SESSION['user_id']) {
                $isMember = true;
                break;
            }
        }

        if (!$isMember) {
            header("Location: " . BASEURL . "/groups");
            exit;
        }

        $currentUserId = $_SESSION['user_id'];

        $unreadCount = $this->groupChatModel->getUnreadCount($id, $currentUserId);

        if ($unreadCount > 0) {
            $this->groupChatModel->updateLastReadAt($id, $currentUserId);
        }

        function formatDatePretty($rawDate)
        {
            if (!$rawDate) return '-';

            $timestamp = strtotime($rawDate);
            if (!$timestamp) return $rawDate;

            return date("d F Y", $timestamp); 
        }

        $mediaPreview = $this->chatMessageModel->getGroupChatMediaPreview($id, 4);
        $joinedGroupChats = $this->groupChatModel->getGroupChatsByUserId($currentUserId);
        $activeChatId = $id;

        $contentView = __DIR__ . '/../views/groups/chat/index.php';
        require_once __DIR__ . '/../views/groups/layout.php';
    }

    public function create()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $groupChatName = trim($_POST['groupChatName'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $isPrivate = isset($_POST['isPrivate']) ? 1 : 0;

        if (empty($groupChatName) || empty($bio)) {
            echo json_encode(['success' => false, 'message' => 'Nama Group Chat dan Bio tidak boleh kosong.']);
            exit;
        }

        $photoPath = null;
        if (!empty($_FILES['groupChatPhoto']['name'])) {
            $id_sementara = uniqid();
            $targetDir = __DIR__ . '/../../storage/groups/photos/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = $id_sementara . "_" . basename($_FILES['groupChatPhoto']['name']);
            $targetFile = $targetDir . $fileName;
            if (move_uploaded_file($_FILES['groupChatPhoto']['tmp_name'], $targetFile)) {
                $photoPath = $fileName;
            }
        }

        $data = [
            'groupChatName' => $groupChatName,
            'bio'       => $bio,
            'isPrivate' => $isPrivate,
            'keyGroupChat'  => $_POST['keyGroupChat'] ?? null,
            'user_id'   => $_SESSION['user_id'],
            'photo'     => $photoPath
        ];

        $newGroupChatId = $this->groupChatModel->create($data);
        if ($newGroupChatId) {
            $response = ['success' => true, 'message' => 'Group berhasil dibuat!', 'redirectUrl' => BASEURL . "/groups/chat/" . $newGroupChatId];
        } else {
            $response = ['success' => false, 'message' => 'Gagal membuat Group.'];
        }
        echo json_encode($response);
        exit;
    }

    public function edit()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $groupChatId = $_POST['group_chat_id'] ?? null;
        $groupChatName = trim($_POST['groupChatName'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $isPrivate = isset($_POST['isPrivate']) ? 1 : 0;
        $keyGroupChat = $_POST['keyGroupChat'] ?? null;

        if (empty($groupChatId) || empty($groupChatName) || empty($bio)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak boleh kosong.']);
            exit;
        }

        $oldGroupChat = $this->groupChatModel->findById($groupChatId);
        if (!$oldGroupChat) {
            echo json_encode(['success' => false, 'message' => 'Group tidak ditemukan.']);
            exit;
        }

        if ($oldGroupChat['OWNER_ID'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki izin untuk mengedit group ini.']);
            exit;
        }

        $photoPath = $oldGroupChat['PATH_PHOTO'];
        if (!empty($_FILES['groupChatPhoto']['name'])) {
            $targetDir = __DIR__ . '/../../storage/groups/photos/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = uniqid() . "_" . basename($_FILES['groupChatPhoto']['name']);
            $targetFile = $targetDir . $fileName;
            if (move_uploaded_file($_FILES['groupChatPhoto']['tmp_name'], $targetFile)) {
                if (!empty($oldGroupChat['PATH_PHOTO']) && $oldGroupChat['PATH_PHOTO'] !== 'default.png') {
                    $oldFile = $targetDir . $oldGroupChat['PATH_PHOTO'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $photoPath = $fileName;
            }
        }

        $data = [
            'NAME' => $groupChatName,
            'ABOUT' => $bio,
            'IS_PRIVATE' => $isPrivate,
            'ACCESS_KEY' => $isPrivate ? $keyGroupChat : null,
            'PATH_PHOTO' => $photoPath,
        ];

        if ($this->groupChatModel->edit($groupChatId, $data)) {
            $response = ['success' => true, 'message' => 'Group berhasil diperbarui!', 'redirectUrl' => BASEURL . "/groups/chat/" . $groupChatId];
        } else {
            $response = ['success' => false, 'message' => 'Gagal memperbarui Group.'];
        }
        echo json_encode($response);
        exit;
    }

    public function delete()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $groupChatId = $_POST['group_chat_id'] ?? null;
        if (empty($groupChatId)) {
            echo json_encode(['success' => false, 'message' => 'Group ID tidak ditemukan.']);
            exit;
        }

        $groupChatToDelete = $this->groupChatModel->findById($groupChatId);
        if (!$groupChatToDelete || $groupChatToDelete['OWNER_ID'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki izin atau group tidak ditemukan.']);
            exit;
        }

        if ($this->groupChatModel->delete($groupChatId)) {
            $photoPath = $groupChatToDelete['PATH_PHOTO'] ?? null;
            if (!empty($photoPath) && $photoPath !== 'default.png') {
                $fullPath = __DIR__ . '/../../storage/groups/photos/' . $photoPath;
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            $response = ['success' => true, 'message' => 'Group berhasil dihapus!', 'redirectUrl' => BASEURL . "/groups"];
        } else {
            $response = ['success' => false, 'message' => 'Gagal menghapus Group.'];
        }
        echo json_encode($response);
        exit;
    }

    public function exit()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $groupChatId = $_POST['group_chat_id'] ?? null;
        if (empty($userId) || empty($groupChatId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            exit;
        }

        if ($this->groupChatModel->exitGroupChat($groupChatId, $userId)) {
            $response = ['success' => true, 'message' => 'Anda berhasil keluar dari group!', 'redirectUrl' => BASEURL . "/groups"];
        } else {
            $response = ['success' => false, 'message' => 'Gagal keluar dari group.'];
        }
        echo json_encode($response);
        exit;
    }

    public function search()
    {
        header('Content-Type: application/json');
        $keyword = $_GET['q'] ?? '';
        $userId = $_SESSION['user_id'] ?? null;
        if (trim($keyword) === '') {
            echo json_encode([]);
            exit;
        }
        $groupChat = $this->groupChatModel->searchByName($keyword, $userId);
        echo json_encode($groupChat);
        exit;
    }

public function join()
{
    ob_start();
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        ob_clean();
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ob_clean();
        header('Content-Type: application/json');
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $groupChatId = trim($_POST['group_chat_id'] ?? '');
    $accessKey = trim($_POST['access_key'] ?? '');

    if (empty($groupChatId)) {
        ob_clean();
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Group ID is required']);
        exit;
    }

    $result = $this->groupChatModel->joinGroupChat($userId, $groupChatId, $accessKey);

    ob_clean();
    header('Content-Type: application/json');

    if ($result['success']) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => $result['message'] ?? 'Successfully joined the group',
            'redirectUrl' => BASEURL . '/groups/chat/' . $groupChatId
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to join group'
        ]);
    }
    
    exit;
}

    public function joinViaInvite()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $groupChatId = $_POST['group_chat_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if (!$groupChatId || !$userId) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit;
        }

        if ($this->groupChatMemberModel->isMember($groupChatId, $userId)) {
            echo json_encode(['success' => true, 'message' => 'Already a member']);
            exit;
        }

        $insert = $this->groupChatMemberModel->insertMember($groupChatId, $userId);

        if ($insert) {
            echo json_encode([
                'success' => true,
                'redirect' => BASEURL . "/groups/chat/" . $groupChatId
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to join group']);
        }
    }


    public function reportGroupChatOrPost()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Metode request tidak valid.']);
            return;
        }
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Pengguna tidak terautentikasi.']);
            return;
        }

        $data = [
            'user_id' => $_SESSION['user_id'],
            'target_id' => $_POST['target_id'] ?? null,
            'target_type' => $_POST['target_type'] ?? null,
            'reason' => $_POST['reason'] ?? null,
            'other_reason_text' => $_POST['other_reason_text'] ?? null,
        ];


        $reportDescription = $data['reason'];
        if ($data['reason'] === 'other') {
            $reportDescription = "Lainnya: " . htmlspecialchars($data['other_reason_text']);
        }

        $reportData = [
            'user_id' => $data['user_id'],
            'target_id' => $data['target_id'],
            'target_type' => $data['target_type'],
            'reason' => $reportDescription,
        ];

        try {
            if ($this->groupChatModel->createReport($reportData)['success']) {
                echo json_encode(['success' => true, 'message' => 'Laporan Anda telah berhasil dikirim.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan laporan.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server.']);
        }
    }

    public function checkMembership()
    {
        header('Content-Type: application/json');
        $groupChatId = $_GET['group_chat_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if (!$groupChatId || !$userId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request.']);
            return;
        }

        $isMember = $this->groupChatMemberModel->isMember($groupChatId, $userId);
        echo json_encode(['is_member' => $isMember]);
    }

    public function kickMember()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $currentUserId = $_SESSION['user_id'];
        $groupChatId = $_POST['group_chat_id'] ?? null;
        $targetUserId = $_POST['user_id'] ?? null;

        if (!$groupChatId || !$targetUserId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing parameters']);
            exit;
        }

        $groupChat = $this->groupChatModel->findById($groupChatId);
        if (!$groupChat) {
            echo json_encode(['success' => false, 'message' => 'Group not found']);
            exit;
        }

        if ($groupChat['OWNER_ID'] !== $currentUserId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $removed = $this->groupChatMemberModel->removeMember($groupChatId, $targetUserId);

        if (!$removed) {
            echo json_encode(['success' => false, 'message' => 'Failed to remove member']);
            exit;
        }

        $this->notificationModel->addNotification(
            $targetUserId,
            $currentUserId,
            $groupChatId,
            "KICKED",
            ""
        );

        echo json_encode([
            'success' => true,
            'message' => 'Member kicked successfully'
        ]);
        exit;
    }

    public function searchUser()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $keyword = $_POST['keyword'] ?? '';

        if (strlen($keyword) < 2) {
            echo json_encode([]);
            exit;
        }

        $users = $this->postModel->searchUsers($keyword);

        echo json_encode($users);
    }

    public function addMember()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $ownerId = $_SESSION['user_id'];
        $groupChatId = $_POST['id'] ?? null;
        $targetUserId = $_POST['user_id'] ?? null;

        if (!$groupChatId || !$targetUserId) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit;
        }

        $groupChat = $this->groupChatModel->findById($groupChatId);
        if (!$groupChat) {
            echo json_encode(['success' => false, 'message' => 'Group not found']);
            exit;
        }

        if ($groupChat['OWNER_ID'] != $ownerId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        if ($this->groupChatMemberModel->isMember($groupChatId, $targetUserId)) {
            echo json_encode(['success' => false, 'message' => 'User already a member']);
            exit;
        }

        $this->notificationModel->addNotification(
            $targetUserId,
            $ownerId,
            $groupChatId,
            "INVITE_GROUP",
            "GROUP"
        );

        echo json_encode(['success' => true, 'message' => 'Member invited successfully']);
        exit;
    }

    public function getGroupChatInfo()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode(['error' => 'Missing id']);
            return;
        }

        $groupChat = $this->groupChatModel->findById($id);
        $owner = $this->userModel->getUsersById($groupChat['OWNER_ID']);
        $members = $this->groupChatMemberModel->findByGroupChatId($id);

        echo json_encode([
            "ID" => $groupChat['ID'],
            "NAME" => $groupChat['NAME'],
            "ABOUT" => $groupChat['ABOUT'],
            "PHOTO" => $groupChat['PATH_PHOTO'],
            "OWNER" => [
                "ID" => $owner['ID'],
                "NAME" => $owner['FULL_NAME'],
                "PHOTO" => $owner['PATH_PHOTO']
            ],
            "MEMBERS" => array_map(function ($m) {
                return [
                    "ID" => $m["USER_ID"],
                    "NAME" => $m["NAME"],
                    "PHOTO" => $m["PATH_PHOTO"]
                ];
            }, $members)
        ]);
    }

    public function detail($groupChatId)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $groupChat = $this->groupChatModel->findById($groupChatId);

        if (!$groupChat) {
            header('Location: ' . BASEURL . '/groups');
            exit;
        }
        $chatMessageModel = new ChatMessage();
        $messages = $chatMessageModel->getMessagesByGroupChatId($groupChatId);

        $mediaPreview = $chatMessageModel->getGroupChatMediaPreview($groupChatId, 8);

        $data = [
            'title' => 'Group - ' . $groupChat['TITLE'],
            'groupChat' => $groupChat,
            'messages' => $messages,
            'mediaPreview' => $mediaPreview
        ];

        require_once __DIR__ . '/../views/groups/detail.php';
    }
}
