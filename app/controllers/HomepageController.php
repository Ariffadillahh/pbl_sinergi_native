<?php

require_once __DIR__ . '/../models/CRUD/crud.php';


class HomePageController
{
    public function index()
    {
        session_start();

        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     if (isset($_POST['create'])) {
        //         try {
        //             $this->create($_POST['name'], $_POST['email']);
        //             $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data pengguna berhasil ditambahkan!'];
        //         } catch (Exception $e) {
        //             $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal menambahkan data: ' . $e->getMessage()];
        //         }
        //         header("Location: " . BASEURL);
        //         exit;
        //     }

        //     if (isset($_POST['update'])) {
        //         try {
        //             $this->update($_POST['id'], $_POST['name'], $_POST['email']);
        //             $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data pengguna berhasil diUpdate!'];
        //         } catch (Exception $e) {
        //             $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal update data: ' . $e->getMessage()];
        //         }
        //         header("Location: " . BASEURL);
        //         exit;
        //     }

        //     if (isset($_POST['delete'])) {
        //         try {
        //             $this->delete($_POST['id']);
        //             $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data pengguna berhasil didelete!'];
        //         } catch (Exception $e) {
        //             $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Gagal update data: ' . $e->getMessage()];
        //         }
        //         header("Location: " . BASEURL);
        //         exit;
        //     }
        // }

        $users = CRUD::getAll();

        include __DIR__ . '/../views/homepage/index.php';
    }
}

class NotFoundPageController
{
    public function index()
    {
        include __DIR__ . '/../views/404/index.php';
    }
}
