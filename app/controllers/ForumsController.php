<?php
class ForumsController
{
    public $idForum;

    public function index()
    {
        $contentView = __DIR__ . '/../views/forums/index.php';
        require_once __DIR__ . '/../views/forums/layout.php';
    }

    public function chat($id)
    {
        $forumByid = $this->getForumById($id);
        $membersForum = $this->getMembersByForumId($id);

        $contentView = __DIR__ . '/../views/forums/chat/index.php';
        require_once __DIR__ . '/../views/forums/layout.php';
    }

    private function getConnection()
    {
        require __DIR__ . '/../../config/database.php';
        return $conn;
    }

    public function getAllForums()
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM forums");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMembersByForumId($forumId)
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT fm.*, a.nama, a.email FROM forum_members fm JOIN anggota a ON fm.user_id = a.nomor WHERE fm.forum_id = ?");
        $stmt->bind_param("s", $forumId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getForumById($id)
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT * FROM forums WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => 'An error occurred.'];

        // Ambil data
        $forumName = trim($_POST['forumName'] ?? '');
        $bio       = trim($_POST['bio'] ?? '');
        $isPrivate = isset($_POST['isPrivate']) ? 1 : 0;
        $keyForum  = $isPrivate ? ($_POST['keyForum'] ?? '') : null;
        $user_id   = 1; // Ganti dengan user yang sedang login

        if (empty($forumName) || empty($bio)) {
            $response['message'] = 'Nama Forum dan Bio tidak boleh kosong.';
            echo json_encode($response);
            exit;
        }
        if ($isPrivate && empty($keyForum)) {
            $response['message'] = 'Key Forum harus diisi untuk forum privat.';
            echo json_encode($response);
            exit;
        }

        $id        = uniqid();
        $photoPath = null;

        if (!empty($_FILES['forumPhoto']['name'])) {
            $targetDir = __DIR__ . '/../../storage/forums/photos/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName   = $id . "_" . basename($_FILES['forumPhoto']['name']);
            $targetFile = $targetDir . $fileName;
            if (move_uploaded_file($_FILES['forumPhoto']['tmp_name'], $targetFile)) {
                $photoPath = $fileName;
            }
        }

        $conn = $this->getConnection();
        $conn->begin_transaction();

        try {
            $stmt = $conn->prepare("INSERT INTO forums (id, name, about, isPrivate, `key`, photo, owner_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisss", $id, $forumName, $bio, $isPrivate, $keyForum, $photoPath, $user_id);
            $stmt->execute();

            $memberId = uniqid();
            $joinedAt = date('Y-m-d H:i:s');
            $stmt2 = $conn->prepare("INSERT INTO forum_members (id, forum_id, user_id, joined_at) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("ssis", $memberId, $id, $user_id, $joinedAt);
            $stmt2->execute();

            $conn->commit();

            $response['success'] = true;
            $response['message'] = 'Forum berhasil dibuat!';
            $response['redirectUrl'] = BASEURL . "/forums/chat/" . $id;
        } catch (Exception $e) {
            $conn->rollback();
            $response['message'] = 'Gagal menyimpan ke database.';
        }

        echo json_encode($response);
        exit;
    }
}
