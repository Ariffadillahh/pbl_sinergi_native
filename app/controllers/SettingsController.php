<?php

class SettingsController
{
    public function index()
    {
        $contentViewSettings = __DIR__ . '/../views/settings/index.php';
        require_once __DIR__ . '/../views/settings/layout.php';
    }
}
