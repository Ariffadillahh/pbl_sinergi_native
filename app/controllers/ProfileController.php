<?php
require_once __DIR__ . '/../models/Users/UserModel.php';
require_once __DIR__ . '/../models/Posts/PostModel.php';

class ProfileController
{
    public function index()
    {
        $userId = $_SESSION['user_id'];

        if (!$userId) {
            header('Location: ' . BASEURL . '/sign-in');
            exit;
        }

        $postModel = new PostModel();
        $posts = $postModel->getPostsByUser($userId);

        $contentViewProfile = __DIR__ . '/../views/profile/index.php';
        require_once __DIR__ . '/../views/profile/layout.php';
    }

    public function getProfileById($id) {
        
    }
}
