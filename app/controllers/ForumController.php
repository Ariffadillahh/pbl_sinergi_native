<?php
require_once __DIR__ . '/../models/Forum/Forum.php';
require_once __DIR__ . '/../models/Forum/TopicModel.php';
require_once __DIR__ . '/../models/Notif/NotificationModel.php';
require_once __DIR__ . '/../models/Auth/SignIn.php';
require_once __DIR__ . '/../models/Auth/SignUp.php';

class forumController
{

    private $forumModel;
    private $topicModel;
    private $notificationModel;
    private $loginModel;
    private $userModel;

    public function __construct()
    {
        $this->forumModel = new ForumModel();
        $this->topicModel = new TopicModel();
        $this->notificationModel = new NotificationModel();
        $this->loginModel = new SignInModel();
        $this->userModel = new User();
    }

    public function index()
    {
        $myUserId = $_SESSION['user_id'] ?? null;

        $userRole = $_SESSION['role'] ?? '';

        if (in_array($userRole, ['MITRA', 'ALUMNI'])) {
            $filter = 'joined';
            $search = '';
        } else {
            $filter = $_GET['filter'] ?? 'all';
            $search = $_GET['search'] ?? '';
        }

        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit  = 9;

        if ($page < 1) $page = 1;

        $offset = ($page - 1) * $limit;

        $forums = $this->forumModel->getForumsWithFilter($myUserId, $filter, $search, $limit, $offset);

        $totalForums = $this->forumModel->countForumsWithFilter($myUserId, $filter, $search);
        $totalPages  = ceil($totalForums / $limit);

        $contentViewForum = __DIR__ . '/../views/forum/index.php';
        require_once __DIR__ . '/../views/forum/layout.php';
    }

    public function checkMembership()
    {
        header('Content-Type: application/json');
        $forumId = $_GET['forum_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if (!$forumId || !$userId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request.']);
            return;
        }

        $isMember = $this->forumModel->isMember($forumId, $userId);
        echo json_encode(['is_member' => $isMember]);
    }


    public function forumById($forumId)
    {
        $forumById = $this->forumModel->getForumById($forumId);

        if (!$forumById) {
            header("Location: " . BASEURL . "/forums");
            exit;
        }

        $currentUserId = $_SESSION['user_id'] ?? null;
        $currentUserRole = $_SESSION['role'] ?? '';

        $forumOwnerId = $forumById['OWNER_ID'] ?? $forumById['USER_ID'] ?? null;

        $canUnpin = false;

        if ($currentUserId) {
            if ($currentUserRole === 'ADMIN' || $currentUserRole === 'DOSEN') {
                $canUnpin = true;
            } elseif ($currentUserId == $forumOwnerId) {
                $canUnpin = true;
            }
        }

        $membersForum = $this->forumModel->getForumMembers($forumId);
        $isMember = false;

        if ($currentUserId) {
            $isMember = $this->forumModel->isMember($forumId, $currentUserId);
        }

        if ((int)$forumById['IS_PRIVATE'] === 1 && !$isMember) {
            header("Location: " . BASEURL . "/forums");
            exit;
        }

        $postLimit = $isMember ? null : 1;

        $pinned_topics = $this->topicModel->getPinnedTopics($forumId);
        $topics = $this->topicModel->getTopicsByForumId($forumId);

        $data = [
            'forumById'     => $forumById,
            'membersForum'  => $membersForum,
            'isMember'      => $isMember,
            'postLimit'     => $postLimit,
            'pinned_topics' => $pinned_topics,
            'topics'        => $topics,
            'can_unpin'     => $canUnpin
        ];

        extract($data);

        $contentViewForum = __DIR__ . '/../views/forum/detail/index.php';
        require_once __DIR__ . '/../views/forum/layout.php';
    }

    public function getForumInfo()
    {
        header('Content-Type: application/json');

        // Add session check
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $forumId = $_GET['id'] ?? null;

        if (!$forumId) {
            echo json_encode(['success' => false, 'message' => 'Forum ID required']);
            exit;
        }

        try {
            $forum = $this->forumModel->getForumById($forumId);

            if (!$forum) {
                echo json_encode(['success' => false, 'message' => 'Forum not found']);
                exit;
            }

            $members = $this->forumModel->getForumMembers($forumId);

            $response = [
                'success' => true,
                'ID' => $forum['ID'],
                'NAME' => $forum['NAME'],
                'ABOUT' => $forum['ABOUT'],
                'PHOTO' => $forum['PATH_PHOTO'],
                'IS_PRIVATE' => $forum['IS_PRIVATE'],
                'OWNER' => [
                    'NAME' => $forum['OWNER_NAME'],
                    'PHOTO' => $forum['PATH_PHOTO_OWNER']
                ],
                'MEMBERS' => array_map(function ($member) {
                    return [
                        'NAME' => $member['FULL_NAME'],
                        'PHOTO' => $member['PATH_PHOTO']
                    ];
                }, $members),
                'TOTAL_MEMBERS' => $forum['TOTAL_MEMBERS']
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function createForum()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized: Silakan login terlebih dahulu.']);
            return;
        }

        $name = isset($_POST['NAME']) ? trim($_POST['NAME']) : '';
        $about = isset($_POST['ABOUT']) ? trim($_POST['ABOUT']) : '';
        $isPrivate = isset($_POST['IS_PRIVATE']) ? intval($_POST['IS_PRIVATE']) : 0;
        $accessKey = isset($_POST['ACCESS_KEY']) ? trim($_POST['ACCESS_KEY']) : null;
        $ownerId = $_SESSION['user_id'];

        if (empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama forum wajib diisi.']);
            return;
        }

        if ($isPrivate == 1 && empty($accessKey)) {
            echo json_encode(['status' => 'error', 'message' => 'Forum private memerlukan kunci akses.']);
            return;
        }

        $pathPhoto = null;
        $pathThumbnail = null;

        $uploadDir = __DIR__ . '/../../storage/forums/photos/';
        $uploadDirThumb = __DIR__ . '/../../storage/forums/thumbnail/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!is_dir($uploadDirThumb)) {
            mkdir($uploadDirThumb, 0777, true);
        }

        if (isset($_FILES['PATH_PHOTO']) && $_FILES['PATH_PHOTO']['error'] === UPLOAD_ERR_OK) {
            $pathPhoto = $this->uploadFile($_FILES['PATH_PHOTO'], $uploadDir, 'profile');
            if (!$pathPhoto) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal upload foto profil (format/size salah).']);
                return;
            }
        }

        if (isset($_FILES['PATH_THUMBNAIL']) && $_FILES['PATH_THUMBNAIL']['error'] === UPLOAD_ERR_OK) {
            $pathThumbnail = $this->uploadFile($_FILES['PATH_THUMBNAIL'], $uploadDirThumb, 'banner');
            if (!$pathThumbnail) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal upload banner (format/size salah).']);
                return;
            }
        }

        $data = [
            'NAME' => $name,
            'ABOUT' => $about,
            'IS_PRIVATE' => $isPrivate,
            'ACCESS_KEY' => ($isPrivate == 1) ? $accessKey : null,
            'OWNER_ID' => $ownerId,
            'PATH_PHOTO' => $pathPhoto,
            'PATH_THUMBNAIL' => $pathThumbnail
        ];

        try {
            $result = $this->forumModel->createForum($data);

            if ($result['success']) {

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Forum berhasil dibuat!',
                    'id' => $result['ID']
                ]);
            } else {

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan ke database.',
                    'error' => $result['error']
                ]);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
        }
    }

    public function update()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $forumId = $_POST['ID'] ?? null;
        if (!$forumId) {
            echo json_encode(['success' => false, 'message' => 'ID Forum tidak ditemukan']);
            exit;
        }

        $oldForumData = $this->forumModel->getForumById($forumId);
        if (!$oldForumData) {
            echo json_encode(['success' => false, 'message' => 'Forum tidak ditemukan']);
            exit;
        }

        if ($oldForumData['OWNER_ID'] !== $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Anda bukan pemilik forum ini!']);
            exit;
        }

        $isPrivate = $_POST['IS_PRIVATE'] ?? 0;

        $updateData = [
            'NAME'       => $_POST['NAME'],
            'ABOUT'      => $_POST['ABOUT'],
            'IS_PRIVATE' => $isPrivate,
            'ACCESS_KEY' => ($isPrivate == 1) ? $_POST['ACCESS_KEY'] : null
        ];

        if (isset($_FILES['PATH_PHOTO']) && $_FILES['PATH_PHOTO']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['PATH_PHOTO'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = 'forum_profile_' . uniqid() . '.' . $ext;
            $uploadDir = __DIR__ . '/../../storage/forums/photos/';

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
                $updateData['PATH_PHOTO'] = $newFileName;

                if (!empty($oldForumData['PATH_PHOTO'])) {
                    $oldFile = $uploadDir . $oldForumData['PATH_PHOTO'];
                    if (file_exists($oldFile)) unlink($oldFile);
                }
            }
        }

        if (isset($_FILES['PATH_THUMBNAIL']) && $_FILES['PATH_THUMBNAIL']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['PATH_THUMBNAIL'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = 'forum_banner_' . uniqid() . '.' . $ext;
            $uploadDir = __DIR__ . '/../../storage/forums/thumbnail/';

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
                $updateData['PATH_THUMBNAIL'] = $newFileName;

                if (!empty($oldForumData['PATH_THUMBNAIL'])) {
                    $oldFile = $uploadDir . $oldForumData['PATH_THUMBNAIL'];
                    if (file_exists($oldFile)) unlink($oldFile);
                }
            }
        }

        if ($this->forumModel->updateForum($forumId, $updateData)) {
            echo json_encode(['success' => true, 'message' => 'Perubahan berhasil disimpan!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan ke database.']);
        }
    }

    public function delete()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $forumId = $_POST['ID'] ?? null;
        if (!$forumId) {
            echo json_encode(['success' => false, 'message' => 'ID Forum tidak ditemukan']);
            exit;
        }

        $forum = $this->forumModel->getForumById($forumId);

        if (!$forum) {
            echo json_encode(['success' => false, 'message' => 'Forum tidak ditemukan']);
            exit;
        }

        if ($forum['OWNER_ID'] !== $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Anda bukan pemilik forum ini!']);
            exit;
        }

        $assets = $this->forumModel->getAllForumAssets($forumId);

        if ($this->forumModel->deleteForum($forumId)) {

            if (!empty($assets['forum_thumb'])) {
                $path = __DIR__ . '/../../storage/forums/thumbnail/' . $assets['forum_thumb'];
                if (file_exists($path)) unlink($path);
            }

            if (!empty($assets['forum_photo'])) {
                $path = __DIR__ . '/../../storage/forums/photos/' . $assets['forum_photo'];
                if (file_exists($path)) unlink($path);
            }

            if (!empty($assets['topic_media'])) {
                foreach ($assets['topic_media'] as $mediaFile) {
                    $path = __DIR__ . '/../../storage/topics/media/' . $mediaFile;
                    if (file_exists($path)) unlink($path);
                }
            }

            echo json_encode(['success' => true, 'message' => 'Forum dan seluruh isinya berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus data dari database']);
        }
    }

    private function uploadFile($file, $targetDir, $prefix)
    {
        $allowedExts = ['jpg', 'jpeg', 'png'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExts)) {
            return false;
        }

        if ($fileSize > 2 * 1024 * 1024) {
            return false;
        }

        $newFileName = $prefix . '_' . uniqid() . '.' . $fileExt;
        $targetPath = $targetDir . $newFileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            return $newFileName;
        }

        return false;
    }

    public function joinForum()
    {
        ob_clean();
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["success" => false, "message" => "Sesi habis, silakan login kembali."]);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;
        $key     = trim($_POST['key'] ?? '');

        if (empty($forumId)) {
            echo json_encode(["success" => false, "message" => "Forum ID hilang/kosong."]);
            exit;
        }

        $forum = $this->forumModel->getForumById($forumId);

        if (!$forum) {
            echo json_encode(["success" => false, "message" => "Forum tidak ditemukan di database."]);
            exit;
        }

        if ($this->forumModel->isMember($forumId, $_SESSION['user_id'])) {
            echo json_encode(["success" => false, "message" => "Anda sudah bergabung di forum ini."]);
            exit;
        }

        if ((int)$forum['IS_PRIVATE'] === 1) {
            if (empty($key)) {
                echo json_encode(["success" => false, "message" => "Key wajib diisi untuk forum private!"]);
                exit;
            }
            if ($key !== $forum['ACCESS_KEY']) {
                echo json_encode(["success" => false, "message" => "Access Key salah!"]);
                exit;
            }
        }

        if ($this->forumModel->addMember($forumId, $_SESSION['user_id'])) {
            echo json_encode(["success" => true, "message" => "Berhasil bergabung!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menyimpan data ke database."]);
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

        $forumId = $_POST['forum_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;

        if (!$forumId || !$userId) {
            echo json_encode(['success' => false, 'message' => 'Missing parameters']);
            exit;
        }

        if ($this->forumModel->isMember($forumId, $userId)) {
            echo json_encode([
                'success' => true,
                'redirect' => BASEURL . "/forum/" . $forumId
            ]);
            exit;
        }

        $insert = $this->forumModel->addMember($forumId, $userId);

        if ($insert) {
            echo json_encode([
                'success' => true,
                'redirect' => BASEURL . "/forum/" . $forumId
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to join forum']);
        }
        exit;
    }

    public function leaveForum()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["success" => false, "message" => "Sesi habis, silakan login kembali."]);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;

        if (empty($forumId)) {
            echo json_encode(["success" => false, "message" => "Forum ID hilang/kosong."]);
            exit;
        }

        if (!$this->forumModel->isMember($forumId, $_SESSION['user_id'])) {
            echo json_encode(["success" => false, "message" => "Anda bukan anggota forum ini."]);
            exit;
        }

        if ($this->forumModel->removeMember($forumId, $_SESSION['user_id'])) {
            echo json_encode(["success" => true, "message" => "Berhasil keluar dari forum."]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menghapus data dari database."]);
        }
        exit;
    }

    public function getAssetsJson()
    {
        $forumId = isset($_GET['forum_id']) ? $_GET['forum_id'] : null;

        if (!$forumId) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Forum ID required']);
            exit;
        }

        $data = $this->forumModel->getGalleryMediaByForum($forumId);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
        exit;
    }

    public function searchAvailableUsers()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        // Accept both GET and POST
        $forumId = $_GET['forum_id'] ?? $_POST['forum_id'] ?? null;
        $search = $_GET['search'] ?? $_POST['search'] ?? '';

        if (!$forumId) {
            echo json_encode(['success' => false, 'message' => 'Forum ID required']);
            exit;
        }

        // Verify user has access to this forum
        $forum = $this->forumModel->getForumById($forumId);
        if (!$forum || $forum['OWNER_ID'] !== $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Only forum owner can search users']);
            exit;
        }

        // Search for users not already in the forum
        $users = $this->forumModel->searchNonMembers($forumId, $search);

        echo json_encode([
            'success' => true,
            'users' => $users
        ]);
        exit;
    }

    public function getReqForum()
    {
        header('Content-Type: application/json');

        if (!isset($_GET['forum_id'])) {
            echo json_encode([]);
            return;
        }

        $forumId = $_GET['forum_id'];

        try {
            $requests = $this->forumModel->getPendingRequests($forumId);

            echo json_encode($requests);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    public function updateReqForum()
    {
        header('Content-Type: application/json');

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['id']) || !isset($data['status'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            return;
        }

        $requestId = $data['id'];
        $statusAction = $data['status']; // 'accepted' atau 'rejected'

        try {
            $result = false;

            if ($statusAction === 'accepted') {
                // Update status jadi JOINED
                $result = $this->forumModel->acceptMember($requestId);
            } elseif ($statusAction === 'rejected') {
                // Hapus row karena constraint hanya boleh JOINED/PENDING
                $result = $this->forumModel->rejectMember($requestId);
            }

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal update database']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function reqJoin()
    {
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $forumId = $input['forum_id'] ?? null;
        $userId  = $_SESSION['user_id'];

        if (!$forumId) {
            echo json_encode(['success' => false, 'message' => 'Forum ID tidak valid.']);
            exit;
        }

        try {
            $result = $this->forumModel->sendJoinRequest($forumId, $userId);
            echo json_encode($result);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    public function addMemberByOwner()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $ownerId = $_SESSION['user_id'];
        $forumId = $_POST['forum_id'] ?? null;
        $targetUserId = $_POST['user_id'] ?? null;

        if (!$forumId || !$targetUserId) {
            echo json_encode(['success' => false, 'message' => 'Forum ID and User ID required']);
            exit;
        }

        // Verify user is the owner
        $forum = $this->forumModel->getForumById($forumId);
        if (!$forum || $forum['OWNER_ID'] !== $ownerId) {
            echo json_encode(['success' => false, 'message' => 'Only forum owner can add members']);
            exit;
        }

        // Check if user is already a member
        if ($this->forumModel->isMember($forumId, $targetUserId)) {
            echo json_encode(['success' => false, 'message' => 'User is already a member']);
            exit;
        }

        // Send notification instead of directly adding
        $this->notificationModel->addNotification(
            $targetUserId,
            $ownerId,
            $forumId,
            "INVITE_FORUM",
            "FORUM"
        );

        echo json_encode(['success' => true, 'message' => 'Member invited successfully']);
        exit;
    }

    public function removeMemberByOwner()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;
        $userId = $_POST['user_id'] ?? null;

        if (!$forumId || !$userId) {
            echo json_encode(['success' => false, 'message' => 'Forum ID and User ID required']);
            exit;
        }

        // Verify user is the owner
        $forum = $this->forumModel->getForumById($forumId);
        if (!$forum || $forum['OWNER_ID'] !== $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Only forum owner can remove members']);
            exit;
        }

        // Cannot remove the owner
        if ($userId === $forum['OWNER_ID']) {
            echo json_encode(['success' => false, 'message' => 'Cannot remove forum owner']);
            exit;
        }

        // Remove member
        if ($this->forumModel->removeMember($forumId, $userId)) {
            echo json_encode(['success' => true, 'message' => 'Member removed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove member']);
        }
        exit;
    }

    public function ownerRequestAccount()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;
        $role = $_POST['role'] ?? '';
        $namaLengkap = trim($_POST['nama_lengkap'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $personalNumber = trim($_POST['personal_number'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($forumId) || empty($namaLengkap) || empty($username) || empty($personalNumber) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
            exit;
        }

        $forum = $this->forumModel->getForumById($forumId);
        if (!$forum || $forum['OWNER_ID'] !== $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Hanya owner forum yang dapat membuat request']);
            exit;
        }


        if ($this->loginModel->getUserByUsernameOrEmail($email)) {
            echo json_encode(['success' => false, 'message' => 'Email sudah terdaftar']);
            exit;
        }

        if ($this->loginModel->getUserByUsernameOrEmail($username)) {
            echo json_encode(['success' => false, 'message' => 'Username sudah digunakan']);
            exit;
        }

        if ($this->loginModel->getUserByUsernameOrEmail($personalNumber)) {
            echo json_encode(['success' => false, 'message' => 'Nomor mitra sudah terdaftar']);
            exit;
        }

        try {


            $userId = uniqid();
            $userData = [
                'ID'              => $userId,
                'USERNAME'        => htmlspecialchars($username, ENT_QUOTES, 'UTF-8'),
                'PERSONAL_NUMBER' => htmlspecialchars($personalNumber, ENT_QUOTES, 'UTF-8'),
                'FULL_NAME'       => htmlspecialchars($namaLengkap, ENT_QUOTES, 'UTF-8'),
                'EMAIL'           => $email,
                'PASSWORD'        => password_hash(bin2hex(random_bytes(6)), PASSWORD_BCRYPT),
                'ROLE'            => $role,
                'STATUS'          => 'PENDING',
            ];

            $result = $this->userModel->createPendingMitraAllumniRequest($userData);

            if ($result) {
                $memberAdded = $this->forumModel->addPendingMemberToForum($forumId, $userId);

                if ($memberAdded) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Request berhasil dikirim. Akun mitra akan otomatis bergabung ke forum setelah disetujui admin.'
                    ]);
                } else {
                    $this->userModel->deletePendingUser($userId);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Gagal menambahkan mitra ke forum'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengirim request'
                ]);
            }
        } catch (Exception $e) {
            error_log("Request Mitra Error: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public function updateTopic()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $topicId = $_POST['topic_id'] ?? null;
        $content = $_POST['content'] ?? '';
        $deletedMedia = isset($_POST['deleted_media']) ? json_decode($_POST['deleted_media'], true) : [];

        if (!$topicId) {
            echo json_encode(['success' => false, 'message' => 'Topic ID tidak ditemukan']);
            exit;
        }

        // Cek kepemilikan topic
        if (!$this->topicModel->isTopicOwner($topicId, $_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Anda bukan pemilik topic ini']);
            exit;
        }

        // Hitung total media yang tersisa
        $currentMedia = $this->topicModel->getMediaByTopicId($topicId);
        $remainingMedia = count($currentMedia) - count($deletedMedia);

        // Hitung media baru
        $newMediaCount = isset($_FILES['new_media']) ? count($_FILES['new_media']['name']) : 0;
        $totalMedia = $remainingMedia + $newMediaCount;

        if ($totalMedia > 5) {
            echo json_encode(['success' => false, 'message' => 'Maksimal 5 media per topic']);
            exit;
        }

        // Handle upload media baru
        $uploadedFiles = [];
        if (isset($_FILES['new_media']) && !empty($_FILES['new_media']['name'][0])) {
            $uploadResult = $this->handleMultipleFileUpload($_FILES['new_media']);

            if (!$uploadResult['success']) {
                echo json_encode(['success' => false, 'message' => $uploadResult['message']]);
                exit;
            }

            $uploadedFiles = $uploadResult['files'];
        }

        $result = $this->topicModel->updateTopic($topicId, $content, $uploadedFiles, $deletedMedia);

        if ($result['status']) {
            echo json_encode(['success' => true, 'message' => $result['message']]);
        } else {
            foreach ($uploadedFiles as $file) {
                $filePath = __DIR__ . '/../../storage/forums/topics/' . $file['path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
        exit;
    }


    private function handleMultipleFileUpload($files)
    {
        $uploadDir = __DIR__ . '/../../storage/forums/topics/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $allowedFileTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip'
        ];

        $uploadedFiles = [];
        $fileCount = count($files['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $fileName = $files['name'][$i];
            $fileTmpName = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            $fileMimeType = mime_content_type($fileTmpName);

            // Validasi tipe file
            if (!in_array($fileMimeType, array_merge($allowedImageTypes, $allowedFileTypes))) {
                return ['success' => false, 'message' => "Tipe file tidak diizinkan: $fileName"];
            }

            // Validasi ukuran (10MB max)
            if ($fileSize > 10 * 1024 * 1024) {
                return ['success' => false, 'message' => "File terlalu besar: $fileName (max 10MB)"];
            }

            // Tentukan tipe media
            $mediaType = in_array($fileMimeType, $allowedImageTypes) ? 'IMAGE' : 'FILE';

            // Generate nama file unik
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $fileExtension;
            $targetPath = $uploadDir . $newFileName;

            // Upload file
            if (!move_uploaded_file($fileTmpName, $targetPath)) {
                return ['success' => false, 'message' => "Gagal upload file: $fileName"];
            }

            $uploadedFiles[] = [
                'path' => $newFileName,
                'type' => $mediaType,
                'original_filename' => $fileName
            ];
        }

        return ['success' => true, 'files' => $uploadedFiles];
    }
}
