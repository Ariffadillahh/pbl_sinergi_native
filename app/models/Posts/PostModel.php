<?php

require_once __DIR__ . '/../BaseModel.php';

class PostModel extends BaseModel
{
    public function createPost($userId, $content, $mediaPaths)
    {
        $conn = self::getConnection();

        $mode = OCI_NO_AUTO_COMMIT;

        $postId = uniqid();
        $sqlPost = "
            INSERT INTO POSTS (ID, USER_ID, CONTENT)
            VALUES (:id, :user_id, :content)
        ";
        $stmtPost = oci_parse($conn, $sqlPost);
        oci_bind_by_name($stmtPost, ":id", $postId);
        oci_bind_by_name($stmtPost, ":user_id", $userId);
        oci_bind_by_name($stmtPost, ":content", $content);

        if (!oci_execute($stmtPost, $mode)) {
            $e = oci_error($stmtPost);
            error_log("Gagal insert POSTS: " . $e['message']);
            oci_rollback($conn);
            return false;
        }

        foreach ($mediaPaths as $path) {
            $mediaId = 'media_' . uniqid();
            $mediaType = 'IMAGE';

            $sqlMedia = "
            INSERT INTO POST_MEDIA (ID, POST_ID, MEDIA_PATH, MEDIA_TYPE)
            VALUES (:id, :post_id, :media_path, :media_type)
        ";
            $stmtMedia = oci_parse($conn, $sqlMedia);
            oci_bind_by_name($stmtMedia, ":id", $mediaId);
            oci_bind_by_name($stmtMedia, ":post_id", $postId);
            oci_bind_by_name($stmtMedia, ":media_path", $path);
            oci_bind_by_name($stmtMedia, ":media_type", $mediaType);

            if (!oci_execute($stmtMedia, $mode)) {
                $e = oci_error($stmtMedia);
                error_log("Gagal insert POST_MEDIA: " . $e['message']);
                oci_rollback($conn);
                return false;
            }
        }

        if (!oci_commit($conn)) {
            error_log("Gagal melakukan commit transaksi.");
            oci_rollback($conn);
            return false;
        }

        return [
            'success' => true,
            'ID' => $postId
        ];
    }

    public function getAllPosts()
    {
        $conn = self::getConnection();

        $sql = "
            SELECT 
                P.ID AS POST_ID,
                P.CONTENT,
                P.USER_ID,
                TO_CHAR(P.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT,
                U.USERNAME,
                U.FULL_NAME,
                U.PATH_PHOTO,
                (
                    SELECT COUNT(*) FROM LIKE_POST L WHERE L.POST_ID = P.ID
                ) AS TOTAL_LIKES,
                (
                    SELECT COUNT(*) FROM COMMENTAR C WHERE C.POST_ID = P.ID
                ) AS COMMENT_COUNT,
                (
                    SELECT COUNT(*) FROM LIKE_POST L 
                    WHERE L.POST_ID = P.ID AND L.USER_ID = :current_user_id
                ) AS IS_LIKED
            FROM POSTS P
            JOIN USERS U ON P.USER_ID = U.ID
            ORDER BY P.CREATED_AT DESC
        ";

        $stmt = oci_parse($conn, $sql);
        $currentUserId = $_SESSION['user_id'] ?? '';
        oci_bind_by_name($stmt, ":current_user_id", $currentUserId);
        oci_execute($stmt);

        $posts = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $media = $this->getMediaByPostId($row['POST_ID']);
            $row['MEDIA'] = $media;
            $commentCount = (int)$row['COMMENT_COUNT'];

            $replySql = "
                SELECT COUNT(*) AS REPLY_COUNT 
                FROM REPLY_COMMENTAR R 
                WHERE R.COMMENTAR_ID IN (
                    SELECT ID FROM COMMENTAR WHERE POST_ID = :post_id
                )
            ";
            $replyStmt = oci_parse($conn, $replySql);
            oci_bind_by_name($replyStmt, ":post_id", $row['POST_ID']);
            oci_execute($replyStmt);
            $replyRow = oci_fetch_assoc($replyStmt);
            $replyCount = (int)$replyRow['REPLY_COUNT'];

            $row['TOTAL_COMMENT'] = $commentCount + $replyCount;

            $posts[] = $row;
        }

        return $posts;
    }


    public function getMediaByPostId($postId)
    {
        $conn = self::getConnection();
        $sql = "SELECT MEDIA_PATH FROM POST_MEDIA WHERE POST_ID = :post_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':post_id', $postId);
        oci_execute($stmt);

        $media = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $media[] = $row['MEDIA_PATH'];
        }
        return $media;
    }
    public function updatePost($postId, $userId, $newContent, $finalMediaPaths = [])
    {
        $conn = self::getConnection();

        $checkSql = "SELECT USER_ID FROM POSTS WHERE ID = :id";
        $checkStmt = oci_parse($conn, $checkSql);
        oci_bind_by_name($checkStmt, ":id", $postId);
        if (!oci_execute($checkStmt)) return false;

        $row = oci_fetch_assoc($checkStmt);
        if (!$row || $row['USER_ID'] !== $userId) {
            return false;
        }

        $sqlUpdate = "UPDATE POSTS SET CONTENT = :content WHERE ID = :id";
        $stmt = oci_parse($conn, $sqlUpdate);
        oci_bind_by_name($stmt, ":content", $newContent);
        oci_bind_by_name($stmt, ":id", $postId);
        if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
            oci_rollback($conn);
            return false;
        }

        $deleteSql = "DELETE FROM POST_MEDIA WHERE POST_ID = :post_id";
        $stmtDel = oci_parse($conn, $deleteSql);
        oci_bind_by_name($stmtDel, ":post_id", $postId);
        if (!oci_execute($stmtDel, OCI_NO_AUTO_COMMIT)) {
            oci_rollback($conn);
            return false;
        }

        if (!empty($finalMediaPaths)) {
            $sqlMedia = "INSERT INTO POST_MEDIA (ID, POST_ID, MEDIA_PATH, MEDIA_TYPE) VALUES (:id, :post_id, :media_path, :media_type)";
            foreach ($finalMediaPaths as $path) {
                $mediaId = uniqid('media_');
                $mediaType = 'IMAGE';

                $stmtMedia = oci_parse($conn, $sqlMedia);
                oci_bind_by_name($stmtMedia, ":id", $mediaId);
                oci_bind_by_name($stmtMedia, ":post_id", $postId);
                oci_bind_by_name($stmtMedia, ":media_path", $path);
                oci_bind_by_name($stmtMedia, ":media_type", $mediaType);

                if (!oci_execute($stmtMedia, OCI_NO_AUTO_COMMIT)) {
                    oci_rollback($conn);
                    return false;
                }
            }
        }

        oci_commit($conn);
        return true;
    }

    public function deletePost($postId, $userId)
    {
        $conn = self::getConnection();

        $checkSql = "SELECT USER_ID FROM POSTS WHERE ID = :id";
        $checkStmt = oci_parse($conn, $checkSql);
        oci_bind_by_name($checkStmt, ":id", $postId);

        if (!oci_execute($checkStmt)) {
            error_log("Gagal cek kepemilikan post: " . oci_error($checkStmt)['message']);
            return false;
        }
        $row = oci_fetch_assoc($checkStmt);

        if (!$row || $row['USER_ID'] !== $userId) {
            return false; 
        }

        $mediaSql = "SELECT MEDIA_PATH FROM POST_MEDIA WHERE POST_ID = :post_id";
        $stmtMedia = oci_parse($conn, $mediaSql);
        oci_bind_by_name($stmtMedia, ":post_id", $postId);

        if (!oci_execute($stmtMedia)) {
            error_log("Gagal get media path: " . oci_error($stmtMedia)['message']);
            return false;
        }

        $mediaPaths = [];
        while ($mediaRow = oci_fetch_assoc($stmtMedia)) {
            $mediaPaths[] = $mediaRow['MEDIA_PATH'];
        }

        foreach ($mediaPaths as $path) {
            $filePath = realpath(__DIR__ . '/../../../' . $path);

            if ($filePath && file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $deleteReplySql = "
        DELETE FROM REPLY_COMMENTAR 
        WHERE COMMENTAR_ID IN (
            SELECT ID FROM COMMENTAR WHERE POST_ID = :post_id
        )";
        
        $stmtReply = oci_parse($conn, $deleteReplySql);
        oci_bind_by_name($stmtReply, ":post_id", $postId);

        if (!oci_execute($stmtReply)) {
            error_log("Gagal delete REPLY_COMMENTAR: " . oci_error($stmtReply)['message']);
            return false;
        }

        $deleteCommentSql = "DELETE FROM COMMENTAR WHERE POST_ID = :post_id";
        $stmtComment = oci_parse($conn, $deleteCommentSql);
        oci_bind_by_name($stmtComment, ":post_id", $postId);

        if (!oci_execute($stmtComment)) {
            error_log("Gagal delete COMMENTAR: " . oci_error($stmtComment)['message']);
            return false;
        }


        $deletePostSql = "DELETE FROM POSTS WHERE ID = :id";
        $stmtDelPost = oci_parse($conn, $deletePostSql);
        oci_bind_by_name($stmtDelPost, ":id", $postId);

        if (!oci_execute($stmtDelPost)) {
            $e = oci_error($stmtDelPost);
            error_log("Gagal delete POSTS: " . $e['message']);
            return false;
        }


        return true; 
    }

    public function getPostsByUser($userId)
    {
        $conn = self::getConnection();

        $sql = "
                SELECT 
                    P.ID AS POST_ID,
                    P.CONTENT,
                    P.USER_ID,
                    TO_CHAR(P.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT,
                    (
                        SELECT COUNT(*) FROM LIKE_POST L WHERE L.POST_ID = P.ID
                    ) AS TOTAL_LIKES,
                    (
                        SELECT COUNT(*) FROM LIKE_POST L 
                        WHERE L.POST_ID = P.ID AND L.USER_ID = :current_user_id
                    ) AS IS_LIKED,
                    (
                        SELECT COUNT(*) FROM COMMENTAR C WHERE C.POST_ID = P.ID
                    ) AS COMMENT_COUNT
                FROM POSTS P
                WHERE P.USER_ID = :user_id
                ORDER BY P.CREATED_AT DESC
            ";

        $stmt = oci_parse($conn, $sql);
        $currentUserId = $_SESSION['user_id'] ?? '';
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':current_user_id', $currentUserId);
        oci_execute($stmt);

        $posts = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $replySql = "
                    SELECT COUNT(*) AS REPLY_COUNT 
                    FROM REPLY_COMMENTAR R 
                    WHERE R.COMMENTAR_ID IN (
                        SELECT ID FROM COMMENTAR WHERE POST_ID = :post_id
                    )
                ";
            $replyStmt = oci_parse($conn, $replySql);
            oci_bind_by_name($replyStmt, ":post_id", $row['POST_ID']);
            oci_execute($replyStmt);
            $replyRow = oci_fetch_assoc($replyStmt);
            $replyCount = (int)$replyRow['REPLY_COUNT'];

            $commentCount = (int)$row['COMMENT_COUNT'];
            $row['TOTAL_COMMENT'] = $commentCount + $replyCount;

            // Ambil media post
            $row['MEDIA'] = $this->getMediaByPostId($row['POST_ID']);

            $posts[] = $row;
        }

        return $posts;
    }


    public function getPostById($postId)
    {
        $conn = self::getConnection();

        $sqlPost = "
                SELECT 
                    P.ID AS POST_ID,
                    P.CONTENT,
                    TO_CHAR(P.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT,
                    P.USER_ID,
                    U.USERNAME,
                    U.FULL_NAME,
                    U.PATH_PHOTO,
                    (
                        SELECT COUNT(*) FROM LIKE_POST L WHERE L.POST_ID = P.ID
                    ) AS TOTAL_LIKES,
                    (
                        SELECT COUNT(*) FROM LIKE_POST L 
                        WHERE L.POST_ID = P.ID AND L.USER_ID = :current_user_id
                    ) AS IS_LIKED
                FROM POSTS P
                JOIN USERS U ON P.USER_ID = U.ID
                WHERE P.ID = :post_id
            ";

        $stmtPost = oci_parse($conn, $sqlPost);
        $currentUserId = $_SESSION['user_id'] ?? '';
        oci_bind_by_name($stmtPost, ':post_id', $postId);
        oci_bind_by_name($stmtPost, ':current_user_id', $currentUserId);
        oci_execute($stmtPost);

        $post = oci_fetch_assoc($stmtPost);

        if (!$post) {
            return null;
        }

        $post['MEDIA'] = [];
        $sqlMedia = "SELECT MEDIA_PATH FROM POST_MEDIA WHERE POST_ID = :post_id";
        $stmtMedia = oci_parse($conn, $sqlMedia);
        oci_bind_by_name($stmtMedia, ':post_id', $postId);
        oci_execute($stmtMedia);

        while ($row = oci_fetch_assoc($stmtMedia)) {
            $post['MEDIA'][] = $row['MEDIA_PATH'];
        }

        oci_free_statement($stmtPost);
        oci_free_statement($stmtMedia);

        return $post;
    }

    public function searchPosts($keyword, $filter = 'top', $userId = null)
    {
        require_once __DIR__ . '/../../helpers/mentionHelper.php';
        $conn = self::getConnection();
        $orderBy = ($filter === 'latest') ? 'P.CREATED_AT DESC' : 'TOTAL_LIKES DESC';

        $sql = "
                SELECT 
                    P.ID AS POST_ID,
                    P.CONTENT,
                    P.USER_ID,
                    U.USERNAME,
                    U.FULL_NAME,
                    U.PATH_PHOTO,
                    (
                        SELECT COUNT(*) FROM LIKE_POST L WHERE L.POST_ID = P.ID
                    ) AS TOTAL_LIKES,
                    (
                        SELECT COUNT(*) FROM COMMENTAR C WHERE C.POST_ID = P.ID
                    ) AS COMMENT_COUNT,
                    (
                        SELECT COUNT(*) FROM REPLY_COMMENTAR R 
                        WHERE R.COMMENTAR_ID IN (
                            SELECT ID FROM COMMENTAR WHERE POST_ID = P.ID
                        )
                    ) AS REPLY_COUNT,
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 FROM LIKE_POST L WHERE L.POST_ID = P.ID AND L.USER_ID = :user_id
                        )
                        THEN 1 ELSE 0
                    END AS IS_LIKED
                FROM POSTS P
                JOIN USERS U ON P.USER_ID = U.ID
                WHERE LOWER(P.CONTENT) LIKE LOWER(:keyword) 
                OR LOWER(U.USERNAME) LIKE LOWER(:keyword)
                ORDER BY $orderBy
            ";

        $stmt = oci_parse($conn, $sql);
        $search = "%$keyword%";
        oci_bind_by_name($stmt, ":keyword", $search);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_execute($stmt);

        $posts = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if (isset($row['CONTENT']) && $row['CONTENT'] instanceof OCILob) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }

            $row['CONTENT'] = \MentionHelper::formatMentions($row['CONTENT']);

            $row['TOTAL_COMMENT'] = ((int)$row['COMMENT_COUNT']) + ((int)$row['REPLY_COUNT']);
            $row['IS_LIKED'] = ($row['IS_LIKED'] == 1);

            $mediaStmt = oci_parse($conn, "SELECT MEDIA_PATH FROM POST_MEDIA WHERE POST_ID = :pid");
            oci_bind_by_name($mediaStmt, ":pid", $row['POST_ID']);
            oci_execute($mediaStmt);
            $media = [];
            while ($m = oci_fetch_assoc($mediaStmt)) {
                $media[] = $m['MEDIA_PATH'];
            }
            $row['MEDIA'] = $media;

            $posts[] = $row;
        }

        return $posts;
    }

    public function getUsersByUsernames(array $usernames)
    {
        if (empty($usernames)) {
            return [];
        }

        $conn = self::getConnection();

        $placeholders = [];
        foreach ($usernames as $key => $value) {
            $placeholders[] = ":u" . $key;
        }
        $inClause = implode(', ', $placeholders);

        $sql = "SELECT ID, USERNAME FROM USERS WHERE USERNAME IN ($inClause)";

        $stmt = oci_parse($conn, $sql);

        foreach ($usernames as $key => $username) {
            oci_bind_by_name($stmt, ":u" . $key, $usernames[$key]);
        }

        oci_execute($stmt);

        $results = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $results[$row['USERNAME']] = $row;
        }

        oci_free_statement($stmt);
        return $results;
    }

    public function searchUsers($keyword)
{
    $conn = self::getConnection();

    $sql = "SELECT ID, USERNAME, FULL_NAME, PATH_PHOTO
            FROM USERS
            WHERE LOWER(USERNAME) LIKE LOWER(:kw)
               OR LOWER(FULL_NAME) LIKE LOWER(:kw)
            FETCH FIRST 10 ROWS ONLY";

    $stmt = oci_parse($conn, $sql);
    $like = "%$keyword%";
    oci_bind_by_name($stmt, ':kw', $like);

    oci_execute($stmt);

    $results = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $results[] = $row;
    }

    return $results;
}

}
