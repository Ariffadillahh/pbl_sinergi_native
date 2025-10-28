<?php
require_once __DIR__ . '/../models/Users/NotificationModel.php';

class NotificationController
{
    private $model;

    public function __construct()
    {
        $this->model = new NotificationModel();
    }

    public function index()
    {
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $data = $this->model->getUserNotifications($userId);
        echo json_encode(['success' => true, 'notifications' => $data]);
    }
}
