<?php
require_once __DIR__ . '/../models/Posts/LikeModel.php';
require_once __DIR__ . '/../models/Users/NotificationModel.php';

class LikeController {
    public function toggleLike() {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        $postId = $_POST['post_id'] ?? null;

        if (!$userId || !$postId) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        $likeModel = new LikeModel();
        $notifModel = new NotificationModel();

        // 🔁 Toggle like / unlike
        $result = $likeModel->toggleLike($userId, $postId);
        $totalLikes = $likeModel->getLikeCount($postId);

        // 📨 Jika baru di-like, kirim notifikasi
        if ($result['action'] === 'liked') {
            $postOwner = $likeModel->getPostOwner($postId);

            if ($postOwner && $postOwner['ID'] !== $userId) {
                $notifData = [
                    'sender_name' => $_SESSION['full_name'] ?? 'Someone',
                    'sender_id'   => $userId,
                    'target_id'   => $postId,
                    'link'        => "homepage/reply/$postId"
                ];

                $notifModel->addNotification(
                    $postOwner['ID'],
                    'LIKE_POST',
                    json_encode($notifData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                );
            }
        }

        echo json_encode([
            'success' => true,
            'action' => $result['action'],
            'total_likes' => $totalLikes
        ]);
    }
}
