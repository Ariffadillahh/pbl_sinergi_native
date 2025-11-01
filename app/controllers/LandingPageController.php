<?php

class LandingPageController
{
    public function landingPage()
    {
        include __DIR__ . '/../views/landingPage/index.php';
    }

    public function notFound()
    {
        http_response_code(404);
        include __DIR__ . '/../views/404/index.php';
    }

    public function con()
    {
        include __DIR__ . '/../../config/database.php';
    }
}
