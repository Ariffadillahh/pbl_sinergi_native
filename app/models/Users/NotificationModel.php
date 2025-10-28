<?php
require_once __DIR__ . '/../BaseModel.php';

class NotificationModel extends BaseModel
{
    /**
     * Tambah notifikasi baru
     * @param string $userId → pemilik notifikasi (yang akan menerima)
     * @param string $type → jenis notifikasi ('LIKE_POST', 'REPLY_POST', dll)
     * @param array $data → payload JSON (misal: ['from_user' => ..., 'post_id' => ...])
     */

    public function addNotification($userId, $type, $data)
    {
        $conn = self::getConnection();
        $notifId = uniqid('notif_');
        $jsonData = json_encode($data);

        $sql = "INSERT INTO NOTIFICATIONS (ID, USER_ID, TYPE, DATA, IS_READ, CREATED_AT)
                VALUES (:id, :user_id, :type, :data, 0, CURRENT_TIMESTAMP)";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":id", $notifId);
        oci_bind_by_name($stmt, ":user_id", $userId);
        oci_bind_by_name($stmt, ":type", $type);
        oci_bind_by_name($stmt, ":data", $jsonData);

        if (!oci_execute($stmt)) {
            $err = oci_error($stmt);
            error_log("Gagal menambah notifikasi: " . $err['message']);
        }
    }

    /**
     * Ambil semua notifikasi milik user tertentu
     */
    public function getUserNotifications($userId)
    {
        $conn = self::getConnection();
        $sql = "SELECT ID, TYPE, DATA, IS_READ, TO_CHAR(CREATED_AT, 'YYYY-MM-DD HH24:MI:SS') AS CREATED_AT
                FROM NOTIFICATIONS
                WHERE USER_ID = :user_id
                ORDER BY CREATED_AT DESC";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':user_id', $userId);
        oci_execute($stmt);

        $result = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $row['DATA'] = json_decode($row['DATA'], true);
            $result[] = $row;
        }

        return $result;
    }

    /**
     * Tandai notifikasi sebagai dibaca
     */
    public function markAsRead($notifId)
    {
        $conn = self::getConnection();
        $sql = "UPDATE NOTIFICATIONS SET IS_READ = 1 WHERE ID = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $notifId);
        oci_execute($stmt);
    }
}
