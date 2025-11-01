<?php
require_once __DIR__ . '/../BaseModel.php';

class LikeModel extends BaseModel
{
    public function toggleLike($userId, $postId)
    {
        $conn = self::getConnection();

        $checkSql = "SELECT ID FROM LIKE_POST WHERE USER_ID = :user_id AND POST_ID = :post_id";
        $stmt = oci_parse($conn, $checkSql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':post_id', $postId);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);

        if ($row) {
            $deleteSql = "DELETE FROM LIKE_POST WHERE USER_ID = :user_id AND POST_ID = :post_id";
            $del = oci_parse($conn, $deleteSql);
            oci_bind_by_name($del, ':user_id', $userId);
            oci_bind_by_name($del, ':post_id', $postId);
            oci_execute($del, OCI_COMMIT_ON_SUCCESS);
            return ['action' => 'unliked'];
        } else {
            $id = uniqid('like_');
            $insertSql = "INSERT INTO LIKE_POST (ID, USER_ID, POST_ID, CREATED_AT)
                          VALUES (:id, :user_id, :post_id, CURRENT_TIMESTAMP)";
            $ins = oci_parse($conn, $insertSql);
            oci_bind_by_name($ins, ':id', $id);
            oci_bind_by_name($ins, ':user_id', $userId);
            oci_bind_by_name($ins, ':post_id', $postId);
            oci_execute($ins, OCI_COMMIT_ON_SUCCESS);
            return ['action' => 'liked'];
        }
    }

    public function getLikeCount($postId)
    {
        $conn = self::getConnection();
        $sql = "SELECT COUNT(*) AS TOTAL FROM LIKE_POST WHERE POST_ID = :post_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':post_id', $postId);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        return (int)($row['TOTAL'] ?? 0);
    }

    public function isLikedByUser($postId, $userId)
    {
        $conn = self::getConnection();
        $sql = "SELECT COUNT(*) AS TOTAL FROM LIKE_POST WHERE POST_ID = :post_id AND USER_ID = :user_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':post_id', $postId);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        return $row['TOTAL'] > 0;
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
