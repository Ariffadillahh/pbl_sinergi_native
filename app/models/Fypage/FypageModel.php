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

    public function getHotForums()
    {
        $conn = self::getConnection();
        $sql = "
            SELECT F.ID, F.NAME, F.PATH_PHOTO,
                   (SELECT COUNT(*) FROM FORUM_MEMBERS M WHERE M.FORUM_ID = F.ID) AS MEMBER_COUNT
            FROM FORUMS F
            ORDER BY MEMBER_COUNT DESC
            FETCH FIRST 3 ROWS ONLY
        ";
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);
        $forums = [];
        while ($r = oci_fetch_assoc($stmt)) $forums[] = $r;
        return $forums;
    }

    public function getNewForums()
    {
        $conn = self::getConnection();
        $sql = "
            SELECT F.ID, F.NAME, F.PATH_PHOTO, F.ABOUT
            FROM FORUMS F
            ORDER BY F.CREATED_AT DESC
            FETCH FIRST 3 ROWS ONLY
        ";
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);
        $forums = [];
        while ($r = oci_fetch_assoc($stmt)) $forums[] = $r;
        return $forums;
    }
}
