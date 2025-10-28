<?php

require_once __DIR__ . '/../BaseModel.php';

class CommentModel extends BaseModel
{
    public function getCommentsByPostId($postId)
    {
        $conn = self::getConnection();

        $sql = "
            SELECT 
                C.ID AS COMMENT_ID,
                C.POST_ID,
                C.USER_ID,
                C.MESSAGE,
                TO_CHAR(C.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT,
                U.USERNAME,
                U.FULL_NAME,
                U.PATH_PHOTO
            FROM COMMENTAR C
            JOIN USERS U ON C.USER_ID = U.ID
            WHERE C.POST_ID = :post_id
            ORDER BY C.CREATED_AT ASC
        ";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":post_id", $postId);
        oci_execute($stmt);

        $comments = [];

        while ($row = oci_fetch_assoc($stmt)) {
            $commentId = $row['COMMENT_ID'];

            $comments[$commentId] = [
                'COMMENT_ID' => $commentId,
                'POST_ID' => $row['POST_ID'],
                'USER_ID' => $row['USER_ID'],
                'USERNAME' => $row['USERNAME'],
                'FULL_NAME' => $row['FULL_NAME'],
                'PATH_PHOTO' => $row['PATH_PHOTO'],
                'MESSAGE' => $row['MESSAGE'],
                'CREATED_AT' => $row['CREATED_AT'],
                'REPLIES' => []
            ];
        }

        $sqlReply = "
            SELECT 
                R.ID AS REPLY_ID,
                R.COMMENTAR_ID,
                R.USER_ID,
                R.MESSAGE,
                TO_CHAR(R.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT,
                UR.USERNAME AS REPLY_USERNAME,
                UR.FULL_NAME AS REPLY_FULL_NAME,
                UR.PATH_PHOTO AS REPLY_PATH_PHOTO,
                UC.USERNAME AS COMMENT_USERNAME,
                UC.FULL_NAME AS COMMENT_FULL_NAME
            FROM REPLY_COMMENTAR R
            JOIN USERS UR ON R.USER_ID = UR.ID
            JOIN COMMENTAR C ON R.COMMENTAR_ID = C.ID
            JOIN USERS UC ON C.USER_ID = UC.ID
            WHERE C.POST_ID = :post_id
            ORDER BY R.CREATED_AT ASC
        ";

        $stmtReply = oci_parse($conn, $sqlReply);
        oci_bind_by_name($stmtReply, ":post_id", $postId);
        oci_execute($stmtReply);

        while ($row = oci_fetch_assoc($stmtReply)) {
            $commentId = $row['COMMENTAR_ID'];
            if (isset($comments[$commentId])) {
            $comments[$commentId]['REPLIES'][] = [
                'REPLY_ID' => $row['REPLY_ID'],
                'USER_ID' => $row['USER_ID'],
                'USERNAME' => $row['REPLY_USERNAME'],
                'FULL_NAME' => $row['REPLY_FULL_NAME'],
                'PATH_PHOTO' => $row['REPLY_PATH_PHOTO'],
                'MESSAGE' => $row['MESSAGE'],
                'CREATED_AT' => $row['CREATED_AT'],
                'REPLY_TO_USERNAME' => $row['COMMENT_USERNAME'],
                'REPLY_TO_FULLNAME' => $row['COMMENT_FULL_NAME']
            ];
            }
        }

        return array_values($comments);
    }

    public function addComment($postId, $userId, $message)
    {
        $conn = self::getConnection();
        $commentId = uniqid('comment_');

        $sql = "
            INSERT INTO COMMENTAR (ID, POST_ID, USER_ID, MESSAGE, CREATED_AT)
            VALUES (:id, :post_id, :user_id, :message, CURRENT_TIMESTAMP)
        ";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":id", $commentId);
        oci_bind_by_name($stmt, ":post_id", $postId);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_bind_by_name($stmt, ":message", $message);

        if (oci_execute($stmt)) {
            return $commentId;
        } else {
            $e = oci_error($stmt);
            error_log("Gagal tambah komentar: " . $e['message']);
            return false;
        }
    }

   public function addReply($commentId, $userId, $message)
    {
        $conn = self::getConnection();
        $replyId = uniqid('reply_');

        error_log("==== DEBUG ADD REPLY ====");
        error_log("commentId: $commentId");
        error_log("userId: $userId");
        error_log("message: $message");

        // ✅ Cek apakah commentId beneran ada di tabel COMMENTAR
        $checkSql = "SELECT COUNT(*) AS CNT FROM COMMENTAR WHERE ID = :cid";
        $checkStmt = oci_parse($conn, $checkSql);
        oci_bind_by_name($checkStmt, ":cid", $commentId);
        oci_execute($checkStmt);
        oci_fetch($checkStmt);
        $count = oci_result($checkStmt, "CNT");
        error_log("CHECK COMMENTAR.ID EXIST? => $count");

        if ($count == 0) {
            error_log("❌ COMMENTAR_ID TIDAK DITEMUKAN DI TABEL COMMENTAR");
        }

        $sql = "
            INSERT INTO REPLY_COMMENTAR (ID, COMMENTAR_ID, USER_ID, MESSAGE, CREATED_AT)
            VALUES (:id, :comment_id, :user_id, :message, CURRENT_TIMESTAMP)
        ";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":id", $replyId);
        oci_bind_by_name($stmt, ":comment_id", $commentId);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_bind_by_name($stmt, ":message", $message);

        if (oci_execute($stmt)) {
            return $replyId;
        } else {
            $e = oci_error($stmt);
            error_log("Gagal tambah reply: " . $e['message']);
            return false;
        }
    }
    public function getPostOwner($postId)
    {
        $conn = self::getConnection();
        $sql = "SELECT U.ID, U.FULL_NAME
                FROM POSTS P 
                JOIN USERS U ON P.USER_ID = U.ID
                WHERE P.ID = :post_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':post_id', $postId);
        oci_execute($stmt);
        return oci_fetch_assoc($stmt);
    }
}
