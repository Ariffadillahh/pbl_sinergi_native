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

    public function toggleLikeTopic($userId, $topicId)
    {
        $conn = self::getConnection();

        // Cek apakah sudah di-like
        $checkSql = "SELECT ID FROM TOPIC_LIKES WHERE USER_ID = :user_id AND TOPIC_ID = :topic_id";
        $stmt = oci_parse($conn, $checkSql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':topic_id', $topicId);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);

        if ($row) {
            // UNLIKE: Hapus like
            $deleteSql = "DELETE FROM TOPIC_LIKES WHERE USER_ID = :user_id AND TOPIC_ID = :topic_id";
            $del = oci_parse($conn, $deleteSql);
            oci_bind_by_name($del, ':user_id', $userId);
            oci_bind_by_name($del, ':topic_id', $topicId);

            if (!oci_execute($del, OCI_COMMIT_ON_SUCCESS)) {
                $error = oci_error($del);
                throw new Exception("Failed to unlike: " . $error['message']);
            }

            return ['action' => 'unliked'];
        } else {
            // LIKE: Tambah like baru
            $id = uniqid('like_', true);

            $insertSql = "INSERT INTO TOPIC_LIKES (ID, USER_ID, TOPIC_ID, CREATED_AT) 
                      VALUES (:id, :user_id, :topic_id, SYSDATE)";

            $ins = oci_parse($conn, $insertSql);
            oci_bind_by_name($ins, ':id', $id);
            oci_bind_by_name($ins, ':user_id', $userId);
            oci_bind_by_name($ins, ':topic_id', $topicId);

            if (!oci_execute($ins, OCI_COMMIT_ON_SUCCESS)) {
                $error = oci_error($ins);
                throw new Exception("Failed to like: " . $error['message']);
            }

            return ['action' => 'liked'];
        }
    }

    public function getLikeCountTopic($topicId)
    {
        $conn = self::getConnection();

        $sql = "SELECT COUNT(*) AS TOTAL FROM TOPIC_LIKES WHERE TOPIC_ID = :topic_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':topic_id', $topicId);

        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            throw new Exception("Failed to get like count: " . $error['message']);
        }

        $row = oci_fetch_assoc($stmt);
        return (int)($row['TOTAL'] ?? 0);
    }

    public function isLikedByUserTopic($topicId, $userId)
    {
        $conn = self::getConnection();

        $sql = "SELECT COUNT(*) AS TOTAL 
            FROM TOPIC_LIKES 
            WHERE TOPIC_ID = :topic_id AND USER_ID = :user_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':topic_id', $topicId);
        oci_bind_by_name($stmt, ':user_id', $userId);

        if (!oci_execute($stmt)) {
            return false;
        }

        $row = oci_fetch_assoc($stmt);
        return (int)$row['TOTAL'] > 0;
    }

    public function getTopicOwner($topicId)
    {
        $conn = self::getConnection();

        // FIX UTAMA: Langsung ambil USER_ID dari FORUM_TOPICS
        $sql = "SELECT USER_ID, 
                   (SELECT FULL_NAME FROM USERS WHERE ID = FORUM_TOPICS.USER_ID) AS FULL_NAME
            FROM FORUM_TOPICS 
            WHERE ID = :topic_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':topic_id', $topicId);

        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            error_log("Error getting topic owner: " . $error['message']);
            return null;
        }

        $result = oci_fetch_assoc($stmt);

        // Debugging
        if (!$result) {
            error_log("Topic not found: " . $topicId);
            return null;
        }

        error_log("Topic Owner Found - USER_ID: " . $result['USER_ID'] . ", Name: " . $result['FULL_NAME']);

        return $result;
    }

    public function topicExists($topicId)
    {
        $conn = self::getConnection();

        $sql = "SELECT COUNT(*) AS TOTAL FROM FORUM_TOPICS WHERE ID = :id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $topicId);

        if (!oci_execute($stmt)) {
            $error = oci_error($stmt);
            error_log("topicExists error: " . ($error['message'] ?? 'unknown'));
            return false;
        }

        $row = oci_fetch_assoc($stmt);
        return (int)$row['TOTAL'] > 0;
    }
}
