<?php
require_once __DIR__ . '/../models/Posts/PostModel.php';
require_once __DIR__ . '/../models/Posts/CommentModel.php';
require_once __DIR__ . '/../models/Users/UserModel.php';
require_once __DIR__ . '/../models/Auth/SignIn.php';
require_once __DIR__ . '/../models/Fypage/FypageModel.php';
require_once __DIR__ . '/../helpers/mentionHelper.php';

class HomepageController
{
    private $postModel;
    private $commentModel;
    private $userModel;
    private $signInModel;
    private $fypageModel;

    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->commentModel = new CommentModel();
        $this->userModel = new UserModel();
        $this->signInModel = new SignInModel();
        $this->fypageModel = new FypageModel();
    }

    private function getSidebarData()
    {
        return [
            'trending' => $this->fypageModel->getTrendingPosts(),
            'hot'      => $this->fypageModel->getHotForums(),
            'new'      => $this->fypageModel->getNewForums(),
        ];
    }


    public function home()
    {
        $posts = $this->postModel->getAllPosts();
        $sidebarData = $this->getSidebarData();
        extract($sidebarData);

        foreach ($posts as &$post) {
            $post['CONTENT_FORMATTED'] = mentionHelper::formatMentions($post['CONTENT']);
        }
        unset($post);

        $contentViewPost = __DIR__ . '/../views/homePage/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
    }

    public function replyPage($id)
    {
        $post = $this->postModel->getPostById($id);
        if (!$post) {
            header("Location: " . BASEURL . "/homepage");
            exit();
        }

        $post['CONTENT_FORMATTED'] = mentionHelper::extractMentions($post['CONTENT']);

        $comments = $this->commentModel->getCommentsByPostId($id);

        foreach ($comments as &$comment) {
            if (isset($comment['MESSAGE'])) {
                $comment['MESSAGE_FORMATTED'] = mentionHelper::extractMentions($comment['MESSAGE']);
            }

            if (isset($comment['REPLIES']) && is_array($comment['REPLIES'])) {
                foreach ($comment['REPLIES'] as &$reply) {
                    if (isset($reply['MESSAGE'])) {
                        $reply['MESSAGE_FORMATTED'] = mentionHelper::extractMentions($reply['MESSAGE']);
                    }
                }
                unset($reply); 
            }
        }
        unset($comment); 

        $sidebarData = $this->getSidebarData();
        extract($sidebarData);

        $contentViewPost = __DIR__ . '/../views/homePage/reply/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
    }


    public function profile($id)
    {
        if (!$id) {
            header('Location: ' . BASEURL . '/sign-in');
            exit;
        }

        $posts = $this->postModel->getPostsByUser($id);
        foreach ($posts as &$post) {
            $post['CONTENT_FORMATTED'] = mentionHelper::formatMentions($post['CONTENT']);
        }
        unset($post);

        $userById = $this->signInModel->getUserByUsernameOrEmail($id);

        $sidebarData = $this->getSidebarData();
        extract($sidebarData);

        $contentViewPost = __DIR__ . '/../views/homePage/profile/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
    }


    public function searchPage()
    {
        $keyword = $_GET['keyword'] ?? '';
        $sidebarData = $this->getSidebarData();
        extract($sidebarData);

        $contentViewPost = __DIR__ . '/../views/homePage/search/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
    }

    public function searchAjax()
    {
        header('Content-Type: application/json');
        $keyword = $_GET['keyword'] ?? '';
        $filter  = $_GET['filter'] ?? 'top';

        if ($filter === 'users') {
            $results = $this->userModel->searchUsers($keyword);
            echo json_encode(['type' => 'users', 'data' => $results]);
        } else {
            $userId = $_SESSION['user_id'] ?? null;
            $results = $this->postModel->searchPosts($keyword, $filter, $userId);
            echo json_encode(['type' => 'posts', 'data' => $results]);
        }
        exit;
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

class NotFoundPageController
{
    public function index()
    {
        http_response_code(404);
        include __DIR__ . '/../views/404/index.php';
    }
}
