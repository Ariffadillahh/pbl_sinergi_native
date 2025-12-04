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

        try {
            // Lakukan Toggle Like
            $result = $this->likeModel->toggleLike($userId, $postId);
            $totalLikes = $this->likeModel->getLikeCount($postId);

            // Ambil data pemilik Post
            $owner = $this->likeModel->getPostOwner($postId);

            // Cek jika owner ada DAN bukan diri sendiri
            if ($owner && $owner['ID'] !== $userId) {
                $type = 'LIKE_POST';

                // 1. HAPUS notifikasi lama (Entah itu mau unlike atau like ulang, hapus dulu biar bersih)
                $this->notificationModel->deleteNotification($owner['ID'], $userId, $postId, $type);

                // 2. Jika aksinya adalah 'liked', BARU tambah notifikasi baru
                if ($result['action'] === 'liked') {
                    $this->notificationModel->addNotification($owner['ID'], $userId, $postId, $type, 'POST');
                }
            }

            echo json_encode([
                'success' => true,
                'action' => $result['action'],
                'total_likes' => $totalLikes
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
            exit;
        }
    }

    public function toggleLikeTopic()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized. Please login first.']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $topicId = $_POST['topic_id'] ?? null;

        if (!$topicId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Topic ID is required']);
            exit;
        }

        try {
            if (!$this->likeModel->topicExists($topicId)) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Topic not found']);
                exit;
            }

            // Toggle Like/Unlike
            $result = $this->likeModel->toggleLikeTopic($userId, $topicId);
            $totalLikes = $this->likeModel->getLikeCountTopic($topicId);

            // LOGIKA NOTIFIKASI
            $owner = $this->likeModel->getTopicOwner($topicId);

            // Validasi owner & pastikan bukan like punya sendiri
            if ($owner && !empty($owner['USER_ID']) && $owner['USER_ID'] !== $userId) {

                // Definisikan tipe notifikasi
                $notifType = 'LIKE_TOPIC';

                try {
                    // 1. Selalu HAPUS notifikasi lama terlebih dahulu
                    // Ini akan membersihkan notifikasi jika user melakukan UNLIKE,
                    // Atau membersihkan duplikat sebelum insert baru jika user melakukan LIKE.
                    $this->notificationModel->deleteNotification(
                        $owner['USER_ID'],
                        $userId,
                        $topicId,
                        $notifType
                    );

                    // 2. Jika statusnya sekarang LIKED, baru kirim notifikasi baru
                    if ($result['action'] === 'liked') {
                        $this->notificationModel->addNotification(
                            $owner['USER_ID'],
                            $userId,
                            $topicId,
                            $notifType,
                            'TOPIC'
                        );
                        error_log("Notification sent (and old one cleaned) for Topic ID: " . $topicId);
                    } else {
                        error_log("Notification removed (Unlike action) for Topic ID: " . $topicId);
                    }
                } catch (Exception $notifError) {
                    error_log("Notification Logic Error: " . $notifError->getMessage());
                }
            }

            echo json_encode([
                'success' => true,
                'action' => $result['action'],
                'total_likes' => $totalLikes
            ]);
            exit;
        } catch (Exception $e) {
            error_log("=== LIKE TOPIC ERROR ===");
            error_log($e->getMessage());

            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}
