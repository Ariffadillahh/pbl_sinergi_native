<?php
require_once __DIR__ . '/../models/Users/UserModel.php';
require_once __DIR__ . '/../models/Posts/PostModel.php';

class ProfileController
{
    public function index($userId = null)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ambil user ID dari session jika tidak ada di URL
        if (!$userId && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        }

        if (!$userId) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $userModel = new UserModel();
        $postModel = new PostModel();

        // Ambil data user & postingan
        $user = $userModel->getUserById($userId);
        $posts = $postModel->getPostsByUser($userId);

        if (!$user) {
            die("Data user tidak ditemukan di database.");
        }

        // 🧠 kirim ke view dengan cara include manual
        $contentViewProfile = __DIR__ . '/../views/profile/index.php';
        require_once __DIR__ . '/../views/profile/layout.php'; 
    }
}
