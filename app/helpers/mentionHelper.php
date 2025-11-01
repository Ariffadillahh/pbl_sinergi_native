<?php

require_once __DIR__ . '/../models/Posts/PostModel.php';


class mentionHelper
{
    public static function formatMentions($text)
    {
        if (is_object($text) && $text instanceof OCILob) {
            $text = $text->load();
        }

        if (!is_string($text)) {
            return (string)$text;
        }

        if (!preg_match_all('/@(\w+)/', $text, $matches)) {
            return $text;
        }

        $mentionedUsernames = array_unique($matches[1]);
        if (empty($mentionedUsernames)) {
            return $text;
        }

        $postModel = new PostModel();
        $usersData = $postModel->getUsersByUsernames($mentionedUsernames);

        foreach ($usersData as $username => $userData) {
            $id = htmlspecialchars($userData['ID']);
            $username_safe = htmlspecialchars($username);
            $currentId = $_SESSION['user_id'];

            if ($id == $currentId) {
                $profileLink = BASEURL . '/profile';
            } else {
                $profileLink = BASEURL . '/homepage/user/profile/' . $id;
            }

            $link = '<a href="' . $profileLink . '" class="text-blue-500 hover:underline">@' . $username_safe . '</a>';

            $text = preg_replace('/@' . preg_quote($username, '/') . '\b/', $link, $text);
        }

        return $text;
    }

    public static function extractMentions($text)
    {
        if (is_object($text) && $text instanceof OCILob) {
            $text = $text->load();
        }

        if (!is_string($text)) {
            return '';
        }

        $originalText = $text;

        if (!preg_match_all('/@(\w+)/', $text, $matches)) {
            return $originalText;
        }

        $mentionedUsernames = array_unique($matches[1]);
        if (empty($mentionedUsernames)) {
            return $originalText;
        }

        $postModel = new PostModel();
        $usersData = $postModel->getUsersByUsernames($mentionedUsernames);

        foreach ($usersData as $username => $userData) {
            $id = htmlspecialchars($userData['ID']);
            $username_safe = htmlspecialchars($username);
            $currentId = $_SESSION['user_id'];

            $profileLink = ($id == $currentId)
                ? BASEURL . '/profile'
                : BASEURL . '/homepage/user/profile/' . $id;

            $link = '<a href="' . $profileLink . '" class="text-blue-500 hover:underline">@' . $username_safe . '</a>';

            $text = preg_replace('/@' . preg_quote($username, '/') . '\b/', $link, $text);
        }


        return $text;
    }
}
