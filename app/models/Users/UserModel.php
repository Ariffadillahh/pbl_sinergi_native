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

    public function updateToMahasiswa($userId)
    {
        $conn = self::getConnection();

        $sqlGet = "SELECT TAHUN_MASUK, JENJANG_STUDI FROM USERS WHERE ID = :id";
        $stmtGet = oci_parse($conn, $sqlGet);
        oci_bind_by_name($stmtGet, ':id', $userId);
        oci_execute($stmtGet);
        $userData = oci_fetch_assoc($stmtGet);
        oci_free_statement($stmtGet);

        if (!$userData) {
            return ['success' => false, 'message' => 'User not found'];
        }

        $oldTahunMasuk = (int)$userData['TAHUN_MASUK'];
        $jenjangStudi  = strtoupper(trim($userData['JENJANG_STUDI']));

        $newTahunMasuk = $oldTahunMasuk + 1;

        $durasi = ($jenjangStudi === 'D4') ? 4 : 3;

        $tahunLulusBaru = $newTahunMasuk + $durasi;
        $bulanLulus     = 10; 

        $tahunSekarang = (int)date('Y');
        $bulanSekarang = (int)date('m');

        $isValidStudent = false;

        if ($tahunSekarang < $tahunLulusBaru) {
            $isValidStudent = true;
        } elseif ($tahunSekarang == $tahunLulusBaru && $bulanSekarang < $bulanLulus) {
            $isValidStudent = true;
        }

        if (!$isValidStudent) {
            oci_close($conn);
            return [
                'success' => false,
                'message' => "Verification failed. Your study period has ended."
            ];
        }

        $sqlUpdate = "UPDATE USERS 
                  SET ROLE = 'MAHASISWA', 
                      TAHUN_MASUK = TAHUN_MASUK + 1,
                      STATUS = 'APPROVED'
                  WHERE ID = :id";

        $stmtUpdate = oci_parse($conn, $sqlUpdate);
        oci_bind_by_name($stmtUpdate, ':id', $userId);

        $exec = oci_execute($stmtUpdate, OCI_NO_AUTO_COMMIT);

        if ($exec) {
            oci_commit($conn);
            oci_free_statement($stmtUpdate);
            oci_close($conn);
            return ['success' => true, 'message' => 'Status changed to Student successfully.'];
        } else {
            $e = oci_error($stmtUpdate);
            oci_free_statement($stmtUpdate);
            oci_close($conn);
            return ['success' => false, 'message' => 'Database error: ' . $e['message']];
        }
    }

    public function getAdmin()
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return [];
        }

        $sql = "SELECT ID FROM USERS WHERE ROLE = 'ADMIN'";

        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return [];
        }


        $result = oci_execute($stmt);

        if (!$result) {
            $e = oci_error($stmt);
            error_log("Gagal mengeksekusi query: " . $e['message']);
            oci_free_statement($stmt);
            return [];
        }

        $users = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $users[] = $row;
        }

        oci_free_statement($stmt);
        return $users;
    }

    public function updateUserRole($userId, $newRole)
    {
        $conn = self::getConnection();

        $sql = "UPDATE USERS SET ROLE = :role WHERE ID = :user_id";

        $stid = oci_parse($conn, $sql);

        oci_bind_by_name($stid, ':role', $newRole);
        oci_bind_by_name($stid, ':user_id', $userId);

        $result = oci_execute($stid, OCI_NO_AUTO_COMMIT);

        if ($result) {
            oci_commit($conn);
            $success = true;
        } else {
            $success = false;
        }

        oci_free_statement($stid);
        oci_close($conn);

        return $success;
    }
}
