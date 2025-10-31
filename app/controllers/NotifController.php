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

        if (empty($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        session_write_close();

        $lastTimestamp = $_GET['last_timestamp'] ?? date('c', strtotime('-1 hour'));
        $userId = $_SESSION['user_id'];

        set_time_limit(30);
        ignore_user_abort(true);

        $startTime = time();
        $timeout = 28;

        while ((time() - $startTime) < $timeout) {
            $notifications = $this->notificationModel->getNewNotifications($userId, $lastTimestamp);

            if (!empty($notifications)) {
                echo json_encode($notifications);
                exit;
            }

            sleep(2);

            if (connection_aborted()) {
                exit;
            }
        }

        echo json_encode([]);
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
            // Jika result false, mungkin notif tidak ditemukan
            echo json_encode(['success' => false, 'message' => 'Notification not found or already read.']);
        }
        exit;
    }
}
