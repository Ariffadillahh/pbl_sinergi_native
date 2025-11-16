<?php

require_once "app/models/BaseModel.php";

class User extends BaseModel
{
    public static function create($data)
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return false;
        }

        $sql = "INSERT INTO USERS (
                    ID, USERNAME, PERSONAL_NUMBER, FULL_NAME, TAHUN_MASUK, 
                    EMAIL, PASSWORD, PATH_PHOTO, JENJANG_STUDI, ROLE, PRODI
                ) VALUES (
                    :id, :username, :personal_number, :full_name, :tahun_masuk, 
                    :email, :password, :path_photo, :jenjang_studi, :role, :prodi
                )";


        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            error_log("OCI Parse Error: " . $e['message']);
            return false;
        }

        try {
            oci_bind_by_name($stmt, ':id', $data['ID']);
            oci_bind_by_name($stmt, ':username', $data['USERNAME']);
            oci_bind_by_name($stmt, ':personal_number', $data['PERSONAL_NUMBER']);
            oci_bind_by_name($stmt, ':full_name', $data['FULL_NAME']);
            oci_bind_by_name($stmt, ':tahun_masuk', $data['TAHUN_MASUK']);
            oci_bind_by_name($stmt, ':email', $data['EMAIL']);
            oci_bind_by_name($stmt, ':password', $data['PASSWORD']);
            oci_bind_by_name($stmt, ':path_photo', $data['PATH_PHOTO']);
            oci_bind_by_name($stmt, ':jenjang_studi', $data['JENJANG_STUDI']);
            oci_bind_by_name($stmt, ':role', $data['ROLE']);
            oci_bind_by_name($stmt, ':prodi', $data['PRODI']);

            $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

            if ($result) {
                oci_commit($conn);
                return true;
            } else {
                oci_rollback($conn);
                $e = oci_error($stmt);
                error_log("OCI Execute Error: " . $e['message']);
                return false;
            }
        } catch (\Throwable $th) {
            oci_rollback($conn);
            error_log("Transaction Exception: " . $th->getMessage());
            return false;
        } finally {
            if ($stmt) {
                oci_free_statement($stmt);
            }
        }
    }

    public static function updatePassword($userId, $hashedPassword)
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return false;
        }

        $sql = "UPDATE USERS SET PASSWORD = :password WHERE ID = :id";
        $stmt_update = oci_parse($conn, $sql);

        if (!$stmt_update) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return false;
        }

        oci_bind_by_name($stmt_update, ':password', $hashedPassword);
        oci_bind_by_name($stmt_update, ':id', $userId);

        $result = oci_execute($stmt_update, OCI_COMMIT_ON_SUCCESS);

        if (!$result) {
            $e = oci_error($stmt_update);
            error_log("Gagal memperbarui password: " . $e['message']);
            oci_free_statement($stmt_update);
            return false;
        }

        oci_free_statement($stmt_update);
        return true;
    }


    public static function updateProfile($userId, $data)
    {
        $allowedColumns = [
            'FULL_NAME',
            'PRODI',
            'JENJANG_STUDI',
            'TAHUN_MASUK',
            'PATH_PHOTO',
            'PERSONAL_NUMBER',
            'PASSWORD'
        ];

        $setClauses = [];
        $dataToBind = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedColumns)) {
                $setClauses[] = "$key = :$key";
                $dataToBind[$key] = $value;
            }
        }

        if (empty($setClauses)) {
            error_log("Tidak ada data valid untuk diupdate.");
            return false;
        }

        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return false;
        }

        $setString = implode(', ', $setClauses);
        $sql = "UPDATE USERS SET $setString WHERE ID = :id";

        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return false;
        }

        foreach ($dataToBind as $key => &$value) {
            oci_bind_by_name($stmt, ':' . $key, $value);
        }
        oci_bind_by_name($stmt, ':id', $userId);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
        if (!$result) {
            $e = oci_error($stmt);
            error_log("Gagal mengupdate data user: " . $e['message']);
            oci_free_statement($stmt);
            return false;
        }

        oci_free_statement($stmt);
        return true;
    }

    public function getAllUsers()
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return [];
        }

        $userId = $_SESSION['user_id'];

        $sql = "SELECT ID, USERNAME, FULL_NAME, PATH_PHOTO, ROLE 
            FROM USERS 
            WHERE ROLE NOT IN ('MITRA', 'ALUMNI') 
            AND ID != :id";

        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return [];
        }

        oci_bind_by_name($stmt, ':id', $userId);

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

    public function getTotalUsers($keyword)
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return 0;
        }

        $sql = "SELECT COUNT(*) as total FROM USERS";

        if (!empty($keyword)) {
            $sql .= " WHERE LOWER(FULL_NAME) LIKE :keyword OR LOWER(PERSONAL_NUMBER) LIKE :keyword";
            $params[':keyword'] = "%" . strtolower($keyword) . "%";
        }

        $stid = oci_parse($conn, $sql);
        if (!$stid) {
            $e = oci_error($conn);
            error_log("OCI Parse Error: " . $e['message']);
            return 0;
        }

        if (!empty($keyword)) {
            $searchKeyword = "%" . $keyword . "%";
            if (!oci_bind_by_name($stid, ':keyword', $searchKeyword, -1, SQLT_CHR)) {
                $e = oci_error($stid);
                error_log("OCI Bind Error: " . $e['message']);
                oci_free_statement($stid);
                return 0;
            }
        }

        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            error_log("OCI Execute Error: " . $e['message']);
            oci_free_statement($stid);
            return 0;
        }

        $row = oci_fetch_assoc($stid);

        $total = 0;
        if ($row && isset($row['TOTAL'])) {
            $total = (int)$row['TOTAL'];
        }

        oci_free_statement($stid);

        return $total;
    }

    public function getPaginatedUsers($searchTerm, $limit, $offset)
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return [];
        }

        $sql = "SELECT * FROM users";

        if (!empty($searchTerm)) {
            $sql .= " WHERE LOWER(FULL_NAME) LIKE :keyword OR LOWER(PERSONAL_NUMBER) LIKE :keyword";
        }

        $sql .= " ORDER BY FULL_NAME ASC";
        $sql .= " OFFSET :offset_val ROWS";
        $sql .= " FETCH NEXT :limit_val ROWS ONLY";

        $stid = oci_parse($conn, $sql);
        if (!$stid) {
            $e = oci_error($conn);
            error_log("OCI Parse Error: " . $e['message']);
            return [];
        }

        $bind_offset = (int)$offset;
        $bind_limit = (int)$limit;

        if (!empty($searchTerm)) {
            $searchKeyword = "%" . strtolower($searchTerm) . "%";

            if (!oci_bind_by_name($stid, ':keyword', $searchKeyword, -1, SQLT_CHR)) {
                $e = oci_error($stid);
                error_log("OCI Bind Error (keyword): " . $e['message']);
                oci_free_statement($stid);
                return [];
            }
        }

        if (!oci_bind_by_name($stid, ':offset_val', $bind_offset)) {
            $e = oci_error($stid);
            error_log("OCI Bind Error (offset): " . $e['message']);
            oci_free_statement($stid);
            return [];
        }

        if (!oci_bind_by_name($stid, ':limit_val', $bind_limit)) {
            $e = oci_error($stid);
            error_log("OCI Bind Error (limit): " . $e['message']);
            oci_free_statement($stid);
            return [];
        }

        $r = oci_execute($stid);
        if (!$r) {
            $e = oci_error($stid);
            error_log("OCI Execute Error: " . $e['message']);
            oci_free_statement($stid);
            return [];
        }

        $users = [];

        while ($row = oci_fetch_assoc($stid)) {
            $users[] = $row;
        }

        oci_free_statement($stid);

        return $users;
    }

    public static function createByAdmin($data)
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return false;
        }

        $sql = "INSERT INTO USERS (
                    ID, USERNAME, PERSONAL_NUMBER, FULL_NAME,  
                    EMAIL, PASSWORD, ROLE
                ) VALUES (
                    :id, :username, :personal_number, :full_name,  
                    :email, :password, :role
                )";


        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            error_log("OCI Parse Error: " . $e['message']);
            return false;
        }

        try {
            oci_bind_by_name($stmt, ':id', $data['ID']);
            oci_bind_by_name($stmt, ':username', $data['USERNAME']);
            oci_bind_by_name($stmt, ':personal_number', $data['PERSONAL_NUMBER']);
            oci_bind_by_name($stmt, ':full_name', $data['FULL_NAME']);
            oci_bind_by_name($stmt, ':email', $data['EMAIL']);
            oci_bind_by_name($stmt, ':password', $data['PASSWORD']);
            oci_bind_by_name($stmt, ':role', $data['ROLE']);

            $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

            if ($result) {
                oci_commit($conn);
                return true;
            } else {
                oci_rollback($conn);
                $e = oci_error($stmt);
                error_log("OCI Execute Error: " . $e['message']);
                return false;
            }
        } catch (\Throwable $th) {
            oci_rollback($conn);
            error_log("Transaction Exception: " . $th->getMessage());
            return false;
        } finally {
            if ($stmt) {
                oci_free_statement($stmt);
            }
        }
    }

    public function updateUserRoleById($userId, $newRole)
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return false; 
        }

        $sql = "UPDATE users SET ROLE = :new_role WHERE ID = :user_id";

        $stid = oci_parse($conn, $sql);
        if (!$stid) {
            $e = oci_error($conn);
            error_log("OCI Parse Error (Update Role): " . $e['message']);
            return false;
        }

        $bindUserId = $userId;
        $bindNewRole = $newRole;

        if (!oci_bind_by_name($stid, ':new_role', $bindNewRole, -1, SQLT_CHR)) {
            $e = oci_error($stid);
            error_log("OCI Bind Error (role): " . $e['message']);
            oci_free_statement($stid);
            return false;
        }

        if (!oci_bind_by_name($stid, ':user_id', $bindUserId, -1, SQLT_CHR)) {
            $e = oci_error($stid);
            error_log("OCI Bind Error (id): " . $e['message']);
            oci_free_statement($stid);
            return false;
        }

        $r = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

        if (!$r) {
            $e = oci_error($stid);
            error_log("OCI Execute Error (Update Role): " . $e['message']);
            oci_free_statement($stid);
            throw new Exception("Database execution failed: " . $e['message']);
        }

        $affectedRows = oci_num_rows($stid);

        oci_free_statement($stid);

        return $affectedRows > 0;
    }
}
