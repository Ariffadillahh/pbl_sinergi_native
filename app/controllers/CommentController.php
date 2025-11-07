<?php

require_once __DIR__ . '/../models/Posts/CommentModel.php';
require_once __DIR__ . '/../models/Notif/NotificationModel.php';
require_once __DIR__ . '/../models/Posts/PostModel.php';

class CommentController
{
    private $commentModel;
    private $postModel;
    private $notificationModel;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
        $this->notificationModel = new NotificationModel();
        $this->postModel = new PostModel();
    }

    public function getModel()
    {
        return $this->commentModel;
    }

    public function getComments($postId)
    {
        $comments = $this->commentModel->getCommentsByPostId($postId);

        echo json_encode([
            'success' => true,
            'comments' => $comments
        ]);
    }

    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ob_clean();
            header('Content-Type: application/json');
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }


        $userId = $_SESSION['user_id'] ?? null;
        $postId = $_POST['post_id'] ?? null;
        $message = trim($_POST['message'] ?? '');

        if (!$userId || !$postId || $message === '') {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        $newComment = $this->commentModel->addComment($postId, $userId, $message);

        if ($newComment) {
            $owner = $this->commentModel->getPostOwner($postId);
            if ($owner && $owner['ID'] !== $userId) {
                $this->notificationModel->addNotification(
                    $owner['ID'],
                    $userId,
                    $postId,
                    'REPLY_POST'
                );
            }

            preg_match_all('/@(\w+)/', $message, $matches);
            $mentionedUsernames = !empty($matches[1]) ? array_unique($matches[1]) : [];

            if (!empty($mentionedUsernames)) {
                $mentionedUsers = $this->postModel->getUsersByUsernames($mentionedUsernames);

                foreach ($mentionedUsers as $mentionedUser) {
                    $isNotCommenter = $mentionedUser['ID'] !== $userId;
                    $isNotPostOwner = !$owner || ($mentionedUser['ID'] !== $owner['ID']);

                    if ($isNotCommenter && $isNotPostOwner) {
                        $this->notificationModel->addNotification(
                            $mentionedUser['ID'],
                            $userId,
                            $postId,
                            'MENTION'
                        );
                    }
                }
            }
            echo json_encode(['success' => true, 'message' => 'Komentar berhasil ditambahkan']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan komentar']);
        }
    }


    public function addReply()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ob_clean();
            header('Content-Type: application/json');
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $commentId = $_POST['comment_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        $postId = $_POST['post_id'] ?? null;
        $parentId = empty($_POST['parent_id']) ? null : $_POST['parent_id'];

        if (!$userId || !$commentId || $message === '') {
            ob_clean();
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        $newReplyId = $this->commentModel->addReply($commentId, $userId, $message, $parentId);

        if ($newReplyId) {

            $commentDetails = $this->commentModel->getCommentDetails($commentId);

            if ($commentDetails['success']) {
                $commentOwnerId = $commentDetails['details']['USER_ID'];
                $postId = $commentDetails['details']['POST_ID'];

                error_log("=== DEBUG REPLY ===");
                error_log("Comment Owner ID: " . $commentOwnerId);
                error_log("Post ID: " . $postId);
                error_log("Current User ID: " . $userId);

                if (!$parentId) {
                    // Notif untuk Yang punya Post 
                    $owner = $this->commentModel->getPostOwner($postId);
                    if ($owner && $owner['ID'] !== $userId) {
                        $this->notificationModel->addNotification(
                            $owner['ID'],
                            $userId,
                            $postId,
                            'REPLY_POST'
                        );
                    }
                } else {
                    // Notif untuk Yang punya Post 
                    $owner = $this->commentModel->getPostOwner($postId);
                    if ($owner && $owner['ID'] !== $userId) {
                        $this->notificationModel->addNotification(
                            $owner['ID'],
                            $userId,
                            $postId,
                            'REPLY_POST'
                        );
                    }
                    // Notif untuk yang di reply / Perentnya
                    $ownerReply = $this->commentModel->getReplyDetails($parentId);
                    if ($ownerReply && $ownerReply['ID'] !== $userId) {
                        $this->notificationModel->addNotification(
                            $ownerReply['ID'],
                            $userId,
                            $postId,
                            'REPLY_POST'
                        );
                    }
                }


                preg_match_all('/@(\w+)/', $message, $matches);
                $mentionedUsernames = !empty($matches[1]) ? array_unique($matches[1]) : [];

                if (!empty($mentionedUsernames)) {
                    error_log("Mention ditemukan: " . implode(', ', $mentionedUsernames));

                    $mentionedUsers = $this->postModel->getUsersByUsernames($mentionedUsernames);

                    foreach ($mentionedUsers as $mentionedUser) {
                        $isNotReplier = $mentionedUser['ID'] !== $userId;
                        $isNotCommentOwner = $mentionedUser['ID'] !== $commentOwnerId;

                        if ($isNotReplier && $isNotCommentOwner) {
                            error_log("➡️ Menambahkan notifikasi MENTION untuk user ID: {$mentionedUser['ID']}");
                            $this->notificationModel->addNotification(
                                $mentionedUser['ID'],
                                $userId,
                                $postId,
                                'MENTION'
                            );
                        }
                    }
                } else {
                    error_log("Tidak ada mention ditemukan dalam balasan.");
                }
            }

            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Balasan berhasil ditambahkan', 'reply_id' => $newReplyId]);
        } else {
            ob_clean();
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan balasan']);
        }
    }
}