<?php
require_once __DIR__ . '/../models/Forums/Forum.php';
require_once __DIR__ . '/../models/Forums/ForumMember.php';
require_once __DIR__ . '/../models/Forums/ChatMessage.php';

class ForumsController
{
    public function index()
    {
        $joinedForums = Forum::getForumsByUserId($_SESSION['user_id']);
        $activeChatId = null;

        $contentView = __DIR__ . '/../views/forums/index.php';
        require_once __DIR__ . '/../views/forums/layout.php';
    }

    public function chat($id)
    {
        $forumByid = Forum::findById($id);

        if (!$forumByid) {
            header("Location: " . BASEURL . "/forums");
            exit;
        }

        $membersForum = ForumMember::findByForumId($id);

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

        $joinedForums = Forum::getForumsByUserId($_SESSION['user_id']);
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
            'user_id'   => $_SESSION['user_id'],
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

    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        header('Content-Type: application/json');

        $forumId   = $_POST['forum_id'] ?? null;
        $forumName = trim($_POST['forumName'] ?? '');
        $bio       = trim($_POST['bio'] ?? '');
        $isPrivate = isset($_POST['isPrivate']) ? 1 : 0;
        $keyForum  = $_POST['keyForum'] ?? null;

        if (empty($forumId)) {
            echo json_encode(['success' => false, 'message' => 'Forum ID tidak ditemukan.']);
            exit;
        }

        if (empty($forumName) || empty($bio)) {
            echo json_encode(['success' => false, 'message' => 'Nama Forum dan Bio tidak boleh kosong.']);
            exit;
        }



        $oldForum = Forum::findById($forumId);
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
            'NAME'       => $forumName,
            'ABOUT'      => $bio,
            'IS_PRIVATE' => $isPrivate,
            'ACCESS_KEY' => $isPrivate ? $keyForum : null,
            'PATH_PHOTO' => $photoPath,
        ];

        $updated = Forum::edit($forumId, $data);

        if ($updated) {
            $response = [
                'success' => true,
                'message' => 'Forum berhasil diperbarui!',
                'redirectUrl' => BASEURL . "/forums/chat/" . $forumId
            ];
        } else {
            $response = ['success' => false, 'message' => 'Gagal memperbarui forum.'];
        }

        echo json_encode($response);
        exit;
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        header('Content-Type: application/json');

        $forumId = $_POST['forum_id'] ?? null;

        if (empty($forumId)) {
            echo json_encode(['success' => false, 'message' => 'Forum ID tidak ditemukan.']);
            exit;
        }

        $forumToDelete = Forum::findById($forumId);
        if (!$forumToDelete) {
            echo json_encode(['success' => false, 'message' => 'Forum yang akan dihapus tidak ditemukan.']);
            exit;
        }

        if ($forumToDelete['OWNER_ID'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki izin untuk mengedit forum ini.']);
            exit;
        }

        $deleted = Forum::delete($forumId);

        if ($deleted) {
            $photoPath = $forumToDelete['PATH_PHOTO'] ?? null;

            if (!empty($photoPath) && $photoPath !== 'default.png') {
                $targetDir = __DIR__ . '/../../storage/forums/photos/';
                $fullPath = $targetDir . $photoPath;

                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }

            $response = [
                'success' => true,
                'message' => 'Forum berhasil dihapus!',
                'redirectUrl' => BASEURL . "/forums"
            ];
        } else {
            $response = ['success' => false, 'message' => 'Gagal menghapus forum.'];
        }

        echo json_encode($response);
        exit;
    }


    public function exit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        header('Content-Type: application/json');

        $userId = $_SESSION['user_id'] ?? null;
        if (empty($userId)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Anda harus login untuk keluar dari forum.']);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;
        if (empty($forumId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Forum ID tidak ditemukan.']);
            exit;
        }

        $exited = Forum::exitForum($forumId, $userId);

        if ($exited) {
            $response = [
                'success'     => true,
                'message'     => 'Anda berhasil keluar dari forum!',
                'redirectUrl' => BASEURL . "/forums"
            ];
        } else {
            $response = ['success' => false, 'message' => 'Gagal keluar dari forum. Silakan coba lagi.'];
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

        $forums = Forum::searchByName($keyword, $userId);

        echo json_encode($forums);
        exit;
    }


    public function join()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed.']);
            exit;
        }

        header('Content-Type: application/json');

        $forumId = $_POST['forum_id'] ?? null;
        $userId = $_SESSION['user_id'] ?? null;
        $accessKey = $_POST['access_key'] ?? null;

        if (empty($forumId) || empty($userId)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap.']);
            exit;
        }

        $result = Forum::joinForum($forumId, $userId, $accessKey);

        if ($result['success']) {
            echo json_encode(
                [
                    'success' => true,
                    'message' => $result['message'],
                    'redirectUrl' => BASEURL . '/forums/chat/' . $forumId
                ]
            );
        } else {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
        exit;
    }
}

class ChatMessages
{
    public function sendMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Invalid request method.']);
            return;
        }

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'User not authenticated.']);
            return;
        }

        $forum_id = $_POST['forum_id'] ?? null;
        $user_id  = $_SESSION['user_id'];
        $message  = trim($_POST['message'] ?? '');
        $responses = [];

        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../storage/forums/attachment/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $originalName = basename($_FILES['attachment']['name']);
            $fileName = uniqid() . '-' . $originalName;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
                $mime = mime_content_type($targetPath);
                $fileType = 'FILE';
                if (strpos($mime, 'image/') === 0) {
                    $fileType = 'IMAGE';
                } elseif (strpos($mime, 'video/') === 0) {
                    $fileType = 'VIDEO';
                }

                $dataFile = [
                    'forum_id'   => $forum_id,
                    'sender_id'  => $user_id,
                    'content'    => $originalName,
                    'path_media' => 'storage/forums/attachment/' . $fileName,
                    'type'       => $fileType
                ];

                $resultFile = ChatMessage::createMessage($dataFile);

                if (is_array($resultFile) && isset($resultFile['ID'])) {
                    $responses[] = ['file' => $resultFile['ID']];
                } else {
                    error_log("❌ Gagal menyimpan pesan file: " . json_encode($dataFile));
                }
            } else {
                error_log("❌ Gagal memindahkan file ke $targetPath");
            }
        }

        if (!empty($message)) {
            $dataText = [
                'forum_id'   => $forum_id,
                'sender_id'  => $user_id,
                'content'    => $message,
                'path_media' => null,
                'type'       => 'TEXT'
            ];

            $resultText = ChatMessage::createMessage($dataText);

            if (is_array($resultText) && isset($resultText['ID'])) {
                $responses[] = ['text' => $resultText['ID']];
            } else {
                error_log("❌ Gagal menyimpan pesan teks: " . json_encode($dataText));
            }
        }

        if (empty($responses)) {
            http_response_code(400);
            echo json_encode(['error' => 'Message or file cannot be empty or failed to save.']);
            return;
        }

        echo json_encode([
            'success' => true,
            'responses' => $responses
        ]);
    }

    public function getNewMessages()
    {
        header('Content-Type: application/json');
        $forumId = $_GET['forum_id'] ?? 0;
        $lastTimestamp = $_GET['since'] ?? date('Y-m-d H:i:s');

        set_time_limit(30);

        while (true) {
            $messages = ChatMessage::getMessagesSince($forumId, $lastTimestamp);

            if (!empty($messages)) {
                echo json_encode($messages);
                return;
            }
            sleep(1);
        }
    }

    public function getInitialMessages()
    {
        header('Content-Type: application/json');
        $forumId = $_GET['forum_id'] ?? 0;
        $messages = ChatMessage::getInitialMessages($forumId);
        echo json_encode($messages);
    }
}
