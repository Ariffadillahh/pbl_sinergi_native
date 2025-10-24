<?php

require_once __DIR__ . '/../models/Posts/CommentModel.php';

class CommentController
{
    private $commentModel;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
    }

    /**
     * Ambil semua komentar + reply untuk 1 post
     */
    public function getComments($postId)
    {
        $comments = $this->commentModel->getCommentsByPostId($postId);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'comments' => $comments
        ]);
    }

    /**
     * Tambah komentar utama di sebuah post
     */
    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        $postId = $_POST['post_id'] ?? null;
        $message = trim($_POST['message'] ?? '');

        if (!$userId || !$postId || $message === '') {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        $result = $this->commentModel->addComment($postId, $userId, $message);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Komentar berhasil ditambahkan']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan komentar']);
        }
    }

    /**
     * Tambah balasan komentar
     */
    public function addReply()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        session_start();
        $userId = $_SESSION['user_id'] ?? null;
        $commentId = $_POST['comment_id'] ?? null;
        $message = trim($_POST['message'] ?? '');

        if (!$userId || !$commentId || $message === '') {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        $result = $this->commentModel->addReply($commentId, $userId, $message);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Balasan berhasil ditambahkan']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan balasan']);
        }
    }
}
