<?php
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

        $users = $this->show();

        include __DIR__ . '/../views/homepage/index.php';
    }

    private function getConnection()
    {
        require __DIR__ . '/../../config/database.php';
        return $conn;
    }

    private function show()
    {
        // $conn = $this->getConnection();
        // $query = 'SELECT ID, NAME, EMAIL FROM USERS';

        // $stid = oci_parse($conn, $query);
        // oci_execute($stid);

        // $users = [];
        // while ($row = oci_fetch_assoc($stid)) {
        //     $users[] = $row;
        // }

        // oci_free_statement($stid);
        // oci_close($conn);

        // return $users;
        $conn = $this->getConnection();

        $query = "SELECT nomor as ID, nama as NAME, email as EMAIL FROM anggota";

        $result = $conn->query($query);

        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        $result->free();
        $conn->close();

        return $users;
    }

    private function create($name, $email)
    {
        $conn = $this->getConnection();
        $query = "INSERT INTO USERS (NAME, EMAIL) VALUES (:name, :email)";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':email', $email);
        oci_execute($stid);
        oci_free_statement($stid);
        oci_close($conn);
    }

    private function update($id, $name, $email)
    {
        $conn = $this->getConnection();
        $query = "UPDATE USERS SET NAME = :name, EMAIL = :email WHERE ID = :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':email', $email);
        oci_execute($stid);
        oci_free_statement($stid);
        oci_close($conn);
    }

    private function delete($id)
    {
        $conn = $this->getConnection();
        $query = "DELETE USERS WHERE ID = :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        oci_free_statement($stid);
        oci_close($conn);
    }
}

class NotFoundPageController
{
    public function index()
    {
        include __DIR__ . '/../views/404/index.php';
    }
}
