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
                    EMAIL, PASSWORD, PATH_PHOTO, JENJANG_STUDI, ROLE
                ) VALUES (
                    :id, :username, :personal_number, :full_name, :tahun_masuk, 
                    :email, :password, :path_photo, :jenjang_studi, :role
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
}
