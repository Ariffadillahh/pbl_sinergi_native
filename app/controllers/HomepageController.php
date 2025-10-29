<?php

require_once __DIR__ . '/../models/CRUD/crud.php';
require_once __DIR__ . '/../helpers/mentionHelper.php';


class HomePageController
{
    public function index()
    {
        require_once __DIR__ . '/../models/Posts/PostModel.php';
        $postModel = new PostModel();
        $posts = $postModel->getAllPosts();

        foreach ($posts as &$post) {
            $post['CONTENT_FORMATTED'] = mentionHelper::formatMentions($post['CONTENT']);
        }

        $contentViewPost = __DIR__ . '/../views/homePage/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
    }

    public function replyPage($id)
    {
        $postModel = new PostModel();

        require_once __DIR__ . '/../models/Posts/CommentModel.php';
        $post = $postModel->getPostById($id);

        if (!$post) {
            header("Location: " . BASEURL . "/homepage");
            exit();
        }
        $post['CONTENT_FORMATTED'] = mentionHelper::extractMentions($post['CONTENT']);

        $commentModel = new CommentModel();
        $comments = $commentModel->getCommentsByPostId($id);

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

        $contentViewPost = __DIR__ . '/../views/homePage/reply/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
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
