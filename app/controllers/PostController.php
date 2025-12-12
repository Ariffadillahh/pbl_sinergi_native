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

    public function fetchPosts()
    {
        $posts = $this->postModel->getAllPosts();

        return $posts;
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
            echo json_encode(['success' => false, 'message' => 'You must log in to create a post.']);
            exit;
        }

        $user = [
            'ID' => $_SESSION['user_id'],
            'ROLE' => $_SESSION['role'],
            'USERNAME' => $_SESSION['username'],
        ];

        if (!in_array($user['ROLE'], ['MAHASISWA', 'DOSEN', 'ADMIN'])) {
            echo json_encode(['success' => false, 'message' => 'Your role does not allow you to create posts.']);
            exit;
        }

        $caption = trim($_POST['content'] ?? '');
        $hasImages = isset($_FILES['images'])
            && isset($_FILES['images']['name'])
            && is_array($_FILES['images']['name'])
            && !empty($_FILES['images']['name'][0]);

        if (empty($caption) && !$hasImages) {
            echo json_encode([
                'success' => false,
                'message' => 'Write a caption or add an image first.'
            ]);
            exit;
        }

        $uploadDir = __DIR__ . '/../../storage/posts/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadedPaths = [];
        if ($hasImages) {
            $totalFiles = count($_FILES['images']['name']);

            if ($totalFiles > 5) {
                echo json_encode(['success' => false, 'message' => 'Maximum of 5 images per post.']);
                exit;
            }

            $maxTotalSize = 10 * 1024 * 1024; // 10 MB
            $currentTotalSize = 0;

            foreach ($_FILES['images']['size'] as $size) {
                $currentTotalSize += $size;
            }

            if ($currentTotalSize > $maxTotalSize) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Total size of images cannot exceed 10 MB.'
                ]);
                exit;
            }

            for ($i = 0; $i < $totalFiles; $i++) {
                if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                    continue;
                }

                $fileName = uniqid('post_', true) . '-' . basename($_FILES['images']['name'][$i]);
                $targetFile = $uploadDir . $fileName;
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($fileType, $allowedTypes)) {
                    echo json_encode(['success' => false, 'message' => 'Only image files (JPG, JPEG, PNG, GIF) are allowed.']);
                    exit;
                }

                if (!move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFile)) {
                    echo json_encode(['success' => false, 'message' => 'Failed to upload image file.']);
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
                            'MENTION',
                            'POST'
                        );
                    }
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Post created successfully.',
            ]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save the post to the database.']);
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
            echo json_encode(['success' => false, 'message' => 'You must log in to edit posts.']);
            exit;
        }

        $postId = $_POST['post_id'] ?? null;
        $caption = trim($_POST['content'] ?? '');

        $existingMedia = $_POST['existing_media'] ?? []; 
        $mediaToDelete = $_POST['deleted_media'] ?? [];  

        if (!$postId) {
            echo json_encode(['success' => false, 'message' => 'Post ID not found.']);
            exit;
        }

        $countNewUpload = 0;
        $totalNewSize = 0;

        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            $validFiles = array_filter($_FILES['images']['name']);
            $countNewUpload = count($validFiles);

            foreach ($_FILES['images']['size'] as $size) {
                $totalNewSize += $size;
            }
        }

        $countExisting = count($existingMedia);
        $totalCount = $countExisting + $countNewUpload;

        if ($totalCount > 5) {
            echo json_encode([
                'success' => false,
                'message' => "Maximum 5 images allowed. You have $countExisting existing and adding $countNewUpload new."
            ]);
            exit;
        }

        $maxTotalSize = 10 * 1024 * 1024; 
        if ($totalNewSize > $maxTotalSize) {
            echo json_encode([
                'success' => false,
                'message' => 'Total size of new images cannot exceed 10 MB.'
            ]);
            exit;
        }


        $uploadDir = __DIR__ . '/../../storage/posts/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $newlyUploadedPaths = [];

        if ($countNewUpload > 0) {
            foreach (array_keys($_FILES['images']['name']) as $i) {
                if (empty($_FILES['images']['name'][$i])) continue;

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
                    echo json_encode(['success' => false, 'message' => 'File format not allowed. Only (JPG, JPEG, PNG, GIF, WEBP).']);
                    exit;
                }

                $fileName = uniqid('post_', true) . '-' . basename($_FILES['images']['name'][$i]);
                $targetFile = $uploadDir . $fileName;

                if (!move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFile)) {
                    echo json_encode(['success' => false, 'message' => 'Failed to move the uploaded file. Check the folder permissions.']);
                    exit;
                }
                $newlyUploadedPaths[] = 'storage/posts/images/' . $fileName;
            }
        }

        foreach ($mediaToDelete as $path) {
            $filePath = realpath(__DIR__ . '/../../' . $path);
            $baseDir = realpath($uploadDir);
            if ($filePath && strpos($filePath, $baseDir) === 0 && file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $finalMediaPaths = array_merge($existingMedia, $newlyUploadedPaths);

        if (empty($caption) && empty($finalMediaPaths)) {
            echo json_encode(['success' => false, 'message' => 'Posts cannot be empty.']);
            exit;
        }

        $success = $this->postModel->updatePost($postId, $_SESSION['user_id'], $caption, $finalMediaPaths);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Post updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update the post in the database.']);
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

        if (empty($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'You must log in to delete a post.']);
            exit;
        }

        $postId = $_POST['post_id'] ?? null;

        if (!$postId) {
            echo json_encode(['success' => false, 'message' => 'Post ID not found.']);
            exit;
        }

        $success = $this->postModel->deletePost($postId, $_SESSION['user_id']);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Post successfully deleted.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete post.']);
            exit;
        }
    }
}
