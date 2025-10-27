<?php
require_once __DIR__ . '/../BaseModel.php';

class UserModel extends BaseModel
{
    public function getUserById($userId)
    {
        $conn = self::getConnection();

        $sql = "SELECT * FROM USERS WHERE ID = :id";
        $stmt = oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ':id', $userId);
        oci_execute($stmt);

        return oci_fetch_assoc($stmt);
    }
}
