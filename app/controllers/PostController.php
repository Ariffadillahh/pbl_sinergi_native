<?php
require_once __DIR__ . '/../models/Posts/PostModel.php';
require_once __DIR__ . '/../models/Notif/NotificationModel.php';

class PostController
{
    private $postModel;
    private $notificationModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->notificationModel = new NotificationModel();
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

        $newPost = $this->postModel->createPost($user['ID'], $caption, $uploadedPaths);

        if ($newPost && isset($newPost['success']) && $newPost['success'] === true) {
            $newPostId = $newPost['ID'];

            preg_match_all('/@(\w+)/', $caption, $matches);
            $mentionedUsernames = !empty($matches[1]) ? array_unique($matches[1]) : [];

            if (!empty($mentionedUsernames)) {
                $mentionedUsers = $this->postModel->getUsersByUsernames($mentionedUsernames);

                foreach ($mentionedUsers as $mentionedUser) {
                    if ($mentionedUser['ID'] !== $user['ID']) {
                        $this->notificationModel->addNotification(
                            $mentionedUser['ID'],
                            $user['ID'],
                            $newPostId,
                            'MENTION'
                        );
                    }
                }
            }

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

        $uploadDir = __DIR__ . '/../../storage/posts/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newlyUploadedPaths = [];
        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            foreach (array_keys($_FILES['images']['name']) as $i) {
                if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                    if ($_FILES['images']['error'][$i] == UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    echo json_encode(['success' => false, 'message' => 'Error saat upload file: ' . $_FILES['images']['error'][$i]]);
                    exit;
                }

                $fileExtension = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($fileExtension, $allowedExtensions)) {
                    echo json_encode(['success' => false, 'message' => 'Format file tidak diizinkan. Hanya (JPG, JPEG, PNG, GIF, WEBP).']);
                    exit;
                }

                $fileName = uniqid('post_', true) . '-' . basename($_FILES['images']['name'][$i]);
                $targetFile = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFile)) {
                    echo json_encode(['success' => false, 'message' => 'Gagal memindahkan file upload. Periksa izin folder.']);
                    exit;
                }
                $newlyUploadedPaths[] = 'storage/posts/images/' . $fileName;
            }
        }

        $mediaToDelete = $_POST['deleted_media'] ?? [];
        foreach ($mediaToDelete as $path) {
            $filePath = realpath(__DIR__ . '/../../' . $path);
            $baseDir = realpath($uploadDir);
            if ($filePath && strpos($filePath, $baseDir) === 0 && file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $existingMedia = $_POST['existing_media'] ?? [];
        $finalMediaPaths = array_merge($existingMedia, $newlyUploadedPaths);

        if (empty($caption) && empty($finalMediaPaths)) {
            echo json_encode(['success' => false, 'message' => 'Postingan tidak boleh kosong.']);
            exit;
        }

        $success = $this->postModel->updatePost($postId, $_SESSION['user_id'], $caption, $finalMediaPaths);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Postingan berhasil diperbarui']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui postingan di database.']);
        }
        exit;
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
