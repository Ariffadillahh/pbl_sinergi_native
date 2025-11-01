<?php
require_once __DIR__ . '/../models/Forums/ChatMessage.php';

class ChatMessagesController
{
    private $chatMessageModel;

    public function __construct()
    {
        $this->chatMessageModel = new ChatMessage();
    }

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
        $result = $this->chatMessageModel->createMessage($data);
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
                $messages = $this->chatMessageModel->getMessagesSince($forumId, $lastTimestamp);

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

        $messages = $this->chatMessageModel->getMessagesByForumId($forum_id);

        echo json_encode($messages ?? []);

        exit();
    }
}
