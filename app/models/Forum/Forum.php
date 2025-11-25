<?php

require_once "app/models/BaseModel.php";

class ForumModel extends BaseModel
{
        public function allForums()
    {
        $conn = self::getConnection();

        if (!$conn) {
            error_log("Gagal terhubung ke database.");
            return [];
        }

        $sql = "SELECT ID, NAME, IS_PRIVATE, OWNER_ID FROM FORUMS ORDER BY NAME ASC";

        $stmt = oci_parse($conn, $sql);

        if (!oci_execute($stmt)) {
            return [];
        }

        $forums = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $forums[] = $row;
        }

        oci_free_statement($stmt);

        return $forums;
    }

    public function createForum($data)
    {
        $conn = self::getConnection();

        $forumId = uniqid();
        $ownerId = $_SESSION['user_id'];

        $sql = "INSERT INTO FORUMS (
                ID, NAME, PATH_THUMBNAIL, PATH_PHOTO, ABOUT, IS_PRIVATE,
                ACCESS_KEY, OWNER_ID, STATUS, CREATED_AT
            ) VALUES (
                :id, :name, :path_thumbnail, :path_photo, :about,
                :is_private, :access_key, :owner_id, 'NONACTIVE', SYSDATE
            )";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":id", $forumId);
        oci_bind_by_name($stmt, ":name", $data['NAME']);
        oci_bind_by_name($stmt, ":path_thumbnail", $data['PATH_THUMBNAIL']);
        oci_bind_by_name($stmt, ":path_photo", $data['PATH_PHOTO']);
        oci_bind_by_name($stmt, ":about", $data['ABOUT']);
        oci_bind_by_name($stmt, ":is_private", $data['IS_PRIVATE']);
        oci_bind_by_name($stmt, ":access_key", $data['ACCESS_KEY']);
        oci_bind_by_name($stmt, ":owner_id", $ownerId);

        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if (!$result) {
            $e = oci_error($stmt);
            oci_rollback($conn);
            oci_free_statement($stmt);

            return [
                'success' => false,
                'error' => $e['message']
            ];
        }

        oci_free_statement($stmt);

        $memberId = uniqid();

        $sql2 = "INSERT INTO FORUM_MEMBERS (ID, FORUM_ID, USER_ID, JOINED_AT)
             VALUES (:id, :forum_id, :user_id, SYSDATE)";

        $stmt2 = oci_parse($conn, $sql2);

        oci_bind_by_name($stmt2, ":id", $memberId);
        oci_bind_by_name($stmt2, ":forum_id", $forumId);
        oci_bind_by_name($stmt2, ":user_id", $ownerId);

        $result2 = oci_execute($stmt2, OCI_NO_AUTO_COMMIT);

        if (!$result2) {
            $e = oci_error($stmt2);
            oci_rollback($conn);
            oci_free_statement($stmt2);

            return [
                'success' => false,
                'error' => $e['message']
            ];
        }

        oci_commit($conn);
        oci_free_statement($stmt2);

        return [
            'success' => true,
            'ID' => $forumId
        ];
    }

    public function getForumsWithFilter($userId, $filter, $search, $limit, $offset)
    {
        $conn = self::getConnection();
        $bindings = []; 

        $sql = "SELECT 
                f.*, 
                u.FULL_NAME AS OWNER_NAME,
                (SELECT COUNT(*) FROM FORUM_MEMBERS fm WHERE fm.FORUM_ID = f.ID) AS TOTAL_MEMBERS,
                (SELECT COUNT(*) FROM FORUM_MEMBERS fm2 WHERE fm2.FORUM_ID = f.ID AND fm2.USER_ID = :userid_check) AS IS_MEMBER
            FROM FORUMS f
            JOIN USERS u ON f.OWNER_ID = u.ID ";

        if ($filter === 'joined') {
            $sql .= " JOIN FORUM_MEMBERS fm_filter ON f.ID = fm_filter.FORUM_ID ";
        }

        $sql .= " WHERE f.STATUS = 'ACTIVE' ";

        if ($filter === 'joined') {
            $sql .= " AND fm_filter.USER_ID = :userid_filter ";
            $bindings[':userid_filter'] = $userId;
        } elseif ($filter === 'owned') {
            $sql .= " AND f.OWNER_ID = :owner_id ";
            $bindings[':owner_id'] = $userId;
        }

        if (!empty($search)) {
            $sql .= " AND (LOWER(f.NAME) LIKE '%' || LOWER(:search_query) || '%' OR LOWER(f.ABOUT) LIKE '%' || LOWER(:search_desc) || '%') ";
            $bindings[':search_query'] = $search;
            $bindings[':search_desc'] = $search;
        }

        $sql .= " ORDER BY f.CREATED_AT DESC ";

        $sql .= " OFFSET :offset_val ROWS FETCH NEXT :limit_val ROWS ONLY";
        $bindings[':offset_val'] = $offset;
        $bindings[':limit_val'] = $limit;

        $bindings[':userid_check'] = $userId;

        $stmt = oci_parse($conn, $sql);

        foreach ($bindings as $key => $val) {
            oci_bind_by_name($stmt, $key, $bindings[$key]);
        }

        oci_execute($stmt);

        $forums = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $forums[] = $row;
        }
        return $forums;
    }

    public function countForumsWithFilter($userId, $filter, $search)
    {
        $conn = self::getConnection();
        $bindings = [];

        $sql = "SELECT COUNT(*) AS TOTAL FROM FORUMS f ";

        if ($filter === 'joined') {
            $sql .= " JOIN FORUM_MEMBERS fm_filter ON f.ID = fm_filter.FORUM_ID ";
        }

        $sql .= " WHERE f.STATUS = 'ACTIVE' ";

        if ($filter === 'joined') {
            $sql .= " AND fm_filter.USER_ID = :userid_filter ";
            $bindings[':userid_filter'] = $userId;
        } elseif ($filter === 'owned') {
            $sql .= " AND f.OWNER_ID = :owner_id ";
            $bindings[':owner_id'] = $userId;
        }

        if (!empty($search)) {
            $sql .= " AND (LOWER(f.NAME) LIKE '%' || LOWER(:search_query) || '%' OR LOWER(f.ABOUT) LIKE '%' || LOWER(:search_desc) || '%') ";
            $bindings[':search_query'] = $search;
            $bindings[':search_desc'] = $search;
        }

        $stmt = oci_parse($conn, $sql);
        foreach ($bindings as $key => $val) {
            oci_bind_by_name($stmt, $key, $bindings[$key]);
        }

        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        return $row['TOTAL'];
    }


    public function getForumById($forumId)
    {
        $conn = self::getConnection();

        $sql = "SELECT * FROM FORUMS WHERE ID = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":id", $forumId);

        oci_execute($stmt);

        $forum = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return $forum ?: null;
    }

    public function getForumJoinOwnerById($forumId)
    {
        $conn = self::getConnection();

        $sql = "SELECT f.*, u.USERNAME AS OWNER_USERNAME, u.FULL_NAME AS OWNER_FULL_NAME
                FROM FORUMS f
                JOIN USERS u ON f.OWNER_ID = u.ID
                WHERE f.ID = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":id", $forumId);

        oci_execute($stmt);

        $forum = oci_fetch_assoc($stmt);
        oci_free_statement($stmt);

        return $forum ?: null;
    }

    public function joinForum($forumId, $userId, $accessKey = null)
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
                return ['success' => false, 'message' => 'Kunci akses diperlukan untuk Forum ini.'];
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
            return ['success' => false, 'message' => 'Pengguna ini sudah tercatat sebagai anggota Forum Chat.'];
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
                return ['success' => true, 'message' => 'Berhasil bergabung dengan Forum.'];
            } else {
                return ['success' => false, 'message' => 'Gagal bergabung dengan Forum.'];
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan server.'];
        } finally {
            if ($stmt_insert) oci_free_statement($stmt_insert);
        }
    }

    public function getMyForum($search = '', $limit = 6, $offset = 0)
    {
        $conn = self::getConnection();
        if (!$conn) return ['data' => [], 'total' => 0];

        $currentUser = $_SESSION['user_id'];
        $searchParam = '%' . strtolower($search) . '%';

        $sqlCount = "
            SELECT COUNT(f.ID) AS TOTAL_ROWS
            FROM FORUM f
            WHERE f.OWNER_ID = :currentUser
        ";
        if (!empty($search)) {
            $sqlCount .= " AND LOWER(f.NAME) LIKE :search";
        }

        $stmtCount = oci_parse($conn, $sqlCount);
        oci_bind_by_name($stmtCount, ":currentUser", $currentUser);
        if (!empty($search)) {
            oci_bind_by_name($stmtCount, ":search", $searchParam);
        }
        oci_execute($stmtCount);
        $totalRows = oci_fetch_assoc($stmtCount)['TOTAL_ROWS'] ?? 0;
        oci_free_statement($stmtCount);

        $sqlData = "
            SELECT * FROM (
                SELECT 
                    f.ID,
                    f.NAME,
                    f.IS_PRIVATE,
                    u.FULL_NAME AS OWNER_NAME,
                    COUNT(m.USER_ID) AS TOTAL_MEMBERS,
                    f.PATH_PHOTO,
                    f.ACCESS_KEY,
                    f.CREATED_AT
                FROM FORUM f
                JOIN USERS u ON u.ID = f.OWNER_ID
                LEFT JOIN FORUM_MEMBERS fm ON fm._FORUM_ID = f.ID
                WHERE f.OWNER_ID = :currentUser
            ";

        if (!empty($search)) {
            $sqlData .= " AND LOWER(f.NAME) LIKE :search";
        }

        $sqlData .= "
                GROUP BY 
                    f.ID, f.NAME, f.IS_PRIVATE, u.FULL_NAME, f.CREATED_AT, f.ACCESS_KEY, f.PATH_PHOTO
                ORDER BY f.CREATED_AT DESC
            )
            OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY
        ";

        $stmtData = oci_parse($conn, $sqlData);
        oci_bind_by_name($stmtData, ":currentUser", $currentUser);
        oci_bind_by_name($stmtData, ":offset", $offset);
        oci_bind_by_name($stmtData, ":limit", $limit);
        if (!empty($search)) {
            oci_bind_by_name($stmtData, ":search", $searchParam);
        }

        if (!oci_execute($stmtData)) return ['data' => [], 'total' => 0];

        $forums = [];
        while ($row = oci_fetch_assoc($stmtData)) {
            $forums[] = $row;
        }
        oci_free_statement($stmtData);

        return ['data' => $forums, 'total' => $totalRows];
    }

    public function getAllForumsPagination($search = '', $limit = 6, $offset = 0)
    {
        $conn = self::getConnection();
        if (!$conn) return ['data' => [], 'total' => 0];

        $currentUser = $_SESSION['user_id'];
        $searchParam = '%' . strtolower($search) . '%';

        $sqlCount = "
            SELECT COUNT(f.ID) AS TOTAL_ROWS
            FROM FORUM f
            WHERE f.OWNER_ID != :currentUser
        ";
        if (!empty($search)) {
            $sqlCount .= " AND LOWER(f.NAME) LIKE :search";
        }

        $stmtCount = oci_parse($conn, $sqlCount);
        oci_bind_by_name($stmtCount, ":currentUser", $currentUser);
        if (!empty($search)) {
            oci_bind_by_name($stmtCount, ":search", $searchParam);
        }
        oci_execute($stmtCount);
        $totalRows = oci_fetch_assoc($stmtCount)['TOTAL_ROWS'] ?? 0;
        oci_free_statement($stmtCount);

        $sqlData = "
            SELECT * FROM (
                SELECT 
                    f.ID,
                    f.NAME,
                    f.IS_PRIVATE,
                    u.FULL_NAME AS OWNER_NAME,
                    COUNT(m.USER_ID) AS TOTAL_MEMBERS,
                    f.PATH_PHOTO,
                    f.CREATED_AT
                FROM FORUM f
                JOIN USERS u ON u.ID = f.OWNER_ID
                LEFT JOIN FORUM_MEMBERS fm ON fm.FORUM_ID = f.ID
                WHERE f.OWNER_ID != :currentUser
        ";

        if (!empty($search)) {
            $sqlData .= " AND LOWER(f.NAME) LIKE :search";
        }

        $sqlData .= "
                GROUP BY 
                    f.ID, f.NAME, f.IS_PRIVATE, u.FULL_NAME, f.CREATED_AT, f.PATH_PHOTO
                ORDER BY f.CREATED_AT DESC
            )
            OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY
        ";

        $stmtData = oci_parse($conn, $sqlData);
        oci_bind_by_name($stmtData, ":currentUser", $currentUser);
        oci_bind_by_name($stmtData, ":offset", $offset);
        oci_bind_by_name($stmtData, ":limit", $limit);

        if (!empty($search)) {
            oci_bind_by_name($stmtData, ":search", $searchParam);
        }

        if (!oci_execute($stmtData)) return ['data' => [], 'total' => 0];

        $forums = [];
        while ($row = oci_fetch_assoc($stmtData)) {
            $forums[] = $row;
        }
        oci_free_statement($stmtData);

        return ['data' => $forums, 'total' => $totalRows];
    }
}
