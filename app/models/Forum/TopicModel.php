<?php

require_once "app/models/BaseModel.php";

class TopicModel extends BaseModel
{
    public function createPostWithMedia($data, $files)
    {
        $conn = self::getConnection();

        try {
            $topicId = uniqid();

            $sqlTopic = "INSERT INTO FORUM_TOPICS (ID, FORUM_ID, USER_ID, CONTENT, CREATED_AT) 
                         VALUES (:id, :forum_id, :userId, :content, SYSDATE)";

            $stmt = oci_parse($conn, $sqlTopic);

            oci_bind_by_name($stmt, ":id", $topicId);
            oci_bind_by_name($stmt, ":userId", $data['user_id']);
            oci_bind_by_name($stmt, ":forum_id", $data['forum_id']);

            $clob = oci_new_descriptor($conn, OCI_D_LOB);
            oci_bind_by_name($stmt, ":content", $clob, -1, OCI_B_CLOB);

            $clob->writeTemporary($data['content'] ?? '');

            if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
                throw new Exception("Gagal membuat topik.");
            }
            $clob->close();

            if (!empty($files)) {
                $sqlMedia = "INSERT INTO TOPIC_MEDIA (ID, TOPIC_ID, MEDIA_PATH, MEDIA_TYPE, ORIGINAL_FILENAME) 
                             VALUES (:id, :topic_id, :path, :type, :filename)";

                $stmtMedia = oci_parse($conn, $sqlMedia);

                foreach ($files as $file) {
                    $mediaId = uniqid("media_");

                    oci_bind_by_name($stmtMedia, ":id", $mediaId);
                    oci_bind_by_name($stmtMedia, ":topic_id", $topicId);
                    oci_bind_by_name($stmtMedia, ":path", $file['path']);
                    oci_bind_by_name($stmtMedia, ":type", $file['type']);
                    oci_bind_by_name($stmtMedia, ":filename", $file['original_filename']);

                    if (!oci_execute($stmtMedia, OCI_NO_AUTO_COMMIT)) {
                        throw new Exception("Gagal menyimpan media.");
                    }
                }
            }

            oci_commit($conn);
            return ['status' => true, 'message' => 'Post created successfully!'];
        } catch (Exception $e) {
            oci_rollback($conn);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function getTopicsByForumId($forumId)
    {
        $conn = self::getConnection();
        $userId = $_SESSION['user_id'] ?? null;

        $sql = "SELECT 
                    t.ID, 
                    t.CONTENT, 
                    TO_CHAR(t.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') as CREATED_AT, 
                    u.FULL_NAME, 
                    u.PATH_PHOTO, 
                    u.ROLE, 
                    u.USERNAME, 
                    u.ID AS USER_ID, 
                    t.IS_PINNED,
                    (SELECT COUNT(*) FROM TOPIC_LIKES l WHERE l.TOPIC_ID = t.ID) AS TOTAL_LIKES,
                    (
                        (SELECT COUNT(*) FROM TOPIC_COMMENTS tc WHERE tc.TOPIC_ID = t.ID) 
                        + 
                        (SELECT COUNT(*) 
                            FROM COMMENT_REPLIES cr 
                            JOIN TOPIC_COMMENTS tc_parent ON cr.COMMENT_ID = tc_parent.ID 
                            WHERE tc_parent.TOPIC_ID = t.ID)
                    ) AS TOTAL_COMMENTS";

        if ($userId) {
            $sql .= ",
                    CASE WHEN EXISTS (
                        SELECT 1 FROM TOPIC_LIKES 
                        WHERE TOPIC_ID = t.ID AND USER_ID = :user_id
                    ) THEN 1 ELSE 0 END as IS_LIKED";
        } else {
            $sql .= ", 0 as IS_LIKED";
        }

        $sql .= " FROM FORUM_TOPICS t
                JOIN USERS u ON t.USER_ID = u.ID  
                WHERE t.FORUM_ID = :forum_id
                ORDER BY t.CREATED_AT DESC";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":forum_id", $forumId);

        if ($userId) {
            oci_bind_by_name($stmt, ":user_id", $userId);
        }

        oci_execute($stmt);

        $topics = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if (is_object($row['CONTENT'])) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            $row['MEDIA'] = $this->getMediaByTopicId($row['ID']);

            $row['IS_LIKED'] = (int) ($row['IS_LIKED'] ?? 0);

            $topics[] = $row;
        }

        return $topics;
    }

    public function deleteTopicById($topicId, $forumId)
    {
        $conn = self::getConnection();

        try {
            $sqlGetMedia = "SELECT MEDIA_PATH FROM TOPIC_MEDIA tm 
                        JOIN FORUM_TOPICS ft ON tm.TOPIC_ID = ft.ID
                        WHERE ft.ID = :topic_id 
                        AND ft.FORUM_ID = :forum_id";

            $stmtMedia = oci_parse($conn, $sqlGetMedia);
            oci_bind_by_name($stmtMedia, ":topic_id", $topicId);
            oci_bind_by_name($stmtMedia, ":forum_id", $forumId);
            oci_execute($stmtMedia);

            $filesToDelete = [];
            while ($row = oci_fetch_assoc($stmtMedia)) {
                $filesToDelete[] = __DIR__ . '/../../../storage/forums/topics/' . $row['MEDIA_PATH'];
            }
            oci_free_statement($stmtMedia);

            $sqlDeleteMediaParams = "DELETE FROM TOPIC_MEDIA WHERE TOPIC_ID = :topic_id";
            $stmtDelMedia = oci_parse($conn, $sqlDeleteMediaParams);
            oci_bind_by_name($stmtDelMedia, ":topic_id", $topicId);

            if (!oci_execute($stmtDelMedia, OCI_NO_AUTO_COMMIT)) {
                throw new Exception("Gagal menghapus data referensi media.");
            }
            oci_free_statement($stmtDelMedia);

            $sqlDeleteTopic = "DELETE FROM FORUM_TOPICS 
                           WHERE ID = :topic_id 
                           AND FORUM_ID = :forum_id";

            $stmtTopic = oci_parse($conn, $sqlDeleteTopic);
            oci_bind_by_name($stmtTopic, ":topic_id", $topicId);
            oci_bind_by_name($stmtTopic, ":forum_id", $forumId);

            $isExecuted = oci_execute($stmtTopic, OCI_NO_AUTO_COMMIT);
            $rowsDeleted = oci_num_rows($stmtTopic);

            oci_commit($conn);

            foreach ($filesToDelete as $filePath) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            return ['status' => true, 'message' => 'Topik berhasil dihapus!'];
        } catch (Exception $e) {
            oci_rollback($conn);
            return ['status' => false, 'message' => 'Database Error: ' . $e->getMessage()];
        }
    }

    public function togglePin($topicId)
    {
        $conn = self::getConnection();

        try {
            $sqlInfo = "SELECT FORUM_ID, IS_PINNED FROM FORUM_TOPICS WHERE ID = :id";
            $stmtInfo = oci_parse($conn, $sqlInfo);
            oci_bind_by_name($stmtInfo, ":id", $topicId);
            oci_execute($stmtInfo);

            $topic = oci_fetch_assoc($stmtInfo);
            oci_free_statement($stmtInfo);

            if (!$topic) {
                return ['success' => false, 'message' => 'Topik tidak ditemukan.'];
            }

            $forumId = $topic['FORUM_ID'];
            $currentStatus = (int)$topic['IS_PINNED'];
            $newStatus = ($currentStatus === 1) ? 0 : 1;

            if ($newStatus === 1) {
                $sqlCount = "SELECT COUNT(*) AS TOTAL_PINNED 
                         FROM FORUM_TOPICS 
                         WHERE FORUM_ID = :forum_id AND IS_PINNED = 1";
                $stmtCount = oci_parse($conn, $sqlCount);
                oci_bind_by_name($stmtCount, ":forum_id", $forumId);
                oci_execute($stmtCount);

                $row = oci_fetch_assoc($stmtCount);
                $totalPinned = (int)$row['TOTAL_PINNED'];
                oci_free_statement($stmtCount);

                if ($totalPinned >= 3) {
                    return ['success' => false, 'message' => 'Gagal: Maksimal hanya 3 topik yang boleh disematkan (Pinned) dalam forum ini.'];
                }
            }

            $sqlUpdate = "UPDATE FORUM_TOPICS SET IS_PINNED = :status WHERE ID = :id";
            $stmtUpdate = oci_parse($conn, $sqlUpdate);
            oci_bind_by_name($stmtUpdate, ":status", $newStatus);
            oci_bind_by_name($stmtUpdate, ":id", $topicId);

            if (oci_execute($stmtUpdate, OCI_COMMIT_ON_SUCCESS)) {
                $msg = ($newStatus === 1) ? 'Topik berhasil disematkan!' : 'Semat topik dilepas.';
                return ['success' => true, 'message' => $msg, 'is_pinned' => $newStatus];
            } else {
                return ['success' => false, 'message' => 'Gagal mengupdate database.'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public function getPinnedTopics($forumId)
    {
        $conn = self::getConnection();

        $sql = "SELECT 
                t.ID, 
                t.CONTENT, 
                t.CREATED_AT, 
                u.USERNAME, 
                u.FULL_NAME,
                u.ID AS USER_ID,
                u.PATH_PHOTO AS PROFILE_PIC,
                (SELECT COUNT(*) FROM TOPIC_COMMENTS c WHERE c.TOPIC_ID = t.ID) AS TOTAL_COMMENTS,
                (SELECT COUNT(*) FROM TOPIC_LIKES l WHERE l.TOPIC_ID = t.ID) AS TOTAL_LIKES,
                m.MEDIA_PATH,
                m.MEDIA_TYPE,
                m.ORIGINAL_FILENAME
            FROM FORUM_TOPICS t
            JOIN USERS u ON t.USER_ID = u.ID
            LEFT JOIN (
                SELECT 
                    TOPIC_ID, 
                    MEDIA_PATH, 
                    MEDIA_TYPE, 
                    ORIGINAL_FILENAME,
                    ROW_NUMBER() OVER (PARTITION BY TOPIC_ID ORDER BY CREATED_AT ASC) as rn
                FROM TOPIC_MEDIA
            ) m ON t.ID = m.TOPIC_ID AND m.rn = 1
            WHERE t.FORUM_ID = :forum_id 
            AND t.IS_PINNED = 1
            ORDER BY t.CREATED_AT DESC";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":forum_id", $forumId);
        oci_execute($stmt);

        $pinnedTopics = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if (is_object($row['CONTENT'])) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            $pinnedTopics[] = $row;
        }

        return $pinnedTopics;
    }

    public function getTopicById($topicId)
    {
        $conn = self::getConnection();
        $userId = $_SESSION['user_id'] ?? null;

        $sql = "SELECT 
                    t.ID, 
                    t.FORUM_ID, 
                    t.CONTENT, 
                    TO_CHAR(t.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') as CREATED_AT, 
                    t.IS_PINNED,
                    u.FULL_NAME, 
                    u.PATH_PHOTO, 
                    u.ROLE, 
                    u.USERNAME, 
                    u.ID AS USER_ID,
                    (SELECT COUNT(*) FROM TOPIC_LIKES tl WHERE tl.TOPIC_ID = t.ID) AS TOTAL_LIKES,
                    (
                        (SELECT COUNT(*) FROM TOPIC_COMMENTS tc WHERE tc.TOPIC_ID = t.ID) 
                        + 
                        (SELECT COUNT(*) 
                        FROM COMMENT_REPLIES cr 
                        JOIN TOPIC_COMMENTS tc_parent ON cr.COMMENT_ID = tc_parent.ID 
                        WHERE tc_parent.TOPIC_ID = t.ID)
                    ) AS TOTAL_COMMENTS";

        // Add IS_LIKED check if user is logged in
        if ($userId) {
            $sql .= ",
                    CASE WHEN EXISTS (
                        SELECT 1 FROM TOPIC_LIKES 
                        WHERE TOPIC_ID = t.ID AND USER_ID = :user_id
                    ) THEN 1 ELSE 0 END as IS_LIKED";
        } else {
            $sql .= ", 0 as IS_LIKED";
        }

        $sql .= " FROM FORUM_TOPICS t
                JOIN USERS u ON t.USER_ID = u.ID  
                WHERE t.ID = :topic_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":topic_id", $topicId);

        if ($userId) {
            oci_bind_by_name($stmt, ":user_id", $userId);
        }

        if (!oci_execute($stmt)) {
            return false;
        }

        $row = oci_fetch_assoc($stmt);

        if ($row) {
            if (isset($row['CONTENT']) && is_object($row['CONTENT'])) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }

            $row['MEDIA'] = $this->getMediaByTopicId($row['ID']);
            $row['TOTAL_LIKES'] = (int) ($row['TOTAL_LIKES'] ?? 0);
            $row['TOTAL_COMMENTS'] = (int) ($row['TOTAL_COMMENTS'] ?? 0);

            // Ensure IS_LIKED is integer
            $row['IS_LIKED'] = (int) ($row['IS_LIKED'] ?? 0);

            return $row;
        }

        return false;
    }

    public function getMediaByTopicId($topicId)
    {
        $conn = self::getConnection();
        $sql = "SELECT * FROM TOPIC_MEDIA WHERE TOPIC_ID = :topic_id ORDER BY CREATED_AT ASC";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":topic_id", $topicId);
        oci_execute($stmt);

        $media = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $media[] = $row;
        }
        return $media;
    }

    public function updateTopic($topicId, $content, $newFiles = [], $deletedMediaIds = [])
    {
        $conn = self::getConnection();

        try {
            // 1. Update konten topic
            $sqlUpdate = "UPDATE FORUM_TOPICS 
                         SET CONTENT = :content 
                         WHERE ID = :id";

            $stmt = oci_parse($conn, $sqlUpdate);
            oci_bind_by_name($stmt, ":id", $topicId);

            $clob = oci_new_descriptor($conn, OCI_D_LOB);
            oci_bind_by_name($stmt, ":content", $clob, -1, OCI_B_CLOB);
            $clob->writeTemporary($content ?? '');

            if (!oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
                throw new Exception("Gagal update konten topic.");
            }
            $clob->close();
            oci_free_statement($stmt);

            // 2. Hapus media yang dipilih untuk dihapus
            if (!empty($deletedMediaIds)) {
                // Ambil path file untuk dihapus dari filesystem
                $sqlGetPath = "SELECT MEDIA_PATH FROM TOPIC_MEDIA WHERE ID = :id";
                $stmtPath = oci_parse($conn, $sqlGetPath);

                foreach ($deletedMediaIds as $mediaId) {
                    oci_bind_by_name($stmtPath, ":id", $mediaId);
                    oci_execute($stmtPath, OCI_NO_AUTO_COMMIT);

                    if ($row = oci_fetch_assoc($stmtPath)) {
                        $filePath = __DIR__ . '/../../../storage/forums/topics/' . $row['MEDIA_PATH'];
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
                oci_free_statement($stmtPath);

                // Hapus dari database
                $sqlDelete = "DELETE FROM TOPIC_MEDIA WHERE ID = :id";
                $stmtDelete = oci_parse($conn, $sqlDelete);

                foreach ($deletedMediaIds as $mediaId) {
                    oci_bind_by_name($stmtDelete, ":id", $mediaId);
                    if (!oci_execute($stmtDelete, OCI_NO_AUTO_COMMIT)) {
                        throw new Exception("Gagal menghapus media.");
                    }
                }
                oci_free_statement($stmtDelete);
            }

            if (!empty($newFiles)) {
                $sqlMedia = "INSERT INTO TOPIC_MEDIA (ID, TOPIC_ID, MEDIA_PATH, MEDIA_TYPE, ORIGINAL_FILENAME) 
                            VALUES (:id, :topic_id, :path, :type, :filename)";

                $stmtMedia = oci_parse($conn, $sqlMedia);

                foreach ($newFiles as $file) {
                    $mediaId = uniqid("media_");

                    oci_bind_by_name($stmtMedia, ":id", $mediaId);
                    oci_bind_by_name($stmtMedia, ":topic_id", $topicId);
                    oci_bind_by_name($stmtMedia, ":path", $file['path']);
                    oci_bind_by_name($stmtMedia, ":type", $file['type']);
                    oci_bind_by_name($stmtMedia, ":filename", $file['original_filename']);

                    if (!oci_execute($stmtMedia, OCI_NO_AUTO_COMMIT)) {
                        throw new Exception("Gagal menyimpan media baru.");
                    }
                }
                oci_free_statement($stmtMedia);
            }

            oci_commit($conn);
            return ['status' => true, 'message' => 'Topic berhasil diupdate!'];
        } catch (Exception $e) {
            oci_rollback($conn);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function isTopicOwner($topicId, $userId)
    {
        $conn = self::getConnection();

        $sql = "SELECT COUNT(*) AS TOTAL 
                FROM FORUM_TOPICS 
                WHERE ID = :topic_id AND USER_ID = :user_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":topic_id", $topicId);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return ($row['TOTAL'] > 0);
    }

    public function canPinMoreTopics($forumId)
    {
        $conn = self::getConnection();

        $sql = "SELECT COUNT(*) AS TOTAL_PINNED 
                FROM FORUM_TOPICS 
                WHERE FORUM_ID = :forum_id AND IS_PINNED = 1";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":forum_id", $forumId);
        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return (int)$row['TOTAL_PINNED'] < 3;
    }
}
