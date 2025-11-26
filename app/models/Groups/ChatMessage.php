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
                $sql = "INSERT INTO GROUP_CHAT_MESSAGES 
                    (ID, GROUP_CHAT_ID, SENDER_ID, CONTENT, PATH_MEDIA, ORIGINAL_FILENAME, TYPE) 
                    VALUES (:id, :group_chat_id, :sender_id, :content, :path_media, :original_filename, :type)";

                $stmt = oci_parse($conn, $sql);

                oci_bind_by_name($stmt, ':id', $uuid);
                oci_bind_by_name($stmt, ':group_chat_id', $data['group_chat_id']);
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
                $sql = "INSERT INTO GROUP_CHAT_MESSAGES 
                    (ID, GROUP_CHAT_ID, SENDER_ID, CONTENT, PATH_MEDIA, ORIGINAL_FILENAME, TYPE) 
                    VALUES (:id, :group_chat_id, :sender_id, EMPTY_CLOB(), :path_media, :original_filename, :type)
                    RETURNING CONTENT INTO :content";

                $stmt = oci_parse($conn, $sql);
                $clob = oci_new_descriptor($conn, OCI_D_LOB);

                oci_bind_by_name($stmt, ':id', $uuid);
                oci_bind_by_name($stmt, ':group_chat_id', $data['group_chat_id']);
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

    public function getMessagesSince($groupChatId, $timestamp = null)
    {
        $conn = self::getConnection();
        if (!$conn) return [];

        $baseQuery = "SELECT m.ID, m.GROUP_CHAT_ID, m.SENDER_ID, m.CONTENT,m.ORIGINAL_FILENAME, m.PATH_MEDIA, m.TYPE, 
                         TO_CHAR(m.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS.FF6') AS CREATED_AT,
                         u.FULL_NAME AS SENDER_NAME, u.PATH_PHOTO AS SENDER_PHOTO, u.ROLE
                  FROM GROUP_CHAT_MESSAGES m
                  JOIN USERS u ON m.SENDER_ID = u.ID
                  WHERE m.GROUP_CHAT_ID = :group_chat_id";

        if (!empty($timestamp)) {
            $sql = $baseQuery . " AND m.CREATED_AT > TO_TIMESTAMP(:since_timestamp, 'YYYY-MM-DD HH24:MI:SS.FF6') ORDER BY m.CREATED_AT ASC";
        } else {
            $sql = $baseQuery . " ORDER BY m.CREATED_AT ASC";
        }

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':group_chat_id', $groupChatId);
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

    public function getMessagesByGroupChatId($group_chat_id)
    {
        $conn = self::getConnection();
        $messages = [];
        $stmt = null;

        try {
            $sql = "SELECT 
                        gcm.ID,
                        gcm.GROUP_CHAT_ID,
                        gcm.SENDER_ID,
                        gcm.CONTENT,
                        gcm.PATH_MEDIA,
                        gcm.TYPE,
                        gcm.ORIGINAL_FILENAME,
                        TO_CHAR(gcm.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT,
                        u.FULL_NAME AS SENDER_NAME, 
                        u.PATH_PHOTO AS SENDER_PHOTO,
                        u.ROLE 
                    FROM 
                        GROUP_CHAT_MESSAGES gcm
                    JOIN 
                        USERS u ON gcm.SENDER_ID = u.ID
                    WHERE 
                        gcm.GROUP_CHAT_ID = :group_chat_id
                    ORDER BY 
                        gcm.CREATED_AT ASC";

            $stmt = oci_parse($conn, $sql);

            oci_bind_by_name($stmt, ':group_chat_id', $group_chat_id);

            oci_execute($stmt);

            while (($row = oci_fetch_assoc($stmt)) != false) {
                if (is_object($row['CONTENT']) && get_class($row['CONTENT']) === 'OCILob') {
                    $row['CONTENT'] = $row['CONTENT']->load();
                }

                $messages[] = $row;
            }
        } catch (\Exception $e) {
            error_log('Error in getMessagesByGroupChatId: ' . $e->getMessage());
            return [];
        } finally {
            if ($stmt) {
                oci_free_statement($stmt);
            }
            oci_close($conn);
        }

        return $messages;
    }

    public function isUserInGroupChat($userId, $groupChatId)
    {
        $conn = self::getConnection();

        $sql = "SELECT COUNT(1) AS CNT FROM GROUP_CHAT_MEMBERS 
            WHERE GROUP_CHAT_ID = :group_chat_id AND USER_ID = :user_id";

        $stid = oci_parse($conn, $sql);

        $clean_uid = trim($userId);
        $clean_fid = trim($groupChatId);

        oci_bind_by_name($stid, ':group_chat_id', $clean_fid);
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
    public function getGroupChatMediaPreview($groupChatId, $limit)
    {
        $conn = self::getConnection();

        if (!$conn) {
            return [];
        }

        try {
            $sql = "SELECT 
                    gcm.ID,
                    gcm.PATH_MEDIA,
                    gcm.ORIGINAL_FILENAME,
                    gcm.TYPE,
                    TO_CHAR(gcm.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT
                FROM 
                    GROUP_CHAT_MESSAGES gcm
                WHERE 
                    gcm.GROUP_CHAT_ID = :group_chat_id
                    AND gcm.PATH_MEDIA IS NOT NULL
                    AND gcm.TYPE IN ('IMAGE', 'VIDEO', 'FILE')
                ORDER BY 
                    gcm.CREATED_AT DESC
                FETCH FIRST :limit ROWS ONLY";

            $stmt = oci_parse($conn, $sql);

            oci_bind_by_name($stmt, ':group_chat_id', $groupChatId);
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
            error_log('Error in getGroupChatMediaPreview: ' . $e->getMessage());
            return [];
        } finally {
            oci_close($conn);
        }
    }

    public function getAllGroupChatMedia($groupChatId)
    {
        $conn = self::getConnection();

        if (!$conn) {
            return [];
        }

        try {
            $sql = "SELECT 
                    gcm.ID,
                    gcm.PATH_MEDIA,
                    gcm.ORIGINAL_FILENAME,
                    gcm.TYPE,
                    u.FULL_NAME AS SENDER_NAME,
                    TO_CHAR(gcm.CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT
                FROM 
                    GROUP_CHAT_MESSAGES gcm
                JOIN 
                    USERS u ON gcm.SENDER_ID = u.ID
                WHERE 
                    gcm.GROUP_CHAT_ID = :group_chat_id
                    AND gcm.PATH_MEDIA IS NOT NULL
                    AND gcm.TYPE IN ('IMAGE', 'VIDEO', 'FILE')
                ORDER BY 
                    gcm.CREATED_AT DESC";

            $stmt = oci_parse($conn, $sql);
            oci_bind_by_name($stmt, ':group_chat_id', $groupChatId);
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
            error_log('Error in getAllGroupChatMedia: ' . $e->getMessage());
            return [];
        } finally {
            oci_close($conn);
        }
    }
}
