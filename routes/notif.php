<?php
require_once __DIR__ . '/../app/controllers/NotifController.php';

switch (true) {
    case $route === 'notifications/checkForUpdates':
        requireLogin();
        $notifController = new NotifController();
        $notifController->checkForUpdates();
        break;

    case $route === 'notifications/getRecent':
        requireLogin();
        $notifController = new NotifController();
        $notifController->getRecent();
        break;

    case $route === 'notifications/markAllRead':
        requireLogin();
        $notifController = new NotifController();
        $notifController->markAllRead();
        break;

    case $route === 'notifications/markAsRead':
        requireLogin();
        $notifController = new NotifController();
        $notifController->readNotif();
        break;
}
