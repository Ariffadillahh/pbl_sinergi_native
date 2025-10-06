<?php

require_once __DIR__ . '/../models/CRUD/crud.php';


class HomePageController
{
    public function index()
    {
        $contentViewPost = __DIR__ . '/../views/homePage/index.php';
        require_once __DIR__ . '/../views/homePage/layout.php';
    }

    public function replayPage($id)
    {
        $contentViewPost = __DIR__ . '/../views/homePage/repaly/index.php';
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
