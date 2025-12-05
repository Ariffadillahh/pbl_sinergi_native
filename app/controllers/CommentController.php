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

    public function getCommentsTopics($topicId)
    {
        $comments = $this->commentModel->getCommentsByTopicId($topicId);

        echo json_encode([
            'success' => true,
            'comments' => $comments
        ]);
    }

    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            ob_clean();
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
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Incomplete data.']);
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
                    'REPLY_POST',
                    'POST'
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
                            'MENTION',
                            'POST'
                        );
                    }
                }
            }
            echo json_encode(['success' => true, 'message' => 'Comment successfully added.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add comment.']);
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
            echo json_encode(['success' => false, 'message' => 'Incomplete data']);
            return;
        }

        $newReplyId = $this->commentModel->addReply($commentId, $userId, $message, $parentId);

        if ($newReplyId) {

            $commentDetails = $this->commentModel->getCommentDetails($commentId);

            if ($commentDetails['success']) {
                $commentOwnerId = $commentDetails['details']['USER_ID'];
                $postId = $commentDetails['details']['POST_ID'];

                if (!$parentId) {
                    $owner = $this->commentModel->getPostOwner($postId);
                    if ($owner && $owner['ID'] !== $userId) {
                        $this->notificationModel->addNotification(
                            $owner['ID'],
                            $userId,
                            $postId,
                            'REPLY_POST',
                            'POST'
                        );
                    }

                    $ownerComment = $this->commentModel->getCommentOwner($postId);
                    if ($ownerComment['success']) {
                        $commentData = $ownerComment['data'];

                        if ($commentData['ID'] !== $userId) {
                            $this->notificationModel->addNotification(
                                $commentData['ID'],
                                $userId,
                                $postId,
                                'REPLY_COMMENT',
                                'POST'
                            );
                        }
                    }
                } else {
                    $owner = $this->commentModel->getPostOwner($postId);
                    if ($owner && $owner['ID'] !== $userId) {
                        $this->notificationModel->addNotification(
                            $owner['ID'],
                            $userId,
                            $postId,
                            'REPLY_POST',
                            'POST'
                        );
                    }

                    $ownerComment = $this->commentModel->getCommentOwner($postId);
                    if ($ownerComment['success']) {
                        $commentData = $ownerComment['data'];

                        if ($commentData['ID'] !== $userId) {
                            $this->notificationModel->addNotification(
                                $commentData['ID'],
                                $userId,
                                $postId,
                                'REPLY_COMMENT',
                                'POST'
                            );
                        }
                    }


                    $ownerReply = $this->commentModel->getReplyDetails($parentId);
                    if ($ownerReply['success']) {
                        $replyData = $ownerReply['data'];
                        if ($replyData['ID'] !== $userId) {
                            $this->notificationModel->addNotification(
                                $replyData['ID'],
                                $userId,
                                $postId,
                                'REPLY_COMMENT',
                                'POST'
                            );
                        }
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
                                'MENTION',
                                'POST'
                            );
                        }
                    }
                } else {
                    error_log("Tidak ada mention ditemukan dalam balasan.");
                }
            }

            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Reply successfully added.', 'reply_id' => $newReplyId]);
        } else {
            ob_clean();
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add reply.']);
        }
    }

    public function deleteComment()
    {
        if (!isset($_POST['comment_id'])) {
            echo json_encode(['success' => false, 'message' => 'Comment ID not found.']);
            return;
        }

        $commentId = $_POST['comment_id'];

        $details = $this->commentModel->getCommentDetails($commentId);

        if (!$details['success']) {
            echo json_encode(['success' => false, 'message' => 'Comment not found.']);
            return;
        }

        $commentUserId = $details['details']['USER_ID'];
        $postId        = $details['details']['POST_ID'];

        $postOwner = $this->commentModel->getPostOwner($postId);

        if (
            $_SESSION['user_id'] !== $commentUserId &&
            $_SESSION['user_id'] !== $postOwner['ID'] &&
            ($_SESSION['role'] ?? '') !== 'ADMIN'
        ) {
            echo json_encode(['success' => false, 'message' => 'No permission to delete comments.']);
            return;
        }

        $success = $this->commentModel->deleteComment($commentId);

        echo json_encode(['success' => $success]);
    }

    public function deleteReply()
    {
        if (!isset($_POST['reply_id'])) {
            echo json_encode(['success' => false, 'message' => 'Reply ID not found']);
            return;
        }

        $replyId = $_POST['reply_id'];

        $details = $this->commentModel->getReplyDetails($replyId);

        if (!$details['success']) {
            echo json_encode(['success' => false, 'message' => 'Reply not found']);
            return;
        }

        $replyUserId = $details['data']['USER_ID'];
        $postId      = $details['data']['POST_ID'];

        $postOwner = $details['data']['POST_ID']
            ? $this->commentModel->getPostOwner($details['data']['POST_ID'])
            : null;

        if (
            $_SESSION['user_id'] !== $replyUserId &&
            $_SESSION['user_id'] !== ($postOwner['ID'] ?? null) &&
            ($_SESSION['role'] ?? '') !== 'ADMIN'
        ) {
            echo json_encode(['success' => false, 'message' => 'No permission to delete reply']);
            return;
        }

        $success = $this->commentModel->deleteReply($replyId);

        echo json_encode(['success' => $success]);
    }

    public function addCommentTopic()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ob_clean();
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $topikId = $_POST['topic_id'] ?? null;
        $message = trim($_POST['message'] ?? '');

        if (!$userId || !$topikId || $message === '') {
            ob_clean();
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        $newComment = $this->commentModel->addCommentTopik($topikId, $userId, $message);

        if ($newComment) {
            $owner = $this->commentModel->getTopicOwner($topikId);
            if ($owner && $owner['ID'] !== $userId) {
                $this->notificationModel->addNotification(
                    $owner['ID'],
                    $userId,
                    $topikId,
                    'REPLY_POST',
                    'FORUM'
                );
            }
            echo json_encode(['success' => true, 'message' => 'Comment successfully added.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add comment.']);
        }
        exit;
    }

    public function addReplyTopic()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $commentId = $_POST['comment_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        $topicId = $_POST['topik_id'] ?? null;
        $parentId = empty($_POST['parent_id']) ? null : $_POST['parent_id'];

        if (!$userId || !$commentId || $message === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Incomplete data.']);
            return;
        }

        $newReplyId = $this->commentModel->addReplyTopic($commentId, $userId, $message, $parentId);

        if ($newReplyId) {

            $commentDetails = $this->commentModel->getCommentDetailsTopic($commentId);

            if ($commentDetails['success']) {
                $commentOwnerId = $commentDetails['details']['USER_ID'];
                $topicId = $commentDetails['details']['TOPIC_ID'];

                if (!$parentId) {
                    $owner = $this->commentModel->getTopicOwner($topicId);
                    if ($owner && $owner['ID'] !== $userId) {
                        $this->notificationModel->addNotification(
                            $owner['ID'],
                            $userId,
                            $topicId,
                            'REPLY_POST',
                            'FORUM'
                        );
                    }

                    $ownerComment = $this->commentModel->getCommentTopicOwner($topicId);
                    if ($ownerComment['success']) {
                        $commentData = $ownerComment['data'];

                        if ($commentData['ID'] !== $userId) {
                            $this->notificationModel->addNotification(
                                $commentData['ID'],
                                $userId,
                                $topicId,
                                'REPLY_COMMENT',
                                'FORUM'
                            );
                        }
                    }
                } else {
                    $owner = $this->commentModel->getTopicOwner($topicId);
                    if ($owner && $owner['ID'] !== $userId) {
                        $this->notificationModel->addNotification(
                            $owner['ID'],
                            $userId,
                            $topicId,
                            'REPLY_POST',
                            'FORUM'
                        );
                    }

                    $ownerComment = $this->commentModel->getCommentTopicOwner($topicId);
                    if ($ownerComment['success']) {
                        $commentData = $ownerComment['data'];

                        if ($commentData['ID'] !== $userId) {
                            $this->notificationModel->addNotification(
                                $commentData['ID'],
                                $userId,
                                $topicId,
                                'REPLY_COMMENT',
                                'FOEUM'
                            );
                        }
                    }

                    $ownerReply = $this->commentModel->getReplyTopicDetails($parentId);
                    if ($ownerReply['success']) {
                        $replyData = $ownerReply['data'];
                        if ($replyData['ID'] !== $userId) {
                            $this->notificationModel->addNotification(
                                $replyData['ID'],
                                $userId,
                                $topicId,
                                'REPLY_COMMENT',
                                'FORUM'
                            );
                        }
                    }
                }
            }

            echo json_encode(['success' => true, 'message' => 'Reply successfully added.', 'reply_id' => $newReplyId]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add reply.']);
        }
        exit;
    }

    public function deleteCommentTopic()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['comment_id'])) {
            echo json_encode(['success' => false, 'message' => 'Comment ID not found.']);
            return;
        }

        $commentId = $_POST['comment_id'];

        $details = $this->commentModel->getCommentDetailsTopic($commentId);

        if (!$details['success']) {
            echo json_encode(['success' => false, 'message' => 'Comment not found.']);
            return;
        }

        $commentUserId = $details['details']['USER_ID'];
        $topicId       = $details['details']['TOPIC_ID'];

        $postOwner = $this->commentModel->getTopicOwner($topicId);

        if (
            $_SESSION['user_id'] !== $commentUserId &&
            $_SESSION['user_id'] !== $postOwner['ID'] &&
            ($_SESSION['role'] ?? '') !== 'ADMIN'
        ) {
            echo json_encode(['success' => false, 'message' => 'No permission to delete comments.']);
            return;
        }

        $success = $this->commentModel->deleteCommentTopic($commentId);

        echo json_encode(['success' => $success]);
        exit;
    }

    public function deleteReplyTopic()
    {
        header('Content-Type: application/json');

        if (!isset($_POST['reply_id'])) {
            echo json_encode(['success' => false, 'message' => 'Reply ID not found']);
            return;
        }

        $replyId = $_POST['reply_id'];

        $details = $this->commentModel->getReplyDetailsTopic($replyId);

        if (!$details['success']) {
            echo json_encode(['success' => false, 'message' => 'Reply not found']);
            return;
        }

        $replyUserId = $details['data']['USER_ID'];
        $topicId      = $details['data']['TOPIC_ID'];

        $postOwner = $details['data']['TOPIC_ID']
            ? $this->commentModel->getTopicOwner($topicId)
            : null;

        if (
            $_SESSION['user_id'] !== $replyUserId &&
            $_SESSION['user_id'] !== ($postOwner['ID'] ?? null) &&
            ($_SESSION['role'] ?? '') !== 'ADMIN'
        ) {
            echo json_encode(['success' => false, 'message' => 'No permission to delete reply.']);
            return;
        }

        $success = $this->commentModel->deleteReplyTopic($replyId);

        echo json_encode(['success' => $success]);
        exit;
    }
}
