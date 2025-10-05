<?php
function requireLogin()
{
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: ' . BASEURL . '/sign-in');
        exit;
    }
}

function checkRoleAccess(array $allowedRoles)
{
    if (!isset($_SESSION['role'])) {
        header('Location: ' . BASEURL . '/sign-in');
        exit;
    }

    $userRole = $_SESSION['role'];

    if (!in_array($userRole, $allowedRoles)) {
        header('Location: ' . BASEURL . '/forums');
        exit;
    }
}


function guestOnly()
{
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            $redirectUrl = $_SERVER['HTTP_REFERER'];
        } else {
            $role = $_SESSION['role']; 

            if ($role == 'MAHASISWA' || $role == 'DOSEN') {
                $redirectUrl = BASEURL . '/homepage';
            } elseif ($role == 'ALUMNI' || $role == 'MITRA') {
                $redirectUrl = BASEURL . '/forums';
            } else {
                $redirectUrl = BASEURL . '/dashboard';
            }
        }
        header('Location: ' . $redirectUrl);
        exit;
    }
}
