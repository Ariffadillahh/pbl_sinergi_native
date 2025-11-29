<?php

require_once "app/models/BaseModel.php";

class ReportManage extends BaseModel
{
    public function getReportForum()
    {
        $conn = self::getConnection();

        $sql = "
            SELECT 
                F.ID AS FORUM_ID,
                F.NAME AS FORUM_NAME,
                F.PATH_PHOTO AS FORUM_PHOTO,
                F.OWNER_ID AS FORUM_OWNER_ID,
                F.OWNER_ID,
                OWNER.FULL_NAME AS FORUM_OWNER_NAME,
                LISTAGG(R.ID, ', ') WITHIN GROUP (ORDER BY R.ID) AS REPORT_IDS,
                COUNT(R.ID) AS TOTAL_REPORTS
            FROM REPORT R
            JOIN FORUMS F ON R.TARGET_ID = F.ID
            JOIN USERS OWNER ON F.OWNER_ID = OWNER.ID
            WHERE R.TARGET_TYPE = 'FORUM'
            GROUP BY 
                F.ID, F.NAME, F.PATH_PHOTO, F.OWNER_ID, OWNER.FULL_NAME
            ORDER BY TOTAL_REPORTS DESC
        ";


        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            throw new Exception('Oracle parse error: ' . htmlentities($e['message']));
        }

        $executed = oci_execute($stmt);
        if (!$executed) {
            $e = oci_error($stmt);
            throw new Exception('Oracle execute error: ' . htmlentities($e['message']));
        }

        $reports = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $reports[] = $row;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        return $reports;
    }

    public function getReportPost()
    {
        $conn = self::getConnection();

        $sql = "
            SELECT 
                P.ID AS POST_ID,
                P.USER_ID,
                U.FULL_NAME AS POST_OWNER_NAME,
                COUNT(R.ID) AS TOTAL_REPORTS,
                LISTAGG(R.ID, ', ') WITHIN GROUP (ORDER BY R.ID) AS REPORT_IDS
            FROM REPORT R
            JOIN POSTS P ON R.TARGET_ID = P.ID
            JOIN USERS U ON P.USER_ID = U.ID
            WHERE R.TARGET_TYPE = 'POSTINGAN'
            GROUP BY P.ID, P.USER_ID, U.FULL_NAME
            ORDER BY TOTAL_REPORTS DESC
        ";


        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            throw new Exception('Oracle parse error: ' . htmlentities($e['message']));
        }

        $executed = oci_execute($stmt);
        if (!$executed) {
            $e = oci_error($stmt);
            throw new Exception('Oracle execute error: ' . htmlentities($e['message']));
        }

        $reports = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $reports[] = $row;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        return $reports;
    }

    public function getReportGroup()
    {
        $conn = self::getConnection();

        $sql = "
            SELECT 
                GC.ID AS GROUP_ID,
                GC.NAME AS GROUP_NAME,
                GC.OWNER_ID,
                U.FULL_NAME AS OWNER_NAME,
                COUNT(R.ID) AS TOTAL_REPORTS,
                LISTAGG(R.ID, ', ') WITHIN GROUP (ORDER BY R.ID) AS REPORT_IDS
            FROM REPORT R
            JOIN GROUP_CHATS GC ON R.TARGET_ID = GC.ID
            JOIN USERS U ON GC.OWNER_ID = U.ID
            WHERE R.TARGET_TYPE = 'GROUP'
            GROUP BY GC.ID, GC.NAME, GC.OWNER_ID, U.FULL_NAME
            ORDER BY TOTAL_REPORTS DESC
        ";


        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            throw new Exception('Oracle parse error: ' . htmlentities($e['message']));
        }

        $executed = oci_execute($stmt);
        if (!$executed) {
            $e = oci_error($stmt);
            throw new Exception('Oracle execute error: ' . htmlentities($e['message']));
        }

        $reports = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $reports[] = $row;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        return $reports;
    }

    public function getReasonsByForumId($forumId)
    {
        $conn = self::getConnection();

        $sql = "
            SELECT 
                R.REASON,
                U.FULL_NAME AS REPORTER_NAME,
                U.PATH_PHOTO
            FROM REPORT R
            JOIN USERS U ON R.USER_ID = U.ID
            WHERE R.TARGET_TYPE = 'FORUM' AND R.TARGET_ID = :forumId
            ORDER BY R.CREATED_AT DESC
        ";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':forumId', $forumId);
        oci_execute($stmt);

        $reasons = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $reasons[] = $row;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        return $reasons;
    }

    public function getReasonsByGroupId($groupId)
    {
        $conn = self::getConnection();

        $sql = "
            SELECT 
                R.REASON,
                U.FULL_NAME AS REPORTER_NAME,
                U.PATH_PHOTO
            FROM REPORT R
            JOIN USERS U ON R.USER_ID = U.ID
            WHERE R.TARGET_TYPE = 'GROUP'
            AND R.TARGET_ID = :groupId
            ORDER BY R.CREATED_AT DESC
        ";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':groupId', $groupId);
        oci_execute($stmt);

        $reasons = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $reasons[] = $row;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        return $reasons;
    }


    public function getReasonsByPostId($postId)
    {
        $conn = self::getConnection();

        $sql = "
            SELECT 
                R.REASON,
                U.FULL_NAME AS REPORTER_NAME,
                U.PATH_PHOTO
            FROM REPORT R
            JOIN USERS U ON R.USER_ID = U.ID
            WHERE R.TARGET_TYPE = 'POSTINGAN' AND R.TARGET_ID = :postId
            ORDER BY R.CREATED_AT DESC
        ";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':postId', $postId);
        oci_execute($stmt);

        $reasons = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $reasons[] = $row;
        }

        oci_free_statement($stmt);
        oci_close($conn);

        return $reasons;
    }

    public function deleteReports(array $ids)
    {
        if (empty($ids)) {
            return true;
        }

        $conn = self::getConnection();
        if (!$conn) {
            error_log("ReportModel tidak bisa mendapatkan koneksi OCI8.");
            return false;
        }

        $num_ids = count($ids);
        $placeholders = [];

        for ($i = 0; $i < $num_ids; $i++) {
            $placeholders[] = ':id' . $i;
        }
        $placeholder_string = implode(',', $placeholders);

        $sql = "DELETE FROM REPORT WHERE ID IN ($placeholder_string)";

        try {
            $stmt = oci_parse($conn, $sql);
            if (!$stmt) {
                $e = oci_error($conn);
                error_log("OCI8 Parse Error: " . $e['message'] . " (SQL: $sql)");
                return false;
            }

            for ($i = 0; $i < $num_ids; $i++) {
                $placeholder_name = ':id' . $i;

                oci_bind_by_name($stmt, $placeholder_name, $ids[$i], -1, SQLT_CHR);
            }

            $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

            if (!$result) {
                $e = oci_error($stmt);
                error_log("OCI8 Execute Error: " . $e['message']);
                oci_free_statement($stmt);
                return false;
            }

            oci_free_statement($stmt);
            return true;
        } catch (\Exception $e) {
            error_log("OCI8 Exception: " . $e->getMessage());
            if (isset($stmt) && $stmt) {
                oci_free_statement($stmt);
            }
            return false;
        }
    }

    public function getMediaByPostId($postId)
    {
        $conn = self::getConnection();
        $sql = "SELECT MEDIA_PATH FROM POST_MEDIA WHERE POST_ID = :post_id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':post_id', $postId);
        oci_execute($stmt);

        $media = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $media[] = $row;
        }
        return $media;
    }

    public function deletePostByAdmin($postId)
    {
        $conn = self::getConnection();
        $allQueriesSuccess = true;

        $deleteReportSql = "DELETE FROM REPORT WHERE TARGET_ID = :post_id AND TARGET_TYPE = 'POSTINGAN'";
        $stmtReport = oci_parse($conn, $deleteReportSql);
        oci_bind_by_name($stmtReport, ":post_id", $postId);
        if (!oci_execute($stmtReport)) {
            $allQueriesSuccess = false;
            error_log("Gagal delete REPORT: " . oci_error($stmtReport)['message']);
        }

        $deleteReplySql = "
        DELETE FROM REPLY_COMMENTAR 
        WHERE COMMENTAR_ID IN ( 
            SELECT ID FROM COMMENTAR WHERE POST_ID = :post_id
        )";
        $stmtReply = oci_parse($conn, $deleteReplySql);
        oci_bind_by_name($stmtReply, ":post_id", $postId);
        if (!oci_execute($stmtReply)) {
            $allQueriesSuccess = false;
            error_log("Gagal delete REPLY_COMMENTAR: " . oci_error($stmtReply)['message']);
        }

        $deleteCommentSql = "DELETE FROM COMMENTAR WHERE POST_ID = :post_id";
        $stmtComment = oci_parse($conn, $deleteCommentSql);
        oci_bind_by_name($stmtComment, ":post_id", $postId);
        if (!oci_execute($stmtComment)) {
            $allQueriesSuccess = false;
            error_log("Gagal delete COMMENTAR: " . oci_error($stmtComment)['message']);
        }

        $deletePostSql = "DELETE FROM POSTS WHERE ID = :id";
        $stmtDelPost = oci_parse($conn, $deletePostSql);
        oci_bind_by_name($stmtDelPost, ":id", $postId);
        if (!oci_execute($stmtDelPost)) {
            $allQueriesSuccess = false;
            $e = oci_error($stmtDelPost);
            error_log("Gagal delete POSTS: " . $e['message']);
            return false;
        }

        return $allQueriesSuccess;
    }
}
