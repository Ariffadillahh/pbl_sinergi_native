<?php
require_once __DIR__ . '/../models/Groups/ChatMessage.php';

class ChatMessagesController
{
    private $chatMessageModel;
    private $groupChatModel;

    public function __construct()
    {
        $this->chatMessageModel = new ChatMessage();
        $this->groupChatModel = new GroupChat();
    }

    public function sendMessage()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method request invalid.']);
            return;
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthenticated user.']);
            return;
        }

        $user_id  = $_SESSION['user_id'];
        $groupChatId = isset($_POST['group_chat_id']) ? trim($_POST['group_chat_id']) : null;
        $message  = trim($_POST['message'] ?? '');

        if (!$groupChatId || !$this->chatMessageModel->isUserInGroupChat($user_id, $groupChatId)) {
            http_response_code(403);
            echo json_encode(['error' => 'You are not a member of this Group.']);
            return;
        }

        $hasFile = isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK;

        if (empty($message) && !$hasFile) {
            http_response_code(400);
            echo json_encode(['error' => 'Messages or files cannot be empty.']);
            return;
        }

        $data = [
            'group_chat_id'     => $groupChatId,
            'sender_id'         => $user_id,
            'content'           => $message,
            'path_media'        => null,
            'original_filename' => null,
            'type'              => 'TEXT'
        ];

        if ($hasFile) {
            $fileTmpPath = $_FILES['attachment']['tmp_name'];
            $fileSize    = $_FILES['attachment']['size'];

            // 1. Cek MIME Type dari file sementara
            $mime = mime_content_type($fileTmpPath);

            // 2. Tentukan Tipe dan Batas Ukuran
            $fileType = 'FILE';
            $maxSize  = 10 * 1024 * 1024; // Default FILE: 10 MB

            if (strpos($mime, 'image/') === 0) {
                $fileType = 'IMAGE';
                $maxSize  = 5 * 1024 * 1024; // IMAGE: 5 MB
            } elseif (strpos($mime, 'video/') === 0) {
                $fileType = 'VIDEO';
                $maxSize  = 30 * 1024 * 1024; // VIDEO: 30 MB
            }

            // 3. Validasi Ukuran Berdasarkan Tipe
            if ($fileSize > $maxSize) {
                http_response_code(400);
                $limitInMb = $maxSize / (1024 * 1024);
                echo json_encode(['error' => "File too large. Max size for $fileType is {$limitInMb}MB."]);
                return;
            }

            $uploadDir = __DIR__ . '/../../storage/groups/attachment/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $originalName = basename($_FILES['attachment']['name']);
            $cleanName = preg_replace("/[^a-zA-Z0-9.\-_]/", "", $originalName);
            $fileName = uniqid('', true) . '-' . $cleanName;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $targetPath)) {
                $data['path_media'] = 'storage/groups/attachment/' . $fileName;
                $data['original_filename'] = $originalName;
                $data['type'] = $fileType;
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to process the file.']);
                return;
            }
        }

        $result = $this->chatMessageModel->createMessage($data);

        if (is_array($result) && isset($result['ID'])) {
            echo json_encode([
                'success'    => true,
                'message_id' => $result['ID']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save message to database.']);
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
                echo json_encode(['error' => 'Not authenticated']);
                return;
            }

            $userId = $_SESSION['user_id'];
            // AMBIL ROLE DARI SESSION (Sesuaikan kuncinya, misal 'role', 'level', atau 'user_role')
            $userRole = $_SESSION['role'] ?? 'user';

            $groupChatId = isset($_GET['group_chat_id']) ? trim($_GET['group_chat_id']) : '';
            $lastTimestamp = $_GET['since'] ?? null;

            session_write_close();

            if (empty($groupChatId)) {
                echo json_encode([]);
                return;
            }

            $isMember = $this->chatMessageModel->isUserInGroupChat($userId, $groupChatId);
            $isAdmin  = (strtoupper($userRole) === 'ADMIN');

            if (!$isMember && !$isAdmin) {
                http_response_code(403);
                echo json_encode(['error' => 'You are not a member of this group.']);
                return;
            }

            set_time_limit(60);
            $startTime = time();

            while ((time() - $startTime) < 55) {
                if (!$isAdmin && !$this->chatMessageModel->isUserInGroupChat($userId, $groupChatId)) {
                    http_response_code(403);
                    echo json_encode(['error' => 'You have been removed from the group.']);
                    return;
                }

                $messages = $this->chatMessageModel->getMessagesSince($groupChatId, $lastTimestamp);

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

    public function getInitialMessages($groupChatId)
    {
        header('Content-Type: application/json');

        $messages = $this->chatMessageModel->getMessagesByGroupChatId($groupChatId);

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
        $this->groupChatModel->updateLastReadAt($id, $currentUserId);
        http_response_code(204);
        exit;
    }

    public function pollCounts()
    {
        header('Content-Type: application/json');
        set_time_limit(40);

        try {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

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

                $groupChatsData = $this->groupChatModel->getGroupChatsByUserId($userId);

                $payload = [];

                foreach ($groupChatsData as $groupChat) {
                    // Handle CLOB if needed
                    $content = $groupChat['LAST_MESSAGE_CONTENT'] ?? null;
                    if (is_object($content) && get_class($content) === 'OCILob') {
                        $content = $content->load();
                    }

                    $lastMessage = $this->formatLastMessage(
                        $content,
                        $groupChat['LAST_MESSAGE_TYPE'] ?? 'TEXT'
                    );

                    $lastTime = $groupChat['LAST_MESSAGE_AT'] ?? $groupChat['CREATED_AT'];

                    // ✅ Trim the ID
                    $payload[] = [
                        'group_chat_id' => trim($groupChat['ID']),
                        'count' => (int) ($groupChat['UNREAD_COUNT'] ?? 0),
                        'lastMessage' => $lastMessage,
                        'lastTime' => $lastTime
                    ];
                }

                $newHash = md5(json_encode($payload));

                // Always return on first poll or if changed
                if ($lastHash === '' || $newHash != $lastHash) {
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
            echo json_encode(['error' => 'An error occurred on the server.']);
            exit;
        }
    }

    private function formatLastMessage($content, $type)
    {
        switch ($type) {
            case 'IMAGE':
                return '📷 Photo';
            case 'VIDEO':
                return '▶️ Video';
            case 'FILE':
                return '🗂️ File';
            case 'TEXT':
            default:
                return $content ?: 'No messages yet';
        }
    }

    public function getAllMedia($groupChatId)
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        try {
            $allMedia = $this->chatMessageModel->getAllGroupChatMedia($groupChatId);

            echo json_encode([
                'success' => true,
                'data' => $allMedia
            ]);
        } catch (\Exception $e) {
            error_log('Error getting all media: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Failed to retrieve media.']);
        }
    }
}
