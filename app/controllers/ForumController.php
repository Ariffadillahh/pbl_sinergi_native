<?php
require_once __DIR__ . '/../models/Forum/Forum.php';

class forumController
{

    private $forumModel;

    public function __construct()
    {
        $this->forumModel = new ForumModel();
    }

    public function index()
    {
        $myUserId = $_SESSION['user_id'] ?? null;

        $filter = $_GET['filter'] ?? 'all'; 
        $search = $_GET['search'] ?? '';
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit  = 9; 

        if ($page < 1) $page = 1;

        $offset = ($page - 1) * $limit;

        $forums = $this->forumModel->getForumsWithFilter($myUserId, $filter, $search, $limit, $offset);

        $totalForums = $this->forumModel->countForumsWithFilter($myUserId, $filter, $search);
        $totalPages  = ceil($totalForums / $limit);

        $contentViewForum = __DIR__ . '/../views/forum/index.php';
        require_once __DIR__ . '/../views/forum/layout.php';
    }

    public function forumById($forumId)
    {
        $forumById = $this->forumModel->getForumById($forumId);
        $contentViewForum = __DIR__ . '/../views/forum/detail/index.php';
        require_once __DIR__ . '/../views/forum/layout.php';
    }

    public function createForum()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized: Silakan login terlebih dahulu.']);
            return;
        }

        $name = isset($_POST['NAME']) ? trim($_POST['NAME']) : '';
        $about = isset($_POST['ABOUT']) ? trim($_POST['ABOUT']) : '';
        $isPrivate = isset($_POST['IS_PRIVATE']) ? intval($_POST['IS_PRIVATE']) : 0;
        $accessKey = isset($_POST['ACCESS_KEY']) ? trim($_POST['ACCESS_KEY']) : null;
        $ownerId = $_SESSION['user_id'];

        if (empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama forum wajib diisi.']);
            return;
        }

        if ($isPrivate == 1 && empty($accessKey)) {
            echo json_encode(['status' => 'error', 'message' => 'Forum private memerlukan kunci akses.']);
            return;
        }

        $pathPhoto = null;
        $pathThumbnail = null;

        $uploadDir = __DIR__ . '/../../storage/forums/photos/';
        $uploadDirThumb = __DIR__ . '/../../storage/forums/thumbnail/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!is_dir($uploadDirThumb)) {
            mkdir($uploadDirThumb, 0777, true);
        }

        if (isset($_FILES['PATH_PHOTO']) && $_FILES['PATH_PHOTO']['error'] === UPLOAD_ERR_OK) {
            $pathPhoto = $this->uploadFile($_FILES['PATH_PHOTO'], $uploadDir, 'profile');
            if (!$pathPhoto) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal upload foto profil (format/size salah).']);
                return;
            }
        }

        if (isset($_FILES['PATH_THUMBNAIL']) && $_FILES['PATH_THUMBNAIL']['error'] === UPLOAD_ERR_OK) {
            $pathThumbnail = $this->uploadFile($_FILES['PATH_THUMBNAIL'], $uploadDirThumb, 'banner');
            if (!$pathThumbnail) {
                echo json_encode(['status' => 'error', 'message' => 'Gagal upload banner (format/size salah).']);
                return;
            }
        }

        $data = [
            'NAME' => $name,
            'ABOUT' => $about,
            'IS_PRIVATE' => $isPrivate,
            'ACCESS_KEY' => ($isPrivate == 1) ? $accessKey : null,
            'OWNER_ID' => $ownerId,
            'PATH_PHOTO' => $pathPhoto,
            'PATH_THUMBNAIL' => $pathThumbnail
        ];

        try {
            $result = $this->forumModel->createForum($data);

            if ($result['success']) {

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Forum berhasil dibuat!',
                    'id' => $result['ID']
                ]);
            } else {

                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan ke database.',
                    'error' => $result['error']
                ]);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
        }
    }

    private function uploadFile($file, $targetDir, $prefix)
    {
        $allowedExts = ['jpg', 'jpeg', 'png'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExt, $allowedExts)) {
            return false;
        }

        if ($fileSize > 2 * 1024 * 1024) {
            return false;
        }

        $newFileName = $prefix . '_' . uniqid() . '.' . $fileExt;
        $targetPath = $targetDir . $newFileName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            return $newFileName;
        }

        return false;
    }
}
