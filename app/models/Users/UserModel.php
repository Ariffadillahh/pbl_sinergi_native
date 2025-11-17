<?php
require_once __DIR__ . '/../BaseModel.php';

class UserModel extends BaseModel
{
    public function getUserById($userId)
    {
        $conn = self::getConnection();

        $sql = "SELECT ID, PASSWORD, PATH_PHOTO FROM USERS WHERE ID = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $userId);
        oci_execute($stmt);

        return oci_fetch_assoc($stmt);
    }

    public function getUsersById($id)
    {
        $conn = self::getConnection();

        $sql = "SELECT * FROM USERS WHERE ID = :id";
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':id', $id);
        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return $row;
    }

    public function updateProfile($userId, $fullName, $personalNumber, $programStudi, $jenjangStudi, $tahunMasuk, $photoPath)
    {
        $conn = self::getConnection();

        $sql = "UPDATE USERS SET 
                FULL_NAME = :full_name,
                PERSONAL_NUMBER = :personal_number,
                PRODI = :program_studi,
                JENJANG_STUDI = :jenjang_studi,
                TAHUN_MASUK = :tahun_masuk,
                PATH_PHOTO = :path_photo
            WHERE ID = :id";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':full_name', $fullName);
        oci_bind_by_name($stmt, ':personal_number', $personalNumber);
        oci_bind_by_name($stmt, ':program_studi', $programStudi);
        oci_bind_by_name($stmt, ':jenjang_studi', $jenjangStudi);
        oci_bind_by_name($stmt, ':tahun_masuk', $tahunMasuk);
        oci_bind_by_name($stmt, ':path_photo', $photoPath);
        oci_bind_by_name($stmt, ':id', $userId);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if ($result) {
            return ['success' => true];
        } else {
            $e = oci_error($stmt);
            return ['success' => false, 'message' => $e['message']];
        }
    }

    public function updatePassword($userId, $hashedPassword)
    {
        $conn = self::getConnection();

        $sql = "UPDATE USERS SET 
                PASSWORD = :password
            WHERE ID = :id";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':password', $hashedPassword);
        oci_bind_by_name($stmt, ':id', $userId);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if ($result) {
            return ['success' => true];
        } else {
            $e = oci_error($stmt);
            return ['success' => false, 'message' => $e['message']];
        }
    }
    
    public function searchUsers($keyword)
    {
        $conn = self::getConnection();
        $sql = "
            SELECT 
                ID,
                USERNAME,
                FULL_NAME,
                PATH_PHOTO,
                ROLE
            FROM USERS
            WHERE LOWER(USERNAME) LIKE LOWER(:keyword)
               OR LOWER(FULL_NAME) LIKE LOWER(:keyword)
            ORDER BY FULL_NAME ASC
        ";
        $stmt = oci_parse($conn, $sql);
        $search = "%$keyword%";
        oci_bind_by_name($stmt, ":keyword", $search);
        oci_execute($stmt);

        $users = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $users[] = $row;
        }

        return $users;
    }
}
