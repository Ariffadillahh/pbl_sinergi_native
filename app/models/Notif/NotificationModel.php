<?php
require_once __DIR__ . '/../BaseModel.php';

class NotificationModel extends BaseModel
{

    public function getNewNotifications($userId, $lastTimestamp)
    {
        $conn = self::getConnection();

        $sql = "SELECT ID, USER_ID, TYPE, DATA, IS_READ, 
                   TO_CHAR(CREATED_AT, 'YYYY-MM-DD\"T\"HH24:MI:SS.FF3\"Z\"') AS CREATED_AT
            FROM NOTIFICATIONS
            WHERE USER_ID = :user_id 
              AND FROM_TZ(CAST(CREATED_AT AS TIMESTAMP), DBTIMEZONE) > TO_TIMESTAMP_TZ(:last_timestamp, 'YYYY-MM-DD\"T\"HH24:MI:SS.FFTZH:TZM')
            ORDER BY CREATED_AT ASC";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':last_timestamp', $lastTimestamp);
        oci_execute($stmt);

        $notifications = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if (isset($row['DATA'])) {
                if (is_object($row['DATA'])) {
                    $row['DATA'] = json_decode($row['DATA']->load(), true);
                } else {
                    $row['DATA'] = json_decode($row['DATA'], true);
                }
            }
            $notifications[] = $row;
        }

        oci_free_statement($stmt);
        return $notifications;
    }

    public function getRecentNotifications($userId, $limit = 20)
    {
        $conn = self::getConnection();

        $sql = "SELECT ID, USER_ID, TYPE, DATA, IS_READ, 
                       TO_CHAR(CREATED_AT, 'YYYY-MM-DD\"T\"HH24:MI:SS.FF3\"Z\"') AS CREATED_AT
                FROM NOTIFICATIONS
                WHERE USER_ID = :user_id 
                ORDER BY CREATED_AT DESC
                FETCH FIRST :limit ROWS ONLY";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':limit', $limit);
        oci_execute($stmt);

        $notifications = [];
        while ($row = oci_fetch_assoc($stmt)) {
            if (isset($row['DATA'])) {
                if (is_object($row['DATA'])) {
                    $row['DATA'] = json_decode($row['DATA']->load(), true);
                } else {
                    $row['DATA'] = json_decode($row['DATA'], true);
                }
            }
            $notifications[] = $row;
        }

        oci_free_statement($stmt);
        return $notifications;
    }

    public function getUnreadCount($userId)
    {
        $conn = self::getConnection();

        $sql = "SELECT COUNT(*) AS UNREAD_COUNT
                FROM NOTIFICATIONS
                WHERE USER_ID = :user_id AND IS_READ = 0";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);
        $count = $row['UNREAD_COUNT'] ?? 0;

        oci_free_statement($stmt);
        return (int)$count;
    }

    public function markAllAsRead($userId)
    {
        $conn = self::getConnection();

        $sql = "UPDATE NOTIFICATIONS 
                SET IS_READ = 1 
                WHERE USER_ID = :user_id AND IS_READ = 0";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        oci_free_statement($stmt);
        return $result;
    }

    public function markAsRead($userId, $notifId)
    {
        $conn = self::getConnection();

        $sql = "UPDATE NOTIFICATIONS 
                SET IS_READ = 1 
                WHERE USER_ID = :user_id AND ID = :notif_id";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':notif_id', $notifId);
        $result = oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        oci_free_statement($stmt);
        return $result;
    }
}
