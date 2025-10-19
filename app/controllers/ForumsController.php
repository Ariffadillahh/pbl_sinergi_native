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

    public function reportForum()
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

        $userId = $_SESSION['user_id'];
        $forumId = isset($_POST['forum_id']) ? $_POST['forum_id'] : null;
        $targetType = $_POST['target_type'] ?? null;
        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : null;
        $otherReasonText = isset($_POST['other_reason_text']) ? trim($_POST['other_reason_text']) : null;

        if (empty($forumId) || empty($reason)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap. ID Forum dan alasan wajib diisi.']);
            return;
        }

        if ($reason === 'other' && empty($otherReasonText)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Silakan jelaskan alasan Anda jika memilih "Lainnya".']);
            return;
        }

        $reportDescription = $reason;
        if ($reason === 'other') {
            $reportDescription = "Lainnya: " . htmlspecialchars($otherReasonText);
        }

        $data = [
            'user_id' => $userId,
            'forum_id' => $forumId,
            'target_type' => $targetType,
            'reason' => $reportDescription,
        ];

        $reportForum = Forum::createReport($data);

        try {
            if ($reportForum['success']) {
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Laporan Anda telah berhasil dikirim.']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan laporan ke database.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan pada server.']);
        }
    }
}

class ChatMessages
{
    public function sendMessage()
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

        $forum_id = $_POST['forum_id'] ?? null;
        $user_id  = $_SESSION['user_id'];
        $message  = trim($_POST['message'] ?? '');

        $hasFile = isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK;

        if (empty($message) && !$hasFile) {
            http_response_code(400);
            echo json_encode(['error' => 'Pesan atau file tidak boleh kosong.']);
            return;
        }

        $data = [
            'forum_id'          => $forum_id,
            'sender_id'         => $user_id,
            'content'           => $message,
            'path_media'        => null,
            'original_filename' => null,
            'type'              => 'TEXT'
        ];

        if ($hasFile) {
            $uploadStart = microtime(true);

            $uploadDir = __DIR__ . '/../../storage/forums/attachment/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $originalName = basename($_FILES['attachment']['name']);
            $fileName = uniqid('', true) . '-' . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $originalName);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
                $mime = mime_content_type($targetPath);

                $fileType = 'FILE';
                if (strpos($mime, 'image/') === 0) $fileType = 'IMAGE';
                elseif (strpos($mime, 'video/') === 0) $fileType = 'VIDEO';

                $data['path_media'] = 'storage/forums/attachment/' . $fileName;
                $data['original_filename'] = $originalName;
                $data['type'] = $fileType;
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal memproses file.']);
                return;
            }

            $uploadEnd = microtime(true);
            error_log("File upload took: " . ($uploadEnd - $uploadStart) . " seconds");
        }

        $dbStart = microtime(true);
        $result = ChatMessage::createMessage($data);
        $dbEnd = microtime(true);
        error_log("Database insert took: " . ($dbEnd - $dbStart) . " seconds");

        if (is_array($result) && isset($result['ID'])) {

            echo json_encode([
                'success'    => true,
                'message_id' => $result['ID']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menyimpan pesan.']);
        }
    }

    public function getNewMessages()
    {
        header('Content-Type: application/json');

        try {
            $forumId = $_GET['forum_id'] ?? 0;
            $lastTimestamp = $_GET['since'] ?? null;

            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Tidak terautentikasi']);
                return;
            }

            session_write_close();

            if (!$forumId) {
                echo json_encode([]);
                return;
            }

            set_time_limit(60);

            $startTime = time();

            while ((time() - $startTime) < 55) {
                $messages = ChatMessage::getMessagesSince($forumId, $lastTimestamp);

                if (!empty($messages)) {
                    echo json_encode($messages);
                    return;
                }

                sleep(1);
            }

            echo json_encode([]);
        } catch (\Throwable $e) {
            error_log('Error in getNewMessages: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan pada server.']);
        }
    }

    public function getInitialMessages($forum_id)
    {
        header('Content-Type: application/json');

        $messages = ChatMessage::getMessagesByForumId($forum_id);

        echo json_encode($messages ?? []);

        exit();
    }
}
