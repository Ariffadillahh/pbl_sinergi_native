<?php
require_once __DIR__ . '/../BaseModel.php';

class NotificationModel extends BaseModel
{

    public function getNewNotifications($userId, $lastTimestamp)
    {
        $conn = self::getConnection();

        $sql = "SELECT ID, USER_ID, TYPE, DATA, IS_READ, 
                   -- DIPERBAIKI: Hapus .FF3 karena DATE tidak memilikinya
                   TO_CHAR(CREATED_AT, 'YYYY-MM-DD\"T\"HH24:MI:SS\"Z\"') AS CREATED_AT
            FROM NOTIFICATIONS
            WHERE USER_ID = :user_id 
              -- DIPERBAIKI: 
              -- 1. Jangan ubah CREATED_AT (biarkan sebagai DATE)
              -- 2. Ubah string input :last_timestamp Anda (yang kompleks) menjadi DATE sederhana untuk perbandingan
              AND CREATED_AT > CAST(TO_TIMESTAMP_TZ(:last_timestamp, 'YYYY-MM-DD\"T\"HH24:MI:SS.FFTZH:TZM') AS DATE)
            ORDER BY CREATED_AT ASC";

        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_bind_by_name($stmt, ':last_timestamp', $lastTimestamp);

        if (!oci_execute($stmt)) {
            error_log("Gagal getNewNotifications: " . oci_error($stmt)['message']);
            return [];
        }

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
                   TO_CHAR(CREATED_AT, 'YYYY-MM-DD\"T\"HH24:MI:SS\"Z\"') AS CREATED_AT
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

    public function addNotification($targetUserId, $senderId, $targetId, $type, $targetTypeNotif)
    {
        $conn = self::getConnection();

        $id = uniqid('notif_');

        $senderName = '';
        if ($_SESSION['role'] == 'ADMIN') {
            $senderName = 'ADMIN';
        } else {
            $senderName = $_SESSION['full_name'] ?? 'Someone';
        }

        $notifData = [
            'sender_name' => $senderName,
            'sender_id' => $senderId,
            'target_id' => $targetId,
            'content_type' => $targetTypeNotif
        ];

        $targetType = $targetTypeNotif ?? 'POST';

        if ($targetType === 'POST') {
            $notifData['link'] = "homepage/reply/$targetId";
        } elseif ($targetType === 'FORUM') {
            $notifData['link'] = "forums/chat/$targetId";
        } elseif ($targetType === 'INVITE_FORUM') {
            $notifData['link'] = "#";
        } else {
            $notifData['link'] = "#";
        }


        $jsonData = json_encode($notifData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $sql = "INSERT INTO NOTIFICATIONS (ID, USER_ID, TYPE, DATA, IS_READ, CREATED_AT)
                VALUES (:id, :user_id, :type, :data, 0, CURRENT_TIMESTAMP)";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $id);
        oci_bind_by_name($stmt, ':user_id', $targetUserId);
        oci_bind_by_name($stmt, ':type', $type);
        oci_bind_by_name($stmt, ':data', $jsonData);
        oci_execute($stmt, OCI_COMMIT_ON_SUCCESS);

        oci_free_statement($stmt);
    }

    public function deleteAllRead($userId)
    {
        $conn = self::getConnection();

        $sql = "DELETE FROM NOTIFICATIONS WHERE USER_ID = :user_id AND IS_READ = 1";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        $result = oci_execute($stmt);
        oci_free_statement($stmt);

        if ($result) {
            oci_free_statement($stmt);
            return true;
        } else {
            $error = oci_error($stmt);
            error_log("Gagal menghapus notifikasi: " . $error['message']);
            oci_free_statement($stmt);
            return false;
        }
    }
}
