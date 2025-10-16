<?php

require_once __DIR__ . "/../BaseModel.php";

class ChatMessage extends BaseModel
{
    public static function createMessage($data)
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

    public static function getMessageById($id)
    {
        $conn = self::getConnection();
        if (!$conn) return false;

        $sql = "SELECT * FROM FORUM_MESSAGES WHERE ID = :id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id);

        $rowData = false;
        if (oci_execute($stmt)) {
            $row = oci_fetch_assoc($stmt);
            if ($row && is_object($row['CONTENT'])) {
                $row['CONTENT'] = $row['CONTENT']->load();
            }
            $rowData = $row;
        }

        oci_free_statement($stmt);
        oci_close($conn);
        return $rowData;
    }

    public static function getMessagesSince($forumId, $timestamp = null)
    {
        $conn = self::getConnection();
        if (!$conn) return [];

        $baseQuery = "SELECT m.ID, m.FORUM_ID, m.SENDER_ID, m.CONTENT,m.ORIGINAL_FILENAME, m.PATH_MEDIA, m.TYPE, 
                         TO_CHAR(m.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS.FF6') AS CREATED_AT,
                         u.FULL_NAME AS SENDER_NAME, u.PATH_PHOTO AS SENDER_PHOTO
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

    public static function getMessagesByForumId($forum_id)
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
                        u.USERNAME AS SENDER_NAME, 
                        u.PATH_PHOTO AS SENDER_PHOTO 
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
}
