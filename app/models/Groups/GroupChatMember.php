<?php

require_once "app/models/BaseModel.php";

class GroupChatMember extends BaseModel
{
    public static function findBygroupChatId($groupChatId)
    {
        $conn = self::getConnection();

        $sql = "SELECT 
                gcm.USER_ID, 
                TO_CHAR(gcm.JOINED_AT, 'DD Mon YYYY', 'NLS_DATE_LANGUAGE = American') AS JOINED_AT, 
                u.FULL_NAME AS NAME, 
                U.USERNAME,
                u.PATH_PHOTO,
                u.ROLE AS ROLE,
                u.EMAIL
            FROM 
                GROUP_CHAT_MEMBERS gcm
            JOIN 
                USERS u ON gcm.USER_ID = u.ID
            WHERE 
                gcm.GROUP_CHAT_ID = :group_chat_id_bv";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':group_chat_id_bv', $groupChatId);

        oci_execute($stmt);

        $members = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $members[] = $row;
        }

        oci_free_statement($stmt);

        return $members;
    }

    public function isMember($groupChatId, $userId)
    {
        $conn = self::getConnection();

        // pakai nama bind variable yang jelas dan tidak sama dengan kolom
        $sql = "SELECT COUNT(*) AS \"CNT\" 
            FROM GROUP_CHAT_MEMBERS 
            WHERE GROUP_CHAT_ID = :group_chat_id_bv AND USER_ID = :user_id_bv";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, "group_chat_id_bv", $groupChatId);
        oci_bind_by_name($stmt, "user_id_bv", $userId);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            error_log("❌ Oracle Execute Error (isMember): " . $e['message']);
            return false;
        }

        $row = oci_fetch_assoc($stmt);
        return $row && intval($row['CNT']) > 0;
    }

    public function removeMember($groupChatId, $userId)
    {
        $conn = self::getConnection();

        $sql = "DELETE FROM GROUP_CHAT_MEMBERS 
            WHERE GROUP_CHAT_ID = :group_chat_id_bv 
            AND USER_ID = :user_id_bv";

        $stmt = oci_parse($conn, $sql);

        // bind variabel
        oci_bind_by_name($stmt, ":group_chat_id_bv", $groupChatId);
        oci_bind_by_name($stmt, ":user_id_bv", $userId);

        $exec = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        if (!$exec) {
            $e = oci_error($stmt);
            error_log("❌ Oracle Execute Error (removeMember): " . $e['message']);
            oci_free_statement($stmt);
            return false;
        }

        oci_free_statement($stmt);
        return true;
    }

    public function insertMember($groupChatId, $userId)
    {
        $conn = self::getConnection();

        $id = uniqid('fm_');

        $sql = "INSERT INTO GROUP_CHAT_MEMBERS (ID, GROUP_CHAT_ID, USER_ID, JOINED_AT)
            VALUES (:id, :GROUP_CHAT_ID, :user_id, CURRENT_TIMESTAMP)";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id);
        oci_bind_by_name($stmt, ':GROUP_CHAT_ID', $groupChatId);
        oci_bind_by_name($stmt, ':user_id', $userId);

        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);
        oci_free_statement($stmt);

        return $result;
    }
}
