<?php

require_once __DIR__ . '/../models/Admin/ReportManage.php';
require_once __DIR__ . '/../models/Forum/Forum.php';
require_once __DIR__ . '/../models/Notif/NotificationModel.php';
require_once __DIR__ . '/../models/Posts/PostModel.php';
require_once __DIR__ . '/../models/Groups/GroupChat.php';


class ReportController
{
    private $reportModel;
    private $forumModel;
    private $notificationModel;
    private $groupModel;

    public function __construct()
    {
        $this->reportModel = new ReportManage();
        $this->forumModel = new ForumModel();
        $this->groupModel = new  GroupChat();
        $this->notificationModel = new NotificationModel();
    }

    public function getReportForums()
    {
        $report = $this->reportModel->getReportForum();
        return $report;
    }

    public function getReportPost()
    {
        $report = $this->reportModel->getReportPost();
        return $report;
    }

    public function getReportGroup()
    {
        $report = $this->reportModel->getReportGroup();
        return $report;
    }

    public function getReasons()
    {
        header('Content-Type: application/json');
        $targetId = $_GET['target_id'] ?? null;
        $type = $_GET['type'] ?? 'FORUMS';

        if (!$targetId) {
            echo json_encode(['error' => 'Forum Not Found']);
            exit;
        }

        if ($type === 'POST') {
            $reasons = $this->reportModel->getReasonsByPostId($targetId);
        } elseif ($type === 'GROUPS') {
            $reasons = $this->reportModel->getReasonsByGroupId($targetId);
        } else {
            $reasons = $this->reportModel->getReasonsByForumId($targetId);
        }


        echo json_encode($reasons);
    }

    public function deleteForumByAdmin()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;
        $reportIdsString = $_POST['report_ids'] ?? null;
        $ownerId = $_POST['owner_id'] ?? null;
        $type = "DELETE";
        $userId = $_SESSION['user_id'];

        if (empty($forumId)) {
            echo json_encode(['success' => false, 'message' => 'Forum Not Found']);
            exit;
        }

        $forumToDelete = $this->forumModel->getForumById($forumId);

        $reportDeleteSuccess = true;
        $forumDeleteSuccess = false;

        if (!empty($reportIdsString)) {
            $reportIdsArray = array_map('trim', explode(',', $reportIdsString));

            if (!$this->reportModel->deleteReports($reportIdsArray)) {
                $reportDeleteSuccess = false;
            }
        }

        if ($reportDeleteSuccess) {
            if ($this->forumModel->deleteForum($forumId)) {
                $forumDeleteSuccess = true;

                $photoPath = $forumToDelete['PATH_PHOTO'] ?? null;
                if (!empty($photoPath) && $photoPath !== 'default.png') {
                    $fullPath = __DIR__ . '/../../storage/forums/photos/' . $photoPath;
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }

                $this->notificationModel->addNotification($ownerId, $userId, $forumId, $type, 'FORUM');
            }
        }

        if ($forumDeleteSuccess && $reportDeleteSuccess) {
            $response = ['success' => true, 'message' => 'Forum dan laporan terkait berhasil dihapus!'];
        } else if (!$forumDeleteSuccess) {
            $response = ['success' => false, 'message' => 'Gagal menghapus forum. Laporan mungkin sudah terhapus.'];
        } else {
            $response = ['success' => false, 'message' => 'Gagal menghapus laporan, forum tidak jadi dihapus.'];
        }

        echo json_encode($response);
        exit;
    }

    public function deleteGroupByAdmin()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
            exit;
        }

        $groupId = $_POST['group_id'] ?? null;
        $reportIdsString = $_POST['report_ids'] ?? null;
        $ownerId = $_POST['owner_id'] ?? null;
        $type = "DELETE";
        $userId = $_SESSION['user_id'];

        if (empty($groupId)) {
            echo json_encode(['success' => false, 'message' => 'Group Not Found.']);
            exit;
        }

        $forumToDelete = $this->groupModel->findById($groupId);

        $reportDeleteSuccess = true;
        $forumDeleteSuccess = false;

        if (!empty($reportIdsString)) {
            $reportIdsArray = array_map('trim', explode(',', $reportIdsString));

            if (!$this->reportModel->deleteReports($reportIdsArray)) {
                $reportDeleteSuccess = false;
            }
        }

        if ($reportDeleteSuccess) {
            if ($this->groupModel->delete($groupId)) {
                $forumDeleteSuccess = true;

                $photoPath = $forumToDelete['PATH_PHOTO'] ?? null;
                if (!empty($photoPath) && $photoPath !== 'default.png') {
                    $fullPath = __DIR__ . '/../../storage/forums/photos/' . $photoPath;
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                    }
                }

                $this->notificationModel->addNotification($ownerId, $userId, $groupId, $type, 'FORUM');
            }
        }

        if ($forumDeleteSuccess && $reportDeleteSuccess) {
            $response = ['success' => true, 'message' => 'Forum dan laporan terkait berhasil dihapus!'];
        } else if (!$forumDeleteSuccess) {
            $response = ['success' => false, 'message' => 'Gagal menghapus forum. Laporan mungkin sudah terhapus.'];
        } else {
            $response = ['success' => false, 'message' => 'Gagal menghapus laporan, forum tidak jadi dihapus.'];
        }

        echo json_encode($response);
        exit;
    }

    public function sendWarningNotification()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed.',
            ]);
            return;
        }

        $targetId = $_POST['target_id'] ?? null;
        $ownerId  = $_POST['owner_id'] ?? null;
        $userId   = $_SESSION['user_id'] ?? null;
        $type     = $_POST['type_target'] ?? null;

        if (empty($targetId) || empty($ownerId) || empty($userId)) {
            echo json_encode([
                'success' => false,
                'message' => 'Data tidak lengkap.',
            ]);
            return;
        }

        $this->notificationModel->addNotification($ownerId, $userId, $targetId, 'WARNING', $type);

        echo json_encode([
            'success' => true,
            'message' => 'Peringatan berhasil dikirim.',
        ]);
    }

    public function deletePostByAdmin()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed.',
            ]);
            return;
        }

        $postId = $_POST['post_id'] ?? null;
        $reportIdsString = $_POST['report_ids'] ?? null;
        $ownerId = $_POST['owner_id'] ?? null;
        $type = "DELETE";
        $userId = $_SESSION['user_id'];

        if (empty($postId) || empty($ownerId)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required parameters.',
            ]);
            return;
        }

        $postMedia = $this->reportModel->getMediaByPostId($postId);
        $mediaPaths = [];

        if ($postMedia && is_array($postMedia)) {
            foreach ($postMedia as $mediaRow) {
                if (!empty($mediaRow['MEDIA_PATH'])) {
                    $mediaPaths[] = $mediaRow['MEDIA_PATH'];
                }
            }
        }

        foreach ($mediaPaths as $path) {
            $filePath = realpath(__DIR__ . '/../../' . $path);
            if ($filePath && file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $reportDeleteSuccess = true;
        if (!empty($reportIdsString)) {
            $reportIdsArray = array_map('trim', explode(',', $reportIdsString));
            if (!$this->reportModel->deleteReports($reportIdsArray)) {
                $reportDeleteSuccess = false;
            }
        }

        $deleteSuccess = $this->reportModel->deletePostByAdmin($postId);

        if ($deleteSuccess) {
            $this->notificationModel->addNotification(
                $ownerId,
                $userId,
                $postId,
                $type,
                'POST'
            );
        }


        echo json_encode([
            'success' => $deleteSuccess && $reportDeleteSuccess,
            'message' => $deleteSuccess
                ? 'Post dan laporan berhasil dihapus.'
                : 'Gagal menghapus post.',
        ]);
    }
}
