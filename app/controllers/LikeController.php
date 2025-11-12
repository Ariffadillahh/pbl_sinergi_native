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
}

