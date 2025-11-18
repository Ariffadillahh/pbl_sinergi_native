<?php
require_once __DIR__ . '/../models/Forums/ChatMessage.php';

class ChatMessagesController
{
    private $chatMessageModel;
    private $forumModel;

    public function __construct()
    {
        $this->chatMessageModel = new ChatMessage();
        $this->forumModel = new Forum();
    }

    public function sendMessage()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Metode request tidak valid.']);
            return;
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Pengguna tidak terautentikasi.']);
            return;
        }

        $user_id  = $_SESSION['user_id'];

        $forum_id = isset($_POST['forum_id']) ? trim($_POST['forum_id']) : null;
        $message  = trim($_POST['message'] ?? '');

       
        if (!$forum_id || !$this->chatMessageModel->isUserInForum($user_id, $forum_id)) {
            http_response_code(403);
            echo json_encode(['error' => 'Anda bukan anggota forum ini.']);
            return;
        }

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
            $cleanName = preg_replace("/[^a-zA-Z0-9.\-_]/", "", $originalName);
            $fileName = uniqid('', true) . '-' . $cleanName;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
                $mime = mime_content_type($targetPath);

                if (strpos($mime, 'image/') === 0) $fileType = 'IMAGE';
                elseif (strpos($mime, 'video/') === 0) $fileType = 'VIDEO';
                else $fileType = 'FILE';

                $data['path_media'] = 'storage/forums/attachment/' . $fileName;
                $data['original_filename'] = $originalName;
                $data['type'] = $fileType;
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Gagal memproses file.']);
                return;
            }

            $uploadEnd = microtime(true);
        }

        $dbStart = microtime(true);
        $result = $this->chatMessageModel->createMessage($data);
        $dbEnd = microtime(true);

        if (is_array($result) && isset($result['ID'])) {
            echo json_encode([
                'success'    => true,
                'message_id' => $result['ID']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Gagal menyimpan pesan ke database.']);
        }
    }

    public function getNewMessages()
    {
        header('Content-Type: application/json');

        try {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode(['error' => 'Tidak terautentikasi']);
                return;
            }

            $userId = $_SESSION['user_id'];
            $forumId = isset($_GET['forum_id']) ? trim($_GET['forum_id']) : '';
            $lastTimestamp = $_GET['since'] ?? null;

            session_write_close();

            if (empty($forumId)) {
                echo json_encode([]);
                return;
            }

            if (!$this->chatMessageModel->isUserInForum($userId, $forumId)) {
                http_response_code(403);
                echo json_encode(['error' => 'Anda bukan member forum ini.']);
                return;
            }

            set_time_limit(60);
            $startTime = time();

            while ((time() - $startTime) < 55) {

                if (!$this->chatMessageModel->isUserInForum($userId, $forumId)) {
                    http_response_code(403);
                    echo json_encode(['error' => 'Anda telah dikeluarkan dari forum.']);
                    return;
                }

                $messages = $this->chatMessageModel->getMessagesSince($forumId, $lastTimestamp);

                if (!empty($messages)) {
                    echo json_encode($messages);
                    return;
                }

                sleep(1);
            }

            echo json_encode([]);
        } catch (\Throwable $e) {
            error_log('Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getInitialMessages($forum_id)
    {
        header('Content-Type: application/json');

        $messages = $this->chatMessageModel->getMessagesByForumId($forum_id);

        echo json_encode($messages ?? []);

        exit();
    }

    public function markAsRead($id)
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            exit;
        }

        $currentUserId = $_SESSION['user_id'];
        $this->forumModel->updateLastReadAt($id, $currentUserId);
        http_response_code(204);
        exit;
    }

    public function pollCounts()
    {
        header('Content-Type: application/json');
        set_time_limit(40);

        try {
            if (!isset($_SESSION['user_id'])) {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
            $userId = $_SESSION['user_id'];
            session_write_close();

            $lastHash = $_GET['lastHash'] ?? '';
            $startTime = time();

            while (time() - $startTime < 35) {

                $forumsData = $this->forumModel->getForumsByUserId($userId);

                $payload = [];
                foreach ($forumsData as $forum) {

                    $lastMessage = $this->formatLastMessage(
                        $forum['LAST_MESSAGE_CONTENT'] ?? null,
                        $forum['LAST_MESSAGE_TYPE'] ?? 'TEXT'
                    );

                    $lastTime = $forum['LAST_MESSAGE_AT'] ?? $forum['CREATED_AT'];

                    $payload[] = [
                        'forumId' => $forum['ID'],
                        'count' => (int) $forum['UNREAD_COUNT'],
                        'lastMessage' => $lastMessage,
                        'lastTime' => $lastTime
                    ];
                }

                $newHash = md5(json_encode($payload));

                if ($newHash != $lastHash) {
                    http_response_code(200);
                    echo json_encode([
                        'hash' => $newHash,
                        'data' => $payload
                    ]);
                    exit;
                }

                sleep(2);
            }

            http_response_code(204);
            exit;
        } catch (\Throwable $e) {
            error_log('Error in pollCounts: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Terjadi kesalahan pada server.']);
        }
    }

    private function formatLastMessage($content, $type)
    {
        switch ($type) {
            case 'IMAGE':
                return '📷 Photo';
            case 'VIDEO':
                return '🎥 Video';
            case 'FILE':
                return '📎 File';
            case 'TEXT':
            default:
                return $content ?: 'No messages yet';
        }
    }

    // In app/controllers/ChatMessagesController.php

public function getMediaPreview($forumId, $limit = 8)
{
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Tidak terautentikasi']);
        return;
    }
    
    try {
        $mediaPreview = $this->chatMessageModel->getForumMediaPreview($forumId, $limit);
        
        echo json_encode([
            'success' => true,
            'data' => $mediaPreview
        ]);
        
    } catch (\Exception $e) {
        error_log('Error getting media preview: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Gagal mengambil media']);
    }
}

public function getAllMedia($forumId)
{
    header('Content-Type: application/json');
    
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Tidak terautentikasi']);
        return;
    }
    
    try {
        $allMedia = $this->chatMessageModel->getAllForumMedia($forumId);
        
        echo json_encode([
            'success' => true,
            'data' => $allMedia
        ]);
        
    } catch (\Exception $e) {
        error_log('Error getting all media: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Gagal mengambil media']);
    }
}

}
