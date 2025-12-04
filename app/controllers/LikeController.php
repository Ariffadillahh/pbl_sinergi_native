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

        // Validasi Request Method
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
            // Cek Topic Exists
            if (!$this->likeModel->topicExists($topicId)) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Topic not found']);
                exit;
            }

            // Toggle Like
            $result = $this->likeModel->toggleLikeTopic($userId, $topicId);
            $totalLikes = $this->likeModel->getLikeCountTopic($topicId);

            // --- LOGIC NOTIFIKASI ---
            if ($result['action'] === 'liked') {

                $owner = $this->likeModel->getTopicOwner($topicId);

                // Cek owner valid dan bukan diri sendiri
                if ($owner && !empty($owner['USER_ID']) && $owner['USER_ID'] !== $userId) {
                    try {
                        // Tipe notifikasi harus sama dengan yang ada di database
                        $typeNotif = 'LIKE_POST';

                        // === DEBUG LOG (optional, bisa dihapus nanti) ===
                        error_log("=== DEBUG NOTIF CHECK ===");
                        error_log("Receiver ID: " . $owner['USER_ID']);
                        error_log("Sender ID: " . $userId);
                        error_log("Target ID: " . $topicId);
                        error_log("Type: " . $typeNotif);
                        // ================================================

                        // 1. CEK APAKAH SUDAH ADA NOTIFIKASI YANG SAMA (BELUM DIBACA)
                        $isSpam = $this->notificationModel->checkExistingUnread(
                            $owner['USER_ID'],  // Penerima
                            $userId,            // Pengirim
                            $topicId,           // Target ID
                            $typeNotif          // Type: 'LIKE_POST'
                        );

                        error_log("Is Spam: " . ($isSpam ? 'YES' : 'NO')); // Debug log

                        // 2. KIRIM NOTIFIKASI HANYA JIKA BELUM ADA
                        if (!$isSpam) {
                            $this->notificationModel->addNotification(
                                $owner['USER_ID'],  // Target User
                                $userId,            // Sender
                                $topicId,           // Target ID
                                $typeNotif,         // Type
                                'POST'              // Content Type untuk link
                            );
                            error_log("✓ Notifikasi berhasil dikirim");
                        } else {
                            error_log("✗ Notifikasi tidak dikirim (sudah ada yang belum dibaca)");
                        }
                    } catch (Exception $notifError) {
                        error_log("Notification Error: " . $notifError->getMessage());
                        // Tidak stop eksekusi, notif gagal tapi like tetap jalan
                    }
                }
            }

            // Response Success
            echo json_encode([
                'success' => true,
                'action' => $result['action'],
                'total_likes' => $totalLikes
            ]);
            exit;
        } catch (Exception $e) {
            error_log("=== LIKE ERROR ===");
            error_log("Error: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Server error occurred.']);
            exit;
        }
    }
}
