<?php

require_once "app/models/BaseModel.php";

class SignInModel extends BaseModel
{
    public function getUserByUsernameOrEmail($identifier)
    {
        $conn = self::getConnection();

        $sql = "SELECT ID, USERNAME, FULL_NAME, EMAIL, PASSWORD, ROLE, PRODI, JENJANG_STUDI, TAHUN_MASUK, PATH_PHOTO 
            FROM USERS 
            WHERE USERNAME = :identifier_bv 
               OR EMAIL = :identifier_bv
               OR ID = :identifier_bv";

        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            die('Oracle parse error: ' . htmlentities($e['message']));
        }

        oci_bind_by_name($stmt, ':identifier_bv', $identifier);

        $executed = oci_execute($stmt);
        if (!$executed) {
            $e = oci_error($stmt);
            die('Oracle execute error: ' . htmlentities($e['message']));
        }

        $user = oci_fetch_assoc($stmt);

        oci_free_statement($stmt);

        return $user;
    }

    public static function updateUserRole($userId, $newRole)
    {
        $conn = self::getConnection();
        $sql = "UPDATE USERS SET ROLE = :new_role_bv WHERE ID = :user_id_bv";

        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            error_log('Oracle parse error in updateUserRole: ' . htmlentities($e['message']));
            return false;
        }

        oci_bind_by_name($stmt, ':new_role_bv', $newRole);
        oci_bind_by_name($stmt, ':user_id_bv', $userId);

        $executed = oci_execute($stmt);
        if (!$executed) {
            $e = oci_error($stmt);
            error_log('Oracle execute error in updateUserRole: ' . htmlentities($e['message']));
            oci_free_statement($stmt); 
            return false;
        }

        oci_free_statement($stmt);

        return true;
    }
}
