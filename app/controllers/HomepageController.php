<?php

require_once __DIR__ . '/../models/CRUD/crud.php';
require_once __DIR__ . '/../models/Users/UserModel.php';
require_once __DIR__ . '/../models/Posts/PostModel.php';


class HomePageController
{
    public function index()
    {
        require_once __DIR__ . '/../models/Posts/PostModel.php';
        $postModel = new PostModel();
        $posts = $postModel->getAllPosts();

        $contentViewPost = __DIR__ . '/../views/homePage/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
    }

    public function replyPage($id)
    {
        $postModel = new PostModel();

        $post = $postModel->getPostById($id);

        if (!$post) {
            header("Location: " . BASEURL . "/homepage");
            exit();
        }

        $contentViewPost = __DIR__ . '/../views/homePage/reply/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
    }

    public function searchPage()
    {
        $keyword = $_GET['keyword'] ?? '';
        $contentViewPost = __DIR__ . '/../views/search/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
    }

    public function searchAjax()
    {
        header('Content-Type: application/json');

        error_log("=== searchAjax() called ===");

        $keyword = $_GET['keyword'] ?? '';
        $filter  = $_GET['filter'] ?? 'top';

        error_log("Keyword: $keyword | Filter: $filter");

        require_once __DIR__ . '/../models/Posts/PostModel.php';
        $postModel = new PostModel();

        if ($filter === 'users') {
            require_once __DIR__ . '/../models/Users/UserModel.php'; // perbaiki path
            $userModel = new UserModel();
            $results = $userModel->searchUsers($keyword);
            error_log("✅ User results count: " . count($results));
            ob_clean();
            echo json_encode(['type' => 'users', 'data' => $results]);
            exit;
        } else {
            error_log("➡️ Searching posts...");
            $userId = $_SESSION['user_id'] ?? null;
            $results = $postModel->searchPosts($keyword, $filter, $userId);
            error_log("✅ Post results count: " . count((array)$results));

            // Tambahkan log hasilnya
            error_log(print_r($results, true));

            $json = json_encode(['type' => 'posts', 'data' => $results]);
            error_log("Sending JSON length: " . strlen($json));

            ob_clean();
            echo $json;
            exit;
        }
    }
}

class NotFoundPageController
{
    public function index()
    {
        include __DIR__ . '/../views/404/index.php';
    }
}

class landingPageController
{
    public function index()
    {
        include __DIR__ . '/../views/landingPage/index.php';
    }

    public function con()
    {
        include __DIR__ . '/../../config/database.php';
    }
}
