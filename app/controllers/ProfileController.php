<?php

class ProfileController
{
    public function index()
    {
        $contentViewProfile = __DIR__ . '/../views/profile/index.php';
        require_once __DIR__ . '/../views/profile/layout.php';
    }
}
