<?php

require_once __DIR__ . "/../BaseModel.php";

class ChatMessage extends BaseModel
{
    public function createMessage($data)
    {
        $conn = self::getConnection();
        $uuid = uniqid();

        try {
            $contentLength = strlen($data['content']);

            if ($contentLength < 4000) {
                $sql = "INSERT INTO FORUM_MESSAGES 
                    (ID, FORUM_ID, SENDER_ID, CONTENT, PATH_MEDIA, ORIGINAL_FILENAME, TYPE) 
                    VALUES (:id, :forum_id, :sender_id, :content, :path_media, :original_filename, :type)";

                $stmt = oci_parse($conn, $sql);

                oci_bind_by_name($stmt, ':id', $uuid);
                oci_bind_by_name($stmt, ':forum_id', $data['forum_id']);
                oci_bind_by_name($stmt, ':sender_id', $data['sender_id']);
                oci_bind_by_name($stmt, ':content', $data['content']);
                oci_bind_by_name($stmt, ':path_media', $data['path_media']);
                oci_bind_by_name($stmt, ':original_filename', $data['original_filename']);
                oci_bind_by_name($stmt, ':type', $data['type']);

                $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

                oci_free_statement($stmt);

                if ($result) {
                    return ['ID' => $uuid];
                }
            } else {
                $sql = "INSERT INTO FORUM_MESSAGES 
                    (ID, FORUM_ID, SENDER_ID, CONTENT, PATH_MEDIA, ORIGINAL_FILENAME, TYPE) 
                    VALUES (:id, :forum_id, :sender_id, EMPTY_CLOB(), :path_media, :original_filename, :type)
                    RETURNING CONTENT INTO :content";

                $stmt = oci_parse($conn, $sql);
                $clob = oci_new_descriptor($conn, OCI_D_LOB);

                oci_bind_by_name($stmt, ':id', $uuid);
                oci_bind_by_name($stmt, ':forum_id', $data['forum_id']);
                oci_bind_by_name($stmt, ':sender_id', $data['sender_id']);
                oci_bind_by_name($stmt, ':path_media', $data['path_media']);
                oci_bind_by_name($stmt, ':original_filename', $data['original_filename']);
                oci_bind_by_name($stmt, ':type', $data['type']);
                oci_bind_by_name($stmt, ':content', $clob, -1, OCI_B_CLOB);

                if (oci_execute($stmt, OCI_NO_AUTO_COMMIT)) {
                    if ($clob->save($data['content'])) {
                        oci_commit($conn);
                        $clob->free();
                        oci_free_statement($stmt);
                        return ['ID' => $uuid];
                    }
                }

                oci_rollback($conn);
                $clob->free();
                oci_free_statement($stmt);
            }
        } catch (\Exception $e) {
            error_log('Error in createMessage: ' . $e->getMessage());
            if ($conn) {
                oci_rollback($conn);
            }
        }

        return false;
    }

    public function getMessagesSince($forumId, $timestamp = null)
    {
        $conn = self::getConnection();
        if (!$conn) return [];

        $baseQuery = "SELECT m.ID, m.FORUM_ID, m.SENDER_ID, m.CONTENT,m.ORIGINAL_FILENAME, m.PATH_MEDIA, m.TYPE, 
                         TO_CHAR(m.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS.FF6') AS CREATED_AT,
                         u.FULL_NAME AS SENDER_NAME, u.PATH_PHOTO AS SENDER_PHOTO, u.ROLE
                  FROM FORUM_MESSAGES m
                  JOIN USERS u ON m.SENDER_ID = u.ID
                  WHERE m.FORUM_ID = :forum_id";

        if (!empty($timestamp)) {
            $sql = $baseQuery . " AND m.CREATED_AT > TO_TIMESTAMP(:since_timestamp, 'YYYY-MM-DD HH24:MI:SS.FF6') ORDER BY m.CREATED_AT ASC";
        } else {
            $sql = $baseQuery . " ORDER BY m.CREATED_AT ASC";
        }

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':forum_id', $forumId);
        if ($timestamp) {
            oci_bind_by_name($stmt, ':since_timestamp', $timestamp);
        }

        oci_execute($stmt);

        $messages = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if (is_object($row['CONTENT'])) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            $messages[] = $row;
        }

        oci_free_statement($stmt);
        oci_close($conn);
        return $messages;
    }

    public function getMessagesByForumId($forum_id)
    {
        $conn = self::getConnection();
        $messages = [];
        $stmt = null;

        try {
            $sql = "SELECT 
                        fm.ID,
                        fm.FORUM_ID,
                        fm.SENDER_ID,
                        fm.CONTENT,
                        fm.PATH_MEDIA,
                        fm.TYPE,
                        fm.ORIGINAL_FILENAME,
                        TO_CHAR(fm.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT,
                        u.FULL_NAME AS SENDER_NAME, 
                        u.PATH_PHOTO AS SENDER_PHOTO,
                        u.ROLE 
                    FROM 
                        FORUM_MESSAGES fm
                    JOIN 
                        USERS u ON fm.SENDER_ID = u.ID
                    WHERE 
                        fm.FORUM_ID = :forum_id
                    ORDER BY 
                        fm.CREATED_AT ASC";

            $stmt = oci_parse($conn, $sql);

            oci_bind_by_name($stmt, ':forum_id', $forum_id);

            oci_execute($stmt);

            while (($row = oci_fetch_assoc($stmt)) != false) {
                if (is_object($row['CONTENT']) && get_class($row['CONTENT']) === 'OCILob') {
                    $row['CONTENT'] = $row['CONTENT']->load();
                }

                $messages[] = $row;
            }
        } catch (\Exception $e) {
            error_log('Error in getMessagesByForumId: ' . $e->getMessage());
            return [];
        } finally {
            if ($stmt) {
                oci_free_statement($stmt);
            }
            oci_close($conn);
        }

        return $messages;
    }

    public function isUserInForum($userId, $forumId)
    {
        $conn = self::getConnection();

        $sql = "SELECT COUNT(1) AS CNT FROM FORUM_MEMBERS 
            WHERE FORUM_ID = :forum_id AND USER_ID = :user_id";

        $stid = oci_parse($conn, $sql);

        $clean_uid = trim($userId);
        $clean_fid = trim($forumId);

        oci_bind_by_name($stid, ':forum_id', $clean_fid);
        oci_bind_by_name($stid, ':user_id', $clean_uid);

        if (!oci_execute($stid)) {
            $e = oci_error($stid);
            error_log("SQL Error: " . $e['message']);
            return false;
        }

        $row = oci_fetch_assoc($stid);

        if ($row && isset($row['CNT']) && $row['CNT'] > 0) {
            return true;
        }

        return false;
    }
    public function getForumMediaPreview($forumId, $limit)
    {
        $conn = self::getConnection();

        if (!$conn) {
            return [];
        }

        try {
            $sql = "SELECT 
                    fm.ID,
                    fm.PATH_MEDIA,
                    fm.ORIGINAL_FILENAME,
                    fm.TYPE,
                    TO_CHAR(fm.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT
                FROM 
                    FORUM_MESSAGES fm
                WHERE 
                    fm.FORUM_ID = :forum_id
                    AND fm.PATH_MEDIA IS NOT NULL
                    AND fm.TYPE IN ('IMAGE', 'VIDEO', 'FILE')
                ORDER BY 
                    fm.CREATED_AT DESC
                FETCH FIRST :limit ROWS ONLY";

            $stmt = oci_parse($conn, $sql);

            oci_bind_by_name($stmt, ':forum_id', $forumId);
            oci_bind_by_name($stmt, ':limit', $limit);

            oci_execute($stmt);

            $media = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $media[] = [
                    'file' => basename($row['PATH_MEDIA']),
                    'type' => strtolower($row['TYPE']),
                    'path' => $row['PATH_MEDIA'],
                    'original_name' => $row['ORIGINAL_FILENAME'],
                    'created_at' => $row['CREATED_AT']
                ];
            }

            oci_free_statement($stmt);
            return $media;
        } catch (\Exception $e) {
            error_log('Error in getForumMediaPreview: ' . $e->getMessage());
            return [];
        } finally {
            oci_close($conn);
        }
    }

    public function getAllForumMedia($forumId)
    {
        $conn = self::getConnection();

        if (!$conn) {
            return [];
        }

        try {
            $sql = "SELECT 
                    fm.ID,
                    fm.PATH_MEDIA,
                    fm.ORIGINAL_FILENAME,
                    fm.TYPE,
                    u.FULL_NAME AS SENDER_NAME,
                    TO_CHAR(fm.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT
                FROM 
                    FORUM_MESSAGES fm
                JOIN 
                    USERS u ON fm.SENDER_ID = u.ID
                WHERE 
                    fm.FORUM_ID = :forum_id
                    AND fm.PATH_MEDIA IS NOT NULL
                    AND fm.TYPE IN ('IMAGE', 'VIDEO', 'FILE')
                ORDER BY 
                    fm.CREATED_AT DESC";

            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':forum_id', $forumId);
            oci_execute($stmt);

            $media = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $media[] = [
                    'file' => basename($row['PATH_MEDIA']),
                    'type' => strtolower($row['TYPE']),
                    'path' => $row['PATH_MEDIA'],
                    'original_name' => $row['ORIGINAL_FILENAME'],
                    'sender_name' => $row['SENDER_NAME'],
                    'created_at' => $row['CREATED_AT']
                ];
            }

            oci_free_statement($stmt);
            return $media;
        } catch (\Exception $e) {
            error_log('Error in getAllForumMedia: ' . $e->getMessage());
            return [];
        } finally {
            oci_close($conn);
        }
    }
}
