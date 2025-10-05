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
}
