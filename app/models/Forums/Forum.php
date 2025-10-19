<?php

require_once __DIR__ . "/../BaseModel.php";

class Forum extends BaseModel
{
    public $id;
    public $name;
    public $about;
    public $is_private;
    public $access_key;
    public $path_photo;
    public $owner_id;
    public $created_at;


    public static function getForumsByUserId($userId)
    {
        $conn = self::getConnection();

        $sql = "SELECT f.ID, f.NAME, f.PATH_PHOTO, f.ABOUT, f.CREATED_AT
            FROM FORUMS f
            JOIN FORUM_MEMBERS fm ON f.ID = fm.FORUM_ID
            WHERE fm.USER_ID = :user_id_bv
            ORDER BY f.CREATED_AT DESC";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ':user_id_bv', $userId);

        oci_execute($stmt);

        $forums = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $forums[] = $row;
        }

        oci_free_statement($stmt);

        return $forums;
    }


    public static function findById($id)
    {
        $conn = self::getConnection();
        $sql = "SELECT ID, NAME, ABOUT, IS_PRIVATE, ACCESS_KEY, PATH_PHOTO, OWNER_ID, CREATED_AT FROM FORUMS WHERE ID = :id_bv";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id_bv', $id);

        oci_execute($stmt);

        $forum = oci_fetch_assoc($stmt);

        oci_free_statement($stmt);

        return $forum;
    }

    public static function create($data)
    {
        $conn = self::getConnection();
        $stmt = null;
        $stmt2 = null;

        try {
            $id = uniqid();
            $photoPath = $data['photo'] ?? null;
            $isPrivate = (int)$data['isPrivate'];
            $keyForum = $isPrivate ? ($data['keyForum'] ?? null) : null;

            $sqlForum = "INSERT INTO FORUMS (ID, NAME, ABOUT, IS_PRIVATE, ACCESS_KEY, PATH_PHOTO, OWNER_ID) 
                     VALUES (:id, :name, :about, :is_private, :access_key, :path_photo, :owner_id)";

            $stmt = oci_parse($conn, $sqlForum);

            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $data['forumName']);
            oci_bind_by_name($stmt, ':about', $data['bio']);
            oci_bind_by_name($stmt, ':is_private', $isPrivate);
            oci_bind_by_name($stmt, ':access_key', $keyForum);
            oci_bind_by_name($stmt, ':path_photo', $photoPath);
            oci_bind_by_name($stmt, ':owner_id', $data['user_id']);

            oci_execute($stmt, OCI_NO_AUTO_COMMIT);

            $memberId = uniqid();
            $sqlMembers = "INSERT INTO FORUM_MEMBERS (ID, FORUM_ID, USER_ID, JOINED_AT) 
                     VALUES (:id, :forum_id, :user_id, CURRENT_TIMESTAMP)";

            $stmt2 = oci_parse($conn, $sqlMembers);

            oci_bind_by_name($stmt2, ':id', $memberId);
            oci_bind_by_name($stmt2, ':forum_id', $id);
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


    public static function edit($id, $data)
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

            $sql = "UPDATE FORUMS SET " . implode(', ', $setClauses) . " WHERE ID = :id";

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


    public static function delete($id)
    {
        $conn = self::getConnection();
        $stmt_messages = null;
        $stmt_members = null;
        $stmt_forum = null;

        try {
            $sql_messages = "DELETE FROM FORUM_MESSAGES WHERE FORUM_ID = :forum_id";
            $stmt_messages = oci_parse($conn, $sql_messages);
            oci_bind_by_name($stmt_messages, ':forum_id', $id);
            oci_execute($stmt_messages, OCI_NO_AUTO_COMMIT);

            $sql_members = "DELETE FROM FORUM_MEMBERS WHERE FORUM_ID = :forum_id";
            $stmt_members = oci_parse($conn, $sql_members);
            oci_bind_by_name($stmt_members, ':forum_id', $id);
            oci_execute($stmt_members, OCI_NO_AUTO_COMMIT);

            $sql_forum = "DELETE FROM FORUMS WHERE ID = :id";
            $stmt_forum = oci_parse($conn, $sql_forum);
            oci_bind_by_name($stmt_forum, ':id', $id);
            oci_execute($stmt_forum, OCI_NO_AUTO_COMMIT);

            oci_commit($conn);
            return true;
        } catch (Exception $e) {
            oci_rollback($conn);
            error_log($e->getMessage());
            return false;
        } finally {
            if ($stmt_messages) oci_free_statement($stmt_messages);
            if ($stmt_members) oci_free_statement($stmt_members);
            if ($stmt_forum) oci_free_statement($stmt_forum);
        }
    }

    public static function exitForum($forumId, $userId)
    {
        $conn = self::getConnection();
        $stmt = null;

        try {
            $sql = "DELETE FROM FORUM_MEMBERS WHERE FORUM_ID = :forum_id AND USER_ID = :user_id";

            $stmt = oci_parse($conn, $sql);

            oci_bind_by_name($stmt, ':forum_id', $forumId);
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

    public static function searchByName($keyword, $userId)
    {
        $conn = self::getConnection();
        $stmt = null;
        $results = [];

        try {
            $sql = "SELECT f.*, u.USERNAME as OWNER_USERNAME, fm.USER_ID as IS_MEMBER
                FROM FORUMS f
                LEFT JOIN FORUM_MEMBERS fm ON f.ID = fm.FORUM_ID AND fm.USER_ID = :user_id
                LEFT JOIN USERS u ON f.OWNER_ID = u.ID
                WHERE UPPER(f.NAME) LIKE :keyword";

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

    public static function joinForum($forumId, $userId, $accessKey = null)
    {
        $conn = self::getConnection();

        $forumSql = "SELECT IS_PRIVATE, ACCESS_KEY FROM FORUMS WHERE ID = :forum_id";
        $stmt_forum = oci_parse($conn, $forumSql);
        oci_bind_by_name($stmt_forum, ':forum_id', $forumId);
        oci_execute($stmt_forum);
        $forum = oci_fetch_assoc($stmt_forum);
        oci_free_statement($stmt_forum);

        if (!$forum) {
            return ['success' => false, 'message' => 'Forum tidak ditemukan.'];
        }

        if ($forum['IS_PRIVATE'] == 1) {
            if (empty($accessKey)) {
                return ['success' => false, 'message' => 'Kunci akses diperlukan untuk forum ini.'];
            }
            if (strtoupper($accessKey) !== strtoupper($forum['ACCESS_KEY'])) {
                return ['success' => false, 'message' => 'Kunci akses yang Anda masukkan salah.'];
            }
        }

        $checkSql = "SELECT COUNT(*) as COUNT FROM FORUM_MEMBERS WHERE FORUM_ID = :forum_id AND USER_ID = :user_id";
        $stmt_check = oci_parse($conn, $checkSql);
        oci_bind_by_name($stmt_check, ':forum_id', $forumId);
        oci_bind_by_name($stmt_check, ':user_id', $userId);
        oci_execute($stmt_check);
        $row = oci_fetch_assoc($stmt_check);
        oci_free_statement($stmt_check);

        if ($row['COUNT'] > 0) {
            return ['success' => false, 'message' => 'Anda sudah bergabung dengan forum ini.'];
        }

        $stmt_insert = null;
        try {
            $id = uniqid();
            $sql = "INSERT INTO FORUM_MEMBERS (ID, FORUM_ID, USER_ID, JOINED_AT) 
                VALUES (:id, :forum_id, :user_id, CURRENT_TIMESTAMP)";

            $stmt_insert = oci_parse($conn, $sql);
            oci_bind_by_name($stmt_insert, ':id', $id);
            oci_bind_by_name($stmt_insert, ':forum_id', $forumId);
            oci_bind_by_name($stmt_insert, ':user_id', $userId);

            $result = oci_execute($stmt_insert);

            if ($result) {
                return ['success' => true, 'message' => 'Berhasil bergabung dengan forum.'];
            } else {
                return ['success' => false, 'message' => 'Gagal bergabung dengan forum.'];
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan server.'];
        } finally {
            if ($stmt_insert) oci_free_statement($stmt_insert);
        }
    }

    public static function createReport($data)
    {
        $conn = self::getConnection();
        if (!$conn) {
            return ['success' => false, 'message' => 'Gagal terhubung ke database.'];
        }

        $stmt_insert = null; 
        try {
            $insertData = [
                'id' => uniqid(), 
                'target_id' => $data['forum_id'],
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
}
