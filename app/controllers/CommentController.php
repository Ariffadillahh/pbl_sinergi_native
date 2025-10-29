<?php

require_once __DIR__ . '/../models/Posts/CommentModel.php';

class CommentController
{
    private $commentModel;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
    }

    public function getModel()
    {
        return $this->commentModel;
    }


    public function getComments($postId)
    {
        $comments = $this->commentModel->getCommentsByPostId($postId);

        header('Content-Type: application/json');

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

        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        $postId = $_POST['post_id'] ?? null;
        $message = trim($_POST['message'] ?? '');

        if (!$userId || !$postId || $message === '') {
            ob_clean();
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        $result = $this->commentModel->addComment($postId, $userId, $message);

        ob_clean();
        header('Content-Type: application/json');
        if ($result) {
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

        session_start();

        $userId = $_SESSION['user_id'] ?? null;
        $commentId = $_POST['comment_id'] ?? null;
        $message = trim($_POST['message'] ?? '');

        $parentId = empty($_POST['parent_id']) ? null : $_POST['parent_id'];

        if (!$userId || !$commentId || $message === '') {
            ob_clean();
            header('Content-Type: application/json');
            http_response_code(400); 
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        $result = $this->commentModel->addReply($commentId, $userId, $message, $parentId);

        ob_clean();
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Balasan berhasil ditambahkan', 'reply_id' => $result]);
        } else {
            http_response_code(500); 
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan balasan']);
        }
    }
}
