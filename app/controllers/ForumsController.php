<?php
require_once __DIR__ . '/../models/Forums/Forum.php';
require_once __DIR__ . '/../models/Forums/ForumMember.php';
require_once __DIR__ . '/../models/Forums/ChatMessage.php';

class ForumsController
{
    private $forumModel;
    private $forumMemberModel;

    public function __construct()
    {
        $this->forumModel = new Forum();
        $this->forumMemberModel = new ForumMember();
    }

    public function index()
    {
        $joinedForums = $this->forumModel->getForumsByUserId($_SESSION['user_id']);
        $activeChatId = null;

        $contentView = __DIR__ . '/../views/forums/index.php';
        require_once __DIR__ . '/../views/forums/layout.php';
    }

    public function chat($id)
    {
        $forumByid = $this->forumModel->findById($id);

        if (!$forumByid) {
            header("Location: " . BASEURL . "/forums");
            exit;
        }

        $membersForum = $this->forumMemberModel->findByForumId($id);
        $isMember = false;
        foreach ($membersForum as $member) {
            if ($member['USER_ID'] == $_SESSION['user_id']) {
                $isMember = true;
                break;
            }
        }

        if (!$isMember) {
            header("Location: " . BASEURL . "/forums");
            exit;
        }

        $joinedForums = $this->forumModel->getForumsByUserId($_SESSION['user_id']);
        $activeChatId = $id;

        $contentView = __DIR__ . '/../views/forums/chat/index.php';
        require_once __DIR__ . '/../views/forums/layout.php';
    }

    public function create()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $forumName = trim($_POST['forumName'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $isPrivate = isset($_POST['isPrivate']) ? 1 : 0;

        if (empty($forumName) || empty($bio)) {
            echo json_encode(['success' => false, 'message' => 'Nama Forum dan Bio tidak boleh kosong.']);
            exit;
        }

        $photoPath = null;
        if (!empty($_FILES['forumPhoto']['name'])) {
            $id_sementara = uniqid();
            $targetDir = __DIR__ . '/../../storage/forums/photos/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = $id_sementara . "_" . basename($_FILES['forumPhoto']['name']);
            $targetFile = $targetDir . $fileName;
            if (move_uploaded_file($_FILES['forumPhoto']['tmp_name'], $targetFile)) {
                $photoPath = $fileName;
            }
        }

        $data = [
            'forumName' => $forumName,
            'bio'       => $bio,
            'isPrivate' => $isPrivate,
            'keyForum'  => $_POST['keyForum'] ?? null,
            'user_id'   => $_SESSION['user_id'],
            'photo'     => $photoPath
        ];

        $newForumId = $this->forumModel->create($data);
        if ($newForumId) {
            $response = ['success' => true, 'message' => 'Forum berhasil dibuat!', 'redirectUrl' => BASEURL . "/forums/chat/" . $newForumId];
        } else {
            $response = ['success' => false, 'message' => 'Gagal membuat forum.'];
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

        $forumId = $_POST['forum_id'] ?? null;
        $forumName = trim($_POST['forumName'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $isPrivate = isset($_POST['isPrivate']) ? 1 : 0;
        $keyForum = $_POST['keyForum'] ?? null;

        if (empty($forumId) || empty($forumName) || empty($bio)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak boleh kosong.']);
            exit;
        }

        $oldForum = $this->forumModel->findById($forumId);
        if (!$oldForum) {
            echo json_encode(['success' => false, 'message' => 'Forum tidak ditemukan.']);
            exit;
        }

        if ($oldForum['OWNER_ID'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki izin untuk mengedit forum ini.']);
            exit;
        }

        $photoPath = $oldForum['PATH_PHOTO'];
        if (!empty($_FILES['forumPhoto']['name'])) {
            $targetDir = __DIR__ . '/../../storage/forums/photos/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = uniqid() . "_" . basename($_FILES['forumPhoto']['name']);
            $targetFile = $targetDir . $fileName;
            if (move_uploaded_file($_FILES['forumPhoto']['tmp_name'], $targetFile)) {
                if (!empty($oldForum['PATH_PHOTO']) && $oldForum['PATH_PHOTO'] !== 'default.png') {
                    $oldFile = $targetDir . $oldForum['PATH_PHOTO'];
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
                $photoPath = $fileName;
            }
        }

        $data = [
            'NAME' => $forumName,
            'ABOUT' => $bio,
            'IS_PRIVATE' => $isPrivate,
            'ACCESS_KEY' => $isPrivate ? $keyForum : null,
            'PATH_PHOTO' => $photoPath,
        ];

        if ($this->forumModel->edit($forumId, $data)) {
            $response = ['success' => true, 'message' => 'Forum berhasil diperbarui!', 'redirectUrl' => BASEURL . "/forums/chat/" . $forumId];
        } else {
            $response = ['success' => false, 'message' => 'Gagal memperbarui forum.'];
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

        $forumId = $_POST['forum_id'] ?? null;
        if (empty($forumId)) {
            echo json_encode(['success' => false, 'message' => 'Forum ID tidak ditemukan.']);
            exit;
        }

        $forumToDelete = $this->forumModel->findById($forumId);
        if (!$forumToDelete || $forumToDelete['OWNER_ID'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki izin atau forum tidak ditemukan.']);
            exit;
        }

        if ($this->forumModel->delete($forumId)) {
            $photoPath = $forumToDelete['PATH_PHOTO'] ?? null;
            if (!empty($photoPath) && $photoPath !== 'default.png') {
                $fullPath = __DIR__ . '/../../storage/forums/photos/' . $photoPath;
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            $response = ['success' => true, 'message' => 'Forum berhasil dihapus!', 'redirectUrl' => BASEURL . "/forums"];
        } else {
            $response = ['success' => false, 'message' => 'Gagal menghapus forum.'];
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
        $forumId = $_POST['forum_id'] ?? null;
        if (empty($userId) || empty($forumId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            exit;
        }

        if ($this->forumModel->exitForum($forumId, $userId)) {
            $response = ['success' => true, 'message' => 'Anda berhasil keluar dari forum!', 'redirectUrl' => BASEURL . "/forums"];
        } else {
            $response = ['success' => false, 'message' => 'Gagal keluar dari forum.'];
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
        $forums = $this->forumModel->searchByName($keyword, $userId);
        echo json_encode($forums);
        exit;
    }

    public function join()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed.']);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;
        $accessKey = $_POST['access_key'] ?? null;

        if (empty($forumId) || empty($userId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            exit;
        }

        $result = $this->forumModel->joinForum($forumId, $userId, $accessKey);
        if ($result['success']) {
            echo json_encode(['success' => true, 'message' => $result['message'], 'redirectUrl' => BASEURL . '/forums/chat/' . $forumId]);
        } else {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
        exit;
    }

    public function reportForumOrPost()
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
            if ($this->forumModel->createReport($reportData)['success']) {
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
}


