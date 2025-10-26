<?php
require_once __DIR__ . '/../models/Posts/PostModel.php';

class PostController
{
    private $postModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
    }

    public function index()
    {
        header('Content-Type: application/json');

        try {
            $posts = $this->postModel->getAllPosts();
            echo json_encode(['success' => true, 'data' => $posts]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal mengambil data postingan.']);
        }
        $posts = $this->postModel->getAllPosts();
        foreach ($posts as &$post) {
            $post['MEDIA_PATHS'] = $this->postModel->getMediaByPostId($post['POST_ID']);
        }

        include __DIR__ . '/../views/components/postingan/post.php';
    }

    public function create()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        if (empty($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Anda harus login untuk membuat postingan.']);
            exit;
        }

        $user = [
            'ID' => $_SESSION['user_id'],
            'ROLE' => $_SESSION['role'],
            'USERNAME' => $_SESSION['username'],
        ];

        if (!in_array($user['ROLE'], ['MAHASISWA', 'DOSEN'])) {
            echo json_encode(['success' => false, 'message' => 'Role Anda tidak diizinkan membuat postingan.']);
            exit;
        }

        $caption = trim($_POST['content'] ?? '');
        if (empty($caption) && empty($_FILES['images']['name'][0])) {
            echo json_encode(['success' => false, 'message' => 'Tuliskan caption atau tambahkan gambar terlebih dahulu.']);
            exit;
        }

        $uploadDir = __DIR__ . '/../../storage/posts/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedPaths = [];
        if (!empty($_FILES['images']['name'][0])) {
            $totalFiles = count($_FILES['images']['name']);
            if ($totalFiles > 5) {
                echo json_encode(['success' => false, 'message' => 'Maksimal 5 gambar per postingan.']);
                exit;
            }

            for ($i = 0; $i < $totalFiles; $i++) {
                $fileName = uniqid('post_', true) . '-' . basename($_FILES['images']['name'][$i]);
                $targetFile = $uploadDir . $fileName;

                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($fileType, $allowedTypes)) {
                    echo json_encode(['success' => false, 'message' => 'Hanya file gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan.']);
                    exit;
                }

                if (!move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFile)) {
                    echo json_encode(['success' => false, 'message' => 'Gagal mengunggah file gambar.']);
                    exit;
                }

                $uploadedPaths[] = 'storage/posts/images/' . $fileName;
            }
        }

        $success = $this->postModel->createPost($user['ID'], $caption, $uploadedPaths);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Postingan berhasil dibuat.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan postingan ke database.']);
            exit;
        }
    }

    public function update()
    {
        header('Content-Type: application/json');
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        if (empty($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Anda harus login untuk mengedit postingan.']);
            exit;
        }

        $postId = $_POST['post_id'] ?? null;
        $caption = trim($_POST['content'] ?? '');
        if (!$postId) {
            echo json_encode(['success' => false, 'message' => 'ID postingan tidak ditemukan.']);
            exit;
        }

        if (empty($caption) && empty($_FILES['images']['name'][0]) && empty($_POST['existing_media'])) {
            echo json_encode(['success' => false, 'message' => 'Postingan tidak boleh kosong.']);
            exit;
        }

        $uploadDir = __DIR__ . '/../../storage/posts/images/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $uploadedPaths = [];
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['name'] as $i => $name) {
                if (empty($name)) continue;
                $fileName = uniqid('post_', true) . '-' . basename($name);
                $targetFile = $uploadDir . $fileName;
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($fileType, $allowedTypes)) {
                    echo json_encode(['success' => false, 'message' => 'Hanya gambar (JPG, JPEG, PNG, GIF) yang diperbolehkan']);
                    exit;
                }
                if (!move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFile)) {
                    echo json_encode(['success' => false, 'message' => 'Gagal upload gambar']);
                    exit;
                }
                $uploadedPaths[] = 'storage/posts/images/' . $fileName;
            }
        }

        $existingMedia = $_POST['existing_media'] ?? [];
        if (!is_array($existingMedia)) $existingMedia = explode(',', $existingMedia);

        $mediaToDelete = $_POST['deleted_media'] ?? [];
        if (!is_array($mediaToDelete)) $mediaToDelete = explode(',', $mediaToDelete);

        foreach ($mediaToDelete as $path) {
            $filePath = __DIR__ . '/../../' . $path;
            if (file_exists($filePath)) @unlink($filePath);
        }

        $allMediaPaths = array_values(array_unique(array_merge($existingMedia, $uploadedPaths)));

        $success = $this->postModel->updatePost($postId, $_SESSION['user_id'], $caption, $uploadedPaths, $mediaToDelete);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Postingan berhasil diperbarui']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui postingan']);
            exit;
        }
    }

    public function delete()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Anda harus login untuk menghapus postingan.']);
            exit;
        }

        $postId = $_POST['post_id'] ?? null;

        if (!$postId) {
            echo json_encode(['success' => false, 'message' => 'ID postingan tidak ditemukan.']);
            exit;
        }

        $success = $this->postModel->deletePost($postId, $_SESSION['user_id']);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Postingan berhasil dihapus.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus postingan.']);
            exit;
        }
    }
}
