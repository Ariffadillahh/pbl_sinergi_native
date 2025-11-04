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
                        rc.ID AS REPLY_ID,
                        rc.COMMENTAR_ID,
                        rc.USER_ID AS REPLY_USER_ID,
                        rc.MESSAGE,
                        rc.PARENT_ID,
                        ru.USERNAME AS REPLY_USERNAME,
                        ru.FULL_NAME AS REPLY_FULL_NAME,
                        ru.PATH_PHOTO AS REPLY_PATH_PHOTO,
                        cu.USERNAME AS COMMENT_USERNAME,
                        cu.FULL_NAME AS COMMENT_FULL_NAME,
                        pu.USERNAME AS PARENT_USERNAME,
                        pu.FULL_NAME AS PARENT_FULL_NAME,
                        TO_CHAR(rc.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT
                    FROM REPLY_COMMENTAR rc
                    JOIN COMMENTAR c 
                        ON rc.COMMENTAR_ID = c.ID
                    JOIN USERS ru 
                        ON rc.USER_ID = ru.ID
                    JOIN USERS cu 
                        ON c.USER_ID = cu.ID
                    LEFT JOIN REPLY_COMMENTAR prc 
                        ON rc.PARENT_ID = prc.ID
                    LEFT JOIN USERS pu 
                        ON prc.USER_ID = pu.ID
                    WHERE c.POST_ID = :post_id
                    ORDER BY rc.CREATED_AT ASC
            ";

        $stmtReply = oci_parse($conn, $sqlReply);
        oci_bind_by_name($stmtReply, ":post_id", $postId);
        oci_execute($stmtReply);

        while ($row = oci_fetch_assoc($stmtReply)) {
            $commentId = $row['COMMENTAR_ID'];

            if (isset($comments[$commentId])) {
                $comments[$commentId]['REPLIES'][] = [
                    'REPLY_ID'          => $row['REPLY_ID'],
                    'USER_ID'           => $row['REPLY_USER_ID'],
                    'USERNAME'          => $row['REPLY_USERNAME'],
                    'FULL_NAME'         => $row['REPLY_FULL_NAME'],
                    'PATH_PHOTO'        => $row['REPLY_PATH_PHOTO'],
                    'MESSAGE'           => $row['MESSAGE'],
                    'CREATED_AT'        => $row['CREATED_AT'],
                    'PARENT_ID'         => $row['PARENT_ID'],
                    'REPLY_TO_USERNAME' => $row['PARENT_USERNAME']
                        ?? $row['COMMENT_USERNAME'],
                    'REPLY_TO_FULLNAME' => $row['PARENT_FULL_NAME']
                        ?? $row['COMMENT_FULL_NAME'],
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

    public function addReply($commentId, $userId, $message, $parentId)
    {
        $conn = self::getConnection();
        $replyId = 'reply_' . uniqid();

        error_log("==== DEBUG ADD REPLY ====");
        error_log("Comment ID (Jangkar): $commentId");
        error_log("User ID: $userId");
        error_log("Parent ID (Balasan ke): " . ($parentId ?? 'NULL'));
        error_log("Message: $message");

        $checkSql = "SELECT COUNT(*) AS CNT FROM COMMENTAR WHERE ID = :cid";
        $checkStmt = oci_parse($conn, $checkSql);
        oci_bind_by_name($checkStmt, ":cid", $commentId);
        oci_execute($checkStmt);
        $row = oci_fetch_array($checkStmt, OCI_ASSOC);
        $count = $row['CNT'];
        if ($count == 0) {
            error_log("❌ GAGAL: COMMENTAR_ID '$commentId' tidak ditemukan.");
            return false;
        }

        $sql = "
            INSERT INTO REPLY_COMMENTAR (ID, COMMENTAR_ID, USER_ID, MESSAGE, PARENT_ID)
            VALUES (:id, :comment_id, :user_id, :message, :parent_id)
        ";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":id", $replyId);
        oci_bind_by_name($stmt, ":comment_id", $commentId);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_bind_by_name($stmt, ":message", $message);


        oci_bind_by_name($stmt, ":parent_id", $parentId);

        if (oci_execute($stmt)) {
            error_log("✅ SUKSES: Reply ditambahkan dengan ID: $replyId");
            return true;
        } else {
            $e = oci_error($stmt);
            error_log("❌ GAGAL INSERT REPLY: " . $e['message']);
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

    public function getCommentDetails($commentId)
    {
        $conn = self::getConnection();

        $sql = "SELECT USER_ID, POST_ID 
            FROM COMMENTAR 
            WHERE ID = :comment_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':comment_id', $commentId);

        if (oci_execute($stmt)) {
            $details = oci_fetch_assoc($stmt);
            oci_free_statement($stmt);

            if ($details) {
                return [
                    'success' => true,
                    'details' => $details
                ];
            }
        }

        oci_free_statement($stmt);
        return [
            'success' => false,
            'details' => null
        ];
    }

    public function getReplyDetails($replyId)
    {
        $conn = self::getConnection();
        $sql = "SELECT U.ID, U.FULL_NAME
                FROM REPLY_COMMENTAR P 
                JOIN USERS U ON P.USER_ID = U.ID
                WHERE P.ID = :reply_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':reply_id', $replyId);
        oci_execute($stmt);
        return oci_fetch_assoc($stmt);
    }
}
