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
        $status = "JOINED";

        $sql2 = "INSERT INTO FORUM_MEMBERS (ID, FORUM_ID, USER_ID, STATUS, JOINED_AT)
             VALUES (:id, :forum_id, :user_id, :status ,SYSDATE)";

        $stmt2 = oci_parse($conn, $sql2);

        oci_bind_by_name($stmt2, ":id", $memberId);
        oci_bind_by_name($stmt2, ":forum_id", $forumId);
        oci_bind_by_name($stmt2, ":status", $status);
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

    public function updateForum($id, $data)
    {
        $conn = self::getConnection();

        $sql = "UPDATE FORUMS SET 
                NAME = :name, 
                ABOUT = :about, 
                IS_PRIVATE = :is_private, 
                ACCESS_KEY = :access_key";

        if (array_key_exists('PATH_PHOTO', $data)) {
            $sql .= ", PATH_PHOTO = :photo";
        }

        if (array_key_exists('PATH_THUMBNAIL', $data)) {
            $sql .= ", PATH_THUMBNAIL = :thumb";
        }

        $sql .= " WHERE ID = :id";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":id", $id);
        oci_bind_by_name($stmt, ":name", $data['NAME']);
        oci_bind_by_name($stmt, ":about", $data['ABOUT']);
        oci_bind_by_name($stmt, ":is_private", $data['IS_PRIVATE']);
        oci_bind_by_name($stmt, ":access_key", $data['ACCESS_KEY']);

        if (array_key_exists('PATH_PHOTO', $data)) {
            oci_bind_by_name($stmt, ":photo", $data['PATH_PHOTO']);
        }

        if (array_key_exists('PATH_THUMBNAIL', $data)) {
            oci_bind_by_name($stmt, ":thumb", $data['PATH_THUMBNAIL']);
        }

        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if ($result) {
            oci_commit($conn);
            return true;
        } else {
            return false;
        }
    }

    public function getForumsWithFilter($userId, $filter, $search, $limit, $offset)
    {
        $conn = self::getConnection();
        $bindings = [];

        $sql = "SELECT 
                f.*, 
                u.FULL_NAME AS OWNER_NAME,
                (SELECT COUNT(*) FROM FORUM_MEMBERS fm WHERE fm.FORUM_ID = f.ID AND fm.STATUS = 'JOINED') AS TOTAL_MEMBERS,
                (SELECT COUNT(*) FROM FORUM_MEMBERS fm2 WHERE fm2.FORUM_ID = f.ID AND fm2.USER_ID = :userid_check AND fm2.STATUS = 'JOINED') AS IS_MEMBER
            FROM FORUMS f
            JOIN USERS u ON f.OWNER_ID = u.ID ";

        if ($filter === 'joined') {
            $sql .= " JOIN FORUM_MEMBERS fm_filter ON f.ID = fm_filter.FORUM_ID ";
        }

        if ($filter === 'owned') {
            $sql .= " WHERE f.STATUS IN ('ACTIVE', 'NONACTIVE') ";
        } else {
            $sql .= " WHERE f.STATUS = 'ACTIVE' ";
        }

        if ($filter === 'joined') {
            $sql .= " AND fm_filter.USER_ID = :userid_filter ";
            $sql .= " AND fm_filter.STATUS = 'JOINED' ";
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

        $sql .= " ORDER BY f.CREATED_AT DESC OFFSET :offset_val ROWS FETCH NEXT :limit_val ROWS ONLY";

        $bindings[':offset_val'] = $offset;
        $bindings[':limit_val'] = $limit;
        $bindings[':userid_check'] = $userId;

        $stmt = oci_parse($conn, $sql);
        foreach ($bindings as $key => $val) {
            $tempVal = $val;
            oci_bind_by_name($stmt, $key, $bindings[$key]);
        }
        oci_execute($stmt);

        $forums = [];
        while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
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

        $sql = "SELECT f.*, 
                   (SELECT COUNT(*) FROM FORUM_MEMBERS fm WHERE fm.FORUM_ID = f.ID AND fm.STATUS = 'JOINED') AS TOTAL_MEMBERS,
                   (SELECT COUNT(*) FROM FORUM_MEMBERS fm WHERE fm.FORUM_ID = f.ID AND fm.STATUS = 'PENDING') AS TOTAL_REQUESTS,
                   u.FULL_NAME AS OWNER_NAME,
                   u.PATH_PHOTO AS PATH_PHOTO_OWNER,
                   u.ROLE AS ROLE_OWNER
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

    public function getAllForumAssets($forumId)
    {
        $conn = self::getConnection();

        $sqlForum = "SELECT PATH_THUMBNAIL, PATH_PHOTO FROM FORUMS WHERE ID = :id";
        $stmtF = oci_parse($conn, $sqlForum);
        oci_bind_by_name($stmtF, ":id", $forumId);
        oci_execute($stmtF);
        $forumAssets = oci_fetch_assoc($stmtF);

        $sqlMedia = "SELECT tm.MEDIA_PATH 
                     FROM TOPIC_MEDIA tm
                     JOIN FORUM_TOPICS ft ON tm.TOPIC_ID = ft.ID
                     WHERE ft.FORUM_ID = :fid";

        $stmtM = oci_parse($conn, $sqlMedia);
        oci_bind_by_name($stmtM, ":fid", $forumId);
        oci_execute($stmtM);

        $mediaPaths = [];
        while ($row = oci_fetch_assoc($stmtM)) {
            if (!empty($row['MEDIA_PATH'])) {
                $mediaPaths[] = $row['MEDIA_PATH'];
            }
        }

        return [
            'forum_photo' => $forumAssets['PATH_PHOTO'] ?? null,
            'forum_thumb' => $forumAssets['PATH_THUMBNAIL'] ?? null,
            'topic_media' => $mediaPaths
        ];
    }

    public function deleteForum($id)
    {
        $conn = self::getConnection();

        try {
            $sqlMember = "DELETE FROM FORUM_MEMBERS WHERE FORUM_ID = :id";
            $stmtMember = oci_parse($conn, $sqlMember);
            oci_bind_by_name($stmtMember, ":id", $id);
            $resMember = oci_execute($stmtMember, OCI_NO_AUTO_COMMIT);
            if (!$resMember) throw new Exception("Gagal menghapus member");

            $sqlForum = "DELETE FROM FORUMS WHERE ID = :id";
            $stmtForum = oci_parse($conn, $sqlForum);
            oci_bind_by_name($stmtForum, ":id", $id);
            $resForum = oci_execute($stmtForum, OCI_NO_AUTO_COMMIT);
            if (!$resForum) throw new Exception("Gagal menghapus forum");

            oci_commit($conn);
            return true;
        } catch (Exception $e) {
            oci_rollback($conn);
            return false;
        }
    }

    public function isMember($forumId, $userId)
    {
        $conn = self::getConnection();

        $sql = "SELECT COUNT(*) AS TOTAL 
            FROM FORUM_MEMBERS 
            WHERE FORUM_ID = :forum_id 
            AND USER_ID = :user_id
            AND STATUS = 'JOINED'";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":forum_id", $forumId);
        oci_bind_by_name($stmt, ":user_id", $userId);

        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);

        return ($row['TOTAL'] > 0);
    }

    public function addMember($forumId, $userId)
    {
        $conn = self::getConnection();

        $id = uniqid('member_');
        $status = 'JOINED';

        $sql = "INSERT INTO FORUM_MEMBERS (ID, FORUM_ID, USER_ID, STATUS, JOINED_AT) 
            VALUES (:id, :forum_id, :user_id, :status, SYSDATE)";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":id", $id);
        oci_bind_by_name($stmt, ":forum_id", $forumId);
        oci_bind_by_name($stmt, ":status", $status);
        oci_bind_by_name($stmt, ":user_id", $userId);

        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if ($result) {
            oci_commit($conn);
            return true;
        } else {
            $e = oci_error($stmt);
            error_log("Database Error: " . $e['message']);
            return false;
        }
    }

    public function getForumMembers($forumId)
    {
        $conn = self::getConnection();

        $sql = "SELECT 
                fm.USER_ID, 
                fm.JOINED_AT,
                u.FULL_NAME, 
                u.PATH_PHOTO, 
                u.ROLE 
            FROM FORUM_MEMBERS fm
            JOIN USERS u ON fm.USER_ID = u.ID
            WHERE fm.FORUM_ID = :forum_id AND fm.STATUS = 'JOINED'
            ORDER BY fm.JOINED_AT DESC";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":forum_id", $forumId);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            error_log($e['message']);
            return [];
        }

        $members = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $members[] = $row;
        }

        return $members;
    }

    public function removeMember($forumId, $userId)
    {
        $conn = self::getConnection();

        $sql = "DELETE FROM FORUM_MEMBERS 
                WHERE FORUM_ID = :forum_id 
                AND USER_ID = :user_id";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":forum_id", $forumId);
        oci_bind_by_name($stmt, ":user_id", $userId);

        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if ($result) {
            oci_commit($conn);
            return true;
        } else {
            return false;
        }
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
            $status = "JOINED";
            $sql = "INSERT INTO FORUM_MEMBERS (ID, FORUM_ID, USER_ID, STATUS, JOINED_AT) 
                VALUES (:id, :forum_id, :user_id, :status, CURRENT_TIMESTAMP)";

            $stmt_insert = oci_parse($conn, $sql);
            oci_bind_by_name($stmt_insert, ':id', $id);
            oci_bind_by_name($stmt_insert, ':forum_id', $forumId);
            oci_bind_by_name($stmt_insert, ':status', $status);
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
            FROM FORUMS f
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
                    COUNT(fm.USER_ID) AS TOTAL_MEMBERS,
                    f.PATH_PHOTO,
                    f.ACCESS_KEY,
                    f.CREATED_AT
                FROM FORUMS f
                JOIN USERS u ON u.ID = f.OWNER_ID
                LEFT JOIN FORUM_MEMBERS fm ON fm.FORUM_ID = f.ID
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
            FROM FORUMS f
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
                    COUNT(fm.USER_ID) AS TOTAL_MEMBERS,
                    f.PATH_PHOTO,
                    f.CREATED_AT
                FROM FORUMS f
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

    public function getGalleryMediaByForum($forumId)
    {
        $conn = self::getConnection();

        $sql = "SELECT 
                m.ID AS MEDIA_ID,
                m.MEDIA_PATH,
                m.MEDIA_TYPE,
                m.ORIGINAL_FILENAME, 
                t.ID AS TOPIC_ID,
                DBMS_LOB.SUBSTR(t.CONTENT, 2000, 1) AS TOPIC_CONTENT, 
                TO_CHAR(m.CREATED_AT, 'DD Mon YYYY HH24:MI') AS FORMATTED_DATE,
                u.USERNAME,
                u.PATH_PHOTO, 
                u.FULL_NAME
            FROM TOPIC_MEDIA m
            JOIN FORUM_TOPICS t ON m.TOPIC_ID = t.ID
            JOIN USERS u ON t.USER_ID = u.ID
            WHERE t.FORUM_ID = :forum_id 
            AND m.MEDIA_TYPE IN ('IMAGE', 'FILE') 
            ORDER BY m.CREATED_AT DESC";

        $stid = oci_parse($conn, $sql);

        if (!$stid) {
            $e = oci_error($conn);
            error_log("Oracle Error: " . $e['message']);
            return [];
        }

        oci_bind_by_name($stid, ":forum_id", $forumId);

        oci_execute($stid);

        $results = [];
        oci_fetch_all($stid, $results, 0, -1, OCI_FETCHSTATEMENT_BY_ROW);

        oci_free_statement($stid);

        return $results;
    }

    public function getPendingRequests($forumId)
    {
        $conn = self::getConnection();

        $sql = "SELECT 
                    FM.ID as \"id\",
                    U.FULL_NAME as \"nama\",
                    U.USERNAME as \"username\",
                    U.ROLE as \"role\",
                    U.PATH_PHOTO as \"photo\"
                FROM FORUM_MEMBERS FM
                JOIN USERS U ON FM.USER_ID = U.ID
                WHERE FM.FORUM_ID = :forum_id 
                AND FM.STATUS = 'PENDING'";

        // 1. Prepare Statement
        $stid = oci_parse($conn, $sql);
        if (!$stid) {
            $e = oci_error($conn);
            throw new Exception($e['message']);
        }

        // 2. Bind Parameter
        // Note: oci_bind_by_name butuh variable reference, jadi $forumId aman
        oci_bind_by_name($stid, ':forum_id', $forumId);

        // 3. Execute
        oci_execute($stid);

        // 4. Fetch All
        // OCI_FETCHSTATEMENT_BY_ROW: Agar format arraynya per baris (seperti PDO::FETCH_ASSOC)
        // OCI_ASSOC: Agar index arraynya nama kolom
        $output = [];
        oci_fetch_all($stid, $output, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);

        // Bersihkan statement
        oci_free_statement($stid);

        // Penting: Oracle return nama kolom UPPERCASE by default, 
        // tapi karena kita pake alias di SQL (as "id"), biasanya aman jadi lowercase.
        return $output;
    }

    public function acceptMember($requestId)
    {
        $conn = self::getConnection();

        $sql = "UPDATE FORUM_MEMBERS 
                SET STATUS = 'JOINED', JOINED_AT = SYSDATE 
                WHERE ID = :id";

        $stid = oci_parse($conn, $sql);
        if (!$stid) {
            $e = oci_error($conn);
            throw new Exception($e['message']);
        }

        oci_bind_by_name($stid, ':id', $requestId);

        // OCI_COMMIT_ON_SUCCESS: Auto commit jika tidak error
        $result = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

        oci_free_statement($stid);

        return $result;
    }

    public function rejectMember($requestId)
    {
        $conn = self::getConnection();

        $sql = "DELETE FROM FORUM_MEMBERS WHERE ID = :id";

        $stid = oci_parse($conn, $sql);
        if (!$stid) {
            $e = oci_error($conn);
            throw new Exception($e['message']);
        }

        oci_bind_by_name($stid, ':id', $requestId);

        // OCI_COMMIT_ON_SUCCESS: Auto commit jika tidak error
        $result = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

        oci_free_statement($stid);

        return $result;
    }

    public function checkMemberStatus($forumId, $userId)
    {
        $conn = self::getConnection();

        $sql = "SELECT STATUS FROM FORUM_MEMBERS 
                WHERE FORUM_ID = :forum_id 
                AND USER_ID = :user_id";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":forum_id", $forumId);
        oci_bind_by_name($stmt, ":user_id", $userId);

        oci_execute($stmt);

        $row = oci_fetch_array($stmt, OCI_ASSOC);

        if ($row) {
            return $row['STATUS'];
        }

        return null; 
    }

    public function sendJoinRequest($forumId, $userId)
    {
        $conn = self::getConnection();

        $currentStatus = $this->checkMemberStatus($forumId, $userId);

        if ($currentStatus === 'JOINED') {
            return ['success' => false, 'message' => 'Anda sudah menjadi anggota forum ini.'];
        }

        if ($currentStatus === 'PENDING') {
            return ['success' => false, 'message' => 'Your join request is already pending approval.'];
        }

        $id = uniqid('req_'); 
        $status = 'PENDING'; 

        $sql = "INSERT INTO FORUM_MEMBERS (ID, FORUM_ID, USER_ID, STATUS, JOINED_AT) 
                VALUES (:id, :forum_id, :user_id, :status, SYSDATE)";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":id", $id);
        oci_bind_by_name($stmt, ":forum_id", $forumId);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_bind_by_name($stmt, ":status", $status);

        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if ($result) {
            oci_commit($conn);
            return ['success' => true, 'message' => 'Permintaan berhasil dikirim.'];
        } else {
            $e = oci_error($stmt);
            oci_rollback($conn);
            return ['success' => false, 'message' => 'Database Error: ' . $e['message']];
        }
    }
    public function searchNonMembers($forumId, $search = '')
    {
        $conn = self::getConnection();
        
        $searchParam = '%' . strtolower($search) . '%';
        
        $sql = "SELECT u.ID, u.USERNAME, u.FULL_NAME, u.PATH_PHOTO, u.ROLE
                FROM USERS u
                WHERE u.ID NOT IN (
                    SELECT fm.USER_ID 
                    FROM FORUM_MEMBERS fm 
                    WHERE fm.FORUM_ID = :forum_id
                )
                AND u.ID != (SELECT OWNER_ID FROM FORUMS WHERE ID = :forum_id2)";
        
        if (!empty($search)) {
            $sql .= " AND (LOWER(u.FULL_NAME) LIKE :search OR LOWER(u.USERNAME) LIKE :search2)";
        }
        
        $sql .= " ORDER BY u.FULL_NAME ASC";
        
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':forum_id', $forumId);
        oci_bind_by_name($stmt, ':forum_id2', $forumId);
        
        if (!empty($search)) {
            oci_bind_by_name($stmt, ':search', $searchParam);
            oci_bind_by_name($stmt, ':search2', $searchParam);
        }
        
        oci_execute($stmt);
        
        $users = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $users[] = $row;
        }
        
        oci_free_statement($stmt);
        
        return $users;
    }

    public function addPendingMemberToForum($forumId, $userId)
    {
        $conn = self::getConnection();

        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return false;
        }

        // Generate unique ID untuk member
        $id = uniqid('member_');
        $status = 'PENDING';

        $sql = "INSERT INTO FORUM_MEMBERS (ID, FORUM_ID, USER_ID, STATUS, JOINED_AT) 
                VALUES (:id, :forum_id, :user_id, :status, SYSDATE)";

        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
            $e = oci_error($conn);
            error_log("OCI Parse Error addPendingMemberToForum: " . $e['message']);
            return false;
        }

        // Bind parameters
        oci_bind_by_name($stmt, ":id", $id);
        oci_bind_by_name($stmt, ":forum_id", $forumId);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_bind_by_name($stmt, ":status", $status);

        // Execute dengan NO_AUTO_COMMIT untuk transaction control
        $result = oci_execute($stmt, OCI_NO_AUTO_COMMIT);

        if ($result) {
            oci_commit($conn);
            oci_free_statement($stmt);
            return true;
        } else {
            $e = oci_error($stmt);
            error_log("Database Error addPendingMemberToForum: " . $e['message']);
            oci_rollback($conn);
            oci_free_statement($stmt);
            return false;
        }
    }
}
