<?php

class DashboardController
{
    public function index()
    {

        $contentViewDashboard =  __DIR__ . '/../views/dashboard/index.php';
        require_once __DIR__ . '/../views/dashboard/layout.php';
    }
}
