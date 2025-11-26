<?php
require_once __DIR__ . '/../models/Posts/LikeModel.php';
require_once __DIR__ . '/../models/Notif/NotificationModel.php';

class LikeController
{
    private $likeModel;
    private $notificationModel;

    public function __construct()
    {
        $this->likeModel = new LikeModel();
        $this->notificationModel = new NotificationModel();
    }

    public function toggleLike()
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

        $userId = $_SESSION['user_id'];
        $postId = $_POST['post_id'] ?? null;

        if (!$postId) {
            http_response_code(400);
            echo json_encode(['error' => 'Post ID required']);
            exit;
        }

        $result = $this->likeModel->toggleLike($userId, $postId);
        $totalLikes = $this->likeModel->getLikeCount($postId);
        $type = 'LIKE_POST';

        if ($result['action'] === 'liked') {
            $owner = $this->likeModel->getPostOwner($postId);
            if ($owner && $owner['ID'] !== $userId) {
                $this->notificationModel->addNotification($owner['ID'], $userId, $postId, $type, 'POST');
            }
        }

        echo json_encode([
            'success' => true,
            'action' => $result['action'],
            'total_likes' => $totalLikes
        ]);
        exit;
    }

    public function toggleLikeTopic()
    {
        header('Content-Type: application/json');

        // Validasi Method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        // Validasi Login
        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login first.']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $topicId = $_POST['topic_id'] ?? null;

        // Validasi Topic ID
        if (!$topicId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Topic ID is required']);
            exit;
        }

        try {
            // Cek apakah topik ada
            if (!$this->likeModel->topicExists($topicId)) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Topic not found']);
                exit;
            }

            // Toggle Like/Unlike
            $result = $this->likeModel->toggleLikeTopic($userId, $topicId);

            // Hitung total likes terbaru
            $totalLikes = $this->likeModel->getLikeCountTopic($topicId);

            // Kirim notifikasi HANYA jika action = 'liked'
            if ($result['action'] === 'liked') {

                // Ambil data owner topic
                $owner = $this->likeModel->getTopicOwner($topicId);

                // Debugging lengkap
                error_log("=== LIKE DEBUG ===");
                error_log("Topic ID: " . $topicId);
                error_log("Current User ID: " . $userId);
                error_log("Owner Data: " . print_r($owner, true));

                // Validasi owner data dan pastikan bukan like sendiri
                if ($owner && !empty($owner['USER_ID']) && $owner['USER_ID'] !== $userId) {

                    error_log("Sending notification to: " . $owner['USER_ID']);

                    try {
                        $this->notificationModel->addNotification(
                            $owner['USER_ID'],  // Penerima notifikasi (owner topic)
                            $userId,            // Yang nge-like
                            $topicId,           // Referensi topic
                            'LIKE_TOPIC',       // Tipe notifikasi
                            'TOPIC'             // Target type
                        );
                        error_log("Notification sent successfully!");
                    } catch (Exception $notifError) {
                        // Jangan gagalkan like hanya karena notifikasi error
                        error_log("Notification Error: " . $notifError->getMessage());
                        error_log("Notification Stack: " . $notifError->getTraceAsString());
                    }
                } else {
                    error_log("Notification skipped - Reason:");
                    if (!$owner) {
                        error_log("  - Owner not found");
                    } elseif (empty($owner['USER_ID'])) {
                        error_log("  - Owner USER_ID is empty");
                    } elseif ($owner['USER_ID'] === $userId) {
                        error_log("  - User liked their own topic");
                    }
                }
            }

            // Response sukses
            echo json_encode([
                'success' => true,
                'action' => $result['action'],
                'total_likes' => $totalLikes
            ]);
            exit;
        } catch (Exception $e) {
            // Log error lengkap untuk debugging
            error_log("=== LIKE ERROR ===");
            error_log("Error Message: " . $e->getMessage());
            error_log("Stack Trace: " . $e->getTraceAsString());
            error_log("User ID: " . $userId);
            error_log("Topic ID: " . $topicId);

            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}
