<?php

require_once "app/models/BaseModel.php";

class ForumMember extends BaseModel
{
    public static function findByForumId($forumId)
    {
        $conn = self::getConnection();

        $sql = "SELECT 
                fm.USER_ID, 
                TO_CHAR(fm.JOINED_AT, 'DD Mon YYYY', 'NLS_DATE_LANGUAGE = American') AS JOINED_AT, 
                u.FULL_NAME AS NAME, 
                u.PATH_PHOTO,
                u.ROLE AS ROLE,
                u.EMAIL
            FROM 
                FORUM_MEMBERS fm
            JOIN 
                USERS u ON fm.USER_ID = u.ID
            WHERE 
                fm.FORUM_ID = :forum_id_bv";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':forum_id_bv', $forumId);

        oci_execute($stmt);

        $members = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $members[] = $row;
        }

        oci_free_statement($stmt);

        return $members;
    }

public function isMember($forumId, $userId)
{
    $conn = self::getConnection();

    // pakai nama bind variable yang jelas dan tidak sama dengan kolom
    $sql = "SELECT COUNT(*) AS \"CNT\" 
            FROM FORUM_MEMBERS 
            WHERE FORUM_ID = :forum_id_bv AND USER_ID = :user_id_bv";

    $stmt = oci_parse($conn, $sql);

    oci_bind_by_name($stmt, "forum_id_bv", $forumId);
    oci_bind_by_name($stmt, "user_id_bv", $userId);

    if (!oci_execute($stmt)) {
        $e = oci_error($stmt);
        error_log("❌ Oracle Execute Error (isMember): " . $e['message']);
        return false;
    }

    $row = oci_fetch_assoc($stmt);
    return $row && intval($row['CNT']) > 0;
}


}
