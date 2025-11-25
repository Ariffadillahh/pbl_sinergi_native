<?php

require_once __DIR__ . "/../BaseModel.php";

class GroupChat extends BaseModel
{
    public function allGroupChats()
    {
        $conn = self::getConnection();

        if (!$conn) {
            error_log("Gagal terhubung ke database.");
            return [];
        }

        $sql = "SELECT ID, NAME, IS_PRIVATE, OWNER_ID FROM GROUP_CHATS ORDER BY NAME ASC";

        $stmt = oci_parse($conn, $sql);

        if (!oci_execute($stmt)) {
            return [];
        }

        $groupChat = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $groupChat[] = $row;
        }

        oci_free_statement($stmt);

        return $groupChat;
    }

    public function getGroupChatsByUserId($userId)
    {
        $conn = self::getConnection();

        if (!$conn) {
            error_log(" Gagal terhubung ke database.");
            return [];
        }

        $sql = "
            SELECT 
                gc.ID, 
                gc.NAME, 
                gc.PATH_PHOTO, 
                gc.CREATED_AT,
                gc.ABOUT,
                gc.OWNER_ID,
                (
                    SELECT COUNT(*) 
                    FROM GROUP_CHAT_MEMBERS gcm
                    WHERE gcm.GROUP_CHAT_ID = gc.ID
                    AND gcm.USER_ID != gc.OWNER_ID
                ) AS MEMBER_COUNT,
                (
                    SELECT COUNT(msg_count.ID)
                    FROM GROUP_CHAT_MESSAGES msg_count
                    WHERE msg_count.GROUP_CHAT_ID = gcm.GROUP_CHAT_ID
                    AND msg_count.CREATED_AT > gcm.LAST_READ_AT
                    AND msg_count.SENDER_ID != gcm.USER_ID
                ) AS UNREAD_COUNT,

                lm.CONTENT AS LAST_MESSAGE_CONTENT,
                lm.TYPE AS LAST_MESSAGE_TYPE,
                lm.CREATED_AT AS LAST_MESSAGE_AT
                
            FROM 
                GROUP_CHATS gc
            JOIN 
                GROUP_CHAT_MEMBERS gcm ON gc.ID = gcm.GROUP_CHAT_ID
            
            OUTER APPLY (
                SELECT msg.CONTENT, msg.TYPE, msg.CREATED_AT
                FROM GROUP_CHAT_MESSAGES msg
                WHERE msg.GROUP_CHAT_ID = gc.ID 
                ORDER BY msg.CREATED_AT DESC
                FETCH FIRST 1 ROWS ONLY
            ) lm
                
            WHERE 
             gcm.USER_ID = :user_id_bv
            ORDER BY 
                lm.CREATED_AT DESC NULLS LAST";

        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal parse query: " . $e['message']);
            oci_close($conn);
            return [];
        }

        oci_bind_by_name($stmt, ':user_id_bv', $userId);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            error_log("Gagal execute query: " . $e['message']);
            oci_free_statement($stmt);
            oci_close($conn);
            return [];
        }

        $groupChat = [];
        oci_fetch_all($stmt, $groupChat, 0, -1, OCI_FETCHSTATEMENT_BY_ROW | OCI_ASSOC);
        oci_free_statement($stmt);
        oci_close($conn);

        return $groupChat;
    }

    public function findById($id)
    {
        $conn = self::getConnection();
        $sql = "
            SELECT 
                gc.ID, 
                gc.NAME, 
                gc.ABOUT, 
                gc.IS_PRIVATE, 
                gc.ACCESS_KEY, 
                gc.PATH_PHOTO, 
                gc.OWNER_ID, 
                gc.CREATED_AT,
                u.FULL_NAME AS OWNER_NAME,
                u.ROLE AS ROLE_OWNER,
                u.PATH_PHOTO AS PATH_PHOTO_OWNER
            FROM GROUP_CHATS gc
            LEFT JOIN USERS u ON gc.OWNER_ID = u.ID
            WHERE gc.ID = :id_bv
        ";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id_bv', $id);
        oci_execute($stmt);

        $groupChat = oci_fetch_assoc($stmt);

        oci_free_statement($stmt);
        oci_close($conn);

        return $groupChat;
    }

    public function create($data)
    {
        $conn = self::getConnection();
        $stmt = null;
        $stmt2 = null;

        try {
            $id = uniqid();
            $photoPath = $data['photo'] ?? null;
            $isPrivate = (int)$data['isPrivate'];
            $keyGroupChat = $isPrivate ? ($data['keyGroupChat'] ?? null) : null;

            $sqlGroupChat = "INSERT INTO GROUP_CHATS (ID, NAME, ABOUT, IS_PRIVATE, ACCESS_KEY, PATH_PHOTO, OWNER_ID) 
                     VALUES (:id, :name, :about, :is_private, :access_key, :path_photo, :owner_id)";

            $stmt = oci_parse($conn, $sqlGroupChat);

            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $data['groupChatName']);
            oci_bind_by_name($stmt, ':about', $data['bio']);
            oci_bind_by_name($stmt, ':is_private', $isPrivate);
            oci_bind_by_name($stmt, ':access_key', $keyGroupChat);
            oci_bind_by_name($stmt, ':path_photo', $photoPath);
            oci_bind_by_name($stmt, ':owner_id', $data['user_id']);

            oci_execute($stmt, OCI_NO_AUTO_COMMIT);

            $memberId = uniqid();
            $sqlMembers = "INSERT INTO GROUP_CHAT_MEMBERS (ID, GROUP_CHAT_ID, USER_ID, JOINED_AT) 
                     VALUES (:id, :group_chat_id, :user_id, CURRENT_TIMESTAMP)";

            $stmt2 = oci_parse($conn, $sqlMembers);

            oci_bind_by_name($stmt2, ':id', $memberId);
            oci_bind_by_name($stmt2, ':groupChat_id', $id);
            oci_bind_by_name($stmt2, ':user_id', $data['user_id']);

            oci_execute($stmt2, OCI_NO_AUTO_COMMIT);

            oci_commit($conn);

            return $id;
        } catch (Exception $e) {
            oci_rollback($conn);
            return false;
        } finally {
            if ($stmt) {
                oci_free_statement($stmt);
            }
            if ($stmt2) {
                oci_free_statement($stmt2);
            }
        }
    }


    public function edit($id, $data)
    {
        $conn = self::getConnection();
        $stmt = null;

        try {
            $setClauses = [];
            foreach (array_keys($data) as $key) {
                if (in_array($key, ['NAME', 'ABOUT', 'IS_PRIVATE', 'ACCESS_KEY', 'PATH_PHOTO'])) {
                    $setClauses[] = "$key = :$key";
                }
            }

            if (empty($setClauses)) {
                return false;
            }

            $sql = "UPDATE GROUP_CHATS SET " . implode(', ', $setClauses) . " WHERE ID = :id";

            $stmt = oci_parse($conn, $sql);

            foreach ($data as $key => &$value) {
                if (in_array($key, ['NAME', 'ABOUT', 'IS_PRIVATE', 'ACCESS_KEY', 'PATH_PHOTO'])) {
                    oci_bind_by_name($stmt, ":$key", $value);
                }
            }
            unset($value);

            oci_bind_by_name($stmt, ':id', $id);

            $result = oci_execute($stmt);

            if (!$result) {
                oci_rollback($conn);
                return false;
            }

            return true;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        } finally {
            if ($stmt) {
                oci_free_statement($stmt);
            }
        }
    }


    public function delete($id)
    {
        $conn = self::getConnection();
        $stmt_get_messages = null;
        $stmt_messages = null;
        $stmt_members = null;
        $stmt_groupChat = null;

        try {
            $sql_get_messages = "SELECT PATH_MEDIA FROM GROUP_CHAT_MESSAGES 
                             WHERE GROUP_CHAT_ID = :GROUP_CHAT_id";

            $stmt_get_messages = oci_parse($conn, $sql_get_messages);
            oci_bind_by_name($stmt_get_messages, ':groupChat_id', $id);
            oci_execute($stmt_get_messages);

            $filesToDelete = [];
            while ($row = oci_fetch_assoc($stmt_get_messages)) {
                if (!empty($row['PATH_MEDIA'])) {
                    $filesToDelete[] = $row['PATH_MEDIA'];
                }
            }
            oci_free_statement($stmt_get_messages);

            $projectRoot = realpath(__DIR__ . '/../../../');

            foreach ($filesToDelete as $filePath) {

                $fullPath = $projectRoot . '/' . $filePath;

                if (file_exists($fullPath)) {
                    unlink($fullPath);
                } else {
                    error_log("File tidak ditemukan: " . $fullPath);
                }
            }

            $sql_messages = "DELETE FROM GROUP_CHAT_MESSAGES WHERE GROUP_CHAT_ID = :group_chat_id";
            $stmt_messages = oci_parse($conn, $sql_messages);
            oci_bind_by_name($stmt_messages, ':group_chat_id', $id);
            oci_execute($stmt_messages, OCI_NO_AUTO_COMMIT);

            $sql_members = "DELETE FROM GROUP_CHAT_MEMBERS WHERE GROUP_CHAT_ID = :group_chat_id";
            $stmt_members = oci_parse($conn, $sql_members);
            oci_bind_by_name($stmt_members, ':group_chat_id', $id);
            oci_execute($stmt_members, OCI_NO_AUTO_COMMIT);

            $sql_groupChat = "DELETE FROM GROUP_CHATS WHERE ID = :id";
            $stmt_groupChat = oci_parse($conn, $sql_groupChat);
            oci_bind_by_name($stmt_groupChat, ':id', $id);
            oci_execute($stmt_groupChat, OCI_NO_AUTO_COMMIT);

            oci_commit($conn);

            return true;
        } catch (Exception $e) {

            oci_rollback($conn);
            error_log($e->getMessage());
            return false;
        } finally {

            if ($stmt_messages) oci_free_statement($stmt_messages);
            if ($stmt_members) oci_free_statement($stmt_members);
            if ($stmt_groupChat) oci_free_statement($stmt_groupChat);
        }
    }

    public function exitGroupChat($groupChatId, $userId)
    {
        $conn = self::getConnection();
        $stmt = null;

        try {
            $sql = "DELETE FROM GROUP_CHAT_MEMBERS WHERE GROUP_CHAT_ID = :group_chat_id AND USER_ID = :user_id";

            $stmt = oci_parse($conn, $sql);

            oci_bind_by_name($stmt, ':group_chat_id', $groupChatId);
            oci_bind_by_name($stmt, ':user_id', $userId);

            $result = oci_execute($stmt);

            return $result;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        } finally {
            if ($stmt) {
                oci_free_statement($stmt);
            }
        }
    }

    public function searchByName($keyword, $userId)
    {
        $conn = self::getConnection();
        $stmt = null;
        $results = [];

        try {
            $sql = "SELECT gc.*, u.USERNAME as OWNER_USERNAME, gcm.USER_ID as IS_MEMBER
                FROM GROUP_CHATS gc
                LEFT JOIN GROUP_CHAT_MEMBERS gcm ON gc.ID = gcm.GroupChat_ID AND gcm.USER_ID = :user_id
                LEFT JOIN USERS u ON gc.OWNER_ID = u.ID
                WHERE UPPER(gc.NAME) LIKE :keyword";

            $stmt = oci_parse($conn, $sql);

            $searchKeyword = '%' . strtoupper($keyword) . '%';
            oci_bind_by_name($stmt, ':keyword', $searchKeyword);
            oci_bind_by_name($stmt, ':user_id', $userId);

            oci_execute($stmt);

            while ($row = oci_fetch_assoc($stmt)) {
                $row['IS_PRIVATE'] = (int)$row['IS_PRIVATE'];
                $results[] = $row;
            }

            return $results;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        } finally {
            if ($stmt) {
                oci_free_statement($stmt);
            }
        }
    }

    public function createReport($data)
    {
        $conn = self::getConnection();
        if (!$conn) {
            return ['success' => false, 'message' => 'Gagal terhubung ke database.'];
        }

        $stmt_insert = null;
        try {
            $insertData = [
                'id' => uniqid(),
                'target_id' => $data['target_id'],
                'target_type' => $data['target_type'],
                'user_id' => $data['user_id'],
                'reason' => $data['reason']
            ];

            $query = "INSERT INTO REPORT (ID, TARGET_ID, TARGET_TYPE, USER_ID, REASON) 
                  VALUES (:id, :target_id, :target_type, :user_id, :reason)";

            $stmt_insert = oci_parse($conn, $query);
            if (!$stmt_insert) {
                $error = oci_error($conn);
                return ['success' => false, 'message' => 'Terjadi kesalahan saat menyiapkan query.'];
            }

            oci_bind_by_name($stmt_insert, ':id', $insertData['id']);
            oci_bind_by_name($stmt_insert, ':target_id', $insertData['target_id']);
            oci_bind_by_name($stmt_insert, ':target_type', $insertData['target_type']);
            oci_bind_by_name($stmt_insert, ':user_id', $insertData['user_id']);
            oci_bind_by_name($stmt_insert, ':reason', $insertData['reason']);

            $result = oci_execute($stmt_insert);

            if ($result) {
                return ['success' => true, 'message' => 'Laporan Anda berhasil dikirim. Terima kasih.'];
            } else {
                $error = oci_error($stmt_insert);
                return ['success' => false, 'message' => 'Gagal mengirim laporan: ' . htmlspecialchars($error['message'])];
            }
        } finally {
            if ($stmt_insert) {
                oci_free_statement($stmt_insert);
            }
        }
    }

    public function getUnreadCount($groupChatId, $userId)
    {
        $conn = self::getConnection();

        if (!$conn) {
            error_log("Gagal terhubung ke database.");
            return 0;
        }

        $sql = "
            SELECT COUNT(gcm.ID) AS UNREAD_COUNT
            FROM GROUP_CHAT_MESSAGES m
            JOIN GROUP_CHAT_MEMBERS gcm ON m.GROUP_CHAT_ID = gcm.GROUP_CHAT_ID
            WHERE
                m.GROUP_CHAT_ID = :group_chat_id
                AND gcm.USER_ID = :userId
                AND m.CREATED_AT > gcm.LAST_READ_AT
                AND m.SENDER_ID != :userId
        ";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':group_chat_id', $groupChatId);
        oci_bind_by_name($stmt, ':userId', $userId);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            error_log("Gagal menjalankan query: " . $e['message']);
            oci_free_statement($stmt);
            oci_close($conn);
            return 0;
        }

        $row = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);
        oci_close($conn);

        $unreadCount = isset($row['UNREAD_COUNT']) ? (int)$row['UNREAD_COUNT'] : 0;

        error_log("[getUnreadCount] GROUP_CHAT_ID={$groupChatId}, User ID={$userId}, Unread={$unreadCount}");

        return $unreadCount;
    }

    public function updateLastReadAt($groupChatId, $userId)
    {
        $conn = self::getConnection();

        if (!$conn) {
            error_log("Gagal terhubung ke database.");
            return;
        }

        $sql = "
            UPDATE GROUP_CHAT_MEMBERS
            SET LAST_READ_AT = CURRENT_TIMESTAMP
            WHERE GROUP_CHAT_ID = :groupChatId
            AND USER_ID = :userId
        ";

        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal parse query: " . $e['message']);
            oci_close($conn);
            return;
        }

        oci_bind_by_name($stmt, ':groupChatId', $groupChatId);
        oci_bind_by_name($stmt, ':userId', $userId);

        if (!oci_execute($stmt, OCI_COMMIT_ON_SUCCESS)) {
            $e = oci_error($stmt);
            error_log("Gagal menjalankan query: " . $e['message']);
            oci_free_statement($stmt);
            oci_close($conn);
            return;
        }

        $rowsAffected = oci_num_rows($stmt);

        if ($rowsAffected > 0) {
            error_log("Update berhasil untuk GROUP_CHAT_ID={$groupChatId}, USER_ID={$userId}");
        } else {
            error_log("Tidak ada baris diperbarui untuk GROUP_CHAT_ID={$groupChatId}, USER_ID={$userId}.");
        }

        oci_free_statement($stmt);
        oci_close($conn);
    }

    public function joinGroupChat($groupChatId, $userId, $accessKey = null)
    {
        $conn = self::getConnection();

        $groupChatSql = "SELECT IS_PRIVATE, ACCESS_KEY FROM GROUP_CHATS WHERE ID = :group_chat_id";
        $stmt_groupChat = oci_parse($conn, $groupChatSql);
        oci_bind_by_name($stmt_groupChat, ':group_chat_id', $groupChatId);
        oci_execute($stmt_groupChat);
        $groupChat = oci_fetch_assoc($stmt_groupChat);
        oci_free_statement($stmt_groupChat);

        if (!$groupChat) {
            return ['success' => false, 'message' => 'Group Chat tidak ditemukan.'];
        }

        if ($groupChat['IS_PRIVATE'] == 1) {
            if (empty($accessKey)) {
                return ['success' => false, 'message' => 'Kunci akses diperlukan untuk Group Chat ini.'];
            }
            if (strtoupper($accessKey) !== strtoupper($groupChat['ACCESS_KEY'])) {
                return ['success' => false, 'message' => 'Kunci akses yang Anda masukkan salah.'];
            }
        }

        $checkSql = "SELECT COUNT(*) as COUNT FROM GROUP_CHAT_MEMBERS WHERE GROUP_CHAT_ID = :group_chat_id AND USER_ID = :user_id";
        $stmt_check = oci_parse($conn, $checkSql);
        oci_bind_by_name($stmt_check, ':group_chat_id', $groupChatId);
        oci_bind_by_name($stmt_check, ':user_id', $userId);
        oci_execute($stmt_check);
        $row = oci_fetch_assoc($stmt_check);
        oci_free_statement($stmt_check);

        if ($row['COUNT'] > 0) {
            return ['success' => false, 'message' => 'Pengguna ini sudah tercatat sebagai anggota Group Chat.'];
        }

        $stmt_insert = null;
        try {
            $id = uniqid();
            $sql = "INSERT INTO GROUP_CHAT_MEMBERS (ID, GROUP_CHAT_ID, USER_ID, JOINED_AT) 
                VALUES (:id, :group_chat_id, :user_id, CURRENT_TIMESTAMP)";

            $stmt_insert = oci_parse($conn, $sql);
            oci_bind_by_name($stmt_insert, ':id', $id);
            oci_bind_by_name($stmt_insert, ':group_chat_id', $groupChatId);
            oci_bind_by_name($stmt_insert, ':user_id', $userId);

            $result = oci_execute($stmt_insert);

            if ($result) {
                return ['success' => true, 'message' => 'Berhasil bergabung dengan Group Chat.'];
            } else {
                return ['success' => false, 'message' => 'Gagal bergabung dengan Group Chat.'];
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan server.'];
        } finally {
            if ($stmt_insert) oci_free_statement($stmt_insert);
        }
    }
}
