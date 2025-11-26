<?php
require_once __DIR__ . '/../models/Forum/Forum.php';
require_once __DIR__ . '/../models/Forum/TopicModel.php';


class forumController
{

    private $forumModel;
    private $topicModel;

    public function __construct()
    {
        $this->forumModel = new ForumModel();
        $this->topicModel = new TopicModel();
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

        if (!$forumById) {
            header("Location: " . BASEURL . "/forums");
            exit;
        }

        $currentUserId = $_SESSION['user_id'] ?? null;
        $currentUserRole = $_SESSION['role'] ?? ''; 

        $forumOwnerId = $forumById['OWNER_ID'] ?? $forumById['USER_ID'] ?? null;

        $canUnpin = false;

        if ($currentUserId) {
            if ($currentUserRole === 'ADMIN' || $currentUserRole === 'DOSEN') {
                $canUnpin = true;
            }
            elseif ($currentUserId == $forumOwnerId) {
                $canUnpin = true;
            }
        }

        $membersForum = $this->forumModel->getForumMembers($forumId);
        $isMember = false;

        if ($currentUserId) {
            $isMember = $this->forumModel->isMember($forumId, $currentUserId);
        }

        if ((int)$forumById['IS_PRIVATE'] === 1 && !$isMember) {
            header("Location: " . BASEURL . "/forums");
            exit;
        }

        $postLimit = $isMember ? null : 1;

        $pinned_topics = $this->topicModel->getPinnedTopics($forumId);
        $topics = $this->topicModel->getTopicsByForumId($forumId);

        $data = [
            'forumById'     => $forumById,
            'membersForum'  => $membersForum,
            'isMember'      => $isMember,
            'postLimit'     => $postLimit,
            'pinned_topics' => $pinned_topics, 
            'topics'        => $topics,       
            'can_unpin'     => $canUnpin      
        ];
      
        extract($data);

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

    public function update()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $forumId = $_POST['ID'] ?? null;
        if (!$forumId) {
            echo json_encode(['success' => false, 'message' => 'ID Forum tidak ditemukan']);
            exit;
        }

        $oldForumData = $this->forumModel->getForumById($forumId);
        if (!$oldForumData) {
            echo json_encode(['success' => false, 'message' => 'Forum tidak ditemukan']);
            exit;
        }

        if ($oldForumData['OWNER_ID'] !== $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Anda bukan pemilik forum ini!']);
            exit;
        }

        $isPrivate = $_POST['IS_PRIVATE'] ?? 0;

        $updateData = [
            'NAME'       => $_POST['NAME'],
            'ABOUT'      => $_POST['ABOUT'],
            'IS_PRIVATE' => $isPrivate,
            'ACCESS_KEY' => ($isPrivate == 1) ? $_POST['ACCESS_KEY'] : null
        ];

        if (isset($_FILES['PATH_PHOTO']) && $_FILES['PATH_PHOTO']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['PATH_PHOTO'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = 'forum_profile_' . uniqid() . '.' . $ext;
            $uploadDir = __DIR__ . '/../../storage/forums/photos/';

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
                $updateData['PATH_PHOTO'] = $newFileName;

                if (!empty($oldForumData['PATH_PHOTO'])) {
                    $oldFile = $uploadDir . $oldForumData['PATH_PHOTO'];
                    if (file_exists($oldFile)) unlink($oldFile);
                }
            }
        }

        if (isset($_FILES['PATH_THUMBNAIL']) && $_FILES['PATH_THUMBNAIL']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['PATH_THUMBNAIL'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = 'forum_banner_' . uniqid() . '.' . $ext;
            $uploadDir = __DIR__ . '/../../storage/forums/thumbnail/';

            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
                $updateData['PATH_THUMBNAIL'] = $newFileName;

                if (!empty($oldForumData['PATH_THUMBNAIL'])) {
                    $oldFile = $uploadDir . $oldForumData['PATH_THUMBNAIL'];
                    if (file_exists($oldFile)) unlink($oldFile);
                }
            }
        }

        if ($this->forumModel->updateForum($forumId, $updateData)) {
            echo json_encode(['success' => true, 'message' => 'Perubahan berhasil disimpan!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan ke database.']);
        }
    }

    public function delete()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $forumId = $_POST['ID'] ?? null;
        if (!$forumId) {
            echo json_encode(['success' => false, 'message' => 'ID Forum tidak ditemukan']);
            exit;
        }

        $forum = $this->forumModel->getForumById($forumId);

        if (!$forum) {
            echo json_encode(['success' => false, 'message' => 'Forum tidak ditemukan']);
            exit;
        }

        if ($forum['OWNER_ID'] !== $_SESSION['user_id']) {
            echo json_encode(['success' => false, 'message' => 'Anda bukan pemilik forum ini!']);
            exit;
        }

        $assets = $this->forumModel->getAllForumAssets($forumId);

        if ($this->forumModel->deleteForum($forumId)) {

            if (!empty($assets['forum_thumb'])) {
                $path = __DIR__ . '/../../storage/forums/thumbnail/' . $assets['forum_thumb'];
                if (file_exists($path)) unlink($path);
            }

            if (!empty($assets['forum_photo'])) {
                $path = __DIR__ . '/../../storage/forums/photos/' . $assets['forum_photo'];
                if (file_exists($path)) unlink($path);
            }

            if (!empty($assets['topic_media'])) {
                foreach ($assets['topic_media'] as $mediaFile) {
                    $path = __DIR__ . '/../../storage/topics/media/' . $mediaFile;
                    if (file_exists($path)) unlink($path);
                }
            }

            echo json_encode(['success' => true, 'message' => 'Forum dan seluruh isinya berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus data dari database']);
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

    public function joinForum()
    {
        ob_clean();
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["success" => false, "message" => "Sesi habis, silakan login kembali."]);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;
        $key     = trim($_POST['key'] ?? '');

        if (empty($forumId)) {
            echo json_encode(["success" => false, "message" => "Forum ID hilang/kosong."]);
            exit;
        }

        $forum = $this->forumModel->getForumById($forumId);

        if (!$forum) {
            echo json_encode(["success" => false, "message" => "Forum tidak ditemukan di database."]);
            exit;
        }

        if ($this->forumModel->isMember($forumId, $_SESSION['user_id'])) {
            echo json_encode(["success" => false, "message" => "Anda sudah bergabung di forum ini."]);
            exit;
        }

        if ((int)$forum['IS_PRIVATE'] === 1) {
            if (empty($key)) {
                echo json_encode(["success" => false, "message" => "Key wajib diisi untuk forum private!"]);
                exit;
            }
            if ($key !== $forum['ACCESS_KEY']) {
                echo json_encode(["success" => false, "message" => "Access Key salah!"]);
                exit;
            }
        }

        if ($this->forumModel->addMember($forumId, $_SESSION['user_id'])) {
            echo json_encode(["success" => true, "message" => "Berhasil bergabung!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menyimpan data ke database."]);
        }
        exit;
    }

    public function leaveForum()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(["success" => false, "message" => "Sesi habis, silakan login kembali."]);
            exit;
        }

        $forumId = $_POST['forum_id'] ?? null;

        if (empty($forumId)) {
            echo json_encode(["success" => false, "message" => "Forum ID hilang/kosong."]);
            exit;
        }

        if (!$this->forumModel->isMember($forumId, $_SESSION['user_id'])) {
            echo json_encode(["success" => false, "message" => "Anda bukan anggota forum ini."]);
            exit;
        }

        if ($this->forumModel->removeMember($forumId, $_SESSION['user_id'])) {
            echo json_encode(["success" => true, "message" => "Berhasil keluar dari forum."]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal menghapus data dari database."]);
        }
        exit;
    }
}
