<?php
require_once __DIR__ . '/../BaseModel.php';

class FypageModel extends BaseModel
{
    public function getTrendingPosts()
    {
        $conn = self::getConnection();

        $cacheFile = __DIR__ . '/../../../storage/cache_trending.json';
        if (file_exists($cacheFile)) {
            $cache = json_decode(file_get_contents($cacheFile), true);
            if ($cache && time() - $cache['timestamp'] < 432000) { 
                return $cache['data'];
            }
        }

        $sql = "
            SELECT P.ID AS POST_ID, P.CONTENT, U.USERNAME, U.FULL_NAME, U.PATH_PHOTO,
                   (SELECT COUNT(*) FROM LIKE_POST L WHERE L.POST_ID = P.ID) AS TOTAL_LIKES,
                   (SELECT COUNT(*) FROM COMMENTAR C WHERE C.POST_ID = P.ID) AS TOTAL_COMMENTS
            FROM POSTS P
            JOIN USERS U ON U.ID = P.USER_ID
            WHERE ROWNUM <= 3
            ORDER BY (TOTAL_LIKES + TOTAL_COMMENTS) DESC
        ";

        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);

        $posts = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if ($row['CONTENT'] instanceof OCILob) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            $posts[] = $row;
        }

        // simpan cache
        file_put_contents($cacheFile, json_encode([
            'timestamp' => time(),
            'data' => $posts
        ]));

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
