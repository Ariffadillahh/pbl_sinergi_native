<?php
// Pastikan path-nya sesuai dengan struktur folder Anda
require_once __DIR__ . '/../models/Forums/Forum.php';
require_once __DIR__ . '/../models/Forums/ForumMember.php';

class ForumsController
{
    public function index()
    {
        $allForums = Forum::getAll();
        // Di halaman index, tidak ada chat yang aktif
        $activeChatId = null;

        $contentView = __DIR__ . '/../views/forums/index.php';
        require_once __DIR__ . '/../views/forums/layout.php';
    }

    public function chat($id)
    {
        $forumByid = Forum::findById($id);
        $membersForum = ForumMember::findByForumId($id);

        $allForums = Forum::getAll();

        $activeChatId = $id;

        $contentView = __DIR__ . '/../views/forums/chat/index.php';
        require_once __DIR__ . '/../views/forums/layout.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        header('Content-Type: application/json');

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
            'user_id'   => 1, // Ganti dengan user yang sedang login
            'photo'     => $photoPath
        ];

        $newForumId = Forum::create($data);

        if ($newForumId) {
            $response = [
                'success' => true,
                'message' => 'Forum berhasil dibuat!',
                'redirectUrl' => BASEURL . "/forums/chat/" . $newForumId
            ];
        } else {
            $response = ['success' => false, 'message' => 'Gagal membuat forum.'];
        }

        echo json_encode($response);
        exit;
    }
}
