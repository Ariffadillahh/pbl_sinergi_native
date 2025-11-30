<?php
require_once __DIR__ . '/../BaseModel.php';

class FypageModel extends BaseModel
{
    public function getTrendingPosts()
    {
        $conn = self::getConnection();
        $sql = "
            SELECT * FROM (
                SELECT 
                    P.ID AS POST_ID,
                    P.CONTENT,
                    U.USERNAME,
                    U.FULL_NAME,
                    U.PATH_PHOTO,
                    NVL((SELECT COUNT(*) FROM LIKE_POST L WHERE L.POST_ID = P.ID), 0) AS TOTAL_LIKES,
                    NVL((SELECT COUNT(*) FROM COMMENTAR C WHERE C.POST_ID = P.ID), 0) AS TOTAL_COMMENTS,
                    (NVL((SELECT COUNT(*) FROM LIKE_POST L WHERE L.POST_ID = P.ID), 0) +
                    NVL((SELECT COUNT(*) FROM COMMENTAR C WHERE C.POST_ID = P.ID), 0)) AS POPULAR_SCORE
                FROM POSTS P
                JOIN USERS U ON U.ID = P.USER_ID
                WHERE P.CREATED_AT >= SYSDATE - 7
                ORDER BY POPULAR_SCORE DESC, P.CREATED_AT DESC
            )
            WHERE ROWNUM <= 3
        ";

        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);

        $posts = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if (isset($row['CONTENT']) && $row['CONTENT'] instanceof OCILob) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            $posts[] = $row;
        }

        return $posts;
    }

    public function getYourForums()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) return [];

        $conn = self::getConnection();
        
        // Get forums user has joined, ordered by latest activity (new topics)
        $sql = "
            SELECT DISTINCT
                F.ID,
                F.NAME,
                F.PATH_PHOTO,
                F.PATH_THUMBNAIL,
                F.IS_PRIVATE,
                (SELECT COUNT(*) FROM FORUM_MEMBERS FM WHERE FM.FORUM_ID = F.ID) AS MEMBER_COUNT,
                (SELECT MAX(T.CREATED_AT) 
                 FROM FORUM_TOPICS T 
                 WHERE T.FORUM_ID = F.ID) AS LAST_ACTIVITY
            FROM FORUMS F
            INNER JOIN FORUM_MEMBERS FM ON FM.FORUM_ID = F.ID
            WHERE FM.USER_ID = :user_id
              AND F.STATUS = 'ACTIVE'
            ORDER BY LAST_ACTIVITY DESC NULLS LAST
            FETCH FIRST 3 ROWS ONLY
        ";
        
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);
        
        $forums = [];
        while ($row = oci_fetch_assoc($stmt)) {
            // Debug: Log the PATH_PHOTO value
            error_log("Forum ID: " . $row['ID'] . ", PATH_PHOTO: " . ($row['PATH_PHOTO'] ?? 'NULL'));
            $forums[] = $row;
        }
        
        return $forums;
    }

    public function getYourGroups()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) return [];

        $conn = self::getConnection();
        
        $sql = "
            SELECT DISTINCT
                GC.ID,
                GC.NAME,
                GC.PATH_PHOTO,
                GC.ABOUT,
                (SELECT MAX(M.CREATED_AT) 
                 FROM GROUP_CHAT_MESSAGES M 
                 WHERE M.GROUP_CHAT_ID = GC.ID) AS LAST_MESSAGE_TIME,
                (SELECT COUNT(*) 
                 FROM GROUP_CHAT_MEMBERS GM 
                 WHERE GM.GROUP_CHAT_ID = GC.ID) AS MEMBER_COUNT
            FROM GROUP_CHATS GC
            INNER JOIN GROUP_CHAT_MEMBERS GM ON GM.GROUP_CHAT_ID = GC.ID
            WHERE GM.USER_ID = :user_id
            ORDER BY LAST_MESSAGE_TIME DESC NULLS LAST
            FETCH FIRST 3 ROWS ONLY
        ";
        
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);
        
        $groups = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $groups[] = $row;
        }
        
        return $groups;
    }
}