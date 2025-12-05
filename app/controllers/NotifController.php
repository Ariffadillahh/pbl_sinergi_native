<?php
require_once __DIR__ . '/../models/Notif/NotificationModel.php';

class NotifController
{
    private $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function checkForUpdates()
    {
        header('Content-Type: application/json');

        // Ambil data JSON dari body request (karena kita akan pakai POST fetch)
        $input = json_decode(file_get_contents('php://input'), true);

        $lastTimestamp = $input['last_timestamp'] ?? date('c', strtotime('-1 hour'));
        $clientIds = $input['visible_ids'] ?? []; // ID yang ada di layar user

        $userId = $_SESSION['user_id']; // Pastikan session start sudah ada di construct/index

        session_write_close();
        set_time_limit(40);
        ignore_user_abort(true);

        $startTime = time();
        $timeout = 28;

        while ((time() - $startTime) < $timeout) {

            // 1. Cek Pesan BARU (Logic lama)
            $newNotifications = $this->notificationModel->getNewNotifications($userId, $lastTimestamp);

            // 2. Cek Pesan TERHAPUS (Logic baru: Bandingkan ID client vs DB)
            $deletedIds = [];
            if (!empty($clientIds)) {
                $deletedIds = $this->notificationModel->getDeletedIdsFromList($clientIds);
            }

            // Jika ada perubahan (Baru ATAU Hapus)
            if (!empty($newNotifications) || !empty($deletedIds)) {
                echo json_encode([
                    'type' => 'update',
                    'new_notifications' => $newNotifications,
                    'deleted_ids' => $deletedIds
                ]);
                exit;
            }

            sleep(2);

            // Bersihkan cache stat query oracle/php jika perlu, atau cek koneksi
            if (connection_aborted()) exit;
        }

        echo json_encode(['type' => 'no_update']);
        exit;
    }

    public function getRecent()
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        $userId = $_SESSION['user_id'];

        $notifications = $this->notificationModel->getRecentNotifications($userId, 20);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);

        echo json_encode([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
        exit;
    }

    public function markAllRead()
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
        $result = $this->notificationModel->markAllAsRead($userId);

        echo json_encode(['success' => $result]);
        exit;
    }

    public function readNotif()
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

        $input = json_decode(file_get_contents('php://input'), true);
        $notifId = $input['notifId'] ?? null;

        if (!$notifId) {
            http_response_code(400);
            echo json_encode(['error' => 'Notification ID is required']);
            exit;
        }

        $userId = $_SESSION['user_id'];

        $result = $this->notificationModel->markAsRead($userId, $notifId);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Notification not found or already read.']);
        }
        exit;
    }

    public function deleteAllRead()
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

        $result = $this->notificationModel->deleteAllRead($userId);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Notification failed to delete.']);
        }
        exit;
    }

    public function deleteInviteNotif()
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

        $input = json_decode(file_get_contents('php://input'), true);
        $targetId = $input['target_id'] ?? null;
        $type = $input['type'] ?? null;

        if (!$targetId || !$type) {
            http_response_code(400);
            echo json_encode(['error' => 'Target ID and Type are required']);
            exit;
        }

        // Validasi tipe notifikasi yang diperbolehkan
        $allowedTypes = ['INVITE_GROUP', 'INVITE_FORUM', 'ADMIN_INVITE_FORUM'];
        if (!in_array($type, $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid notification type']);
            exit;
        }

        $userId = $_SESSION['user_id'];

        $result = $this->notificationModel->deleteInviteNotification($userId, $targetId, $type);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete notification.']);
        }
        exit;
    }
}
