<?php

require_once "app/models/BaseModel.php";

class overviewCount extends BaseModel
{
    public function countAnggota()
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return 0;
        }

        $sql = "SELECT COUNT(*) AS JUMLAH_USER FROM USERS";
        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return 0;
        }

        $result = oci_execute($stmt);
        if (!$result) {
            $e = oci_error($stmt);
            error_log("Gagal mengeksekusi query: " . $e['message']);
            oci_free_statement($stmt);
            return 0;
        }

        $row = oci_fetch_assoc($stmt);
        $count = $row ? $row['JUMLAH_USER'] : 0;

        oci_free_statement($stmt);
        return $count;
    }

    public function countPost()
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return 0;
        }

        $sql = "SELECT COUNT(*) AS JUMLAH_POST FROM POSTS";
        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return 0;
        }

        $result = oci_execute($stmt);
        if (!$result) {
            $e = oci_error($stmt);
            error_log("Gagal mengeksekusi query: " . $e['message']);
            oci_free_statement($stmt);
            return 0;
        }

        $row = oci_fetch_assoc($stmt);
        $count = $row ? $row['JUMLAH_POST'] : 0;

        oci_free_statement($stmt);
        return $count;
    }

    public function countForum()
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return 0;
        }

        $sql = "SELECT COUNT(*) AS JUMLAH_FORUM FROM FORUMS";
        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return 0;
        }

        $result = oci_execute($stmt);
        if (!$result) {
            $e = oci_error($stmt);
            error_log("Gagal mengeksekusi query: " . $e['message']);
            oci_free_statement($stmt);
            return 0;
        }

        $row = oci_fetch_assoc($stmt);
        $count = $row ? $row['JUMLAH_FORUM'] : 0;

        oci_free_statement($stmt);
        return $count;
    }

    public function countLaporan()
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return 0;
        }

        $sql = "SELECT COUNT(*) AS JUMLAH_LAPORAN FROM REPORT";
        $stmt = oci_parse($conn, $sql);

        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return 0;
        }

        $result = oci_execute($stmt);
        if (!$result) {
            $e = oci_error($stmt);
            error_log("Gagal mengeksekusi query: " . $e['message']);
            oci_free_statement($stmt);
            return 0;
        }

        $row = oci_fetch_assoc($stmt);
        $count = $row ? $row['JUMLAH_LAPORAN'] : 0;

        oci_free_statement($stmt);
        return $count;
    }

    private function fetchSingleValue($sql)
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return 0;
        }

        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return 0;
        }

        $result = oci_execute($stmt);
        if (!$result) {
            $e = oci_error($stmt);
            error_log("Gagal mengeksekusi query: " . $e['message']);
            oci_free_statement($stmt);
            return 0;
        }

        $row = oci_fetch_array($stmt, OCI_NUM);
        $count = $row ? (int)$row[0] : 0;

        oci_free_statement($stmt);
        return $count;
    }

    private function executeQuery($sql)
    {
        $conn = self::getConnection();
        if (!$conn) {
            error_log("Gagal mendapatkan koneksi database.");
            return [];
        }

        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $e = oci_error($conn);
            error_log("Gagal mem-parsing SQL: " . $e['message']);
            return [];
        }

        $result = oci_execute($stmt);
        if (!$result) {
            $e = oci_error($stmt);
            error_log("Gagal mengeksekusi query: " . $e['message']);
            oci_free_statement($stmt);
            return [];
        }

        $results = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $results[] = $row;
        }

        oci_free_statement($stmt);
        return $results;
    }


    public function getMonthlyForumTrend()
    {
        $sql = "
            SELECT 
                TO_CHAR(CREATED_AT, 'MM') AS FORUM_MONTH, 
                COUNT(*) AS TOTAL_FORUMS
            FROM FORUMS
            WHERE TO_CHAR(CREATED_AT, 'YYYY') = TO_CHAR(SYSDATE, 'YYYY')
            GROUP BY TO_CHAR(CREATED_AT, 'MM')
            ORDER BY FORUM_MONTH ASC
        ";

        return $this->executeQuery($sql);
    }

    public function getForumStats()
    {
        $sql_current = "
            SELECT COUNT(*) 
            FROM FORUMS
            WHERE TO_CHAR(CREATED_AT, 'YYYY-MM') = TO_CHAR(SYSDATE, 'YYYY-MM')
        ";
        $current_count = $this->fetchSingleValue($sql_current);

        $sql_previous = "
            SELECT COUNT(*) 
            FROM FORUMS
            WHERE TO_CHAR(CREATED_AT, 'YYYY-MM') = TO_CHAR(ADD_MONTHS(SYSDATE, -1), 'YYYY-MM')
        ";
        $previous_count = $this->fetchSingleValue($sql_previous);

        $percentage_change = 0;
        if ($previous_count > 0) {
            $percentage_change = (($current_count - $previous_count) / $previous_count) * 100;
        } elseif ($current_count > 0) {
            $percentage_change = 100;
        }

        return [
            'total_this_month' => $current_count,
            'total_last_month' => $previous_count,
            'percentage_change' => round($percentage_change, 1)
        ];
    }


    public function getMonthlyPostTrend()
    {
        $sql = "
            SELECT 
                TO_CHAR(CREATED_AT, 'MM') AS POST_MONTH, 
                COUNT(*) AS TOTAL_POSTS
            FROM POSTS
            WHERE TO_CHAR(CREATED_AT, 'YYYY') = TO_CHAR(SYSDATE, 'YYYY')
            GROUP BY TO_CHAR(CREATED_AT, 'MM')
            ORDER BY POST_MONTH ASC
        ";

        return $this->executeQuery($sql);
    }

    public function getPostStats()
    {
        $sql_current = "
            SELECT COUNT(*) 
            FROM POSTS
            WHERE TO_CHAR(CREATED_AT, 'YYYY-MM') = TO_CHAR(SYSDATE, 'YYYY-MM')
        ";
        $current_count = $this->fetchSingleValue($sql_current);

        // Total posts bulan lalu
        $sql_previous = "
            SELECT COUNT(*) 
            FROM POSTS
            WHERE TO_CHAR(CREATED_AT, 'YYYY-MM') = TO_CHAR(ADD_MONTHS(SYSDATE, -1), 'YYYY-MM')
        ";
        $previous_count = $this->fetchSingleValue($sql_previous);

        // Hitung persentase perubahan
        $percentage_change = 0;
        if ($previous_count > 0) {
            $percentage_change = (($current_count - $previous_count) / $previous_count) * 100;
        } elseif ($current_count > 0) {
            // Jika bulan lalu 0 tapi bulan ini ada, maka 100% increase
            $percentage_change = 100;
        }

        return [
            'total_this_month' => $current_count,
            'total_last_month' => $previous_count,
            'percentage_change' => round($percentage_change, 1)
        ];
    }


    public function getMonthlyActivityTrend()
    {
        $sql = "
            WITH all_forum_activities AS (
                SELECT CREATED_AT FROM FORUMS
                
                UNION ALL
                
                SELECT JOINED_AT AS CREATED_AT FROM FORUM_MEMBERS
                
                UNION ALL
                
                SELECT CREATED_AT FROM FORUM_MESSAGES
            )
            SELECT 
                TO_CHAR(CREATED_AT, 'MM') AS ACTIVITY_MONTH, 
                COUNT(*) AS TOTAL_ACTIVITY
            FROM all_forum_activities
            WHERE TO_CHAR(CREATED_AT, 'YYYY') = TO_CHAR(SYSDATE, 'YYYY')
            GROUP BY TO_CHAR(CREATED_AT, 'MM')
            ORDER BY ACTIVITY_MONTH ASC
        ";

        return $this->executeQuery($sql);
    }

    public function getActivityStats()
    {
        $union_sql = "
            (
                SELECT CREATED_AT FROM FORUMS
                UNION ALL
                SELECT JOINED_AT AS CREATED_AT FROM FORUM_MEMBERS
                UNION ALL
                SELECT CREATED_AT FROM FORUM_MESSAGES
            )
        ";

        $sql_current = "
            SELECT COUNT(*) FROM $union_sql
            WHERE CREATED_AT >= SYSDATE - 30
        ";
        $current_count = $this->fetchSingleValue($sql_current);

        // 2. Total 30 hari SEBELUMNYA
        $sql_previous = "
            SELECT COUNT(*) FROM $union_sql
            WHERE CREATED_AT >= SYSDATE - 60 
            AND CREATED_AT < SYSDATE - 30    
        ";
        $previous_count = $this->fetchSingleValue($sql_previous);

        // 3. Hitung persentase
        $percentage_change = 0;
        if ($previous_count > 0) {
            $percentage_change = (($current_count - $previous_count) / $previous_count) * 100;
        }

        return [
            'total_last_30_days' => $current_count,
            'percentage_change' => round($percentage_change, 1)
        ];
    }
}
