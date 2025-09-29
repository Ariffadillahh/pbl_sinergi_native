<?php

require_once "app/models/BaseModel.php";

class CRUD extends BaseModel
{
    public static function getAll()
    {
        $conn = self::getConnection();
        $stmt = $conn->prepare("SELECT nomor as ID, nama as NAME, email as EMAIL FROM anggota");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function create($data)
    {
        $conn = self::getConnection();
        $query = "INSERT INTO USERS (NAME, EMAIL) VALUES (:name, :email)";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':email', $email);
        oci_execute($stid);
        oci_free_statement($stid);
        oci_close($conn);
    }

    public static function update($id, $name, $email)
    {
        $conn = self::getConnection();
        $query = "UPDATE USERS SET NAME = :name, EMAIL = :email WHERE ID = :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':name', $name);
        oci_bind_by_name($stid, ':email', $email);
        oci_execute($stid);
        oci_free_statement($stid);
        oci_close($conn);
    }

    public static function delete($id)
    {
        $conn = self::getConnection();
        $query = "DELETE USERS WHERE ID = :id";
        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        oci_free_statement($stid);
        oci_close($conn);
    }
}
