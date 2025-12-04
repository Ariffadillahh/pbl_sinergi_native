<?php
require_once __DIR__ . '/../models/Forum/TopicModel.php';
require_once __DIR__ . '/../models/Forum/Forum.php';
require_once __DIR__ . '/../models/Posts/CommentModel.php';

class TopicsController
{
    private $topicModel;
    private $forumModel;
    private $commentModel;

    public function __construct()
    {
        $this->topicModel = new TopicModel();
        $this->forumModel = new ForumModel();
        $this->commentModel = new CommentModel();
    }

    public function index($topicId)
    {
        $topic = $this->topicModel->getTopicById($topicId);

        if (!$topic) {
            header("Location: " . BASEURL . "/forums");
            exit;
        }

        $forumId = $topic['FORUM_ID'] ?? null;
        $forumById = $this->forumModel->getForumById($forumId);

        $currentUserId = $_SESSION['user_id'] ?? null;
        $currentUserRole = $_SESSION['role'] ?? '';

        $isMember = false;
        if ($currentUserId) {
            $isMember = $this->forumModel->isMember($forumId, $currentUserId);
        }

        $isAdmin = ($currentUserRole === 'ADMIN');

        if (!$isMember && !$isAdmin) {
            header("Location: " . BASEURL . "/forum/" . $forumId);
            exit;
        }

        $comments = $this->commentModel->getCommentsByTopicId($topicId);

        $data = [
            'title'     => 'Detail Topik',
            'forumById' => $forumById,
            'topic'     => $topic,
            'comments'  => $comments,
            'isMember'  => $isMember 
        ];

        extract($data);

        $contentViewForum = __DIR__ . '/../views/forum/detail/topic/index.php';
        require_once __DIR__ . '/../views/forum/layout.php';
    }

    public function create()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized: Silakan login terlebih dahulu.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['content'] ?? '';
            $forumId = $_POST['forum_id'] ?? '';

            if (empty($content) && empty($_FILES['images']['name'][0])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Konten atau gambar tidak boleh kosong!']);
                exit;
            }

            if (empty($forumId)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Forum ID tidak ditemukan!']);
                exit;
            }

            $uploadedFiles = [];
            $uploadErrors = [];

            if (!empty($_FILES['images']['name'][0])) {
                $count = count($_FILES['images']['name']);

                for ($i = 0; $i < $count; $i++) {
                    $fileName = $_FILES['images']['name'][$i];
                    $fileTmp  = $_FILES['images']['tmp_name'][$i];
                    $fileSize = $_FILES['images']['size'][$i];
                    $fileError = $_FILES['images']['error'][$i];

                    if ($fileError === 0) {
                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];
                        if (!in_array($ext, $allowedExts)) {
                            $uploadErrors[] = "File '$fileName' memiliki format yang tidak diizinkan.";
                            continue;
                        }

                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                        $mediaType = $isImage ? 'IMAGE' : 'FILE';

                        $newName = uniqid() . '.' . $ext;

                        $targetDir = __DIR__ . '/../../storage/forums/topics/';

                        if (!is_dir($targetDir)) {
                            mkdir($targetDir, 0777, true);
                        }

                        $targetPath = $targetDir . $newName;

                        if (move_uploaded_file($fileTmp, $targetPath)) {
                            $uploadedFiles[] = [
                                'path' => $newName,
                                'type' => $mediaType,
                                'original_filename' => $fileName
                            ];
                        } else {
                            $uploadErrors[] = "Gagal mengupload file: $fileName";
                        }
                    }
                }
            }

            if (!empty($uploadErrors)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => implode(', ', $uploadErrors)]);
                exit;
            }


            $dataPost = [
                'forum_id' => $forumId,
                'content'  => $content,
                'user_id'  => $_SESSION['user_id']
            ];

            $result = $this->topicModel->createPostWithMedia($dataPost, $uploadedFiles);

            header('Content-Type: application/json');
            if ($result['status']) {
                echo json_encode(['success' => true, 'message' => 'Post created successfully!']);
            } else {
                foreach ($uploadedFiles as $file) {
                    $filePath = __DIR__ . '/../../storage/forums/topics/' . $file['path'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                echo json_encode(['success' => false, 'message' => 'Database error: ' . $result['message']]);
            }
            exit;
        }
    }

    public function delete()
    {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topicId = $_POST['topic_id'] ?? '';
            $forumId = $_POST['forum_id'] ?? '';

            if (empty($topicId) || empty($forumId)) {
                echo json_encode(['success' => false, 'message' => 'Data tidak lengkap (ID Topik/Forum hilang)']);
                exit;
            }

            $result = $this->topicModel->deleteTopicById($topicId, $forumId);

            echo json_encode(['success' => $result['status'], 'message' => $result['message']]);
            exit;
        }
    }

    public function pinTopic()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Anda harus login.']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topicId = $_POST['topic_id'] ?? '';

            if (empty($topicId)) {
                echo json_encode(['success' => false, 'message' => 'ID Topik tidak valid.']);
                exit;
            }

            $result = $this->topicModel->togglePin($topicId);

            echo json_encode($result);
            exit;
        }
    }
}
