<?php

require_once "app/models/BaseModel.php";

class SignInModel extends BaseModel
{
    public function getUserByUsernameOrEmail($identifier)
    {
        // 1. Ambil koneksi Oracle dari BaseModel
        $conn = self::getConnection();

        // 2. Siapkan SQL dengan named placeholders (:) untuk Oracle
        $sql = "SELECT ID, USERNAME, FULL_NAME, EMAIL, PASSWORD, ROLE, PRODI, JENJANG_STUDI, TAHUN_MASUK, PATH_PHOTO 
                FROM USERS 
                WHERE USERNAME = :identifier_bv OR EMAIL = :identifier_bv";

        // 3. Gunakan oci_parse() sebagai ganti ->prepare()
        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            die('Oracle parse error: ' . htmlentities($e['message']));
        }

        // 4. Gunakan oci_bind_by_name() sebagai ganti ->bind_param()
        // Kita bind variabel PHP ke placeholder di SQL
        oci_bind_by_name($stmt, ':identifier_bv', $identifier);

        // 5. Gunakan oci_execute()
        $executed = oci_execute($stmt);
        if (!$executed) {
            $e = oci_error($stmt);
            die('Oracle execute error: ' . htmlentities($e['message']));
        }

        // 6. Gunakan oci_fetch_assoc() untuk mengambil hasil
        // Ini menggantikan get_result() dan fetch_assoc()
        $user = oci_fetch_assoc($stmt);

        // 7. Gunakan oci_free_statement() untuk membersihkan memori
        oci_free_statement($stmt);

        // oci_fetch_assoc akan mengembalikan false jika tidak ada baris data
        return $user;
    }
}
